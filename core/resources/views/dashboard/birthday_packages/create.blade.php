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
                <h3><i class="material-icons">&#xe02e;</i> {{ __('New Package') }}</h3>
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
                {{Form::open(['route'=>['birthdaypackagesStore'],'method'=>'POST', 'files' => true])}}
                    <div class="form-group row">
                        <label for="section_id" class="col-sm-2 form-control-label">
                            {!! __('Cabanas') !!}
                        </label>
                        <div class="col-sm-10">
                            <select name="cabanas[]" id="cabanas"
                                    class="form-control select2-multiple" multiple
                                    ui-jp="select2" ui-options="{theme: 'bootstrap'}" required>
                                    @foreach ($cabanas as $cabana)
                                        <option value="{{ $cabana['ticketSlug'] }}"
                                            @if(!empty($selectedSections) && in_array($cabana['ticketSlug'], $selectedSections)) selected @endif>
                                            {!! $cabana['ticketType'] !!}
                                        </option>
                                    @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="title"
                                class="col-sm-2 form-control-label">{!!  __('Title') !!}
                        </label>
                        <div class="col-sm-10">
                            {!! Form::text('title','', array('placeholder' => '','class' => 'form-control','id'=>'title','required'=>'')) !!}
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
                    <div class="form-group row">
                        <label for="banner"
                            class="col-sm-2 form-control-label">{!!  __('Cover Image') !!}</label>
                        <div class="col-sm-10">
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
                                class="col-sm-2 form-control-label">{!!  __('Price') !!}
                        </label>
                        <div class="col-sm-10">
                            {!! Form::text('price','', array('placeholder' => '','class' => 'form-control','id'=>'price','required'=>'')) !!}
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="title"
                                class="col-sm-2 form-control-label">{!!  __('Map Link') !!}
                        </label>
                        <div class="col-sm-10">
                            {!! Form::text('map_link','', array('placeholder' => '','class' => 'form-control','id'=>'map_link','required'=>'')) !!}
                        </div>
                    </div>
                    <div class="form-group row">
                            <label for="link_status" class="col-sm-2 form-control-label">Status</label>
                            <div class="col-sm-10">
                                <div class="radio">
                                    <label class="ui-check ui-check-md">
                                        <input id="status1" class="has-value" checked="checked" name="status" type="radio" value="1">
                                        <i class="dark-white"></i>
                                        Active
                                    </label>
                                    &nbsp; &nbsp;
                                    <label class="ui-check ui-check-md">
                                        <input id="status2" class="has-value" name="status" type="radio" value="0">
                                        <i class="dark-white"></i>
                                        Not Active
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
    </script>
@endpush
