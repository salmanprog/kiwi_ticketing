<!-- @push('after-styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css" />
@endpush
<div class="dker b-b displayNone" id="filter_div">
    <div class="p-a">
        {{Form::open(['route'=>['offercreationpackagesorders'],'method'=>'GET','id'=>'filter_form','target'=>''])}}
        <input type="hidden" name="stat" id="search_submit_stat" value="">
       <div class="filter_div">
            <div class="row">
                <div class="col-md-4 col-xs-6 m-b-5p">
                        {!! Form::text('find_q',@$_GET['find_q'], array('placeholder' =>  __('backend.searchFor'),'class' => 'form-control','id'=>'find_q', "autocomplete"=>"off")) !!}
                    </div>
                 <div class="col-md-4 col-xs-6 m-b-5p">
                    <select name="type"
                            id="find_type"
                            class="form-control select2"
                            ui-jp="select2"
                            ui-options="{theme: 'bootstrap'}">
                        <option value="">{{ __('Select Type') }}</option>
                        <option value="offer_creation">{{ __('OfferCreation Orders') }}</option>
                        
                    </select>
                </div>
                <div class="col-md-4 col-xs-6 m-b-5p">
                    <select name="package_id"
                            id="find_package"
                            class="form-control select2"
                            ui-jp="select2"
                            ui-options="{theme: 'bootstrap'}"
                            data-selected="{{ request('package_id') }}">
                        <option value="">{{ __('Select Packages') }}</option>
                    </select>
                </div>
                <div class="col-md-4 col-xs-6 m-b-5p">
                    <input type="text" name="from_date" id="from_date" class="form-control datepicker" placeholder="{{ __('From Date') }}" autocomplete="off" value="{{ request('from_date') }}">
                </div>
                <div class="col-md-4 col-xs-6 m-b-5p">
                    <input type="text" name="to_date" id="to_date" class="form-control datepicker" placeholder="{{ __('To Date') }}" autocomplete="off" value="{{ request('to_date') }}">
                </div>
                <div class="col-md-1 col-xs-6 m-b-5p">
                    <button class="btn white w-full" id="search-btn" type="button"><i
                            class="fa fa-search"></i>
                    </button>
                </div>
                <div class="col-md-1 col-xs-6 m-b-5p">
                    <button class="btn white w-full" id="reset-btn" type="button"><i class="fa fa-refresh"></i> Reset</button>
                </div>
            </div>
        </div>
        {{Form::close()}}
    </div>
</div>
@push("after-scripts")
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>
<script>
    $(document).ready(function () {
        $('#find_type').on('change', function () {
            let selectedType = $(this).val();
            let $packageDropdown = $('#find_package');
            let previousSelectedPackage = $packageDropdown.data('selected') || '';
            $packageDropdown.html('<option value="">{{ __("Loading...") }}</option>').prop('disabled', true);

            if (selectedType !== '') {
                $.ajax({
                    url: '{{ route("getPackagesByType") }}',
                    type: 'GET',
                    data: {
                        type: selectedType
                    },
                    success: function (response) {
                        let options = '<option value="">{{ __("Select Packages") }}</option>';
                        if (response.packages && response.packages.length > 0) {
                            response.packages.forEach(function (item) {
                                let isSelected = (item.id == previousSelectedPackage) ? 'selected' : '';
                                options += `<option value="${item.id}" ${isSelected}>${item.name}</option>`;
                            });
                        }
                        $packageDropdown.html(options).prop('disabled', false);
                    },
                    error: function () {
                        $packageDropdown.html('<option value="">{{ __("Failed to load packages") }}</option>');
                    }
                });
            } else {
                $packageDropdown.html('<option value="">{{ __("Select Packages") }}</option>').prop('disabled', false);
            }
        });
        let initialSelected = '{{ request("package_id") }}';
        if (initialSelected) {
            $('#find_package').data('selected', initialSelected);
        }

        $('#reset-btn').on('click', function() {
            $('#find_q').val('');
            $('#find_type').val('').trigger('change');
            $('#find_package').html('<option value="">{{ __("Select Packages") }}</option>').prop('disabled', false);
            $('#date_range').val('');
            $('#from_date').val('');
            $('#to_date').val('');
            $('#filter_form').submit();
        });
    });
    $(function () {
        $('.datepicker').datepicker({
            format: 'yyyy-mm-dd',
            autoclose: true,
            todayHighlight: true,
            clearBtn: true
        });

        $('#from_date').datepicker({
            format: 'yyyy-mm-dd',
            autoclose: true,
            todayHighlight: true
        }).on('changeDate', function (e) {
            let startDate = e.date;
            $('#to_date').datepicker('setStartDate', startDate);

            let currentToDate = $('#to_date').datepicker('getDate');
            if (currentToDate && currentToDate < startDate) {
                $('#to_date').datepicker('clearDate'); // ✅ Corrected method
            }
        });

        $('#to_date').datepicker({
            format: 'yyyy-mm-dd',
            autoclose: true,
            todayHighlight: true
        });
    });


</script>
@endpush -->

@push('after-styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css" />
@endpush

<div style="background: linear-gradient(135deg, #A0C242 0%, #8AAE38 100%); padding: 1.5rem; margin-top:4px; box-shadow: 0 4px 20px rgba(0,0,0,0.1); " id="filter_div">
    <div style="background: rgba(255, 255, 255, 0.95); backdrop-filter: blur(10px); border-radius: 10px; padding: 1.5rem; border: 1px solid rgba(255, 255, 255, 0.2);">
        {{Form::open(['route'=>['offercreationpackagesorders'],'method'=>'GET','id'=>'filter_form','target'=>''])}}
        <input type="hidden" name="stat" id="search_submit_stat" value="">
        
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem; align-items: end;">
            <!-- Search Input -->
            <div style="display: flex; flex-direction: column;">
                <label style="font-weight: 600; color: #2c3e50; margin-bottom: 0.5rem; font-size: 0.9rem; display: flex; align-items: center; gap: 0.5rem;">
                    <i class="fas fa-search" style="color: #A0C242; font-size: 14px;"></i>
                    {{ __('backend.searchFor') }}
                </label>
                {!! Form::text('find_q',@$_GET['find_q'], array(
                    'placeholder' => __('backend.searchFor'),
                    'style' => 'border: 2px solid #e9ecef; border-radius: 8px; padding: 0.75rem 1rem; font-size: 0.9rem; transition: all 0.3s ease; background: white; width: 100%;',
                    'id'=>'find_q', 
                    "autocomplete"=>"off",
                    'onfocus' => 'this.style.borderColor="#A0C242"; this.style.boxShadow="0 0 0 3px rgba(160, 194, 66, 0.1)";',
                    'onblur' => 'this.style.borderColor="#e9ecef"; this.style.boxShadow="none";'
                )) !!}
            </div>

            <!-- Type Select -->
            <div style="display: flex; flex-direction: column;">
                <label style="font-weight: 600; color: #2c3e50; margin-bottom: 0.5rem; font-size: 0.9rem; display: flex; align-items: center; gap: 0.5rem;">
                    <i class="fas fa-filter" style="color: #A0C242; font-size: 14px;"></i>
                    {{ __('Select Type') }}
                </label>
                <select name="type"
                        id="find_type"
                        style="border: 2px solid #e9ecef; border-radius: 8px; padding: 0.75rem 1rem; font-size: 0.9rem; transition: all 0.3s ease; background: white; width: 100%; appearance: none; background-image: url(\"data:image/svg+xml;charset=US-ASCII,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 4 5'><path fill='%236c757d' d='M2 0L0 2h4zm0 5L0 3h4z'/></svg>\"); background-repeat: no-repeat; background-position: right 1rem center; background-size: 12px; padding-right: 2.5rem;"
                        ui-jp="select2"
                        ui-options="{theme: 'bootstrap'}">
                    <option value="">{{ __('Select Type') }}</option>
                    <option value="offer_creation">{{ __('OfferCreation Orders') }}</option>
                </select>
            </div>

            <!-- Package Select -->
            <div style="display: flex; flex-direction: column;">
                <label style="font-weight: 600; color: #2c3e50; margin-bottom: 0.5rem; font-size: 0.9rem; display: flex; align-items: center; gap: 0.5rem;">
                    <i class="fas fa-cube" style="color: #A0C242; font-size: 14px;"></i>
                    {{ __('Select Packages') }}
                </label>
                <select name="package_id"
                        id="find_package"
                        style="border: 2px solid #e9ecef; border-radius: 8px; padding: 0.75rem 1rem; font-size: 0.9rem; transition: all 0.3s ease; background: white; width: 100%; appearance: none; background-image: url(\"data:image/svg+xml;charset=US-ASCII,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 4 5'><path fill='%236c757d' d='M2 0L0 2h4zm0 5L0 3h4z'/></svg>\"); background-repeat: no-repeat; background-position: right 1rem center; background-size: 12px; padding-right: 2.5rem;"
                        ui-jp="select2"
                        ui-options="{theme: 'bootstrap'}"
                        data-selected="{{ request('package_id') }}">
                    <option value="">{{ __('Select Packages') }}</option>
                </select>
            </div>

            <!-- From Date -->
            <div style="display: flex; flex-direction: column;">
                <label style="font-weight: 600; color: #2c3e50; margin-bottom: 0.5rem; font-size: 0.9rem; display: flex; align-items: center; gap: 0.5rem;">
                    <i class="fas fa-calendar-alt" style="color: #A0C242; font-size: 14px;"></i>
                    {{ __('From Date') }}
                </label>
                <div style="position: relative;">
                    <input type="text" name="from_date" id="from_date" 
                           style="border: 2px solid #e9ecef; border-radius: 8px; padding: 0.75rem 1rem; font-size: 0.9rem; transition: all 0.3s ease; background: white; width: 100%; padding-right: 2.5rem;"
                           placeholder="{{ __('From Date') }}" 
                           autocomplete="off" 
                           value="{{ request('from_date') }}"
                           onfocus="this.style.borderColor='#A0C242'; this.style.boxShadow='0 0 0 3px rgba(160, 194, 66, 0.1)';"
                           onblur="this.style.borderColor='#e9ecef'; this.style.boxShadow='none';">
                    <i class="fas fa-calendar" style="position: absolute; right: 1rem; top: 50%; transform: translateY(-50%); color: #6c757d; pointer-events: none;"></i>
                </div>
            </div>

            <!-- To Date -->
            <div style="display: flex; flex-direction: column;">
                <label style="font-weight: 600; color: #2c3e50; margin-bottom: 0.5rem; font-size: 0.9rem; display: flex; align-items: center; gap: 0.5rem;">
                    <i class="fas fa-calendar-check" style="color: #A0C242; font-size: 14px;"></i>
                    {{ __('To Date') }}
                </label>
                <div style="position: relative;">
                    <input type="text" name="to_date" id="to_date" 
                           style="border: 2px solid #e9ecef; border-radius: 8px; padding: 0.75rem 1rem; font-size: 0.9rem; transition: all 0.3s ease; background: white; width: 100%; padding-right: 2.5rem;"
                           placeholder="{{ __('To Date') }}" 
                           autocomplete="off" 
                           value="{{ request('to_date') }}"
                           onfocus="this.style.borderColor='#A0C242'; this.style.boxShadow='0 0 0 3px rgba(160, 194, 66, 0.1)';"
                           onblur="this.style.borderColor='#e9ecef'; this.style.boxShadow='none';">
                    <i class="fas fa-calendar" style="position: absolute; right: 1rem; top: 50%; transform: translateY(-50%); color: #6c757d; pointer-events: none;"></i>
                </div>
            </div>

            <!-- Order Status Select -->
            <div style="display: flex; flex-direction: column;">
                <label style="font-weight: 600; color: #2c3e50; margin-bottom: 0.5rem; font-size: 0.9rem; display: flex; align-items: center; gap: 0.5rem;">
                    <i class="fas fa-cube" style="color: #A0C242; font-size: 14px;"></i>
                    {{ __('Select Order Status') }}
                </label>
                <select name="order_status"
                        id="find_order_status"
                        style="border: 2px solid #e9ecef; border-radius: 8px; padding: 0.75rem 1rem; font-size: 0.9rem; transition: all 0.3s ease; background: white; width: 100%; appearance: none; background-image: url(\"data:image/svg+xml;charset=US-ASCII,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 4 5'><path fill='%236c757d' d='M2 0L0 2h4zm0 5L0 3h4z'/></svg>\"); background-repeat: no-repeat; background-position: right 1rem center; background-size: 12px; padding-right: 2.5rem;"
                        ui-jp="select2"
                        ui-options="{theme: 'bootstrap'}"
                        data-selected="{{ request('package_id') }}">
                    <option value="">{{ __('Select Order Status') }}</option>    
                    <option value="paid_order">{{ __('Paid Order') }}</option>
                    <option value="unpaid_order">{{ __('UnPaid Order') }}</option>
                </select>
            </div>

            <!-- Action Buttons -->
            <div style="display: flex; gap: 0.75rem; align-items: end; height: 100%;">
                <button id="search-btn" type="button" 
                        style="background: #A0C242; color: white; padding: 0.75rem 1.5rem; border: none; border-radius: 8px; font-weight: 600; font-size: 0.9rem; cursor: pointer; display: flex; align-items: center; gap: 0.5rem; height: 48px; white-space: nowrap;"
                        onmouseover="this.style.background='#8AAE38';"
                        onmouseout="this.style.background='#A0C242';">
                    <i class="fas fa-search"></i>
                    {{ __('Search') }}
                </button>
                <button id="reset-btn" type="button"
                        style="background: white; color: #6c757d; padding: 0.75rem 1.5rem; border: 2px solid #e9ecef; border-radius: 8px; font-weight: 600; font-size: 0.9rem; cursor: pointer; display: flex; align-items: center; gap: 0.5rem; height: 48px; white-space: nowrap;"
                        onmouseover="this.style.borderColor='#A0C242'; this.style.color='#A0C242';"
                        onmouseout="this.style.borderColor='#e9ecef'; this.style.color='#6c757d';">
                    <i class="fas fa-refresh"></i>
                    {{ __('Reset') }}
                </button>
            </div>
        </div>
        {{Form::close()}}
    </div>
</div>

@push("after-scripts")
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>
<script>
    $(document).ready(function () {
        $('#find_type').on('change', function () {
            let selectedType = $(this).val();
            let $packageDropdown = $('#find_package');
            let previousSelectedPackage = $packageDropdown.data('selected') || '';
            $packageDropdown.html('<option value="">{{ __("Loading...") }}</option>').prop('disabled', true);

            if (selectedType !== '') {
                $.ajax({
                    url: '{{ route("getPackagesByType") }}',
                    type: 'GET',
                    data: {
                        type: selectedType
                    },
                    success: function (response) {
                        let options = '<option value="">{{ __("Select Packages") }}</option>';
                        if (response.packages && response.packages.length > 0) {
                            response.packages.forEach(function (item) {
                                let isSelected = (item.id == previousSelectedPackage) ? 'selected' : '';
                                options += `<option value="${item.id}" ${isSelected}>${item.name}</option>`;
                            });
                        }
                        $packageDropdown.html(options).prop('disabled', false);
                    },
                    error: function () {
                        $packageDropdown.html('<option value="">{{ __("Failed to load packages") }}</option>');
                    }
                });
            } else {
                $packageDropdown.html('<option value="">{{ __("Select Packages") }}</option>').prop('disabled', false);
            }
        });
        let initialSelected = '{{ request("package_id") }}';
        if (initialSelected) {
            $('#find_package').data('selected', initialSelected);
        }

        $('#reset-btn').on('click', function() {
            $('#find_q').val('');
            $('#find_type').val('').trigger('change');
            $('#find_package').html('<option value="">{{ __("Select Packages") }}</option>').prop('disabled', false);
            $('#from_date').val('');
            $('#to_date').val('');
            $('#find_order_status').val('').trigger('change');
            $('#filter_form').submit();
        });

        // Search button handler
        $('#search-btn').on('click', function() {
            $('#filter_form').submit();
        });

        // Enter key support in search field
        $('#find_q').on('keypress', function(e) {
            if (e.which === 13) {
                $('#filter_form').submit();
            }
        });
    });

    $(function () {
        $('.datepicker').datepicker({
            format: 'yyyy-mm-dd',
            autoclose: true,
            todayHighlight: true,
            clearBtn: true
        });

        $('#from_date').datepicker({
            format: 'yyyy-mm-dd',
            autoclose: true,
            todayHighlight: true
        }).on('changeDate', function (e) {
            let startDate = e.date;
            $('#to_date').datepicker('setStartDate', startDate);

            let currentToDate = $('#to_date').datepicker('getDate');
            if (currentToDate && currentToDate < startDate) {
                $('#to_date').datepicker('clearDates');
            }
        });

        $('#to_date').datepicker({
            format: 'yyyy-mm-dd',
            autoclose: true,
            todayHighlight: true
        });
    });
</script>
@endpush