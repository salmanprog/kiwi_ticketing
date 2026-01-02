@extends('dashboard.layouts.master')
@section('title', __('Email Logs'))
@section('content')
<style>
    .email-logs-container {
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
        display: none;
    }

    .breadcrumb-modern a {
        color: rgba(255,255,255,0.9);
        text-decoration: none;
    }

    .breadcrumb-modern a:hover {
        color: white;
        text-decoration: underline;
    }

    .table-responsive {
        /* overflow: visible !important; */
    }

    .table-modern {
        margin: 0;
        border: none;
        width: 100%;
    }

    .table-modern thead th {
        background: #f8f9fa;
        border-bottom: 2px solid #dee2e6;
        font-weight: 600;
        color: #95b83d;
        padding: 1rem 1.2rem;
        vertical-align: middle;
    }

    .table-modern tbody td {
        padding: 1rem 1.2rem;
        vertical-align: middle;
        border-color: #f1f3f4;
    }

    .table-modern tbody tr:hover {
        background: rgba(160, 194, 66, 0.05);
    }

    /* Skeleton Loader Styles */
    .skeleton-loader {
        display: none;
    }

    .skeleton-row {
        display: flex;
        align-items: center;
        padding: 1rem 1.2rem;
        border-bottom: 1px solid #f1f3f4;
    }

    .skeleton-checkbox {
        width: 18px;
        height: 18px;
        background: #e9ecef;
        border-radius: 4px;
        margin-right: 1rem;
    }

    .skeleton-text {
        height: 12px;
        background: #e9ecef;
        border-radius: 6px;
        margin: 0.5rem 0;
        animation: pulse 1.5s ease-in-out infinite;
    }

    .skeleton-short {
        width: 60px;
    }

    .skeleton-medium {
        width: 120px;
    }

    .skeleton-long {
        width: 200px;
    }

    .skeleton-status {
        width: 80px;
        height: 24px;
        background: #e9ecef;
        border-radius: 12px;
    }

    .skeleton-actions {
        display: flex;
        gap: 8px;
    }

    .skeleton-action {
        width: 32px;
        height: 32px;
        background: #e9ecef;
        border-radius: 6px;
    }

    @keyframes pulse {
        0% {
            opacity: 1;
        }
        50% {
            opacity: 0.5;
        }
        100% {
            opacity: 1;
        }
    }

    /* Original checkbox styles */
    .width20.dker {
        width: 20px;
    }

    .ui-check.m-a-0 {
        margin: 0 !important;
    }

    /* Table footer */
    .dker.p-a {
        background: #f8f9fa;
        padding: 1rem;
        border-top: 1px solid #dee2e6;
    }

    /* Modal styles */
    .modal-content {
        border-radius: 8px;
        border: none;
        box-shadow: 0 10px 30px rgba(0,0,0,0.2);
    }

    .modal-header {
        background: #f8f9fa;
        border-bottom: 1px solid #dee2e6;
        border-radius: 8px 8px 0 0;
    }

    .modal-footer {
        border-top: 1px solid #dee2e6;
        border-radius: 0 0 8px 8px;
    }

    .btn.dark-white {
        background: #6c757d;
        color: white;
        border: none;
    }

    .btn.danger {
        background: #dc3545;
        color: white;
        border: none;
    }

    .btn.white {
        background: white;
        color: #2c3e50;
        border: 1px solid #dee2e6;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .table-modern {
            font-size: 0.9rem;
        }
        
        .hidden-xs {
            display: none !important;
        }
        
        .col-sm-3, .col-sm-6 {
            width: 100%;
            text-align: center;
            margin-bottom: 1rem;
        }
        
        /* Allow horizontal scroll only on mobile */
        .table-responsive {
            /* overflow-x: auto !important; */
        }
        
        .table-modern {
            min-width: 800px;
        }
    }
</style>

