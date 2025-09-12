<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class OrderItemResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'slug' => $this->slug,
            'ticketType' => $this->ticketType,
            'sectionId' => $this->sectionId,
            'capacityId' => $this->capacityId,
            'quantity' => $this->quantity,
            'amount' => $this->amount,
        ];
    }
}
