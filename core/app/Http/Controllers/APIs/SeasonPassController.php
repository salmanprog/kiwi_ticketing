<?php

namespace App\Http\Controllers\APIs;

use App\Models\Setting;
use App\Models\SeasonPass;
use Carbon\Carbon;
use Helper;
use App\Http\Resources\SeasonPassResource;
use Illuminate\Support\Facades\Http;

class SeasonPassController extends BaseAPIController
{
    public function index()
    {
        $baseUrl = Helper::GeneralSiteSettings('external_api_link_en');
        $authCode = Helper::GeneralSiteSettings('auth_code_en');
        $date = Carbon::today()->toDateString();
        $seasonpass = SeasonPass::with(['media_slider'])->where('auth_code',$authCode)->get();
        
        if ($seasonpass->isEmpty()) {
            return $this->sendResponse(200, 'Retrieved Season Pass Listing', []);
        }

        $resource = SeasonPassResource::collection($seasonpass);
        return $this->sendResponse(200, 'Retrieved Season Pass Listing', $resource);
    }
}
