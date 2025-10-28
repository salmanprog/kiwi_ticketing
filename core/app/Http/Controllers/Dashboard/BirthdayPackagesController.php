<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\Models\Section;
use App\Models\BirthdayPackages;
use App\Models\WebmasterSection;
use App\Models\Media;
use App\Models\BirthdayCabanas;
use Illuminate\Pagination\LengthAwarePaginator;
use Auth;
use File;
use Helper;
use Illuminate\Http\Request;
use Redirect;
use Illuminate\Support\Facades\Http;
use Carbon\Carbon;



class BirthdayPackagesController extends Controller
{
    private $uploadPath = "uploads/sections/";

    public function __construct()
    {
        $this->middleware('auth');

    }

    public function index()
    {
        // General for all pages
        $GeneralWebmasterSections = WebmasterSection::where('status', '=', '1')->orderby('row_no', 'asc')->get();
        $birthday_packages = BirthdayPackages::with(['cabanas','media_slider','media_cover','createdBy','updatedBy'])->get();
         // Paginate
        $perPage = 10;
        $currentPage = LengthAwarePaginator::resolveCurrentPage();
        $collection = collect($birthday_packages);
        $paginated = new LengthAwarePaginator(
            $collection->forPage($currentPage, $perPage),
            $collection->count(),
            $perPage,
            $currentPage,
            ['path' => url()->current(), 'query' => request()->query()]
        );
        return view("dashboard.birthday_packages.list", compact("paginated", "GeneralWebmasterSections"));
    }

