@extends('dashboard.layouts.master')
@section('title', __('Edit Coupon'))
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
                <h3><i class="material-icons">&#xe02e;</i> {{ __('Edit Coupon') }}</h3>
                <small>
                    <a href="{{ route('adminHome') }}">{{ __('backend.home') }}</a> /
                    <a>{{__('edit-coupon')}}</a> 
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
                {{Form::open(['route'=>['couponUpdate',"id"=>$couponsPackages->slug],'method'=>'POST', 'files' => true])}}
                    <div class="form-group row">
                        <label for="section_id" class="col-sm-2 form-control-label">
                            {!! __('Type') !!}
                        </label>
                        <div class="col-sm-10">
                            {!! Form::text('package_type',old('package_type', $couponsPackages->package_type ?? ''), array('placeholder' => '','class' => 'form-control','id'=>'package_type','required'=>'','readonly'=>'readonly')) !!}
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="title"
                                class="col-sm-2 form-control-label">{!!  __('Title') !!}
                        </label>
                        <div class="col-sm-10">
                            {!! Form::text('title',old('title', $couponsPackages->title ?? ''), array('placeholder' => '','class' => 'form-control','id'=>'title','required'=>'')) !!}
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="title"
                                class="col-sm-2 form-control-label">{!!  __('Coupon Code') !!}
                        </label>
                        <div class="col-sm-10">
                            {!! Form::text('coupon_code',old('coupon_code', $couponsPackages->coupon_code ?? ''), array('placeholder' => '','class' => 'form-control','id'=>'coupon_code','required'=>'')) !!}
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="title"
                                class="col-sm-2 form-control-label">{!!  __('Discount') !!}
                        </label>
                        <div class="col-sm-10">
                            {!! Form::text('discount',old('discount', $couponsPackages->discount ?? ''), array('placeholder' => '','class' => 'form-control','id'=>'discount','required'=>'')) !!}
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="title"
                                class="col-sm-2 form-control-label">{!!  __('Discount Type') !!}
                        </label>
                        <div class="col-sm-10">
                            {!! Form::text('discount_type',old('discount_type', $couponsPackages->discount_type ?? ''), array('placeholder' => '','class' => 'form-control','id'=>'discount_type','required'=>'','readonly'=>'readonly')) !!}
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="title"
                                class="col-sm-2 form-control-label">{!!  __('Start Date') !!}
                        </label>
                        <div class="col-sm-10">
                            {!! Form::text('start_date',old('start_date', $couponsPackages->start_date ?? ''), array('placeholder' => '','class' => 'form-control','id'=>'start_date','required'=>'')) !!}
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="title"
                                class="col-sm-2 form-control-label">{!!  __('End Date') !!}
                        </label>
                        <div class="col-sm-10">
                            {!! Form::text('end_date',old('end_date', $couponsPackages->end_date ?? ''), array('placeholder' => '','class' => 'form-control','id'=>'end_date','required'=>'')) !!}
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="title"
                                class="col-sm-2 form-control-label">{!!  __('Coupon Limit') !!}
                        </label>
                        <div class="col-sm-10">
                            {!! Form::text('coupon_total_limit',old('coupon_total_limit', $couponsPackages->coupon_total_limit ?? ''), array('placeholder' => '','class' => 'form-control','id'=>'coupon_total_limit','required'=>'')) !!}
                        </div>
                    </div>            
                    <div class="form-group row">
                        <label for="link_status"
                                class="col-sm-2 form-control-label">{!!  __('backend.status') !!}</label>
                        <div class="col-sm-10">
                            <div class="radio">
                                <label class="ui-check ui-check-md">
                                    {!! Form::radio('status','1',($couponsPackages->status==1) ? true : false, array('id' => 'status1','class'=>'has-value')) !!}
                                    <i class="dark-white"></i>
                                    {{ __('backend.active') }}
                                </label>
                                &nbsp; &nbsp;
                                <label class="ui-check ui-check-md">
                                    {!! Form::radio('status','0',($couponsPackages->status==0) ? true : false, array('id' => 'status2','class'=>'has-value')) !!}
                                    <i class="dark-white"></i>
                                    {{ __('backend.notActive') }}
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                <button type="button"
                        class="btn dark-white p-x-md"
                        data-dismiss="modal">{{ __('backend.cancel') }}</button>
                <button type="submit"
                        class="btn btn-primary p-x-md">{!! __('backend.update') !!}</button>
            </div>
                {{Form::close()}}
            
        </div>
    </div>
@endsection
@push("after-scripts")
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>
    <script src="{{ asset("assets/dashboard/js/iconpicker/fontawesome-iconpicker.js") }}"></script>
    <script>
        $(function () {
            $('.icp-auto').iconpicker({placement: '{{ (@Helper::currentLanguage()->direction=="rtl")?"topLeft":"topRight" }}'});
        });
        $(document).ready(function () {
        $('#start_date').datepicker({
            format: 'yyyy-mm-dd',
            autoclose: true,
            todayHighlight: true
        });
        $('#end_date').datepicker({
            format: 'yyyy-mm-dd',
            autoclose: true,
            todayHighlight: true
        });
    });
    </script>
@endpush
