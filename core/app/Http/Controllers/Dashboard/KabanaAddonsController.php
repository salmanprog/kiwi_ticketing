<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\Models\Section;
use App\Models\Topic;
use App\Models\Cabana;
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



class KabanaAddonsController extends Controller
{
    private $uploadPath = "uploads/sections/";

    public function __construct()
    {
        $this->middleware('auth');

    }

    public function index()
    {
        
    }

    public function cabanAddon()
    {
        
    }

    public function create($webmasterId)
    {
        
    }

    public function store(Request $request)
    {
        $params = $request->all();
        $cabanaSlug = $params['cabanaSlug'];
        $baseUrl = Helper::GeneralSiteSettings('external_api_link_en');
        $authCode = Helper::GeneralSiteSettings('auth_code_en');
        $date = Carbon::today()->toDateString();
        $cabanaResponse = Http::get($baseUrl . '/Pricing/GetAllProductPrice?authcode=' . $authCode . '&date=' . $date);
        if (isset($params['ticket']) && count($params['ticket']) > 0) {
            $cabana = CabanaAddon::where('cabanaSlug', $cabanaSlug)->delete();
            if ($cabanaResponse->successful()) {
                $apiData = $cabanaResponse->json();
                $tickets = $apiData['getAllProductPrice']['data'] ?? [];
                $ticketMap = [];
                foreach ($tickets as $ticket) {
                    $ticketMap[$ticket['ticketSlug']] = $ticket;
                }

                foreach ($params['ticket'] as $ticketSlug) {
                    if (isset($ticketMap[$ticketSlug])) {
                        $matchedTicket = $ticketMap[$ticketSlug];
                        $cabana = Cabana::where('ticketSlug', $ticketSlug)->first();
                        $cabanaAddon = new CabanaAddon;
                        $cabanaAddon->cabanaSlug = $cabanaSlug;
                        $cabanaAddon->venueId = $matchedTicket['venueId'];
                        $cabanaAddon->ticketType = $matchedTicket['ticketType'];
                        $cabanaAddon->ticketSlug = $matchedTicket['ticketSlug'];
                        $cabanaAddon->ticketCategory = $matchedTicket['ticketCategory'];
                        $cabanaAddon->price = $matchedTicket['price'];
                        $cabanaAddon->save();
                    }
                }
            }
        }
        // General for all pages
        $GeneralWebmasterSections = WebmasterSection::where('status', '=', '1')->orderby('row_no', 'asc')->get();
        try {
            
            $getFeaturedCabana = Cabana::where('featured', '=', '1')->orderby('id', 'asc')->get();
            // Paginate
            $perPage = 10;
            $currentPage = LengthAwarePaginator::resolveCurrentPage();
            $collection = collect($getFeaturedCabana);
            $paginated = new LengthAwarePaginator(
                $collection->forPage($currentPage, $perPage),
                $collection->count(),
                $perPage,
                $currentPage,
                ['path' => url()->current(), 'query' => request()->query()]
            );

            return redirect()->back();

        } catch (\Exception $e) {
            dd('Exception: ' . $e->getMessage());
        }
    }

    public function clone($webmasterId, $id)
    {
        
    }

    public function edit($id)
    {
       
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
