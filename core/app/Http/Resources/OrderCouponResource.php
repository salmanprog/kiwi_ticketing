<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class OrderCouponResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->coupon_id,
            'original_amount' => $this->original_amount,
            'discount_type' => $this->discount_type,
            'discount' => $this->discount,
            'final_amount' => $this->final_amount
        ];
    }
}
