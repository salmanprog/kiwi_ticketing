@extends('dashboard.layouts.master')
@section('title', __('Email Template'))
@section('content')
<style>
    .email-template-container {
        background: #fff;
        border-radius: 10px;
        box-shadow: 0 2px 15px rgba(0,0,0,0.08);
        overflow: hidden;
        margin-bottom: 25px;
    }
    .email-template-header {
        background: linear-gradient(135deg, #A0C242 0%, #8AAE38 100%);
        color: white;
        padding: 20px 25px;
        border-bottom: none;
    }
    .email-template-header h3 {
        margin: 0;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 10px;
        font-size: 1.3rem;
    }
    .email-template-header small {
        opacity: 0.9;
        margin-top: 5px;
        display: block;
    }
    .email-template-header small a {
        color: rgba(255,255,255,0.9);
        text-decoration: none;
        transition: color 0.2s;
    }
    .email-template-header small a:hover {
        color: white;
        text-decoration: underline;
    }
    .add-new-btn {
        background: rgba(255, 255, 255, 0.2);
        color: white;
        border: none;
        border-radius: 6px;
        padding: 10px 20px;
        font-weight: 500;
        transition: all 0.2s;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        text-decoration: none;
        margin-top: -70px;
    }
    .add-new-btn:hover {
        background: rgba(255, 255, 255, 0.3);
        transform: translateY(-1px);
        box-shadow: 0 4px 8px rgba(160, 194, 66, 0.3);
        color: white;
        text-decoration: none;
    }
    .table-container {
        padding: 0 25px;
    }
    .table {
        margin-bottom: 0;
        border: 1px solid #e9ecef;
    }
    .table thead.dker {
        background: #f8f9fa;
        border-bottom: 2px solid #A0C242;
    }
    .table th {
        font-weight: 600;
        color: #2d3748;
        border-color: #e9ecef;
        padding: 15px 12px;
    }
    .table td {
        padding: 12px;
        vertical-align: middle;
        border-color: #e9ecef;
    }
    .ui-check input[type="checkbox"] {
        margin: 0;
    }
    .table-footer {
        background: #f8f9fa;
        padding: 20px 25px;
        border-top: 1px solid #e9ecef;
    }
    .pagination {
        margin: 0;
    }
    .page-link {
        color: #A0C242;
        border: 1px solid #dee2e6;
        padding: 8px 12px;
    }
    .page-link:hover {
        color: #8AAE38;
        background-color: #e9ecef;
        border-color: #dee2e6;
    }
    .page-item.active .page-link {
        background-color: #A0C242;
        border-color: #A0C242;
    }
    
    /* Skeleton Loader Styles */
    .skeleton-loader {
        display: none;
    }
    .skeleton-row {
        display: flex;
        align-items: center;
        padding: 12px;
        border-bottom: 1px solid #e9ecef;
        animation: pulse 1.5s ease-in-out infinite;
    }
    .skeleton-checkbox {
        width: 20px;
        height: 20px;
        background: #e0e0e0;
        border-radius: 3px;
        margin-right: 15px;
    }
    .skeleton-cell {
        flex: 1;
        height: 16px;
        background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
        background-size: 200% 100%;
        border-radius: 4px;
        margin: 0 10px;
        animation: shimmer 2s infinite;
    }
    .skeleton-cell.short {
        flex: 0.5;
    }
    .skeleton-cell.medium {
        flex: 0.8;
    }
    .skeleton-cell.long {
        flex: 1.5;
    }
    .skeleton-actions {
        display: flex;
        gap: 8px;
        margin-left: 10px;
    }
    .skeleton-action {
        width: 60px;
        height: 30px;
        background: #e0e0e0;
        border-radius: 4px;
    }
    
    @keyframes pulse {
        0% {
            opacity: 1;
        }
        50% {
            opacity: 0.7;
        }
        100% {
            opacity: 1;
        }
    }
    
    @keyframes shimmer {
        0% {
            background-position: -200% 0;
        }
        100% {
            background-position: 200% 0;
        }
    }
    
    .dataTables_processing {
        position: absolute;
        top: 50%;
        left: 50%;
        width: 200px;
        margin-left: -100px;
        margin-top: -26px;
        text-align: center;
        padding: 1em 0;
        background: linear-gradient(135deg, #A0C242 0%, #8AAE38 100%);
        color: white;
        border-radius: 6px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        z-index: 1000;
    }
    
    .btn-action {
        padding: 6px 12px;
        border-radius: 4px;
        font-size: 0.85rem;
        display: inline-flex;
        align-items: center;
        gap: 4px;
        text-decoration: none;
        transition: all 0.2s;
    }
    .btn-edit {
        background: #17a2b8;
        color: white;
        border: none;
    }
    .btn-edit:hover {
        background: #138496;
        color: white;
        text-decoration: none;
        transform: translateY(-1px);
    }
    .btn-delete {
        background: #dc3545;
        color: white;
        border: none;
    }
    .btn-delete:hover {
        background: #c82333;
        color: white;
        text-decoration: none;
        transform: translateY(-1px);
    }
    .status-badge {
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 0.85rem;
        font-weight: 500;
    }
    .status-active {
        background: #d4edda;
        color: #155724;
    }
    .status-inactive {
        background: #f8d7da;
        color: #721c24;
    }
    
    @media (max-width: 768px) {
        .table-container {
            padding: 0 15px;
            overflow-x: auto;
        }
        .add-new-btn {
            margin-top: 10px;
            margin-bottom: 15px;
        }
        .table-footer .row {
            flex-direction: column;
            gap: 15px;
        }
        .table-footer .col-sm-6 {
            text-align: center !important;
        }
        .skeleton-row {
            flex-wrap: wrap;
        }
        .skeleton-cell {
            margin: 5px 0;
        }
    }
</style>

<div class="padding">
    <div class="email-template-container">
        <div class="email-template-header">
            <h3>{{ __('Email Template') }}</h3>
            <small>
                <a href="{{ route('adminHome') }}">{{ __('backend.home') }}</a> /
                <a href="">{{ __('email-template') }}</a>
            </small>
        </div>
        
        <div class="row p-a pull-right" style="margin-top: -70px;">
            <div class="col-sm-12">
                <a class="add-new-btn" href="{{route('emailTemplateCreate')}}">
                    <i class="material-icons">&#xe7fe;</i>
                    &nbsp; {{ __('Add New Template') }}
                </a>
            </div>
        </div>
        
        <div class="table-container">
            <div class="table-responsive">
                <table class="table table-bordered m-a-0" id="email_templates">
                    <thead class="dker">
                        <tr>
                            <th class="width20 dker">
                                <label class="ui-check m-a-0">
                                    <input id="checkAll" type="checkbox"><i></i>
                                </label>
                            </th>
                            <th>{{ __('ID') }}</th>
                            <th>{{ __('Identifier') }}</th>
                            <th>{{ __('Subject') }}</th>
                            <th>{{ __('Status') }}</th>
                            <th class="text-center" style="width:200px;">{{ __('backend.options') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Skeleton Loader Rows -->
                        <tr class="skeleton-loader">
                            <td colspan="6">
                                <div class="skeleton-row">
                                    <div class="skeleton-checkbox"></div>
                                    <div class="skeleton-cell short"></div>
                                    <div class="skeleton-cell medium"></div>
                                    <div class="skeleton-cell long"></div>
                                    <div class="skeleton-cell short"></div>
                                    <div class="skeleton-actions">
                                        <div class="skeleton-action"></div>
                                        <div class="skeleton-action"></div>
                                    </div>
                                </div>
                                <div class="skeleton-row">
                                    <div class="skeleton-checkbox"></div>
                                    <div class="skeleton-cell short"></div>
                                    <div class="skeleton-cell medium"></div>
                                    <div class="skeleton-cell long"></div>
                                    <div class="skeleton-cell short"></div>
                                    <div class="skeleton-actions">
                                        <div class="skeleton-action"></div>
                                        <div class="skeleton-action"></div>
                                    </div>
                                </div>
                                <div class="skeleton-row">
                                    <div class="skeleton-checkbox"></div>
                                    <div class="skeleton-cell short"></div>
                                    <div class="skeleton-cell medium"></div>
                                    <div class="skeleton-cell long"></div>
                                    <div class="skeleton-cell short"></div>
                                    <div class="skeleton-actions">
                                        <div class="skeleton-action"></div>
                                        <div class="skeleton-action"></div>
                                    </div>
                                </div>
                                <div class="skeleton-row">
                                    <div class="skeleton-checkbox"></div>
                                    <div class="skeleton-cell short"></div>
                                    <div class="skeleton-cell medium"></div>
                                    <div class="skeleton-cell long"></div>
                                    <div class="skeleton-cell short"></div>
                                    <div class="skeleton-actions">
                                        <div class="skeleton-action"></div>
                                        <div class="skeleton-action"></div>
                                    </div>
                                </div>
                                <div class="skeleton-row">
                                    <div class="skeleton-checkbox"></div>
                                    <div class="skeleton-cell short"></div>
                                    <div class="skeleton-cell medium"></div>
                                    <div class="skeleton-cell long"></div>
                                    <div class="skeleton-cell short"></div>
                                    <div class="skeleton-actions">
                                        <div class="skeleton-action"></div>
                                        <div class="skeleton-action"></div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        
        <footer class="table-footer">
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
                                    <button type="submit"
                                            class="btn danger p-x-md">{{ __('backend.yes') }}</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    @if(@Auth::user()->permissionsGroup->settings_status)
                        <!-- Bulk action controls (commented in original) -->
                    @endif
                </div>
                <div class="col-sm-6 text-right text-center-xs">
                    {!! $paginated->links() !!}
                </div>
            </div>
        </footer>
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
        // Show skeleton loader initially
        $('.skeleton-loader').show();
        
        var dataTable = $("#email_templates").DataTable({
            processing: true,
            serverSide: true,
            searching: true,
            ajax: {
                url: "{{ route('email.data') }}",
                type: "POST",
                data: function (data) {
                    data._token = "{{ csrf_token() }}";
                    data.find_q = $('#find_q').val();
                },
                beforeSend: function() {
                    // Show skeleton loader when making request
                    $('.skeleton-loader').show();
                },
                complete: function() {
                    // Hide skeleton loader when request completes
                    $('.skeleton-loader').hide();
                }
            },
            dom: '<"row"<"col-sm-6"f><"col-sm-6"l>>rtip',
            columns: [
                { data: 'check', orderable: false, searchable: false },
                { data: 'id' },
                { data: 'identifier' },
                { data: 'Subject' },
                { data: 'status', orderable: false, searchable: false },
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
            ),
            initComplete: function() {
                // Hide skeleton loader when table is initialized
                $('.skeleton-loader').hide();
            },
            drawCallback: function(settings) {
                // Hide skeleton loader when drawing is complete
                $('.skeleton-loader').hide();
            }
        });

        dataTable.on('page.dt', function () {
            $('html, body').animate({
                scrollTop: $(".dataTables_wrapper").offset().top
            }, 'slow');
        });
        
        // Show skeleton loader during searches and pagination
        dataTable.on('preXhr.dt', function (e, settings, data) {
            $('.skeleton-loader').show();
        });
        
        dataTable.on('xhr.dt', function (e, settings, json, xhr) {
            $('.skeleton-loader').hide();
        });
        
        $.fn.dataTable.ext.errMode = 'none';
    });
</script>
@endpush