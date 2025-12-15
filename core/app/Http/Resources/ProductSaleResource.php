<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\MediaResource;

class ProductSaleResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'slug' => $this->slug,
            'offerType' => $this->offerType,
            'description' => $this->description,
            'start_date' => $this->from_date,
            'end_date' => $this->to_date,
            'slider_images' => MediaResource::collection($this->media_slider),
        ];
    }
}
