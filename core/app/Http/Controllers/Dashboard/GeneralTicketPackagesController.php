<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\Models\Section;
use App\Models\GeneralTicketPackages;
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



class GeneralTicketPackagesController extends Controller
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
        $general_ticket_packages = GeneralTicketPackages::with(['media_slider'])->where('auth_code',$authCode)->get();
         // Paginate
        $perPage = 10;
        $currentPage = LengthAwarePaginator::resolveCurrentPage();
        $collection = collect($general_ticket_packages);
        $paginated = new LengthAwarePaginator(
            $collection->forPage($currentPage, $perPage),
            $collection->count(),
            $perPage,
            $currentPage,
            ['path' => url()->current(), 'query' => request()->query()]
        );
        return view("dashboard.general_ticket_packages.list", compact("paginated", "GeneralWebmasterSections"));
    }

    public function create()
    {
        // General for all pages
        $GeneralWebmasterSections = WebmasterSection::where('status', '=', '1')->orderby('row_no', 'asc')->get();
        // General END
        return view("dashboard.general_ticket_packages.create", compact("GeneralWebmasterSections"));
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'title' => 'required',
            'description' => 'required',
        ]);
        $authCode = Helper::GeneralSiteSettings('auth_code_en');
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

        $generalTicketPackage = new GeneralTicketPackages;
        $generalTicketPackage->auth_code  = $authCode;
        $generalTicketPackage->title  = $request->title;
        $generalTicketPackage->description = $request->description;
        $generalTicketPackage->status = $request->status;
        $generalTicketPackage->save();
        
        if(count($uploadedFileNames) > 0){
            for($i=0;$i<count($uploadedFileNames);$i++){
                $media = new Media;
                $media->module  = 'general_ticket_packages';
                $media->module_id = $generalTicketPackage->id;
                $media->filename  = $uploadedFileNames[$i];
                $media->original_name = $uploadedFileNames[$i];
                $media->file_url = $this->uploadPath.$uploadedFileNames[$i];
                $media->mime_type = '';
                $media->file_type  = 'image';
                $media->save();
            }
        }

        return redirect()->action('Dashboard\GeneralTicketPackagesController@index')->with('doneMessage', __('backend.addDone'));
    }

    public function clone($webmasterId, $id)
    {
        
    }

    public function edit($slug)
    {
        
        $authCode = Helper::GeneralSiteSettings('auth_code_en');
        $GeneralWebmasterSections = WebmasterSection::where('status', '=', '1')->orderby('row_no', 'asc')->get();
        $general_ticket_packages = GeneralTicketPackages::with(['media_slider'])->where('slug',$slug)->where('auth_code',$authCode)->first();
        return view("dashboard.general_ticket_packages.edit", compact("general_ticket_packages", "GeneralWebmasterSections"));
    }

    public function update(Request $request, $id)
    {
       $authCode = Helper::GeneralSiteSettings('auth_code_en');
       $generalTicketPackages = GeneralTicketPackages::where('slug',$id)->where('auth_code',$authCode)->first();
       if (!empty($generalTicketPackages)) {
            $this->validate($request, [
                'title' => 'required',
                'description' => 'required'
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

            $generalTicketPackages->title  = $request->title;
            $generalTicketPackages->description = $request->description;
            $generalTicketPackages->status = $request->status;
            $generalTicketPackages->save();

            if(count($uploadedFileNames) > 0){
                for($i=0;$i<count($uploadedFileNames);$i++){
                    $media = new Media;
                    $media->module  = 'general_ticket_packages';
                    $media->module_id = $generalTicketPackages->id;
                    $media->filename  = $uploadedFileNames[$i];
                    $media->original_name = $uploadedFileNames[$i];
                    $media->file_url = $this->uploadPath.$uploadedFileNames[$i];
                    $media->mime_type = '';
                    $media->file_type  = 'image';
                    $media->save();
                }
            }
            return redirect()->action('Dashboard\GeneralTicketPackagesController@edit', [$id])->with('doneMessage',
                __('backend.saveDone'));

       }else{
            return redirect()->action('Dashboard\GeneralTicketPackagesController@index');
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
