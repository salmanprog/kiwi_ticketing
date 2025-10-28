@extends('dashboard.layouts.master')
@section('title', __('backend.newPermissions'))
@push('after-styles')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        /* Modern Green Theme */
        :root {
            --primary-green: #A0C242;
            --primary-dark: #8AAE38;
            --primary-light: #B8D46A;
            --text-dark: #2c3e50;
            --text-light: #6c757d;
            --border-light: #e9ecef;
            --bg-light: #f8f9fa;
            --success-light: rgba(40, 167, 69, 0.1);
            --info-light: rgba(23, 162, 184, 0.1);
        }

        .modern-header {
            background: linear-gradient(135deg, var(--primary-green) 0%, var(--primary-dark) 100%);
            color: white;
            padding: 1.5rem 2rem;
            border-radius: 12px 12px 0 0;
            margin-bottom: 0;
            position: relative;
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
            overflow: hidden;
        }

        .form-container {
            padding: 2rem;
        }

        .form-group-modern {
            margin-bottom: 2rem;
            display: flex;
            align-items: flex-start;
        }

        .form-label-modern {
            font-weight: 600;
            color: var(--text-dark);
            margin-bottom: 0.5rem;
            flex: 0 0 250px;
            padding-top: 0.5rem;
            display: flex;
            align-items: center;
            gap: 8px;
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

        /* Checkbox Modern Styles */
        .checkbox-modern-group {
            background: var(--bg-light);
            border: 1px solid var(--border-light);
            border-radius: 8px;
            padding: 1.5rem;
        }

        .checkbox-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 1rem;
        }

        .checkbox-item-modern {
            display: flex;
            align-items: center;
            padding: 0.75rem;
            background: white;
            border: 1px solid var(--border-light);
            border-radius: 6px;
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .checkbox-item-modern:hover {
            border-color: var(--primary-green);
            transform: translateY(-2px);
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }

        .checkbox-item-modern input[type="checkbox"] {
            display: none;
        }

        .checkmark {
            width: 20px;
            height: 20px;
            border: 2px solid var(--border-light);
            border-radius: 4px;
            margin-right: 12px;
            position: relative;
            transition: all 0.3s ease;
            flex-shrink: 0;
        }

        .checkbox-item-modern input[type="checkbox"]:checked + .checkmark {
            background: var(--primary-green);
            border-color: var(--primary-green);
        }

        .checkbox-item-modern input[type="checkbox"]:checked + .checkmark::after {
            content: '✓';
            position: absolute;
            color: white;
            font-size: 12px;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
        }

        /* Radio Group Styles */
        .radio-group-modern {
            display: flex;
            gap: 2rem;
            flex-wrap: wrap;
        }

        .radio-item-modern {
            display: flex;
            align-items: center;
            padding: 0.75rem 1.5rem;
            background: white;
            border: 2px solid var(--border-light);
            border-radius: 8px;
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .radio-item-modern:hover {
            border-color: var(--primary-green);
        }

        .radio-item-modern input[type="radio"] {
            display: none;
        }

        .radiomark {
            width: 18px;
            height: 18px;
            border: 2px solid var(--border-light);
            border-radius: 50%;
            margin-right: 10px;
            position: relative;
            transition: all 0.3s ease;
            flex-shrink: 0;
        }

        .radio-item-modern input[type="radio"]:checked + .radiomark {
            border-color: var(--primary-green);
        }

        .radio-item-modern input[type="radio"]:checked + .radiomark::after {
            content: '';
            position: absolute;
            width: 8px;
            height: 8px;
            background: var(--primary-green);
            border-radius: 50%;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
        }

        .radio-item-modern.checked {
            border-color: var(--primary-green);
        }

        .radio-item-modern.checked .radio-label {
            color: var(--primary-green);
            font-weight: 600;
        }

        /* Button Styles */
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

        /* Section Styles */
        .section-modern {
            margin-bottom: 2.5rem;
            padding-bottom: 1.5rem;
            border-bottom: 1px solid var(--border-light);
        }

        .section-title {
            font-size: 1.25rem;
            font-weight: 600;
            color: var(--text-dark);
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .section-title i {
            color: var(--primary-green);
        }

        .help-text {
            font-size: 0.85rem;
            color: var(--text-light);
            margin-top: 0.5rem;
            display: flex;
            align-items: center;
            gap: 5px;
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
            
            .checkbox-grid {
                grid-template-columns: 1fr;
            }
            
            .radio-group-modern {
                flex-direction: column;
                gap: 0.5rem;
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
            <h3><i class="fas fa-shield-alt"></i> {{ __('backend.newPermissions') }}</h3>
            <nav class="breadcrumb-modern">
                <a href="{{ route('adminHome') }}">{{ __('backend.home') }}</a> /
                <a href="">{{ __('backend.settings') }}</a> /
                <a href="">{{ __('backend.usersPermissions') }}</a>
            </nav>
        </div>

        <!-- Form Section -->
        <div class="form-container">
            {{ Form::open(['route'=>['permissionsStore'],'method'=>'POST', 'class' => 'modern-form']) }}

            <!-- Basic Information Section -->
            <div class="section-modern">
                <div class="section-title">
                    <i class="fas fa-info-circle"></i>
                        Basic Information
                </div>
                
                <div class="form-group-modern">
                    <label for="name" class="form-label-modern">
                        <i class="fas fa-tag"></i>
                        {!!  __('backend.title') !!}
                    </label>
                    <div style="flex: 1;">
                        {!! Form::text('name','', array('placeholder' => __('Permission Name'),'class' => 'form-control-modern','id'=>'name','required'=>'')) !!}
                        <div class="help-text">
                            <i class="fas fa-info-circle"></i>
                            Enter Unique Permission Name
                        </div>
                    </div>
                </div>
            </div>

            <!-- Site Sections Permissions -->
            <div class="section-modern">
                <div class="section-title">
                    <i class="fas fa-sitemap"></i>
                    {!!  __('backend.activeSiteSections') !!}
                </div>
                
                <div class="form-group-modern">
                    <label class="form-label-modern">
                        <i class="fas fa-check-square"></i>
                         Select Sections
                    </label>
                    <div style="flex: 1;">
                        <div class="checkbox-modern-group">
                            <div class="checkbox-grid">
                                <?php
                                $i = 0;
                                $title_var = "title_" . @Helper::currentLanguage()->code;
                                $title_var2 = "title_" . config('smartend.default_language');
                                ?>
                                @foreach($GeneralWebmasterSections as $WebSection)
                                    <?php
                                    if ($WebSection->$title_var != "") {
                                        $WSectionTitle = $WebSection->$title_var;
                                    } else {
                                        $WSectionTitle = $WebSection->$title_var2;
                                    }
                                    ?>
                                    <label class="checkbox-item-modern">
                                        {!! Form::checkbox('data_sections[]',$WebSection->id,false, array('id' => 'data_sections'.$i)) !!}
                                        <span class="checkmark"></span>
                                        <span class="checkbox-label">{!! $WSectionTitle !!}</span>
                                    </label>
                                    <?php $i++; ?>
                                @endforeach
                            </div>
                        </div>
                        <div class="help-text">
                            <i class="fas fa-lightbulb"></i>
                            Select Sections Help
                        </div>
                    </div>
                </div>
            </div>

            <!-- Hidden Sections (Preserved but hidden) -->
            <div style="display: none;">
                <!-- Data Management Radio -->
                <div class="form-group-modern">
                    <label class="form-label-modern">
                        <i class="fas fa-database"></i>
                        {!!  __('backend.dataManagements') !!}
                    </label>
                    <div style="flex: 1;">
                        <div class="radio-group-modern">
                            <label class="radio-item-modern checked">
                                {!! Form::radio('view_status','1',true, array('id' => 'view_status1')) !!}
                                <span class="radiomark"></span>
                                <span class="radio-label">{{ __('backend.dataManagements1') }}</span>
                            </label>
                            <label class="radio-item-modern">
                                {!! Form::radio('view_status','0',false, array('id' => 'view_status2')) !!}
                                <span class="radiomark"></span>
                                <span class="radio-label">{{ __('backend.dataManagements2') }}</span>
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Application Permissions -->
                <div class="section-modern">
                    <div class="section-title">
                        <i class="fas fa-th-large"></i>
                        {!!  __('backend.activeApps') !!}
                    </div>
                    
                    <div class="form-group-modern">
                        <label class="form-label-modern">
                            <i class="fas fa-plug"></i>
                            {{ __('backend.applicationAccess') }}
                        </label>
                        <div style="flex: 1;">
                            <div class="checkbox-modern-group">
                                <div class="checkbox-grid">
                                    <!-- All your existing application checkboxes here -->
                                    <label class="checkbox-item-modern">
                                        {!! Form::checkbox('analytics_status','1',false, array('id' => 'analytics_status')) !!}
                                        <span class="checkmark"></span>
                                        <span class="checkbox-label">{{ __('backend.visitorsAnalytics') }}</span>
                                    </label>

                                    <label class="checkbox-item-modern">
                                        {!! Form::checkbox('newsletter_status','1',false, array('id' => 'newsletter_status')) !!}
                                        <span class="checkmark"></span>
                                        <span class="checkbox-label">{{ __('backend.newsletter') }}</span>
                                    </label>

                                    <!-- Add all other checkboxes similarly -->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Other hidden form groups -->
                <!-- Topics Status -->
                <div class="form-group-modern">
                    <label class="form-label-modern">
                        <i class="fas fa-toggle-on"></i>
                        {!!  __('backend.topicsStatus') !!}
                    </label>
                    <div style="flex: 1;">
                        <div class="radio-group-modern">
                            <label class="radio-item-modern checked">
                                {!! Form::radio('active_status','1',true, array('id' => 'active_status1')) !!}
                                <span class="radiomark"></span>
                                <span class="radio-label">{{ __('backend.active') }}</span>
                            </label>
                            <label class="radio-item-modern">
                                {!! Form::radio('active_status','0',false, array('id' => 'active_status2')) !!}
                                <span class="radiomark"></span>
                                <span class="radio-label">{{ __('backend.notActive') }}</span>
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Add Permission -->
                <div class="form-group-modern">
                    <label class="form-label-modern">
                        <i class="fas fa-plus-circle"></i>
                        {!!  __('backend.addPermission') !!}
                    </label>
                    <div style="flex: 1;">
                        <div class="radio-group-modern">
                            <label class="radio-item-modern checked">
                                {!! Form::radio('add_status','1',true, array('id' => 'add_status1')) !!}
                                <span class="radiomark"></span>
                                <span class="radio-label">{{ __('backend.yes') }}</span>
                            </label>
                            <label class="radio-item-modern">
                                {!! Form::radio('add_status','0',false, array('id' => 'add_status2')) !!}
                                <span class="radiomark"></span>
                                <span class="radio-label">{{ __('backend.no') }}</span>
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Edit Permission -->
                <div class="form-group-modern">
                    <label class="form-label-modern">
                        <i class="fas fa-edit"></i>
                        {!!  __('backend.editPermission') !!}
                    </label>
                    <div style="flex: 1;">
                        <div class="radio-group-modern">
                            <label class="radio-item-modern checked">
                                {!! Form::radio('edit_status','1',true, array('id' => 'edit_status1')) !!}
                                <span class="radiomark"></span>
                                <span class="radio-label">{{ __('backend.yes') }}</span>
                            </label>
                            <label class="radio-item-modern">
                                {!! Form::radio('edit_status','0',false, array('id' => 'edit_status2')) !!}
                                <span class="radiomark"></span>
                                <span class="radio-label">{{ __('backend.no') }}</span>
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Delete Permission -->
                <div class="form-group-modern">
                    <label class="form-label-modern">
                        <i class="fas fa-trash-alt"></i>
                        {!!  __('backend.deletePermission') !!}
                    </label>
                    <div style="flex: 1;">
                        <div class="radio-group-modern">
                            <label class="radio-item-modern checked">
                                {!! Form::radio('delete_status','1',true, array('id' => 'delete_status1')) !!}
                                <span class="radiomark"></span>
                                <span class="radio-label">{{ __('backend.yes') }}</span>
                            </label>
                            <label class="radio-item-modern">
                                {!! Form::radio('delete_status','0',false, array('id' => 'delete_status2')) !!}
                                <span class="radiomark"></span>
                                <span class="radio-label">{{ __('backend.no') }}</span>
                            </label>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="form-actions">
                <button type="submit" class="btn-modern-primary">
                    <i class="fas fa-plus-circle"></i>
                    {!! __('backend.add') !!}
                </button>
                <a href="{{ route('users') }}" class="btn-modern-default">
                    <i class="fas fa-times"></i>
                    {!! __('backend.cancel') !!}
                </a>
            </div>

            {{ Form::close() }}
        </div>
    </div>
</div>
@endsection

@push('after-scripts')
<script>
    // Checkbox functionality
    document.querySelectorAll('.checkbox-item-modern').forEach(item => {
        item.addEventListener('click', function() {
            const checkbox = this.querySelector('input[type="checkbox"]');
            checkbox.checked = !checkbox.checked;
            
            // Add visual feedback
            this.style.transform = 'translateY(-2px)';
            setTimeout(() => {
                this.style.transform = 'translateY(0)';
            }, 150);
        });
    });

    // Radio button functionality
    document.querySelectorAll('.radio-item-modern').forEach(item => {
        item.addEventListener('click', function() {
            const radio = this.querySelector('input[type="radio"]');
            if (!radio.checked) {
                radio.checked = true;
                
                // Remove checked state from siblings
                const name = radio.getAttribute('name');
                document.querySelectorAll(`input[name="${name}"]`).forEach(r => {
                    r.closest('.radio-item-modern').classList.remove('checked');
                });
                
                // Add checked state to current
                this.classList.add('checked');
                
                // Add visual feedback
                this.style.transform = 'translateY(-2px)';
                setTimeout(() => {
                    this.style.transform = 'translateY(0)';
                }, 150);
            }
        });
    });

    // Form submission enhancement
    document.querySelector('.modern-form').addEventListener('submit', function(e) {
        const submitBtn = this.querySelector('button[type="submit"]');
        const originalText = submitBtn.innerHTML;
        
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Creating...';
        submitBtn.disabled = true;
        
        // Re-enable after 3 seconds if form doesn't submit (fallback)
        setTimeout(() => {
            if (submitBtn.disabled) {
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalText;
            }
        }, 3000);
    });

    // Initialize radio button states
    document.querySelectorAll('.radio-item-modern input[type="radio"]').forEach(radio => {
        if (radio.checked) {
            radio.closest('.radio-item-modern').classList.add('checked');
        }
    });
</script>
@endpush