<div class="padding">
    <div class="email-logs-container">
        <!-- Header Section -->
        <div class="modern-header">
            <h3><i class="fas fa-envelope-open-text"></i> {{ __('Email Logs') }}</h3>
            <nav class="breadcrumb-modern">
                <a href="{{ route('adminHome') }}">{{ __('backend.home') }}</a> /
                <a href="">{{ __('email-logs') }}</a>
            </nav>
        </div>

        <!-- Table Section -->
        <div class="table-responsive">
            <table class="table table-bordered m-a-0 table-modern" id="email_logs">
                <thead class="dker">
                    <tr>
                        <th class="width20 dker">
                            <label class="ui-check m-a-0">
                                <input id="checkAll" type="checkbox"><i></i>
                            </label>
                        </th>
                        <th>{{ __('ID') }}</th>
                        <th>{{ __('Order Number') }}</th>
                        <th>{{ __('Email To') }}</th>
                        <th>{{ __('Identifier') }}</th>
                        <th>{{ __('Subject') }}</th>
                        <th>{{ __('Status') }}</th>
                        <th class="text-center" style="width:200px;">{{ __('backend.options') }}</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Data will be populated by DataTables -->
                </tbody>
            </table>
        </div>

        <!-- Skeleton Loader -->
        <div class="skeleton-loader" id="skeletonLoader">
            <div class="skeleton-row">
                <div class="skeleton-checkbox"></div>
                <div style="flex: 1;">
                    <div class="skeleton-text skeleton-short"></div>
                </div>
                <div style="flex: 1;">
                    <div class="skeleton-text skeleton-medium"></div>
                </div>
                <div style="flex: 1.5;">
                    <div class="skeleton-text skeleton-long"></div>
                </div>
                <div style="flex: 1;">
                    <div class="skeleton-text skeleton-medium"></div>
                </div>
                <div style="flex: 2;">
                    <div class="skeleton-text skeleton-long"></div>
                </div>
                <div style="flex: 1;">
                    <div class="skeleton-status"></div>
                </div>
                <div style="flex: 1;">
                    <div class="skeleton-actions">
                        <div class="skeleton-action"></div>
                        <div class="skeleton-action"></div>
                        <div class="skeleton-action"></div>
                    </div>
                </div>
            </div>
            <!-- Repeat skeleton rows 5 times -->
            <div class="skeleton-row">
                <div class="skeleton-checkbox"></div>
                <div style="flex: 1;"><div class="skeleton-text skeleton-short"></div></div>
                <div style="flex: 1;"><div class="skeleton-text skeleton-medium"></div></div>
                <div style="flex: 1.5;"><div class="skeleton-text skeleton-long"></div></div>
                <div style="flex: 1;"><div class="skeleton-text skeleton-medium"></div></div>
                <div style="flex: 2;"><div class="skeleton-text skeleton-long"></div></div>
                <div style="flex: 1;"><div class="skeleton-status"></div></div>
                <div style="flex: 1;"><div class="skeleton-actions"><div class="skeleton-action"></div><div class="skeleton-action"></div><div class="skeleton-action"></div></div></div>
            </div>
            <div class="skeleton-row">
                <div class="skeleton-checkbox"></div>
                <div style="flex: 1;"><div class="skeleton-text skeleton-short"></div></div>
                <div style="flex: 1;"><div class="skeleton-text skeleton-medium"></div></div>
                <div style="flex: 1.5;"><div class="skeleton-text skeleton-long"></div></div>
                <div style="flex: 1;"><div class="skeleton-text skeleton-medium"></div></div>
                <div style="flex: 2;"><div class="skeleton-text skeleton-long"></div></div>
                <div style="flex: 1;"><div class="skeleton-status"></div></div>
                <div style="flex: 1;"><div class="skeleton-actions"><div class="skeleton-action"></div><div class="skeleton-action"></div><div class="skeleton-action"></div></div></div>
            </div>
            <div class="skeleton-row">
                <div class="skeleton-checkbox"></div>
                <div style="flex: 1;"><div class="skeleton-text skeleton-short"></div></div>
                <div style="flex: 1;"><div class="skeleton-text skeleton-medium"></div></div>
                <div style="flex: 1.5;"><div class="skeleton-text skeleton-long"></div></div>
                <div style="flex: 1;"><div class="skeleton-text skeleton-medium"></div></div>
                <div style="flex: 2;"><div class="skeleton-text skeleton-long"></div></div>
                <div style="flex: 1;"><div class="skeleton-status"></div></div>
                <div style="flex: 1;"><div class="skeleton-actions"><div class="skeleton-action"></div><div class="skeleton-action"></div><div class="skeleton-action"></div></div></div>
            </div>
            <div class="skeleton-row">
                <div class="skeleton-checkbox"></div>
                <div style="flex: 1;"><div class="skeleton-text skeleton-short"></div></div>
                <div style="flex: 1;"><div class="skeleton-text skeleton-medium"></div></div>
                <div style="flex: 1.5;"><div class="skeleton-text skeleton-long"></div></div>
                <div style="flex: 1;"><div class="skeleton-text skeleton-medium"></div></div>
                <div style="flex: 2;"><div class="skeleton-text skeleton-long"></div></div>
                <div style="flex: 1;"><div class="skeleton-status"></div></div>
                <div style="flex: 1;"><div class="skeleton-actions"><div class="skeleton-action"></div><div class="skeleton-action"></div><div class="skeleton-action"></div></div></div>
            </div>
        </div>

        <!-- Footer Section (Original Structure Preserved) -->
        <footer class="dker p-a">
            <div class="row">
                <div class="col-sm-3 hidden-xs">
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
                    <!-- @if(@Auth::user()->permissionsGroup->settings_status)
                        <select name="action" id="action" class="form-control c-select w-sm inline v-middle"
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
                        </button>
                    @endif -->
                </div>
                <!-- <div class="col-sm-3 text-center">
                    <small class="text-muted inline m-t-sm m-b-sm" id="table-info">
                        {{ __('backend.showing') }} 0 - 0 {{ __('backend.of') }} <strong>0</strong> {{ __('backend.records') }}
                    </small>
                </div>
                <div class="col-sm-6 text-right text-center-xs">
                    <div id="pagination-container">
                        
                    </div>
                </div> -->
            </div>
        </footer>
    </div>
