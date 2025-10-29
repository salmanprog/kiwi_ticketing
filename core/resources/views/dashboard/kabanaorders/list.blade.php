@extends('dashboard.layouts.master')
@section('title', __('Cabana Orders'))
@section('content')
<style>
    div.dataTables_wrapper div.dataTables_processing {
        position: absolute;
        top: 50%;
        left: 50%;
        width: 200px;
        margin-left: -100px;
        margin-top: -26px;
        text-align: center;
        padding: 1em 0;
    }
     .box-header.dker {
            background: linear-gradient(135deg, #A0C242 0%, #8AAE38 100%) !important;
            color: white;
             border-radius: 5px 5px 0 0;
             font-size: 18px;
        }
        .box-header.dker h3 {
            font-size: 22px;
            font-weight: 700;
        }
        .box-tool {
            color: white;
        }
        .primary
          {
            background: #A0C242 !important;
            border-color: #A0C242 !important;
        }
        
        .info
          {
            background: #A0C242 !important;
            border-color: #A0C242 !important;
        }
        .success
          {
            background: #A0C242 !important;
            border-color: #A0C242 !important;
        }
        .btn-primary:hover {
            background: #8AAE38 !important;
            border-color: #8AAE38 !important;
        }
    </style>
<div class="padding">
<div class="box">
    <div class="box-header dker">
        <h3>{{ __('Cabana') }}</h3>
        <small>
            <a href="{{ route('adminHome') }}">{{ __('backend.home') }}</a> /
            <a href="">{{ __('Orders') }}</a>
        </small>
    </div>
    <div class="box-tool box-tool-lg">
        <ul class="nav">
            <li class="nav-item inline">
                <a class="btn primary" href="Javascript:void(0);">
                    <i class="material-icons">&#xe02e;</i>
                    <strong>Total Earnings:</strong> <span id="total-earnings">$0.00</span>
                </a>
            </li>
            <li class="nav-item inline">
                <button type="button" class="btn info" id="filter_btn" title="{{ __('Advance Search') }}"
                        data-toggle="tooltip">
                    <i class="fa fa-search"></i>
                </button>
            </li>
            <li class="nav-item inline">
                <button type="button" class="btn success" id="excel_btn" title="{{ __('backend.export') }}"
                        data-toggle="tooltip"
                        onclick="print_as('excel')">
                    <i class="fa fa-file-excel-o"></i>
                </button>
            </li>
        </ul>
    </div>
    <div class="box-tool box-tool-lg">
    </div>
        <div>
            <div class="table-responsive">
                 @include("dashboard.kabanaorders.search")
                <table class="table table-bordered m-a-0" id="cabana_orders">
                    <thead class="dker">
                    <tr>
                        <th class="width20 dker">
                            <label class="ui-check m-a-0">
                                <input id="checkAll" type="checkbox"><i></i>
                            </label>
                        </th>
                        <th>{{ __('ID') }}</th>
                        <th>{{ __('Package') }}</th>
                        <th>{{ __('Customer Name') }}</th>
                        <th>{{ __('Customer Email') }}</th>
                        <th>{{ __('Total') }}</th>
                        <th>{{ __('Order Status') }}</th>
                        <th>{{ __('Status') }}</th>
                        <th>{{ __('OrderDate') }}</th>
                        <th>{{ __('CreatedAt') }}</th>
                        <th class="text-center" style="width:200px;">{{ __('backend.options') }}</th>
                    </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        <!-- <footer class="dker p-a"> -->
                    <!-- <div class="row"> -->
                        <!-- <div class="col-sm-3 hidden-xs"> -->
                            <!-- .modal -->
                            <div id="m-all" class="modal fade" data-backdrop="true">
                                <div class="modal-dialog" id="animate">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">{{ __('backend.confirmation') }}</h5>
                                        </div>
                                        <div class="modal-body text-center p-lg">
                                            <p>
                                                {{ __('backend.confirmationDeleteMsg') }}
                                            </p>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn dark-white p-x-md"
                                                    data-dismiss="modal">{{ __('backend.no') }}</button>
                                            <button type="submit"
                                                    class="btn danger p-x-md">{{ __('backend.yes') }}</button>
                                        </div>
                                    </div><!-- /.modal-content -->
                                </div>
                            </div>
                            <!-- / .modal -->
                            <!-- @if(@Auth::user()->permissionsGroup->settings_status) -->
                                <!-- <select name="action" id="action" class="form-control c-select w-sm inline v-middle"
                                        required>
                                    <option value="">{{ __('backend.bulkAction') }}</option>
                                    <option value="activate">{{ __('backend.activeSelected') }}</option>
                                    <option value="block">{{ __('backend.blockSelected') }}</option>
                                    <option value="delete">{{ __('backend.deleteSelected') }}</option>
                                </select>
                                <button type="submit" id="submit_all"
                                        class="btn white">{{ __('backend.apply') }}</button>
                                <button id="submit_show_msg" class="btn white" data-toggle="modal"
                                        style="display: none"
                                        data-target="#m-all" ui-toggle-class="bounce"
                                        ui-target="#animate">{{ __('backend.apply') }}
                                </button> -->
                            <!-- @endif -->
                        <!-- </div> -->
                            <!-- <div class="col-sm-3 text-center">
                                <small class="text-muted inline m-t-sm m-b-sm">
                                    {{ __('backend.showing') }} {{ $paginated->firstItem() }} - {{ $paginated->lastItem() }}
                                    {{ __('backend.of') }} <strong>{{ $paginated->total() }}</strong> {{ __('backend.records') }}
                                </small>
                            </div> -->
                            <!-- <div class="col-sm-6 text-right text-center-xs">
                                {!! $paginated->links() !!}
                            </div> -->
                       
                    <!-- </div> -->
                <!-- </footer> -->
    
</div>
</div>

@endsection
@push("after-scripts")
    <script src="{{ asset('assets/dashboard/js/datatables/datatables.min.js') }}"></script>
    <script type="text/javascript">
        $("#checkAll").click(function () {
            $('input:checkbox').not(this).prop('checked', this.checked);
        });
        $("#action").change(function () {
            if (this.value == "delete") {
                $("#submit_all").css("display", "none");
                $("#submit_show_msg").css("display", "inline-block");
            } else {
                $("#submit_all").css("display", "inline-block");
                $("#submit_show_msg").css("display", "none");
            }
        });
        $(document).ready(function () {
            var dataTable = $("#cabana_orders").DataTable({
                processing: true,
                serverSide: true,
                searching: true,
                ajax: {
                    url: "{{ route('cabanaorders.data') }}",
                    type: "POST",
                    data: function (data) {
                        data._token = "{{ csrf_token() }}";
                        data.find_q = $('#find_q').val();
                        data.type = $('#find_type').val();
                        data.package_id = $('#find_package').val();
                        data.from_date = $('#from_date').val();
                        data.to_date = $('#to_date').val();
                        data.type = 'cabana';
                        data.route = 'kabanaordersdetail';
                    }
                },
               dom: '<"row"<"col-sm-6"f><"col-sm-6"l>>rtip',
                columns: [
                    { data: 'check', orderable: false, searchable: false },
                    { data: 'id' },
                    { data: 'package' },
                    { data: 'customerName' },
                    { data: 'customerEmail' },
                    { data: 'orderTotal' },
                    { data: 'order_status' },
                    { data: 'status' },
                    { data: 'orderDate' },
                    { data: 'createdAt' },
                    { data: 'options', orderable: false, searchable: false }
                ],
                order: [[1, 'desc']],
                language: $.extend(
                    {!! json_encode(__('backend.dataTablesTranslation')) !!},
                    {
                        processing: `<div class="col-sm-12 col-md-12">
                            <img src="{{ asset('assets/dashboard/images/loading.gif') }}" style="height: 25px;" alt="Loading...">
                            <div>{!! __('backend.loading') !!}</div>
                        </div>`
                    }
                )
            });

            dataTable.on('page.dt', function () {
                $('html, body').animate({
                    scrollTop: $(".dataTables_wrapper").offset().top
                }, 'slow');
            });
            $.fn.dataTable.ext.errMode = 'none';

            $('#cabana_orders').on('xhr.dt', function (e, settings, json, xhr) {
                if (json.totalEarnings !== undefined) {
                    $('#total-earnings').text(json.totalEarnings);
                }
            });
            $('#search-btn').on('click', function () {
                $("#search_submit_stat").val(""); 
                $("#filter_form").submit();
            });
            $('#filter_form').submit(function () {
                if ($("#search_submit_stat").val() === "") {
                    dataTable.draw();
                    return false;
                }
            });
            $("#filter_btn").on('click', function () {
                $("#filter_div").slideToggle();
            });
           
        });
        function print_as(stat) {
            $("#search_submit_stat").val(stat);
            $("#filter_form").attr('action', '{{ route("cabanaPrint") }}');
            $("#filter_form").attr('target', '_blank');
            $("#filter_form").submit();
            $("#filter_form").attr('action', '{{ route("cabanaPrint") }}');
            $("#search_submit_stat").val("");
            $("#filter_form").attr('target', '');
        }
    </script>

@endpush
