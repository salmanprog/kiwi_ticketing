<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\Models\Section;
use App\Models\Order;
use App\Models\WebmasterSection;
use Illuminate\Pagination\LengthAwarePaginator;
use Auth;
use File;
use Helper;
use App\Helpers\OrdersHelper;
use Illuminate\Http\Request;
use Redirect;
use Illuminate\Support\Facades\Http;
use Carbon\Carbon;



class OrderController extends Controller
{
    private $uploadPath = "uploads/sections/";

    public function __construct()
    {
        $this->middleware('auth');

    }

    public function getCabanaOrders()
    {
        // General for all pages
        $baseUrl = Helper::GeneralSiteSettings('external_api_link_en');
        $authCode = Helper::GeneralSiteSettings('auth_code_en');
        $GeneralWebmasterSections = WebmasterSection::where('status', '=', '1')->orderby('row_no', 'desc')->get();
        $get_cabana_orders = Order::with(['customer','purchases','transaction'])->where('auth_code',$authCode)->where('type','cabana')->get();
        
         // Paginate
        $perPage = 10;
        $currentPage = LengthAwarePaginator::resolveCurrentPage();
        $collection = collect($get_cabana_orders);
        $paginated = new LengthAwarePaginator(
            $collection->forPage($currentPage, $perPage),
            $collection->count(),
            $perPage,
            $currentPage,
            ['path' => url()->current(), 'query' => request()->query()]
        );
        return view("dashboard.kabanaorders.list", compact("paginated", "GeneralWebmasterSections"));
    }

