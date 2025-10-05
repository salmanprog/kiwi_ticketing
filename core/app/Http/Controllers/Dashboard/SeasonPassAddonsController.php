<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\Models\Section;
use App\Models\Topic;
use App\Models\SeasonPass;
use App\Models\SeasonPassAddon;
use App\Models\WebmasterSection;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Models\Media;
use Auth;
use File;
use Helper;
use App\Helpers\ApiHelper;
use Illuminate\Http\Request;
use Redirect;
use Illuminate\Support\Facades\Http;
use Carbon\Carbon;



class SeasonPassAddonsController extends Controller
{
    private $uploadPath = "uploads/sections/";

    public function __construct()
    {
        $this->middleware('auth');

    }

    public function index()
    {
        $baseUrl = Helper::GeneralSiteSettings('external_api_link_en');
        $authCode = Helper::GeneralSiteSettings('auth_code_en');
        $date = Carbon::today()->toDateString();
        // General for all pages
        $GeneralWebmasterSections = WebmasterSection::where('status', '=', '1')->orderby('row_no', 'asc')->get();
        try {
            
            $getSeasonPass = SeasonPassAddon::with(['media_slider','season_pass'])->where('auth_code', $authCode)->orderby('id', 'desc')->get();
            // Paginate
            $perPage = 10;
            $currentPage = LengthAwarePaginator::resolveCurrentPage();
            $collection = collect($getSeasonPass);
            $paginated = new LengthAwarePaginator(
                $collection->forPage($currentPage, $perPage),
                $collection->count(),
                $perPage,
                $currentPage,
                ['path' => url()->current(), 'query' => request()->query()]
            );

            return view("dashboard.seasonpassaddon.list", compact("paginated", "GeneralWebmasterSections"));

        } catch (\Exception $e) {
            // Handle connection or request exceptions
            dd('Exception: ' . $e->getMessage());
        }
    }

