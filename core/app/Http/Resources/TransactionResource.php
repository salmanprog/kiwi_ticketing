<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TransactionResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'slug' => $this->slug,
            'transactionId' => $this->transactionId,
            'totalItem' => $this->totalItem,
            'quantity' => $this->quantity,
            'transactionDate' => $this->transactionDate,
            'amount' => $this->amount
        ];
    }
}