</div>

@endsection

@push("after-scripts")
<script src="{{ asset('assets/dashboard/js/datatables/datatables.min.js') }}"></script>
<script type="text/javascript">
    $(document).ready(function () {
        // Show skeleton loader when table is processing
        function showSkeletonLoader() {
            $('#skeletonLoader').show();
            $('#email_logs tbody').hide();
        }

        function hideSkeletonLoader() {
            $('#skeletonLoader').hide();
            $('#email_logs tbody').show();
        }

        // Original checkbox functionality
        $("#checkAll").click(function () {
            $('input:checkbox').not(this).prop('checked', this.checked);
        });

        // Original action functionality
        $("#action").change(function () {
            if (this.value == "delete") {
                $("#submit_all").css("display", "none");
                $("#submit_show_msg").css("display", "inline-block");
            } else {
                $("#submit_all").css("display", "inline-block");
                $("#submit_show_msg").css("display", "none");
            }
        });

        // Initialize DataTable with original configuration
        var dataTable = $("#email_logs").DataTable({
            processing: true,
            serverSide: true,
            searching: true,
            ajax: {
                url: "{{ route('emailsLogs.data') }}",
                type: "POST",
                data: function (data) {
                    data._token = "{{ csrf_token() }}";
                    data.find_q = $('#find_q').val();
                },
                beforeSend: function() {
                    showSkeletonLoader();
                },
                complete: function() {
                    hideSkeletonLoader();
                }
            },
            dom: '<"row"<"col-sm-6"f><"col-sm-6"l>>rtip',
            columns: [
                { 
                    data: 'check', 
                    orderable: false, 
                    searchable: false,
                    render: function(data, type, row) {
                        return data; // Return original checkbox HTML
                    }
                },
                { data: 'id' },
                { data: 'order_number' },
                { data: 'email' },
                { data: 'identifier' },
                { data: 'Subject' },
                { 
                    data: 'status', 
                    orderable: false, 
                    searchable: false,
                    render: function(data, type, row) {
                        // Return EXACT original status HTML - no changes
                        return data;
                    }
                },
                { 
                    data: 'options', 
                    orderable: false, 
                    searchable: false,
                    className: 'text-center',
                    render: function(data, type, row) {
                        return data; // Return original options HTML
                    }
                }
            ],
            order: [[1, 'desc']],
            language: {
                ...{!! json_encode(__('backend.dataTablesTranslation')) !!},
                processing: `<div class="col-sm-12 col-md-12">
                    <img src="{{ asset('assets/dashboard/images/loading.gif') }}" style="height: 25px;" alt="Loading...">
                    <div>{!! __('backend.loading') !!}</div>
                </div>`
            },
            drawCallback: function(settings) {
                // Update pagination info
                var info = this.api().page.info();
                $('#table-info').html(
                    '{{ __('backend.showing') }} ' + (info.start + 1) + ' - ' + info.end + 
                    ' {{ __('backend.of') }} <strong>' + info.recordsTotal + '</strong> {{ __('backend.records') }}'
                );

                // Move pagination to our container
                $('.dataTables_paginate').appendTo('#pagination-container');
            },
            initComplete: function() {
                hideSkeletonLoader();
            }
        });

        // Original scroll functionality
        dataTable.on('page.dt', function () {
            $('html, body').animate({
                scrollTop: $(".dataTables_wrapper").offset().top
            }, 'slow');
        });

        // Original error handling
        $.fn.dataTable.ext.errMode = 'none';

        // Show skeleton on initial load
        showSkeletonLoader();
    });
</script>
@endpush