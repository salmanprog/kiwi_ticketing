<div class="tab-pane {{ (Session::get('active_tab') == 'socialTab') ? 'active' : '' }}" id="tab-3">
    <div class="p-a-md">
        <h5 style="display: flex; align-items: center; gap: 10px; color: #2c3e50; font-weight: 600; margin-bottom: 1.5rem;">
            <i class="fas fa-share-alt" style="color: #A0C242;"></i>
            {!! __('backend.siteSocialSettings') !!}
        </h5>
    </div>
    
    <div class="p-a-md col-md-12">
        <!-- Facebook -->
        <div class="form-group" style="margin-bottom: 1.5rem;">
            <label style="font-weight: 600; color: #2c3e50; margin-bottom: 0.5rem; display: flex; align-items: center; gap: 10px; padding: 0.75rem 1rem; background: rgba(59, 89, 152, 0.05); border-radius: 8px; border-left: 4px solid #3b5998;">
                <i class="fab fa-facebook" style="color: #3b5998; font-size: 1.2rem;"></i>
                {!! __('backend.facebook') !!}
            </label>
            {!! Form::text('social_link1', $Setting->social_link1, array(
                'placeholder' => 'https://facebook.com/yourpage',
                'class' => 'form-control',
                'dir' => 'ltr',
                'pattern' => 'https?://.+', // Must start with http:// or https://
                'title' => 'Please enter a valid URL starting with http:// or https://',
                'style' => 'border: 2px solid #e9ecef; border-radius: 8px; padding: 0.75rem 1rem; font-size: 1rem; transition: all 0.3s ease;'
            )) !!}
        </div>

        

        <!-- LinkedIn -->
        <div class="form-group" style="margin-bottom: 1.5rem;">
            <label style="font-weight: 600; color: #2c3e50; margin-bottom: 0.5rem; display: flex; align-items: center; gap: 10px; padding: 0.75rem 1rem; background: rgba(0, 119, 181, 0.05); border-radius: 8px; border-left: 4px solid #0077b5;">
                <i class="fab fa-linkedin" style="color: #0077b5; font-size: 1.2rem;"></i>
                {!! __('backend.linkedin') !!}
            </label>
            {!! Form::text('social_link4', $Setting->social_link4, array(
                'placeholder' => 'https://linkedin.com/company/yourcompany',
                'class' => 'form-control',
                'dir' => 'ltr',
                'pattern' => 'https?://.+', // Must start with http:// or https://
                'title' => 'Please enter a valid URL starting with http:// or https://',
                'style' => 'border: 2px solid #e9ecef; border-radius: 8px; padding: 0.75rem 1rem; font-size: 1rem; transition: all 0.3s ease;'
            )) !!}
        </div>

        <!-- YouTube -->
        <div class="form-group" style="margin-bottom: 1.5rem;">
            <label style="font-weight: 600; color: #2c3e50; margin-bottom: 0.5rem; display: flex; align-items: center; gap: 10px; padding: 0.75rem 1rem; background: rgba(255, 0, 0, 0.05); border-radius: 8px; border-left: 4px solid #ff0000;">
                <i class="fab fa-youtube" style="color: #ff0000; font-size: 1.2rem;"></i>
                {!! __('backend.youtube') !!}
            </label>
            {!! Form::text('social_link5', $Setting->social_link5, array(
                'placeholder' => 'https://youtube.com/c/yourchannel',
                'class' => 'form-control',
                'dir' => 'ltr',
                'pattern' => 'https?://.+', // Must start with http:// or https://
                'title' => 'Please enter a valid URL starting with http:// or https://',
                'style' => 'border: 2px solid #e9ecef; border-radius: 8px; padding: 0.75rem 1rem; font-size: 1rem; transition: all 0.3s ease;'
            )) !!}
        </div>

        <!-- Instagram -->
        <div class="form-group" style="margin-bottom: 1.5rem;">
            <label style="font-weight: 600; color: #2c3e50; margin-bottom: 0.5rem; display: flex; align-items: center; gap: 10px; padding: 0.75rem 1rem; background: rgba(225, 48, 108, 0.05); border-radius: 8px; border-left: 4px solid #e1306c;">
                <i class="fab fa-instagram" style="color: #e1306c; font-size: 1.2rem;"></i>
                {!! __('backend.instagram') !!}
            </label>
            {!! Form::text('social_link6', $Setting->social_link6, array(
                'placeholder' => 'https://instagram.com/yourprofile',
                'class' => 'form-control',
                'dir' => 'ltr',
                'pattern' => 'https?://.+', // Must start with http:// or https://
                'title' => 'Please enter a valid URL starting with http:// or https://',
                'style' => 'border: 2px solid #e9ecef; border-radius: 8px; padding: 0.75rem 1rem; font-size: 1rem; transition: all 0.3s ease;'
            )) !!}
        </div>

    </div>
</div>

<style>
    /* Add focus effects for all form controls */
    .form-control:focus {
        border-color: #A0C242 !important;
        box-shadow: 0 0 0 3px rgba(160, 194, 66, 0.1) !important;
        outline: none !important;
    }
    
    /* Social media label hover effects */
    .form-group label {
        transition: all 0.3s ease;
    }
    
    .form-group:hover label {
        transform: translateX(5px);
    }
</style>