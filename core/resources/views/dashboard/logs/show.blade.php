@extends('dashboard.layouts.master')
@section('title', __('Log Detail'))
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
            <h3><i class="material-icons"></i> {{ __('Log Detail') }}</h3>
            <small>
                <a href="{{ route('adminHome') }}">{{ __('backend.home') }}</a> /
                <a>Log</a> /
                <a>Details</a>
            </small>
        </div>
        <div class="box-tool">
            <ul class="nav">
                <li class="nav-item inline dropdown">
                    <a class="btn white b-a nav-link dropdown-toggle" data-toggle="dropdown">
                        <i class="material-icons md-18">&#xe5d4;</i> {{  __('backend.options') }}
                    </a>
                    <div class="dropdown-menu dropdown-menu-scale pull-right">
                        <a class="dropdown-item" href="{{ route('kabanaorders') }}"><i
                                class="material-icons">&#xe31b;</i> {{ __('backend.back') }}</a>
                    </div>
                </li>
            </ul>
        </div>
    </div>
    <?php
        $tab_1 = "active";
        $tab_2 = "";
        if (Session::has('activeTab')) {
            if (Session::get('activeTab') == "response") {
                $tab_1 = "";
                $tab_2 = "active";
                $tab_3 = "";
            }
        }
    ?>
    <div class="box nav-active-border b-info">
        <ul class="nav nav-md">
            <li class="nav-item inline">
                <a class="nav-link {{ $tab_1 }}" data-toggle="tab" data-target="#tab_request">
                    <span class="text-md"><i class="material-icons">
                            &#xe31e;</i> {{ __('Request') }}</span>
                </a>
            </li>
            <li class="nav-item inline">
                <a class="nav-link  {{ $tab_2 }}" data-toggle="tab" data-target="#tab_response">
            <span class="text-md"><i class="material-icons">
                    &#xe0b9;</i> {{ __('Response') }}</span>
                </a>
            </li>
        </ul>
        <div class="tab-content clear b-t">
            <div class="tab-pane {{ $tab_1 }}" id="tab_request">
                <div class="box-body p-a-2">
                    @php
                        if (is_string($log_content->request)) {
                            $data = json_decode($log_content->request, true);
                        } elseif (is_object($log_content->request)) {
                            $data = json_decode(json_encode($log_content->request), true);
                        } else {
                            $data = (array) $log_content->request;
                        }

                        if (!empty($data['purchases']) && is_array($data['purchases'])) {
                            foreach ($data['purchases'] as $key => $purchase) {
                                if (is_string($purchase)) {
                                    $decoded = json_decode($purchase, true);
                                    if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                                        $data['purchases'][$key] = $decoded;
                                    }
                                }
                            }
                        }
                        $cleanJson = json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
                    @endphp

                <pre>{{ $cleanJson }}</pre>
                </div>
            </div>

            <div class="tab-pane  {{ $tab_2 }}" id="tab_response">
                <div class="box-body p-a-2">
                   @php
                        if (is_string($log_content->response)) {
                            $data = json_decode($log_content->response, true);
                        } elseif (is_object($log_content->response)) {
                            $data = json_decode(json_encode($log_content->response), true);
                        } else {
                            $data = (array) $log_content->response;
                        }

                        if (!empty($data['purchases']) && is_array($data['purchases'])) {
                            foreach ($data['purchases'] as $key => $purchase) {
                                if (is_string($purchase)) {
                                    $decoded = json_decode($purchase, true);
                                    if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                                        $data['purchases'][$key] = $decoded;
                                    }
                                }
                            }
                        }
                        $cleanJson = json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
                    @endphp

                <pre>{{ $cleanJson }}</pre>
                </div>
            </div>

        </div>
</div>
@endsection
@push("after-scripts")
    
@endpush
