@extends('dashboard.layouts.master')
@section('title', __('SeasonPass Addon'))
@section('content')
<div class="padding">
<div class="box">
    <div class="box-header dker">
        <h3>{{ __('SeasonPass Addon') }}</h3>
        <small>
            <a href="{{ route('adminHome') }}">{{ __('backend.home') }}</a> /
            <a href="">{{ __('SeasonPassAddon') }}</a>
        </small>
    </div>
     <div class="row p-a pull-right" style="margin-top: -70px;">
        <div class="col-sm-12">
            <a class="btn btn-fw primary" href="{{route('seasonpassaddonCreate')}}">
                <i class="material-icons">&#xe7fe;</i>
                &nbsp; {{ __('Add New Sale Addon') }}
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
                            <th>{{ __('venueId') }}</th>
                            <th>{{ __('ticketType') }}</th>
                            <th>{{ __('ticketSlug') }}</th>
                            <th>{{ __('price') }}</th>
                            <th>{{ __('new_price') }}</th>
                            <th class="text-center" style="width:200px;">{{ __('backend.options') }}</th>
                        </tr>
                        </thead>
                        <tbody>
                             @if(count($paginated) > 0)
                                @foreach ($paginated as $ticket)
                                <tr>
                                    <td class="dker"><label class="ui-check m-a-0">
                                            <input type="checkbox" name="ids[]" value="{{ $ticket['venueId'] }}"><i
                                                class="dark-white"></i>
                                            {!! Form::hidden('row_ids[]',$ticket['venueId'], array('class' => 'form-control row_no')) !!}
                                        </label>
                                    </td>
                                    <td class="h6">
                                    {{ $ticket['venueId'] }}
                                    </td>
                                    <td>
                                    <div class="">
                                            <a href="{{ route('seasonpassaddonEdit',$ticket['slug']) }}"> 
                                                <div class="pull-right">
                                                    @foreach($ticket->media_slider as $media_cover)
                                                    <img src="{{ asset('uploads/sections/' . $media_cover->filename) }}" style="height: 40px;width:150px" alt="Curabitur vitae leo vitae ipsum varius laoreet">
                                                    @endforeach
                                                </div>
                                                <div class="h6 m-b-0">{{ $ticket['ticketType'] }}</div>
                                            
                                            </a>
                                        </div>
                                    </td>

                                    <td>
                                        <small>{{ $ticket['ticketSlug'] }}</small>
                                    </td>
                                    <td class="text-center">
                                        <small>${{ number_format($ticket['price'], 2) }}</small>
                                    </td>
                                    <td class="text-center">
                                        <small>${{ number_format($ticket['new_price'], 2) }}</small>
                                    </td>
                                    </td>
                                    <td class="text-center">
                                        <div class="dropdown">
                                            <button type="button" class="btn btn-sm light dk dropdown-toggle"
                                                    data-toggle="dropdown"><i class="material-icons">&#xe5d4;</i>
                                                {{ __('backend.options') }}
                                            </button>
                                            <div class="dropdown-menu pull-right">
                                                <a class="dropdown-item"
                                                    href="{{ route('seasonpassaddonEdit',$ticket['slug']) }}"><i
                                                        class="material-icons">&#xe3c9;</i> {{ __('backend.edit') }}
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
