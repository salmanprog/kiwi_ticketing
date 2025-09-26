@extends('dashboard.layouts.master')
@section('title', __('General Ticket Addon'))
@push("after-styles")
    <link href="{{ asset("assets/dashboard/js/iconpicker/fontawesome-iconpicker.min.css") }}" rel="stylesheet">
    <!--[if lt IE 9]>
    <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->
@endpush
@section('content')
    <div class="padding">
        <div class="box">
            <div class="box-header dker">
                <h3><i class="material-icons">&#xe02e;</i> {{ __('New General') }}</h3>
                <small>
                    <a href="{{ route('adminHome') }}">{{ __('backend.home') }}</a> /
                    <a>{{__('Ticket Addon')}}</a> 
                </small>
            </div>
            <div class="box-tool">
                <ul class="nav">
                    <li class="nav-item inline">
                        <a class="nav-link" href="#">
                            <i class="material-icons md-18">×</i>
                        </a>
                    </li>
                </ul>
            </div>
            <div class="box-body p-a-2">
                {{Form::open(['route'=>['generalticketsaddonStore'],'method'=>'POST', 'files' => true])}}
                    <div class="form-group row">
                        <label for="section_id" class="col-sm-2 form-control-label">
                            {!! __('Package') !!}
                        </label>
                        <div class="col-sm-10">
                           <select name="generalTicketSlug" class="form-control">
                                <option value="">- - {!!  __('Select General Package') !!} - -</option>
                                @foreach($getTicketGeneral as $ticket)
                                    <option value="{{ $ticket['slug'] }}">{{ $ticket['title'] }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="section_id" class="col-sm-2 form-control-label">
                            {!! __('Ticket Addon') !!}
                        </label>
                        <div class="col-sm-10">
                           <select name="ticketSlug" id="ticketSlug" class="form-control">
                                <option value="">- - {!!  __('Select Ticket Addon') !!} - -</option>
                                @foreach($tickets_arr['ticket_addon'] as $ticket_addon)
                                    <option value="{{ $ticket_addon['ticketSlug'] }}" data-price="{{ $ticket_addon['price'] ?? '0' }}">{{ $ticket_addon['ticketType'] }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="title"
                                class="col-sm-2 form-control-label">{!!  __('Ticket Addon Price') !!}
                        </label>
                        <div class="col-sm-10">
                            {!! Form::text('new_price','', array('placeholder' => '','class' => 'form-control','id'=>'new_price','required'=>'')) !!}
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="details"
                            class="col-sm-2 form-control-label">{!!  __('Description') !!}
                        </label>
                        <div class="col-sm-10">
                                @foreach(Helper::languagesList() as $ActiveLanguage)
                                @if($ActiveLanguage->box_status)
                                    <div class="m-b-1">
                                        {!!  __('backend.customFieldsType99') !!} {!! @Helper::languageName($ActiveLanguage) !!}
                                        <div class="box p-a-xs">
                                            {!! Form::textarea(
                                                'description',
                                                optional(Session::get('WebmasterSectionField'))->{'details_' . @$ActiveLanguage->code},
                                                [
                                                    'ui-jp' => 'summernote',
                                                    'placeholder' => '',
                                                    'class' => 'form-control summernote_' . @$ActiveLanguage->code,
                                                    'dir' => @$ActiveLanguage->direction,
                                                    'ui-options' => '{height: 150}'
                                                ]
                                            ) !!}
                                        </div>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="photo"
                            class="col-sm-2 form-control-label">{!!  __('Image') !!}</label>
                        <div class="col-sm-10">
                            {!! Form::file('photo[]', array('class' => 'form-control','id'=>'photo','accept'=>'image/*','multiple' => 'multiple')) !!}
                        </div>
                    </div>

                    <div class="form-group row m-t-md" style="margin-top: 0 !important;">
                        <div class="offset-sm-2 col-sm-10">
                            <small>
                                <i class="material-icons">&#xe8fd;</i>
                                {!!  __('backend.imagesTypes') !!}
                            </small>
                        </div>
                    </div>
                    
                </div>
                <div class="modal-footer">
                <button type="button"
                        class="btn dark-white p-x-md"
                        data-dismiss="modal">{{ __('backend.cancel') }}</button>
                <button type="submit"
                        class="btn btn-primary p-x-md">{!! __('backend.add') !!}</button>
            </div>
                {{Form::close()}}
            
        </div>
    </div>
@endsection
@push("after-scripts")
    <script src="{{ asset("assets/dashboard/js/iconpicker/fontawesome-iconpicker.js") }}"></script>
    <script>
        $(function () {
            $('.icp-auto').iconpicker({placement: '{{ (@Helper::currentLanguage()->direction=="rtl")?"topLeft":"topRight" }}'});
        });
        const select = document.getElementById('ticketSlug');
        const priceDisplay = document.getElementById('new_price');

        select.addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            const price = selectedOption.getAttribute('data-price');
            priceDisplay.value = price ? price : '0';
        });
    </script>
@endpush
