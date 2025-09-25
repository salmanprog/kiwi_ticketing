<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\MediaResource;

class CabanaResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'venueId' => $this->venueId,
            'ticketType' => $this->ticketType,
            'ticketSlug' => $this->ticketSlug,
            'ticketCategory' => $this->ticketCategory,
            'price' => $this->price,
            'description' => $this->description,
            'slider_images' => MediaResource::collection($this->media_slider),
        ];
    }
}
