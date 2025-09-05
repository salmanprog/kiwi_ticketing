<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class BirthdayPackagesResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'slug' => $this->slug,
            'description' => $this->description,
            'image_url' => $this->image_url ? url('uploads/sections/' . $this->image_url) : null,
            'banner_image' => $this->banner_image ? url('uploads/sections/' . $this->banner_image) : null,
            'price' => $this->price,
            'map_link' => $this->map_link,
            'status' => $this->status
        ];
    }
}
