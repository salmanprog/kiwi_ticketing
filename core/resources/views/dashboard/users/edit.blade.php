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

        /* FIXED: Icon margin styles */
        .form-label-icon {
            margin-right: 10px !important;
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
            outline: none;
        }

        .file-input-modern {
            position: relative;
            overflow: hidden;
            margin-bottom: 1rem;
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
            margin-right: 8px !important; /* FIXED: Added margin */
        }

        /* Photo Preview */
        .photo-preview {
            margin-bottom: 1rem;
        }

        .current-photo {
            display: flex;
            align-items: center;
            gap: 15px;
            padding: 1rem;
            background: var(--bg-light);
            border-radius: 8px;
            border: 1px solid var(--border-light);
        }

        .current-photo img {
            width: 80px;
            height: 80px;
            border-radius: 8px;
            object-fit: cover;
        }

        .photo-info {
            flex: 1;
        }

        .photo-actions {
            display: flex;
            gap: 10px;
        }

        .btn-photo-action {
            padding: 0.5rem 1rem;
            border: 1px solid var(--border-light);
            border-radius: 6px;
            background: white;
            color: var(--text-dark);
            font-size: 0.85rem;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .btn-photo-delete {
            background: rgba(220, 53, 69, 0.1);
            color: #dc3545;
            border-color: rgba(220, 53, 69, 0.2);
        }

        .btn-photo-delete:hover {
            background: #dc3545;
            color: white;
        }

        .btn-photo-undo {
            background: rgba(23, 162, 184, 0.1);
            color: #17a2b8;
            border-color: rgba(23, 162, 184, 0.2);
        }

        .btn-photo-undo:hover {
            background: #17a2b8;
            color: white;
        }

        /* Radio Buttons */
        .radio-group {
            display: flex;
            gap: 2rem;
            align-items: center;
        }

        .radio-modern {
            display: flex;
            align-items: center;
            gap: 8px;
            cursor: pointer;
        }

        .radio-input {
            display: none;
        }

        .radio-custom {
            width: 20px;
            height: 20px;
            border: 2px solid var(--border-light);
            border-radius: 50%;
            position: relative;
            transition: all 0.3s ease;
        }

        .radio-input:checked + .radio-custom {
            border-color: var(--primary-green);
            background: var(--primary-green);
        }

        .radio-input:checked + .radio-custom::after {
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

        .radio-label {
            font-weight: 500;
            color: var(--text-dark);
        }

        /* Select Box */
        .select-modern {
            appearance: none;
            background-image: url("data:image/svg+xml;charset=US-ASCII,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 4 5'><path fill='%236c757d' d='M2 0L0 2h4zm0 5L0 3h4z'/></svg>");
            background-repeat: no-repeat;
            background-position: right 1rem center;
            background-size: 12px;
            padding-right: 3rem;
        }

        /* Buttons */
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
            cursor: pointer;
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
            text-decoration: none;
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
            margin-right: 5px !important; /* FIXED: Added margin */
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
            
            .radio-group {
                flex-direction: column;
                align-items: flex-start;
                gap: 1rem;
            }
            
            .current-photo {
                flex-direction: column;
                text-align: center;
            }
            
            .photo-actions {
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
            <h3><i class="fas fa-user-edit"></i> {{ __('backend.editUser') }}</h3>
            <!-- <nav class="breadcrumb-modern">
                <a href="{{ route('adminHome') }}">{{ __('backend.home') }}</a> /
                <a href="">{{ __('backend.settings') }}</a> /
                <a href="">{{ __('backend.usersPermissions') }}</a>
            </nav> -->
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
            {{ Form::open(['route' => ['usersUpdate', $Users->id], 'method' => 'POST', 'files' => true, 'class' => 'modern-form']) }}

            <!-- Full Name Field -->
            <div class="form-group-modern">
                <label for="name" class="form-label-modern">
                    <i class="fas fa-user form-label-icon"></i>{!! __('backend.fullName') !!}
                </label>
                <div style="flex: 1;">
                    {!! Form::text('name', $Users->name, [
                        'placeholder' => __('Enter FullName'), 
                        'class' => 'form-control-modern', 
                        'id' => 'name', 
                        'required' => '',
                        'maxlength' => 50
                    ]) !!}
                </div>
            </div>

            <!-- Email Field -->
            <div class="form-group-modern">
                <label for="email" class="form-label-modern">
                    <i class="fas fa-envelope form-label-icon"></i>{!! __('backend.loginEmail') !!}
                </label>
                <div style="flex: 1;">
                    {!! Form::email('email', $Users->email, [
                        'placeholder' => __('Enter Email'), 
                        'class' => 'form-control-modern', 
                        'id' => 'email', 
                        'required' => '',
                        'maxlength' => 40
                    ]) !!}
                </div>
            </div>

            <!-- Password Field -->
            <!-- <div class="form-group-modern">
                <label for="password" class="form-label-modern">
                    <i class="fas fa-lock form-label-icon"></i>{!! __('backend.loginPassword') !!}
                </label>
                <div style="flex: 1;">
                    <input type="password" name="password" 
                           class="form-control-modern" 
                           minlength="6" 
                           placeholder="{{ __('backend.enterNewPassword') }}">
                    <div class="help-text">
                        <i class="fas fa-info-circle"></i>
                        {{ __('backend.passwordLeaveBlank') }}
                    </div>
                </div>
            </div> -->

            <!-- Photo Upload Field -->
            <div class="form-group-modern">
                <label class="form-label-modern">
                    <i class="fas fa-camera form-label-icon"></i>{!! __('backend.topicPhoto') !!}
                </label>
                <div style="flex: 1;">
                    <!-- Current Photo -->
                    @if($Users->photo != "")
                        <div class="photo-preview">
                            <div class="current-photo" id="user_photo">
                                <img id="photoPreview" src="{{ asset('uploads/users/'.$Users->photo) }}" 
                                     alt="{{ $Users->name }}" 
                                     class="img-responsive">
                                <div class="photo-info">
                                    <strong>{{ $Users->photo }}</strong>
                                    <div class="help-text">Current profile photo</div>
                                </div>
                                <div class="photo-actions">
                                    <a href="{{ asset('uploads/users/'.$Users->photo) }}" 
                                       target="_blank" 
                                       class="btn-photo-action">
                                        <i class="fas fa-eye"></i> View
                                    </a>
                                    <button type="button" 
                                            onclick="deletePhoto()" 
                                            class="btn-photo-action btn-photo-delete">
                                        <i class="fas fa-trash"></i> Delete
                                    </button>
                                </div>
                            </div>
                            <div id="undo" class="current-photo" style="display: none; background: rgba(23, 162, 184, 0.1);">
                                <i class="fas fa-info-circle text-info"></i>
                                <div class="photo-info">
                                    <strong>Photo scheduled for deletion</strong>
                                    <div class="help-text">Photo will be removed when you save changes</div>
                                </div>
                                <button type="button" 
                                        onclick="undoDelete()" 
                                        class="btn-photo-action btn-photo-undo">
                                    <i class="fas fa-undo"></i> Undo
                                </button>
                            </div>
                            {!! Form::hidden('photo_delete', '0', ['id' => 'photo_delete']) !!}
                        </div>
                    @endif

                    <!-- New Photo Upload -->
                    <div class="file-input-modern">
                    {!! Form::file('photo', [
                        'class' => 'form-control', 
                        'id' => 'photo', 
                        'accept' => 'image/*'
                    ]) !!}
                    <label for="photo" class="file-label-modern">
                        <i class="fas fa-cloud-upload-alt"></i>
                        <span id="file-name">
                            {{ $Users->photo ? 'Change Photo' : 'Upload Photo' }}
                        </span>
                    </label>
                    </div>
                    <div class="help-text">
                        <i class="fas fa-image"></i>
                        {!! __('backend.imagesTypes') !!}
                    </div>
                </div>
            </div>

            <!-- Permissions Field (Webmaster Only) -->
            @if(@Auth::user()->permissionsGroup->webmaster_status)
                <div class="form-group-modern" style="display: none">
                    <label for="permissions_id" class="form-label-modern">
                        <i class="fas fa-shield-alt form-label-icon"></i>{!! __('backend.Permission') !!}
                    </label>
                    <div style="flex: 1;">
                        <select name="permissions_id" id="permissions_id" 
                                class="form-control-modern select-modern">
                            <option value="">- - {!! __('backend.selectPermissionsType') !!} - -</option>
                            @foreach ($Permissions as $Permission)
                                <option value="{{ $Permission->id }}" 
                                    {{ $Users->permissions_id == $Permission->id ? 'selected' : '' }}>
                                    {{ $Permission->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <!-- Status Field -->
                 
                <div class="form-group-modern" style="display:none;">
                    <label class="form-label-modern">
                        <i class="fas fa-toggle-on form-label-icon"></i>{!! __('backend.status') !!}
                    </label>
                    <div style="flex: 1;">
                        <div class="radio-group">
                            <label class="radio-modern">
                                <input type="radio" name="status" value="1" 
                                       class="radio-input" 
                                       id="status1"
                                       {{ $Users->status == 1 ? 'checked' : '' }}>
                                <span class="radio-custom"></span>
                                <span class="radio-label">{{ __('backend.active') }}</span>
                            </label>
                            <label class="radio-modern">
                                <input type="radio" name="status" value="0" 
                                       class="radio-input" 
                                       id="status2"
                                       {{ $Users->status == 0 ? 'checked' : '' }}>
                                <span class="radio-custom"></span>
                                <span class="radio-label">{{ __('backend.notActive') }}</span>
                            </label>
                        </div>
                    </div>
                </div>
            @else
                {!! Form::hidden('permissions_id', $Users->permissions_id) !!}
                {!! Form::hidden('status', $Users->status) !!}
            @endif

            <!-- Form Actions -->
            
                <div class="form-actions">
                    <button type="submit" class="btn-modern-primary">
                        <i class="fas fa-save"></i> {!! __('backend.update') !!}
                    </button>
                    <!-- <a href="{{ route('users') }}" class="btn-modern-default">
                        <i class="fas fa-times"></i> {!! __('backend.cancel') !!}
                    </a> -->
                </div>
            {{ Form::close() }}
        </div>
    </div>
</div>
@endsection

@push('after-scripts')
<script>
    // Photo deletion functionality
    function deletePhoto() {
        document.getElementById('user_photo').style.display = 'none';
        document.getElementById('photo_delete').value = '1';
        document.getElementById('undo').style.display = 'flex';
    }

    function undoDelete() {
        document.getElementById('user_photo').style.display = 'flex';
        document.getElementById('photo_delete').value = '0';
        document.getElementById('undo').style.display = 'none';
    }

    // File input name display
    document.getElementById('photo').addEventListener('change', function(e) {
        const fileName = e.target.files[0] ? e.target.files[0].name : '{{ $Users->photo ? "Change Photo" : "Upload Photo" }}';
        document.getElementById('file-name').textContent = fileName;
    });

    // Form validation enhancement
    document.querySelector('.modern-form').addEventListener('submit', function(e) {
        const submitBtn = this.querySelector('button[type="submit"]');
        const originalText = submitBtn.innerHTML;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Updating...';
        submitBtn.disabled = true;
        
        // Re-enable after 3 seconds if form doesn't submit (fallback)
        setTimeout(() => {
            if (submitBtn.disabled) {
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalText;
            }
        }, 3000);
    });

    // Add focus effects
    document.querySelectorAll('.form-control-modern').forEach(input => {
        input.addEventListener('focus', function() {
            this.style.transform = 'translateY(-2px)';
        });
        
        input.addEventListener('blur', function() {
            this.style.transform = 'translateY(0)';
        });
    });

    // Radio button effects
    document.querySelectorAll('.radio-input').forEach(radio => {
        radio.addEventListener('change', function() {
            document.querySelectorAll('.radio-input').forEach(r => {
                r.parentElement.classList.remove('active');
            });
            if (this.checked) {
                this.parentElement.classList.add('active');
            }
        });
        
        // Initialize active state
        if (radio.checked) {
            radio.parentElement.classList.add('active');
        }
    });

    document.addEventListener('DOMContentLoaded', function () {
    var photoInput = document.getElementById('photo');
    if (!photoInput) return;

    photoInput.addEventListener('change', function () {
        var file = photoInput.files[0];
        if (!file) return;

        if (!file.type.startsWith('image/')) {
            alert('Please select a valid image file.');
            return;
        }

        var reader = new FileReader();
        reader.onload = function (e) {
            var img = document.getElementById('photoPreview');

            if (!img) {
                img = document.createElement('img');
                img.id = 'photoPreview';
                img.className = 'img-responsive';

                var container = document.getElementById('user_photo');
                if (!container) {
                    container = document.createElement('div');
                    container.id = 'user_photo';
                    container.className = 'current-photo';
                    photoInput.closest('.form-group-modern').prepend(container);
                }

                container.appendChild(img);
            }

            img.src = e.target.result;
        };

        reader.readAsDataURL(file);
    });
});
</script>
@endpush