@extends('dashboard.layouts.master')
@section('title', __('Discount'))
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

        .primary {
            background: #A0C242 !important;
            border-color: #A0C242 !important;
        }

        .info {
            background: #A0C242 !important;
            border-color: #A0C242 !important;
        }

        .success {
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
                <h3>{{ __('Discount') }}</h3>
                <small>
                    <a href="{{ route('adminHome') }}">{{ __('backend.home') }}</a> /
                    <a href="">{{ __('discount') }}</a>
                </small>
            </div>


            <div class="row p-a pull-right" style="margin-top: -70px;">
                <div class="col-sm-12">
                    <a class="btn btn-fw primary" href="{{ route('couponCreate') }}">
                        <i class="material-icons">&#xe7fe;</i>
                        &nbsp; {{ __('Add New Discount') }}
                    </a>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-bordered m-a-0" id="coupon_packages">
                    <thead class="dker">
                        <tr>
                            <th>{{ __('ID') }}</th>
                            <th>{{ __('Title') }}</th>
                            <th>{{ __('Slug') }}</th>
                            <th>{{ __('Type') }}</th>
                            <th>{{ __('Coupon Code') }}</th>
                            <th>{{ __('Status') }}</th>
                            <th>{{ __('createdBy') }}</th>
                            <th>{{ __('updatedBy') }}</th>
                            <th>{{ __('updatedAt') }}</th>
                            <th class="text-center" style="width:200px;">{{ __('backend.options') }}</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>


            {{-- <footer class="dker p-a">
                <div class="row">
                    <div class="col-sm-3 hidden-xs">

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
                                        <button type="submit" class="btn danger p-x-md">{{ __('backend.yes') }}</button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        @if (@Auth::user()->permissionsGroup->settings_status)
                           <select name="action" id="action" class="" required>
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
                            </button>
                        @endif
                    </div>


                    <div class="col-sm-3 text-center">
                            <small class="text-muted inline m-t-sm m-b-sm">
                                {{ __('backend.showing') }} {{ $paginated->firstItem() }} - {{ $paginated->lastItem() }}
                                {{ __('backend.of') }} <strong>{{ $paginated->total() }}</strong> {{ __('backend.records') }}
                            </small>
                            </div>
                     <div class="col-sm-6 text-right text-center-xs">
                        {!! $paginated->links() !!}
                    </div>
                </div>
            </footer> --}}
        </div>
    </div>
@endsection


@push('after-scripts')
    <script src="{{ asset('assets/dashboard/js/datatables/datatables.min.js') }}"></script>
    <script type="text/javascript">
        $("#checkAll").click(function() {
            $('input:checkbox').not(this).prop('checked', this.checked);
        });

        $("#action").change(function() {
            if (this.value == "delete") {
                $("#submit_all").css("display", "none");
                $("#submit_show_msg").css("display", "inline-block");
            } else {
                $("#submit_all").css("display", "inline-block");
                $("#submit_show_msg").css("display", "none");
            }
        });

        $(document).ready(function() {
            var dataTable = $("#coupon_packages").DataTable({
                processing: true,
                serverSide: true,
                searching: true,
                ajax: {
                    url: "{{ route('coupon.data') }}",
                    type: "POST",
                    data: function(data) {
                        data._token = "{{ csrf_token() }}";
                        data.find_q = $('#find_q').val();
                    }
                },
                dom: '<"row"<"col-sm-6"f><"col-sm-6"l>>rtip',
                columns: [
                    {
                        data: 'id'
                    },
                    {
                        data: 'title'
                    },
                    {
                        data: 'slug'
                    },
                    {
                        data: 'package_type'
                    },
                    {
                        data: 'coupon_code'
                    },
                    {
                        data: 'status',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'created_by',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'updated_by',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'updated_at',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'options',
                        orderable: false,
                        searchable: false
                    }
                ],
                order: [
                    [1, 'desc']
                ],
                language: $.extend(
                    {!! json_encode(__('backend.dataTablesTranslation')) !!}, {
                        processing: `<div class="col-sm-12 col-md-12">
                            <img src="{{ asset('assets/dashboard/images/loading.gif') }}" style="height: 25px;" alt="Loading...">


                            <div>{!! __('backend.loading') !!}</div>
                        </div>`
                    }
                ),
                initComplete: function () {
                    // Add placeholder to search input
                    var $searchInput = $('#coupon_packages_filter input');
                    $searchInput.attr('placeholder', 'Search by title');
                }
            });

            dataTable.on('page.dt', function() {
                $('html, body').animate({
                    scrollTop: $(".dataTables_wrapper").offset().top
                }, 'slow');
            });
            $.fn.dataTable.ext.errMode = 'none';
        });
    </script>
@endpush
