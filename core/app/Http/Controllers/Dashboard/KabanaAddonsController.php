<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\Models\Section;
use App\Models\Topic;
use App\Models\Cabana;
use App\Models\CabanaAddon;
use App\Models\CabanaPackages;
use App\Models\WebmasterSection;
use Illuminate\Pagination\LengthAwarePaginator;
use Auth;
use File;
use Helper;
use App\Helpers\ApiHelper;
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

    public function getData(Request $request)
    {
        $authCode = Helper::GeneralSiteSettings('auth_code_en');
        $date = Carbon::today()->toDateString();
        $query = CabanaPackages::with(['media_slider'])->where('auth_code', $authCode);
        if ($request->has('search') && $request->search['value'] != '') {
            $search = $request->search['value'];
            $query->where(function ($q) use ($search) {
                $q->where('ticketType', 'like', "%{$search}%")
                ->orWhere('ticketSlug', 'like', "%{$search}%")
                ->orWhere('ticketCategory', 'like', "%{$search}%")
                ->orWhere('venueId', 'like', "%{$search}%");
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
        $externalProducts = ApiHelper::getProductByCategory($data,'Cabanas',$date);
        $externalMap = collect($externalProducts)->keyBy('ticketSlug');
        $result = [];
        foreach ($data as $row) {
            $external = $externalMap[$row->ticketSlug] ?? null;
            $result[] = [
                'id' => $row->id,
                'venueId' => $row->venueId,
                'check' => '<label class="ui-check m-a-0">
                                <input type="checkbox" name="ids[]" value="' . $row->id . '"><i></i>
                                <input type="hidden" name="row_ids[]" value="' . $row->id . '" class="form-control row_no">
                            </label>',
                'ticketType' => '<a class="dropdown-item" href="' . route('kabanaaddonEdit', $row->ticketSlug) . '">'.$row->ticketType.'</a>',
                'ticketSlug' => $row->ticketSlug,
                'ticketCategory' => $row->ticketCategory,
                'price' => '$' . number_format($external['price'], 2),
                
                'featured' => '<div class="text-center"><i class="fa ' . ($row->is_featured ? 'fa-check text-success' : 'fa-times text-danger') . ' inline"></i></div>',
                'status' => '<div class="text-center"><i class="fa ' . ($row->status ? 'fa-check text-success' : 'fa-times text-danger') . ' inline"></i></div>',
                'options' => '<div class="dropdown">
                                <button type="button" class="btn btn-sm light dk dropdown-toggle" data-toggle="dropdown">
                                    <i class="material-icons">&#xe5d4;</i> Options
                                </button>
                                <div class="dropdown-menu pull-right">
                                    <a class="dropdown-item" href="' . route('kabanaaddonEdit', $row->ticketSlug) . '">
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
