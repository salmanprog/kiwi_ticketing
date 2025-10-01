<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CouponResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'slug' => $this->slug,
            'package_type' => $this->package_type,
            'title' => $this->title,
            'coupon_code' => $this->coupon_code,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'discount' => $this->discount,
            'discount_type' => $this->discount_type,
            'coupon_total_limit' => $this->coupon_total_limit,
        ];
    }
}
