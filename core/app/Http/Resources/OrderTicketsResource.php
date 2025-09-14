<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class OrderTicketsResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'visualId' => $this->visualId,
            'parentVisualId' => $this->parentVisualId,
            'childVisualId' => $this->childVisualId,
            'ticketType' => $this->ticketType,
            'description' => $this->description,
            'seat' => $this->seat,
            'price' => $this->price,
            'ticketDate' => $this->ticketDate,
            'ticketDisplayDate' => $this->ticketDisplayDate,
            'quantity' => $this->quantity,
            'slotTime' => $this->slotTime,
            'isRefundedOrder' => $this->isRefundedOrder,
            'checkInStatus' => $this->checkInStatus,
            'totalRefundedAmount' => $this->totalRefundedAmount,
            'isWavierFormSubmitted' => $this->isWavierFormSubmitted,
            'isQrCodeBurn' => $this->isQrCodeBurn,
            'wavierSubmittedDateTime' => $this->wavierSubmittedDateTime,
            'refundedDateTime' => $this->refundedDateTime,
            'isTicketUpgraded' => $this->isTicketUpgraded,
            'ticketUpgradedFrom' => $this->ticketUpgradedFrom,
            'isSearchParentRecord' => $this->isSearchParentRecord,
            'validUntil' => $this->validUntil,
            'isSeasonPassRenewal' => $this->isSeasonPassRenewal,
            'isSeasonPass' => $this->isSeasonPass,
            'totalOrderRefundedAmount' => $this->totalOrderRefundedAmount,
        ];
    }
}
