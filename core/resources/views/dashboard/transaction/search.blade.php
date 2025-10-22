@push('after-styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css" />
@endpush
<div class="dker b-b displayNone" id="filter_div">
    <div class="p-a">
        {{Form::open(['route'=>['transactionorders'],'method'=>'GET','id'=>'filter_form','target'=>''])}}
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
                        <option value="cabana">{{ __('Cabana Orders') }}</option>
                        <option value="birthday">{{ __('Packages Orders') }}</option>
                        <option value="general_ticket">{{ __('Platform Orders') }}</option>
                        <option value="season_pass">{{ __('LandingPage Orders') }}</option>
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
                <div class="col-md-2 col-xs-6 m-b-5p">
                    <select name="order_status"
                            id="find_order_status"
                            class="form-control select2"
                            ui-jp="select2"
                            ui-options="{theme: 'bootstrap'}">
                        <option value="">{{ __('Select Order Status') }}</option>
                        <option value="update_order">{{ __('Update Order') }}</option>
                        <option value="upgrade_order">{{ __('Upgrade Orders') }}</option>
                    </select>
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
            $('#find_order_status').val('');
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
@endpush