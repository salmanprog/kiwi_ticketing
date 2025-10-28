<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\MediaResource;

class LogResource extends JsonResource
{
    public function toArray($request)
    {
        
        return [
            'id' => $this->id,
            'slug' => $this->slug,
            'type' => $this->type,
            'order_number' => $this->order_number,
            'endpoint' => $this->endpoint,
            'request' => $this->request,
            'response' => $this->response,
            'message' => $this->message,
            'status' => $this->status
        ];
    }
}
