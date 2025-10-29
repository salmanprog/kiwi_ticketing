@extends('dashboard.layouts.master')
@section('title', __('backend.mailSettings'))
@push("after-styles")
    <link href="{{ asset("assets/dashboard/js/iconpicker/fontawesome-iconpicker.min.css") }}" rel="stylesheet">
    <!--[if lt IE 9]>
    <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->
    <style>
        .smtp-settings-container {
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 2px 15px rgba(0,0,0,0.08);
            overflow: hidden;
            margin-bottom: 25px;
        }
        .smtp-header {
           background: linear-gradient(135deg, #A0C242 0%, #8AAE38 100%);
            color: white;
            padding: 20px 25px;
            border-bottom: none;
        }
        .smtp-header h3 {
            margin: 0;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 1.3rem;
        }
        .smtp-header small {
            opacity: 0.9;
            margin-top: 5px;
            display: block;
        }
        .smtp-header small a {
            color: rgba(255,255,255,0.9);
            text-decoration: none;
            transition: color 0.2s;
        }
        .smtp-header small a:hover {
            color: white;
            text-decoration: underline;
        }
        .smtp-body {
            padding: 25px;
        }
        .form-group.row {
            margin-bottom: 22px;
            align-items: center;
            padding: 8px 0;
        }
        .form-control-label {
            font-weight: 500;
            color: #2d3748;
            margin-bottom: 6px;
            font-size: 0.95rem;
        }
        .form-control {
            border-radius: 6px;
            border: 1px solid #e2e8f0;
            padding: 10px 12px;
            transition: all 0.2s;
            font-size: 0.95rem;
        }
        .form-control:focus {
            border-color: #28a745;
            box-shadow: 0 0 0 3px rgba(40, 167, 69, 0.15);
        }
        .c-select {
            cursor: pointer;
        }
        .smtp-footer {
            background: #f8f9fa;
            padding: 20px 25px;
            border-top: 1px solid #e9ecef;
            display: flex;
            justify-content: flex-end;
            gap: 12px;
        }
        .btn {
            border-radius: 6px;
            padding: 10px 20px;
            font-weight: 500;
            transition: all 0.2s;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            border: none;
            cursor: pointer;
            font-size: 0.9rem;
        }
        .btn.primary {
              background: linear-gradient(135deg, #A0C242 0%, #8AAE38 100%);
            color: white;
        }
        .btn.primary:hover {
            background: #218838;
            transform: translateY(-1px);
            box-shadow: 0 4px 8px rgba(40, 167, 69, 0.3);
        }
        .btn.info {
            background: #17a2b8;
            color: white;
        }
        .btn.info:hover {
            background: #138496;
            transform: translateY(-1px);
            box-shadow: 0 4px 8px rgba(23, 162, 184, 0.3);
        }
        .btn:disabled {
            opacity: 0.6;
            cursor: not-allowed;
            transform: none !important;
            box-shadow: none !important;
        }
        .displayNone {
            display: none !important;
        }
        .setting-description {
            color: #718096;
            font-size: 0.85rem;
            margin-top: 4px;
            font-style: italic;
        }
        .form-section {
            margin-bottom: 10px;
        }
        @media (max-width: 768px) {
            .smtp-footer {
                flex-direction: column;
                align-items: stretch;
            }
            .btn {
                justify-content: center;
            }
            .form-group.row {
                flex-direction: column;
                align-items: flex-start;
            }
            .form-control-label {
                margin-bottom: 8px;
            }
        }
    </style>
@endpush
@section('content')
    <div class="padding">
        <div class="smtp-settings-container">
            <div class="smtp-header">
                <h3><i class="material-icons">&#xe02e;</i> {{ __('SMTP Email') }}</h3>
                <small>
                    <a href="{{ route('adminHome') }}">{{ __('backend.home') }}</a> /
                    <a>{{__('smtp-email')}}</a> 
                </small>
            </div>
            
            {{Form::open(['route'=>['smtpUpdate'],'method'=>'POST','id'=>'settingsForm'])}}
            <input type="hidden" name="permission_group" id="permission_group" value="3">
            
            <div class="smtp-body">
                <div class="form-section">
                    <div class="form-group row">
                        <label for="mail_driver" class="col-sm-2 form-control-label">
                            {!!  __('backend.mailDriver') !!}
                        </label>
                        <div class="col-sm-10">
                            <select name="mail_driver" id="mail_driver" class="form-control c-select">
                                <option value="" {{ (config('smartend.mail_driver')== "") ? "selected='selected'":""  }}>
                                    None
                                </option>
                                <option value="sendmail" {{ (config('smartend.mail_driver')== "sendmail") ? "selected='selected'":""  }}>
                                    sendmail - PHP mail()
                                </option>
                                <option value="smtp" {{ (config('smartend.mail_driver')== "smtp") ? "selected='selected'":""  }}>
                                    SMTP (Recommended)
                                </option>
                                <option value="mailgun" {{ (config('smartend.mail_driver')== "mailgun") ? "selected='selected'":""  }}>
                                    Mailgun
                                </option>
                                <option value="ses" {{ (config('smartend.mail_driver')== "ses") ? "selected='selected'":""  }}>
                                    Amazon SES
                                </option>
                                <option value="postmark" {{ (config('smartend.mail_driver')== "postmark") ? "selected='selected'":""  }}>
                                    Postmark
                                </option>
                            </select>
                            <div class="setting-description">
                                Select the mail driver for sending emails from your application
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-section {{ (config('smartend.mail_driver') != "sendmail" && config('smartend.mail_driver') != "")?"":"displayNone" }}" id="mail_host_div">
                    <div class="form-group row">
                        <label for="mail_host" class="col-sm-2 form-control-label">
                            {!!  __('backend.mailHost') !!}
                        </label>
                        <div class="col-sm-10">
                            {!! Form::text('mail_host',config('smartend.mail_host'), array('id' => 'mail_host','class' => 'form-control', 'dir'=>'ltr')) !!}
                            <div class="setting-description">
                                Your SMTP server address (e.g., smtp.gmail.com)
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-section {{ (config('smartend.mail_driver') != "sendmail" && config('smartend.mail_driver') != "")?"":"displayNone" }}" id="mail_port_div">
                    <div class="form-group row">
                        <label for="mail_port" class="col-sm-2 form-control-label">
                            {!!  __('backend.mailPort') !!}
                        </label>
                        <div class="col-sm-10">
                            {!! Form::text('mail_port',config('smartend.mail_port'), array('id' => 'mail_port','class' => 'form-control', 'dir'=>'ltr')) !!}
                            <div class="setting-description">
                                SMTP port number (common: 587 for TLS, 465 for SSL)
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-section {{ (config('smartend.mail_driver') != "sendmail" && config('smartend.mail_driver') != "")?"":"displayNone" }}" id="mail_username_div">
                    <div class="form-group row">
                        <label for="mail_username" class="col-sm-2 form-control-label">
                            {!!  __('backend.mailUsername') !!}
                        </label>
                        <div class="col-sm-10">
                            {!! Form::text('mail_username',config('smartend.mail_username'), array('id' => 'mail_username','class' => 'form-control', 'dir'=>'ltr')) !!}
                            <div class="setting-description">
                                Your email username or full email address
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-section {{ (config('smartend.mail_driver') != "sendmail" && config('smartend.mail_driver') != "")?"":"displayNone" }}" id="mail_password_div">
                    <div class="form-group row">
                        <label for="mail_password" class="col-sm-2 form-control-label">
                            {!!  __('backend.mailPassword') !!}
                        </label>
                        <div class="col-sm-10">
                            {!! Form::text('mail_password',config('smartend.mail_password'), array('id' => 'mail_password','class' => 'form-control', 'dir'=>'ltr')) !!}
                            <div class="setting-description">
                                Your email password or app-specific password
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-section {{ (config('smartend.mail_driver') != "sendmail" && config('smartend.mail_driver') != "")?"":"displayNone" }}" id="mail_encryption_div">
                    <div class="form-group row">
                        <label for="mail_encryption" class="col-sm-2 form-control-label">
                            {!!  __('backend.mailEncryption') !!}
                        </label>
                        <div class="col-sm-10">
                            <select name="mail_encryption" id="mail_encryption" class="form-control c-select">
                                <option value="" {{ (config('smartend.mail_encryption') == "") ? "selected='selected'":""  }}>
                                    none
                                </option>
                                <option value="ssl" {{ (config('smartend.mail_encryption') == "ssl") ? "selected='selected'":""  }}>
                                    ssl
                                </option>
                                <option value="tls" {{ (config('smartend.mail_encryption') == "tls") ? "selected='selected'":""  }}>
                                    tls
                                </option>
                            </select>
                            <div class="setting-description">
                                Encryption method for secure connection
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-section {{ (config('smartend.mail_driver') == "")?"displayNone":"" }}" id="mail_from_div">
                    <div class="form-group row">
                        <label for="mail_no_replay" class="col-sm-2 form-control-label">
                            {!!  __('backend.mailNoReplay') !!}
                        </label>
                        <div class="col-sm-10">
                            {!! Form::text('mail_no_replay',config('smartend.mail_from_address'), array('id' => 'mail_no_replay','class' => 'form-control', 'dir'=>'ltr')) !!}
                            <div class="setting-description">
                                Default "from" address for outgoing emails
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="smtp-footer">
                <!-- Uncomment these buttons if needed -->
                <!--
                <button id="smtp_check" type="button"
                        class="btn info {{ (config('smartend.mail_driver') == "smtp")?"":"displayNone" }}">
                    <i class="fa fa-bolt"></i> {{ __("backend.smtpCheck") }}
                </button>
                <button id="send_test" type="button" class="btn info {{ (config('smartend.mail_driver') == "")?"displayNone":"" }}">
                    <i class="fa fa-envelope"></i> {{ __("backend.sendTestMail") }}
                </button>
                -->
                <button type="submit" id="save-settings-btn" class="btn primary">
                    <i class="material-icons">&#xe31b;</i> {{ __('backend.update') }}
                </button>
                
                <input type="hidden" name="mail_test" id="to_email" value="">
            </div>
            {{Form::close()}}
        </div>
    </div>
@endsection

@push("after-scripts")
    <script src="{{ asset("assets/dashboard/js/iconpicker/fontawesome-iconpicker.js") }}"></script>
    <script>
        $(function () {
            $('.icp-auto').iconpicker({placement: '{{ (@Helper::currentLanguage()->direction=="rtl")?"topLeft":"topRight" }}'});
        });

        $('#mail_driver').on('change', function () {
            if ($(this).val() == "sendmail" || $(this).val() == "") {
                $("#smtp_check").hide();

                $("#mail_host").val('');
                $("#mail_port").val('');
                $("#mail_username").val('');
                $("#mail_encryption").val('');
                $("#mail_password").val('');

                $("#mail_host_div").hide();
                $("#mail_port_div").hide();
                $("#mail_username_div").hide();
                $("#mail_password_div").hide();
                $("#mail_encryption_div").hide();
                $("#send_test").show();
                $("#mail_from_div").show();
                if ($(this).val() == "") {
                    $("#send_test").hide();
                    $("#mail_from_div").hide();
                }

            } else {
                $("#mail_host_div").show();
                $("#mail_port_div").show();
                $("#mail_username_div").show();
                $("#mail_password_div").show();
                $("#mail_encryption_div").show();
                $("#smtp_check").show();
                $("#send_test").show();
                $("#mail_from_div").show();
            }
            if ($(this).val() != "smtp") {
                $("#smtp_check").hide();
            } else {
                $("#smtp_check").show();
            }
        });

        $('#smtp_check').click(function () {
            if ($("#mail_host").val() != "" && $("#mail_port").val() != "") {
                $('#smtp_check').html("<img src=\"{{ asset('assets/dashboard/images/loading.gif') }}\" style=\"height: 20px\"/> {!! __('backend.smtpCheck') !!}");
                $('#smtp_check').prop('disabled', true);
                $('#save-settings-btn').prop('disabled', true);

                var xhr = $.ajax({
                    type: "POST",
                    url: "<?php echo route("mailSMTPCheck"); ?>",
                    data: {
                        "_token": $('meta[name="csrf-token"]').attr('content'),
                        "mail_driver": $("#mail_driver").val(),
                        "mail_host": $("#mail_host").val(),
                        "mail_port": $("#mail_port").val(),
                        "mail_username": $("#mail_username").val(),
                        "mail_password": $("#mail_password").val(),
                        "mail_encryption": $("#mail_encryption").val(),
                    },
                    success: function (result) {
                        var obj_result = jQuery.parseJSON(result);
                        if (obj_result.stat == 'success') {
                            swal({
                                title: "<span class='text-success'>{{ __("backend.smtpCheckSuccess") }}</span>",
                                text: "{{ __("backend.smtpCheckSuccessMsg") }}",
                                html: true,
                                type: "success",
                                confirmButtonText: "{{ __("backend.close") }}",
                                confirmButtonColor: "#28a745",
                                timer: 5000,
                            });
                        } else {
                            swal({
                                title: "<span class='text-danger'>{{ __("backend.smtpCheck") }}</span>",
                                text: "<span class='text-danger' dir='ltr'>" + obj_result.error + "</span>",
                                html: true,
                                type: "error",
                                confirmButtonText: "{{ __("backend.close") }}",
                                confirmButtonColor: "#dc3545",
                            });
                        }
                        $('#smtp_check').html("<i class=\"fa fa-bolt\"></i> {!! __('backend.smtpCheck') !!}");
                        $('#smtp_check').prop('disabled', false);
                        $('#save-settings-btn').prop('disabled', false);
                    }
                });
            }
        });

        $('#send_test').click(function () {
            swal({
                title: "{{ __("backend.sendTestMail") }}",
                text: "{{ __("backend.sendTestMailTo") }}",
                type: "input",
                showCancelButton: true,
                closeOnConfirm: false,
                animation: "slide-from-top",
                inputPlaceholder: "email@site.com",
                inputValue: $("#to_email").val(),
                confirmButtonText: "{{ __("backend.continue") }}",
                cancelButtonText: "{{ __("backend.cancel") }}",
                confirmButtonColor: "#28a745",
                showLoaderOnConfirm: true,
            }, function (inputValue) {
                if (inputValue === false) return false;
                if (inputValue === "") {
                    swal.showInputError("{{ __("backend.sendTestMailTo") }}");
                    return false
                }
                if (!validateEmail(inputValue)) {
                    swal.showInputError("{{ __("backend.sendTestMailError") }}");
                    return false
                }
                $("#to_email").val(inputValue);
                var xhr = $.ajax({
                    type: "POST",
                    url: "<?php echo route("mailTest"); ?>",
                    data: {
                        "_token": $('meta[name="csrf-token"]').attr('content'),
                        "mail_driver": $("#mail_driver").val(),
                        "mail_host": $("#mail_host").val(),
                        "mail_port": $("#mail_port").val(),
                        "mail_username": $("#mail_username").val(),
                        "mail_password": $("#mail_password").val(),
                        "mail_encryption": $("#mail_encryption").val(),
                        "mail_no_replay": $("#mail_no_replay").val(),
                        "mail_test": $("#to_email").val(),
                    },
                    success: function (result) {
                        var obj_result = jQuery.parseJSON(result);
                        if (obj_result.stat == 'success') {
                            swal({
                                title: "<span class='text-success'>{{ __("backend.mailTestSuccess") }}</span>",
                                text: inputValue,
                                html: true,
                                type: "success",
                                confirmButtonText: "{{ __("backend.close") }}",
                                confirmButtonColor: "#28a745",
                                timer: 5000,
                            });
                        } else {
                            swal({
                                title: "<span class='text-danger'>{{ __("backend.mailTestFailed") }}</span>",
                                text: inputValue,
                                html: true,
                                type: "error",
                                confirmButtonText: "{{ __("backend.close") }}",
                                confirmButtonColor: "#dc3545",
                            });
                        }
                    }
                });
            });
        });

        function validateEmail(email) {
            var re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
            return re.test(String(email).toLowerCase());
        }
    </script>
@endpush