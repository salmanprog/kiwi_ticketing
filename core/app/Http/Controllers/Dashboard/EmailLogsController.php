<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\Models\Section;
use App\Models\Email;
use App\Models\EmailLogs;
use App\Models\WebmasterSection;
use App\Models\Media;
use App\Models\Order;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Models\WebmasterSetting;
use Auth;
use File;
use Helper;
use Illuminate\Http\Request;
use Redirect;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Artisan;
use Carbon\Carbon;
use App\Helpers\MailHelper;



class EmailLogsController extends Controller
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
        $email_logs = EmailLogs::get();
         // Paginate
        $perPage = 10;
        $currentPage = LengthAwarePaginator::resolveCurrentPage();
        $collection = collect($email_logs);
        $paginated = new LengthAwarePaginator(
            $collection->forPage($currentPage, $perPage),
            $collection->count(),
            $perPage,
            $currentPage,
            ['path' => url()->current(), 'query' => request()->query()]
        );
        return view("dashboard.email_template.loglist", compact("paginated", "GeneralWebmasterSections"));
    }

    public function getData(Request $request)
    {
        $query = EmailLogs::query();
        if ($request->has('search') && $request->search['value'] != '') {
            $search = $request->search['value'];
            $query->where(function ($q) use ($search) {
                $q->where('identifier', 'like', "%{$search}%")
                  ->orWhere('order_number', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
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
                'order_number' => $row->order_number,
                'email' => $row->email,
                'identifier' => $row->identifier,
                'Subject' => '<a class="dropdown-item" href="' . route('emailShow', $row->slug) . '">'.$row->subject.'</a>',
                'status' => '<div class="text-center"><i class="fa ' . ($row->status ? 'fa-check text-success' : 'fa-times text-danger') . ' inline"></i></div>',
                'options' => '<div class="dropdown">
                                <button type="button" class="btn btn-sm light dk dropdown-toggle" data-toggle="dropdown">
                                    <i class="material-icons">&#xe5d4;</i> Options
                                </button>
                                <div class="dropdown-menu pull-right">
                                    <a class="dropdown-item" href="' . route('emailShow', $row->slug) . '">
                                        <i class="material-icons">&#xe8f4;</i> Preview
                                    </a>
                                    <a class="dropdown-item" href="' . route('emailResend', strtolower($row->order_number)) . '">
                                        <i class="material-icons">&#xe14d;</i> Resend
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

    public function show($slug)
    {
        $GeneralWebmasterSections = WebmasterSection::where('status', '=', '1')->orderby('row_no', 'asc')->get();
        $email_content = EmailLogs::where('slug',$slug)->first();
        return view("dashboard.email_template.logview", compact("email_content", "GeneralWebmasterSections"));
    }

    public function resendmail($ordernumber)
    {
        $GeneralWebmasterSections = WebmasterSection::where('status', '=', '1')->orderby('row_no', 'asc')->get();
        $order = Order::where('slug',$ordernumber)->first();
        $get_order = Order::with(['customer','purchases','apply_coupon','transaction',$order->type])->where('id',$order->id)->first();
        $emailSent = MailHelper::orderConfirmationEmail($get_order,'resend_order');
        return redirect()->action('Dashboard\EmailLogsController@index')->with('doneMessage', __('Email Send Successfully'));
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
}
