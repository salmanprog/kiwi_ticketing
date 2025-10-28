@extends('dashboard.layouts.auth')
@section('title', __('backend.signedInToControl'))
@section('content')

<style>
@import url('https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap');

:root {
    --primary-color: #A0C242;
    --primary-dark: #8AAE38;
    --secondary-color: #2e3e4e;
    --accent-color: #FF6B35;
    --text-dark: #2c3e50;
    --text-light: #6c757d;
    --white: #ffffff;
    --gradient-bg: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}

* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body {
    font-family: 'Outfit', sans-serif;
    background: var(--gradient-bg);
    background-size: 400% 400%;
    animation: gradientShift 15s ease infinite;
    display: flex;
    align-items: center;
    justify-content: center;
    min-height: 100vh;
    overflow: hidden;
    position: relative;
}

/* Animated Background */
body::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: 
        radial-gradient(circle at 20% 80%, rgba(160, 194, 66, 0.1) 0%, transparent 50%),
        radial-gradient(circle at 80% 20%, rgba(255, 107, 53, 0.1) 0%, transparent 50%),
        radial-gradient(circle at 40% 40%, rgba(102, 126, 234, 0.1) 0%, transparent 50%);
    animation: float 20s ease-in-out infinite;
}

@keyframes gradientShift {
    0% { background-position: 0% 50%; }
    50% { background-position: 100% 50%; }
    100% { background-position: 0% 50%; }
}

@keyframes float {
    0%, 100% { transform: translateY(0px) rotate(0deg); }
    50% { transform: translateY(-20px) rotate(180deg); }
}

/* Main Auth Container */
.auth-container {
    width: 95%;
    max-width: 1200px;
    min-height: 650px;
    background: rgba(255, 255, 255, 0.95);
    backdrop-filter: blur(20px);
    border-radius: 24px;
    box-shadow: 
        0 25px 50px rgba(0, 0, 0, 0.15),
        inset 0 1px 0 rgba(255, 255, 255, 0.4);
    display: flex;
    overflow: hidden;
    animation: slideUp 0.8s cubic-bezier(0.25, 0.46, 0.45, 0.94) both;
}

@keyframes slideUp {
    from { 
        opacity: 0;
        transform: translateY(30px) scale(0.95);
    }
    to { 
        opacity: 1;
        transform: translateY(0) scale(1);
    }
}

/* Left Brand Section */
.auth-brand {
    flex: 1;
    background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
    padding: 3rem;
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: flex-start;
    color: white;
    position: relative;
    overflow: hidden;
}

.auth-brand::before {
    content: '';
    position: absolute;
    top: -50%;
    left: -50%;
    width: 200%;
    height: 200%;
    background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
    animation: rotate 20s linear infinite;
}

@keyframes rotate {
    from { transform: rotate(0deg); }
    to { transform: rotate(360deg); }
}

.brand-content {
    position: relative;
    z-index: 2;
}

.brand-logo {
    display: flex;
    align-items: center;
    gap: 15px;
    margin-bottom: 2rem;
}

.brand-logo img {
    width: 60px;
    height: 60px;
}

.brand-logo h1 {
    font-size: 2rem;
    font-weight: 700;
    color: white;
}

.brand-tagline {
    font-size: 1.1rem;
    opacity: 0.9;
    margin-bottom: 3rem;
    line-height: 1.6;
}

.features-list {
    list-style: none;
    margin-bottom: 2rem;
}

.features-list li {
    display: flex;
    align-items: center;
    gap: 15px;
    margin-bottom: 1.2rem;
    font-size: 1rem;
    font-weight: 500;
}

.features-list i {
    width: 40px;
    height: 40px;
    background: rgba(255, 255, 255, 0.2);
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.1rem;
    backdrop-filter: blur(10px);
}

.brand-footer {
    margin-top: auto;
    font-size: 0.9rem;
    opacity: 0.8;
}

/* Right Login Form */
.auth-form-section {
    flex: 0 0 480px;
    padding: 3rem;
    display: flex;
    flex-direction: column;
    justify-content: center;
    background: var(--white);
    position: relative;
}

