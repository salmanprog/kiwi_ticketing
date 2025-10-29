@extends('dashboard.layouts.master')
@section('title', __('General Tickets'))
@section('content')
<div class="padding">
<div class="box">
    <div class="box-header dker">
        <h3>{{ __('General Tickets') }}</h3>
        <small>
            <a href="{{ route('adminHome') }}">{{ __('backend.home') }}</a> /
            <a href="">{{ __('Tickets') }}</a>
        </small>
    </div>
     <div class="row p-a pull-right" style="margin-top: -70px;">
        <div class="col-sm-12">
            <a class="btn btn-fw primary" href="{{route('generalticketsCreate')}}">
                <i class="material-icons">&#xe7fe;</i>
                &nbsp; {{ __('Add New Product') }}
            </a>
        </div>
    </div>
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
                            <th>{{ __('Venu ID') }}</th>
                            <th>{{ __('Ticket Type') }}</th>
                            <th>{{ __('Slug') }}</th>
                            <th>{{ __('Category') }}</th>
                            <th>{{ __('Price') }}</th>
                            <th>{{ __('Add-on') }}</th>
                            <th class="text-center" style="width:200px;">{{ __('backend.options') }}</th>
                        </tr>
                        </thead>
                        <tbody>
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
                                        <a href="{{ route('generalticketsEdit',$ticket['ticketSlug']) }}"> 
                                            <div class="pull-right">
                                                 @foreach($ticket->media_slider as $media_cover)
                                                <img src="{{ asset('uploads/sections/' . $media_cover->filename) }}" style="height: 30px;width:100px" alt="Curabitur vitae leo vitae ipsum varius laoreet">
                                                @endforeach
                                            </div>
                                            <div class="h6 m-b-0">{{ $ticket['ticketType'] }}</div>
                                           
                                        </a>
                                    </div>
                                </td>

                                <td>
                                    <small>{{ $ticket['ticketSlug'] }}</small>
                                </td>
                                <td>
                                    <span class="label green"><i class="material-icons"></i>  {{ $ticket['ticketCategory'] }}</span>
                                </td>
                                <td class="text-center">
                                    <small>${{ number_format($ticket['price'], 2) }}</small>
                                </td>
                                <td class="text-center">
                                    <small>{{ count($ticket->addons) }}</small>
                                </td>
                                <td class="text-center">
                                    <div class="dropdown">
                                        <button type="button" class="btn btn-sm light dk dropdown-toggle"
                                                data-toggle="dropdown"><i class="material-icons">&#xe5d4;</i>
                                            {{ __('backend.options') }}
                                        </button>
                                        <div class="dropdown-menu pull-right">
                                            <a class="dropdown-item"
                                                href="{{ route('generalticketsEdit',$ticket['ticketSlug']) }}"><i
                                                    class="material-icons">&#xe3c9;</i> {{ __('backend.edit') }}
                                            </a>
                                            <a class="dropdown-item text-danger"
                                                onclick="DeleteTicket('{{ $ticket['ticketSlug'] }}')"><i
                                                    class="material-icons">&#xe872;</i> {{ __('backend.delete') }}
                                            </a>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                      <!-- Edit Modal -->
                        <div id="editCabanaModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="editCabanaLabel">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">{{ __('Edit Cabana') }}</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="{{ __('backend.close') }}">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <form id="editCabanaForm">
                                            <div class="form-group">
                                                <label for="venueId">Venue ID</label>
                                                <input type="text" class="form-control" id="modalVenueId" readonly>
                                            </div>
                                            <div class="form-group">
                                                <label for="ticketType">Ticket Type</label>
                                                <input type="text" class="form-control" id="modalTicketType" readonly>
                                            </div>
                                            <div class="form-group">
                                                <label for="ticketSlug">Ticket Slug</label>
                                                <input type="text" class="form-control" id="modalTicketSlug" readonly>
                                            </div>
                                            <div class="form-group">
                                                <label for="ticketCategory">Ticket Category</label>
                                                <input type="text" class="form-control" id="modalTicketCategory" readonly>
                                            </div>
                                            <div class="form-group">
                                                <label for="ticketPrice">Price</label>
                                                <input type="text" class="form-control" id="modalTicketPrice" readonly>
                                            </div>
                                            <div class="form-group form-check">
                                                <input type="checkbox" class="form-check-input" id="isfeatured">
                                                <label class="form-check-label" for="isfeatured">Featured</label>
                                            </div>
                                        </form>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('backend.close') }}</button>
                                        <button type="button" class="btn btn-primary" id="saveCabanaChanges">{{ __('Save Changes') }}</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <!-- Edit Modal Close -->

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
                            </div>
                            <div class="col-sm-6 text-right text-center-xs">
                                {!! $paginated->links() !!}
                            </div> -->
<!--                        
                    </div>
                </footer> -->
    @endif
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
        let url = '{{ route("generalticketsDestroy", ":slug") }}';
        url = url.replace(':slug', slug);

        $("#ticketaddon_delete_btn").attr("href", url);
        $("#delete-tickets").modal("show");
    }
</script>
@endpush
