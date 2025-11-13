<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\MediaResource;
use App\Http\Resources\BirthdayAddonResource;

class BirthdayPackagesResource extends JsonResource
{
    public function toArray($request)
    {
        
        return [
            'id' => $this->id,
            'title' => $this->title,
            'slug' => $this->slug,
            'description' => $this->description,
            'slider_images' => MediaResource::collection($this->media_slider),
            'cover_images' => MediaResource::collection($this->media_cover),
            'cabanas' => $this->filteredTickets ?? [],
            'addons' => BirthdayAddonResource::collection($this->addons),
            'price' => $this->price,
            'map_link' => $this->map_link,
            'status' => $this->status
        ];
    }
}
