
@extends('dashboard.layouts.master')
@section('title', __('backend.sectionsOf')." Addons")
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
         .btn-primary
          {
            background: #A0C242 !important;
            border-color: #A0C242 !important;
            
        }
        .offset-sm-2.col-sm-10 {
	        text-align: end;
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
            <h3><i class="material-icons">&#xe3c9;</i> {{ __('Cabana Addons') }}</h3>
            <small>
                <a href="{{ route('adminHome') }}">{{ __('Cabana') }}</a> /
                <a>{{ __('Addons') }}</a>
            </small>
        </div>
        <div class="box-tool">
            <ul class="nav">
                <li class="nav-item inline">
                    <a class="nav-link" href="">
                        <i class="material-icons md-18">×</i>
                    </a>
                </li>
            </ul>
        </div>
    </div>
    <?php $tab_1 = "active"; ?>
    <div class="box nav-active-border b-info">
        <ul class="nav nav-md">
            <li class="nav-item inline">
                <a class="nav-link {{ $tab_1 }}" data-toggle="tab" data-target="#tab_details">
                    <span class="text-md"><i class="material-icons">
                            &#xe31e;</i> {{ __('Cabana Addons') }}</span>
                </a>
            </li>
        </ul>
        <div class="tab-content clear b-t">
            <div class="tab-pane  {{ $tab_1 }}" id="tab_details">
                <div class="box-body p-a-2">
                    {{Form::open(['route'=>['cabanaAddonStore'],'method'=>'POST'])}}
                    
                    <input name="cabanaSlug" type="hidden" value="{{$cabana->ticketSlug}}">
                    <div class="form-group row">
                        <label
                            class="col-sm-2 form-control-label">{{__('Cabana Ticket')}}
                        </label>
                        <div class="col-sm-10">
                            <input placeholder="" class="form-control has-value" required="" maxlength="191" dir="ltr" name="ticketType" type="text" value="{{$cabana->ticketType}}" readonly>
                        </div>
                    </div>
                    <!-- <div class="form-group row">
                        <label for="link_status" class="col-sm-2 form-control-label">Addons</label>
                        <div class="col-sm-10">
                            
                                @if(count($tickets) > 0)
                                    @foreach($tickets as $ticket)
                                        @if($ticket['ticketSlug'] != $cabana->ticketSlug)
                                            <div class="radio">
                                                <label class="ui-check ui-check-md">
                                                    <input id="ticket_active_{{ $ticket['ticketSlug'] }}" class="has-value" name="ticket[]" type="checkbox" value="{{ $ticket['ticketSlug'] }}" {{ in_array($ticket['ticketSlug'], array_column($cabana_addon, 'ticketSlug')) ? 'checked' : '' }}>
                                                    <i class="dark-white"></i>
                                                    {{ $ticket['ticketType'] }}
                                                </label>
                                            </div>
                                        @endif
                                @endforeach
                                @else
                                    <p>No tickets available.</p>
                                @endif
                            
                        </div>
                    </div> -->
                    <div class="form-group row">
                        <label for="link_status" class="col-sm-2 form-control-label">Addons</label>
                        <div class="col-sm-10">
                            @if(count($tickets) > 0)
                                <div class="row">
                                    @foreach($tickets as $ticket)
                                        @if($ticket['ticketSlug'] != $cabana->ticketSlug)
                                            <div class="col-md-4 col-sm-6 mb-2">
                                                <label class="ui-check ui-check-md d-block">
                                                    <input 
                                                        id="ticket_active_{{ $ticket['ticketSlug'] }}" 
                                                        class="has-value" 
                                                        name="ticket[]" 
                                                        type="checkbox" 
                                                        value="{{ $ticket['ticketSlug'] }}"
                                                        {{ in_array($ticket['ticketSlug'], array_column($cabana_addon, 'ticketSlug')) ? 'checked' : '' }}>
                                                    <i class="dark-white"></i>
                                                    {{ $ticket['ticketType'] }}
                                                </label>
                                            </div>
                                        @endif
                                    @endforeach
                                </div>
                            @else
                                <p>No addon available.</p>
                            @endif
                        </div>
                    </div>

                    <div class="form-group row m-t-md">
                        <div class="offset-sm-2 col-sm-10">
                            <button type="submit" class="btn btn-lg btn-primary m-t"><i class="material-icons">
                                    &#xe31b;</i> {!! __('backend.update') !!}</button>
                        </div>
                    </div>
                     {{Form::close()}}
                </div>
            </div>
        </div>
    </div>
</div>
    
@endsection
@push("after-scripts")
    
@endpush
