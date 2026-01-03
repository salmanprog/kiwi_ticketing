<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\Models\Section;
use App\Models\Topic;
use App\Models\BirthdayPackages;
use App\Models\BirthdayAddon;
use App\Models\WebmasterSection;
use Illuminate\Pagination\LengthAwarePaginator;
use Auth;
use File;
use Helper;
use Illuminate\Http\Request;
use Redirect;
use Illuminate\Support\Facades\Http;
use Carbon\Carbon;



class BirthdayAddonsController extends Controller
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
            
            $getFeaturedCabana = BirthdayPackages::with(['cabanas','addons','media_slider','media_cover','createdBy','updatedBy'])->where('status', '=', '1')->orderby('id', 'asc')->get();
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

            return view("dashboard.birthdayaddon.list", compact("paginated", "GeneralWebmasterSections"));

        } catch (\Exception $e) {
            // Handle connection or request exceptions
            dd('Exception: ' . $e->getMessage());
        }
    }

    public function getData(Request $request)
    {
        $authCode = Helper::GeneralSiteSettings('auth_code_en');
        $query = BirthdayPackages::with(['cabanas','addons','media_slider','media_cover','createdBy','updatedBy'])->where('status', '=', '1');
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
            $get_bday_addon = BirthdayAddon::with(['createdBy','updatedBy'])->where('birthday_slug', $row->slug)->first();
            $result[] = [
                'id' => $row->id,
                'title' => '<a class="dropdown-item" href="' . route('birthdayaddonEdit', $row->slug) . '">'.$row->title.'</a>',
                'slug' => $row->slug,
                'price' => '$' . number_format($row->price, 2),
                'addons' => '<div class="text-center">'.count($row->addons).'</div>',
                'status' => '<div class="text-center"><i class="fa ' . ($row->status ? 'fa-check text-success' : 'fa-times text-danger') . ' inline"></i></div>',
                'created_by' => $row->createdBy->name,
                'updated_by' => $get_bday_addon->updatedBy->name ?? 'N/A',
                'updated_at' => $row->updated_at->format('Y-m-d'),
                'options' => '<div class="dropdown">
                                <button type="button" class="btn btn-sm light dk dropdown-toggle" data-toggle="dropdown">
                                    <i class="material-icons">&#xe5d4;</i> Options
                                </button>
                                <div class="dropdown-menu pull-right">
                                    <a class="dropdown-item" href="' . route('birthdayaddonEdit', $row->slug) . '">
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

    public function cabanAddon()
    {
        
    }

    public function create($webmasterId)
    {
        
    }

    public function store(Request $request)
    {
        // $params = $request->all();
        // $cabanaSlug = $params['cabanaSlug'];
        // $baseUrl = Helper::GeneralSiteSettings('external_api_link_en');
        // $authCode = Helper::GeneralSiteSettings('auth_code_en');
        // $date = Carbon::today()->toDateString();
        // $cabanaResponse = Http::get($baseUrl . '/Pricing/GetAllProductPrice?authcode=' . $authCode . '&date=' . $date);
        // if (isset($params['ticket']) && count($params['ticket']) > 0) {
        //     $cabana = BirthdayAddon::where('birthday_slug', $cabanaSlug)->delete();
        //     if ($cabanaResponse->successful()) {
        //         $apiData = $cabanaResponse->json();
        //         $tickets = $apiData['getAllProductPrice']['data'] ?? [];
        //         $ticketMap = [];
        //         foreach ($tickets as $ticket) {
        //             $ticketMap[$ticket['ticketSlug']] = $ticket;
        //         }

        //         foreach ($params['ticket'] as $ticketSlug) {
        //             if (isset($ticketMap[$ticketSlug])) {
        //                 $matchedTicket = $ticketMap[$ticketSlug];
        //                 //$cabana = BirthdayPackages::where('slug', $ticketSlug)->first();
        //                 $cabanaAddon = new BirthdayAddon;
        //                 $cabanaAddon->birthday_slug = $cabanaSlug;
        //                 $cabanaAddon->venueId = $matchedTicket['venueId'];
        //                 $cabanaAddon->ticketType = $matchedTicket['ticketType'];
        //                 $cabanaAddon->ticketSlug = $matchedTicket['ticketSlug'];
        //                 $cabanaAddon->ticketCategory = $matchedTicket['ticketCategory'];
        //                 $cabanaAddon->price = $matchedTicket['price'];
        //                 $cabanaAddon->updated_by = Auth::user()->id;
        //                 $cabanaAddon->updated_at = now();
        //                 $cabanaAddon->save();
        //             }
        //         }
        //     }
        // }
        // // General for all pages
        // $GeneralWebmasterSections = WebmasterSection::where('status', '=', '1')->orderby('row_no', 'asc')->get();
        // try {
            
        //     $getFeaturedCabana = BirthdayPackages::where('status', '=', '1')->orderby('id', 'asc')->get();
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

        //     return redirect()->back();

        // } catch (\Exception $e) {
        //     dd('Exception: ' . $e->getMessage());
        // }

        $params = $request->all();
        $cabanaSlug = $params['cabanaSlug'];

        // Remove existing addons for this package
        BirthdayAddon::where('birthday_slug', $cabanaSlug)->delete();

        if (isset($params['ticket']) && count($params['ticket']) > 0) {

            // Map user-entered quantities and prices
            $quantities = $params['quantity'] ?? [];
            $prices = $params['price'] ?? [];

            // Get API data
            $baseUrl = Helper::GeneralSiteSettings('external_api_link_en');
            $authCode = Helper::GeneralSiteSettings('auth_code_en');
            $date = Carbon::today()->toDateString();
            $cabanaResponse = Http::get($baseUrl . '/Pricing/GetAllProductPrice?authcode=' . $authCode . '&date=' . $date);

            if ($cabanaResponse->successful()) {
                $apiData = $cabanaResponse->json();
                $tickets = $apiData['getAllProductPrice']['data'] ?? [];

                // Map tickets by slug for quick lookup
                $ticketMap = [];
                foreach ($tickets as $ticket) {
                    $ticketMap[$ticket['ticketSlug']] = $ticket;
                }

                // Save each selected addon with user-entered quantity and price
                foreach ($params['ticket'] as $ticketSlug) {
                    if (isset($ticketMap[$ticketSlug])) {
                        $matchedTicket = $ticketMap[$ticketSlug];

                        $cabanaAddon = new BirthdayAddon;
                        $cabanaAddon->birthday_slug = $cabanaSlug;
                        $cabanaAddon->venueId = $matchedTicket['venueId'];
                        $cabanaAddon->ticketType = $matchedTicket['ticketType'];
                        $cabanaAddon->ticketSlug = $matchedTicket['ticketSlug'];
                        $cabanaAddon->ticketCategory = $matchedTicket['ticketCategory'];

                        // ✅ Use user-entered price and quantity
                        $cabanaAddon->price = $prices[$ticketSlug] ?? $matchedTicket['price'];
                        $cabanaAddon->quantity = $quantities[$ticketSlug] ?? 1;

                        $cabanaAddon->updated_by = Auth::user()->id;
                        $cabanaAddon->updated_at = now();
                        $cabanaAddon->save();
                    }
                }
            }
        }

        return redirect()->back();
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
        $cabana = BirthdayPackages::where('slug', $id)->first();
        $cabana_addon = BirthdayAddon::select('ticketSlug', 'price', 'quantity')->where('birthday_slug', $id)->get()->toArray();
        $response = Http::get($baseUrl.'/Pricing/GetAllProductPrice?authcode='.$authCode.'&date='.$date);
        if ($response->successful()) {
            $apiData = $response->json();
            $tickets_arr = $apiData['getAllProductPrice']['data'] ?? [];
            $tickets = [];
            if(count($tickets_arr) > 0){
                for($i=0;$i<count($tickets_arr);$i++){
                    //if($tickets_arr[$i]['venueId'] == 0 && $tickets_arr[$i]['ticketCategory'] !== 'Season Passes' && $tickets_arr[$i]['ticketCategory'] !== 'Anyday' ){
                    if($tickets_arr[$i]['venueId'] == 0 && $tickets_arr[$i]['ticketCategory'] !== 'Season Passes' && $tickets_arr[$i]['ticketCategory'] !== 'Anyday'){
                        array_push($tickets,$tickets_arr[$i]);
                    }elseif($tickets_arr[$i]['ticketCategory'] == 'Cabanas'){
                        array_push($tickets,$tickets_arr[$i]);
                    }
                }
            }
        }
        return view("dashboard.birthdayaddon.edit", compact("cabana", "tickets","cabana_addon" ,"GeneralWebmasterSections"));
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
