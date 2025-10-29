@extends('dashboard.layouts.master')
@section('title', Helper::GeneralSiteSettings("site_title_".@Helper::currentLanguage()->code))
@push("after-styles")
    <link rel="stylesheet" href="{{ asset('assets/dashboard/css/flags.css') }}" type="text/css"/>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        /* Professional Clean Theme */
        :root {
            --primary-green: #A0C242;
            --text-dark: #2c3e50;
            --text-light: #6c757d;
            --border-light: #e9ecef;
        }
        
        .dashboard-welcome {
            background: linear-gradient(135deg, var(--primary-green) 0%, #8AAE38 100%);
            border-radius: 12px;
            padding: 25px;
            margin-bottom: 25px;
            color: white;
            box-shadow: 0 4px 12px rgba(160, 194, 66, 0.25);
        }
        
        .stat-card {
            background: white;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 15px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.06);
            border: 1px solid var(--border-light);
            transition: all 0.3s ease;
            cursor: pointer;
        }
        
        .stat-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 15px rgba(160, 194, 66, 0.15);
            border-color: var(--primary-green);
        }
        
        .stat-icon {
            width: 50px;
            height: 50px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 22px;
            margin-right: 15px;
            background: rgba(160, 194, 66, 0.1);
            color: var(--primary-green);
            flex-shrink: 0;
        }
        
        .stat-content {
            flex: 1;
        }
        
        .stat-number {
            font-size: 24px;
            font-weight: 700;
            line-height: 1.2;
            color: var(--text-dark);
            margin-bottom: 2px;
        }
        
        .stat-label {
            font-size: 13px;
            color: var(--text-light);
            font-weight: 500;
        }
        
        .chart-container {
            background: white;
            border-radius: 12px;
            padding: 20px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.06);
            margin-bottom: 20px;
            border: 1px solid var(--border-light);
        }
        
        .dashboard-section {
            margin-bottom: 25px;
        }
        
        .section-title {
            font-size: 18px;
            font-weight: 600;
            margin-bottom: 15px;
            color: var(--text-dark);
        }
        
        .orders-row {
            display: flex;
            gap: 15px;
            margin-bottom: 15px;
            flex-wrap: wrap;
        }
        
        .order-item {
            flex: 1;
            min-width: 200px;
            display: flex;
            align-items: center;
            background: white;
            padding: 20px;
            margin-bottom: 10px;
            border-radius: 10px;
            border: 1px solid var(--border-light);
            transition: all 0.3s ease;
            cursor: pointer;
        }
        
        .order-item:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
            border-color: var(--primary-green);
        }
        
        .btn-green {
            background: var(--primary-green);
            border-color: var(--primary-green);
            color: white;
            font-size: 12px;
            padding: 6px 12px;
        }
        
        .btn-green:hover {
            background: #8AAE38;
            border-color: #8AAE38;
            color: white;
        }
        
        .text-green {
            color: var(--primary-green) !important;
        }
        
        .icons i {
            margin-right: 5px;
        }
        
        /* Pie Chart Styles */
        .pie-chart-container {
            display: flex;
            justify-content: space-around;
            align-items: center;
            flex-wrap: wrap;
            padding: 20px 0;
        }
        
        .pie-chart-item {
            text-align: center;
            padding: 15px;
        }
        
        .easyPieChart {
            position: relative;
            margin: 0 auto 15px;
        }
        
        .easyPieChart canvas {
            position: absolute;
            top: 0;
            left: 0;
        }
        
        .pie-percent {
            font-size: 18px;
            font-weight: 600;
            color: var(--text-dark);
        }
        
        .pie-label {
            font-size: 14px;
            color: var(--text-light);
            margin-bottom: 5px;
        }
        
        .pie-amount {
            font-size: 12px;
            color: var(--text-light);
            margin-bottom: 10px;
        }
        
        /* Full width sections */
        .full-width-section {
            width: 100%;
            margin-bottom: 25px;
        }
    </style>
@endpush

