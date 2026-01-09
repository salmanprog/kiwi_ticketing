<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\Models\Waiver;
use App\Models\WebmasterSection;
use App\Models\Media;
use App\Models\CouponsTickets;
use Illuminate\Pagination\LengthAwarePaginator;
use Auth;
use File;
use Helper;
use Illuminate\Http\Request;
use Redirect;
use Illuminate\Support\Facades\Http;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;



class WaiverController extends Controller
{
    private $uploadPath = "uploads/sections/";

    public function __construct()
    {
        $this->middleware('auth');

    }

    public function index()
    {
        // General for all pages
        $GeneralWebmasterSections = WebmasterSection::where('status', '=', '1')->orderby('row_no', 'asc')->get();
        $coupons = Waiver::get();
         // Paginate
        $perPage = 10;
        $currentPage = LengthAwarePaginator::resolveCurrentPage();
        $collection = collect($coupons);
        $paginated = new LengthAwarePaginator(
            $collection->forPage($currentPage, $perPage),
            $collection->count(),
            $perPage,
            $currentPage,
            ['path' => url()->current(), 'query' => request()->query()]
        );
        return view("dashboard.waiver.list", compact("paginated", "GeneralWebmasterSections"));
    }

    public function getData(Request $request)
    {
        $authCode = Helper::GeneralSiteSettings('auth_code_en');
        $query = Waiver::query();
        if ($request->has('search') && $request->search['value'] != '') {
            $search = $request->search['value'];
            $query->where(function ($q) use ($search) {
                $q->where('order_id', 'like', "%{$search}%")
                  ->orWhere('qr_code', 'like', "%{$search}%")
                  ->orWhere('waiver_type', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('name', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
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
                'order_id' => strtoupper($row->order_id),
                'qr_code' => $row->qr_code,
                'waiver_type' => $row->waiver_type,
                'email' => $row->email,
                'name' => $row->name,
                'phone' => $row->phone,
                'status' => '<div class="text-center"><i class="fa ' . ($row->status ? 'fa-check text-success' : 'fa-times text-danger') . ' inline"></i></div>',
                'options' => '<div class="dropdown">
                            <button type="button" class="btn btn-sm light dk dropdown-toggle" data-toggle="dropdown">
                                <i class="material-icons">&#xe5d4;</i> Options
                            </button>
                            <div class="dropdown-menu pull-right">
                                <a class="dropdown-item" target="_blank" href="' . route('waiver.preview', $row->slug) . '">
                                    <i class="material-icons">&#xe8f4;</i> Preview
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

    public function update(Request $request, $id)
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

    public function preview($slug)
    {
        $waiver = Waiver::where('slug', $slug)->firstOrFail();

        $pdf = Pdf::loadView('dashboard.waiver.preview', compact('waiver'))
          ->setPaper('A4', 'portrait')
          ->setOptions([
              'isRemoteEnabled' => true,
          ]);
        return $pdf->stream('waiver-' . $waiver->order_id . '.pdf');
    }
}
