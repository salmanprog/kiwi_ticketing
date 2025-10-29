@extends('dashboard.layouts.master')
@section('title', __('Payment Success Logs'))
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
}     .box-header.dker {
            background: linear-gradient(135deg, #A0C242 0%, #8AAE38 100%) !important;
            color: white;
             border-radius: 5px 5px 0 0;
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
        <h3>{{ __('Payment Success Logs') }}</h3>
        <small>
            <a href="{{ route('adminHome') }}">{{ __('backend.home') }}</a> /
            <a href="">{{ __('payment-success-logs') }}</a>
        </small>
    </div>
    <div class="box-tool box-tool-lg">
    </div>
        <div>
            <div class="table-responsive">
                <table class="table table-bordered m-a-0" id="logs">
                    <thead class="dker">
                    <tr>
                        <th class="width20 dker">
                            <label class="ui-check m-a-0">
                                <input id="checkAll" type="checkbox"><i></i>
                            </label>
                        </th>
                        <th>{{ __('ID') }}</th>
                        <th>{{ __('Type') }}</th>
                        <th>{{ __('Order Number') }}</th>
                        <th>{{ __('End Point') }}</th>
                        <th>{{ __('Error') }}</th>
                        <th>{{ __('Status') }}</th>
                        <th>{{ __('CreatedAt') }}</th>
                        <th class="text-center" style="width:200px;">{{ __('backend.options') }}</th>
                    </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        <!-- <footer class="dker p-a">
                    <div class="row">
                        <div class="col-sm-3 hidden-xs"> -->
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
            var dataTable = $("#logs").DataTable({
                processing: true,
                serverSide: true,
                searching: true,
                ajax: {
                    url: "{{ route('ordersLogs.data') }}",
                    type: "POST",
                    data: function (data) {
                        data._token = "{{ csrf_token() }}";
                        data.type = "payment";
                        data.status = "success";
                        data.find_q = $('#find_q').val();
                    }
                },
               dom: '<"row"<"col-sm-6"f><"col-sm-6"l>>rtip',
                columns: [
                    { data: 'check', orderable: false, searchable: false },
                    { data: 'id' },
                    { data: 'type' },
                    { data: 'order_number' },
                    { data: 'endpoint' },
                    { data: 'message' },
                    { data: 'status', orderable: false, searchable: false },
                    { data: 'created_at', orderable: false, searchable: false },
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

           
        });
    </script>

@endpush
