@extends('dashboard.layouts.master')
@section('title', __('backend.usersPermissions'))
@push('after-styles')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        /* Modern Green Theme */
        :root {
            --primary-green: #A0C242;
            --primary-dark: #8AAE38;
            --text-dark: #2c3e50;
            --text-light: #6c757d;
            --border-light: #e9ecef;
            --bg-light: #f8f9fa;
        }

        .modern-header {
            background: linear-gradient(135deg, var(--primary-green) 0%, var(--primary-dark) 100%);
            color: white;
            padding: 1.5rem 2rem;
            border-radius: 12px 12px 0 0;
            margin-bottom: 0;
        }

        .modern-header h3 {
            color: white;
            font-weight: 700;
            margin-bottom: 0.5rem;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .breadcrumb-modern {
            background: transparent;
            padding: 0;
            margin: 0;
            font-size: 0.9rem;
            opacity: 0.9;
        }

        .breadcrumb-modern a {
            color: rgba(255,255,255,0.9);
            text-decoration: none;
        }

        .breadcrumb-modern a:hover {
            color: white;
            text-decoration: underline;
        }

        .modern-box {
            background: white;
            border-radius: 12px;
            box-shadow: 0 2px 20px rgba(0,0,0,0.08);
            border: 1px solid var(--border-light);
            margin-bottom: 2rem;
        }
        

        .box-tool-modern {
            display: none;
            padding: 1rem 2rem;
            background: var(--bg-light);
            border-bottom: 1px solid var(--border-light);
        }

        .close-btn {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            background: white;
            color: var(--text-light);
            text-decoration: none;
            transition: all 0.3s ease;
            border: 1px solid var(--border-light);
        }
        

        .close-btn:hover {
            background: var(--primary-green);
            color: white;
            transform: rotate(90deg);
        }

        .form-container {
            padding: 2rem;
        }

        .form-group-modern {
            margin-bottom: 1.8rem;
            display: flex;
            align-items: flex-start;
        }

        .form-label-modern {
            font-weight: 600;
            color: var(--text-dark);
            margin-bottom: 0.5rem;
            flex: 0 0 200px;
            padding-top: 0.5rem;
            display: flex;
            align-items: center;
        }

        .form-label-icon {
            margin-right: 10px; /* Fixed margin for icons */
            width: 16px;
            text-align: center;
            color: var(--primary-green);
        }

        .form-control-modern {
            border: 2px solid var(--border-light);
            border-radius: 8px;
            padding: 0.75rem 1rem;
            font-size: 1rem;
            transition: all 0.3s ease;
            background: white;
            width: 100%;
        }

        .form-control-modern:focus {
            border-color: var(--primary-green);
            box-shadow: 0 0 0 3px rgba(160, 194, 66, 0.1);
            background: white;
        }

        .form-control-modern:focus-within {
            border-color: var(--primary-green);
        }

        .file-input-modern {
            position: relative;
            overflow: hidden;
        }

        .file-input-modern input[type="file"] {
            position: absolute;
            left: 0;
            top: 0;
            opacity: 0;
            width: 100%;
            height: 100%;
            cursor: pointer;
        }

        .file-label-modern {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 0.75rem 1rem;
            background: var(--bg-light);
            border: 2px dashed var(--border-light);
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .file-label-modern:hover {
            border-color: var(--primary-green);
            background: rgba(160, 194, 66, 0.05);
        }

        .file-label-modern i {
            color: var(--primary-green);
            margin-right: 8px; /* Fixed margin for file upload icon */
        }

        .select-modern {
            appearance: none;
            background-image: url("data:image/svg+xml;charset=US-ASCII,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 4 5'><path fill='%236c757d' d='M2 0L0 2h4zm0 5L0 3h4z'/></svg>");
            background-repeat: no-repeat;
            background-position: right 1rem center;
            background-size: 12px;
            padding-right: 3rem;
        }

        .btn-modern-primary {
            background: linear-gradient(135deg, var(--primary-green) 0%, var(--primary-dark) 100%);
            border: none;
            border-radius: 8px;
            padding: 0.75rem 2rem;
            color: white;
            font-weight: 600;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }
        .height{
            height: 3.375rem !important;
        }

        .btn-modern-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(160, 194, 66, 0.3);
            color: white;
        }

        .btn-modern-default {
            background: white;
            border: 2px solid var(--border-light);
            border-radius: 8px;
            padding: 0.75rem 2rem;
            color: var(--text-dark);
            font-weight: 500;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .btn-modern-default:hover {
            border-color: var(--primary-green);
            color: var(--primary-green);
            transform: translateY(-1px);
        }

        .form-actions {
            padding-top: 1.5rem;
            border-top: 1px solid var(--border-light);
            margin-top: 2rem;
            display: flex;
            gap: 1rem;
            align-items: center;
        }

        .help-text {
            font-size: 0.85rem;
            color: var(--text-light);
            margin-top: 0.5rem;
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .help-text i {
            margin-right: 5px; /* Fixed margin for help text icons */
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .form-group-modern {
                flex-direction: column;
                align-items: stretch;
            }
            
            .form-label-modern {
                flex: none;
                margin-bottom: 0.75rem;
            }
            
            .form-actions {
                flex-direction: column;
                align-items: stretch;
            }
            
            .btn-modern-primary,
            .btn-modern-default {
                width: 100%;
                justify-content: center;
            }
        }

        /* Animation */
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .modern-box {
            animation: fadeIn 0.5s ease-out;
        }
    </style>
@endpush

@section('content')
<div class="padding">
    <div class="modern-box">
        <!-- Header Section -->
        <div class="modern-header">
            <h3><i class="fas fa-user-plus"></i> {{ __('backend.newUser') }}</h3>
            <nav class="breadcrumb-modern">
                <a href="{{ route('adminHome') }}">{{ __('backend.home') }}</a> /
                <a href="{{ route('adminHome') }}">{{ __('backend.settings') }}</a> /
                <a href="">{{ __('backend.usersPermissions') }}</a>
            </nav>
        </div>

        <!-- Toolbar -->
        <div class="box-tool-modern">
            <div class="nav">
                <a class="close-btn" href="{{ route('users') }}">
                    <i class="fas fa-times"></i>
                </a>
            </div>
        </div>

        <!-- Form Section -->
        <div class="form-container">
            {{ Form::open(['route' => ['usersStore'], 'method' => 'POST', 'files' => true, 'class' => 'modern-form']) }}

            <!-- Full Name Field -->
            <div class="form-group-modern">
                <label for="name" class="form-label-modern">
                    <i class="fas fa-user form-label-icon"></i>{!! __('backend.fullName') !!}
                </label>
                <div style="flex: 1;">
                    {!! Form::text('name', '', [
                        'placeholder' => __('backend.enterFullName'), 
                        'class' => 'form-control form-control-modern', 
                        'id' => 'name', 
                        'required' => ''
                    ]) !!}
                </div>
            </div>

            <!-- Email Field -->
            <div class="form-group-modern">
                <label for="email" class="form-label-modern">
                    <i class="fas fa-envelope form-label-icon"></i>{!! __('backend.loginEmail') !!}
                </label>
                <div style="flex: 1;">
                    {!! Form::email('email', '', [
                        'placeholder' => __('backend.enterEmail'), 
                        'class' => 'form-control form-control-modern', 
                        'id' => 'email', 
                        'required' => ''
                    ]) !!}
                </div>
            </div>

            <!-- Password Field -->
            <div class="form-group-modern">
                <label for="password" class="form-label-modern">
                    <i class="fas fa-lock form-label-icon"></i>{!! __('backend.loginPassword') !!}
                </label>
                <div style="flex: 1;">
                    <input type="password" name="password" 
                           class="form-control form-control-modern" 
                           minlength="6" 
                           placeholder="{{ __('backend.enterPassword') }}" 
                           required>
                    <div class="help-text">
                        <i class="fas fa-info-circle"></i>
                        {{ __('backend.passwordMinLength') }}
                    </div>
                </div>
            </div>

            <!-- Photo Upload Field -->
            <div class="form-group-modern">
                <label for="photo" class="form-label-modern">
                    <i class="fas fa-camera form-label-icon"></i>{!! __('backend.personalPhoto') !!}
                </label>
                <div style="flex: 1;">
                    <div class="file-input-modern">
                        {!! Form::file('photo', [
                            'class' => 'form-control', 
                            'id' => 'photo', 
                            'accept' => 'image/*'
                        ]) !!}
                        <label for="photo" class="file-label-modern">
                            <i class="fas fa-cloud-upload-alt"></i>
                            <span id="file-name">{{ __('backend.choosePhoto') }}</span>
                        </label>
                    </div>
                    <div class="help-text">
                        <i class="fas fa-image"></i>
                        {!! __('backend.imagesTypes') !!}
                    </div>
                </div>
            </div>

            <!-- Permissions Field -->
            <div class="form-group-modern">
                <label for="permissions_id" class="form-label-modern">
                    <i class="fas fa-shield-alt form-label-icon"></i>{!! __('backend.Permission') !!}
                </label>
                <div style="flex: 1;">
                    <select name="permissions_id" id="permissions_id" required 
                            class="form-control height form-control-modern select-modern">
                        <option value="">- - {!! __('backend.selectPermissionsType') !!} - -</option>
                        @foreach ($Permissions as $Permission)
                            <option value="{{ $Permission->id }}">{{ $Permission->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="form-actions">
                <button type="submit" class="btn-modern-primary">
                    <i class="fas fa-plus-circle"></i> {!! __('backend.add') !!}
                </button>
                <a href="{{ route('users') }}" class="btn-modern-default">
                    <i class="fas fa-times"></i> {!! __('backend.cancel') !!}
                </a>
            </div>

            {{ Form::close() }}
        </div>
    </div>
</div>
@endsection

@push('after-scripts')
<script>
    // File input name display
    document.getElementById('photo').addEventListener('change', function(e) {
        const fileName = e.target.files[0] ? e.target.files[0].name : '{{ __('backend.choosePhoto') }}';
        document.getElementById('file-name').textContent = fileName;
    });

    // Form validation enhancement
    document.querySelector('.modern-form').addEventListener('submit', function(e) {
        const submitBtn = this.querySelector('button[type="submit"]');
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> {{ __('backend.adding') }}...';
        submitBtn.disabled = true;
    });

    // Add focus effects
    document.querySelectorAll('.form-control-modern').forEach(input => {
        input.addEventListener('focus', function() {
            this.parentElement.style.transform = 'translateY(-2px)';
        });
        
        input.addEventListener('blur', function() {
            this.parentElement.style.transform = 'translateY(0)';
        });
    });
</script>
@endpush