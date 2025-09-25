@extends('dashboard.layouts.master')
@section('title', __('Cabana Packages'))
@section('content')
<div class="padding">
<div class="box">
    <div class="box-header dker">
        <h3>{{ __('Cabana Packages') }}</h3>
        <small>
            <a href="{{ route('adminHome') }}">{{ __('backend.home') }}</a> /
            <a href="">{{ __('cabana-package') }}</a>
        </small>
    </div>
     <div class="row p-a pull-right" style="margin-top: -70px;">
        <div class="col-sm-12">
            <a class="btn btn-fw primary" href="{{route('cabanaCreate')}}">
                <i class="material-icons">&#xe7fe;</i>
                &nbsp; {{ __('Add New Cabana') }}
            </a>
        </div>
    </div>
    
        <div class="table-responsive">
                    <table class="table table-bordered m-a-0">
                        <thead class="dker">
                        <tr>
                            <th  class="width20 dker">
                                <label class="ui-check m-a-0">
                                    <input id="checkAll" type="checkbox"><i></i>
                                </label>
                            </th>
                            <th>{{ __('ID') }}</th>
                            <th>{{ __('venueId') }}</th>
                            <th>{{ __('Cabana Name') }}</th>
                            <th>{{ __('Cabana Slug') }}</th>
                            <th>{{ __('Cabana Category') }}</th>
                            <th>{{ __('Cabana Price') }}</th>
                            <th>{{ __('Featured') }}</th>
                            <th>{{ __('Status') }}</th>
                            <th class="text-center" style="width:200px;">{{ __('backend.options') }}</th>
                        </tr>
                        </thead>
                        <tbody>
                            @if(count($paginated) > 0)
                            @foreach ($paginated as $packages)
                             <tr>
                                <td class="dker"><label class="ui-check m-a-0">
                                        <input type="checkbox" name="ids[]" value="{{ $packages['id'] }}"><i
                                            class="dark-white"></i>
                                        {!! Form::hidden('row_ids[]',$packages['id'], array('class' => 'form-control row_no')) !!}
                                    </label>
                                </td>
                                <td class="h6">
                                    {{ $packages['id'] }}
                                </td>
                                <td class="h6">
                                    {{ $packages['venueId'] }}
                                </td>
                                <td>
                                   {{ $packages['ticketType'] }}
                                </td>

                                <td>
                                    <small>{{ $packages['ticketSlug'] }}</small>
                                </td>
                                <td>
                                    <small>{{ $packages['ticketCategory'] }}</small>
                                </td>
                                <td>
                                    <small>${{ number_format($packages['price'], 2) }}</small>
                                </td>
                                <td>
                                   <i class="fa {{ $packages['is_featured'] == 1 ? 'fa-check text-success' : 'fa-times text-danger' }} inline"></i>
                                </td>
                                <td>
                                   <i class="fa {{ $packages['status'] == 1 ? 'fa-check text-success' : 'fa-times text-danger' }} inline"></i>
                                </td>
                                <td class="text-center">
                                   <div class="dropdown">
                                        <button type="button" class="btn btn-sm light dk dropdown-toggle"
                                                data-toggle="dropdown"><i class="material-icons">&#xe5d4;</i>
                                            {{ __('backend.options') }}
                                        </button>
                                        <div class="dropdown-menu pull-right">
                                            <a class="dropdown-item"
                                                href="{{ route('cabanaEdit',$packages['slug']) }}"><i
                                                    class="material-icons">&#xe3c9;</i> {{ __('backend.edit') }}
                                            </a>
                                            <a class="dropdown-item text-danger"
                                                onclick="DeleteTicket('{{ $packages['slug'] }}')"><i
                                                    class="material-icons">&#xe872;</i> {{ __('backend.delete') }}
                                            </a>
                                        </div>
                                    </div>

                                </td>
                            </tr>
                            @endforeach
                            @endif
                        </tbody>
                    </table>
                    
        </div>
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
                            @if(@Auth::user()->permissionsGroup->settings_status)
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
                </footer>
    
</div>
</div>
<!-- .modal -->
    <div id="delete-tickets" class="modal fade" data-backdrop="true">
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
                    <a type="button" id="ticketaddon_delete_btn" href=""
                       class="btn danger p-x-md">{{ __('backend.yes') }}</a>
                </div>
            </div><!-- /.modal-content -->
        </div>
    </div>
@endsection
@push("after-scripts")
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
    </script>
    <script type="text/javascript">
    function DeleteTicket(slug) {
        let url = '{{ route("cabanaDestroy", ":slug") }}';
        url = url.replace(':slug', slug);

        $("#ticketaddon_delete_btn").attr("href", url);
        $("#delete-tickets").modal("show");
    }
</script>
@endpush
