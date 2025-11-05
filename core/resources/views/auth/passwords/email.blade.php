@extends('dashboard.layouts.auth')
@section('title', __('backend.forgotPassword'))
@section('content')

<style>
/* @import url('https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap'); */

@import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap');

:root {
    --primary-color: #A0C242;
    --primary-dark: #8AAE38;
    --text-dark: #2c3e50;
    --text-light: #6c757d;
    --white: #ffffff;
}

* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body {
    font-family: 'Poppins', sans-serif;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    display: flex;
    align-items: center;
    justify-content: center;
    min-height: 100vh;
    padding: 20px;
}

.auth-container {
    width: 100%;
    max-width: 1000px;
    background: white;
    border-radius: 20px;
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
    display: flex;
    min-height: 600px;
}

/* Left Side - Brand */
.auth-brand {
    flex: 1;
    background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
    padding: 3rem;
    color: white;
    display: flex;
    flex-direction: column;
    justify-content: center;
}

.brand-logo {
    display: flex;
    align-items: center;
    gap: 15px;
    margin-bottom: 2rem;
}

.brand-logo img {
    width: 50px;
    height: 50px;
    border-radius: 12px;
}

.brand-logo h1 {
    font-size: 1.8rem;
    font-weight: 700;
}

.brand-tagline {
    margin-bottom: 2rem;
    line-height: 1.6;
    opacity: 0.9;
}

.features-list {
    list-style: none;
    margin-bottom: 2rem;
}

.features-list li {
    margin-bottom: 1rem;
    display: flex;
    align-items: center;
    gap: 10px;
}

.brand-footer {
    margin-top: auto;
    font-size: 0.9rem;
    opacity: 0.8;
}

/* Right Side - Forgot Password Form */
.auth-form-section {
    flex: 0 0 400px;
    padding: 3rem;
    display: flex;
    flex-direction: column;
    justify-content: center;
    background: var(--white);
}

.form-header {
    text-align: center;
    margin-bottom: 2rem;
}

.form-header h2 {
    font-size: 1.5rem;
    color: var(--text-dark);
    margin-bottom: 0.5rem;
    font-weight: 600;
}

.form-header p {
    color: var(--text-light);
    font-size: 1rem;
}

/* Form Styles */
.forgot-form {
    width: 100%;
}

.form-group {
    position: relative;
    margin-bottom: 1.5rem;
}

.form-input {
    width: 100%;
    padding: 12px 15px;
    border: 2px solid #e9ecef;
    border-radius: 8px;
    font-size: 1rem;
    background: #f8f9fa;
    transition: all 0.3s ease;
}

.form-input:focus {
    outline: none;
    border-color: var(--primary-color);
    background: white;
    box-shadow: 0 0 0 3px rgba(160, 194, 66, 0.1);
}

/* Success Message */
.alert-success {
    background: #d4edda;
    color: #155724;
    padding: 12px 15px;
    border-radius: 8px;
    border: 1px solid #c3e6cb;
    margin-bottom: 1.5rem;
    font-size: 0.95rem;
}

/* Error Message */
.alert-danger {
    background: #f8d7da;
    color: #721c24;
    padding: 12px 15px;
    border-radius: 8px;
    border: 1px solid #f5c6cb;
    margin-bottom: 1.5rem;
    font-size: 0.95rem;
}

/* Submit Button */
.submit-btn {
    width: 100%;
    padding: 12px;
    background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
    color: white;
    border: none;
    border-radius: 8px;
    font-size: 1rem;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
    margin-bottom: 1.5rem;
}

.submit-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(160, 194, 66, 0.3);
}

/* Back to Login Link */
.back-login {
    text-align: center;
    margin-top: 1rem;
}

.back-login a {
    color: var(--primary-color);
    text-decoration: none;
    font-weight: 500;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
}

.back-login a:hover {
    text-decoration: underline;
}

/* Security Badge */
.security-badge {
    text-align: center;
    margin-top: 1.5rem;
    font-size: 0.9rem;
    color: var(--text-light);
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
}

.security-badge i {
    color: var(--primary-color);
}

/* Responsive */
@media (max-width: 768px) {
    .auth-container {
        flex-direction: column;
        min-height: auto;
    }
    
    .auth-brand {
        padding: 2rem;
        text-align: center;
    }
    
    .auth-form-section {
        flex: none;
        padding: 2rem;
    }
    
    .brand-logo {
        justify-content: center;
    }
}

@media (max-width: 480px) {
    .auth-container {
        margin: 0;
        border-radius: 0;
        min-height: 100vh;
    }
    
    .auth-brand,
    .auth-form-section {
        padding: 1.5rem;
    }
}
</style>

@if(config('smartend.mail_driver') != "" && config('smartend.mail_username') != "" && config('smartend.mail_password'))
<div class="auth-container">
    <!-- Left Brand Section -->
    <div class="auth-brand">
        <div class="brand-logo">
            @if(Helper::GeneralSiteSettings("style_logo_" . @Helper::currentLanguage()->code) !="")
                <img src="{{ URL::to('uploads/settings/'.Helper::GeneralSiteSettings("style_logo_" . @Helper::currentLanguage()->code)) }}" alt="Logo">
            @else
                <img src="{{ URL::to('uploads/settings/nologo.png') }}" alt="Logo">
            @endif
            <h1>KiwiTicketing</h1>
        </div>
        
        <p class="brand-tagline">
            Professional Ticketing Management System with Advanced Analytics and Real-time Monitoring
        </p>
        
        <ul class="features-list">
            <li>✓ Enterprise-grade Security</li>
            <li>✓ Advanced Analytics Dashboard</li>
            <li>✓ Real-time Order Processing</li>
            <li>✓ Multi-level User Management</li>
            <li>✓ Fully Responsive Design</li>
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

    <!-- Right Forgot Password Form -->
    <div class="auth-form-section">
        <div class="form-header">
            <h2>{{ __('backend.forgotPassword') }}</h2>
            <p>Enter your email to reset your password</p>
        </div>

        <div class="text-left" style="margin-bottom: 1.5rem;">
            <p class="text-muted">{{ __('backend.enterYourEmail') }}</p>
        </div>

        @if (session('status'))
            <div class="alert alert-success">
                {{ session('status') }}
            </div>
        @endif

        <form name="reset" method="POST" action="{{ url('/'.config('smartend.backend_path').'/password/email') }}" class="forgot-form">
            {{ csrf_field() }}
            
            <div class="form-group {{ $errors->has('email') ? 'has-error' : '' }}">
                <input type="email" name="email" value="{{ old('email') }}" class="form-input" placeholder="Enter your email address" required>
            </div>
            
            @if ($errors->has('email'))
                <div class="alert alert-danger">
                    {{ $errors->first('email') }}
                </div>
            @endif
            
            <button type="submit" class="submit-btn">
                {{ __('backend.sendPasswordResetLink') }}
            </button>
        </form>

        <div class="back-login">
            <a href="{{ url('/'.config('smartend.backend_path').'/login') }}">
                <i class="fas fa-arrow-left"></i>
                {{ __('backend.returnTo') }} {{ __('backend.signIn') }}
            </a>
        </div>

        <div class="security-badge">
            <i class="fas fa-lock"></i>
            <span>Secure SSL Encryption</span>
        </div>
    </div>
</div>
@endif

@endsection