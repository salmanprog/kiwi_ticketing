<div class="tab-pane {{ (Session::get('active_tab') == 'contactsTab') ? 'active' : '' }}" id="tab-2">
    <div class="p-a-md">
        <h5 style="display: flex; align-items: center; gap: 10px; color: #2c3e50; font-weight: 600; margin-bottom: 1.5rem;">
            <i class="fas fa-address-book" style="color: #A0C242;"></i>
            {!! __('backend.siteContactsSettings') !!}
        </h5>
    </div>
    
    <div class="p-a-md col-md-12">
        <!-- Address Fields for Each Language -->
        @foreach(Helper::languagesList() as $ActiveLanguage)
            <div class="form-group" style="margin-bottom: 1.5rem;">
                <label style="font-weight: 600; color: #2c3e50; margin-bottom: 0.5rem; display: block;">
                    {!! __('backend.contactAddress') !!}
                    <!-- <span style="background: linear-gradient(135deg, #A0C242 0%, #8AAE38 100%); color: white; padding: 2px 8px; border-radius: 12px; font-size: 0.75rem; margin-left: 8px;">
                        {!! @Helper::languageName($ActiveLanguage) !!}
                    </span> -->
                </label>
                {!! Form::text('contact_t1_'.@$ActiveLanguage->code, $Setting->{'contact_t1_'.@$ActiveLanguage->code}, array(
                    'placeholder' => __('backend.enterAddress'),
                    'class' => 'form-control',
                    'dir' => @$ActiveLanguage->direction,
                    'style' => 'border: 2px solid #e9ecef; border-radius: 8px; padding: 0.75rem 1rem; font-size: 1rem; transition: all 0.3s ease;'
                )) !!}
            </div>
        @endforeach

        <!-- Phone Field -->
        <div class="form-group" style="margin-bottom: 1.5rem;">
            <label style="font-weight: 600; color: #2c3e50; margin-bottom: 0.5rem; display: flex; align-items: center; gap: 8px;">
                <i class="fas fa-phone" style="color: #A0C242;"></i>
                {!! __('backend.contactPhone') !!}
            </label>
            {!! Form::text('contact_t3', $Setting->contact_t3, [
                'placeholder' => __('backend.contactPhone'),
                'class' => 'form-control',
                'dir' => 'ltr',
                'style' => 'border: 2px solid #e9ecef; border-radius: 8px; padding: 0.75rem 1rem; font-size: 1rem; transition: all 0.3s ease;',
                'pattern' => '^\+\(\d{1,3}\) \d{6,10}$',
                'title' => 'Phone number format should be +(123) 01234567',
                'maxlength' => 15,
                'required' => true
            ]) !!}
        </div>

        <!-- Fax Field -->
        <div class="form-group" style="margin-bottom: 1.5rem;">
            <label style="font-weight: 600; color: #2c3e50; margin-bottom: 0.5rem; display: flex; align-items: center; gap: 8px;">
                <i class="fas fa-fax" style="color: #A0C242;"></i>
                {!! __('backend.contactFax') !!}
            </label>
            {!! Form::text('contact_t4', $Setting->contact_t4, array(
                'placeholder' => __('backend.contactFax'),
                'class' => 'form-control',
                'dir' => 'ltr',
                'pattern' => '^\+\(\d{1,3}\) \d{6,10}$',
                'maxlength' => 15,
                'required' => true,
                'style' => 'border: 2px solid #e9ecef; border-radius: 8px; padding: 0.75rem 1rem; font-size: 1rem; transition: all 0.3s ease;'
            )) !!}
        </div>

        <!-- Mobile Field -->
        <div class="form-group" style="margin-bottom: 1.5rem;">
            <label style="font-weight: 600; color: #2c3e50; margin-bottom: 0.5rem; display: flex; align-items: center; gap: 8px;">
                <i class="fas fa-mobile-alt" style="color: #A0C242;"></i>
                {!! __('backend.contactMobile') !!}
            </label>
            {!! Form::text('contact_t5', $Setting->contact_t5, array(
                'placeholder' => __('backend.contactMobile'),
                'class' => 'form-control',
                'dir' => 'ltr',
                'pattern' => '^\+\(\d{1,3}\) \d{6,10}$',
                'maxlength' => 15,
                'required' => true,
                'style' => 'border: 2px solid #e9ecef; border-radius: 8px; padding: 0.75rem 1rem; font-size: 1rem; transition: all 0.3s ease;'
            )) !!}
        </div>

        <!-- Email Field -->
        <div class="form-group" style="margin-bottom: 1.5rem;">
            <label style="font-weight: 600; color: #2c3e50; margin-bottom: 0.5rem; display: flex; align-items: center; gap: 8px;">
                <i class="fas fa-envelope" style="color: #A0C242;"></i>
                {!! __('backend.contactEmail') !!}
            </label>
            {!! Form::text('contact_t6', $Setting->contact_t6, [
                'placeholder' => __('backend.contactEmail'),
                'class' => 'form-control',
                'dir' => 'ltr',
                'type' => 'email',
                'required' => true,
                'maxlength' => 50,
                'style' => 'border: 2px solid #e9ecef; border-radius: 8px; padding: 0.75rem 1rem; font-size: 1rem; transition: all 0.3s ease;'
            ]) !!}
        </div>

        <!-- Working Hours for Each Language -->
        @foreach(Helper::languagesList() as $ActiveLanguage)
            <div class="form-group" style="margin-bottom: 1.5rem;">
                <label style="font-weight: 600; color: #2c3e50; margin-bottom: 0.5rem; display: block;">
                    {!! __('backend.worksTime') !!}
                    <!-- <span style="background: linear-gradient(135deg, #A0C242 0%, #8AAE38 100%); color: white; padding: 2px 8px; border-radius: 12px; font-size: 0.75rem; margin-left: 8px;">
                        {!! @Helper::languageName($ActiveLanguage) !!}
                    </span> -->
                </label>
                {!! Form::text('contact_t7_'.@$ActiveLanguage->code, $Setting->{'contact_t7_'.@$ActiveLanguage->code}, array(
                    'placeholder' => __('backend.enterWorkingHours'),
                    'class' => 'form-control',
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