.form-header {
    text-align: center;
    margin-bottom: 2.5rem;
}

.form-header h2 {
    font-size: 1.8rem;
    font-weight: 700;
    color: var(--text-dark);
    margin-bottom: 0.5rem;
}

.form-header p {
    color: var(--text-light);
    font-size: 1rem;
}

/* Form Styles */
.login-form {
    width: 100%;
}

.form-group {
    position: relative;
    margin-bottom: 1.8rem;
}

.form-input {
    width: 100%;
    padding: 1rem 1.2rem;
    border: 2px solid #e9ecef;
    border-radius: 12px;
    font-size: 1rem;
    font-weight: 500;
    background: #f8f9fa;
    transition: all 0.3s ease;
    color: var(--text-dark);
}

.form-input:focus {
    outline: none;
    border-color: var(--primary-color);
    background: var(--white);
    box-shadow: 0 0 0 3px rgba(160, 194, 66, 0.1);
    transform: translateY(-2px);
}

.form-label {
    position: absolute;
    left: 1.2rem;
    top: 50%;
    transform: translateY(-50%);
    color: var(--text-light);
    font-size: 1rem;
    font-weight: 500;
    transition: all 0.3s ease;
    pointer-events: none;
    background: var(--white);
    padding: 0 0.5rem;
}

.form-input:focus + .form-label,
.form-input:not(:placeholder-shown) + .form-label {
    top: 0;
    font-size: 0.85rem;
    color: var(--primary-color);
    font-weight: 600;
}

/* Error States */
.has-error .form-input {
    border-color: #dc3545;
    box-shadow: 0 0 0 3px rgba(220, 53, 69, 0.1);
}

.has-error .form-label {
    color: #dc3545;
}

.error-message {
    color: #dc3545;
    font-size: 0.85rem;
    margin-top: 0.5rem;
    display: flex;
    align-items: center;
    gap: 5px;
}

.error-message i {
    font-size: 0.8rem;
}

/* Password Toggle */
.password-toggle {
    position: absolute;
    right: 1.2rem;
    top: 50%;
    transform: translateY(-50%);
    background: none;
    border: none;
    color: var(--text-light);
    cursor: pointer;
    font-size: 1.1rem;
    transition: color 0.3s ease;
}

.password-toggle:hover {
    color: var(--primary-color);
}

/* Remember Me Checkbox */
.remember-me {
    display: flex;
    align-items: center;
    gap: 10px;
    margin-bottom: 1.5rem;
    font-size: 0.95rem;
    color: var(--text-dark);
}

.remember-checkbox {
    width: 18px;
    height: 18px;
    border: 2px solid #e9ecef;
    border-radius: 4px;
    background: white;
    cursor: pointer;
    position: relative;
    transition: all 0.3s ease;
}

.remember-checkbox.checked {
    background: var(--primary-color);
    border-color: var(--primary-color);
}

.remember-checkbox.checked::after {
    content: '✓';
    position: absolute;
    color: white;
    font-size: 12px;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
}

/* Captcha Styling */
.captcha-container {
    margin-bottom: 1.5rem;
    padding: 1rem;
    background: #f8f9fa;
    border-radius: 12px;
    border: 1px solid #e9ecef;
}

/* Submit Button */
.submit-btn {
    width: 100%;
    padding: 1rem 1.5rem;
    background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
    border: none;
    border-radius: 12px;
    color: white;
    font-size: 1rem;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
    box-shadow: 0 4px 15px rgba(160, 194, 66, 0.3);
    position: relative;
    overflow: hidden;
    margin-bottom: 1.5rem;
}

.submit-btn::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
    transition: left 0.5s;
}

.submit-btn:hover::before {
    left: 100%;
}

.submit-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(160, 194, 66, 0.4);
}

.submit-btn:active {
    transform: translateY(0);
}

.submit-btn:disabled {
    opacity: 0.7;
    cursor: not-allowed;
    transform: none;
}

