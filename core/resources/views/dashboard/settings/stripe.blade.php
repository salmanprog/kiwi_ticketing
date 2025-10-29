<div class="tab-pane {{ ( Session::get('active_tab') == 'codeTab') ? 'active' : '' }}" id="tab-7">
    <div class="p-a-md" style="background: linear-gradient(135deg, #A0C242 0%, #8AAE38 100%); border-radius: 8px 8px 0 0; margin: -1rem -1rem 1.5rem -1rem; padding: 1.5rem 2rem;">
        <h5 style="color: white; margin: 0; display: flex; align-items: center; gap: 10px; font-weight: 600;">
            <i class="material-icons" style="color: white; font-size: 24px;">&#xe30c;</i>
            {!! __('Stripe Info') !!}
        </h5>
    </div>
    
    <div class="p-a-md col-md-12" style="padding: 0 1.5rem 1.5rem 1.5rem;">
        @foreach(Helper::languagesList() as $ActiveLanguage)
            <div class="form-group" style="margin-bottom: 1.5rem; padding: 1rem; background: #f8f9fa; border-radius: 8px; border-left: 4px solid #A0C242;">
                <label style="font-weight: 600; color: #2c3e50; margin-bottom: 0.5rem; display: block;">
                    {!! __('Live Publish Key') !!} - {!! @Helper::languageName($ActiveLanguage) !!}
                </label>
                {!! Form::text('stripe_live_pk_'.@$ActiveLanguage->code,$Setting->{'stripe_live_pk_'.@$ActiveLanguage->code}, array(
                    'placeholder' => 'Enter Live Publish Key',
                    'class' => 'form-control',
                    'maxlength'=>191, 
                    'dir'=>@$ActiveLanguage->direction,
                    'style' => 'border: 2px solid #e9ecef; border-radius: 6px; padding: 0.75rem 1rem; transition: all 0.3s ease;'
                )) !!}
            </div>
        @endforeach
        
        @foreach(Helper::languagesList() as $ActiveLanguage)
            <div class="form-group" style="margin-bottom: 1.5rem; padding: 1rem; background: #f8f9fa; border-radius: 8px; border-left: 4px solid #A0C242;">
                <label style="font-weight: 600; color: #2c3e50; margin-bottom: 0.5rem; display: block;">
                    {!! __('Live Secret Key') !!} - {!! @Helper::languageName($ActiveLanguage) !!}
                </label>
                {!! Form::text('stripe_live_sk_'.@$ActiveLanguage->code,$Setting->{'stripe_live_sk_'.@$ActiveLanguage->code}, array(
                    'placeholder' => 'Enter Live Secret Key',
                    'class' => 'form-control',
                    'maxlength'=>191, 
                    'dir'=>@$ActiveLanguage->direction,
                    'style' => 'border: 2px solid #e9ecef; border-radius: 6px; padding: 0.75rem 1rem; transition: all 0.3s ease;'
                )) !!}
            </div>
        @endforeach
        
        @foreach(Helper::languagesList() as $ActiveLanguage)
            <div class="form-group" style="margin-bottom: 1.5rem; padding: 1rem; background: #f8f9fa; border-radius: 8px; border-left: 4px solid #A0C242;">
                <label style="font-weight: 600; color: #2c3e50; margin-bottom: 0.5rem; display: block;">
                    {!! __('Test Publish Key') !!} - {!! @Helper::languageName($ActiveLanguage) !!}
                </label>
                {!! Form::text('stripe_test_pk_'.@$ActiveLanguage->code,$Setting->{'stripe_test_pk_'.@$ActiveLanguage->code}, array(
                    'placeholder' => 'Enter Test Publish Key',
                    'class' => 'form-control',
                    'maxlength'=>191, 
                    'dir'=>@$ActiveLanguage->direction,
                    'style' => 'border: 2px solid #e9ecef; border-radius: 6px; padding: 0.75rem 1rem; transition: all 0.3s ease;'
                )) !!}
            </div>
        @endforeach
        
        <div class="form-group" style="margin-bottom: 1.5rem; padding: 1rem; background: #f8f9fa; border-radius: 8px; border-left: 4px solid #A0C242;">
            <label style="font-weight: 600; color: #2c3e50; margin-bottom: 0.5rem; display: block;">
                {!! __('Test Secret Key') !!}
            </label>
            {!! Form::text('stripe_test_sk_'.@$ActiveLanguage->code,$Setting->{'stripe_test_sk_'.@$ActiveLanguage->code}, array(
                'placeholder' => 'Enter Test Secret Key',
                'class' => 'form-control',
                'maxlength'=>191, 
                'dir'=>@$ActiveLanguage->direction,
                'style' => 'border: 2px solid #e9ecef; border-radius: 6px; padding: 0.75rem 1rem; transition: all 0.3s ease;'
            )) !!}
        </div>
        
        <div class="form-group row" style="margin: 2rem 0 1rem 0; padding: 1.5rem; background: #f8f9fa; border-radius: 8px; border-left: 4px solid #A0C242;">
            <label for="link_status" class="col-sm-2 form-control-label" style="font-weight: 600; color: #2c3e50; padding-top: 0.5rem;">
                {!! __('Live Key') !!}
            </label>
            <div class="col-sm-10">
                <div class="radio" style="display: flex; gap: 2rem; align-items: center; flex-wrap: wrap;">
                    <label class="ui-check ui-check-md" style="display: flex; align-items: center; gap: 8px; cursor: pointer; margin: 0;">
                        {!! Form::radio('stripe_live_activate_en','1',($Setting->stripe_live_activate_en==1) ? true : false, array(
                            'id' => 'status1',
                            'class'=>'has-value'
                        )) !!}
                        <span style="width: 20px; height: 20px; border: 2px solid #e9ecef; border-radius: 50%; position: relative; transition: all 0.3s ease; display: inline-block;"></span>
                        <span style="font-weight: 500; margin-left: 0px; color: #2c3e50;">{{ __('backend.active') }}</span>
                    </label>
                    &nbsp; &nbsp;
                    <label class="ui-check ui-check-md" style="display: flex; align-items: center; gap: 8px; cursor: pointer; margin: 0;">
                        {!! Form::radio('stripe_live_activate_en','0',($Setting->stripe_live_activate_en==0) ? true : false, array(
                            'id' => 'status2',
                            'class'=>'has-value'
                        )) !!}
                        <span style="width: 20px; height: 20px; border: 2px solid #e9ecef; border-radius: 50%; position: relative; transition: all 0.3s ease; display: inline-block;"></span>
                        <span style="font-weight: 500; margin-left: 0px; color: #2c3e50;">{{ __('backend.notActive') }}</span>
                    </label>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    /* Add hover and focus effects */
    .form-control:focus {
        border-color: #A0C242 !important;
        box-shadow: 0 0 0 3px rgba(160, 194, 66, 0.1) !important;
        outline: none;
    }
    
    /* Custom radio button styling */
    .has-value:checked + span {
        border-color: #A0C242 !important;
        background: #A0C242 !important;
    }
    
    .has-value:checked + span::after {
        content: '';
        width: 8px;
        height: 8px;
        background: white;
        border-radius: 50%;
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
    }
    
    /* Responsive design */
    @media (max-width: 768px) {
        .form-group.row .col-sm-2 {
            margin-bottom: 1rem;
        }
        
        .radio {
            gap: 1rem !important;
        }
    }
</style>