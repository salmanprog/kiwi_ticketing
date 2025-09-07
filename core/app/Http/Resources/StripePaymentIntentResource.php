<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class StripePaymentIntentResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'venueId' => $this->venueId,
            'ticketType' => $this->ticketType,
            'ticketSlug' => $this->ticketSlug,
            'ticketCategory' => $this->ticketCategory,
            'price' => $this->price,
        ];
    }
}
