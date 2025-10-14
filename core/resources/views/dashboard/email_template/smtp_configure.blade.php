@extends('dashboard.layouts.master')
@section('title', __('backend.mailSettings'))
@push("after-styles")
    <link href="{{ asset("assets/dashboard/js/iconpicker/fontawesome-iconpicker.min.css") }}" rel="stylesheet">
    <!--[if lt IE 9]>
    <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->
@endpush
@section('content')
    <div class="padding">
        <div class="box">
            <div class="box-header dker">
                <h3><i class="material-icons">&#xe02e;</i> {{ __('SMTP Email') }}</h3>
                <small>
                    <a href="{{ route('adminHome') }}">{{ __('backend.home') }}</a> /
                    <a>{{__('smtp-email')}}</a> 
                </small>
            </div>
            <div class="box-body p-a-2">
                    <div class="form-group row">
                        <label for="title"
                                class="col-sm-2 form-control-label">{!!  __('backend.mailDriver') !!}
                        </label>
                        <div class="col-sm-10">
                            <select name="mail_driver" id="mail_driver" class="form-control c-select">
                                <option
                                    value="" {{ (config('smartend.mail_driver')== "") ? "selected='selected'":""  }}>
                                    None
                                </option>
                                <option
                                    value="sendmail" {{ (config('smartend.mail_driver')== "sendmail") ? "selected='selected'":""  }}>
                                    sendmail - PHP mail()
                                </option>
                                <option
                                    value="smtp" {{ (config('smartend.mail_driver')== "smtp") ? "selected='selected'":""  }}>
                                    SMTP ( Recommended )
                                </option>
                                <option
                                    value="mailgun" {{ (config('smartend.mail_driver')== "mailgun") ? "selected='selected'":""  }}>
                                    Mailgun
                                </option>
                                <option
                                    value="ses" {{ (config('smartend.mail_driver')== "ses") ? "selected='selected'":""  }}>
                                    Amazon SES
                                </option>
                                <option
                                    value="postmark" {{ (config('smartend.mail_driver')== "postmark") ? "selected='selected'":""  }}>
                                    Postmark
                                </option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row {{ (config('smartend.mail_driver') != "sendmail" && config('smartend.mail_driver') != "")?"":"displayNone" }}" id="mail_host_div">
                        <label for="title"
                                class="col-sm-2 form-control-label">{!!  __('backend.mailHost') !!}
                        </label>
                        <div class="col-sm-10">
                            {!! Form::text('mail_host',config('smartend.mail_host'), array('id' => 'mail_host','class' => 'form-control', 'dir'=>'ltr')) !!}
                        </div>
                    </div>
                    <div class="form-group row {{ (config('smartend.mail_driver') != "sendmail" && config('smartend.mail_driver') != "")?"":"displayNone" }}" id="mail_port_div">
                        <label for="title"
                                class="col-sm-2 form-control-label">{!!  __('backend.mailPort') !!}
                        </label>
                        <div class="col-sm-10">
                            {!! Form::text('mail_port',config('smartend.mail_port'), array('id' => 'mail_port','class' => 'form-control', 'dir'=>'ltr')) !!}
                        </div>
                    </div>
                    <div class="form-group row {{ (config('smartend.mail_driver') != "sendmail" && config('smartend.mail_driver') != "")?"":"displayNone" }}" id="mail_username_div">
                        <label for="title"
                                class="col-sm-2 form-control-label">{!!  __('backend.mailUsername') !!}
                        </label>
                        <div class="col-sm-10">
                            {!! Form::text('mail_username',config('smartend.mail_username'), array('id' => 'mail_username','class' => 'form-control', 'dir'=>'ltr')) !!}
                        </div>
                    </div>
                    <div class="form-group row {{ (config('smartend.mail_driver') != "sendmail" && config('smartend.mail_driver') != "")?"":"displayNone" }}" id="mail_password_div">
                        <label for="title"
                                class="col-sm-2 form-control-label">{!!  __('backend.mailPassword') !!}
                        </label>
                        <div class="col-sm-10">
                            {!! Form::text('mail_password',config('smartend.mail_password'), array('id' => 'mail_password','class' => 'form-control', 'dir'=>'ltr')) !!}
                        </div>
                    </div>
                    <div class="form-group row {{ (config('smartend.mail_driver') != "sendmail" && config('smartend.mail_driver') != "")?"":"displayNone" }}" id="mail_encryption_div">
                        <label for="title"
                                class="col-sm-2 form-control-label">{!!  __('backend.mailEncryption') !!}
                        </label>
                        <div class="col-sm-10">
                            <select name="mail_encryption" id="mail_encryption" class="form-control c-select">
                                <option
                                    value="" {{ (config('smartend.mail_encryption') == "") ? "selected='selected'":""  }}>
                                    none
                                </option>
                                <option
                                    value="ssl" {{ (config('smartend.mail_encryption') == "ssl") ? "selected='selected'":""  }}>
                                    ssl
                                </option>
                                <option
                                    value="tls" {{ (config('smartend.mail_encryption') == "tls") ? "selected='selected'":""  }}>
                                    tls
                                </option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row {{ (config('smartend.mail_driver') == "")?"displayNone":"" }}" id="mail_from_div">
                        <label for="title"
                                class="col-sm-2 form-control-label">{!!  __('backend.mailNoReplay') !!}
                        </label>
                        <div class="col-sm-10">
                            {!! Form::text('mail_no_replay',config('smartend.mail_from_address'), array('id' => 'mail_no_replay','class' => 'form-control', 'dir'=>'ltr')) !!}
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                <button id="smtp_check" type="button"
                        class="btn pull-right btn-sm info {{ (config('smartend.mail_driver') == "smtp")?"":"displayNone" }}">
                    <i class="fa fa-bolt"></i> &nbsp;{{ __("backend.smtpCheck") }}
                </button>
                <button id="send_test" type="button" class="btn btn-sm info {{ (config('smartend.mail_driver') == "")?"displayNone":"" }}">
                    <i class="fa fa-envelope"></i> &nbsp;{{ __("backend.sendTestMail") }}
                </button>
                <input type="hidden" name="mail_test" id="to_email" value="">
            </div>
            
        </div>
    </div>
