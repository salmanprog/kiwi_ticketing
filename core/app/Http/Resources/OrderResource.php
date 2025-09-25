<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\OrderTicketsResource;
use App\Http\Resources\TransactionResource;
use App\Http\Resources\UserResource;

class OrderResource extends JsonResource
{
    public function toArray($request)
    {
        $allowedTypes = ['cabana', 'birthday', 'general_ticket', 'season_pass'];
        return [
            'id' => $this->id,
            'slug' => $this->slug,
            'orderNumber'  => $this->slug,
            'type' => $this->type,
            'visualId' => $this->visualId,
            'firstName' => $this->firstName,
            'lastName' => $this->lastName,
            'email' => $this->email,
            'phone' => $this->phone,
            'customerAddress' => $this->customerAddress,
            'orderTotal' => $this->orderTotal,
            'tax' => $this->tax,
            'serviceCharges' => $this->serviceCharges,
            'orderTip' => $this->orderTip,
            'orderDate' => $this->orderDate,
            'slotTime' => $this->slotTime,
            'orderSource' => $this->orderSource,    
            'posStaffIdentity' => $this->posStaffIdentity,
            'isOrderFraudulent' => $this->isOrderFraudulent,
            'orderFraudulentTimeStamp' => $this->orderFraudulentTimeStamp,
            'transactionId' => $this->transactionId,
            'totalOrderRefundedAmount' => $this->totalOrderRefundedAmount,
            'package' => in_array($this->type, $allowedTypes) ? $this->{$this->type} : '',
            'customer' => UserResource::make($this->customer),
            'tickets' => OrderTicketsResource::collection($this->purchases),
            'transaction' => TransactionResource::make($this->transaction),
        ];
    }
}
