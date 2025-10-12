<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\Models\Transaction;
use App\Models\WebmasterSection;
use Illuminate\Pagination\LengthAwarePaginator;
use Auth;
use File;
use Helper;
use Illuminate\Http\Request;
use Redirect;
use Illuminate\Support\Facades\Http;
use Carbon\Carbon;



class TransactionController extends Controller
{
    private $uploadPath = "uploads/sections/";

    public function __construct()
    {
        $this->middleware('auth');

    }

    public function index()
    {
        
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