@endsection
@push("after-scripts")
    <script src="{{ asset("assets/dashboard/js/iconpicker/fontawesome-iconpicker.js") }}"></script>
    <script>
        $(function () {
            $('.icp-auto').iconpicker({placement: '{{ (@Helper::currentLanguage()->direction=="rtl")?"topLeft":"topRight" }}'});
        });
    </script>
    <script type="text/javascript">
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
                $('#mail_save_btn').prop('disabled', true);

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
                                confirmButtonColor: "#acacac",
                                timer: 5000,
                            });
                        } else {
                            swal({
                                title: "<span class='text-danger'>{{ __("backend.smtpCheck") }}</span>",
                                text: "<span class='text-danger' dir='ltr'>" + obj_result.error + "</span>",
                                html: true,
                                type: "error",
                                confirmButtonText: "{{ __("backend.close") }}",
                                confirmButtonColor: "#acacac",
                            });
                        }
                        $('#smtp_check').html("<i class=\"fa fa-bolt\"></i> {!! __('backend.smtpCheck') !!}");
                        $('#smtp_check').prop('disabled', false);
                        $('#mail_save_btn').prop('disabled', false);
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
                                confirmButtonColor: "#acacac",
                                timer: 5000,
                            });
                        } else {
                            swal({
                                title: "<span class='text-danger'>{{ __("backend.mailTestFailed") }}</span>",
                                text: inputValue,
                                html: true,
                                type: "error",
                                confirmButtonText: "{{ __("backend.close") }}",
                                confirmButtonColor: "#acacac",
                            });
                        }
                    }
                });
            });
        });
    </script>
@endpush
