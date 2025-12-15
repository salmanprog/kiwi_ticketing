@extends('dashboard.layouts.master')
@section('title', __('General Ticket Order Detail'))
@push("after-styles")
    <link href="{{ asset("assets/dashboard/js/iconpicker/fontawesome-iconpicker.min.css") }}" rel="stylesheet">
    <!--[if lt IE 9]>
    <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->
    <style>

     .box-header.dker {
            background: linear-gradient(135deg, #A0C242 0%, #8AAE38 100%) !important;
            color: white;
             border-radius: 5px 5px 0 0;
             font-size: 18px;
        }
        .box-header.dker h3 {
            font-size: 22px;
            font-weight: 700;
        }
        .box-tool {
            color: white;
        }
     .dropdown-toggle
          {
            background: #A0C242 !important;
            border-color: #A0C242 !important;
        }
  
        .btn-primary:hover {
            background: #8AAE38 !important;
            border-color: #8AAE38 !important;
        }
    </style>
@endpush
@section('content')
<div class="padding">
    <div class="box m-b-0">
        <div class="box-header dker">
            <h3><i class="material-icons"></i> {{ __('Order Detail') }}</h3>
            <small>
                <a href="{{ route('adminHome') }}">{{ __('backend.home') }}</a> /
                <a>General Ticket</a> /
                <a>Order Details</a>
            </small>
        </div>
        <div class="box-tool">
            <ul class="nav">
                <li class="nav-item inline dropdown">
                    <a class="btn white b-a nav-link dropdown-toggle" data-toggle="dropdown">
                        <i class="material-icons md-18">&#xe5d4;</i> {{  __('backend.options') }}
                    </a>
                    <div class="dropdown-menu dropdown-menu-scale pull-right">
                        <a class="dropdown-item" href="{{ route('birthdayorders') }}"><i
                                class="material-icons">&#xe31b;</i> {{ __('backend.back') }}</a>
                    </div>
                </li>
            </ul>
        </div>
    </div>
    <?php
        $tab_1 = "active";
        $tab_2 = "";
        $tab_3 = "";
        if (Session::has('activeTab')) {
            if (Session::get('activeTab') == "ticket") {
                $tab_1 = "";
                $tab_2 = "active";
                $tab_3 = "";
            }
            if (Session::get('activeTab') == "transaction") {
                $tab_1 = "";
                $tab_2 = "";
                $tab_3 = "active";
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
                    &#xe0b9;</i> {{ __('Tickets') }}</span>
                </a>
            </li>
            <li class="nav-item inline">
                <a class="nav-link  {{ $tab_3 }}" data-toggle="tab" data-target="#tab_transaction">
            <span class="text-md"><i class="material-icons">
                    &#xe8e5;</i> {{ __('Transaction') }}</span>
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
                            <strong>Package Name</strong><br>
                            {{ optional($get_cabana_orders->general_ticket)->title ?? 'N/A' }}
                        </div>
                        <div class="col-md-3 p-3 text-center">
                            <strong>Total Ticket</strong><br>
                            {{ count($get_cabana_orders->purchases) }}
                        </div>
                        <div class="col-md-3 p-3 text-center">
                            <strong>Status</strong><br>
                            Paid
                        </div>
                    </div>

                    <div class="row mb-4" style="margin-bottom: 40px;">
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
                        <div class="col-md-3 p-3 text-center">
                            <strong>Address</strong><br>
                            {{ $get_cabana_orders->customerAddress ? $get_cabana_orders->customerAddress : 'N/A' }}
                        </div>
                    </div>
                    

                    @if($get_cabana_orders->apply_coupon)
                    <div class="row mb-4" style="margin-bottom: 40px;">
                        <div class="col-md-3 p-3 text-center">
                            <strong>Coupon Title</strong><br>
                            {{ $get_cabana_orders->coupon->title }}
                        </div>
                        <div class="col-md-3 p-3 text-center">
                            <strong>Coupon Code</strong><br>
                            {{ $get_cabana_orders->coupon->coupon_code }}
                        </div>
                        <div class="col-md-3 p-3 text-center">
                            <strong>Discount</strong><br>
                            {{ $get_cabana_orders->coupon->discount }}
                        </div>
                        <div class="col-md-3 p-3 text-center">
                            <strong>Discount Type</strong><br>
                            {{ $get_cabana_orders->coupon->discount_type == 'percentage' ? '%' : ' Flat Rate' }}
                        </div>
                    </div>
                    <div class="row mb-4" style="margin-bottom: 40px;">
                        <div class="col-md-3 p-3 text-center">
                            <strong>Sub Total</strong><br>
                            ${{ number_format($get_cabana_orders->orderTotal, 2) }}
                        </div>
                        <div class="col-md-3 p-3 text-center">
                            <strong>Apply Discount</strong><br>
                            {{ $get_cabana_orders->coupon->discount }}{{ $get_cabana_orders->coupon->discount_type == 'percentage' ? '%' : ' Flat Rate' }}
                        </div>
                        <div class="col-md-3 p-3 text-center">
                            <strong>Total Order</strong><br>
                            ${{ number_format($get_cabana_orders->apply_coupon->final_amount, 2) }}
                        </div>
                        <div class="col-md-3 p-3 text-center">
                            <strong>Order Date</strong><br>
                            {{ \Carbon\Carbon::parse($get_cabana_orders->orderDate)->format('Y-m-d') }}
                        </div>
                    </div>
                    @else
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
                    @endif
                </div>
            </div>

            <div class="tab-pane  {{ $tab_2 }}" id="tab_ticket">
                <div class="box-body p-a-2">
                    @if(count($get_cabana_orders->purchases) > 0)
                        @foreach($get_cabana_orders->purchases as $ticket)
                                <div class="row mb-4" style="background-color: #f1f1f1; padding: 12px 20px; border-radius: 6px;">
                                    <div class="col-md-10 d-flex align-items-center">
                                        <h5 class="m-0"><strong>Ticket No {{ $loop->iteration }}</strong></h5>
                                        <p>
                                            <strong>Ticket Status:</strong>
                                            <span style="color: green;">
                                                {{ isset($ticket->ticket_status) ? ucwords(str_replace('_', ' ', $ticket->ticket_status)) : 'N/A' }}
                                            </span>
                                        </p>
                                    </div>
                                    <div class="col-md-2 d-flex justify-content-end align-items-center">
                                        <img src="https://quickchart.io/qr?text={{$ticket->visualId}}&margin=2&size=150"
                                            title="WildRiver Ticket QR Code"
                                            class="cabana_qr_code"
                                            style="max-width: 100px;" />
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

            <div class="tab-pane  {{ $tab_3 }}" id="tab_transaction">
                <div class="box-body p-a-2">
                    <div class="row mb-4" style="margin-bottom: 40px;">
                        <div class="col-md-3 p-3 text-center">
                            <strong>Transaction Id</strong><br>
                            {{ $get_cabana_orders->transaction->transactionId ?? 'N/A' }}
                        </div>
                        <div class="col-md-3 p-3 text-center">
                            <strong>Transaction Date</strong><br>
                            {{ $get_cabana_orders->transaction->transactionDate ? \Carbon\Carbon::parse($get_cabana_orders->transaction->transactionDate)->format('Y-m-d') : 'N/A' }}
                        </div>
                        <div class="col-md-3 p-3 text-center">
                            <strong>Transaction Amount</strong><br>
                            ${{ number_format($get_cabana_orders->transaction->amount ?? 0, 2) }}
                        </div>
                        <div class="col-md-3 p-3 text-center">
                            <strong>Total Tickets</strong><br>
                            {{ $get_cabana_orders->transaction->totalItem }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
</div>
@endsection
@push("after-scripts")
    
@endpush
