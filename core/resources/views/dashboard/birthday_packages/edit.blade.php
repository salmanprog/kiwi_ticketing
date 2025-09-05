@extends('dashboard.layouts.master')
@section('title', __('Birthday Package'))
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
                <h3><i class="material-icons">&#xe02e;</i> {{ __('Edit Package') }}</h3>
                <small>
                    <a href="{{ route('adminHome') }}">{{ __('backend.home') }}</a> /
                    <a>{{__('New Package')}}</a> 
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
                {{Form::open(['route'=>['birthdaypackagesUpdate',"id"=>$birthday_packages->slug],'method'=>'POST', 'files' => true])}}
                <div class="form-group row">
                    <label for="title"
                            class="col-sm-3 form-control-label">{!!  __('Title') !!}
                    </label>
                    <div class="col-sm-9">
                        {!! Form::text('title',old('title', $birthday_packages->title ?? ''), array('placeholder' => '','class' => 'form-control','id'=>'title','required'=>'')) !!}
                    </div>
                </div>
                    <div class="form-group row">
                        <label for="details"
                            class="col-sm-3 form-control-label">{!!  __('Description') !!}
                        </label>
                        <div class="col-sm-9">
                                @foreach(Helper::languagesList() as $ActiveLanguage)
                                @if($ActiveLanguage->box_status)
                                    <div class="m-b-1">
                                        {!! __('backend.customFieldsType99') !!} {!! @Helper::languageName($ActiveLanguage) !!}
                                        <div class="box p-a-xs">
                                            {!! Form::textarea(
                                                'description',
                                                old('description', $birthday_packages->description ?? ''),
                                                [
                                                    'ui-jp' => 'summernote',
                                                    'placeholder' => '',
                                                    'class' => 'form-control summernote_' . $ActiveLanguage->code,
                                                    'dir' => $ActiveLanguage->direction,
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
                            @if($birthday_packages->image_url!="")
                                <div class="row">
                                    <div class="col-sm-12">
                                        <div id="section_photo" class="col-sm-4 box p-a-xs">
                                            <a target="_blank"
                                                href="{{ asset('uploads/sections/'.$birthday_packages->image_url) }}"><img
                                                    src="{{ asset('uploads/sections/'.$birthday_packages->image_url) }}"
                                                    class="img-responsive" height="200px" width="200px">
                                            </a>
                                            
                                        </div>
                                    </div>
                                </div>

                            @endif
                            {!! Form::file('photo', array('class' => 'form-control','id'=>'photo','accept'=>'image/*')) !!}
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
                         <div class="form-group row">
                                <label for="photo"
                                        class="col-sm-2 form-control-label">{!!  __('Cover Image') !!}</label>
                                <div class="col-sm-10">
                                    @if($birthday_packages->banner_image!="")
                                        <div class="row">
                                            <div class="col-sm-12">
                                                <div id="section_photo" class="col-sm-4 box p-a-xs">
                                                    <a target="_blank"
                                                        href="{{ asset('uploads/sections/'.$birthday_packages->banner_image) }}"><img
                                                            src="{{ asset('uploads/sections/'.$birthday_packages->banner_image) }}"
                                                            class="img-responsive" height="200px" width="200px">
                                                    </a>
                                                    
                                                </div>
                                            </div>
                                        </div>

                                    @endif
                                    {!! Form::file('banner_image', array('class' => 'form-control','id'=>'banner_image','accept'=>'image/*')) !!}
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
                                <div class="form-group row">
                            <label for="title"
                                    class="col-sm-3 form-control-label">{!!  __('Price') !!}
                            </label>
                            <div class="col-sm-9">
                                {!! Form::text('price',old('price', $birthday_packages->price ?? ''), array('placeholder' => '','class' => 'form-control','id'=>'price','required'=>'')) !!}
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="title"
                                    class="col-sm-3 form-control-label">{!!  __('Map Link') !!}
                            </label>
                            <div class="col-sm-9">
                                {!! Form::text('map_link',old('map_link', $birthday_packages->map_link ?? ''), array('placeholder' => '','class' => 'form-control','id'=>'map_link','required'=>'')) !!}
                            </div>
                        </div>
                    <div class="form-group row">
                        <label for="link_status"
                                class="col-sm-2 form-control-label">{!!  __('backend.status') !!}</label>
                        <div class="col-sm-10">
                            <div class="radio">
                                <label class="ui-check ui-check-md">
                                    {!! Form::radio('status','1',($birthday_packages->status==1) ? true : false, array('id' => 'status1','class'=>'has-value')) !!}
                                    <i class="dark-white"></i>
                                    {{ __('backend.active') }}
                                </label>
                                &nbsp; &nbsp;
                                <label class="ui-check ui-check-md">
                                    {!! Form::radio('status','0',($birthday_packages->status==0) ? true : false, array('id' => 'status2','class'=>'has-value')) !!}
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
    <script src="{{ asset("assets/dashboard/js/iconpicker/fontawesome-iconpicker.js") }}"></script>
    <script>
        $(function () {
            $('.icp-auto').iconpicker({placement: '{{ (@Helper::currentLanguage()->direction=="rtl")?"topLeft":"topRight" }}'});
        });
    </script>
@endpush
