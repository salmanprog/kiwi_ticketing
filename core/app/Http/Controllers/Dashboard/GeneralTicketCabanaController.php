<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\Models\Section;
use App\Models\GeneralTicketCabana;
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



class GeneralTicketCabanaController extends Controller
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
        $general_ticket_cabana = GeneralTicketCabana::with(['media_slider'])->where('auth_code',$authCode)->get();
        $perPage = 10;
        $currentPage = LengthAwarePaginator::resolveCurrentPage();
        $collection = collect($general_ticket_cabana);
        $paginated = new LengthAwarePaginator(
            $collection->forPage($currentPage, $perPage),
            $collection->count(),
            $perPage,
            $currentPage,
            ['path' => url()->current(), 'query' => request()->query()]
        );
        return view("dashboard.general_ticket_cabana.list", compact("paginated", "GeneralWebmasterSections"));
    }

    public function create()
    {
        $GeneralWebmasterSections = WebmasterSection::where('status', '=', '1')->orderby('row_no', 'asc')->get();

        $baseUrl = Helper::GeneralSiteSettings('external_api_link_en');
        $authCode = Helper::GeneralSiteSettings('auth_code_en');
        $date = Carbon::today()->toDateString();

        try {
            $response = Http::get($baseUrl.'/Pricing/GetAllProductPrice?authcode='.$authCode.'&date='.$date);

            if ($response->successful()) {
            $apiData = $response->json();
            $tickets = $apiData['getAllProductPrice']['data'] ?? [];
            $tickets_arr = ['ticket' => [], 'ticket_addon' => []];

            if (!empty($tickets)) {
                foreach ($tickets as $ticket) {
                    if ($ticket['ticketCategory'] === 'Cabanas') {
                        $tickets_arr['ticket_addon'][] = $ticket;
                    } elseif ($ticket['ticketCategory'] === 'Ticket') {
                        $tickets_arr['ticket'][] = $ticket;
                    }
                }
            }

            return view("dashboard.general_ticket_cabana.create", compact("tickets_arr", "GeneralWebmasterSections"));
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
            'generalTicketSlug' => 'required',
            'ticketSlug' => 'required',
            'description' => 'required',
        ]);
        $baseUrl = Helper::GeneralSiteSettings('external_api_link_en');
        $authCode = Helper::GeneralSiteSettings('auth_code_en');
        $date = Carbon::today()->toDateString();

        try {
            $ticketAddonCheck = GeneralTicketCabana::where('generalTicketSlug',$request->generalTicketSlug)->where('ticketSlug',$request->ticketSlug)->where('auth_code',$authCode)->first();

            if (!empty($ticketAddonCheck)) {
                return redirect()->action('Dashboard\GeneralTicketCabanaController@create')->with('errorMessage', 'This ticket cabana already exists.');
            }

            $response = Http::get($baseUrl.'/Pricing/GetAllProductPrice?authcode='.$authCode.'&date='.$date);

            if ($response->successful()) {
                $apiData = $response->json();
                $tickets = $apiData['getAllProductPrice']['data'] ?? [];
                $tickets_arr = ['ticket' => [], 'ticket_addon' => []];

                if (!empty($tickets)) {
                    foreach ($tickets as $ticket) {
                        if ($ticket['ticketSlug'] == $request->generalTicketSlug ) {
                            $tickets_arr['ticket'][] = $ticket;
                        } elseif ($ticket['ticketSlug'] === $request->ticketSlug) {
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

                $ticketAddon = new GeneralTicketCabana;
                $ticketAddon->auth_code  = Helper::GeneralSiteSettings('auth_code_en');
                $ticketAddon->generalTicketType  = $tickets_arr['ticket'][0]['ticketType'];
                $ticketAddon->generalTicketSlug  = $tickets_arr['ticket'][0]['ticketSlug'];
                $ticketAddon->venueId = $tickets_arr['ticket_addon'][0]['venueId'];
                $ticketAddon->ticketType  = $tickets_arr['ticket_addon'][0]['ticketType'];
                $ticketAddon->ticketSlug = $tickets_arr['ticket_addon'][0]['ticketSlug'];
                $ticketAddon->ticketCategory = $tickets_arr['ticket_addon'][0]['ticketCategory'];
                $ticketAddon->price = $tickets_arr['ticket_addon'][0]['price'];
                $ticketAddon->description = $request->description;
                $ticketAddon->save();
                

                if(count($uploadedFileNames) > 0){
                    for($i=0;$i<count($uploadedFileNames);$i++){
                        $media = new Media;
                        $media->module  = 'general_ticket_cabana';
                        $media->module_id = $ticketAddon->id;
                        $media->filename  = $uploadedFileNames[$i];
                        $media->original_name = $uploadedFileNames[$i];
                        $media->file_url = $this->uploadPath.$uploadedFileNames[$i];
                        $media->mime_type = '';
                        $media->file_type  = 'image';
                        $media->save();
                    }
                }

                return redirect()->action('Dashboard\GeneralTicketCabanaController@index')->with('doneMessage', __('backend.addDone'));
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
            $ticket_addon = GeneralTicketCabana::with(['media_slider'])->where('slug',$slug)->where('auth_code',$authCode)->first();
            return view("dashboard.general_ticket_cabana.edit", compact("ticket_addon", "GeneralWebmasterSections"));

        } catch (\Exception $e) {
            dd('Exception: ' . $e->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
       $authCode = Helper::GeneralSiteSettings('auth_code_en');
       $ticketAddon = GeneralTicketCabana::where('slug',$id)->where('auth_code',$authCode)->first();
       if (!empty($ticketAddon)) {
            $this->validate($request, [
                'description' => 'required',
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
            
            $ticketAddon->description = $request->description;
            $ticketAddon->save();
            if(count($uploadedFileNames) > 0){
                for($i=0;$i<count($uploadedFileNames);$i++){
                    $media = new Media;
                    $media->module  = 'general_ticket_cabana';
                    $media->module_id = $ticketAddon->id;
                    $media->filename  = $uploadedFileNames[$i];
                    $media->original_name = $uploadedFileNames[$i];
                    $media->file_url = $this->uploadPath.$uploadedFileNames[$i];
                    $media->mime_type = '';
                    $media->file_type  = 'image';
                    $media->save();
                }
            }
            return redirect()->action('Dashboard\GeneralTicketCabanaController@edit', [$id])->with('doneMessage',
                __('backend.saveDone'));

       }else{
            return redirect()->action('Dashboard\GeneralTicketCabanaController@index');
       }
    }

    public function seo(Request $request, $webmasterId, $id)
    {
        
    }

    public function destroy($id = 0)
    {
        //
        $authCode = Helper::GeneralSiteSettings('auth_code_en');
        $ticketAddon = GeneralTicketCabana::with(['media_slider'])->where('id',$id)->where('auth_code',$authCode)->first();
        if (!empty($ticketAddon)) {
            GeneralTicketCabana::where('id',$id)->where('auth_code',$authCode)->delete();
            return redirect()->action('Dashboard\GeneralTicketCabanaController@index')->with('doneMessage',
                __('backend.deleteDone'));
        } else {
            return redirect()->action('Dashboard\GeneralTicketCabanaController@index');
        }
        
    }

    public function updateAll(Request $request, $webmasterId)
    {
       
    }


}
