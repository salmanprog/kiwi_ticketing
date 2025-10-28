<div class="tab-pane {{ ( Session::get('active_tab') == 'infoTab') ? 'active' : '' }}" id="tab-1">
    <div class="p-a-md">
        <h5 style="display: flex; align-items: center; gap: 10px; color: #2c3e50; font-weight: 600; margin-bottom: 1.5rem;">
            <i class="fas fa-cog" style="color: #A0C242;"></i>
            {!! __('Account Setting') !!}
        </h5>
    </div>
    
    <div class="p-a-md col-md-12">
        <!-- Website Title for Each Language -->
        @foreach(Helper::languagesList() as $ActiveLanguage)
            <div class="form-group" style="margin-bottom: 1.5rem;">
                <label style="font-weight: 600; color: #2c3e50; margin-bottom: 0.5rem; display: block;">
                    {!! __('backend.websiteTitle') !!}
                    <span style="background: linear-gradient(135deg, #A0C242 0%, #8AAE38 100%); color: white; padding: 2px 8px; border-radius: 12px; font-size: 0.75rem; margin-left: 8px;">
                        {!! @Helper::languageName($ActiveLanguage) !!}
                    </span>
                </label>
                {!! Form::text('site_title_'.@$ActiveLanguage->code, $Setting->{'site_title_'.@$ActiveLanguage->code}, array(
                    'placeholder' => '',
                    'class' => 'form-control',
                    'maxlength' => 191, 
                    'dir' => @$ActiveLanguage->direction,
                    'style' => 'border: 2px solid #e9ecef; border-radius: 8px; padding: 0.75rem 1rem; font-size: 1rem; transition: all 0.3s ease;'
                )) !!}
            </div>
        @endforeach

        <!-- Description for Each Language -->
        @foreach(Helper::languagesList() as $ActiveLanguage)
            <div class="form-group" style="margin-bottom: 1.5rem;">
                <label style="font-weight: 600; color: #2c3e50; margin-bottom: 0.5rem; display: block;">
                    {!! __('Description') !!}
                    <span style="background: linear-gradient(135deg, #A0C242 0%, #8AAE38 100%); color: white; padding: 2px 8px; border-radius: 12px; font-size: 0.75rem; margin-left: 8px;">
                        {!! @Helper::languageName($ActiveLanguage) !!}
                    </span>
                </label>
                {!! Form::textarea('site_desc_'.@$ActiveLanguage->code, $Setting->{'site_desc_'.@$ActiveLanguage->code}, array(
                    'placeholder' => '',
                    'class' => 'form-control',
                    'maxlength' => 191, 
                    'dir' => @$ActiveLanguage->direction,
                    'rows' => '2',
                    'style' => 'border: 2px solid #e9ecef; border-radius: 8px; padding: 0.75rem 1rem; font-size: 1rem; transition: all 0.3s ease; resize: vertical;'
                )) !!}
            </div>
        @endforeach

        <!-- External API URL for Each Language -->
        @foreach(Helper::languagesList() as $ActiveLanguage)
            <div class="form-group" style="margin-bottom: 1.5rem;">
                <label style="font-weight: 600; color: #2c3e50; margin-bottom: 0.5rem; display: block;">
                    {!! __('External API Url') !!}
                    <span style="background: linear-gradient(135deg, #A0C242 0%, #8AAE38 100%); color: white; padding: 2px 8px; border-radius: 12px; font-size: 0.75rem; margin-left: 8px;">
                        {!! @Helper::languageName($ActiveLanguage) !!}
                    </span>
                </label>
                {!! Form::text('external_api_link_'.@$ActiveLanguage->code, $Setting->{'external_api_link_'.@$ActiveLanguage->code}, array(
                    'placeholder' => '',
                    'class' => 'form-control',
                    'maxlength' => 191, 
                    'dir' => @$ActiveLanguage->direction,
                    'style' => 'border: 2px solid #e9ecef; border-radius: 8px; padding: 0.75rem 1rem; font-size: 1rem; transition: all 0.3s ease;'
                )) !!}
            </div>
        @endforeach

        <!-- Auth-Code for Each Language -->
        @foreach(Helper::languagesList() as $ActiveLanguage)
            <div class="form-group" style="margin-bottom: 1.5rem;">
                <label style="font-weight: 600; color: #2c3e50; margin-bottom: 0.5rem; display: block;">
                    {!! __('Auth-Code') !!}
                    <span style="background: linear-gradient(135deg, #A0C242 0%, #8AAE38 100%); color: white; padding: 2px 8px; border-radius: 12px; font-size: 0.75rem; margin-left: 8px;">
                        {!! @Helper::languageName($ActiveLanguage) !!}
                    </span>
                </label>
                {!! Form::text('auth_code_'.@$ActiveLanguage->code, $Setting->{'auth_code_'.@$ActiveLanguage->code}, array(
                    'placeholder' => '',
                    'class' => 'form-control',
                    'maxlength' => 191, 
                    'dir' => @$ActiveLanguage->direction,
                    'style' => 'border: 2px solid #e9ecef; border-radius: 8px; padding: 0.75rem 1rem; font-size: 1rem; transition: all 0.3s ease;'
                )) !!}
            </div>
        @endforeach
    </div>
</div>

<style>
    /* Add focus effects for all form controls */
    .form-control:focus {
        border-color: #A0C242 !important;
        box-shadow: 0 0 0 3px rgba(160, 194, 66, 0.1) !important;
        outline: none !important;
    }
</style>