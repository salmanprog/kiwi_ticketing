<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\OrderTicketsResource;
use App\Http\Resources\TransactionResource;
use App\Http\Resources\UserResource;
use App\Http\Resources\OrderCouponResource;

class OrderResource extends JsonResource
{
    public function toArray($request)
    {
        $allowedTypes = ['cabana', 'birthday', 'general_ticket', 'season_pass','offer_creation'];
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
            'promoCode' => $this->promoCode,
            'package' => in_array($this->type, $allowedTypes) ? $this->{$this->type} : '',
            'order_status' => $this->order_status,
            'coupon' => isset($this->apply_coupon) ? OrderCouponResource::make($this->apply_coupon) : [],
            'customer' => UserResource::make($this->customer),
            'tickets' => OrderTicketsResource::collection($this->purchases),
            'transaction' => TransactionResource::make($this->transaction),
        ];
    }
}
