@extends('dashboard.layouts.master')
@section('title', __('backend.usersPermissions'))
@section('content')
    @if (@Auth::user()->permissionsGroup->webmaster_status)
        @include('dashboard.permissions.list')
    @endif

    <div class="padding">
        <div class="box card-enhanced">

            <div class="box-header dker card-header-enhanced">
                <div class="header-content">
                    <div class="header-title">
                        <h3 class="card-title"><i class="material-icons">people</i> {{ __('backend.users') }}</h3>
                        <small class="breadcrumb-enhanced">
                            <a href="{{ route('adminHome') }}" class="breadcrumb-link">{{ __('backend.home') }}</a> /
                            <span class="breadcrumb-current">{{ __('backend.settings') }}</span>
                        </small>
                    </div>
                    @if ($Users->total() > 0 && @Auth::user()->permissionsGroup->settings_status)
                        <div class="header-actions">
                            <a class="btn btn-primary btn-enhanced" href="{{ route('usersCreate') }}">
                                <i class="material-icons">person_add</i>
                                <span>{{ __('backend.newUser') }}</span>
                            </a>
                        </div>
                    @endif
                </div>
            </div>

            <div class="card-body-enhanced">
                @if ($Users->total() == 0)
                    <div class="empty-state">
                        <div class="empty-state-icon">
                            <i class="material-icons">people_outline</i>
                        </div>
                        <h4 class="empty-state-title">{{ __('backend.noData') }}</h4>
                        <p class="empty-state-description">{{ __('backend.noUsersFound') }}</p>
                        @if (@Auth::user()->permissionsGroup->settings_status)
                            <a class="btn btn-primary btn-enhanced" href="{{ route('usersCreate') }}">
                                <i class="material-icons">person_add</i>
                                <span>{{ __('backend.newUser') }}</span>
                            </a>
                        @endif
                    </div>
                @endif

                @if ($Users->total() > 0)
                    {{ Form::open(['route' => 'usersUpdateAll', 'method' => 'post']) }}
                    <div class="table-responsive table-enhanced">
                        <table class="table table-hover table-striped">
                            <thead class="table-header-enhanced">
                                <tr>
                                    <th class="checkbox-column">
                                        <label class="custom-checkbox">
                                            <input id="checkAll" type="checkbox">
                                            <span class="checkmark"></span>
                                        </label>
                                    </th>
                                    <th class="sortable">{{ __('backend.fullName') }}</th>
                                    <th class="sortable">{{ __('backend.loginEmail') }}</th>
                                    <th>{{ __('backend.Permission') }}</th>
                                    <th class="text-center status-column">{{ __('backend.status') }}</th>
                                    <th class="text-center actions-column">{{ __('backend.options') }}</th>
                                </tr>
                            </thead>
                            <tbody>

                                @foreach ($Users as $User)
                                    <tr class="user-row">
                                        <td class="checkbox-column">
                                            <label class="custom-checkbox">
                                                <input type="checkbox" name="ids[]" value="{{ $User->id }}">
                                                <span class="checkmark"></span>
                                                {!! Form::hidden('row_ids[]', $User->id, ['class' => 'form-control row_no']) !!}
                                            </label>
                                        </td>
                                        <td class="user-info">
                                            <div class="user-avatar">
                                                <i class="material-icons">person</i>
                                            </div>
                                            <div class="user-details">
                                                <strong class="user-name">{!! $User->name !!}</strong>
                                            </div>
                                        </td>
                                        <td class="user-email">
                                            <span class="email-text">{!! $User->email !!}</span>
                                        </td>
                                        <td class="permission-badge">
                                            <span
                                                class="badge badge-permission">{{ @$User->permissionsGroup->name }}</span>
                                        </td>
                                        <td class="text-center">
                                            <span
                                                class="status-indicator {{ $User->status == 1 ? 'active' : 'inactive' }}">
                                                <i
                                                    class="material-icons">{{ $User->status == 1 ? 'check_circle' : 'cancel' }}</i>
                                                <span
                                                    class="status-text">{{ $User->status == 1 ? 'Active' : 'Inactive' }}</span>
                                            </span>
                                        </td>
                                        <td class="text-center action-buttons">
                                            <a class="btn btn-sm btn-success btn-action"
                                                href="{{ route('usersEdit', ['id' => $User->id]) }}" data-toggle="tooltip"
                                                title="{{ __('backend.edit') }}">
                                                <i class="material-icons">edit</i>
                                            </a>
                                            @if (@Auth::user()->permissionsGroup->settings_status)
                                                <button class="btn btn-sm btn-danger btn-action" data-toggle="modal"
                                                    data-target="#m-{{ $User->id }}" ui-toggle-class="bounce"
                                                    ui-target="#animate" data-toggle="tooltip"
                                                    title="{{ __('backend.delete') }}">
                                                    <i class="material-icons">delete</i>
                                                </button>
                                            @endif
                                        </td>
                                    </tr>

                                    <!-- Delete Confirmation Modal -->
                                    <div id="m-{{ $User->id }}" class="modal fade modal-enhanced" data-backdrop="true">
                                        <div class="modal-dialog modal-dialog-centered" id="animate">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">
                                                        <i class="material-icons text-warning">warning</i>
                                                        {{ __('backend.confirmation') }}
                                                    </h5>
                                                    <button type="button" class="close" data-dismiss="modal">
                                                        <i class="material-icons">close</i>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="confirmation-content">
                                                        <p>{{ __('backend.confirmationDeleteMsg') }}</p>
                                                        <div class="confirmation-item">
                                                            <strong>{{ $User->name }}</strong>
                                                            <small>{{ $User->email }}</small>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
                                                        {{ __('backend.no') }}
                                                    </button>
                                                    <a href="{{ route('usersDestroy', ['id' => $User->id]) }}"
                                                        class="btn btn-danger">
                                                        {{ __('backend.yes') }}
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- End Delete Confirmation Modal -->
                                @endforeach

                            </tbody>
                        </table>
                    </div>

                    <div class="card-footer-enhanced">
                        <div class="footer-content">
                            <div class="bulk-actions-section">
                                <div id="m-all" class="modal fade modal-enhanced" data-backdrop="true">
                                    <div class="modal-dialog modal-dialog-centered" id="animate">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">
                                                    <i class="material-icons text-warning">warning</i>
                                                    {{ __('backend.confirmation') }}
                                                </h5>
                                            </div>
                                            <div class="modal-body text-center">
                                                <p>{{ __('backend.confirmationDeleteMsg') }}</p>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary"
                                                    data-dismiss="modal">{{ __('backend.no') }}</button>
                                                <button type="submit"
                                                    class="btn btn-danger">{{ __('backend.yes') }}</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                @if (@Auth::user()->permissionsGroup->settings_status)
                                    <div class="bulk-actions">
                                        <select name="action" id="action" class="form-select bulk-select">
                                            <option value="">{{ __('backend.bulkAction') }}</option>
                                            <option value="activate">{{ __('backend.activeSelected') }}</option>
                                            <option value="block">{{ __('backend.blockSelected') }}</option>
                                            <option value="delete">{{ __('backend.deleteSelected') }}</option>
                                        </select>
                                        <button type="submit" id="submit_all" class="btn btn-outline-primary">
                                            {{ __('backend.apply') }}
                                        </button>
                                        <button id="submit_show_msg" class="btn btn-outline-primary" data-toggle="modal"
                                            style="display: none" data-target="#m-all" ui-toggle-class="bounce"
                                            ui-target="#animate">
                                            {{ __('backend.apply') }}
                                        </button>
                                    </div>
                                @endif
                            </div>

                            <div class="pagination-info">
                                <small class="text-muted">
                                    {{ __('backend.showing') }} {{ $Users->firstItem() }}-{{ $Users->lastItem() }}
                                    {{ __('backend.of') }} <strong>{{ $Users->total() }}</strong>
                                    {{ __('backend.records') }}
                                </small>
                            </div>

                            <div class="pagination-section">
                                {!! $Users->links() !!}
                            </div>
                        </div>
                    </div>
                    {{ Form::close() }}
                @endif
            </div>
        </div>
    </div>
