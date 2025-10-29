@extends('dashboard.layouts.master')
@section('title', __('Coupons'))
@section('content')
<style>
    .coupons-container {
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
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 1rem;
    }

    .header-content {
        flex: 1;
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

    .header-action {
        flex-shrink: 0;
    }

    .btn-simple-primary {
        background: #A0C242;
        border: none;
        border-radius: 6px;
        padding: 0.75rem 1.5rem;
        color: white;
        font-weight: 500;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        cursor: pointer;
        text-decoration: none;
        white-space: nowrap;
    }

    .btn-simple-primary:hover {
        background: #8AAE38;
        color: white;
    }

    .table-responsive {
        overflow: visible !important;
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
        color: #2c3e50;
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

    /* Action Buttons */
    .action-buttons {
        display: flex;
        gap: 8px;
        justify-content: center;
    }

    .btn-simple-action {
        width: 32px;
        height: 32px;
        border: none;
        border-radius: 4px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.3s ease;
        text-decoration: none;
        font-size: 14px;
    }

    .btn-edit {
        background: #17a2b8;
        color: white;
    }

    .btn-edit:hover {
        background: #138496;
    }

    .btn-delete {
        background: #dc3545;
        color: white;
    }

    .btn-delete:hover {
        background: #c82333;
    }

    .btn-view {
        background: #28a745;
        color: white;
    }

    .btn-view:hover {
        background: #218838;
    }

    /* Skeleton Loader */
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
        border-radius: 4px;
    }

    .skeleton-actions {
        display: flex;
        gap: 8px;
    }

    .skeleton-action {
        width: 32px;
        height: 32px;
        background: #e9ecef;
        border-radius: 4px;
    }

    @keyframes pulse {
        0% { opacity: 1; }
        50% { opacity: 0.5; }
        100% { opacity: 1; }
    }

    /* Table Footer */
    .table-footer {
        background: #f8f9fa;
        padding: 1rem 2rem;
        border-top: 1px solid #dee2e6;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 1rem;
    }

    .bulk-actions {
        display: flex;
        align-items: center;
        gap: 1rem;
        flex-wrap: wrap;
    }

    .select-simple {
        border: 1px solid #ced4da;
        border-radius: 4px;
        padding: 0.5rem 1rem;
        background: white;
        color: #2c3e50;
        min-width: 150px;
    }

    .btn-simple-secondary {
        background: white;
        border: 1px solid #ced4da;
        border-radius: 4px;
        padding: 0.5rem 1.5rem;
        color: #2c3e50;
        font-weight: 500;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        cursor: pointer;
    }

    .btn-simple-secondary:hover {
        background: #f8f9fa;
    }

    .table-info {
        color: #6c757d;
        font-size: 0.9rem;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .modern-header {
            flex-direction: column;
            align-items: flex-start;
            gap: 1rem;
        }
        
        .header-action {
            align-self: stretch;
        }
        
        .btn-simple-primary {
            width: 100%;
            justify-content: center;
        }
        
        .table-modern {
            font-size: 0.9rem;
        }
        
        .action-buttons {
            flex-direction: column;
            gap: 4px;
        }
        
        .btn-simple-action {
            width: 28px;
            height: 28px;
            font-size: 12px;
        }
        
        .table-footer {
            flex-direction: column;
            align-items: stretch;
        }
        
        .bulk-actions {
            justify-content: center;
        }
        
        .hidden-xs {
            display: none !important;
        }
    }
</style>

<div class="padding">
    <div class="coupons-container">
        <!-- Header Section with Add Button -->
        <div class="modern-header">
            <div class="header-content">
                <h3><i class="fas fa-tags"></i> {{ __('Coupons') }}</h3>
                <nav class="breadcrumb-modern">
                    <a href="{{ route('adminHome') }}">{{ __('backend.home') }}</a> /
                    <a href="">{{ __('coupons') }}</a>
                </nav>
            </div>
            <div class="header-action">
                <a class="btn-simple-primary" href="{{route('couponCreate')}}">
                    <i class="fas fa-plus"></i>
                    {{ __('Add New Coupon') }}
                </a>
            </div>
        </div>

        <!-- Table Section -->
        <div class="table-responsive">
            <table class="table table-bordered m-a-0 table-modern" id="coupon_packages">
                <thead class="dker">
                    <tr>
                        <th class="width20 dker">
                            <label class="ui-check m-a-0">
                                <input id="checkAll" type="checkbox"><i></i>
                            </label>
                        </th>
                        <th>{{ __('ID') }}</th>
                        <th>{{ __('Title') }}</th>
                        <th>{{ __('Slug') }}</th>
                        <th>{{ __('Type') }}</th>
                        <th>{{ __('Coupon Code') }}</th>
                        <th>{{ __('Status') }}</th>
                        <th>{{ __('createdBy') }}</th>
                        <th>{{ __('updatedBy') }}</th>
                        <th>{{ __('updatedAt') }}</th>
                        <th class="text-center">{{ __('backend.options') }}</th>
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
                <div style="flex: 0.5;"><div class="skeleton-text skeleton-short"></div></div>
                <div style="flex: 1.5;"><div class="skeleton-text skeleton-long"></div></div>
                <div style="flex: 1.2;"><div class="skeleton-text skeleton-medium"></div></div>
                <div style="flex: 1;"><div class="skeleton-text skeleton-medium"></div></div>
                <div style="flex: 1.2;"><div class="skeleton-text skeleton-medium"></div></div>
                <div style="flex: 0.8;"><div class="skeleton-status"></div></div>
                <div style="flex: 1;"><div class="skeleton-text skeleton-medium"></div></div>
                <div style="flex: 1;"><div class="skeleton-text skeleton-medium"></div></div>
                <div style="flex: 1.2;"><div class="skeleton-text skeleton-medium"></div></div>
                <div style="flex: 1.2;">
                    <div class="skeleton-actions">
                        <div class="skeleton-action"></div>
                        <div class="skeleton-action"></div>
                        <div class="skeleton-action"></div>
                    </div>
                </div>
            </div>
            <!-- Repeat skeleton rows -->
            <div class="skeleton-row">
                <div class="skeleton-checkbox"></div>
                <div style="flex: 0.5;"><div class="skeleton-text skeleton-short"></div></div>
                <div style="flex: 1.5;"><div class="skeleton-text skeleton-long"></div></div>
                <div style="flex: 1.2;"><div class="skeleton-text skeleton-medium"></div></div>
                <div style="flex: 1;"><div class="skeleton-text skeleton-medium"></div></div>
                <div style="flex: 1.2;"><div class="skeleton-text skeleton-medium"></div></div>
                <div style="flex: 0.8;"><div class="skeleton-status"></div></div>
                <div style="flex: 1;"><div class="skeleton-text skeleton-medium"></div></div>
                <div style="flex: 1;"><div class="skeleton-text skeleton-medium"></div></div>
                <div style="flex: 1.2;"><div class="skeleton-text skeleton-medium"></div></div>
                <div style="flex: 1.2;"><div class="skeleton-actions"><div class="skeleton-action"></div><div class="skeleton-action"></div><div class="skeleton-action"></div></div></div>
            </div>
            <div class="skeleton-row">
                <div class="skeleton-checkbox"></div>
                <div style="flex: 0.5;"><div class="skeleton-text skeleton-short"></div></div>
                <div style="flex: 1.5;"><div class="skeleton-text skeleton-long"></div></div>
                <div style="flex: 1.2;"><div class="skeleton-text skeleton-medium"></div></div>
                <div style="flex: 1;"><div class="skeleton-text skeleton-medium"></div></div>
                <div style="flex: 1.2;"><div class="skeleton-text skeleton-medium"></div></div>
                <div style="flex: 0.8;"><div class="skeleton-status"></div></div>
                <div style="flex: 1;"><div class="skeleton-text skeleton-medium"></div></div>
                <div style="flex: 1;"><div class="skeleton-text skeleton-medium"></div></div>
                <div style="flex: 1.2;"><div class="skeleton-text skeleton-medium"></div></div>
                <div style="flex: 1.2;"><div class="skeleton-actions"><div class="skeleton-action"></div><div class="skeleton-action"></div><div class="skeleton-action"></div></div></div>
            </div>
        </div>

        <!-- Footer Section -->
        <footer class="table-footer">
            <div class="bulk-actions">
                @if(@Auth::user()->permissionsGroup->settings_status)
                    <select name="action" id="action" class="select-simple" required>
                        <option value="">{{ __('backend.bulkAction') }}</option>
                        <option value="activate">{{ __('backend.activeSelected') }}</option>
                        <option value="block">{{ __('backend.blockSelected') }}</option>
                        <option value="delete">{{ __('backend.deleteSelected') }}</option>
                    </select>
                    <button type="submit" id="submit_all" class="btn-simple-secondary">
                        {{ __('backend.apply') }}
                    </button>
                    <button id="submit_show_msg" class="btn-simple-secondary" data-toggle="modal"
                            style="display: none"
                            data-target="#m-all">
                        {{ __('backend.apply') }}
                    </button>
                @endif
            </div>
            <div class="table-info" id="table-info">
                {{ __('backend.showing') }} 0 - 0 {{ __('backend.of') }} <strong>0</strong> {{ __('backend.records') }}
            </div>
        </footer>
    </div>
</div>

<!-- Delete Confirmation Modal -->
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
        </div>
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
            $('#coupon_packages tbody').hide();
        }

        function hideSkeletonLoader() {
            $('#skeletonLoader').hide();
            $('#coupon_packages tbody').show();
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

        // Initialize DataTable - STATUS KO BILKUL CHANGE NAHI KARENGE
        var dataTable = $("#coupon_packages").DataTable({
            processing: true,
            serverSide: true,
            searching: true,
            ajax: {
                url: "{{ route('coupon.data') }}",
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
                        return data; // Original checkbox
                    }
                },
                { data: 'id' },
                { data: 'title' },
                { data: 'slug' },
                { data: 'package_type' }, // Simple text - no badges
                { data: 'coupon_code' }, // Simple text - no styling
                { 
                    data: 'status', 
                    orderable: false, 
                    searchable: false,
                    render: function(data, type, row) {
                        // STATUS KO BILKUL CHANGE NAHI KARENGE - Original return
                        return data;
                    }
                },
                { data: 'created_by', orderable: false, searchable: false },
                { data: 'updated_by', orderable: false, searchable: false },
                { data: 'updated_at', orderable: false, searchable: false },
                { 
                    data: 'options', 
                    orderable: false, 
                    searchable: false,
                    className: 'text-center',
                    render: function(data, type, row) {
                        return data; // Original options
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
                var info = this.api().page.info();
                $('#table-info').html(
                    '{{ __('backend.showing') }} ' + (info.start + 1) + ' - ' + info.end + 
                    ' {{ __('backend.of') }} <strong>' + info.recordsTotal + '</strong> {{ __('backend.records') }}'
                );
            },
            initComplete: function() {
                hideSkeletonLoader();
            }
        });

        dataTable.on('page.dt', function () {
            $('html, body').animate({
                scrollTop: $(".dataTables_wrapper").offset().top
            }, 'slow');
        });
        
        $.fn.dataTable.ext.errMode = 'none';

        // Show skeleton on initial load
        showSkeletonLoader();
    });
</script>
@endpush