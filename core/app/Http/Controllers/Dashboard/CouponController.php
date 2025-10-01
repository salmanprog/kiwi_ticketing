<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\Models\Section;
use App\Models\Coupons;
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



class CouponController extends Controller
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
        $coupons = Coupons::get();
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
        return view("dashboard.coupons.list", compact("paginated", "GeneralWebmasterSections"));
    }

    public function getData(Request $request)
    {
        $authCode = Helper::GeneralSiteSettings('auth_code_en');
        $query = Coupons::query();
        if ($request->has('search') && $request->search['value'] != '') {
            $search = $request->search['value'];
            $query->where(function ($q) use ($search) {
                $q->where('package_type', 'like', "%{$search}%")
                  ->orWhere('title', 'like', "%{$search}%")
                  ->orWhere('coupon_code', 'like', "%{$search}%");
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
                'check' => '<label class="ui-check m-a-0">
                                <input type="checkbox" name="ids[]" value="' . $row->id . '"><i></i>
                                <input type="hidden" name="row_ids[]" value="' . $row->id . '" class="form-control row_no">
                            </label>',
                'title' => '<a class="dropdown-item" href="' . route('couponEdit', $row->slug) . '">'.$row->title.'</a>',
                'slug' => $row->slug,
                'package_type' => $row->package_type,
                'coupon_code' => $row->coupon_code,
                // 'discount' => '$' . number_format($row->discount, 2),
                // 'discount_type' => $row->discount_type,
                // 'coupon_total_limit' => $row->coupon_total_limit,
                // 'coupon_use_limit' => $row->coupon_use_limit,
                'status' => '<div class="text-center"><i class="fa ' . ($row->status ? 'fa-check text-success' : 'fa-times text-danger') . ' inline"></i></div>',
                'options' => '<div class="dropdown">
                                <button type="button" class="btn btn-sm light dk dropdown-toggle" data-toggle="dropdown">
                                    <i class="material-icons">&#xe5d4;</i> Options
                                </button>
                                <div class="dropdown-menu pull-right">
                                    <a class="dropdown-item" href="' . route('couponEdit', $row->slug) . '">
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

    public function create()
    {
        // General for all pages
        $GeneralWebmasterSections = WebmasterSection::where('status', '=', '1')->orderby('row_no', 'asc')->get();
        return view("dashboard.coupons.create", compact("GeneralWebmasterSections"));
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'package_type' => 'required',
            'title' => 'required',
            'coupon_code' => 'required',
            'start_date' => 'required',
            'end_date' => 'required',
            'discount' => 'required',
            'discount_type' => 'required',
            'coupon_total_limit' => 'required',
        ]);
        
        $couponsPackages = new Coupons;
        $couponsPackages->auth_code  = Helper::GeneralSiteSettings('auth_code_en');
        $couponsPackages->package_type = $request->package_type;
        $couponsPackages->title  = $request->title;
        $couponsPackages->coupon_code = $request->coupon_code;
        $couponsPackages->start_date = $request->start_date;
        $couponsPackages->end_date = $request->end_date;
        $couponsPackages->discount  = $request->discount;
        $couponsPackages->discount_type = $request->discount_type;
        $couponsPackages->coupon_total_limit = $request->coupon_total_limit;
        $couponsPackages->coupon_use_limit = '0';
        $couponsPackages->status = $request->status;
        $couponsPackages->save();
        
        return redirect()->action('Dashboard\CouponController@index')->with('doneMessage', __('backend.addDone'));
    }

    public function clone($webmasterId, $id)
    {
        
    }

    public function edit($slug)
    {
        $authCode = Helper::GeneralSiteSettings('auth_code_en');
        $GeneralWebmasterSections = WebmasterSection::where('status', '=', '1')->orderby('row_no', 'asc')->get();
        $couponsPackages = Coupons::where('slug',$slug)->first();
        return view("dashboard.coupons.edit", compact("couponsPackages", "GeneralWebmasterSections"));   
    }

    public function update(Request $request, $id)
    {
       $couponsPackages = Coupons::where('slug',$id)->first();
       if (!empty($couponsPackages)) {
            $this->validate($request, [
                'title' => 'required',
                'coupon_code' => 'required',
                'start_date' => 'required',
                'end_date' => 'required',
                'discount' => 'required',
                'discount_type' => 'required',
                'coupon_total_limit' => 'required',
            ]);

            $couponsPackages->title  = $request->title;
            $couponsPackages->coupon_code = $request->coupon_code;
            $couponsPackages->start_date = $request->start_date;
            $couponsPackages->end_date = $request->end_date;
            $couponsPackages->discount  = $request->discount;
            $couponsPackages->discount_type = $request->discount_type;
            $couponsPackages->coupon_total_limit = $request->coupon_total_limit;
            $couponsPackages->status = $request->status;
            $couponsPackages->save();
            
            return redirect()->action('Dashboard\CouponController@edit', [$id])->with('doneMessage',
                __('backend.saveDone'));
       }else{
            return redirect()->action('Dashboard\CouponController@index');
       }
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
