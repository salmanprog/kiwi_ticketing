@extends('dashboard.layouts.master')
@section('title',  __('backend.generalSettings'))
@push("after-styles")
    <link rel="stylesheet"
          href="{{ asset('assets/dashboard/js/bootstrap-colorpicker/css/bootstrap-colorpicker.min.css') }}"
          type="text/css"/>
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

        .settings-container {
            background: white;
            border-radius: 12px;
            box-shadow: 0 2px 20px rgba(0,0,0,0.08);
            border: 1px solid var(--border-light);
            overflow: hidden;
        }

        .settings-sidebar {
            background: var(--bg-light);
            border-right: 1px solid var(--border-light);
            padding: 0;
        }

        .sidebar-header {
            background: linear-gradient(135deg, var(--primary-green) 0%, var(--primary-dark) 100%);
            color: white;
            padding: 1.5rem;
            text-align: center;
        }

        .sidebar-header h4 {
            color: white;
            font-weight: 700;
            margin: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }

        .nav-modern {
            padding: 1rem 0;
        }

        .nav-modern .nav-item {
            margin: 0;
            border-bottom: 1px solid var(--border-light);
        }

        .nav-modern .nav-item:last-child {
            border-bottom: none;
        }

        .nav-modern .nav-link {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 1rem 1.5rem;
            color: var(--text-dark);
            text-decoration: none;
            transition: all 0.3s ease;
            border-left: 3px solid transparent;
            font-weight: 500;
        }

        .nav-modern .nav-link:hover {
            background: rgba(160, 194, 66, 0.05);
            color: var(--primary-green);
            border-left-color: var(--primary-green);
        }

        .nav-modern .nav-link.active {
            background: rgba(160, 194, 66, 0.1);
            color: var(--primary-green);
            border-left-color: var(--primary-green);
            font-weight: 600;
        }

        .nav-modern .nav-link i {
            width: 20px;
            text-align: center;
            font-size: 1.1rem;
        }

        .settings-content {
            background: white;
            padding: 0;
            min-height: 600px;
        }

        .content-header {
            background: var(--bg-light);
            padding: 1.5rem 2rem;
            border-bottom: 1px solid var(--border-light);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .content-header h3 {
            color: var(--text-dark);
            font-weight: 700;
            margin: 0;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .btn-modern-primary {
            background: linear-gradient(135deg, var(--primary-green) 0%, var(--primary-dark) 100%);
            border: none;
            border-radius: 8px;
            padding: 0.75rem 1.5rem;
            color: white;
            font-weight: 600;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .btn-modern-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(160, 194, 66, 0.3);
            color: white;
        }

        .tab-content-modern {
            padding: 2rem;
        }

        /* Form Styles */
        .form-group-modern {
            margin-bottom: 1.5rem;
        }

        .form-label-modern {
            font-weight: 600;
            color: var(--text-dark);
            margin-bottom: 0.5rem;
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
        }

        .form-control-modern:focus {
            border-color: var(--primary-green);
            box-shadow: 0 0 0 3px rgba(160, 194, 66, 0.1);
            background: white;
        }

        .help-text {
            font-size: 0.85rem;
            color: var(--text-light);
            margin-top: 0.5rem;
            display: flex;
            align-items: center;
            gap: 5px;
        }

        /* File Upload */
        .file-upload-modern {
            position: relative;
            overflow: hidden;
            margin-bottom: 1rem;
        }

        .file-upload-modern input[type="file"] {
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
        }

        /* Image Preview */
        .image-preview {
            margin-top: 1rem;
            text-align: center;
        }

        .preview-img {
            max-width: 200px;
            max-height: 100px;
            border-radius: 8px;
            border: 2px solid var(--border-light);
            padding: 5px;
            background: white;
        }

        /* Color Picker */
        .color-picker-modern {
            border-radius: 8px;
            overflow: hidden;
            border: 2px solid var(--border-light);
        }

        /* Responsive */
        @media (max-width: 768px) {
            .settings-container {
                flex-direction: column;
            }
            
            .settings-sidebar {
                border-right: none;
                border-bottom: 1px solid var(--border-light);
            }
            
            .nav-modern {
                display: flex;
                overflow-x: auto;
                padding: 0;
            }
            
            .nav-modern .nav-item {
                border-bottom: none;
                border-right: 1px solid var(--border-light);
                flex-shrink: 0;
            }
            
            .nav-modern .nav-item:last-child {
                border-right: none;
            }
            
            .nav-modern .nav-link {
                border-left: none;
                border-bottom: 3px solid transparent;
                white-space: nowrap;
            }
            
            .nav-modern .nav-link.active {
                border-left: none;
                border-bottom-color: var(--primary-green);
            }
            
            .content-header {
                flex-direction: column;
                gap: 1rem;
                align-items: flex-start;
            }
        }

        /* Animation */
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .settings-container {
            animation: fadeIn 0.5s ease-out;
        }

        .tab-pane {
            animation: fadeIn 0.3s ease-out;
        }
    </style>
@endpush

@section('content')
    <div class="padding">
        <div class="settings-container">
            <div class="no-gutters">
                <!-- Sidebar -->
                <div class="col-sm-3 col-lg-2 settings-sidebar">
                    <div class="sidebar-header">
                        <h4><i class="fas fa-cogs"></i> Settings</h4>
                    </div>
                    <div class="nav-modern">
                        <ul class="nav nav-sm flex-column">
                            <li class="nav-item">
                                <a class="nav-link {{ (Session::get('active_tab') == 'styleTab' || Session::get('active_tab') == '') ? 'active' : '' }}"
                                   href="#"
                                   data-toggle="tab" data-target="#tab-5"
                                   onclick="document.getElementById('active_tab').value='styleTab'">
                                    <i class="fas fa-palette"></i>
                                    {!! __('Logo') !!}
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ (Session::get('active_tab') == 'infoTab') ? 'active' : '' }}"
                                   href="#"
                                   data-toggle="tab" data-target="#tab-1"
                                   onclick="document.getElementById('active_tab').value='infoTab'">
                                    <i class="fas fa-info-circle"></i>
                                    {!! __('Account Info') !!}
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ (Session::get('active_tab') == 'contactsTab') ? 'active' : '' }}"
                                   href="#"
                                   data-toggle="tab" data-target="#tab-2"
                                   onclick="document.getElementById('active_tab').value='contactsTab'">
                                    <i class="fas fa-address-book"></i>
                                    {!! __('backend.siteContactsSettings') !!}
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ (Session::get('active_tab') == 'socialTab') ? 'active' : '' }}"
                                   href="#"
                                   data-toggle="tab" data-target="#tab-3"
                                   onclick="document.getElementById('active_tab').value='socialTab'">
                                    <i class="fas fa-share-alt"></i>
                                    {!! __('backend.siteSocialSettings') !!}
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ (Session::get('active_tab') == 'codeTab') ? 'active' : '' }}"
                                   href="#"
                                   data-toggle="tab" data-target="#tab-7"
                                   onclick="document.getElementById('active_tab').value='codeTab'">
                                    <i class="fas fa-credit-card"></i>
                                    {!! __('Payment Setting') !!}
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>

                <!-- Content -->
                <div class="col-sm-9 col-lg-10 settings-content">
                    {{ Form::open(['route' => ['settingsUpdateSiteInfo'], 'method' => 'POST', 'files' => true]) }}
                    <input type="hidden" id="active_tab" name="active_tab" value="{{ Session::get('active_tab') }}"/>
                    
                    <div class="content-header">
                        <h3><i class="fas fa-sliders-h"></i> General Settings</h3>
                        <button type="submit" class="btn-modern-primary">
                            <i class="fas fa-save"></i> {{ __('backend.update') }}
                        </button>
                    </div>

                    <div class="tab-content tab-content-modern">
                        @include('dashboard.settings.style')
                        @include('dashboard.settings.general')
                        @include('dashboard.settings.contacts')
                        @include('dashboard.settings.social')
                        @include('dashboard.settings.stripe')
                        @include('dashboard.settings.notifications')
                        @include('dashboard.settings.site_status')
                    </div>
                    {{ Form::close() }}
                </div>
            </div>
        </div>
    </div>
