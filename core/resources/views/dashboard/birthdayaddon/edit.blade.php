@extends('dashboard.layouts.master')
@section('title', __('Package Addon'))
@push('after-styles')
    <link href="{{ asset('assets/dashboard/js/iconpicker/fontawesome-iconpicker.min.css') }}" rel="stylesheet">
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

        .btn-primary {
            background: #A0C242 !important;
            border-color: #A0C242 !important;
        }

        .btn-primary:hover {
            background: #8AAE38 !important;
            border-color: #8AAE38 !important;
        }

        .pack-field-ui .col-sm-12 {
            padding: 16px;
        }

        .pack-field-ui strong {
            color: #2c3e50;
        }

        .pack-field-ui .alert.alert-info.mt-3 {
            background: #EBF1DF;
            margin-top: 20px;
        }

        .pack-field-ui label {
            color: #5A6E1E;
        }

        .pack-field-ui .row.align-items-end {
            padding-top: 10px;
        }

        .pack-field-ui .form-control {
            border-radius: 0;
        }
    </style>
@endpush
@section('content')
    <div class="padding">
        <div class="box m-b-0">
            <div class="box-header dker">
                <h3><i class="material-icons">&#xe3c9;</i> {{ __('Package Addon') }}</h3>
                <small>
                    <a href="{{ route('adminHome') }}">{{ __('Package') }}</a> /
                    <a>{{ __('package-addon') }}</a>
                </small>
            </div>
            <div class="box-tool">
                <ul class="nav">
                    <li class="nav-item inline">
                        <a class="nav-link" href="">
                            <i class="material-icons md-18">×</i>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
        <?php $tab_1 = 'active'; ?>
        <div class="box nav-active-border b-info">
            <ul class="nav nav-md">
                <li class="nav-item inline">
                    <a class="nav-link {{ $tab_1 }}" data-toggle="tab" data-target="#tab_details">
                        <span class="text-md"><i class="material-icons">
                                &#xe31e;</i> {{ __('Package Addon') }}</span>
                    </a>
                </li>
            </ul>
            <div class="tab-content clear b-t">
                <div class="tab-pane  {{ $tab_1 }}" id="tab_details">
                    <div class="box-body p-a-2">
                        {{ Form::open(['route' => ['birthdayaddonStore'], 'method' => 'POST']) }}

                        <input name="cabanaSlug" type="hidden" value="{{ $cabana->slug }}">
                        <div class="form-group row">
                            <label class="col-md-2 form-control-label">{{ __('Title') }}
                            </label>
                            <div class="col-sm-10">
                                <input placeholder="" class="form-control has-value" required="" maxlength="191"
                                    dir="ltr" name="ticketType" type="text" value="{{ $cabana->title }}" readonly>
                            </div>
                        </div>
                        <div class="form-group row pack-field-ui">
                            <div id="selected-addons" class="col-sm-12"></div>
                        </div>
                        <div class="form-group row">
                            <label for="link_status" class="col-sm-2 form-control-label">Addons</label>
                            <div class="col-sm-10">
                                @if (count($tickets) > 0)
                                    <div class="row">
                                        @foreach ($tickets as $ticket)
                                            @if ($ticket['ticketSlug'] != $cabana->ticketSlug)
                                                <div class="col-md-4 col-sm-6 mb-2">
                                                    <label class="ui-check ui-check-md d-block">
                                                        <input id="ticket_active_{{ $ticket['ticketSlug'] }}"
                                                            class="has-value addon-checkbox" name="ticket[]" type="checkbox"
                                                            value="{{ $ticket['ticketSlug'] }}"
                                                            data-label="{{ $ticket['ticketType'] }}"
                                                            data-price="{{ $ticket['price'] ?? 0 }}"
                                                            {{ in_array($ticket['ticketSlug'], array_column($cabana_addon, 'ticketSlug')) ? 'checked' : '' }}>
                                                        <i class="dark-white"></i>
                                                        {{ $ticket['ticketType'] }}
                                                    </label>
                                                </div>
                                            @endif
                                        @endforeach
                                    </div>
                                @else
                                    <p>No tickets available.</p>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row m-t-md">
                            <div class="offset-sm-2 col-sm-10">
                                <button type="submit" class="btn btn-lg btn-primary m-t"><i class="material-icons">
                                        &#xe31b;</i> {!! __('backend.update') !!}</button>
                            </div>
                        </div>
                        {{ Form::close() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
@push('after-scripts')
    <script>
        const savedAddons = @json($cabana_addon);
        // document.addEventListener('DOMContentLoaded', function() {
        //     const addonContainer = document.getElementById('selected-addons');
        //     const basePackagePrice = parseFloat({{ $cabana->price }}) || 0;
        //     let currentTotal = basePackagePrice;
        //     const totalDisplay = document.createElement('div');
        //     totalDisplay.classList.add('alert', 'alert-info', 'mt-3');
        //     totalDisplay.innerHTML =
        //         `<strong>Package Total:</strong> $<span id="package-total">${currentTotal.toFixed(2)}</span>`;
        //     addonContainer.parentElement.appendChild(totalDisplay);

        //     function showError(message) {
        //         let errorBox = document.getElementById('price-error');
        //         if (!errorBox) {
        //             errorBox = document.createElement('div');
        //             errorBox.id = 'price-error';
        //             errorBox.classList.add('alert', 'alert-danger', 'mt-2');
        //             addonContainer.parentElement.appendChild(errorBox);
        //         }
        //         errorBox.textContent = message;
        //     }

        //     function clearError() {
        //         const errorBox = document.getElementById('price-error');
        //         if (errorBox) errorBox.remove();
        //     }

        //     function updatePackageTotal() {
        //         let addonTotal = 0;
        //         document.querySelectorAll('.addon-total').forEach(input => {
        //             addonTotal += parseFloat(input.value) || 0;
        //         });
        //         currentTotal = basePackagePrice - addonTotal;
        //         const totalElement = document.getElementById('package-total');
        //         totalElement.textContent = currentTotal.toFixed(2);
        //         const difference = Math.abs(currentTotal);
        //         const submitButton = document.querySelector('button[type="submit"]');

        //         if (difference > 0.009) {
        //             showError("Package total must be exactly zero after addons!");
        //             if (submitButton) submitButton.disabled = true;
        //         } else {
        //             clearError();
        //             if (submitButton) submitButton.disabled = false;
        //         }
        //     }

        //     function createAddonItem(checkbox) {
        //         const slug = checkbox.value;
        //         const label = checkbox.dataset.label;
        //         const basePrice = parseFloat(checkbox.dataset.price) || 0;
        //         const saved = savedAddons.find(a => a.ticketSlug === slug) || {};
        //         const quantityValue = saved.quantity ? parseFloat(saved.quantity) : 1;
        //         const priceValue = saved.price ? parseFloat(saved.price) : basePrice;
        //         if (addonContainer.querySelector(`[data-slug="${slug}"]`)) return;

        //         const wrapper = document.createElement('div');
        //         wrapper.classList.add('addon-item', 'p-3', 'border', 'rounded', 'mb-2', 'bg-light');
        //         wrapper.setAttribute('data-slug', slug);
        //         wrapper.innerHTML = `
    //     <div class="row align-items-end">
    //         <div class="col-md-2">
    //             <label class="form-label"><strong>${label}</strong></label>
    //         </div>
    //         <div class="col-md-3">
    //             <label>Quantity</label>
    //             <input type="number" name="quantity[${slug}]" 
    //                 class="form-control addon-qty" 
    //                 value="${isNaN(quantityValue) ? 1 : quantityValue}" min="1">
    //         </div>
    //         <div class="col-md-3">
    //             <label>Price (each)</label>
    //             <input type="number" step="0.01" 
    //                 name="price[${slug}]" 
    //                 class="form-control addon-price" 
    //                 value="${isNaN(priceValue) ? basePrice.toFixed(2) : priceValue.toFixed(2)}">
    //         </div>
    //         <div class="col-md-3">
    //             <label>Total</label>
    //             <input type="text" 
    //                 class="form-control addon-total" 
    //                 value="${((isNaN(quantityValue) ? 1 : quantityValue) * (isNaN(priceValue) ? basePrice : priceValue)).toFixed(2)}" readonly>
    //         </div>
    //     </div>
    // `;

        //         addonContainer.appendChild(wrapper);

        //         const qtyInput = wrapper.querySelector('.addon-qty');
        //         const priceInput = wrapper.querySelector('.addon-price');
        //         const totalInput = wrapper.querySelector('.addon-total');

        //         const updateTotal = () => {
        //             const qty = parseFloat(qtyInput.value) || 0;
        //             const price = parseFloat(priceInput.value) || 0;
        //             totalInput.value = (qty * price).toFixed(2);
        //             updatePackageTotal();
        //         };

        //         qtyInput.addEventListener('input', updateTotal);
        //         priceInput.addEventListener('input', updateTotal);

        //         updatePackageTotal();
        //     }

        //     document.querySelectorAll('.addon-checkbox').forEach(checkbox => {
        //         checkbox.addEventListener('change', function() {
        //             const slug = this.value;
        //             if (this.checked) {
        //                 createAddonItem(this);
        //             } else {
        //                 const existing = addonContainer.querySelector(`[data-slug="${slug}"]`);
        //                 if (existing) existing.remove();
        //                 updatePackageTotal();
        //             }
        //         });
        //         if (checkbox.checked) {
        //             createAddonItem(checkbox);
        //         }
        //     });

        // });


        document.addEventListener('DOMContentLoaded', function() {
            const addonContainer = document.getElementById('selected-addons');
            const basePackagePrice = parseFloat({{ $cabana->price }}) || 0;
            let currentTotal = basePackagePrice;

            // ---------------- TOTAL DISPLAY ----------------
            const totalDisplay = document.createElement('div');
            totalDisplay.classList.add('alert', 'alert-info', 'mt-3');
            totalDisplay.innerHTML =
                `<strong>Package Total:</strong> $<span id="package-total">${currentTotal.toFixed(2)}</span>`;

            // append totalDisplay at the END of container
            addonContainer.appendChild(totalDisplay);

            // ---------------- ERROR HANDLING ----------------
            function showError(message) {
                let errorBox = document.getElementById('price-error');
                if (!errorBox) {
                    errorBox = document.createElement('div');
                    errorBox.id = 'price-error';
                    errorBox.classList.add('alert', 'alert-danger', 'mt-2');
                    addonContainer.appendChild(errorBox); // always last
                }
                errorBox.textContent = message;
            }

            function clearError() {
                const errorBox = document.getElementById('price-error');
                if (errorBox) errorBox.remove();
            }

            // ---------------- UPDATE PACKAGE TOTAL ----------------
            function updatePackageTotal() {
                let addonTotal = 0;
                document.querySelectorAll('.addon-total').forEach(input => {
                    addonTotal += parseFloat(input.value) || 0;
                });
                currentTotal = basePackagePrice - addonTotal;
                const totalElement = document.getElementById('package-total');
                totalElement.textContent = currentTotal.toFixed(2);
                const difference = Math.abs(currentTotal);
                const submitButton = document.querySelector('button[type="submit"]');

                if (difference > 0.009) {
                    showError("Package total must be exactly zero after addons!");
                    if (submitButton) submitButton.disabled = true;
                } else {
                    clearError();
                    if (submitButton) submitButton.disabled = false;
                }
            }

            // ---------------- CREATE ADDON ITEM ----------------
            function createAddonItem(checkbox) {
                const slug = checkbox.value;
                const label = checkbox.dataset.label;
                const basePrice = parseFloat(checkbox.dataset.price) || 0;
                const saved = savedAddons.find(a => a.ticketSlug === slug) || {};
                const quantityValue = saved.quantity ? parseFloat(saved.quantity) : 1;
                const priceValue = saved.price ? parseFloat(saved.price) : basePrice;

                if (addonContainer.querySelector(`[data-slug="${slug}"]`)) return;

                const wrapper = document.createElement('div');
                wrapper.classList.add('addon-item', 'p-3', 'border', 'rounded', 'mb-2', 'bg-light');
                wrapper.setAttribute('data-slug', slug);
                wrapper.innerHTML = `
            <div class="row align-items-end">
                <div class="col-md-2">
                    <label class="form-label"><strong>${label}</strong></label>
                </div>
                <div class="col-md-3">
                    <label>Quantity</label>
                    <input type="number" name="quantity[${slug}]" 
                        class="form-control addon-qty" 
                        value="${isNaN(quantityValue) ? 1 : quantityValue}" min="1">
                </div>
                <div class="col-md-3">
                    <label>Price (each)</label>
                    <input type="number" step="0.01" 
                        name="price[${slug}]" 
                        class="form-control addon-price" 
                        value="${isNaN(priceValue) ? basePrice.toFixed(2) : priceValue.toFixed(2)}">
                </div>
                <div class="col-md-3">
                    <label>Total</label>
                    <input type="text" 
                        class="form-control addon-total" 
                        value="${((isNaN(quantityValue) ? 1 : quantityValue) * (isNaN(priceValue) ? basePrice : priceValue)).toFixed(2)}" readonly>
                </div>
            </div>
        `;

                // INSERT BEFORE totalDisplay to ensure total stays last
                addonContainer.insertBefore(wrapper, totalDisplay);

                const qtyInput = wrapper.querySelector('.addon-qty');
                const priceInput = wrapper.querySelector('.addon-price');
                const totalInput = wrapper.querySelector('.addon-total');

                const updateTotal = () => {
                    const qty = parseFloat(qtyInput.value) || 0;
                    const price = parseFloat(priceInput.value) || 0;
                    totalInput.value = (qty * price).toFixed(2);
                    updatePackageTotal();
                };

                qtyInput.addEventListener('input', updateTotal);
                priceInput.addEventListener('input', updateTotal);

                updatePackageTotal();
            }

            // ---------------- CHECKBOX EVENTS ----------------
            document.querySelectorAll('.addon-checkbox').forEach(checkbox => {
                checkbox.addEventListener('change', function() {
                    const slug = this.value;
                    if (this.checked) {
                        createAddonItem(this);
                    } else {
                        const existing = addonContainer.querySelector(`[data-slug="${slug}"]`);
                        if (existing) existing.remove();
                        updatePackageTotal();
                    }
                });
                if (checkbox.checked) {
                    createAddonItem(checkbox);
                }
            });

        });
    </script>
@endpush
