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

    public function getByOrderSlug($slug)
    {
        // General for all pages
        $baseUrl = Helper::GeneralSiteSettings('external_api_link_en');
        $authCode = Helper::GeneralSiteSettings('auth_code_en');
        $GeneralWebmasterSections = WebmasterSection::where('status', '=', '1')->orderby('row_no', 'asc')->get();
        $get_cabana_orders = Order::with(['customer','purchases','transaction'])->where('auth_code',$authCode)->where('slug',$slug)->first();
        if($get_cabana_orders->type == 'cabana'){
            return view("dashboard.kabanaorders.show", compact("get_cabana_orders", "GeneralWebmasterSections"));
        }elseif($get_cabana_orders->type == 'general_ticket'){
            return view("dashboard.ticketingorders.show", compact("get_cabana_orders", "GeneralWebmasterSections"));
        }elseif($get_cabana_orders->type == 'season_pass'){
            return view("dashboard.seasonpassorders.show", compact("get_cabana_orders", "GeneralWebmasterSections"));
        }else{
            return view("dashboard.birthdayorders.show", compact("get_cabana_orders", "GeneralWebmasterSections"));
        }
        
    }
}
