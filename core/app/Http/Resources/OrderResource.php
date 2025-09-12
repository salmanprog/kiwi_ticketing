<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\OrderItemResource;
use App\Http\Resources\TransactionResource;
use App\Http\Resources\UserResource;

class OrderResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'slug' => $this->slug,
            'auth_code' => $this->auth_code,
            'type' => $this->type,
            'isterminalPayment' => $this->isterminalPayment,
            'staffDiscount' => $this->staffDiscount,
            'sessionId' => $this->sessionId,
            'orderCreationWithScript' => $this->orderCreationWithScript,
            'isOfficeUse' => $this->isOfficeUse,
            'orderSource' => $this->orderSource,
            'posStaffIdentity' => $this->posStaffIdentity,
            'dateNightPass' => $this->dateNightPass,
            'orderCreationDate' => $this->orderCreationDate,
            'transactionId' => $this->transactionId,
            'saleFormName' => $this->saleFormName,
            'notes' => $this->notes,
            'user_id' => $this->user_id,
            'totalAmount' => $this->totalAmount,
            'customer' => UserResource::make($this->customer),
            'purchase_items' => OrderItemResource::collection($this->purchases),
            'transaction' => TransactionResource::make($this->transaction),
        ];
    }
}
