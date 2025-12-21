<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\Models\Section;
use App\Models\Topic;
use App\Models\GeneralTickets;
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



class GeneralTicketController extends Controller
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
            //$response = Http::get($baseUrl.'/Pricing/GetAllProductPrice?authcode='.$authCode.'&date='.$date);
            $filterParams = [];
            if (filter_var(env('API_FILTER'), FILTER_VALIDATE_BOOLEAN)) {
                parse_str(env('API_FILTER_PARAMS'), $filterParams);
            }
            $response = Http::get(
                $baseUrl . '/Pricing/GetAllProductPrice',
                [
                    'authcode' => $authCode,
                    'date' => $date,
                    ...$filterParams,
                ]
            );
            $getTicketGeneral = GeneralTickets::with(['media_slider','addons'])->where('auth_code', $authCode)->get();

            if ($response->successful()) {
                $apiData = $response->json();
                $tickets = $apiData['getAllProductPrice']['data'] ?? [];
                $dbSlugs = $getTicketGeneral->pluck('ticketSlug')->toArray();
                $matchedTickets = collect($tickets)->filter(function ($ticket) use ($dbSlugs) {
                    return in_array($ticket['ticketSlug'], $dbSlugs);
                })->values();
                $matchedTickets = $getTicketGeneral->filter(function ($model) use ($matchedTickets) {
                    return $matchedTickets->contains('ticketSlug', $model->ticketSlug);
                })->values();
                $perPage = 10;
                $currentPage = LengthAwarePaginator::resolveCurrentPage();
                $paginated = new LengthAwarePaginator(
                    $matchedTickets->forPage($currentPage, $perPage),
                    $matchedTickets->count(),
                    $perPage,
                    $currentPage,
                    ['path' => url()->current(), 'query' => request()->query()]
                );
                return view("dashboard.generalticket.list", compact("paginated", "GeneralWebmasterSections"));
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

    public function create()
    {
        // General for all pages
        $GeneralWebmasterSections = WebmasterSection::where('status', '=', '1')->orderby('row_no', 'asc')->get();
        // General END

        $baseUrl = Helper::GeneralSiteSettings('external_api_link_en');
        $authCode = Helper::GeneralSiteSettings('auth_code_en');
        $date = Carbon::today()->toDateString();

        try {
            //$response = Http::get($baseUrl.'/Pricing/GetAllProductPrice?authcode='.$authCode.'&date='.$date);
            $filterParams = [];
            if (filter_var(env('API_FILTER'), FILTER_VALIDATE_BOOLEAN)) {
                parse_str(env('API_FILTER_PARAMS'), $filterParams);
            }
            $response = Http::get(
                $baseUrl . '/Pricing/GetAllProductPrice',
                [
                    'authcode' => $authCode,
                    'date' => $date,
                    ...$filterParams,
                ]
            );
            if ($response->successful()) {
            $apiData = $response->json();
            $tickets = $apiData['getAllProductPrice']['data'] ?? [];
            $fillter_arr = [];
            if(count($tickets) > 0){
                for($i=0;$i<count($tickets);$i++){
                    if($tickets[$i]['venueId'] != 0){
                        array_push($fillter_arr,$tickets[$i]);
                    }
                }
            }
            // Paginate
            $perPage = 100;
            $currentPage = LengthAwarePaginator::resolveCurrentPage();
            $general_products = collect($fillter_arr);

            return view("dashboard.generalticket.create", compact("general_products", "GeneralWebmasterSections"));
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
            'description' => 'required',
        ]);
        $formFileName = "photo";
        $uploadedFileNames = [];
        $baseUrl = Helper::GeneralSiteSettings('external_api_link_en');
        $authCode = Helper::GeneralSiteSettings('auth_code_en');
        $date = Carbon::today()->toDateString();

        try {
            $ticketGeneralCheck = GeneralTickets::where('ticketSlug',$request->ticketSlug)->where('auth_code',$authCode)->first();

            if (!empty($ticketGeneralCheck)) {
                return redirect()->action('Dashboard\GeneralTicketController@create')->with('errorMessage', 'The primary product already exists.');
            }

            $ticketGeneralCount = GeneralTickets::where('auth_code',$authCode)->count();

            if ($ticketGeneralCount > 3) {
                return redirect()->action('Dashboard\GeneralTicketController@create')->with('errorMessage', 'You can add only 4 primary products.');
            }

            //$response = Http::get($baseUrl.'/Pricing/GetAllProductPrice?authcode='.$authCode.'&date='.$date);
            $filterParams = [];
            if (filter_var(env('API_FILTER'), FILTER_VALIDATE_BOOLEAN)) {
                parse_str(env('API_FILTER_PARAMS'), $filterParams);
            }
            $response = Http::get(
                $baseUrl . '/Pricing/GetAllProductPrice',
                [
                    'authcode' => $authCode,
                    'date' => $date,
                    ...$filterParams,
                ]
            );
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

                $generalTickets = new GeneralTickets;
                $generalTickets->auth_code  = Helper::GeneralSiteSettings('auth_code_en');
                $generalTickets->venueId = $tickets_arr[0]['venueId'];
                $generalTickets->ticketType  = $tickets_arr[0]['ticketType'];
                $generalTickets->ticketSlug = $tickets_arr[0]['ticketSlug'];
                $generalTickets->ticketCategory = $tickets_arr[0]['ticketCategory'];
                $generalTickets->price = $tickets_arr[0]['price'];
                $generalTickets->description = $request->description;
                $generalTickets->save();
                

                if(count($uploadedFileNames) > 0){
                    for($i=0;$i<count($uploadedFileNames);$i++){
                        $media = new Media;
                        $media->module  = 'general_ticket';
                        $media->module_id = $generalTickets->id;
                        $media->filename  = $uploadedFileNames[$i];
                        $media->original_name = $uploadedFileNames[$i];
                        $media->file_url = $this->uploadPath.$uploadedFileNames[$i];
                        $media->mime_type = '';
                        $media->file_type  = 'image';
                        $media->save();
                    }
                }

                 return redirect()->action('Dashboard\GeneralTicketController@index')->with('doneMessage', __('backend.addDone'));
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
        try {
            //$response = Http::get($baseUrl.'/Pricing/GetAllProductPrice?authcode='.$authCode.'&date='.$date);
            $filterParams = [];
            if (filter_var(env('API_FILTER'), FILTER_VALIDATE_BOOLEAN)) {
                parse_str(env('API_FILTER_PARAMS'), $filterParams);
            }
            $response = Http::get(
                $baseUrl . '/Pricing/GetAllProductPrice',
                [
                    'authcode' => $authCode,
                    'date' => $date,
                    ...$filterParams,
                ]
            );
            if ($response->successful()) {
            $apiData = $response->json();
            $tickets = $apiData['getAllProductPrice']['data'] ?? [];
            $fillter_arr = [];
            if(count($tickets) > 0){
                for($i=0;$i<count($tickets);$i++){
                    if($tickets[$i]['ticketSlug'] == $slug){
                        array_push($fillter_arr,$tickets[$i]);
                    }
                }
            }
            $get_ticket = GeneralTickets::with(['media_slider'])->where('ticketSlug',$slug)->first();
            $general_ticket = $fillter_arr[0];
            return view("dashboard.generalticket.edit", compact("general_ticket","get_ticket", "GeneralWebmasterSections"));
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

    public function update(Request $request, $webmasterId, $id)
    {
       
    }

    public function seo(Request $request, $webmasterId, $id)
    {
        
    }

    public function destroy($ticketSlug = 0)
    {
        $authCode = Helper::GeneralSiteSettings('auth_code_en');
        $ticketAddon = GeneralTickets::where('ticketSlug',$ticketSlug)->where('auth_code',$authCode)->first();
        if (!empty($ticketAddon)) {
            GeneralTickets::where('ticketSlug',$ticketSlug)->where('auth_code',$authCode)->delete();
            return redirect()->action('Dashboard\GeneralTicketController@index')->with('doneMessage',
                __('backend.deleteDone'));
        } else {
            return redirect()->action('Dashboard\GeneralTicketController@index');
        }
    }

    public function updateAll(Request $request, $webmasterId)
    {
       
    }


}
