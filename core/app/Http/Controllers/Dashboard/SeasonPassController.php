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
use Illuminate\Http\Request;
use Redirect;
use Illuminate\Support\Facades\Http;
use Carbon\Carbon;



class SeasonPassController extends Controller
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
        $getTicketSeasonPass = SeasonPass::with(['media_slider','products','createdBy','updatedBy'])->where('auth_code', $authCode)->orderby('id', 'desc')->get();
        $perPage = 10;
        $currentPage = LengthAwarePaginator::resolveCurrentPage();
        $paginated = new LengthAwarePaginator(
            $getTicketSeasonPass->forPage($currentPage, $perPage),
            $getTicketSeasonPass->count(),
            $perPage,
            $currentPage,
            ['path' => url()->current(), 'query' => request()->query()]
        );
         return view("dashboard.seasonpass.list", compact("paginated", "GeneralWebmasterSections"));
    }

    public function getData(Request $request)
    {
        $authCode = Helper::GeneralSiteSettings('auth_code_en');
        $query = SeasonPass::with(['media_slider','products','createdBy','updatedBy'])->where('auth_code', $authCode);
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
                'title' => '<a class="dropdown-item" href="' . route('seasonpassEdit', $row->slug) . '">'.$row->title.'</a>',
                'slug' => $row->slug,
                'products' => '<div class="text-center">'.count($row->products).'</div>',
                'status' => '<div class="text-center"><i class="fa ' . ($row->status ? 'fa-check text-success' : 'fa-times text-danger') . ' inline"></i></div>',
                'created_by' => $row->createdBy->name,
                'updated_by' => $row->updatedBy->name ?? 'N/A',
                'updated_at' => $row->updated_at->format('Y-m-d'),
                'options' => '<div class="dropdown">
                                <button type="button" class="btn btn-sm light dk dropdown-toggle" data-toggle="dropdown">
                                    <i class="material-icons">&#xe5d4;</i> Options
                                </button>
                                <div class="dropdown-menu pull-right">
                                    <a class="dropdown-item" href="' . route('seasonpassEdit', $row->slug) . '">
                                        <i class="material-icons">&#xe3c9;</i> Edit
                                    </a>
                                    <a class="dropdown-item"
                                        href="' . Helper::GeneralSiteSettings('site_url') . '/sp/' . $row->slug . '"
                                        target="_blank"><i
                                            class="material-icons">&#xe8f4;</i> Preview
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
        return view("dashboard.seasonpass.create", compact("GeneralWebmasterSections"));

    }

    public function store(Request $request)
    {
        $this->validate($request, [
        ]);
        $formFileName = "photo";
        $uploadedFileNames = [];
        $baseUrl = Helper::GeneralSiteSettings('external_api_link_en');
        $authCode = Helper::GeneralSiteSettings('auth_code_en');
        $date = Carbon::today()->toDateString();
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
        $seasonpassTickets = new SeasonPass;
        $seasonpassTickets->auth_code  = Helper::GeneralSiteSettings('auth_code_en');
        $seasonpassTickets->title = $request->title;
        $seasonpassTickets->description = $request->description;
        $seasonpassTickets->created_by = Auth::user()->id;
        $seasonpassTickets->status = $request->status;
        $seasonpassTickets->save();


        if(count($uploadedFileNames) > 0){
            for($i=0;$i<count($uploadedFileNames);$i++){
                $media = new Media;
                $media->module  = 'season_pass';
                $media->module_id = $seasonpassTickets->id;
                $media->filename  = $uploadedFileNames[$i];
                $media->original_name = $uploadedFileNames[$i];
                $media->file_url = $this->uploadPath.$uploadedFileNames[$i];
                $media->mime_type = '';
                $media->file_type  = 'image';
                $media->save();
            }
        }

            return redirect()->action('Dashboard\SeasonPassController@index')->with('doneMessage', __('backend.addDone'));

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
        $get_ticket = SeasonPass::with(['media_slider'])->where('slug',$slug)->first();
        return view("dashboard.seasonpass.edit", compact("get_ticket", "GeneralWebmasterSections"));
    }

    public function update(Request $request, $id)
    {
        $seasonPassUpdate = SeasonPass::where('slug',$id)->first();
       if (!empty($seasonPassUpdate)) {
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
        $seasonPassUpdate->title = $request->title;
        $seasonPassUpdate->description = $request->description;
        $seasonPassUpdate->updated_by = Auth::user()->id;
        $seasonPassUpdate->status = $request->status;
        $seasonPassUpdate->updated_at = now();
        $seasonPassUpdate->save();

        SeasonPassAddon::where('season_passes_slug', $id)->update([
            'status' => $request->status
        ]);

        if(count($uploadedFileNames) > 0){
            for($i=0;$i<count($uploadedFileNames);$i++){
                $media = new Media;
                $media->module  = 'season_pass';
                $media->module_id = $seasonPassUpdate->id;
                $media->filename  = $uploadedFileNames[$i];
                $media->original_name = $uploadedFileNames[$i];
                $media->file_url = $this->uploadPath.$uploadedFileNames[$i];
                $media->mime_type = '';
                $media->file_type  = 'image';
                $media->save();
            }
        }

        return redirect()->action('Dashboard\SeasonPassController@index')->with('doneMessage', __('backend.saveDone'));


       }else{
            return redirect()->action('Dashboard\SeasonPassController@index');
       }

    }

    public function seo(Request $request, $webmasterId, $id)
    {

    }

    public function destroy($ticketSlug = 0)
    {
        $authCode = Helper::GeneralSiteSettings('auth_code_en');
        $ticketAddon = SeasonPass::where('slug',$ticketSlug)->where('auth_code',$authCode)->first();
        if (!empty($ticketAddon)) {
            SeasonPass::where('slug',$ticketSlug)->where('auth_code',$authCode)->delete();
            SeasonPassAddon::where('season_passes_slug',$ticketSlug)->where('auth_code',$authCode)->delete();
            return redirect()->action('Dashboard\SeasonPassController@index')->with('doneMessage',
                __('backend.deleteDone'));
        } else {
            return redirect()->action('Dashboard\SeasonPassController@index');
        }
    }

    public function updateAll(Request $request, $webmasterId)
    {

    }


}
