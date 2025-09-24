@extends('dashboard.layouts.master')
@section('title', __('Season Pass Orders'))
@section('content')
<div class="padding">
<div class="box">
    <div class="box-header dker">
        <h3>{{ __('Season Pass') }}</h3>
        <small>
            <a href="{{ route('adminHome') }}">{{ __('backend.home') }}</a> /
            <a href="">{{ __('Season Pass') }}</a>
        </small>
    </div>
    <div class="box-tool box-tool-lg">
                
            </div>
            <div>
        <div class="table-responsive">
                    <table class="table table table-bordered">
                        <thead class="dker">
                        <tr>
                            <th  class="width20 dker">
                                <label class="ui-check m-a-0">
                                    <input id="checkAll" type="checkbox"><i></i>
                                </label>
                            </th>
                            <th>{{ __('Order No') }}</th>
                            <th>{{ __('Name') }}</th>
                            <th>{{ __('Email') }}</th>
                            <th>{{ __('Phone') }}</th>
                            <th>{{ __('Transaction-Id') }}</th>
                            <th>{{ __('Total') }}</th>
                            <th>{{ __('Paid') }}</th>
                            <th>{{ __('Date') }}</th>
                            <th class="text-center" style="width:200px;">{{ __('backend.options') }}</th>
                        </tr>
                        </thead>
                        <tbody>
                            @if(count($paginated) > 0)
                            @foreach ($paginated as $order)
                             <tr>
                                <td class="dker"><label class="ui-check m-a-0">
                                        <input type="checkbox" name="ids[]" value="{{ $order['id'] }}"><i
                                            class="dark-white"></i>
                                        {!! Form::hidden('row_ids[]',$order['id'], array('class' => 'form-control row_no')) !!}
                                    </label>
                                </td>
                                <td>
                                   <small>{{$order->slug }}</small>
                                </td>
                                <td class="h6">
                                    {{ $order->customer->name }}
                                </td>
                                <td>
                                   <small>{{$order->customer->email }}</small>
                                </td>

                                <td>
                                    <small>{{ $order->customer->phone }}</small>
                                </td>
                                <td>
                                    <small>{{ $order['transactionId'] }}</small>
                                </td>
                                <td>
                                    <small>${{ $order['orderTotal'] }}</small>
                                </td>
                                <td class="text-center">
                                    <i class="fa fa-check text-success inline"></i>
                                </td>
                                <td>
                                    <small>{{ date('Y-m-d', strtotime($order['orderDate'])) }}</small>
                                </td>
                                <td class="text-center">
                                    <div class="dropdown">
                                        <button type="button" class="btn btn-sm light dk dropdown-toggle"
                                                data-toggle="dropdown"><i class="material-icons">&#xe5d4;</i>
                                            {{ __('backend.options') }}
                                        </button>
                                        <div class="dropdown-menu pull-right">
                                            <a class="dropdown-item"
                                                href="{{ route('seasonpassordersdetail',$order['slug']) }}"><i
                                                    class="material-icons"></i> {{ __('backend.preview') }}
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
