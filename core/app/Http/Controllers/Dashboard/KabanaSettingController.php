<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\Models\Section;
use App\Models\Topic;
use App\Models\CabanaPackages;
use App\Models\CabanaAddon;
use App\Models\WebmasterSection;
use Illuminate\Pagination\LengthAwarePaginator;
use Auth;
use File;
use Helper;
use Illuminate\Http\Request;
use Redirect;
use Illuminate\Support\Facades\Http;
use Carbon\Carbon;



class KabanaSettingController extends Controller
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
            if ($response->successful()) {
            $apiData = $response->json();
            $tickets = $apiData['getAllProductPrice']['data'] ?? [];
            $tickets = array_map('mapTicketName', $tickets);
            $tickets = collect($tickets)->map(function ($item) {
                $get_featured = Cabana::where('ticketSlug', $item['ticketSlug'])->exists();
                $item['is_featured'] = $get_featured ? 1 : 0;
                return $item;
            });
            $fillter_arr = [];
            if(count($tickets) > 0){
                for($i=0;$i<count($tickets);$i++){
                    if($tickets[$i]['ticketCategory'] == 'Cabanas'){
                        array_push($fillter_arr,$tickets[$i]);
                    }
                }
            }
            // Paginate
            $perPage = 10;
            $currentPage = LengthAwarePaginator::resolveCurrentPage();
            $collection = collect($fillter_arr);

            $paginated = new LengthAwarePaginator(
                $collection->forPage($currentPage, $perPage),
                $collection->count(),
                $perPage,
                $currentPage,
                ['path' => url()->current(), 'query' => request()->query()]
            );

            return view("dashboard.kabanasetting.list", compact("paginated", "GeneralWebmasterSections"));
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

    public function cabanAddon()
    {
        // $baseUrl = Helper::GeneralSiteSettings('external_api_link_en');
        // $authCode = Helper::GeneralSiteSettings('auth_code_en');
        // $date = Carbon::today()->toDateString();
        // // General for all pages
        // $GeneralWebmasterSections = WebmasterSection::where('status', '=', '1')->orderby('row_no', 'asc')->get();
        // try {
            
        //     $getFeaturedCabana = CabanaPackages::where('is_featured', '=', '1')->where('status', '=', '1')->where('auth_code', $authCode)->orderby('id', 'asc')->get();
        //     // Paginate
        //     $perPage = 10;
        //     $currentPage = LengthAwarePaginator::resolveCurrentPage();
        //     $collection = collect($getFeaturedCabana);
        //     $paginated = new LengthAwarePaginator(
        //         $collection->forPage($currentPage, $perPage),
        //         $collection->count(),
        //         $perPage,
        //         $currentPage,
        //         ['path' => url()->current(), 'query' => request()->query()]
        //     );
        // } catch (\Exception $e) {
        //     // Handle connection or request exceptions
        //     dd('Exception: ' . $e->getMessage());
        // }
        $baseUrl = Helper::GeneralSiteSettings('external_api_link_en');
        $authCode = Helper::GeneralSiteSettings('auth_code_en');
        $date = Carbon::today()->toDateString();
        // General for all pages
        $GeneralWebmasterSections = WebmasterSection::where('status', '=', '1')->orderby('row_no', 'asc')->get();
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
            $tickets_arr = $apiData['getAllProductPrice']['data'] ?? [];
            $tickets_arr = array_map('mapTicketName', $tickets_arr);
            $tickets = [];
            if(count($tickets_arr) > 0){
                for($i=0;$i<count($tickets_arr);$i++){
                    if($tickets_arr[$i]['venueId'] == 0 && $tickets_arr[$i]['ticketCategory'] !== 'Season Passes' && $tickets_arr[$i]['ticketCategory'] !== 'Anyday' ){
                        array_push($tickets,$tickets_arr[$i]);
                    }
                }
            }
        }
        return view("dashboard.kabanaddon.list", compact("tickets", "GeneralWebmasterSections"));

        
        
        
    }

    public function create($webmasterId)
    {
        
    }

    public function store(Request $request, $webmasterId)
    {
        
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
        //$cabana = CabanaPackages::where('ticketSlug', $id)->where('is_featured', '=', '1')->where('status', '=', '1')->where('auth_code', $authCode)->first();
        //$cabana_addon = CabanaAddon::select('ticketSlug')->where('cabanaSlug', $id)->get()->toArray();
        // $response = Http::get($baseUrl.'/Pricing/GetAllProductPrice?authcode='.$authCode.'&date='.$date);
        // if ($response->successful()) {
        //     $apiData = $response->json();
        //     $tickets_arr = $apiData['getAllProductPrice']['data'] ?? [];
        //     $tickets = [];
        //     if(count($tickets_arr) > 0){
        //         for($i=0;$i<count($tickets_arr);$i++){
        //             if($tickets_arr[$i]['venueId'] == 0 && $tickets_arr[$i]['ticketCategory'] !== 'Season Passes' && $tickets_arr[$i]['ticketCategory'] !== 'Anyday' ){
        //                 array_push($tickets,$tickets_arr[$i]);
        //             }
        //         }
        //     }
        // }
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
            $tickets_arr = $apiData['getAllProductPrice']['data'] ?? [];
            $tickets_arr = array_map('mapTicketName', $tickets_arr);
            $tickets = [];
            if(count($tickets_arr) > 0){
                for($i=0;$i<count($tickets_arr);$i++){
                    if($tickets_arr[$i]['ticketSlug'] == $id ){
                        array_push($tickets,$tickets_arr[$i]);
                    }
                }
            }
        }
        $cabanas = CabanaPackages::where('is_featured', '=', '1')->where('status', '=', '1')->where('auth_code', $authCode)->get();
        $cabana_addon = CabanaAddon::select('cabanaSlug')->where('ticketSlug', $tickets[0]['ticketSlug'])->get()->toArray();
        return view("dashboard.kabanaddon.edit", compact("cabanas","tickets","cabana_addon","GeneralWebmasterSections"));
    }

    public function update(Request $request, $webmasterId, $id)
    {
       
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
