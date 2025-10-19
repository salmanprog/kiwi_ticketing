@extends('dashboard.layouts.master')
@section('title', Helper::GeneralSiteSettings("site_title_".@Helper::currentLanguage()->code))
@push("after-styles")
    <link rel="stylesheet" href="{{ asset('assets/dashboard/css/flags.css') }}" type="text/css"/>
@endpush
@section('content')
<div class="padding p-b-0">
    <div class="margin">
        <h5 class="m-b-0 _300">{{ __('backend.hi') }} <span
                class="text-primary">{{ Auth::user()->name }}</span>, {{ __('backend.welcomeBack') }}
        </h5>
    </div>
    <div class="col-sm-12 col-md-5 col-lg-4">
        <div class="row">
            @php
                $iconCodes = [
                    '&#xe050;',
                    '&#xe63a;',
                    '&#xe251;',
                    '&#xe2c8;',
                    '&#xe3e8;',
                    '&#xe02f;',
                    '&#xe540;',
                    '&#xe307;',
                    '&#xe8f6;',
                ];

                // Your orders_info array (make sure this is defined here or passed from controller)
                $orders_info = [
                    'birthday' => [
                        'title' => 'Package',
                        'route' => 'birthdayorders'
                    ],
                    'cabana' => [
                        'title' => 'Cabana',
                        'route' => 'kabanaorders'
                    ],
                    'general_ticket' => [
                        'title' => 'Platform',
                        'route' => 'generalticketsorders'
                    ],
                    'offer_creation' => [
                        'title' => 'Offer',
                        'route' => 'offercreationpackagesorders'
                    ],
                    'season_pass' => [
                        'title' => 'LandingPage',
                        'route' => 'seasonpassorders'
                    ]
                ];
            @endphp

            @foreach ($orders as $index => $order)
                @if ($order->type !== 'TOTAL')
                    @php
                        // Rotate through the icon array
                        $LiIcon = $iconCodes[$index % count($iconCodes)];

                        // Get order info if exists, else fallback
                        $info = $orders_info[$order->type] ?? null;

                        // Title to display
                        $title = $info['title'] ?? ucwords(str_replace('_', ' ', $order->type));

                        // Route to use for links, fallback to '#' if not found
                        $route = isset($info['route']) ? route($info['route']) : '#';
                    @endphp

                    {{-- Orders box --}}
                    <div class="col-xs-6">
                        <div class="box p-a" style="cursor: pointer"
                            onclick="location.href='{{ $route }}'">
                            <a href="{{ $route }}">
                                <div class="pull-left m-r">
                                    <i class="material-icons text-2x text-info m-y-sm">{!! $LiIcon !!}</i>
                                </div>
                                <div class="clear">
                                    <div class="text-muted">{{ $title }} Orders</div>
                                    <h4 class="m-a-0 text-md _600">{{ $order->total_number_of_orders }}</h4>
                                </div>
                            </a>
                        </div>
                    </div>

                    {{-- Earnings box --}}
                    <div class="col-xs-6">
                        <div class="box p-a" style="cursor: pointer"
                            onclick="location.href='{{ $route }}'">
                            <a href="{{ $route }}">
                                <div class="pull-left m-r">
                                    <i class="material-icons text-2x text-success m-y-sm">&#xe050;</i>
                                </div>
                                <div class="clear">
                                    <div class="text-muted">{{ $title }} Earning</div>
                                    <h4 class="m-a-0 text-md _600">{{ number_format($order->total_earning, 2) }}</h4>
                                </div>
                            </a>
                        </div>
                    </div>
                @endif
            @endforeach
           
            @php
                $total = $orders->firstWhere('type', 'TOTAL');
            @endphp

            @if ($total)
                <div class="col-xs-12">
                    <div class="row-col box-color text-center primary">
                        <div class="row-cell p-a">
                            Total Orders
                            <h4 class="m-a-0 text-md _600"><a href="{{ route('transactionorders') }}">{{ $total->total_number_of_orders }}</a></h4>
                        </div>
                        <div class="row-cell p-a dker">
                            Total Earnings
                            <h4 class="m-a-0 text-md _600"><a href="{{ route('transactionorders'    ) }}">{{ number_format($total->total_earning, 2) }}</a></h4>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
    <div class="col-md-12 col-xl-8">
        <div class="box" style="min-height: 300px">
            <div class="box-header">
                <h3>{{ __('Today Orders') }}</h3>
                <small>{{ __('Paid, Upgrade, and Update Total Orders') }} - {{ $totalCount }} {{ __(' and Earning $') }}{{ number_format($totalEarning, 2) }}</small>
            </div>

            <div class="text-center b-t">
                <div class="row-col">
                    {{-- Paid Orders --}}
                    <div class="row-cell p-a">
                        <div class="inline m-b">
                            <div ui-jp="easyPieChart" class="easyPieChart"
                                data-percent="{{ $paidPercent }}"
                                ui-options="{
                                    lineWidth: 8,
                                    trackColor: 'rgba(0,0,0,0.05)',
                                    barColor: '#007bff',
                                    scaleColor: 'transparent',
                                    size: 100
                                }">
                                <div><h5>{{ $paidPercent }}%</h5></div>
                            </div>
                        </div>
                        <div>
                            Paid Orders
                            <small class="block m-b">{{ $paidCount }} (${{ number_format($paidTotal, 2) }})</small>
                            <a href="{{ route('transactionorders') }}"
                            class="btn btn-sm white text-u-c rounded">{{ __('View') }}</a>
                        </div>
                    </div>

                    {{-- Upgrade Orders --}}
                    <div class="row-cell p-a dker">
                        <div class="inline m-b">
                            <div ui-jp="easyPieChart" class="easyPieChart"
                                data-percent="{{ $upgradePercent }}"
                                ui-options="{
                                    lineWidth: 8,
                                    trackColor: 'rgba(0,0,0,0.05)',
                                    barColor: '#28a745',
                                    scaleColor: 'transparent',
                                    size: 100
                                }">
                                <div><h5>{{ $upgradePercent }}%</h5></div>
                            </div>
                        </div>
                        <div>
                            Upgrade Orders
                            <small class="block m-b">{{ $upgradeCount }} (${{ number_format($upgradeTotal, 2) }})</small>
                            <a href="{{ route('transactionorders') }}"
                            class="btn btn-sm white text-u-c rounded">{{ __('View') }}</a>
                        </div>
                    </div>

                    {{-- Update Orders --}}
                    <div class="row-cell p-a">
                        <div class="inline m-b">
                            <div ui-jp="easyPieChart" class="easyPieChart"
                                data-percent="{{ $updatePercent }}"
                                ui-options="{
                                    lineWidth: 8,
                                    trackColor: 'rgba(0,0,0,0.05)',
                                    barColor: '#ffc107',
                                    scaleColor: 'transparent',
                                    size: 100
                                }">
                                <div><h5>{{ $updatePercent }}%</h5></div>
                            </div>
                        </div>
                        <div>
                            Update Orders
                            <small class="block m-b">{{ $updateCount }} (${{ number_format($updateTotal, 2) }})</small>
                            <a href="{{ route('transactionorders') }}"
                            class="btn btn-sm white text-u-c rounded">{{ __('View') }}</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-12 col-md-7 col-lg-8">
        <div class="row-col box bg">
            <div class="col-sm-8">
                <div class="box-header">
                    <h3>{{ __('Total Orders') }}</h3>
                    <small>{{ __('you can view order by monthly') }}</small>
                </div>
                <div class="box-body">
                    <canvas id="ordersChart" height="120"></canvas>
                </div>
            </div>
            <div class="col-sm-4 dker">
                <div class="box-header">
                    <h3>{{ __('By Monthly') }}</h3>
                </div>
                <div class="box-body">
                    <p class="text-muted">
                        {{ __('backend.reportsDetails') }} : <br>
                        {{ __('Order By Type') }}, {{ __('By Packages') }},
                        {{ __('Date Filters') }}, {{ __('Order ID Filter') }}
                    </p>
                    <a href="{{ route('transactionorders') }}" style="margin-bottom: 5px;"
                        class="btn btn-sm btn-outline rounded b-success">{{ __('backend.viewMore') }}</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@push("after-scripts")
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/flot/0.8.3/jquery.flot.min.js"></script>
<script>
    const labels = @json($monthlyOrders->pluck('month_name'));
    const data = @json($monthlyOrders->pluck('total_orders'));

    const ctx = document.getElementById('ordersChart').getContext('2d');
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: labels,
            datasets: [{
                label: 'Total Orders',
                data: data,
                borderColor: '#0cc2aa',
                backgroundColor: 'rgba(12,194,170,0.1)',
                tension: 0.3,
                fill: true
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        precision:0
                    }
                }
            }
        }
    });
</script>
@endpush