@section('content')
<div class="icons padding p-b-0">
    <!-- Welcome Section -->
    <div class="dashboard-welcome">
        <div class="row align-items-center">
            <div class="col-md-8">
                <h4 class="m-b-0 _300"><i class="fas fa-chart-line me-2"></i>{{ __('backend.hi') }} <span class="">{{ Auth::user()->name }}</span>, {{ __('backend.welcomeBack') }}</h4>
                <p class="m-b-0 opacity-90">Your business dashboard overview</p>
            </div>
            <div class="col-md-4 text-right">
                <div class="metric-badge">
                    <i class="far fa-calendar me-1"></i>{{ now()->format('M j, Y') }}
                </div>
            </div>
        </div>
    </div>

    <!-- Order Statistics - FULL WIDTH SECTION -->
    <div class="full-width-section">
        <div class="dashboard-section">
            <h5 class="section-title"><i class="fas fa-cubes me-2"></i>Order Statistics</h5>
            
            <!-- First Row: Orders Count -->
            <div class="orders-row">
                @php
                    $orderTypes = [
                        'birthday' => ['title' => 'Package', 'route' => 'birthdayorders', 'icon' => 'fas fa-gift'],
                        'general_ticket' => ['title' => 'Platform', 'route' => 'generalticketsorders', 'icon' => 'fas fa-ticket-alt'],
                        'season_pass' => ['title' => 'SeasonPass', 'route' => 'seasonpassorders', 'icon' => 'fas fa-star'],
                        'cabana' => ['title' => 'Cabana', 'route' => 'kabanaorders', 'icon' => 'fas fa-umbrella-beach'],
                        'offer_creation' => ['title' => 'Offer', 'route' => 'offercreationpackagesorders', 'icon' => 'fas fa-tag']
                    ];
                @endphp

                @foreach ($orderTypes as $type => $info)
                    @php
                        $order = $orders->firstWhere('type', $type);
                        if (!$order) continue;
                    @endphp

                    <div class="order-item" onclick="location.href='{{ route($info['route']) }}'">
                        <div class="stat-icon">
                            <i class="{{ $info['icon'] }}"></i>
                        </div>
                        <div class="stat-content">
                            <div class="stat-number">{{ $order->total_number_of_orders }}</div>
                            <div class="stat-label">{{ $info['title'] }} Orders</div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Second Row: Revenue -->
            <div class="orders-row">
                @foreach ($orderTypes as $type => $info)
                    @php
                        $order = $orders->firstWhere('type', $type);
                        if (!$order) continue;
                    @endphp

                    <div class="order-item" onclick="location.href='{{ route($info['route']) }}'">
                        <div class="stat-icon">
                            <i class="fas fa-dollar-sign"></i>
                        </div>
                        <div class="stat-content">
                            <div class="stat-number">${{ number_format($order->total_earning, 2) }}</div>
                            <div class="stat-label">{{ $info['title'] }} Revenue</div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Main Content Grid -->
    <div class="row">
        <!-- Left Column - Charts -->
        <div class="col-lg-8">
            <!-- Monthly Trends Chart -->
            <div class="dashboard-section">
                <div class="chart-container">
                    <div class="row align-items-center mb-3">
                        <div class="col-md-8">
                            <h5 class="section-title mb-0">
                                <i class="fas fa-chart-line me-2"></i>Monthly Orders Trend
                                <small class="text-muted d-block mt-1">Real-time data from your database</small>
                            </h5>
                        </div>
                        <div class="col-md-4 text-right">
                            <a href="{{ route('transactionorders') }}" class="btn btn-green btn-sm">
                                <i class="fas fa-external-link-alt me-1"></i>Full Report
                            </a>
                        </div>
                    </div>
                    <canvas id="ordersChart" height="120"></canvas>
                </div>
            </div>

            <!-- Today's Orders Pie Charts -->
            <div class="dashboard-section">
                <div class="chart-container">
                    <div class="row align-items-center mb-3">
                        <div class="col-md-8">
                            <h5 class="section-title mb-0">
                                <i class="fas fa-chart-pie me-2"></i>{{ __('Today Orders') }}
                                <small class="text-muted d-block mt-1">{{ __('Paid, Upgrade, and Update Total Orders') }} - {{ $totalCount }} {{ __(' and Earning $') }}{{ number_format($totalEarning, 2) }}</small>
                            </h5>
                        </div>
                    </div>
                    
                    <div class="pie-chart-container">
                        <!-- Paid Orders -->
                        <div class="pie-chart-item">
                            <div class="easyPieChart" data-percent="{{ $paidPercent }}" style="width: 100px; height: 100px;">
                                <div class="pie-percent">{{ $paidPercent }}%</div>
                            </div>
                            <div class="pie-label">Paid Orders</div>
                            <div class="pie-amount">{{ $paidCount }} (${{ number_format($paidTotal, 2) }})</div>
                            <a href="{{ route('transactionorders') }}" class="btn btn-green btn-sm">
                                <i class="fas fa-eye me-1"></i>{{ __('View') }}
                            </a>
                        </div>

                        <!-- Upgrade Orders -->
                        <div class="pie-chart-item">
                            <div class="easyPieChart" data-percent="{{ $upgradePercent }}" style="width: 100px; height: 100px;">
                                <div class="pie-percent">{{ $upgradePercent }}%</div>
                            </div>
                            <div class="pie-label">Upgrade Orders</div>
                            <div class="pie-amount">{{ $upgradeCount }} (${{ number_format($upgradeTotal, 2) }})</div>
                            <a href="{{ route('transactionorders') }}" class="btn btn-green btn-sm">
                                <i class="fas fa-eye me-1"></i>{{ __('View') }}
                            </a>
                        </div>

                        <!-- Update Orders -->
                        <div class="pie-chart-item">
                            <div class="easyPieChart" data-percent="{{ $updatePercent }}" style="width: 100px; height: 100px;">
                                <div class="pie-percent">{{ $updatePercent }}%</div>
                            </div>
                            <div class="pie-label">Update Orders</div>
                            <div class="pie-amount">{{ $updateCount }} (${{ number_format($updateTotal, 2) }})</div>
                            <a href="{{ route('transactionorders') }}" class="btn btn-green btn-sm">
                                <i class="fas fa-eye me-1"></i>{{ __('View') }}
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Column -->
        <div class="col-lg-4">
            @php
                $total = $orders->firstWhere('type', 'TOTAL');
            @endphp

            @if ($total)
            <div class="dashboard-section">
                <h5 class="section-title"><i class="fas fa-tachometer-alt me-2"></i>Business Overview</h5>
                
                <!-- Total Orders -->
                <div class="order-item mb-3" onclick="location.href='{{ route('transactionorders') }}'">
                    <div class="stat-icon">
                        <i class="fas fa-shopping-cart"></i>
                    </div>
                    <div class="stat-content">
                        <div class="stat-number">{{ $total->total_number_of_orders }}</div>
                        <div class="stat-label">Total Orders</div>
                    </div>
                </div>
                
                <!-- Total Revenue -->
                <div class="order-item mb-3" onclick="location.href='{{ route('transactionorders') }}'">
                    <div class="stat-icon">
                        <i class="fas fa-chart-line"></i>
                    </div>
                    <div class="stat-content">
                        <div class="stat-number">${{ number_format($total->total_earning, 2) }}</div>
                        <div class="stat-label">Total Revenue</div>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="chart-container">
                    <h6 class="section-title mb-3"><i class="fas fa-rocket me-2"></i>Quick Actions</h6>
                    <div class="d-grid gap-2">
                        <a href="{{ route('transactionorders') }}" class="btn btn-green btn-sm">
                            <i class="fas fa-list me-2"></i>View All Orders
                        </a>
                        <a href="{{ route('transactionorders') }}" class="btn btn-outline-secondary btn-sm">
                            <i class="fas fa-download me-2"></i>Export Reports
                        </a>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection

@push("after-scripts")
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/flot/0.8.3/jquery.flot.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/easy-pie-chart/2.1.6/jquery.easypiechart.min.js"></script>
<script>
    // Monthly Orders Chart
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
                borderColor: '#A0C242',
                backgroundColor: 'rgba(160, 194, 66, 0.1)',
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

    // Easy Pie Charts
    $(document).ready(function() {
        $('.easyPieChart').easyPieChart({
            lineWidth: 8,
            trackColor: 'rgba(0,0,0,0.05)',
            barColor: '#A0C242',
            scaleColor: 'transparent',
            lineCap: 'round',
            size: 100,
            animate: 1000,
            onStep: function(from, to, percent) {
                $(this.el).find('.pie-percent').text(Math.round(percent) + '%');
            }
        });
    });
</script>
@endpush