    public function getData(Request $request)
    {
        $authCode = Helper::GeneralSiteSettings('auth_code_en');
        $query = BirthdayPackages::with(['cabanas','media_slider','media_cover','createdBy','updatedBy']);
        if ($request->has('search') && $request->search['value'] != '') {
            $search = $request->search['value'];
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%");
            });
        }

        $totalData = $query->count();
        $totalFiltered = $totalData;
        $start = $request->input('start', 0);
        $limit = $request->input('length', 10);
        $draw = $request->input('draw', 1);
        $orderColumn = $request->input('order.0.column');
        $orderDir = $request->input('order.0.dir', 'desc');
        $columns = $request->input('columns');
        if ($columns && isset($columns[$orderColumn])) {
            $orderField = $columns[$orderColumn]['data'];
            $query->orderBy($orderField, $orderDir);
        } else {
            $query->orderBy('id', 'desc');
        }

        $data = $query->offset($start)->limit($limit)->get();

        $result = [];
        foreach ($data as $row) {
            $result[] = [
                'id' => $row->id,
                'check' => '<label class="ui-check m-a-0">
                                <input type="checkbox" name="ids[]" value="' . $row->id . '"><i></i>
                                <input type="hidden" name="row_ids[]" value="' . $row->id . '" class="form-control row_no">
                            </label>',
                'title' => '<a class="dropdown-item" href="' . route('birthdaypackagesEdit', $row->slug) . '">'.$row->title.'</a>',
                'slug' => $row->slug,
                'price' => '$' . number_format($row->price, 2),
                'cabanas' => '<div class="text-center">'.count($row->cabanas).'</div>',
                'status' => '<div class="text-center"><i class="fa ' . ($row->status ? 'fa-check text-success' : 'fa-times text-danger') . ' inline"></i></div>',
                'created_by' => $row->createdBy->name,
                'updated_by' => ($row->updated_by == 0) ? 'N/A' : $row->updatedBy->name,
                'updated_at' => $row->updated_at->format('Y-m-d'),
                'options' => '<div class="dropdown">
                                <button type="button" class="btn btn-sm light dk dropdown-toggle" data-toggle="dropdown">
                                    <i class="material-icons">&#xe5d4;</i> Options
                                </button>
                                <div class="dropdown-menu pull-right">
                                    <a class="dropdown-item" href="' . route('birthdaypackagesEdit', $row->slug) . '">
                                        <i class="material-icons">&#xe3c9;</i> Edit
                                    </a>
                                </div>
                            </div>',
            ];
        }

        return response()->json([
            'draw' => intval($draw),
            'recordsTotal' => $totalData,
            'recordsFiltered' => $totalFiltered,
            'data' => $result,
        ]);
    }

    public function create()
    {
        // General for all pages
        $GeneralWebmasterSections = WebmasterSection::where('status', '=', '1')->orderby('row_no', 'asc')->get();
        // General END

        $baseUrl = Helper::GeneralSiteSettings('external_api_link_en');
        $authCode = Helper::GeneralSiteSettings('auth_code_en');
        $date = Carbon::today()->toDateString();

        try {
            $response = Http::get($baseUrl.'/Pricing/GetAllProductPrice?authcode='.$authCode.'&date='.$date);

            if ($response->successful()) {
            $apiData = $response->json();
            $tickets = $apiData['getAllProductPrice']['data'] ?? [];
            $fillter_arr = [];
            if(count($tickets) > 0){
                for($i=0;$i<count($tickets);$i++){
                    if($tickets[$i]['ticketCategory'] == 'Cabanas'){
                        array_push($fillter_arr,$tickets[$i]);
                    }
                }
            }
            // Paginate
            $perPage = 100;
            $currentPage = LengthAwarePaginator::resolveCurrentPage();
            $collection = collect($fillter_arr);

            $cabanas = new LengthAwarePaginator(
                $collection->forPage($currentPage, $perPage),
                $collection->count(),
                $perPage,
                $currentPage,
                ['path' => url()->current(), 'query' => request()->query()]
            );

            return view("dashboard.birthday_packages.create", compact("cabanas", "GeneralWebmasterSections"));
            } else {
                // Handle failed response
                dd([
                    'status' => $response->status(),
                    'error' => $response->body(),
                ]);
            }

        } catch (\Exception $e) {
            // Handle connection or request exceptions
            dd('Exception: ' . $e->getMessage());
        }
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'title' => 'required',
            'description' => 'required',
            'price' => 'required',
            'map_link' => 'required'
        ]);
        // Upload multiple photos
        $formFileName = "photo";
        $uploadedFileNames = []; // To store all uploaded file names

        if ($request->hasFile($formFileName)) {
            foreach ($request->file($formFileName) as $file) {
                if ($file && $file->isValid()) {
                    $fileFinalName = time() . rand(1111, 9999) . '.' . $file->getClientOriginalExtension();
                    $path = $this->uploadPath;

                    if (!file_exists($path)) {
                        mkdir($path, 0755, true);
                    }

                    $file->move($path, $fileFinalName);

                    // Optional: Resize & Optimize
                    Helper::imageResize($path . $fileFinalName);
                    Helper::imageOptimize($path . $fileFinalName);

                    $uploadedFileNames[] = $fileFinalName; // Store for reference (DB, etc.)
                }
            }
        }

        // Upload banner_image
        $formFileName2 = "banner_image";
        $fileFinalName2 = "";
        if ($request->$formFileName2 != "") {
            $fileFinalName2 = time() . rand(1111, 9999) . '.' . $request->file($formFileName2)->getClientOriginalExtension();
            $path = $this->uploadPath;

            if (!file_exists($path)) {
                mkdir($path, 0755, true);
            }

            $request->file($formFileName2)->move($path, $fileFinalName2);
            Helper::imageResize($path . $fileFinalName2);
            Helper::imageOptimize($path . $fileFinalName2);
        }
        
        $birthdayPackages = new BirthdayPackages;
        $birthdayPackages->title  = $request->title;
        $birthdayPackages->description = $request->description;
        $birthdayPackages->price  = $request->price;
        $birthdayPackages->map_link = $request->map_link;
        $birthdayPackages->created_by = Auth::user()->id;
        $birthdayPackages->status = $request->status;
        $birthdayPackages->save();

        if ($request->has('cabanas') && is_array($request->cabanas)) {
            foreach ($request->cabanas as $ticketSlug) {
                BirthdayCabanas::create([
                    'birthday_package_id' => $birthdayPackages->id,
                    'ticketSlug' => $ticketSlug,
                ]);
            }
        }

        if(count($uploadedFileNames) > 0){
            for($i=0;$i<count($uploadedFileNames);$i++){
                $media = new Media;
                $media->module  = 'birthday_packages';
                $media->module_id = $birthdayPackages->id;
                $media->filename  = $uploadedFileNames[$i];
                $media->original_name = $uploadedFileNames[$i];
                $media->file_url = $this->uploadPath.$uploadedFileNames[$i];
                $media->mime_type = '';
                $media->file_type  = 'image';
                $media->save();
            }
        }
        if ($fileFinalName2 != "") {
            $media = new Media;
            $media->module  = 'birthday_cover_photo';
            $media->module_id = $birthdayPackages->id;
            $media->filename  = $fileFinalName2;
            $media->original_name = $fileFinalName2;
            $media->file_url = $this->uploadPath.$fileFinalName2;
            $media->mime_type = '';
            $media->file_type  = 'image';
            $media->save();
        }

        return redirect()->action('Dashboard\BirthdayPackagesController@index')->with('doneMessage', __('backend.addDone'));
    }

    public function clone($webmasterId, $id)
    {
        
    }

    public function edit($slug)
    {
        
        $baseUrl = Helper::GeneralSiteSettings('external_api_link_en');
        $authCode = Helper::GeneralSiteSettings('auth_code_en');
        $date = Carbon::today()->toDateString();

        try {
            $GeneralWebmasterSections = WebmasterSection::where('status', '=', '1')->orderby('row_no', 'asc')->get();
            $birthday_packages = BirthdayPackages::with(['cabanas','media_slider','media_cover'])->where('slug',$slug)->first();
            $selectedcabanas = $birthday_packages->cabanas->pluck('ticketSlug')->toArray();
            $response = Http::get($baseUrl.'/Pricing/GetAllProductPrice?authcode='.$authCode.'&date='.$date);

            if ($response->successful()) {
            $apiData = $response->json();
            $tickets = $apiData['getAllProductPrice']['data'] ?? [];
            $fillter_arr = [];
            if(count($tickets) > 0){
                for($i=0;$i<count($tickets);$i++){
                    if($tickets[$i]['ticketCategory'] == 'Cabanas'){
                        array_push($fillter_arr,$tickets[$i]);
                    }
                }
            }
            // Paginate
            $perPage = 100;
            $currentPage = LengthAwarePaginator::resolveCurrentPage();
            $collection = collect($fillter_arr);

            $cabanas = new LengthAwarePaginator(
                $collection->forPage($currentPage, $perPage),
                $collection->count(),
                $perPage,
                $currentPage,
                ['path' => url()->current(), 'query' => request()->query()]
            );

            return view("dashboard.birthday_packages.edit", compact("birthday_packages", "selectedcabanas", "cabanas", "GeneralWebmasterSections"));
            } else {
                // Handle failed response
                dd([
                    'status' => $response->status(),
                    'error' => $response->body(),
                ]);
            }

        } catch (\Exception $e) {
            // Handle connection or request exceptions
            dd('Exception: ' . $e->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
       $birthdayPackages = BirthdayPackages::where('slug',$id)->first();
       if (!empty($birthdayPackages)) {
            $this->validate($request, [
                'title' => 'required',
                'description' => 'required',
                'price' => 'required',
                'map_link' => 'required'
            ]);

            if ($request->has('cabanas') && is_array($request->cabanas)) {
                BirthdayCabanas::where('birthday_package_id',$birthdayPackages->id)->delete();
                foreach ($request->cabanas as $ticketSlug) {
                    BirthdayCabanas::create([
                        'birthday_package_id' => $birthdayPackages->id,
                        'ticketSlug' => $ticketSlug,
                    ]);
                }
            }
            if ($request->has('media_delete')) {
                foreach ($request->input('media_delete') as $mediaId => $shouldDelete) {
                    if ($shouldDelete == '1') {
                        $media = Media::find($mediaId);
                        if ($media) {
                            $filePath = public_path('uploads/sections/' . $media->filename);
                            if (file_exists($filePath)) {
                                unlink($filePath);
                            }
                            $media->delete();
                        }
                    }
                }
            }
            $formFileName = "photo";
            $uploadedFileNames = []; // To store all uploaded file names

            if ($request->hasFile($formFileName)) {
                foreach ($request->file($formFileName) as $file) {
                    if ($file && $file->isValid()) {
                        $fileFinalName = time() . rand(1111, 9999) . '.' . $file->getClientOriginalExtension();
                        $path = $this->uploadPath;

                        if (!file_exists($path)) {
                            mkdir($path, 0755, true);
                        }

                        $file->move($path, $fileFinalName);

                        // Optional: Resize & Optimize
                        Helper::imageResize($path . $fileFinalName);
                        Helper::imageOptimize($path . $fileFinalName);

                        $uploadedFileNames[] = $fileFinalName; // Store for reference (DB, etc.)
                    }
                }
            }

            // Upload banner_image
            $formFileName2 = "banner_image";
            $fileFinalName2 = "";
            if ($request->$formFileName2 != "") {
                $fileFinalName2 = time() . rand(1111, 9999) . '.' . $request->file($formFileName2)->getClientOriginalExtension();
                $path = $this->uploadPath;

                if (!file_exists($path)) {
                    mkdir($path, 0755, true);
                }

                $request->file($formFileName2)->move($path, $fileFinalName2);
                Helper::imageResize($path . $fileFinalName2);
                Helper::imageOptimize($path . $fileFinalName2);
            }
            // End of Upload Files

            $birthdayPackages->title  = $request->title;
            $birthdayPackages->description = $request->description;
            $birthdayPackages->price  = $request->price;
            $birthdayPackages->map_link = $request->map_link;
            $birthdayPackages->updated_by = Auth::user()->id;
            $birthdayPackages->status = $request->status;
            $birthdayPackages->updated_at = now();
            $birthdayPackages->save();
            if(count($uploadedFileNames) > 0){
                for($i=0;$i<count($uploadedFileNames);$i++){
                    $media = new Media;
                    $media->module  = 'birthday_packages';
                    $media->module_id = $birthdayPackages->id;
                    $media->filename  = $uploadedFileNames[$i];
                    $media->original_name = $uploadedFileNames[$i];
                    $media->file_url = $this->uploadPath.$uploadedFileNames[$i];
                    $media->mime_type = '';
                    $media->file_type  = 'image';
                    $media->save();
                }
            }
            if ($fileFinalName2 != "") {
                $media = new Media;
                $media->module  = 'birthday_cover_photo';
                $media->module_id = $birthdayPackages->id;
                $media->filename  = $fileFinalName2;
                $media->original_name = $fileFinalName2;
                $media->file_url = $this->uploadPath.$fileFinalName2;
                $media->mime_type = '';
                $media->file_type  = 'image';
                $media->save();
            }
            return redirect()->action('Dashboard\BirthdayPackagesController@edit', [$id])->with('doneMessage',
                __('backend.saveDone'));

       }else{
            return redirect()->action('Dashboard\BirthdayPackagesController@index');
       }
    }

    public function seo(Request $request, $webmasterId, $id)
    {
        
    }

    public function destroy($webmasterId, $id = 0)
    {
        
    }

    public function updateAll(Request $request, $webmasterId)
    {
       
    }


}
