@extends('dashboard.layouts.master')
@section('title', __('SeasonPass Product'))
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
  
        .btn-primary:hover {
            background: #8AAE38 !important;
            border-color: #8AAE38 !important;
        }
    
    
    </style>
@endpush
@section('content')
    <div class="padding">
        <div class="box">
            <div class="box-header dker">
                <h3><i class="material-icons">&#xe02e;</i> {{ __('Edit Product') }}</h3>
                <small>
                    <a href="{{ route('adminHome') }}">{{ __('backend.home') }}</a> /
                    <a>{{$seasonpassAddon->slug}}</a> 
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
                {{Form::open(['route'=>['seasonpassaddonUpdate',"id"=>$seasonpassAddon->slug],'method'=>'POST', 'files' => true])}}
                    <div class="form-group row">
                        <label for="section_id" class="col-sm-2 form-control-label">
                            {!! __('Sale Pass') !!}
                        </label>
                        <div class="col-sm-10">
                             {!! Form::text('season_passes_slug',old('title', $getSeasonPass->title ?? ''), array('placeholder' => '','class' => 'form-control','id'=>'season_passes_slug','required'=>'','readonly' => 'readonly')) !!}
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="section_id" class="col-sm-2 form-control-label">
                            {!! __('Sale Product') !!}
                        </label>
                        <div class="col-sm-10">
                            {!! Form::text('ticketSlug',old('title', $seasonpassAddon->ticketSlug ?? ''), array('placeholder' => '','class' => 'form-control','id'=>'ticketSlug','required'=>'','readonly' => 'readonly')) !!}
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
                                        {!! __('backend.customFieldsType99') !!} {!! @Helper::languageName($ActiveLanguage) !!}
                                        <div class="box p-a-xs">
                                            {!! Form::textarea(
                                                'description',
                                                old('description', $seasonpassAddon->description ?? ''),
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
                        <label for="title"
                                class="col-sm-2 form-control-label">{!!  __('Price') !!}
                        </label>
                        <div class="col-sm-10">
                             {!! Form::text('new_price',old('title', $seasonpassAddon->new_price ?? ''), array('placeholder' => '','class' => 'form-control','id'=>'new_price','required'=>'')) !!}
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="photo"
                                class="col-sm-2 form-control-label">{!!  __('Image') !!}</label>
                        <div class="col-sm-10">
                            @if(count($seasonpassAddon->media_slider) > 0)
                                <div class="row">
                                    <div class="col-sm-12">
                                        @foreach($seasonpassAddon->media_slider as $media)
                                            <div id="media_{{ $media->id }}" class="col-sm-4 box p-a-xs">
                                                <a target="_blank" href="{{ asset('uploads/sections/' . $media->filename) }}">
                                                    <img src="{{ asset('uploads/sections/' . $media->filename) }}" class="col-sm-4">
                                                </a>
                                                <br>
                                                <a onclick="deleteMedia({{ $media->id }})" class="btn btn-sm btn-default">
                                                    {!! __('backend.delete') !!}
                                                </a>
                                            </div>

                                            <div id="undo_{{ $media->id }}" class="col-sm-4 p-a-xs" style="display: none">
                                                <a onclick="undoDelete({{ $media->id }})">
                                                    <i class="material-icons">&#xe166;</i>
                                                    {!! __('backend.undoDelete') !!}
                                                </a>
                                            </div>

                                            {{-- Hidden field to mark for deletion --}}
                                            {!! Form::hidden("media_delete[{$media->id}]", '0', ['id' => "media_delete_{$media->id}"]) !!}
                                        @endforeach
                                    </div>
                                </div>

                            @endif
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
                        <label for="link_status"
                                class="col-sm-2 form-control-label">{!!  __('Featured Product') !!}</label>
                        <div class="col-sm-10">
                            <div class="radio">
                                <label class="ui-check ui-check-md">
                                    {!! Form::radio('is_featured','1',($seasonpassAddon->is_featured==1) ? true : false, array('id' => 'is_featured1','class'=>'has-value')) !!}
                                    <i class="dark-white"></i>
                                    {{ __('backend.yes') }}
                                </label>
                                &nbsp; &nbsp;
                                <label class="ui-check ui-check-md">
                                    {!! Form::radio('is_featured','0',($seasonpassAddon->is_featured==0) ? true : false, array('id' => 'is_featured2','class'=>'has-value')) !!}
                                    <i class="dark-white"></i>
                                    {{ __('backend.no') }}
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="link_status"
                                class="col-sm-2 form-control-label">{!!  __('Show New Price') !!}</label>
                        <div class="col-sm-10">
                            <div class="radio">
                                <label class="ui-check ui-check-md">
                                    {!! Form::radio('is_new_price_show','1',($seasonpassAddon->is_new_price_show==1) ? true : false, array('id' => 'is_new_price_show1','class'=>'has-value')) !!}
                                    <i class="dark-white"></i>
                                    {{ __('backend.yes') }}
                                </label>
                                &nbsp; &nbsp;
                                <label class="ui-check ui-check-md">
                                    {!! Form::radio('is_new_price_show','0',($seasonpassAddon->is_new_price_show==0) ? true : false, array('id' => 'is_new_price_show2','class'=>'has-value')) !!}
                                    <i class="dark-white"></i>
                                    {{ __('backend.no') }}
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="link_status"
                                class="col-sm-2 form-control-label">{!!  __('backend.status') !!}</label>
                        <div class="col-sm-10">
                            <div class="radio">
                                <label class="ui-check ui-check-md">
                                    {!! Form::radio('status','1',($seasonpassAddon->status==1) ? true : false, array('id' => 'status1','class'=>'has-value')) !!}
                                    <i class="dark-white"></i>
                                    {{ __('backend.active') }}
                                </label>
                                &nbsp; &nbsp;
                                <label class="ui-check ui-check-md">
                                    {!! Form::radio('status','0',($seasonpassAddon->status==0) ? true : false, array('id' => 'status2','class'=>'has-value')) !!}
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
        function deleteMedia(id) {
            document.getElementById('media_' + id).style.display = 'none';
            document.getElementById('media_delete_' + id).value = '1';
            document.getElementById('undo_' + id).style.display = 'block';
        }

        function undoDelete(id) {
            document.getElementById('media_' + id).style.display = 'block';
            document.getElementById('media_delete_' + id).value = '0';
            document.getElementById('undo_' + id).style.display = 'none';
        }
    </script>
@endpush
