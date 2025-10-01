<?php

namespace App\Http\Controllers\APIs;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Coupons;
use App\Http\Resources\CouponResource;
use Carbon\Carbon;
use Helper;
use Illuminate\Support\Facades\Http;

class CouponController extends BaseAPIController
{
    public function index()
    {
        $authCode = Helper::GeneralSiteSettings('auth_code_en');
        $coupons = Coupons::where('auth_code',$authCode)->where('status','1')->get();
        
        if ($coupons->isEmpty()) {
            return $this->sendResponse(200, 'Retrieved Coupon Listing', []);
        }

        $resource = CouponResource::collection($coupons);
        return $this->sendResponse(200, 'Retrieved Coupon Listing', $resource);
        
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'package_type' => 'required',
            'amount' => 'required',
            'coupon_code' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->sendResponse(400, 'Validation Error', $validator->errors());
        }

        $authCode = Helper::GeneralSiteSettings('auth_code_en');
        $coupons = Coupons::where('auth_code',$authCode)->where('package_type',$request->package_type)->where('coupon_code',$request->coupon_code)->where('status','1')->first();

        if ($coupons->isEmpty()) {
            return $this->sendResponse(200, 'Retrieved Coupon Discount', []);
        }

        $resource = CouponResource::make($coupons);
        return $this->sendResponse(200, 'Retrieved Coupon Discount', $resource);
    }
}
