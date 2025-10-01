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

        $coupons = Coupons::where('auth_code', $authCode)
            ->where('package_type', $request->package_type)
            ->where('coupon_code', $request->coupon_code)
            ->where('status', '1')
            ->first();

        if (!$coupons) {
            return $this->sendResponse(400, 'Coupon Error', [
                'coupon_code' => 'Coupon does not exist in our record'
            ]);
        }

        if (\Carbon\Carbon::parse($coupons->start_date)->gt(now())) {
            return $this->sendResponse(400, 'Coupon Error', [
                'coupon_code' => 'This coupon is not yet active'
            ]);
        }

        if (\Carbon\Carbon::parse($coupons->end_date)->lt(now())) {
            return $this->sendResponse(400, 'Coupon Error', [
                'coupon_code' => 'This coupon has expired'
            ]);
        }

        if ($coupons->coupon_use_limit >= $coupons->coupon_total_limit){
            return $this->sendResponse(400, 'Coupon Error', [
                'coupon_code' => 'This coupon limit has expired'
            ]);
        }

        if ($coupons->discount_type === 'percentage') {
            $discount = ($request->amount * $coupons->discount) / 100;
        } elseif ($coupons->discount_type === 'flat_rate') {
            $discount = $coupons->discount;
        } else {
            return $this->sendResponse(400, 'Coupon Error', [
                'coupon_code' => 'Invalid discount type'
            ]);
        }

        $discount = min($discount, $request->amount);
        $finalAmount = $request->amount - $discount;

        return $this->sendResponse(200, 'Discount applied', [
            'id' => $coupons->id,
            'original_amount' => $request->amount,
            'discount_type' => $coupons->discount_type,
            'discount' => $discount,
            'final_amount' => $finalAmount,
            'coupon' => CouponResource::make($coupons)
        ]);
    }
}
