@extends('dashboard.layouts.master')
@section('title', __('Product Sale Addon'))
@push("after-styles")
    <link href="{{ asset("assets/dashboard/js/iconpicker/fontawesome-iconpicker.min.css") }}" rel="stylesheet">
    <!--[if lt IE 9]>
    <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->
      <style>

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
     .btn-primary
          {
            background: #A0C242 !important;
            border-color: #A0C242 !important;
        }
  
        .btn-primary:hover {
            background: #8AAE38 !important;
            border-color: #8AAE38 !important;
        }
    
    
    </style>
@endpush
@section('content')
    <div class="padding">
        <div class="box">
            <div class="box-header dker">
                <h3><i class="material-icons">&#xe02e;</i> {{ __('New Product Sale Addon') }}</h3>
                <small>
                    <a href="{{ route('adminHome') }}">{{ __('backend.home') }}</a> /
                    <a>{{__('product-sale-addon')}}</a> 
                </small>
            </div>
            <div class="box-tool">
                <ul class="nav">
                    <li class="nav-item inline">
                        <a class="nav-link" href="#">
                            <i class="material-icons md-18">×</i>
                        </a>
                    </li>
                </ul>
            </div>
            <div class="box-body p-a-2">
                {{Form::open(['route'=>['productsaleaddonStore'],'method'=>'POST', 'files' => true])}}
                    
                    <div class="form-group row">
                        <label for="section_id" class="col-sm-2 form-control-label">
                            {!! __('Sale') !!}
                        </label>
                        <div class="col-sm-10">
                           <select name="offerSlug" id="is_offer" class="form-control">
                                <option value="">- - {!!  __('Select Sale') !!} - -</option>
                                @foreach($offerCreation as $ticket)
                                    <option value="{{ $ticket['slug'] }}" data-offer-type="{{ $ticket['offerType'] }}">{{ $ticket['title'] }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="section_id" class="col-sm-2 form-control-label">
                            {!! __('Sale Addon') !!}
                        </label>
                        <div class="col-sm-10">
                           <select name="ticketSlug" id="ticket_items" class="form-control">
                                <option value="">- - {!!  __('Select Sale Addon') !!} - -</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="details"
                            class="col-sm-2 form-control-label">{!!  __('Description') !!}
                        </label>
                        <div class="col-sm-10">
                                @foreach(Helper::languagesList() as $ActiveLanguage)
                                @if($ActiveLanguage->box_status)
                                    <div class="m-b-1">
                                        {!!  __('backend.customFieldsType99') !!} {!! @Helper::languageName($ActiveLanguage) !!}
                                        <div class="box p-a-xs">
                                            {!! Form::textarea(
                                                'description',
                                                optional(Session::get('WebmasterSectionField'))->{'details_' . @$ActiveLanguage->code},
                                                [
                                                    'ui-jp' => 'summernote',
                                                    'placeholder' => '',
                                                    'class' => 'form-control summernote_' . @$ActiveLanguage->code,
                                                    'dir' => @$ActiveLanguage->direction,
                                                    'ui-options' => '{height: 150}'
                                                ]
                                            ) !!}
                                        </div>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="photo"
                            class="col-sm-2 form-control-label">{!!  __('Image') !!}</label>
                        <div class="col-sm-10">
                            {!! Form::file('photo[]', array('class' => 'form-control','id'=>'photo','accept'=>'image/*','multiple' => 'multiple')) !!}
                        </div>
                    </div>

                    <div class="form-group row m-t-md" style="margin-top: 0 !important;">
                        <div class="offset-sm-2 col-sm-10">
                            <small>
                                <i class="material-icons">&#xe8fd;</i>
                                {!!  __('backend.imagesTypes') !!}
                            </small>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="link_status" class="col-sm-2 form-control-label">Featured</label>
                        <div class="col-sm-10">
                            <div class="radio">
                                <label class="ui-check ui-check-md">
                                    <input id="is_featured1" class="has-value" checked="checked" name="is_featured" type="radio" value="1">
                                    <i class="dark-white"></i>
                                    Yes
                                </label>
                                &nbsp; &nbsp;
                                <label class="ui-check ui-check-md">
                                    <input id="is_featured2" class="has-value" name="is_featured" type="radio" value="0">
                                    <i class="dark-white"></i>
                                    No
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="link_status" class="col-sm-2 form-control-label">Status</label>
                        <div class="col-sm-10">
                            <div class="radio">
                                <label class="ui-check ui-check-md">
                                    <input id="status1" class="has-value" checked="checked" name="status" type="radio" value="1">
                                    <i class="dark-white"></i>
                                    Active
                                </label>
                                &nbsp; &nbsp;
                                <label class="ui-check ui-check-md">
                                    <input id="status2" class="has-value" name="status" type="radio" value="0">
                                    <i class="dark-white"></i>
                                    Not Active
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                <button type="button"
                        class="btn dark-white p-x-md"
                        data-dismiss="modal">{{ __('backend.cancel') }}</button>
                <button type="submit"
                        class="btn btn-primary p-x-md">{!! __('backend.add') !!}</button>
            </div>
                {{Form::close()}}
            
        </div>
    </div>
@endsection
@push("after-scripts")
    <script src="{{ asset("assets/dashboard/js/iconpicker/fontawesome-iconpicker.js") }}"></script>
    <script>
        $(function () {
            $('.icp-auto').iconpicker({placement: '{{ (@Helper::currentLanguage()->direction=="rtl")?"topLeft":"topRight" }}'});
        });

        const data = @json($tickets_arr);

        const select = document.getElementById('is_offer');
        const ticketItemsSelect = document.getElementById('ticket_items'); // Ensure this matches your actual element ID

        select.addEventListener('change', function () {
            const selectedOption = this.options[this.selectedIndex];
            const selectedValue = selectedOption.getAttribute('data-offer-type');
            ticketItemsSelect.innerHTML = '<option value="">-- Select Offer Addon --</option>';

            let selectedArray = [];

            if (selectedValue === "any_day") {
                selectedArray = data.ticket;
            } else if (selectedValue === "specifc_date") { // fixed typo
                selectedArray = data.ticket_addon;
            }

            selectedArray.forEach(item => {
                const option = document.createElement('option');
                option.value = item.ticketSlug;
                option.textContent = item.ticketType; // or use a more detailed label
                option.setAttribute('data-price', item.price);
                ticketItemsSelect.appendChild(option);
            });
        });
        // const select = document.getElementById('ticket_items');
        // const priceDisplay = document.getElementById('new_price');

        // select.addEventListener('change', function() {
        //     const selectedOption = this.options[this.selectedIndex];
        //     const price = selectedOption.getAttribute('data-price');
        //     priceDisplay.value = price ? price : '0';
        // });
    </script>
@endpush