    public function getOrders(Request $request)
    {
        // General for all pages
        $baseUrl = Helper::GeneralSiteSettings('external_api_link_en');
        $authCode = Helper::GeneralSiteSettings('auth_code_en');
        $GeneralWebmasterSections = WebmasterSection::where('status', '=', '1')->orderby('row_no', 'desc')->get();
        $order_type =  OrdersHelper::order_types($request->type);
        $query = Order::with(['customer','purchases','apply_coupon','coupon','transaction',$request->type])->where('auth_code',$authCode)->where('type',$request->type);
        if ($request->has('search') && $request->search['value'] != '') {
            $search = $request->search['value'];

            $query->where(function ($q) use ($search) {
                $q->where('firstName', 'like', "%{$search}%")
                ->orWhere('lastName', 'like', "%{$search}%")
                ->orWhere('slug', 'like', "%{$search}%")
                ->orWhereRaw("DATE_FORMAT(orderDate, '%Y-%m-%d') LIKE ?", ["%{$search}%"])
                ->orWhereRaw("DATE_FORMAT(created_at, '%Y-%m-%d') LIKE ?", ["%{$search}%"])
                ->orWhereHas('cabana', function ($q2) use ($search) {
                    $q2->where('ticketType', 'like', "%{$search}%");
                });
            });
        }

        if ($request->filled('find_q')) {
            $q = $request->find_q;
            $query->where(function ($q2) use ($q) {
                $q2->where('firstName', 'like', "%{$q}%")
                ->orWhere('lastName', 'like', "%{$q}%")
                ->orWhere('email', 'like', "%{$q}%")
                ->orWhere('slug', 'like', "%{$q}%");
            });
        }

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        if ($request->filled('package_id')) {
            $query->where('package_id', $request->package_id);
        }

        if ($request->from_date && $request->to_date) {
            $from = $request->from_date . ' 00:00:00';
            $to = $request->to_date . ' 23:59:59';
            $query->whereBetween('created_at', [$from, $to]);
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
        $totalEarnings = $query->sum('orderTotal');
        $data = $query->offset($start)->limit($limit)->get();

        $result = [];
        foreach ($data as $row) {
            $result[] = [
                'id' => strtoupper($row->slug),
                'check' => '<label class="ui-check m-a-0">
                                <input type="checkbox" name="ids[]" value="' . $row->id . '"><i></i>
                                <input type="hidden" name="row_ids[]" value="' . $row->id . '" class="form-control row_no">
                            </label>',
                'package' => $row->$order_type
                                ? '<a class="dropdown-item" href="' . route($request->route, $row->slug) . '">' . $row->$order_type->ticketType . '</a>'
                                : '<span class="dropdown-item text-muted">N/A</span>',
                'customerName' => $row->firstName.' '.$row->lastName,
                'customerEmail' => $row->email,
                'orderTotal' => '$' . number_format($row->orderTotal, 2),
                'orderDate' => date('Y-m-d', strtotime($row->orderDate)),
                'createdAt' => date('Y-m-d', strtotime($row->created_at)),
                'order_status' => $row?->order_status ? ucwords(str_replace('_', ' ', $row->order_status)) : 'N/A',
                'status' => '<div class="text-center"><i class="fa ' . ($row->transactionId ? 'fa-check text-success' : 'fa-times text-danger') . ' inline"></i></div>',
                'options' => '<div class="dropdown">
                                <button type="button" class="btn btn-sm light dk dropdown-toggle" data-toggle="dropdown">
                                    <i class="material-icons">&#xe5d4;</i> Options
                                </button>
                                <div class="dropdown-menu pull-right">
                                    <a class="dropdown-item" href="' . route($request->route, $row->slug) . '">
                                        <i class="material-icons"></i> Preview
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
            'totalEarnings' => '$' . number_format($totalEarnings, 2),
        ]);
    }

    public function getTransaction(Request $request)
    {
        // General for all pages
        $baseUrl = Helper::GeneralSiteSettings('external_api_link_en');
        $authCode = Helper::GeneralSiteSettings('auth_code_en');
        $GeneralWebmasterSections = WebmasterSection::where('status', '=', '1')->orderby('row_no', 'desc')->get();
        $query = Order::with(['customer','purchases','apply_coupon','coupon','transaction','birthday','cabana','general_ticket','season_pass','offer_creation'])->where('auth_code',$authCode);
        if ($request->has('search') && $request->search['value'] != '') {
            $search = $request->search['value'];

            $query->where(function ($q) use ($search) {
                $q->where('firstName', 'like', "%{$search}%")
                ->orWhere('lastName', 'like', "%{$search}%")
                ->orWhere('slug', 'like', "%{$search}%")
                ->orWhere('order_status', 'like', "%{$search}%")
                ->orWhereRaw("DATE_FORMAT(orderDate, '%Y-%m-%d') LIKE ?", ["%{$search}%"])
                ->orWhereRaw("DATE_FORMAT(created_at, '%Y-%m-%d') LIKE ?", ["%{$search}%"]);
            });
        }

        if ($request->filled('find_q')) {
            $q = $request->find_q;
            $query->where(function ($q2) use ($q) {
                $q2->where('firstName', 'like', "%{$q}%")
                ->orWhere('lastName', 'like', "%{$q}%")
                ->orWhere('email', 'like', "%{$q}%")
                ->orWhere('slug', 'like', "%{$q}%");
            });
        }

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        if ($request->from_date && $request->to_date) {
            $from = $request->from_date . ' 00:00:00';
            $to = $request->to_date . ' 23:59:59';
            $query->whereBetween('created_at', [$from, $to]);
        }

        if ($request->filled('order_status')) {
            $query->where('order_status', $request->order_status);
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
        $totalEarnings = $query->sum('orderTotal');
        $data = $query->offset($start)->limit($limit)->get();

        $result = [];
        foreach ($data as $row) {
            
            $order_type =  OrdersHelper::order_types($row->type);
            $result[] = [
                'id' => $row->slug,
                'check' => '<label class="ui-check m-a-0">
                                <input type="checkbox" name="ids[]" value="' . $row->id . '"><i></i>
                                <input type="hidden" name="row_ids[]" value="' . $row->id . '" class="form-control row_no">
                            </label>',
                'package' => '<a class="dropdown-item" href="' . route($request->route, $row->slug) . '">'.$row->$order_type->ticketType.'</a>',
                'type' => ucwords(str_replace('_', ' ',$row->type)),
                'customerName' => $row->firstName.' '.$row->lastName,
                'customerEmail' => $row->email,
                'orderTotal' => '$' . number_format($row->orderTotal, 2),
                'orderDate' => date('Y-m-d', strtotime($row->orderDate)),
                'createdAt' => date('Y-m-d', strtotime($row->created_at)),
                'status' => '<div class="text-center"><i class="fa ' . ($row->transactionId ? 'fa-check text-success' : 'fa-times text-danger') . ' inline"></i></div>',
                'order_status' => $row?->order_status ? ucwords(str_replace('_', ' ', $row->order_status)) : 'N/A',
                'options' => '<div class="dropdown">
                                <button type="button" class="btn btn-sm light dk dropdown-toggle" data-toggle="dropdown">
                                    <i class="material-icons">&#xe5d4;</i> Options
                                </button>
                                <div class="dropdown-menu pull-right">
                                    <a class="dropdown-item" href="' . route($request->route, $row->slug) . '">
                                        <i class="material-icons"></i> Preview
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
            'totalEarnings' => '$' . number_format($totalEarnings, 2),
        ]);
    }

    public function print(Request $request)
    {
        $q = $request->input('find_q');
        $from_date = $request->input('from_date');
        $to_date = $request->input('to_date');
        $type = $request->input('type');
        $package_id = $request->input('package_id');
        $stat = $request->input('stat'); // 'excel' or something else

        $query = Order::with([
            'customer',
            'purchases',
            'apply_coupon',
            'coupon',
            'transaction',
            'birthday',
            'cabana',
            'general_ticket',
            'season_pass',
            'offer_creation'
        ]);

        // Filter by search query
        if ($q) {
            $query->where(function ($qBuilder) use ($q) {
                $qBuilder->where('firstName', 'like', "%$q%")
                    ->orWhere('lastName', 'like', "%$q%")
                    ->orWhere('email', 'like', "%$q%")
                    ->orWhere('phone', 'like', "%$q%")
                    ->orWhere('slug', 'like', "%$q%");
            });
        }

        if ($from_date && $to_date) {
            $from = $from_date . ' 00:00:00';
            $to = $to_date . ' 23:59:59';
            $query->whereBetween('created_at', [$from, $to]);
        }

        if ($type) {
            $query->where('type', $type);
        }

        if ($package_id) {
            $query->where('package_id', $package_id);
        }
        $orders = $query->orderBy('id', 'desc')->get();
        $totalEarnings = $orders->sum('orderTotal');
        if ($stat === 'excel') {
        }

        return view('dashboard.transaction.print', compact('orders', 'totalEarnings', 'stat'));
    }

    public function printCabana(Request $request)
    {
        $q = $request->input('find_q');
        $from_date = $request->input('from_date');
        $to_date = $request->input('to_date');
        $type = $request->input('type');
        $package_id = $request->input('package_id');
        $stat = $request->input('stat'); // 'excel' or something else

        $query = Order::with([
            'customer',
            'purchases',
            'apply_coupon',
            'coupon',
            'transaction',
            'cabana',
        ]);

        // Filter by search query
        if ($q) {
            $query->where(function ($qBuilder) use ($q) {
                $qBuilder->where('firstName', 'like', "%$q%")
                    ->orWhere('lastName', 'like', "%$q%")
                    ->orWhere('email', 'like', "%$q%")
                    ->orWhere('phone', 'like', "%$q%")
                    ->orWhere('slug', 'like', "%$q%");
            });
        }

        if ($from_date && $to_date) {
            $from = $from_date . ' 00:00:00';
            $to = $to_date . ' 23:59:59';
            $query->whereBetween('created_at', [$from, $to]);
        }

        if ($package_id) {
            $query->where('package_id', $package_id);
        }
        
        $orders = $query->where('type', 'cabana')->orderBy('id', 'desc')->get();

        $totalEarnings = $orders->sum('orderTotal');
        if ($stat === 'excel') {
        }

        return view('dashboard.transaction.print', compact('orders', 'totalEarnings', 'stat'));
    }

    public function printBirthday(Request $request)
    {
        $q = $request->input('find_q');
        $from_date = $request->input('from_date');
        $to_date = $request->input('to_date');
        $type = $request->input('type');
        $package_id = $request->input('package_id');
        $stat = $request->input('stat'); // 'excel' or something else

        $query = Order::with([
            'customer',
            'purchases',
            'apply_coupon',
            'coupon',
            'transaction',
            'birthday',
        ]);

        // Filter by search query
        if ($q) {
            $query->where(function ($qBuilder) use ($q) {
                $qBuilder->where('firstName', 'like', "%$q%")
                    ->orWhere('lastName', 'like', "%$q%")
                    ->orWhere('email', 'like', "%$q%")
                    ->orWhere('phone', 'like', "%$q%")
                    ->orWhere('slug', 'like', "%$q%");
            });
        }

        if ($from_date && $to_date) {
            $from = $from_date . ' 00:00:00';
            $to = $to_date . ' 23:59:59';
            $query->whereBetween('created_at', [$from, $to]);
        }

        if ($package_id) {
            $query->where('package_id', $package_id);
        }
        $orders = $query->where('type', 'birthday')->orderBy('id', 'desc')->get();
        $totalEarnings = $orders->sum('orderTotal');
        if ($stat === 'excel') {
        }

        return view('dashboard.transaction.print', compact('orders', 'totalEarnings', 'stat'));
    }

    public function printGeneralTicket(Request $request)
    {
        $q = $request->input('find_q');
        $from_date = $request->input('from_date');
        $to_date = $request->input('to_date');
        $type = $request->input('type');
        $package_id = $request->input('package_id');
        $stat = $request->input('stat'); // 'excel' or something else

        $query = Order::with([
            'customer',
            'purchases',
            'apply_coupon',
            'coupon',
            'transaction',
            'general_ticket',
        ]);

        // Filter by search query
        if ($q) {
            $query->where(function ($qBuilder) use ($q) {
                $qBuilder->where('firstName', 'like', "%$q%")
                    ->orWhere('lastName', 'like', "%$q%")
                    ->orWhere('email', 'like', "%$q%")
                    ->orWhere('phone', 'like', "%$q%")
                    ->orWhere('slug', 'like', "%$q%");
            });
        }

        if ($from_date && $to_date) {
            $from = $from_date . ' 00:00:00';
            $to = $to_date . ' 23:59:59';
            $query->whereBetween('created_at', [$from, $to]);
        }

        if ($package_id) {
            $query->where('package_id', $package_id);
        }
        $orders = $query->where('type', 'general_ticket')->orderBy('id', 'desc')->get();
        $totalEarnings = $orders->sum('orderTotal');
        if ($stat === 'excel') {
        }

        return view('dashboard.transaction.print', compact('orders', 'totalEarnings', 'stat'));
    }

    public function printSeasonPass(Request $request)
    {
        $q = $request->input('find_q');
        $from_date = $request->input('from_date');
        $to_date = $request->input('to_date');
        $type = $request->input('type');
        $package_id = $request->input('package_id');
        $stat = $request->input('stat'); // 'excel' or something else

        $query = Order::with([
            'customer',
            'purchases',
            'apply_coupon',
            'coupon',
            'transaction',
            'season_pass',
        ]);

        // Filter by search query
        if ($q) {
            $query->where(function ($qBuilder) use ($q) {
                $qBuilder->where('firstName', 'like', "%$q%")
                    ->orWhere('lastName', 'like', "%$q%")
                    ->orWhere('email', 'like', "%$q%")
                    ->orWhere('phone', 'like', "%$q%")
                    ->orWhere('slug', 'like', "%$q%");
            });
        }

        if ($from_date && $to_date) {
            $from = $from_date . ' 00:00:00';
            $to = $to_date . ' 23:59:59';
            $query->whereBetween('created_at', [$from, $to]);
        }

        if ($package_id) {
            $query->where('package_id', $package_id);
        }
        $orders = $query->where('type', 'season_pass')->orderBy('id', 'desc')->get();
        $totalEarnings = $orders->sum('orderTotal');
        if ($stat === 'excel') {
        }

        return view('dashboard.transaction.print', compact('orders', 'totalEarnings', 'stat'));
    }

    public function printOfferCreation(Request $request)
    {
        $q = $request->input('find_q');
        $from_date = $request->input('from_date');
        $to_date = $request->input('to_date');
        $type = $request->input('type');
        $package_id = $request->input('package_id');
        $stat = $request->input('stat'); // 'excel' or something else

        $query = Order::with([
            'customer',
            'purchases',
            'apply_coupon',
            'coupon',
            'transaction',
            'offer_creation',
        ]);

        // Filter by search query
        if ($q) {
            $query->where(function ($qBuilder) use ($q) {
                $qBuilder->where('firstName', 'like', "%$q%")
                    ->orWhere('lastName', 'like', "%$q%")
                    ->orWhere('email', 'like', "%$q%")
                    ->orWhere('phone', 'like', "%$q%")
                    ->orWhere('slug', 'like', "%$q%");
            });
        }

        if ($from_date && $to_date) {
            $from = $from_date . ' 00:00:00';
            $to = $to_date . ' 23:59:59';
            $query->whereBetween('created_at', [$from, $to]);
        }

        if ($package_id) {
            $query->where('package_id', $package_id);
        }
        $orders = $query->where('type', 'offer_creation')->orderBy('id', 'desc')->get();
        $totalEarnings = $orders->sum('orderTotal');
        if ($stat === 'excel') {
        }

        return view('dashboard.transaction.print', compact('orders', 'totalEarnings', 'stat'));
    }

    public function getBirthdayOrders()
    {
        // General for all pages
        $baseUrl = Helper::GeneralSiteSettings('external_api_link_en');
        $authCode = Helper::GeneralSiteSettings('auth_code_en');
        $GeneralWebmasterSections = WebmasterSection::where('status', '=', '1')->orderby('row_no', 'desc')->get();
        $get_birthday_orders = Order::with(['customer','purchases','transaction'])->where('auth_code',$authCode)->where('type','birthday')->get();
        
         // Paginate
        $perPage = 10;
        $currentPage = LengthAwarePaginator::resolveCurrentPage();
        $collection = collect($get_birthday_orders);
        $paginated = new LengthAwarePaginator(
            $collection->forPage($currentPage, $perPage),
            $collection->count(),
            $perPage,
            $currentPage,
            ['path' => url()->current(), 'query' => request()->query()]
        );
        return view("dashboard.birthdayorders.list", compact("paginated", "GeneralWebmasterSections"));
    }

    public function getTicketingOrders()
    {
        // General for all pages
        $baseUrl = Helper::GeneralSiteSettings('external_api_link_en');
        $authCode = Helper::GeneralSiteSettings('auth_code_en');
        $GeneralWebmasterSections = WebmasterSection::where('status', '=', '1')->orderby('row_no', 'desc')->get();
        $get_ticketing_orders = Order::with(['customer','purchases','transaction'])->where('auth_code',$authCode)->where('type','general_ticket')->get();
        
         // Paginate
        $perPage = 10;
        $currentPage = LengthAwarePaginator::resolveCurrentPage();
        $collection = collect($get_ticketing_orders);
        $paginated = new LengthAwarePaginator(
            $collection->forPage($currentPage, $perPage),
            $collection->count(),
            $perPage,
            $currentPage,
            ['path' => url()->current(), 'query' => request()->query()]
        );
        return view("dashboard.ticketingorders.list", compact("paginated", "GeneralWebmasterSections"));
    }

    public function getSeasonPassOrders()
    {
        // General for all pages
        $baseUrl = Helper::GeneralSiteSettings('external_api_link_en');
        $authCode = Helper::GeneralSiteSettings('auth_code_en');
        $GeneralWebmasterSections = WebmasterSection::where('status', '=', '1')->orderby('row_no', 'desc')->get();
        $get_birthday_orders = Order::with(['customer','purchases','transaction'])->where('auth_code',$authCode)->where('type','season_pass')->get();
        
         // Paginate
        $perPage = 10;
        $currentPage = LengthAwarePaginator::resolveCurrentPage();
        $collection = collect($get_birthday_orders);
        $paginated = new LengthAwarePaginator(
            $collection->forPage($currentPage, $perPage),
            $collection->count(),
            $perPage,
            $currentPage,
            ['path' => url()->current(), 'query' => request()->query()]
        );
        return view("dashboard.seasonpassorders.list", compact("paginated", "GeneralWebmasterSections"));
    }

    public function getOfferCreationOrders()
    {
        // General for all pages
        $baseUrl = Helper::GeneralSiteSettings('external_api_link_en');
        $authCode = Helper::GeneralSiteSettings('auth_code_en');
        $GeneralWebmasterSections = WebmasterSection::where('status', '=', '1')->orderby('row_no', 'desc')->get();
        $get_offer_orders = Order::with(['customer','purchases','transaction'])->where('auth_code',$authCode)->where('type','offer_creation')->get();
        
         // Paginate
        $perPage = 10;
        $currentPage = LengthAwarePaginator::resolveCurrentPage();
        $collection = collect($get_offer_orders);
        $paginated = new LengthAwarePaginator(
            $collection->forPage($currentPage, $perPage),
            $collection->count(),
            $perPage,
            $currentPage,
            ['path' => url()->current(), 'query' => request()->query()]
        );
        return view("dashboard.offercreationorders.list", compact("paginated", "GeneralWebmasterSections"));
    }

    public function getTransactions()
    {
        // General for all pages
        $baseUrl = Helper::GeneralSiteSettings('external_api_link_en');
        $authCode = Helper::GeneralSiteSettings('auth_code_en');
        $GeneralWebmasterSections = WebmasterSection::where('status', '=', '1')->orderby('row_no', 'desc')->get();
        $get_offer_orders = Order::with(['customer','purchases','transaction'])->where('auth_code',$authCode)->get();
        
         // Paginate
        $perPage = 10;
        $currentPage = LengthAwarePaginator::resolveCurrentPage();
        $collection = collect($get_offer_orders);
        $paginated = new LengthAwarePaginator(
            $collection->forPage($currentPage, $perPage),
            $collection->count(),
            $perPage,
            $currentPage,
            ['path' => url()->current(), 'query' => request()->query()]
        );
        return view("dashboard.transaction.list", compact("paginated", "GeneralWebmasterSections"));
    }

    public function getByOrderSlug($slug)
    {
        // General for all pages
        $baseUrl = Helper::GeneralSiteSettings('external_api_link_en');
        $authCode = Helper::GeneralSiteSettings('auth_code_en');
        $order = Order::where('slug',$slug)->first();
        $GeneralWebmasterSections = WebmasterSection::where('status', '=', '1')->orderby('row_no', 'asc')->get();
        $get_cabana_orders = Order::with(['customer','purchases','apply_coupon','coupon','transaction',$order->type])->where('auth_code',$authCode)->where('slug',$slug)->first();
        $views = [
            'cabana'        => 'dashboard.kabanaorders.show',
            'general_ticket'=> 'dashboard.ticketingorders.show',
            'season_pass'   => 'dashboard.seasonpassorders.show',
            'offer_creation'   => 'dashboard.offercreationorders.show',
        ];

        $view = $views[$get_cabana_orders->type] ?? 'dashboard.birthdayorders.show';
        return view($view, compact("get_cabana_orders", "GeneralWebmasterSections"));
    }

    public static function getPackagesByType(Request $request)
    {
        $type = $request->get('type');
        switch ($type) {
            case 'cabana':
                $packages = \App\Models\CabanaPackages::select('id', 'ticketType as name')->get();
                break;
            case 'birthday':
                $packages = \App\Models\BirthdayPackages::select('id', 'title as name')->get();
                break;
            case 'general_ticket':
                $packages = \App\Models\GeneralTicketPackages::select('id', 'title as name')->get();
                break;
            case 'season_pass':
                $packages = \App\Models\SeasonPass::select('id', 'title as name')->get();
                break;
            case 'offer_creation':
                $packages = \App\Models\OfferCreation::select('id', 'title as name')->get();
                break;
            default:
                $packages = [];
        }

        return response()->json([
            'packages' => $packages
        ]);
    }
}
