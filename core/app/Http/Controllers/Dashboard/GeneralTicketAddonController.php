<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\Models\Section;
use App\Models\GeneralTicketPackages;
use App\Models\GeneralTicketAddon;
use App\Models\WebmasterSection;
use App\Models\Media;
use Illuminate\Pagination\LengthAwarePaginator;
use Auth;
use File;
use Helper;
use Illuminate\Http\Request;
use App\Helpers\ApiHelper;
use Redirect;
use Illuminate\Support\Facades\Http;
use Carbon\Carbon;



class GeneralTicketAddonController extends Controller
{
    private $uploadPath = "uploads/sections/";

    public function __construct()
    {
        $this->middleware('auth');

    }

    public function index()
    {
        $authCode = Helper::GeneralSiteSettings('auth_code_en');
        $GeneralWebmasterSections = WebmasterSection::where('status', '=', '1')->orderby('row_no', 'asc')->get();
        $general_ticket_addon = GeneralTicketAddon::with(['media_slider'])->where('auth_code',$authCode)->orderby('id', 'desc')->get();
        $perPage = 10;
        $currentPage = LengthAwarePaginator::resolveCurrentPage();
        $collection = collect($general_ticket_addon);
        $paginated = new LengthAwarePaginator(
            $collection->forPage($currentPage, $perPage),
            $collection->count(),
            $perPage,
            $currentPage,
            ['path' => url()->current(), 'query' => request()->query()]
        );
        return view("dashboard.general_ticket_addon.list", compact("paginated", "GeneralWebmasterSections"));
    }

