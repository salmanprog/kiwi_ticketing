<div
    class="tab-pane {{ ( Session::get('active_tab') == 'infoTab') ? 'active' : '' }}"
    id="tab-1">
    <div class="p-a-md"><h5><i class="material-icons">&#xe30c;</i>
            &nbsp; {!!  __('Account Setting') !!}</h5></div>
    <div class="p-a-md col-md-12">
        @foreach(Helper::languagesList() as $ActiveLanguage)
            <div class="form-group">
                <label>{!!  __('backend.websiteTitle') !!}
                </label> {!! @Helper::languageName($ActiveLanguage) !!}
                {!! Form::text('site_title_'.@$ActiveLanguage->code,$Setting->{'site_title_'.@$ActiveLanguage->code}, array('placeholder' => '','class' => 'form-control','maxlength'=>191, 'dir'=>@$ActiveLanguage->direction)) !!}
            </div>
        @endforeach
        @foreach(Helper::languagesList() as $ActiveLanguage)
            <div class="form-group">
                <label>{!!  __('Description') !!}
                </label> {!! @Helper::languageName($ActiveLanguage) !!}
                {!! Form::textarea('site_desc_'.@$ActiveLanguage->code,$Setting->{'site_desc_'.@$ActiveLanguage->code}, array('placeholder' => '','class' => 'form-control','maxlength'=>191, 'dir'=>@$ActiveLanguage->direction,'rows'=>'2')) !!}
            </div>
        @endforeach
       @foreach(Helper::languagesList() as $ActiveLanguage)
            <div class="form-group">
                <label>{!!  __('External API Url') !!}
                </label> {!! @Helper::languageName($ActiveLanguage) !!}
                {!! Form::text('external_api_link_'.@$ActiveLanguage->code,$Setting->{'external_api_link_'.@$ActiveLanguage->code}, array('placeholder' => '','class' => 'form-control','maxlength'=>191, 'dir'=>@$ActiveLanguage->direction)) !!}
            </div>
        @endforeach
        @foreach(Helper::languagesList() as $ActiveLanguage)
            <div class="form-group">
                <label>{!!  __('Auth-Code') !!}
                </label> {!! @Helper::languageName($ActiveLanguage) !!}
                {!! Form::text('auth_code_'.@$ActiveLanguage->code,$Setting->{'auth_code_'.@$ActiveLanguage->code}, array('placeholder' => '','class' => 'form-control','maxlength'=>191, 'dir'=>@$ActiveLanguage->direction)) !!}
            </div>
        @endforeach
    </div>

</div>