@endsection

@push("after-scripts")
    <script type="text/javascript">
        $(document).ready(function () {
            $("#site_status1").click(function () {
                $("#close_msg_div").css("display", "none");
            });
            $("#site_status2").click(function () {
                $("#close_msg_div").css("display", "block");
            });
        });
    </script>
    
    <script src="{{ asset('assets/dashboard/js/bootstrap-colorpicker/js/bootstrap-colorpicker.min.js') }}"></script>
    <script>
        $(function () {
            let colors = {
                'black': '#000000',
                'white': '#ffffff',
                'red': '#FF0000',
                'default': '#777777',
                'primary': '#337ab7',
                'success': '#5cb85c',
                'info': '#5bc0de',
                'warning': '#f0ad4e',
                'danger': '#d9534f'
            };

            $('#cp1').colorpicker({
                colorSelectors: colors
            });
            $('#cp2').colorpicker({
                colorSelectors: colors
            });
            $('#cp3').colorpicker({
                colorSelectors: colors
            });
            $('#cp4').colorpicker({
                colorSelectors: colors
            });
        });

        function SetThemeColors(clr1, clr2, clr3, clr4) {
            document.getElementById("style_color1").value = clr1;
            $("#cpbg i").css("background-color", clr1);
            document.getElementById("style_color2").value = clr2;
            $("#cpbg2 i").css("background-color", clr2);
            document.getElementById("style_color3").value = clr3;
            $("#cpbg3 i").css("background-color", clr3);
            document.getElementById("style_color4").value = clr4;
            $("#cpbg4 i").css("background-color", clr4);
        }
    </script>
    
    <script type="text/javascript">
        function readURL(input, prv) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function (e) {
                    $('#' + prv).attr('src', e.target.result);
                }
                reader.readAsDataURL(input.files[0]);
            }
        }

        @foreach(Helper::languagesList() as $ActiveLanguage)
        $("#style_logo_{{ @$ActiveLanguage->code }}").change(function () {
            readURL(this, "style_logo_{{ @$ActiveLanguage->code }}_prv");
        });
        @endforeach
    </script>
@endpush