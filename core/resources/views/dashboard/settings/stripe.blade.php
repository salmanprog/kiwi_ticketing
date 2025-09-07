<div
    class="tab-pane {{ ( Session::get('active_tab') == 'codeTab') ? 'active' : '' }}"
    id="tab-7">
    <div class="p-a-md"><h5><i class="material-icons">&#xe30c;</i>
            &nbsp; {!!  __('Stripe Info') !!}</h5></div>
    <div class="p-a-md col-md-12">
        @foreach(Helper::languagesList() as $ActiveLanguage)
            <div class="form-group">
                <label>{!!  __('Live Publish Key') !!}
                </label> {!! @Helper::languageName($ActiveLanguage) !!}
                {!! Form::text('stripe_live_pk_'.@$ActiveLanguage->code,$Setting->{'stripe_live_pk_'.@$ActiveLanguage->code}, array('placeholder' => '','class' => 'form-control','maxlength'=>191, 'dir'=>@$ActiveLanguage->direction)) !!}
            </div>
        @endforeach
        @foreach(Helper::languagesList() as $ActiveLanguage)
            <div class="form-group">
                <label>{!!  __('Live Seceret Key') !!}
                </label> {!! @Helper::languageName($ActiveLanguage) !!}
                {!! Form::text('stripe_live_sk_'.@$ActiveLanguage->code,$Setting->{'stripe_live_sk_'.@$ActiveLanguage->code}, array('placeholder' => '','class' => 'form-control','maxlength'=>191, 'dir'=>@$ActiveLanguage->direction)) !!}
            </div>
        @endforeach
        @foreach(Helper::languagesList() as $ActiveLanguage)
            <div class="form-group">
                <label>{!!  __('Test Publish Key') !!}
                </label> {!! @Helper::languageName($ActiveLanguage) !!}
               {!! Form::text('stripe_test_pk_'.@$ActiveLanguage->code,$Setting->{'stripe_test_pk_'.@$ActiveLanguage->code}, array('placeholder' => '','class' => 'form-control','maxlength'=>191, 'dir'=>@$ActiveLanguage->direction)) !!}
            </div>
        @endforeach
        <div class="form-group">
            <label>{!!  __('Test Seceret Key') !!}</label>
            {!! Form::text('stripe_test_sk_'.@$ActiveLanguage->code,$Setting->{'stripe_test_sk_'.@$ActiveLanguage->code}, array('placeholder' => '','class' => 'form-control','maxlength'=>191, 'dir'=>@$ActiveLanguage->direction)) !!}
        </div>
        <div class="form-group row">
            <label for="link_status"
                    class="col-sm-2 form-control-label">{!!  __('Live Key') !!}</label>
            <div class="col-sm-10">
                <div class="radio">
                    <label class="ui-check ui-check-md">
                        {!! Form::radio('stripe_live_activate_en','1',($Setting->stripe_live_activate_en==1) ? true : false, array('id' => 'status1','class'=>'has-value')) !!}
                        <i class="dark-white"></i>
                        {{ __('backend.active') }}
                    </label>
                    &nbsp; &nbsp;
                    <label class="ui-check ui-check-md">
                        {!! Form::radio('stripe_live_activate_en','0',($Setting->stripe_live_activate_en==0) ? true : false, array('id' => 'status2','class'=>'has-value')) !!}
                        <i class="dark-white"></i>
                        {{ __('backend.notActive') }}
                    </label>
                </div>
            </div>
        </div>
    </div>

</div>
