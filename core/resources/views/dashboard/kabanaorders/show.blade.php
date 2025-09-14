@extends('dashboard.layouts.master')
@section('title', __('Cabana Order Detail'))
@push("after-styles")
    <link href="{{ asset("assets/dashboard/js/iconpicker/fontawesome-iconpicker.min.css") }}" rel="stylesheet">
    <!--[if lt IE 9]>
    <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->
@endpush
@section('content')
<div class="padding">
    <div class="box m-b-0">
        <div class="box-header dker">
            <h3><i class="material-icons"></i> {{ __('Order Detail') }}</h3>
            <small>
                <a href="{{ route('adminHome') }}">{{ __('backend.home') }}</a> /
                <a>Cabana</a> /
                <a>Order Details</a>
            </small>
        </div>
    </div>
    <?php
        $tab_1 = "active";
        $tab_2 = "";
        if (Session::has('activeTab')) {
            if (Session::get('activeTab') == "seo") {
                $tab_1 = "";
                $tab_2 = "active";
            }
        }
    ?>
    <div class="box nav-active-border b-info">
        <ul class="nav nav-md">
            <li class="nav-item inline">
                <a class="nav-link {{ $tab_1 }}" data-toggle="tab" data-target="#tab_details">
                    <span class="text-md"><i class="material-icons">
                            &#xe31e;</i> {{ __('Order') }}</span>
                </a>
            </li>
            <li class="nav-item inline">
                <a class="nav-link  {{ $tab_2 }}" data-toggle="tab" data-target="#tab_ticket">
            <span class="text-md"><i class="material-icons">
                    &#xe8e5;</i> {{ __('Tickets') }}</span>
                </a>
            </li>
        </ul>
        <div class="tab-content clear b-t">
            <div class="tab-pane {{ $tab_1 }}" id="tab_details">
                <div class="box-body p-a-2">
                    <div class="row mb-4" style="margin-bottom: 40px;">
                        <div class="col-md-3 p-3 text-center">
                            <strong>Order Number</strong><br>
                            {{ $get_cabana_orders->slug }}
                        </div>
                        <div class="col-md-3 p-3 text-center">
                            <strong>Name</strong><br>
                            {{ $get_cabana_orders->firstName }} {{ $get_cabana_orders->lastName }}
                        </div>
                        <div class="col-md-3 p-3 text-center">
                            <strong>Email</strong><br>
                            {{ $get_cabana_orders->email }}
                        </div>
                        <div class="col-md-3 p-3 text-center">
                            <strong>Phone</strong><br>
                            {{ $get_cabana_orders->phone }}
                        </div>
                    </div>

                    <div class="row mb-4" style="margin-bottom: 40px;">
                        <div class="col-md-3 p-3 text-center">
                            <strong>Order Total</strong><br>
                            ${{ number_format($get_cabana_orders->orderTotal, 2) }}
                        </div>
                        <div class="col-md-3 p-3 text-center">
                            <strong>Tax</strong><br>
                            ${{ number_format($get_cabana_orders->tax, 2) }}
                        </div>
                        <div class="col-md-3 p-3 text-center">
                            <strong>Order Tip</strong><br>
                            ${{ number_format($get_cabana_orders->orderTip, 2) }}
                        </div>
                        <div class="col-md-3 p-3 text-center">
                            <strong>Order Date</strong><br>
                            {{ \Carbon\Carbon::parse($get_cabana_orders->orderDate)->format('Y-m-d') }}
                        </div>
                    </div>
                </div>
            </div>

            <div class="tab-pane  {{ $tab_2 }}" id="tab_ticket">
                <div class="box-body p-a-2">
                    @if(count($get_cabana_orders->purchases) > 0)
                        @foreach($get_cabana_orders->purchases as $ticket)
                                <div class="row mb-2">
                                    <div class="col-12">
                                        <div style="background-color: #f1f1f1; padding: 12px 20px; border-radius: 6px;margin-bottom: 40px;">
                                            <h5 class="m-0"><strong>Ticket No {{ $loop->iteration }}</strong></h5>
                                        </div>
                                    </div>
                                </div>
                                <div class="row mb-4" style="margin-bottom: 40px;">
                                <div class="col-md-3 p-3 text-center">
                                    <strong>Ticket Type</strong><br>
                                    {{ $ticket->ticketType ?? 'N/A' }}
                                </div>
                                <div class="col-md-3 p-3 text-center">
                                    <strong>Description</strong><br>
                                    {{ $ticket->description ?? 'N/A' }}
                                </div>
                                <div class="col-md-3 p-3 text-center">
                                    <strong>Seat No</strong><br>
                                    {{ $ticket->seat ?? 'N/A' }}
                                </div>
                                <div class="col-md-3 p-3 text-center">
                                    <strong>Price</strong><br>
                                    ${{ number_format($ticket->price ?? 0, 2) }}
                                </div>
                            </div>

                            <div class="row mb-4" style="margin-bottom: 40px;">
                                <div class="col-md-3 p-3 text-center">
                                    <strong>Ticket Date</strong><br>
                                    {{ $ticket->ticketDate ? \Carbon\Carbon::parse($ticket->ticketDate)->format('Y-m-d') : 'N/A' }}
                                </div>
                                <div class="col-md-3 p-3 text-center">
                                    <strong>Quantity</strong><br>
                                    {{ $ticket->quantity ?? 'N/A' }}
                                </div>
                                <div class="col-md-3 p-3 text-center">
                                    <strong>Slot Time</strong><br>
                                    {{ $ticket->slotTime ?? 'N/A' }}
                                </div>
                                <div class="col-md-3 p-3 text-center">
                                    <strong>Check-In Status</strong><br>
                                    {{ $ticket->checkInStatus == 1 ? 'Checked In' : 'Not Checked In' }}
                                </div>
                            </div>
                            <div class="row mb-4" style="margin-bottom: 40px;">
                                <div class="col-md-3 p-3 text-center">
                                    <strong>Refunded Order</strong><br>
                                    {{ $ticket->isRefundedOrder == 0 ? 'No' : 'Yes' }}
                                </div>
                                <div class="col-md-3 p-3 text-center">
                                    <strong>Refunded Amount</strong><br>
                                    ${{ number_format($ticket->totalRefundedAmount ?? 0, 2) }}
                                </div>
                                <div class="col-md-3 p-3 text-center">
                                    <strong>Refunded Date</strong><br>
                                    {{ $ticket->refundedDateTime ? \Carbon\Carbon::parse($ticket->refundedDateTime)->format('Y-m-d') : 'N/A' }}
                                </div>
                                <div class="col-md-3 p-3 text-center">
                                    <strong>Total Order Refunded Amount</strong><br>
                                    ${{ number_format($ticket->totalOrderRefundedAmount ?? 0, 2) }}
                                </div>
                            </div>
                            <div class="row mb-4" style="margin-bottom: 40px;">
                                <div class="col-md-3 p-3 text-center">
                                    <strong>Wavier Form</strong><br>
                                    {{ $ticket->isWavierFormSubmitted == 0 ? 'No' : 'Yes' }}
                                </div>
                                <div class="col-md-3 p-3 text-center">
                                    <strong>Wavier Submitted Date</strong><br>
                                    {{ $ticket->wavierSubmittedDateTime ? \Carbon\Carbon::parse($ticket->wavierSubmittedDateTime)->format('Y-m-d') : 'N/A' }}
                                </div>
                                <div class="col-md-3 p-3 text-center">
                                    <strong>Ticket Upgraded</strong><br>
                                    {{ $ticket->isTicketUpgraded == 0 ? 'No' : 'Yes' }}
                                </div>
                                <div class="col-md-3 p-3 text-center">
                                    <strong>Ticket Upgraded From</strong><br>
                                    {{ $ticket->ticketUpgradedFrom ?? 'N/A' }}
                                </div>
                            </div>
                            <div class="row mb-4" style="margin-bottom: 40px;">
                                <div class="col-md-3 p-3 text-center">
                                    <strong>Qr-Code Burn</strong><br>
                                    {{ $ticket->isQrCodeBurn == 0 ? 'No' : 'Yes' }}
                                </div>
                                <div class="col-md-3 p-3 text-center">
                                    <strong>Valid Until</strong><br>
                                    {{ $ticket->validUntil ? \Carbon\Carbon::parse($ticket->validUntil)->format('Y-m-d') : 'N/A' }}
                                </div>
                                <div class="col-md-3 p-3 text-center">
                                    <strong>Season Pass</strong><br>
                                    {{ $ticket->isSeasonPass == 0 ? 'No' : 'Yes' }}
                                </div>
                                <div class="col-md-3 p-3 text-center">
                                    <strong>Season Pass Renewal</strong><br>
                                    {{ $ticket->isSeasonPassRenewal == 0 ? 'No' : 'Yes' }}
                                </div>
                            </div>
                        @endforeach
                    @endif
                </div>
            </div>
        </div>
</div>
@endsection
@push("after-scripts")
    
@endpush
