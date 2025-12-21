@extends('dashboard.layouts.master')
@section('title', __('Coupons'))
@push("after-styles")
    <link href="{{ asset("assets/dashboard/js/iconpicker/fontawesome-iconpicker.min.css") }}" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css" rel="stylesheet">
    <style>
        .coupon-form-container {
            background: white;
            border-radius: 12px;
            box-shadow: 0 2px 20px rgba(0,0,0,0.08);
            border: 1px solid #e9ecef;
            margin-bottom: 2rem;
            overflow: hidden;
        }

        .modern-header {
            background: linear-gradient(135deg, #A0C242 0%, #8AAE38 100%);
            color: white;
            padding: 1.5rem 2rem;
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
        }

        .breadcrumb-modern a {
            color: rgba(255,255,255,0.9);
            text-decoration: none;
        }

        .breadcrumb-modern a:hover {
            color: white;
            text-decoration: underline;
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
            color: #2c3e50;
            margin-bottom: 0.5rem;
            flex: 0 0 200px;
            padding-top: 0.5rem;
            display: flex;
            align-items: center;
        }

        .form-label-icon {
            margin-right: 10px !important;
            width: 16px;
            text-align: center;
            color: #A0C242;
        }

        .form-control-modern {
            border: 2px solid #e9ecef;
            border-radius: 8px;
            padding: 0.75rem 1rem;
            font-size: 1rem;
            transition: all 0.3s ease;
            background: white;
            width: 100%;
        }

        .form-control-modern:focus {
            border-color: #A0C242;
            box-shadow: 0 0 0 3px rgba(160, 194, 66, 0.1);
            background: white;
            outline: none;
        }

        .select-modern {
            appearance: none;
            background-image: url("data:image/svg+xml;charset=US-ASCII,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 4 5'><path fill='%236c757d' d='M2 0L0 2h4zm0 5L0 3h4z'/></svg>");
            background-repeat: no-repeat;
            background-position: right 1rem center;
            background-size: 12px;
            padding-right: 3rem;
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
            border: 2px solid #e9ecef;
            border-radius: 50%;
            position: relative;
            transition: all 0.3s ease;
        }

        .radio-input:checked + .radio-custom {
            border-color: #A0C242;
            background: #A0C242;
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
            color: #2c3e50;
        }

        /* Form Actions */
        .form-actions {
            padding-top: 1.5rem;
            border-top: 1px solid #e9ecef;
            margin-top: 2rem;
            display: flex;
            gap: 1rem;
            align-items: center;
            justify-content: flex-end;
        }

        .btn-modern-primary {
            background: linear-gradient(135deg, #A0C242 0%, #8AAE38 100%);
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
            border: 2px solid #e9ecef;
            border-radius: 8px;
            padding: 0.75rem 2rem;
            color: #2c3e50;
            font-weight: 500;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            text-decoration: none;
        }

        .btn-modern-default:hover {
            border-color: #A0C242;
            color: #A0C242;
            transform: translateY(-1px);
        }

        /* Date Picker Styling */
        .datepicker {
            border-radius: 8px;
            border: 2px solid #e9ecef;
        }

        .datepicker-dropdown:before {
            border-bottom-color: #A0C242;
        }

        .datepicker table tr td.active.active,
        .datepicker table tr td.active:hover.active {
            background-color: #A0C242;
            border-color: #A0C242;
        }

        .datepicker table tr td.today {
            background-color: rgba(160, 194, 66, 0.1);
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
        }
    </style>
    <!--[if lt IE 9]>
    <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->
@endpush

@section('content')
    <div class="padding">
        <div class="coupon-form-container">
            <!-- Header Section -->
            <div class="modern-header">
                <h3><i class="fas fa-tag"></i> {{ __('New Coupon') }}</h3>
                <nav class="breadcrumb-modern">
                    <a href="{{ route('adminHome') }}">{{ __('backend.home') }}</a> /
                    <a>{{__('coupon')}}</a> 
                </nav>
            </div>

            <!-- Form Section -->
            <div class="form-container">
                {{Form::open(['route'=>['couponStore'],'method'=>'POST', 'files' => true, 'class' => 'modern-form'])}}
                
                <!-- Type Field -->
                <div class="form-group-modern">
                    <label for="package_type" class="form-label-modern">
                        <i class="fas fa-cube form-label-icon"></i>{!! __('Type') !!}
                    </label>
                    <div style="flex: 1;">
                        <select name="package_type" id="package_type" class="form-control-modern select-modern" required>
                            <option value="">- - {!!  __('Select Type') !!} - -</option>
                            <option value="cabana">{!! __('Cabana') !!}</option>
                            <option value="birthday">{!! __('Birthday') !!}</option>
                            <option value="general_ticket">{!! __('Platform Ticket') !!}</option>
                            <option value="season_pass">{!! __('Season Pass') !!}</option>
                            <option value="offer_creation">{!! __('Offers') !!}</option>
                            <option value="product_sale">{!! __('Product Sale') !!}</option>
                        </select>
                    </div>
                </div>

                <div class="form-group-modern mt-3">
                    <label for="package_id" class="form-label-modern">
                        <i class="fas fa-box-open form-label-icon"></i>{!! __('Packages') !!}
                    </label>
                    <div style="flex: 1;">
                        <select name="package_id" id="package_id" class="form-control-modern select-modern" required>
                            <option value="">- - {!! __('Select Package') !!} - -</option>
                        </select>
                    </div>
                </div>
                <div id="addons-container" class="mt-4"></div>
                <!-- Title Field -->
                <div class="form-group-modern">
                    <label for="title" class="form-label-modern">
                        <i class="fas fa-heading form-label-icon"></i>{!!  __('Title') !!}
                    </label>
                    <div style="flex: 1;">
                        {!! Form::text('title','', array('placeholder' => 'Enter coupon title','class' => 'form-control-modern','id'=>'title','required'=>'')) !!}
                    </div>
                </div>

                <!-- Coupon Code Field -->
                <div class="form-group-modern">
                    <label for="coupon_code" class="form-label-modern">
                        <i class="fas fa-code form-label-icon"></i>{!!  __('Coupon Code') !!}
                    </label>
                    <div style="flex: 1;">
                        {!! Form::text('coupon_code','', array('placeholder' => 'Enter coupon code','class' => 'form-control-modern','id'=>'coupon_code','required'=>'')) !!}
                    </div>
                </div>

                <!-- Discount Field -->
                <div class="form-group-modern">
                    <label for="discount" class="form-label-modern">
                        <i class="fas fa-percentage form-label-icon"></i>{!!  __('Discount') !!}
                    </label>
                    <div style="flex: 1;">
                        {!! Form::text('discount','', array('placeholder' => 'Enter discount amount','class' => 'form-control-modern','id'=>'discount','required'=>'')) !!}
                    </div>
                </div>

                <!-- Discount Type Field -->
                <div class="form-group-modern">
                    <label for="discount_type" class="form-label-modern">
                        <i class="fas fa-tags form-label-icon"></i>{!!  __('Discount Type') !!}
                    </label>
                    <div style="flex: 1;">
                        <select name="discount_type" id="discount_type" class="form-control-modern select-modern" required>
                            <option value="">- - {!!  __('Select Discount Type') !!} - -</option>
                            <option value="percentage">{!! __('Percentage') !!}</option>
                            <option value="flat_rate">{!! __('Flat Rate') !!}</option>
                        </select>
                    </div>
                </div>

                <!-- Start Date Field -->
                <div class="form-group-modern">
                    <label for="start_date" class="form-label-modern">
                        <i class="fas fa-calendar-alt form-label-icon"></i>{!!  __('Start Date') !!}
                    </label>
                    <div style="flex: 1;">
                        {!! Form::text('start_date','', array('placeholder' => 'Select start date','class' => 'form-control-modern','id'=>'start_date','required'=>'')) !!}
                    </div>
                </div>

                <!-- End Date Field -->
                <div class="form-group-modern">
                    <label for="end_date" class="form-label-modern">
                        <i class="fas fa-calendar-times form-label-icon"></i>{!!  __('End Date') !!}
                    </label>
                    <div style="flex: 1;">
                        {!! Form::text('end_date','', array('placeholder' => 'Select end date','class' => 'form-control-modern','id'=>'end_date','required'=>'')) !!}
                    </div>
                </div>

                <!-- Coupon Limit Field -->
                <div class="form-group-modern">
                    <label for="coupon_total_limit" class="form-label-modern">
                        <i class="fas fa-sort-amount-up form-label-icon"></i>{!!  __('Coupon Limit') !!}
                    </label>
                    <div style="flex: 1;">
                        {!! Form::text('coupon_total_limit','', array('placeholder' => 'Enter coupon usage limit','class' => 'form-control-modern','id'=>'coupon_total_limit','required'=>'')) !!}
                    </div>
                </div>

                <!-- Status Field -->
                <div class="form-group-modern">
                    <label class="form-label-modern">
                        <i class="fas fa-toggle-on form-label-icon"></i>Status
                    </label>
                    <div style="flex: 1;">
                        <div class="radio-group">
                            <label class="radio-modern">
                                <input type="radio" name="status" value="1" 
                                       class="radio-input" 
                                       id="status1"
                                       checked>
                                <span class="radio-custom"></span>
                                <span class="radio-label">Active</span>
                            </label>
                            <label class="radio-modern">
                                <input type="radio" name="status" value="0" 
                                       class="radio-input" 
                                       id="status2">
                                <span class="radio-custom"></span>
                                <span class="radio-label">Not Active</span>
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="form-actions">
                    <a href="javascript:history.back()" class="btn-modern-default">
                        <i class="fas fa-times"></i> {{ __('backend.cancel') }}
                    </a>
                    <button type="submit" class="btn-modern-primary">
                        <i class="fas fa-plus"></i> {!! __('backend.add') !!}
                    </button>
                </div>

                {{Form::close()}}
            </div>
        </div>
    </div>
@endsection

@push("after-scripts")
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>
    <script src="{{ asset("assets/dashboard/js/iconpicker/fontawesome-iconpicker.js") }}"></script>
    <script>
        $(function () {
            $('.icp-auto').iconpicker({placement: '{{ (@Helper::currentLanguage()->direction=="rtl")?"topLeft":"topRight" }}'});
        });
        
        $(document).ready(function () {
            // Datepicker initialization with modern styling
            $('#start_date').datepicker({
                format: 'yyyy-mm-dd',
                autoclose: true,
                todayHighlight: true,
                templates: {
                    leftArrow: '<i class="fas fa-chevron-left"></i>',
                    rightArrow: '<i class="fas fa-chevron-right"></i>'
                }
            });
            
            $('#end_date').datepicker({
                format: 'yyyy-mm-dd',
                autoclose: true,
                todayHighlight: true,
                templates: {
                    leftArrow: '<i class="fas fa-chevron-left"></i>',
                    rightArrow: '<i class="fas fa-chevron-right"></i>'
                }
            });

            // Add focus effects to form controls
            document.querySelectorAll('.form-control-modern').forEach(input => {
                input.addEventListener('focus', function() {
                    this.style.transform = 'translateY(-2px)';
                });
                
                input.addEventListener('blur', function() {
                    this.style.transform = 'translateY(0)';
                });
            });

            // Form validation enhancement
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

            $('#package_type').on('change', function() {
                let type = $(this).val();
                let $packageSelect = $('#package_id');
                
                $packageSelect.html('<option value="">Loading...</option>');
                $('#addons-container').empty();
                if (type) {
                    $.ajax({
                        url: "{{ route('get.packages.by.type') }}",
                        type: 'GET',
                        data: { type: type },
                        success: function(response) {
                            $packageSelect.empty();
                            $packageSelect.append('<option value="">- - Select Package - -</option>');
                            $.each(response.packages, function(index, item) {
                                $packageSelect.append('<option value="'+ item.id +'" data-slug="'+ item.slug +'">'+ item.name +'</option>');
                            });
                        },
                        error: function() {
                            $packageSelect.html('<option value="">Error loading packages</option>');
                        }
                    });
                } else {
                    $packageSelect.html('<option value="">- - Select Package - -</option>');
                }
            });
            $('#package_id').on('change', function() {
                let $selectedOption = $(this).find(':selected');
                let slug = $selectedOption.data('slug');
                let type = $('#package_type').val();
                let $addonsContainer = $('#addons-container');

                $addonsContainer.html('<p>Loading addons...</p>');

                if (slug && type) {
                    $.ajax({
                        url: "{{ route('get.packages.products') }}",
                        type: 'GET',
                        data: { type: type, slug: slug },
                        success: function(response) {
                            $addonsContainer.empty();

                            if (response.packages.length > 0) {
                                let html = '<div class="form-group-modern">';
                                html += '<label class="form-label-modern"><i class="fas fa-percentage form-label-icon"></i>Products</label>';
                                html += '<div class="col-sm-10"><div class="row">';

                                $.each(response.packages, function(index, addon) {
                                    html += `
                                        <div class="col-md-4 col-sm-6 mb-2">
                                            <label class="ui-check ui-check-md d-block">
                                                <input id="ticket_active_${addon.ticketSlug}" 
                                                    class="has-value" 
                                                    name="ticket[]" 
                                                    type="checkbox" 
                                                    value="${addon.ticketSlug}">
                                                <i class="dark-white"></i> ${addon.name}
                                            </label>
                                        </div>`;
                                });

                                html += '</div></div></div>';
                                $addonsContainer.html(html);
                            } else {
                                $addonsContainer.html('<p>No addons available.</p>');
                            }
                        },
                        error: function() {
                            $addonsContainer.html('<p>Error loading addons.</p>');
                        }
                    });
                } else {
                    $addonsContainer.empty();
                }
            });
        });
    </script>
@endpush