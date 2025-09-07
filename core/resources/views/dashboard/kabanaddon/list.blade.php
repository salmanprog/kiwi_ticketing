@extends('dashboard.layouts.master')
@section('title', __('Cabana Addon'))
@section('content')
<div class="padding">
<div class="box">
    <div class="box-header dker">
        <h3>{{ __('Cabana') }}</h3>
        <small>
            <a href="{{ route('adminHome') }}">{{ __('backend.home') }}</a> /
            <a href="">{{ __('Addon') }}</a>
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
                            <th>{{ __('Venu ID') }}</th>
                            <th>{{ __('Ticket Type') }}</th>
                            <th>{{ __('Slug') }}</th>
                            <th>{{ __('Category') }}</th>
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
                                        {!! Form::hidden('row_ids[]',$ticket['venueId'], array('class' => 'form-control row_no')) !!}
                                    </label>
                                </td>
                                <td class="h6">
                                    {{ $ticket['venueId'] }}
                                </td>
                                <td>
                                   <small>{{ $ticket['ticketType'] }}</small>
                                </td>

                                <td>
                                    <small>{{ $ticket['ticketSlug'] }}</small>
                                </td>
                                <td>
                                    <small>{{ $ticket['ticketCategory'] }}</small>
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
                                                href="{{ route('kabanaaddonEdit',$ticket['ticketSlug']) }}"><i
                                                    class="material-icons">&#xe3c9;</i> {{ __('backend.edit') }}
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
                                                <label for="ticketType">Ticket Type</label>
                                                <input type="text" class="form-control" id="modalTicketType" readonly>
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

<script type="text/javascript">
    $(document).ready(function () {
        $('.open-edit-modal').on('click', function () {
            const button = $(this);
            $('#modalVenueId').val(button.data('venueid'));
            $('#modalTicketType').val(button.data('type'));
            $('#modalTicketSlug').val(button.data('slug'));
            $('#modalTicketCategory').val(button.data('category'));
            $('#modalTicketPrice').val(button.data('price'));
            const isFeatured = button.data('featured') == 1;
            $('#isfeatured').prop('checked', isFeatured);
        });

        $('#saveCabanaChanges').on('click', function () {
            
            const insertData = {
                venueId: $('#modalVenueId').val(),
                ticketType: $('#modalTicketType').val(),
                ticketSlug: $('#modalTicketSlug').val(),
                ticketCategory: $('#modalTicketCategory').val(),
                price: $('#modalTicketPrice').val(),
                featured: $('#isfeatured').is(':checked') ? 1 : 0,
            };

            $.ajax({
                url: "http://localhost/kiwi_ticketing/api/v1/cabana/featured/product",
                type: 'POST',
                data: insertData,
                success: function (response) {
                    alert(response.message);
                    $('#editCabanaModal').modal('hide');
                    location.reload();
                },
                error: function (xhr) {
                    alert('Update failed');
                    console.log(xhr.responseText);
                }
            });
        });
    });
</script>
@endpush
