<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\Models\Section;
use App\Models\CabanaPackages;
use App\Models\WebmasterSection;
use App\Models\Media;
use Illuminate\Pagination\LengthAwarePaginator;
use Auth;
use File;
use Helper;
use Illuminate\Http\Request;
use Redirect;
use Illuminate\Support\Facades\Http;
use Carbon\Carbon;



class CabanaPackagesController extends Controller
{
    private $uploadPath = "uploads/sections/";

    public function __construct()
    {
        $this->middleware('auth');

    }

    public function index()
    {
        // General for all pages
        $authCode = Helper::GeneralSiteSettings('auth_code_en');
        $GeneralWebmasterSections = WebmasterSection::where('status', '=', '1')->orderby('row_no', 'asc')->get();
        $cabana_packages = CabanaPackages::with(['media_slider'])->where('auth_code', $authCode)->get();
         // Paginate
        $perPage = 10;
        $currentPage = LengthAwarePaginator::resolveCurrentPage();
        $collection = collect($cabana_packages);
        $paginated = new LengthAwarePaginator(
            $collection->forPage($currentPage, $perPage),
            $collection->count(),
            $perPage,
            $currentPage,
            ['path' => url()->current(), 'query' => request()->query()]
        );
        return view("dashboard.cabana_packages.list", compact("paginated", "GeneralWebmasterSections"));
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

            return view("dashboard.cabana_packages.create", compact("cabanas", "GeneralWebmasterSections"));
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
            'cabanas' => 'required',
        ]);
        
        $baseUrl = Helper::GeneralSiteSettings('external_api_link_en');
        $authCode = Helper::GeneralSiteSettings('auth_code_en');
        $date = Carbon::today()->toDateString();
        try {
            $cabanaCheck = CabanaPackages::where('ticketSlug',$request->cabanas)->where('auth_code',$authCode)->first();

            if (!empty($cabanaCheck)) {
                return redirect()->action('Dashboard\CabanaPackagesController@create')->with('errorMessage', 'Cabana already exists.');
            }

            $response = Http::get($baseUrl.'/Pricing/GetAllProductPrice?authcode='.$authCode.'&date='.$date);

            if ($response->successful()) {
                $apiData = $response->json();
                $tickets = $apiData['getAllProductPrice']['data'] ?? [];
                $tickets_arr = [];

                if (!empty($tickets)) {
                    foreach ($tickets as $ticket) {
                        if ($ticket['ticketSlug'] == $request->cabanas ) {
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
                $cabanaPackage = new CabanaPackages;
                $cabanaPackage->auth_code  = Helper::GeneralSiteSettings('auth_code_en');
                $cabanaPackage->venueId = $tickets_arr[0]['venueId'];
                $cabanaPackage->ticketType  = $tickets_arr[0]['ticketType'];
                $cabanaPackage->ticketSlug = $tickets_arr[0]['ticketSlug'];
                $cabanaPackage->ticketCategory = $tickets_arr[0]['ticketCategory'];
                $cabanaPackage->price = $tickets_arr[0]['price'];
                $cabanaPackage->description = $request->description;
                $cabanaPackage->is_featured = $request->is_featured;
                $cabanaPackage->status = $request->status;
                $cabanaPackage->save();

                if(count($uploadedFileNames) > 0){
                    for($i=0;$i<count($uploadedFileNames);$i++){
                        $media = new Media;
                        $media->module  = 'cabana_package';
                        $media->module_id = $cabanaPackage->id;
                        $media->filename  = $uploadedFileNames[$i];
                        $media->original_name = $uploadedFileNames[$i];
                        $media->file_url = $this->uploadPath.$uploadedFileNames[$i];
                        $media->mime_type = '';
                        $media->file_type  = 'image';
                        $media->save();
                    }
                }
                return redirect()->action('Dashboard\CabanaPackagesController@index')->with('doneMessage', __('backend.addDone'));

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

    public function edit($slug)
    {
        
        $baseUrl = Helper::GeneralSiteSettings('external_api_link_en');
        $authCode = Helper::GeneralSiteSettings('auth_code_en'); 
        $date = Carbon::today()->toDateString();
        // General for all pages
        $GeneralWebmasterSections = WebmasterSection::where('status', '=', '1')->orderby('row_no', 'asc')->get();
        $cabanaPackage = CabanaPackages::with(['media_slider'])->where('slug', $slug)->where('auth_code', $authCode)->first();

        return view("dashboard.cabana_packages.edit", compact("cabanaPackage","GeneralWebmasterSections"));
    }

    public function update(Request $request, $id)
    {
        $authCode = Helper::GeneralSiteSettings('auth_code_en'); 
       $cabanaPackagesUpdate = CabanaPackages::where('slug',$id)->where('auth_code', $authCode)->first();
       if (!empty($cabanaPackagesUpdate)) {
            $this->validate($request, [
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
        $cabanaPackagesUpdate->description = $request->description;
        $cabanaPackagesUpdate->is_featured = $request->is_featured;
        $cabanaPackagesUpdate->status = $request->status;
        $cabanaPackagesUpdate->save();
        

        if(count($uploadedFileNames) > 0){
            for($i=0;$i<count($uploadedFileNames);$i++){
                $media = new Media;
                $media->module  = 'cabana_package';
                $media->module_id = $cabanaPackagesUpdate->id;
                $media->filename  = $uploadedFileNames[$i];
                $media->original_name = $uploadedFileNames[$i];
                $media->file_url = $this->uploadPath.$uploadedFileNames[$i];
                $media->mime_type = '';
                $media->file_type  = 'image';
                $media->save();
            }
        }

        return redirect()->action('Dashboard\CabanaPackagesController@index')->with('doneMessage', __('backend.saveDone'));

       }else{
            return redirect()->action('Dashboard\CabanaPackagesController@index');
       }
    }

    public function seo(Request $request, $webmasterId, $id)
    {
        
    }

    public function destroy($ticketSlug = 0)
    {
        $authCode = Helper::GeneralSiteSettings('auth_code_en');
        $cabanaPackage = CabanaPackages::where('slug',$ticketSlug)->where('auth_code',$authCode)->first();
        if (!empty($cabanaPackage)) {
            CabanaPackages::where('slug',$ticketSlug)->where('auth_code',$authCode)->delete();
            return redirect()->action('Dashboard\CabanaPackagesController@index')->with('doneMessage',
                __('backend.deleteDone'));
        } else {
            return redirect()->action('Dashboard\CabanaPackagesController@index');
        }
    }

    public function updateAll(Request $request, $webmasterId)
    {
       
    }


}
