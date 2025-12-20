<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\MediaResource;

class WaiverResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'slug' => $this->slug,
            'order_id' => $this->order_id,
            'qr_code' => $this->qr_code,
            'waiver_type' => $this->waiver_type,
            'email' => $this->email,
            'name' => $this->name,
            'dob' => $this->dob,
            'phone' => $this->phone,
            'street_address' => $this->street_address,
            'city' => $this->city,
            'state' => $this->state,
            'zip_code' => $this->zip_code,
            'parent_name' => $this->parent_name,
            'parent_address' => $this->parent_address,
            'parent_phone' => $this->parent_phone,
            'signature' => $this->photo ? url('uploads/waivers/' . $this->photo) : null,
        ];
    }
}