@endsection

@push('after-styles')
    <style>
        /* Enhanced CSS Styles with Your Brand Colors */
        .card-enhanced {
            border: none;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            overflow: hidden;
        }

        .card-header-enhanced {
            background: linear-gradient(135deg, #A0C242 0%, #8AAE38 100%);
            color: white;
            padding: 1.5rem 2rem;
            border-bottom: none;
        }

        .header-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 1rem;
        }

        .header-title {
            flex: 1;
        }

        .card-title {
            display: flex;
            align-items: center;
            gap: 10px;
            font-weight: 600;
            margin: 0;
            color: white;
        }

        .breadcrumb-enhanced {
            opacity: 0.9;
            font-size: 0.9rem;
            margin-top: 5px;
        }

        .breadcrumb-link {
            color: rgba(255, 255, 255, 0.9);
            text-decoration: none;
            transition: opacity 0.3s ease;
        }

        .breadcrumb-link:hover {
            color: white;
            opacity: 1;
        }

        .breadcrumb-current {
            color: white;
            opacity: 0.8;
        }

        .header-actions {
            flex-shrink: 0;
        }

        .btn-enhanced {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 10px 20px;
            border-radius: 8px;
            font-weight: 500;
            transition: all 0.3s ease;
            border: none;
            text-decoration: none;
        }

        .btn-primary {
            background: rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.3);
            color: white;
        }

        .btn-primary:hover {
            background: rgba(255, 255, 255, 0.3);
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(255, 255, 255, 0.2);
            color: white;
        }

        .card-body-enhanced {
            padding: 0;
        }

        .empty-state {
            text-align: center;
            padding: 4rem 2rem;
        }

        .empty-state-icon {
            font-size: 4rem;
            color: #dee2e6;
            margin-bottom: 1.5rem;
        }

        .empty-state-title {
            color: #6c757d;
            margin-bottom: 1rem;
            font-weight: 600;
        }

        .empty-state-description {
            color: #6c757d;
            margin-bottom: 2rem;
        }

        .table-enhanced {
            margin: 0;
        }

        .table-header-enhanced {
            background: #f8f9fa;
        }

        .table-header-enhanced th {
            border-bottom: 2px solid #dee2e6;
            font-weight: 600;
            color: #495057;
            padding: 1rem 0.75rem;
            white-space: nowrap;
        }

        .checkbox-column {
            width: 50px;
            text-align: center;
        }

        .custom-checkbox {
            display: inline-block;
            position: relative;
            cursor: pointer;
        }

        .custom-checkbox input {
            opacity: 0;
            position: absolute;
        }

        .checkmark {
            display: inline-block;
            width: 20px;
            height: 20px;
            background: white;
            border: 2px solid #dee2e6;
            border-radius: 4px;
            position: relative;
            transition: all 0.3s ease;
        }

        .custom-checkbox input:checked+.checkmark {
            background: #A0C242;
            border-color: #A0C242;
        }

        .custom-checkbox input:checked+.checkmark:after {
            content: '✓';
            position: absolute;
            color: white;
            font-size: 14px;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
        }

        .user-row {
            transition: background-color 0.2s ease;
        }

        .user-row:hover {
            background-color: #f8f9fa;
        }

        .user-info {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .user-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: linear-gradient(135deg, #A0C242 0%, #8AAE38 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
        }

        .user-name {
            color: #495057;
            font-weight: 600;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            display: inline-block;
            max-width: 113px;
        }

        .user-email {
            color: #6c757d;
        }

        .badge-permission {
            background: #e9ecef;
            color: #495057;
            padding: 4px 8px;
            border-radius: 12px;
            font-size: 0.8rem;
            font-weight: 500;
        }

        .status-indicator {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 4px 8px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 500;
        }

        .status-indicator.active {
            background: #d4edda;
            color: #155724;
        }

        .status-indicator.inactive {
            background: #f8d7da;
            color: #721c24;
        }

        .action-buttons {
            display: flex;
            gap: 8px;
            justify-content: center;
        }

        .btn-action {
            width: 36px;
            height: 36px;
            border-radius: 8px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border: none;
            transition: all 0.3s ease;
        }

        .btn-action:hover {
            transform: translateY(-2px);
        }

        .btn-success {
            background: #28a745;
            color: white;
        }

        .btn-success:hover {
            background: #218838;
            color: white;
        }

        .btn-danger {
            background: #dc3545;
            color: white;
        }

        .btn-danger:hover {
            background: #c82333;
            color: white;
        }

        .modal-enhanced .modal-content {
            border: none;
            border-radius: 12px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
        }

        .modal-enhanced .modal-header {
            border-bottom: 1px solid #e9ecef;
            padding: 1.5rem;
        }

        .modal-enhanced .modal-body {
            padding: 2rem;
        }

        .confirmation-content {
            text-align: center;
        }

        .confirmation-item {
            background: #f8f9fa;
            padding: 1rem;
            border-radius: 8px;
            margin-top: 1rem;
        }

        .confirmation-item strong {
            display: block;
            color: #495057;
            margin-bottom: 4px;
        }

        .confirmation-item small {
            color: #6c757d;
        }

        .card-footer-enhanced {
            background: #f8f9fa;
            border-top: 1px solid #e9ecef;
            padding: 1.5rem 2rem;
        }

        .footer-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 1rem;
        }

        .bulk-actions {
            display: flex;
            gap: 12px;
            align-items: center;
        }

        .bulk-select {
            min-width: 150px;
            border-radius: 6px;
            border: 1px solid #ced4da;
            padding: 8px 12px;
        }

        .pagination-info {
            color: #6c757d;
            font-size: 0.9rem;
        }

        .pagination-section .pagination {
            margin: 0;
        }

        .btn-outline-primary {
            border: 1px solid #A0C242;
            color: #A0C242;
            background: white;
        }

        .btn-outline-primary:hover {
            background: #A0C242;
            color: white;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .card-header-enhanced {
                padding: 1rem;
            }

            .header-content {
                flex-direction: column;
                align-items: flex-start;
                gap: 1rem;
            }

            .header-actions {
                align-self: stretch;
            }

            .btn-enhanced {
                width: 100%;
                justify-content: center;
            }

            .footer-content {
                flex-direction: column;
                text-align: center;
            }

            .bulk-actions {
                justify-content: center;
                flex-wrap: wrap;
            }

            .user-info {
                flex-direction: column;
                align-items: flex-start;
                gap: 8px;
            }

            .action-buttons {
                flex-direction: column;
                gap: 4px;
            }

            .btn-action {
                width: 32px;
                height: 32px;
            }
        }

        @media (max-width: 576px) {
            .header-title h3 {
                font-size: 1.3rem;
            }

            .breadcrumb-enhanced {
                font-size: 0.8rem;
            }
        }
    </style>
@endpush

@push('after-scripts')
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

        // Initialize tooltips
        $(function() {
            $('[data-toggle="tooltip"]').tooltip();
        });
    </script>
@endpush