    public function getData(Request $request)
    {
        $authCode = Helper::GeneralSiteSettings('auth_code_en');
        $query = SeasonPassAddon::with(['media_slider','season_pass'])->where('auth_code', $authCode);
        if ($request->has('search') && $request->search['value'] != '') {
            $search = $request->search['value'];
            $query->where(function ($q) use ($search) {
                $q->where('ticketType', 'like', "%{$search}%")
                ->orWhereHas('season_pass', function ($q2) use ($search) {
                    $q2->where('title', 'like', "%{$search}%");
                });
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
        $externalProducts = ApiHelper::getProductByCategory($data,'Season Passes');
        $externalMap = collect($externalProducts)->keyBy('ticketSlug');
        $result = [];
        foreach ($data as $row) {
            $external = $externalMap[$row->ticketSlug] ?? null;
            $result[] = [
                'id' => $row->id,
                'check' => '<label class="ui-check m-a-0">
                                <input type="checkbox" name="ids[]" value="' . $row->id . '"><i></i>
                                <input type="hidden" name="row_ids[]" value="' . $row->id . '" class="form-control row_no">
                            </label>',
                'seasonpass' => '<a class="dropdown-item" href="' . route('seasonpass') . '">'.$row->season_pass->title.'</a>',
                'title' => '<a class="dropdown-item" href="' . route('seasonpassaddonEdit', $row->slug) . '">'.$row->ticketType.'</a>',
                'slug' => $row->ticketSlug,
                'price' => '$' . number_format($external['price'], 2),
                'new_price' => '$' . number_format($row->new_price, 2),
                'status' => '<div class="text-center"><i class="fa ' . ($row->status ? 'fa-check text-success' : 'fa-times text-danger') . ' inline"></i></div>',
                'options' => '<div class="dropdown">
                                <button type="button" class="btn btn-sm light dk dropdown-toggle" data-toggle="dropdown">
                                    <i class="material-icons">&#xe5d4;</i> Options
                                </button>
                                <div class="dropdown-menu pull-right">
                                    <a class="dropdown-item" href="' . route('seasonpassaddonEdit', $row->slug) . '">
                                        <i class="material-icons">&#xe3c9;</i> Edit
                                    </a>
                                    <a class="dropdown-item text-danger" onclick="DeleteTicket(\'' . $row->slug . '\')">
                                        <i class="material-icons">&#xe872;</i> Delete
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

    public function cabanAddon()
    {
        
    }

    public function create()
    {
        $baseUrl = Helper::GeneralSiteSettings('external_api_link_en');
        $authCode = Helper::GeneralSiteSettings('auth_code_en');
        $date = Carbon::today()->toDateString();
        $GeneralWebmasterSections = WebmasterSection::where('status', '=', '1')->orderby('row_no', 'asc')->get();
        $getSeasonPass = SeasonPass::with(['media_slider'])->where('auth_code', $authCode)->where('status','1')->orderby('id', 'asc')->get();
        try {
            $response = Http::get($baseUrl.'/Pricing/GetAllProductPrice?authcode='.$authCode.'&date='.$date);

            if ($response->successful()) {
            $apiData = $response->json();
            $tickets = $apiData['getAllProductPrice']['data'] ?? [];
            $addon_arr = [];
            if(count($tickets) > 0){
                for($i=0;$i<count($tickets);$i++){
                    if($tickets[$i]['ticketCategory'] == 'Season Passes'){
                        array_push($addon_arr,$tickets[$i]);
                    }
                }
            }

            return view("dashboard.seasonpassaddon.create", compact("addon_arr","getSeasonPass","GeneralWebmasterSections"));
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
            'season_passes_slug' => 'required',
            'ticketSlug' => 'required',
            'description' => 'required',
            'new_price' => 'required'
        ]);

        $baseUrl = Helper::GeneralSiteSettings('external_api_link_en');
        $authCode = Helper::GeneralSiteSettings('auth_code_en');
        $date = Carbon::today()->toDateString();
        try {
            $ticketGeneralCheck = SeasonPassAddon::where('season_passes_slug',$request->season_passes_slug)->where('ticketSlug',$request->ticketSlug)->where('auth_code',$authCode)->first();

            if (!empty($ticketGeneralCheck)) {
                return redirect()->action('Dashboard\SeasonPassAddonsController@create')->with('errorMessage', 'Season pass addon already exists.');
            }

            $response = Http::get($baseUrl.'/Pricing/GetAllProductPrice?authcode='.$authCode.'&date='.$date);

            if ($response->successful()) {
                $apiData = $response->json();
                $tickets = $apiData['getAllProductPrice']['data'] ?? [];
                $tickets_arr = [];

                if (!empty($tickets)) {
                    foreach ($tickets as $ticket) {
                        if ($ticket['ticketSlug'] == $request->ticketSlug ) {
                            $tickets_arr[] = $ticket;
                        }
                    }
                }

                $formFileName = "photo";
                $uploadedFileNames = [];

                if ($request->hasFile($formFileName)) {
                    foreach ($request->file($formFileName) as $file) {
                        if ($file && $file->isValid()) {
                            $fileFinalName = time() . rand(1111, 9999) . '.' . $file->getClientOriginalExtension();
                            $path = $this->uploadPath;
                            if (!file_exists($path)) {
                                mkdir($path, 0755, true);
                            }
                            $file->move($path, $fileFinalName);
                            Helper::imageResize($path . $fileFinalName);
                            Helper::imageOptimize($path . $fileFinalName);
                            $uploadedFileNames[] = $fileFinalName;
                        }
                    }
                }
                $seasonpassAddon = new SeasonPassAddon;
                $seasonpassAddon->auth_code  = Helper::GeneralSiteSettings('auth_code_en');
                $seasonpassAddon->season_passes_slug = $request->season_passes_slug;
                $seasonpassAddon->venueId = $tickets_arr[0]['venueId'];
                $seasonpassAddon->ticketType  = $tickets_arr[0]['ticketType'];
                $seasonpassAddon->ticketSlug = $tickets_arr[0]['ticketSlug'];
                $seasonpassAddon->ticketCategory = $tickets_arr[0]['ticketCategory'];
                $seasonpassAddon->price = $tickets_arr[0]['price'];
                $seasonpassAddon->description = $request->description;
                $seasonpassAddon->new_price = $request->new_price;
                $seasonpassAddon->is_new_price_show = $request->is_new_price_show;
                $seasonpassAddon->is_featured = $request->is_featured;
                $seasonpassAddon->status = $request->status;
                $seasonpassAddon->save();

                if(count($uploadedFileNames) > 0){
                    for($i=0;$i<count($uploadedFileNames);$i++){
                        $media = new Media;
                        $media->module  = 'season_pass_addon';
                        $media->module_id = $seasonpassAddon->id;
                        $media->filename  = $uploadedFileNames[$i];
                        $media->original_name = $uploadedFileNames[$i];
                        $media->file_url = $this->uploadPath.$uploadedFileNames[$i];
                        $media->mime_type = '';
                        $media->file_type  = 'image';
                        $media->save();
                    }
                }
                return redirect()->action('Dashboard\SeasonPassAddonsController@index')->with('doneMessage', __('backend.addDone'));

            } else {
            dd([
                'status' => $response->status(),
                'error' => $response->body(),
            ]);
            }

        } catch (\Exception $e) {
            dd('Exception: ' . $e->getMessage());
        }
    }

    public function clone($webmasterId, $id)
    {
        
    }

    public function edit($id)
    {
        $baseUrl = Helper::GeneralSiteSettings('external_api_link_en');
        $authCode = Helper::GeneralSiteSettings('auth_code_en'); 
        $date = Carbon::today()->toDateString();
        // General for all pages
        $GeneralWebmasterSections = WebmasterSection::where('status', '=', '1')->orderby('row_no', 'asc')->get();
        $seasonpassAddon = SeasonPassAddon::with(['media_slider'])->where('slug', $id)->first();
        $getSeasonPass = SeasonPass::with(['media_slider'])->where('slug', $seasonpassAddon->season_passes_slug)->where('auth_code', $authCode)->first();
        $response = Http::get($baseUrl.'/Pricing/GetAllProductPrice?authcode='.$authCode.'&date='.$date);
        if ($response->successful()) {
            $apiData = $response->json();
            $tickets = $apiData['getAllProductPrice']['data'] ?? [];
            $fillter_arr = [];
            if (!empty($tickets)) {
                foreach ($tickets as $ticket) {
                    if ($ticket['ticketSlug'] == $seasonpassAddon->ticketSlug ) {
                        $tickets_arr[] = $ticket;
                    }
                }
            }
        }
        return view("dashboard.seasonpassaddon.edit", compact("tickets_arr", "seasonpassAddon","getSeasonPass","GeneralWebmasterSections"));
    }

    public function update(Request $request, $id)
    {
        $seasonPassUpdate = SeasonPassAddon::where('slug',$id)->first();
       if (!empty($seasonPassUpdate)) {
            $this->validate($request, [
                'description' => 'required',
                'new_price' => 'required',
            ]);
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
        $uploadedFileNames = [];

        if ($request->hasFile($formFileName)) {
            foreach ($request->file($formFileName) as $file) {
                if ($file && $file->isValid()) {
                    $fileFinalName = time() . rand(1111, 9999) . '.' . $file->getClientOriginalExtension();
                    $path = $this->uploadPath;
                    if (!file_exists($path)) {
                        mkdir($path, 0755, true);
                    }
                    $file->move($path, $fileFinalName);
                    Helper::imageResize($path . $fileFinalName);
                    Helper::imageOptimize($path . $fileFinalName);
                    $uploadedFileNames[] = $fileFinalName;
                }
            }
        }

        $seasonPassUpdate->description = $request->description;
        $seasonPassUpdate->price = $request->new_price;
        $seasonPassUpdate->is_new_price_show = $request->is_new_price_show;
        $seasonPassUpdate->is_featured = $request->is_featured;
        $seasonPassUpdate->status = $request->status;
        $seasonPassUpdate->save();
        

        if(count($uploadedFileNames) > 0){
            for($i=0;$i<count($uploadedFileNames);$i++){
                $media = new Media;
                $media->module  = 'season_pass_addon';
                $media->module_id = $seasonPassUpdate->id;
                $media->filename  = $uploadedFileNames[$i];
                $media->original_name = $uploadedFileNames[$i];
                $media->file_url = $this->uploadPath.$uploadedFileNames[$i];
                $media->mime_type = '';
                $media->file_type  = 'image';
                $media->save();
            }
        }

        return redirect()->action('Dashboard\SeasonPassAddonsController@index')->with('doneMessage', __('backend.saveDone'));
            

       }else{
            return redirect()->action('Dashboard\SeasonPassAddonsController@index');
       }
       
    }

    public function seo(Request $request, $webmasterId, $id)
    {
        
    }

    public function destroy($ticketSlug = 0)
    {
        $authCode = Helper::GeneralSiteSettings('auth_code_en');
        $ticketAddon = SeasonPassAddon::where('slug',$ticketSlug)->where('auth_code',$authCode)->first();
        if (!empty($ticketAddon)) {
            SeasonPassAddon::where('slug',$ticketSlug)->where('auth_code',$authCode)->delete();
            return redirect()->action('Dashboard\SeasonPassAddonsController@index')->with('doneMessage',
                __('backend.deleteDone'));
        } else {
            return redirect()->action('Dashboard\SeasonPassAddonsController@index');
        }
        
    }

    public function updateAll(Request $request, $webmasterId)
    {
       
    }


}
