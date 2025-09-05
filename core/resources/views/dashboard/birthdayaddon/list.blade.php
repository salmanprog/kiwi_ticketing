@extends('dashboard.layouts.master')
@section('title', __('Birthday Addon'))
@section('content')
<div class="padding">
<div class="box">
    <div class="box-header dker">
        <h3>{{ __('Birthday Addon') }}</h3>
        <small>
            <a href="{{ route('adminHome') }}">{{ __('backend.home') }}</a> /
            <a href="">{{ __('BirthdayAddon') }}</a>
        </small>
    </div>
     <!-- <div class="row p-a pull-right" style="margin-top: -70px;">
        <div class="col-sm-12">
            <a class="btn btn-fw primary" href="#">
                <i class="material-icons">&#xe7fe;</i>
                &nbsp; {{ __('Add New Cabana') }}
            </a>
        </div>
    </div> -->
    @if(count($paginated) > 0)
        <div class="table-responsive">
                    <table class="table table-bordered m-a-0">
                        <thead class="dker">
                        <tr>
                            <th  class="width20 dker">
                                <label class="ui-check m-a-0">
                                    <input id="checkAll" type="checkbox"><i></i>
                                </label>
                            </th>
                            <th>{{ __('Package Title') }}</th>
                            <th>{{ __('Slug') }}</th>
                            <th>{{ __('Price') }}</th>
                            <th class="text-center" style="width:200px;">{{ __('backend.options') }}</th>
                        </tr>
                        </thead>
                        <tbody>
                            @foreach ($paginated as $ticket)
                             <tr>
                                <td class="dker"><label class="ui-check m-a-0">
                                        <input type="checkbox" name="ids[]" value="{{ $ticket['venueId'] }}"><i
                                            class="dark-white"></i>
                                        {!! Form::hidden('row_ids[]',$ticket['id'], array('class' => 'form-control row_no')) !!}
                                    </label>
                                </td>
                                <td>
                                   <small>{{ $ticket['title'] }}</small>
                                </td>

                                <td>
                                    <small>{{ $ticket['slug'] }}</small>
                                </td>
                                <td class="text-center">
                                    <small>{{ $ticket['price'] }}</small>
                                </td>
                                <td class="text-center">
                                    <div class="dropdown">
                                        <button type="button" class="btn btn-sm light dk dropdown-toggle"
                                                data-toggle="dropdown"><i class="material-icons">&#xe5d4;</i>
                                            {{ __('backend.options') }}
                                        </button>
                                        <div class="dropdown-menu pull-right">
                                            <a class="dropdown-item"
                                                href="{{ route('birthdayaddonEdit',$ticket['slug']) }}"><i
                                                    class="material-icons">&#xe3c9;</i> {{ __('backend.edit') }}
                                            </a>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>

        </div>
        <footer class="dker p-a">
                    <div class="row">
                        <div class="col-sm-3 hidden-xs">
                         
                            @if(@Auth::user()->permissionsGroup->settings_status)
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
    @endif
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

@endpush