/* Social Login Buttons */
.social-login {
    margin-bottom: 1.5rem;
}

.social-divider {
    text-align: center;
    margin: 1.5rem 0;
    position: relative;
    color: var(--text-light);
    font-size: 0.9rem;
}

.social-divider::before {
    content: '';
    position: absolute;
    top: 50%;
    left: 0;
    right: 0;
    height: 1px;
    background: #e9ecef;
    z-index: 1;
}

.social-divider span {
    background: white;
    padding: 0 1rem;
    position: relative;
    z-index: 2;
}

.social-buttons {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 0.8rem;
    margin-bottom: 1rem;
}

.social-btn {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    padding: 0.8rem;
    border: 1px solid #e9ecef;
    border-radius: 8px;
    background: white;
    color: var(--text-dark);
    font-size: 0.9rem;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.3s ease;
    text-decoration: none;
}

.social-btn:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
}

.social-btn.facebook { background: #1877F2; color: white; border-color: #1877F2; }
.social-btn.twitter { background: #1DA1F2; color: white; border-color: #1DA1F2; }
.social-btn.google { background: #DB4437; color: white; border-color: #DB4437; }
.social-btn.linkedin { background: #0077B5; color: white; border-color: #0077B5; }
.social-btn.github { background: #333; color: white; border-color: #333; }
.social-btn.bitbucket { background: #0052CC; color: white; border-color: #0052CC; }

/* Additional Options */
.additional-options {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-top: 1rem;
}

.register-btn, .forgot-link {
    color: var(--primary-color);
    text-decoration: none;
    font-weight: 500;
    font-size: 0.95rem;
    transition: color 0.3s ease;
    display: flex;
    align-items: center;
    gap: 5px;
}

.register-btn:hover, .forgot-link:hover {
    color: var(--primary-dark);
    text-decoration: underline;
}

/* Security Badge */
.security-badge {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    margin-top: 1rem;
    font-size: 0.85rem;
    color: var(--text-light);
}

.security-badge i {
    color: var(--primary-color);
}

/* Responsive Design */
@media (max-width: 968px) {
    .auth-container {
        flex-direction: column;
        max-width: 450px;
        min-height: auto;
    }
    
    .auth-brand {
        padding: 2rem;
        text-align: center;
        align-items: center;
    }
    
    .auth-form-section {
        flex: none;
        padding: 2rem;
    }
    
    .brand-logo {
        justify-content: center;
    }
    
    .social-buttons {
        grid-template-columns: 1fr;
    }
}

@media (max-width: 480px) {
    .auth-container {
        margin: 1rem;
        width: calc(100% - 2rem);
    }
    
    .auth-brand,
    .auth-form-section {
        padding: 1.5rem;
    }
    
    .additional-options {
        flex-direction: column;
        gap: 1rem;
        align-items: flex-start;
    }
}

/* Loading Animation */
.btn-loading {
    pointer-events: none;
    opacity: 0.8;
}

.btn-loading::after {
    content: '';
    position: absolute;
    width: 20px;
    height: 20px;
    top: 50%;
    left: 50%;
    margin: -10px 0 0 -10px;
    border: 2px solid transparent;
    border-top: 2px solid white;
    border-radius: 50%;
    animation: spin 1s linear infinite;
}

@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}
</style>

<div class="auth-container">
    <!-- Left Brand Section -->
    <div class="auth-brand">
        <div class="brand-content">
          <div class="brand-logo">
              <img src="{{ asset('assets/dashboard/images/favicon(kiwi).png') }}" alt="Icons">
                <h1>KiwiTicketing</h1>
            </div>
            
            <p class="brand-tagline">
                Professional Ticketing Management System with Advanced Analytics and Real-time Monitoring
            </p>
            
            <ul class="features-list">
                <li><i class="fas fa-shield-alt"></i> Enterprise-grade Security</li>
                <li><i class="fas fa-chart-line"></i> Advanced Analytics Dashboard</li>
                <li><i class="fas fa-bolt"></i> Real-time Order Processing</li>
                <li><i class="fas fa-users-cog"></i> Multi-level User Management</li>
                <li><i class="fas fa-mobile-alt"></i> Fully Responsive Design</li>
            </ul>
            
                       <div class="brand-footer">
           <div class="p-a text-xs">
              <div class="pull-right text-muted">
                    &copy;<?php echo date("Y") ?> Copyright
            <strong>KiwiTicketing {{ Helper::system_version() }}</strong>
                    <a ui-scroll-to="content"><i class="fa fa-long-arrow-up p-x-sm"></i></a>
        </div>
        <div class="nav">
            &nbsp;
        </div>
    </div>
</div>

        </div>
    </div>

    <!-- Right Login Form -->
    <div class="auth-form-section">
        <div class="form-header">
            <h2>{{ __('backend.signedInToControl') }}</h2>
            <p>Sign in to your admin dashboard</p>
        </div>

        <form name="form" method="POST" action="{{ url('/'.config('smartend.backend_path').'/login') }}" class="login-form" id="loginForm" onsubmit="document.getElementById('login_form_submit').disabled = true; return true;">
            {{ csrf_field() }}
            
            <!-- Error Messages -->
            @if($errors->any())
                <div class="error-message">
                    <i class="fas fa-exclamation-circle"></i>
                    @foreach($errors->all() as $error)
                        <div>{{ $error }}</div>
                    @endforeach
                </div>
            @endif
            
            <!-- Email Input -->
            <div class="form-group float-label {{ $errors->has('email') ? 'has-error' : '' }}">
                <input type="email" name="email" value="{{ old('email') }}" class="form-input" placeholder=" " required>
                <label class="form-label">{{ __('backend.connectEmail') }}</label>
            </div>
            
            <!-- Password Input -->
            <div class="form-group float-label {{ $errors->has('password') ? 'has-error' : '' }}">
                <input type="password" name="password" class="form-input" placeholder=" " required id="password">
                <label class="form-label">{{ __('backend.connectPassword') }}</label>
                <button type="button" class="password-toggle" onclick="togglePassword()">
                    <i class="fas fa-eye"></i>
                </button>
                @if ($errors->has('password'))
                    <div class="error-message">
                        <i class="fas fa-exclamation-circle"></i>
                        <strong>{{ $errors->first('password') }}</strong>
                    </div>
                @endif
            </div>
            
            <!-- Captcha -->
            @if(config('smartend.nocaptcha_status'))
                <div class="captcha-container">
                    {!! NoCaptcha::renderJs(@Helper::currentLanguage()->code) !!}
                    {!! NoCaptcha::display() !!}
                </div>
            @endif
            
            <!-- Remember Me -->
            <div class="remember-me">
                <div class="remember-checkbox" id="rememberCheckbox"></div>
                <input type="checkbox" name="remember" id="remember" style="display: none;">
                <label for="remember">{{ __('backend.keepMeSignedIn') }}</label>
            </div>
            
            <!-- Submit Button -->
            <button type="submit" id="login_form_submit" class="submit-btn">
                <span>{{ __('backend.signIn') }}</span>
            </button>
        </form>

        <!-- Social Login Buttons -->
        @if(config('smartend.facebook_status') || config('smartend.twitter_status') || config('smartend.google_status') || 
            config('smartend.linkedin_status') || config('smartend.github_status') || config('smartend.bitbucket_status'))
            <div class="social-divider">
                <span>Or continue with</span>
            </div>
            
            <div class="social-buttons">
                @if(config('smartend.facebook_status') && config('smartend.facebook_id') && config('smartend.facebook_secret'))
                    <a href="{{ route('social.oauth', 'facebook') }}" class="social-btn facebook">
                        <i class="fab fa-facebook-f"></i> Facebook
                    </a>
                @endif
                
                @if(config('smartend.twitter_status') && config('smartend.twitter_id') && config('smartend.twitter_secret'))
                    <a href="{{ route('social.oauth', 'twitter') }}" class="social-btn twitter">
                        <i class="fab fa-twitter"></i> Twitter
                    </a>
                @endif
                
                @if(config('smartend.google_status') && config('smartend.google_id') && config('smartend.google_secret'))
                    <a href="{{ route('social.oauth', 'google') }}" class="social-btn google">
                        <i class="fab fa-google"></i> Google
                    </a>
                @endif
                
                @if(config('smartend.linkedin_status') && config('smartend.linkedin_id') && config('smartend.linkedin_secret'))
                    <a href="{{ route('social.oauth', 'linkedin') }}" class="social-btn linkedin">
                        <i class="fab fa-linkedin-in"></i> LinkedIn
                    </a>
                @endif
                
                @if(config('smartend.github_status') && config('smartend.github_id') && config('smartend.github_secret'))
                    <a href="{{ route('social.oauth', 'github') }}" class="social-btn github">
                        <i class="fab fa-github"></i> GitHub
                    </a>
                @endif
                
                @if(config('smartend.bitbucket_status') && config('smartend.bitbucket_id') && config('smartend.bitbucket_secret'))
                    <a href="{{ route('social.oauth', 'bitbucket') }}" class="social-btn bitbucket">
                        <i class="fab fa-bitbucket"></i> Bitbucket
                    </a>
                @endif
            </div>
        @endif

        <!-- Additional Options -->
        <div class="additional-options">
            @if(Helper::GeneralWebmasterSettings("register_status"))
                <a href="{{ url('/'.config('smartend.backend_path').'/register') }}" class="register-btn">
                    <i class="fas fa-user-plus"></i> {{ __('backend.createNewAccount') }}
                </a>
            @endif
            
            @if(config('smartend.mail_driver') != "" && config('smartend.mail_username') != "" && config('smartend.mail_password'))
                <a href="{{ url('/'.config('smartend.backend_path').'/password/reset') }}" class="forgot-link">
                    <i class="fas fa-key"></i> {{ __('backend.forgotPassword') }}
                </a>
            @endif
             <a href="{{ url('/'.config('smartend.backend_path').'/password/reset') }}" class="forgot-link">
                <i class="fas fa-key"></i> {{ __('backend.forgotPassword') }}
              </a>
        </div>

        <div class="security-badge">
            <i class="fas fa-lock"></i>
            <span>Secure SSL Encryption</span>
        </div>
    </div>
</div>

  <script>
    function togglePassword() {
      const passwordInput = document.getElementById('password');
      const toggleIcon = document.querySelector('.password-toggle i');
      
      if (passwordInput.type === 'password') {
          passwordInput.type = 'text';
          toggleIcon.className = 'fas fa-eye-slash';
      } else {
          passwordInput.type = 'password';
          toggleIcon.className = 'fas fa-eye';
      }
    }

    // Custom checkbox functionality
    const rememberCheckbox = document.getElementById('rememberCheckbox');
    const rememberInput = document.getElementById('remember');

    rememberCheckbox.addEventListener('click', function() {
      this.classList.toggle('checked');
      rememberInput.checked = this.classList.contains('checked');
    });

    // Form submission handling
    document.getElementById('loginForm').addEventListener('submit', function(e) {
      const submitBtn = document.getElementById('login_form_submit');
      submitBtn.classList.add('btn-loading');
      submitBtn.innerHTML = '<i class="fas fa-spinner"></i> Signing In...';
    });

    // Add floating label functionality
    document.querySelectorAll('.form-input').forEach(input => {
      input.addEventListener('focus', function() {
          this.parentElement.classList.add('focused');
      });
      
      input.addEventListener('blur', function() {
          if (!this.value) {
              this.parentElement.classList.remove('focused');
          }
      });
      
      // Initialize focused state if value exists
      if (input.value) {
          input.parentElement.classList.add('focused');
      }
    });

    // Add some interactive effects
    document.querySelectorAll('.form-input').forEach(input => {
      input.addEventListener('focus', function() {
          this.style.transform = 'translateY(-2px)';
      });
      
      input.addEventListener('blur', function() {
          this.style.transform = 'translateY(0)';
      });
   });
  </script>

@endsection