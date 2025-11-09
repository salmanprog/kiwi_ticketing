@extends('dashboard.layouts.master')
@section('title', __('Cabana Addon'))
@section('content')
<style>
    div.dataTables_wrapper div.dataTables_processing {
    position: absolute;
    top: 50%;
    left: 50%;
    width: 200px;
    margin-left: -100px;
    margin-top: -26px;
    text-align: center;
    padding: 1em 0;
    }
    .box-header.dker {
        background: linear-gradient(135deg, #A0C242 0%, #8AAE38 100%) !important;
        color: white;
            border-radius: 5px 5px 0 0;
            font-size: 18px;
    }
    .box-header.dker h3 {
        font-size: 22px;
        font-weight: 700;
    }
    .box-tool {
        color: white;
    }
    .primary
        {
        background: #A0C242 !important;
        border-color: #A0C242 !important;
    }
    
    .info
        {
        background: #A0C242 !important;
        border-color: #A0C242 !important;
    }
    .success
        {
        background: #A0C242 !important;
        border-color: #A0C242 !important;
    }
    .btn-primary:hover {
        background: #8AAE38 !important;
        border-color: #8AAE38 !important;
    }
</style>
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
    <div class="table-responsive">
                    <table class="table table-bordered m-a-0" id="cabana_addon">
                        <thead class="dker">
                        <tr>
                            <th class="width20 dker">
                                <label class="ui-check m-a-0">
                                    <input id="checkAll" type="checkbox"><i></i>
                                </label>
                            </th>
                            <th>{{ __('venueId') }}</th>
                            <th>{{ __('Name') }}</th>
                            <th>{{ __('Slug') }}</th>
                            <th>{{ __('Category') }}</th>
                            <th>{{ __('Price') }}</th>
                            <th>{{ __('createdBy') }}</th>
                            <th>{{ __('updatedBy') }}</th>
                            <th>{{ __('updatedAt') }}</th>
                            <th class="text-center" style="width:200px;">{{ __('backend.options') }}</th>
                        </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                    
        </div>
        <!-- <footer class="dker p-a"> -->
                    <!-- <div class="row"> -->
                        <!-- <div class="col-sm-3 hidden-xs"> -->
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
            var dataTable = $("#cabana_addon").DataTable({
                processing: true,
                serverSide: true,
                searching: true,
                ajax: {
                    url: "{{ route('cabanaaddon.data') }}",
                    type: "POST",
                    data: function (data) {
                        data._token = "{{ csrf_token() }}";
                        data.find_q = $('#find_q').val();
                    }
                },
               dom: '<"row"<"col-sm-6"f><"col-sm-6"l>>rtip',
                columns: [
                    { data: 'check', orderable: false, searchable: false },
                    { data: 'venueId' },
                    { data: 'ticketType' },
                    { data: 'ticketSlug' },
                    { data: 'ticketCategory' },
                    { data: 'price' },
                    { data: 'created_by', orderable: false, searchable: false },
                    { data: 'updated_by', orderable: false, searchable: false },
                    { data: 'updated_at', orderable: false, searchable: false },
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
                )
            });

            dataTable.on('page.dt', function () {
                $('html, body').animate({
                    scrollTop: $(".dataTables_wrapper").offset().top
                }, 'slow');
            });
            $.fn.dataTable.ext.errMode = 'none';

           
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
