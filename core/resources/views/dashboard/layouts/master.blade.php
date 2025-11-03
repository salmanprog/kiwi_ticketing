{{-- <!DOCTYPE html>
<html lang="{{ @Helper::currentLanguage()->code }}" dir="{{ @Helper::currentLanguage()->direction }}">
<head>
    @include('dashboard.layouts.head')
     <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
</head>
<body>
<div class="app" id="app">
    @php( $webmailsNewCount= Helper::webmailsNewCount())
    @include('dashboard.layouts.menu')

    <div id="content" class="app-content box-shadow-z0" role="main">
        @include('dashboard.layouts.header')
        @include('dashboard.layouts.footer')
        <div ui-view class="app-body" id="view">
            @include('dashboard.layouts.errors')
            @yield('content')
        </div>
    </div>

    @include('dashboard.layouts.settings')
</div>
@include('dashboard.layouts.foot')
</body>
</html> --}}




<!DOCTYPE html>
<html lang="{{ @Helper::currentLanguage()->code }}" dir="{{ @Helper::currentLanguage()->direction }}">

<head>
    @include('dashboard.layouts.head')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">

    <!-- Skeleton Loader Styles -->
    <style>
        #skeleton-loader {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: #f8f9fa;
            z-index: 9999;
            transition: opacity 0.5s ease, visibility 0.5s ease;
            overflow-y: auto;
        }

        #skeleton-loader.hidden {
            opacity: 0;
            visibility: hidden;
        }

        /* Skeleton Animation */
        .skeleton-shimmer {
            background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
            background-size: 200% 100%;
            animation: shimmer 1.5s infinite;
            border-radius: 4px;
        }

        @keyframes shimmer {
            0% {
                background-position: -200% 0;
            }

            100% {
                background-position: 200% 0;
            }
        }

        /* Skeleton Structure */
        .skeleton-header {
            height: 60px;
            background: #fff;
            border-bottom: 1px solid #e0e0e0;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 20px;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1000;
        }

        .skeleton-sidebar {
            position: fixed;
            left: 0;
            top: 60px;
            width: 250px;
            height: calc(100% - 60px);
            background: #fff;
            border-right: 1px solid #e0e0e0;
            padding: 20px 0;
        }

        .skeleton-main-content {
            margin-left: 250px;
            margin-top: 60px;
            padding: 20px;
            min-height: calc(100vh - 60px);
        }

        /* Skeleton Items */
        .skeleton-logo {
            width: 150px;
            height: 30px;
            margin-bottom: 30px;
            margin-left: 20px;
        }

        .skeleton-menu-item {
            height: 40px;
            margin: 8px 15px;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .skeleton-menu-icon {
            width: 20px;
            height: 20px;
            border-radius: 4px;
        }

        .skeleton-menu-text {
            height: 16px;
            flex: 1;
        }

        .skeleton-header-left {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .skeleton-header-right {
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .skeleton-search {
            width: 300px;
            height: 40px;
            border-radius: 20px;
        }

        .skeleton-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
        }

        .skeleton-bell {
            width: 24px;
            height: 24px;
            border-radius: 50%;
        }

        /* Main Content Skeletons */
        .skeleton-page-header {
            height: 40px;
            width: 300px;
            margin-bottom: 30px;
        }

        .skeleton-cards {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .skeleton-card {
            height: 120px;
            border-radius: 8px;
            background: #fff;
            padding: 20px;
        }

        .skeleton-card-title {
            height: 16px;
            width: 60%;
            margin-bottom: 15px;
        }

        .skeleton-card-value {
            height: 28px;
            width: 40%;
            margin-bottom: 10px;
        }

        .skeleton-card-footer {
            height: 12px;
            width: 80%;
        }

        .skeleton-chart {
            height: 300px;
            background: #fff;
            border-radius: 8px;
            margin-bottom: 20px;
            padding: 20px;
        }

        .skeleton-table {
            background: #fff;
            border-radius: 8px;
            padding: 20px;
        }

        .skeleton-table-header {
            height: 20px;
            width: 100%;
            margin-bottom: 20px;
        }

        .skeleton-table-row {
            display: flex;
            gap: 15px;
            margin-bottom: 15px;
        }

        .skeleton-table-cell {
            height: 16px;
            flex: 1;
        }

        .skeleton-table-cell:nth-child(1) {
            flex: 0.5;
        }

        .skeleton-table-cell:nth-child(3) {
            flex: 0.8;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .skeleton-sidebar {
                width: 70px;
            }

            .skeleton-main-content {
                margin-left: 70px;
            }

            .skeleton-menu-text {
                display: none;
            }

            .skeleton-search {
                width: 150px;
            }
        }

        /* Content fade in */
        .app {
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .app.loaded {
            opacity: 1;
        }
    </style>
</head>

<body>
    <!-- Skeleton Loader -->
    <div id="skeleton-loader">
        <!-- Header -->
        <div class="skeleton-header">
            <div class="skeleton-header-left">
                <div class="skeleton-shimmer" style="width: 40px; height: 40px; border-radius: 8px;"></div>
                <div class="skeleton-shimmer skeleton-search"></div>
            </div>
            <div class="skeleton-header-right">
                <div class="skeleton-shimmer skeleton-bell"></div>
                <div class="skeleton-shimmer skeleton-avatar"></div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="skeleton-sidebar">
            <div class="skeleton-shimmer skeleton-logo"></div>

            <!-- Menu Items -->
            <div class="skeleton-menu-item">
                <div class="skeleton-shimmer skeleton-menu-icon"></div>
                <div class="skeleton-shimmer skeleton-menu-text"></div>
            </div>
            <div class="skeleton-menu-item">
                <div class="skeleton-shimmer skeleton-menu-icon"></div>
                <div class="skeleton-shimmer skeleton-menu-text"></div>
            </div>
            <div class="skeleton-menu-item">
                <div class="skeleton-shimmer skeleton-menu-icon"></div>
                <div class="skeleton-shimmer skeleton-menu-text"></div>
            </div>
            <div class="skeleton-menu-item">
                <div class="skeleton-shimmer skeleton-menu-icon"></div>
                <div class="skeleton-shimmer skeleton-menu-text"></div>
            </div>
            <div class="skeleton-menu-item">
                <div class="skeleton-shimmer skeleton-menu-icon"></div>
                <div class="skeleton-shimmer skeleton-menu-text"></div>
            </div>

            <!-- Separator -->
            <div style="height: 20px;"></div>

            <!-- More Menu Items -->
            <div class="skeleton-menu-item">
                <div class="skeleton-shimmer skeleton-menu-icon"></div>
                <div class="skeleton-shimmer skeleton-menu-text"></div>
            </div>
            <div class="skeleton-menu-item">
                <div class="skeleton-shimmer skeleton-menu-icon"></div>
                <div class="skeleton-shimmer skeleton-menu-text"></div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="skeleton-main-content">
            <!-- Page Title -->
            <div class="skeleton-shimmer skeleton-page-header"></div>

            <!-- Stats Cards -->
            <div class="skeleton-cards">
                <div class="skeleton-card">
                    <div class="skeleton-shimmer skeleton-card-title"></div>
                    <div class="skeleton-shimmer skeleton-card-value"></div>
                    <div class="skeleton-shimmer skeleton-card-footer"></div>
                </div>
                <div class="skeleton-card">
                    <div class="skeleton-shimmer skeleton-card-title"></div>
                    <div class="skeleton-shimmer skeleton-card-value"></div>
                    <div class="skeleton-shimmer skeleton-card-footer"></div>
                </div>
                <div class="skeleton-card">
                    <div class="skeleton-shimmer skeleton-card-title"></div>
                    <div class="skeleton-shimmer skeleton-card-value"></div>
                    <div class="skeleton-shimmer skeleton-card-footer"></div>
                </div>
                <div class="skeleton-card">
                    <div class="skeleton-shimmer skeleton-card-title"></div>
                    <div class="skeleton-shimmer skeleton-card-value"></div>
                    <div class="skeleton-shimmer skeleton-card-footer"></div>
                </div>
            </div>

            <!-- Chart Area -->
            <div class="skeleton-shimmer skeleton-chart"></div>

            <!-- Table -->
            <div class="skeleton-table">
                <div class="skeleton-shimmer skeleton-table-header"></div>
                <div class="skeleton-table-row">
                    <div class="skeleton-shimmer skeleton-table-cell"></div>
                    <div class="skeleton-shimmer skeleton-table-cell"></div>
                    <div class="skeleton-shimmer skeleton-table-cell"></div>
                    <div class="skeleton-shimmer skeleton-table-cell"></div>
                </div>
                <div class="skeleton-table-row">
                    <div class="skeleton-shimmer skeleton-table-cell"></div>
                    <div class="skeleton-shimmer skeleton-table-cell"></div>
                    <div class="skeleton-shimmer skeleton-table-cell"></div>
                    <div class="skeleton-shimmer skeleton-table-cell"></div>
                </div>
                <div class="skeleton-table-row">
                    <div class="skeleton-shimmer skeleton-table-cell"></div>
                    <div class="skeleton-shimmer skeleton-table-cell"></div>
                    <div class="skeleton-shimmer skeleton-table-cell"></div>
                    <div class="skeleton-shimmer skeleton-table-cell"></div>
                </div>
                <div class="skeleton-table-row">
                    <div class="skeleton-shimmer skeleton-table-cell"></div>
                    <div class="skeleton-shimmer skeleton-table-cell"></div>
                    <div class="skeleton-shimmer skeleton-table-cell"></div>
                    <div class="skeleton-shimmer skeleton-table-cell"></div>
                </div>
            </div>
        </div>
    </div>

    <div class="app" id="app">
        @php($webmailsNewCount = Helper::webmailsNewCount())
        @include('dashboard.layouts.menu')

        <div id="content" class="app-content box-shadow-z0" role="main">
            @include('dashboard.layouts.header')
            @include('dashboard.layouts.footer')
            <div ui-view class="app-body" id="view">
                @include('dashboard.layouts.errors')
                @yield('content')
            </div>
        </div>

        @include('dashboard.layouts.settings')
    </div>

    @include('dashboard.layouts.foot')

    <!-- Loader Script -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const loader = document.getElementById('skeleton-loader');
            const app = document.getElementById('app');

            // Show loader immediately
            loader.classList.remove('hidden');

            // Hide loader when page is fully loaded
            window.addEventListener('load', function() {
                // Add a small delay for better UX
                setTimeout(function() {
                    loader.classList.add('hidden');
                    app.classList.add('loaded');
                }, 800);
            });

            // Fallback: hide loader after 5 seconds max
            setTimeout(function() {
                loader.classList.add('hidden');
                app.classList.add('loaded');
            }, 5000);
        });
    </script>
</body>

</html>
