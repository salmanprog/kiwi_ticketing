<?php

namespace App\Http\Controllers\APIs;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Coupons;
use App\Models\CouponsTickets;
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
            'purchases' => 'required|array'
        ]);

        if ($validator->fails()) {
            return $this->sendResponse(400, 'Validation Error', $validator->errors());
        }

        $authCode = Helper::GeneralSiteSettings('auth_code_en');

        $coupons = Coupons::with(['createdBy','updatedBy','tickets'])
            ->where('auth_code', $authCode)
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

        if (is_array($request->purchases)) {
            $purchases = collect($request->purchases)
                ->map(function ($item) {
                    return is_string($item) ? json_decode($item, true) : $item;
                })
                ->filter()
                ->values()
                ->toArray();

            $couponTickets = $coupons->tickets->pluck('ticket')->toArray();
            $purchases = collect($purchases)
                ->map(function($purchase) use ($couponTickets, $coupons) {

                    $purchase['coupon_applied'] = false;
                    $purchase['original_amount'] = $purchase['amount'];

                    if (in_array($purchase['ticketType'], $couponTickets)) {
                        $purchase['coupon_applied'] = true;

                        if ($coupons->discount_type === 'percentage') {
                            $purchaseDiscount = ($purchase['amount'] * $coupons->discount) / 100;
                        } else { 
                            $purchaseDiscount = $coupons->discount;
                        }

                        $purchaseDiscount = min($purchaseDiscount, $purchase['amount']);
                        $purchase['discount_type'] = $coupons->discount.'-'.$coupons->discount_type;
                        $purchase['coupon_code'] = $coupons->coupon_code;
                        $purchase['discount'] = $purchaseDiscount;
                        $purchase['amount'] = $purchase['amount'] - $purchaseDiscount;
                    } else {
                        $purchase['discount_type'] = null;
                        $purchase['discount'] = 0;
                    }

                    return $purchase;
                })
                ->toArray();

            if (!collect($purchases)->pluck('coupon_applied')->contains(true)) {
                return $this->sendResponse(400, 'Coupon Error', [
                    'coupon_code' => 'This coupon does not apply to the selected tickets'
                ]);
            }

            $finalAmount = collect($purchases)->sum('amount');
            $totalDiscount = collect($purchases)->sum('discount');
        }

        return $this->sendResponse(200, 'Discount applied', [
            'id' => $coupons->id,
            'original_amount' => $request->amount,
            'discount_type' => $coupons->discount_type,
            'discount' => $totalDiscount,
            'final_amount' => $finalAmount,
            'purchases' => $purchases,
            'coupon' => CouponResource::make($coupons)
        ]);
    }
}