    public function getData(Request $request)
    {
        $authCode = Helper::GeneralSiteSettings('auth_code_en');
        $date = Carbon::today()->toDateString();
        $query = GeneralTicketAddon::with(['media_slider'])->where('auth_code',$authCode);
        if ($request->has('search') && $request->search['value'] != '') {
            $search = $request->search['value'];
            $query->where(function ($q) use ($search) {
                $q->where('generalTicketType', 'like', "%{$search}%")
                ->orWhere('ticketType', 'like', "%{$search}%")
                ->orWhere('ticketCategory', 'like', "%{$search}%");
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
        $externalProducts = ApiHelper::getAddonWithoutCategory($data,$date);
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
                'venueId' => $row->venueId,            
                'generalTicketType' => '<a class="dropdown-item" href="' . route('generalticketsaddonEdit', $row->slug) . '">'.$row->generalTicketType.'</a>',
                'ticketType' => $row->ticketType,
                'ticketCategory' => $row->ticketCategory,
                'price' => '$' . number_format($external['price'], 2),
                'new_price' => '$' . number_format($row->new_price, 2),
                'is_primary' => '<div class="text-center"><i class="fa ' . ($row->is_primary ? 'fa-check text-success' : 'fa-times text-danger') . ' inline"></i></div>',
                 'status' => '<div class="text-center"><i class="fa ' . ($row->status ? 'fa-check text-success' : 'fa-times text-danger') . ' inline"></i></div>',
                'options' => '<div class="dropdown">
                                <button type="button" class="btn btn-sm light dk dropdown-toggle" data-toggle="dropdown">
                                    <i class="material-icons">&#xe5d4;</i> Options
                                </button>
                                <div class="dropdown-menu pull-right">
                                    <a class="dropdown-item" href="' . route('generalticketsaddonEdit', $row->slug) . '">
                                        <i class="material-icons">&#xe3c9;</i> Edit
                                    </a>
                                     <a class="dropdown-item text-danger" onclick="DeleteTicketAddon(\'' . $row->id . '\')">
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

    public function create()
    {
        $GeneralWebmasterSections = WebmasterSection::where('status', '=', '1')->orderby('row_no', 'asc')->get();

        $baseUrl = Helper::GeneralSiteSettings('external_api_link_en');
        $authCode = Helper::GeneralSiteSettings('auth_code_en');
        $date = Carbon::today()->toDateString();

        try {
            $response = Http::get($baseUrl.'/Pricing/GetAllProductPrice?authcode='.$authCode.'&date='.$date);
            $getTicketGeneral = GeneralTicketPackages::where('auth_code', $authCode)->where('status', '=', '1')->get();
            if ($response->successful()) {
            $apiData = $response->json();
            $tickets = $apiData['getAllProductPrice']['data'] ?? [];
            $tickets_arr = ['ticket' => [], 'ticket_addon' => []];
            $dbSlugs = $getTicketGeneral->pluck('ticketSlug')->toArray();
            if (!empty($tickets)) {
                foreach ($tickets as $ticket) {
                    //if ($ticket['ticketCategory'] !== 'Season Passes' && !in_array($ticket['ticketSlug'], $dbSlugs)){
                    if ($ticket['ticketCategory'] !== 'Season Passes' && $ticket['ticketCategory'] !== 'Anyday'){
                        $tickets_arr['ticket_addon'][] = $ticket;
                    } 
                    if ($ticket['venueId'] !== 0){
                        $tickets_arr['ticket'][] = $ticket;
                    }
                }
            }
            return view("dashboard.general_ticket_addon.create", compact("tickets_arr","getTicketGeneral", "GeneralWebmasterSections"));
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

    public function store(Request $request)
    {
        $this->validate($request, [
            'is_primary' => 'required',
            'generalTicketSlug' => 'required',
            'ticketSlug' => 'required',
            'description' => 'required',
        ]);
        $baseUrl = Helper::GeneralSiteSettings('external_api_link_en');
        $authCode = Helper::GeneralSiteSettings('auth_code_en');
        $date = Carbon::today()->toDateString();

        try {
            $checkPrimaryProduct = GeneralTicketAddon::where('generalTicketSlug',$request->generalTicketSlug)->where('ticketSlug',$request->ticketSlug)->where('is_primary','1')->where('auth_code',$authCode)->first();

            if (!empty($checkPrimaryProduct)) {
                return redirect()->action('Dashboard\GeneralTicketAddonController@create')->with('errorMessage', 'This product addon already exists in primary product.');
            }

            $checkSecondaryProduct = GeneralTicketAddon::where('generalTicketSlug',$request->generalTicketSlug)->where('ticketSlug',$request->ticketSlug)->where('is_primary','0')->where('auth_code',$authCode)->first();

            if (!empty($checkSecondaryProduct)) {
                return redirect()->action('Dashboard\GeneralTicketAddonController@create')->with('errorMessage', 'This product addon already exists in seconday product.');
            }

            $primaryProductCount = GeneralTicketAddon::where('generalTicketSlug',$request->generalTicketSlug)->where('is_primary','1')->where('status','1')->where('auth_code',$authCode)->count();

            if ($primaryProductCount > 3) {
                return redirect()->action('Dashboard\GeneralTicketAddonController@create')->with('errorMessage', 'You can add only 4 primary products.');
            }

            $response = Http::get($baseUrl.'/Pricing/GetAllProductPrice?authcode='.$authCode.'&date='.$date);

            if ($response->successful()) {
                $apiData = $response->json();
                $tickets = $apiData['getAllProductPrice']['data'] ?? [];
                $tickets_arr = ['ticket' => [], 'ticket_addon' => []];

                if (!empty($tickets)) {
                    foreach ($tickets as $ticket) {
                        // if ($ticket['ticketSlug'] == $request->generalTicketSlug ) {
                        //     $tickets_arr['ticket'][] = $ticket;
                        // } else
                        if ($ticket['ticketSlug'] === $request->ticketSlug) {
                            $tickets_arr['ticket_addon'][] = $ticket;
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
                $general_ticket_packages = GeneralTicketPackages::with(['media_slider'])->where('slug',$request->generalTicketSlug)->where('auth_code',$authCode)->first();
                $ticketAddon = new GeneralTicketAddon;
                $ticketAddon->auth_code  = Helper::GeneralSiteSettings('auth_code_en');
                $ticketAddon->generalTicketType  = $general_ticket_packages->title;
                $ticketAddon->generalTicketSlug  = $request->generalTicketSlug;
                $ticketAddon->is_primary  = $request->is_primary;
                $ticketAddon->venueId = $tickets_arr['ticket_addon'][0]['venueId'];
                $ticketAddon->ticketType  = $tickets_arr['ticket_addon'][0]['ticketType'];
                $ticketAddon->ticketSlug = $tickets_arr['ticket_addon'][0]['ticketSlug'];
                $ticketAddon->ticketCategory = $tickets_arr['ticket_addon'][0]['ticketCategory'];
                $ticketAddon->price = $tickets_arr['ticket_addon'][0]['price'];
                $ticketAddon->new_price = $request->new_price;
                $ticketAddon->is_new_price_show = $request->is_new_price_show;
                $ticketAddon->description = $request->description;
                $ticketAddon->status = $request->status;
                $ticketAddon->save();
                

                if(count($uploadedFileNames) > 0){
                    for($i=0;$i<count($uploadedFileNames);$i++){
                        $media = new Media;
                        $media->module  = 'general_ticket_addon';
                        $media->module_id = $ticketAddon->id;
                        $media->filename  = $uploadedFileNames[$i];
                        $media->original_name = $uploadedFileNames[$i];
                        $media->file_url = $this->uploadPath.$uploadedFileNames[$i];
                        $media->mime_type = '';
                        $media->file_type  = 'image';
                        $media->save();
                    }
                }

                return redirect()->action('Dashboard\GeneralTicketAddonController@index')->with('doneMessage', __('backend.addDone'));
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

        try {
            $GeneralWebmasterSections = WebmasterSection::where('status', '=', '1')->orderby('row_no', 'asc')->get();
            $ticket_addon = GeneralTicketAddon::with(['media_slider'])->where('slug',$slug)->where('auth_code',$authCode)->first();
            return view("dashboard.general_ticket_addon.edit", compact("ticket_addon", "GeneralWebmasterSections"));

        } catch (\Exception $e) {
            dd('Exception: ' . $e->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
       $authCode = Helper::GeneralSiteSettings('auth_code_en');
       $ticketAddon = GeneralTicketAddon::where('slug',$id)->where('auth_code',$authCode)->first();
       if (!empty($ticketAddon)) {
            $this->validate($request, [
                'description' => 'required',
            ]);
        if($request->status == '1'){
            $primaryProductCount = GeneralTicketAddon::where('generalTicketSlug',$ticketAddon->generalTicketSlug)->where('is_primary','1')->where('status','1')->where('auth_code',$authCode)->count();

            if ($primaryProductCount > 3) {
                return redirect()->action('Dashboard\GeneralTicketAddonController@edit', [$id])->with('errorMessage',
                __('Already 4 primary products are activated.'));
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
            $ticketAddon->new_price = $request->new_price;
            $ticketAddon->is_new_price_show = $request->is_new_price_show;
            $ticketAddon->description = $request->description;
            $ticketAddon->status = $request->status;
            $ticketAddon->save();
            if(count($uploadedFileNames) > 0){
                for($i=0;$i<count($uploadedFileNames);$i++){
                    $media = new Media;
                    $media->module  = 'general_ticket_addon';
                    $media->module_id = $ticketAddon->id;
                    $media->filename  = $uploadedFileNames[$i];
                    $media->original_name = $uploadedFileNames[$i];
                    $media->file_url = $this->uploadPath.$uploadedFileNames[$i];
                    $media->mime_type = '';
                    $media->file_type  = 'image';
                    $media->save();
                }
            }
            return redirect()->action('Dashboard\GeneralTicketAddonController@edit', [$id])->with('doneMessage',
                __('backend.saveDone'));

       }else{
            return redirect()->action('Dashboard\GeneralTicketAddonController@index');
       }
    }

    public function seo(Request $request, $webmasterId, $id)
    {
        
    }

    public function destroy($id = 0)
    {
        //
        $authCode = Helper::GeneralSiteSettings('auth_code_en');
        $ticketAddon = GeneralTicketAddon::with(['media_slider'])->where('id',$id)->where('auth_code',$authCode)->first();
        if (!empty($ticketAddon)) {
            GeneralTicketAddon::where('id',$id)->where('auth_code',$authCode)->delete();
            return redirect()->action('Dashboard\GeneralTicketAddonController@index')->with('doneMessage',
                __('backend.deleteDone'));
        } else {
            return redirect()->action('Dashboard\GeneralTicketAddonController@index');
        }
        
    }

    public function updateAll(Request $request, $webmasterId)
    {
       
    }


}
