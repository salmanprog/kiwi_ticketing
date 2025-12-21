<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\Models\Section;
use App\Models\FrontGate;
use App\Models\WebmasterSection;
use App\Models\Media;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Helpers\ApiHelper;
use Auth;
use File;
use Helper;
use Illuminate\Http\Request;
use Redirect;
use Illuminate\Support\Facades\Http;
use Carbon\Carbon;



class FrontGateController extends Controller
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
        $cabana_packages = FrontGate::where('auth_code', $authCode)->get();
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
        return view("dashboard.frontgate.list", compact("paginated", "GeneralWebmasterSections"));
    }

    public function getData(Request $request)
    {
        $authCode = Helper::GeneralSiteSettings('auth_code_en');
        $date = Carbon::today()->toDateString();
        $query = FrontGate::where('auth_code', $authCode);
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
                'venueId' => $external['venueId'],
                'check' => '<label class="ui-check m-a-0">
                                <input type="checkbox" name="ids[]" value="' . $row->id . '"><i></i>
                                <input type="hidden" name="row_ids[]" value="' . $row->id . '" class="form-control row_no">
                            </label>',
                'ticketType' => '<a class="dropdown-item" href="' . route('cabanaEdit', $row->slug) . '">'.$row->ticketType.'</a>',
                'ticketSlug' => $row->ticketSlug,
                'ticketCategory' => $row->ticketCategory,
                'price' => '$' . number_format($external['price'], 2),
                'addon' => '<div class="text-center">'.count($row->cabana_addon).'</div>',
                'featured' => '<div class="text-center"><i class="fa ' . ($row->is_featured ? 'fa-check text-success' : 'fa-times text-danger') . ' inline"></i></div>',
                'status' => '<div class="text-center"><i class="fa ' . ($row->status ? 'fa-check text-success' : 'fa-times text-danger') . ' inline"></i></div>',
                'created_by' => $row->createdBy->name,
                'updated_by' => $row->updatedBy->name ?? 'N/A',
                'updated_at' => $row->updated_at->format('Y-m-d'),
                'options' => '<div class="dropdown">
                                <button type="button" class="btn btn-sm light dk dropdown-toggle" data-toggle="dropdown">
                                    <i class="material-icons">&#xe5d4;</i> Options
                                </button>
                                <div class="dropdown-menu pull-right">
                                    <a class="dropdown-item" href="' . route('cabanaEdit', $row->slug) . '">
                                        <i class="material-icons">&#xe3c9;</i> Edit
                                    </a>
                                    <a class="dropdown-item"
                                        href="' . Helper::GeneralSiteSettings('site_url') . '/cabana' . '"
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

    }

    public function store(Request $request)
    {
       
    }

    public function clone($webmasterId, $id)
    {

    }

    public function edit($slug)
    {

    }

    public function seo(Request $request, $webmasterId, $id)
    {

    }

    public function destroy($ticketSlug = 0)
    {
        
    }

    public function updateAll(Request $request, $webmasterId)
    {

    }


}
