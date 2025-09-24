<?php

namespace App\Http\Resources;
use App\Http\Resources\MediaResource;
use Illuminate\Http\Resources\Json\JsonResource;

class SeasonPassResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'slug' => $this->slug,
            'title' => $this->title,
            'description' => $this->description,
            'slider_images' => MediaResource::collection($this->media_slider),
        ];
    }
}
