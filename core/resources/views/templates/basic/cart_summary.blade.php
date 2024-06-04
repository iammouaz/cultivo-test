@extends($activeTemplate . 'layouts.frontend')
<style>
    .card {
        border-radius: 4px;
        background: var(--background-paper-elevation-4, #FFF);
        /* elevation/4 */
        box-shadow: 0px 2px 4px -1px rgba(36, 40, 40, 0.20), 0px 4px 5px 0px rgba(36, 40, 40, 0.14), 0px 1px 10px 0px rgba(36, 40, 40, 0.12);
    }

    .hero-section {
        display: none !important
    }


    .header {
        color: rgba(36, 40, 40, 0.87);
        font-feature-settings: 'clig' off, 'liga' off;
        font-family: Lato;
        font-size: 48px;
        font-style: normal;
        font-weight: 600;
        line-height: 116.7%;
        /* 56.016px */
    }

    .typography-text {
        color: var(--Primary, #008E8F);
        text-align: center;
        font-family: Lato;
        font-size: 16px;
        font-style: normal;
        font-weight: 400;
        line-height: 24px;
        /* 150% */
    }

    .note {
        background: #E0F1F2;
        color: rgba(0, 142, 143, 1)
    }

    .typography-h6 {
        color: var(--text-secondary, rgba(36, 40, 40, 0.60));
        font-feature-settings: 'clig' off, 'liga' off;
        /* typography/overline */
        font-family: Lato;
        font-size: 12px;
        font-style: normal;
        font-weight: 400;
        line-height: 266%;
        /* 31.92px */
        letter-spacing: 1px;
        text-transform: uppercase;
    }


    .typography-h5 {
        color: var(--text-primary, rgba(36, 40, 40, 0.87));
        font-feature-settings: 'clig' off, 'liga' off;
        font-family: Lato;
        font-size: 20px;
        font-style: normal;
        font-weight: 700;
        line-height: 160%;
        /* 32px */
        letter-spacing: 0.15px;
    }


    .typography-body1 {
        color: var(--text-primary, rgba(36, 40, 40, 0.87));
        font-feature-settings: 'clig' off, 'liga' off;

        /* typography/body1 */
        font-family: Lato;
        font-size: 16px;
        font-style: normal;
        font-weight: 400;
        line-height: 150%;
        /* 24px */
        letter-spacing: 0.15px;
    }


    .typography-body2 {
        color: var(--text-secondary, rgba(36, 40, 40, 0.60));
        font-feature-settings: 'clig' off, 'liga' off;
        font-family: Lato;
        font-size: 16px;
        font-style: normal;
        font-weight: 600;
        line-height: 150%;
        letter-spacing: 0.15px;
    }


    .typography-subtitle1 {
        color: var(--text-secondary, rgba(36, 40, 40, 0.60));
        font-feature-settings: 'clig' off, 'liga' off;

        /* typography/caption */
        font-family: Lato;
        font-size: 12px;
        font-style: normal;
        font-weight: 400;
        line-height: 166%;
        /* 19.92px */
        letter-spacing: 0.4px;
    }

    .typeography-subtitle2 {
        color: var(--text-primary, rgba(36, 40, 40, 0.87));
        font-feature-settings: 'clig' off, 'liga' off;
        /* typography/body1 */
        font-family: Lato;
        font-size: 16px;
        font-style: normal;
        font-weight: 400;
        line-height: 150%;
        /* 24px */
        letter-spacing: 0.15px;
    }


    .button {
        color: var(--primary-contrast, #FFF);
        font-feature-settings: 'clig' off, 'liga' off;
        /* components/button-large */
        font-family: Lato;
        font-size: 15px;
        font-style: normal;
        font-weight: 500;
        line-height: 26px;
        /* 173.333% */
        letter-spacing: 0.46px;
        text-transform: uppercase;
        cursor: pointer;
        display: flex;
        width: 488px;
        padding: 8px 22px;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        border-radius: 24px;
        background: var(--primary-main, #008E8F);

        /* elevation/2 */
        box-shadow: 0px 3px 1px -2px rgba(36, 40, 40, 0.20), 0px 2px 2px 0px rgba(36, 40, 40, 0.14), 0px 1px 5px 0px rgba(36, 40, 40, 0.12);
    }

    .cart-icon-wrapper svg {
        fill: rgba(0, 142, 143, 1)
    }
</style>
@section('content')
    <div class="container-xxl">
        <section id="checkout-page " style="padding-block: 100px">
            <h3 class="header mb-5">@lang("Order Summary")</h3>


            <div class="row">
                <div class="col-md-6 col-xs-12">
                    <div class="d-flex flex-column" style="gap: 20px">

                        <div class="d-flex align-items-center border-bottom py-2">
                            {{-- @include('templates.basic.svgIcons.money_change') --}}
                            <div class="cart-icon-wrapper px-1">
                                @include('templates.basic.svgIcons.cart')
                            </div>
                            <span class="typography-text px-1">{{__($eventCategory)}}</span>
                        </div>


                        <div id="cartItems">
{{--                        @php($sum = 0)--}}
{{--                        @foreach ($cart as $key=>$cartItem)--}}
{{--                            @php($total_price = ($cartItem['price'] ?? 0) * ($cartItem['quantity'] ?? 0))--}}
{{--                            @php($price_lb = round(floatval($cartItem['price'] ?? 0) / floatval($cartItem['size_weight'] ?? 1), 2) ?? 0)--}}
{{--                            @php($sum += $total_price)--}}
{{--                                <div class="d-flex justify-content-between item">--}}
{{--                                    <div>--}}
{{--                                        <div class="d-flex" style="gap: 30px">--}}
{{--                                            <div class="rounded" style="overflow: hidden">--}}
{{--                                                <img src="{{ $cartItem['image'] ?? asset('custom/images/images-placeholder.png') }}" style="object-fit: cover" width="198px"--}}
{{--                                                     height="176px" />--}}
{{--                                            </div>--}}

{{--                                            <div>--}}
{{--                                                <div>--}}
{{--                                                    @if ($cartItem['is_sample'] ?? false)--}}
{{--                                                        <span class="typography-h6 d-block">[Sample request]</span>--}}
{{--                                                    @endif--}}
{{--                                                    <span class="typography-h5 d-block">{{$cartItem['name']}}</span>--}}
{{--                                                    <span class="typography-body1 d-block mb-2">{{$eventName}}</span>--}}
{{--                                                    <span class="typography-body2">@lang('Size'): {{$cartItem['size_name']}} {{ $cartItem['size_weight'] ?? 0 }} lb <br />@lang('Quantity'): {{$cartItem['quantity']}}</span>--}}
{{--                                                </div>--}}

{{--                                                <div class="border mt-2 d-flex rounded py-1 count-container"--}}
{{--                                                     style="height: fit-content;width:fit-content;border-color:var(--input-outlined-enabledBorder, rgba(0, 0, 0, 0.23))">--}}
{{--                                                    <button class="border-0 changeAmount" data-change-type="decrease"--}}
{{--                                                            style="background: transparent">--}}
{{--                                                        @include('templates.basic.svgIcons.minos')--}}
{{--                                                    </button>--}}
{{--                                                    <span class="px-1 shown-value">{{$cartItem['quantity'] ?? 0}}</span>--}}
{{--                                                    <button class="border-0 changeAmount" data-change-type="increase"--}}
{{--                                                            style="background: transparent">--}}
{{--                                                        @include('templates.basic.svgIcons.plus')--}}
{{--                                                    </button>--}}
{{--                                                    <input value="{{$cartItem['quantity'] ?? 0}}" class="count-value" name="count[{{$key}}]['count']"--}}
{{--                                                           type="hidden" />--}}
{{--                                                </div>--}}
{{--                                            </div>--}}
{{--                                        </div>--}}
{{--                                        <div class="mt-3">--}}
{{--                                    <span class="typography-subtitle1">--}}
{{--                                        @lang('Origin'): {{ $cartItem['origin'] ?? '' }}<br />--}}
{{--                                        @lang('Grade'): {{ $cartItem['grade'] ?? '' }}<br />--}}
{{--                                        @lang('Price/lb'): ${{ $price_lb }}.<br />--}}
{{--                                        @lang('Total Units Available'): {{ $cartItem['total_units_available'] ?? '' }}<br />--}}
{{--                                        @lang('Size'): {{$cartItem['size_name']}} {{ $cartItem['size_weight'] ?? 0 }} lb<br />--}}
{{--                                    </span>--}}
{{--                                        </div>--}}
{{--                                    </div>--}}

{{--                                    <div class="text-right">--}}
{{--                                        <button class="border-0 remove-item-button" style="background: transparent">--}}
{{--                                            @include('templates.basic.svgIcons.close')--}}
{{--                                        </button>--}}
{{--                                        <div class="typography-h5 mt-1">USD${{ $total_price }}</div>--}}
{{--                                        <div class="typography-body1">USD${{ $price_lb }}/lb</div>--}}
{{--                                    </div>--}}

{{--                                </div>--}}
{{--                        @endforeach--}}

                        </div>
                        <div>

                        </div>


                    </div>
                </div>


                <div class="col-md-6 col-xs-12" style="padding-left:100px">
                    <div class="card p-3" style="min-width: 537px">
                        <span class="header  bg-white mt-1" style="font-size: 34px">@lang('Total Order')</span>
                        <div class="d-flex justify-content-between mt-4">
                            <span class="typeography-subtitle2">@lang('Subtotal')</span>
                            <span class="typeography-subtitle2" id="subtotalAmount">${{ 0 }}</span>
                            <span class="typeography-subtitle2" id="totalWeight" style="display: none">${{ 0 }}</span>
                        </div>

                        <div class="d-flex justify-content-between mt-2">
                            <span class="typeography-subtitle2">@lang('Shipping and Handling')</span>
                            <span class="typeography-subtitle2" id="shippingAndHandlingFees">$0</span>
                        </div>


                        <div class="mt-3" style="max-width: 257px">
                            <select id="shipping_method" class="form-select mb-1" aria-label=".form-select-lg example">
                                <option selected>@lang('Select Option')</option>
                                <option value='-1'>@lang('I will manage my own shipping')</option>
                                {{--                    <option value='-2'>I am part of a bidding group</option> --}}
                                <option value='-3'>@lang('Air Freight (Request Quote)')</option>
                            </select>
                        </div>

                        <hr />
                        <div id="shippingFeeMessage"></div>

                        <div class="d-flex justify-content-between mt-3">
                            <span class="typeography-subtitle2">@lang('Grand Total (USD)')</span>
                            <span class="typeography-subtitle2" id="grandTotalAmount">${{ 0 }}</span>
                        </div>

                        <div class="mt-4 d-flex justify-content-center">
                            <div class="w-100 button" id="submitButton">
                                @lang('add order details')
                            </div>
                        </div>
                    </div>
                </div>
            </div>



        </section>
    </div>
@endsection





@push('script')
    <script>
        $(document).ready(function() {

            // Call the function to populate cart items initially
            populateCartItems();
            updateEventHandlers();

            // Other event handlers and functions
            $('#shipping_method').change(function() {
                setGrandTotalAjax();
            });
        });
        function populateCartItems(cart=null) {
            {{--$.ajax({--}}
            {{--    url: "{{ route('cart.items') }}",--}}
            {{--    type: "GET",--}}
            {{--    success: function(response) {--}}
            // Clear existing items
            $('#cartItems').empty();
            var sum = 0;
            var totalWeight = 0;
            cart??= {!! json_encode($cart) !!}; // Assuming $cart is passed from PHP to JavaScript

            $.each(cart, function(key, cartItem) {
                var total_price = Math.round((cartItem.price || 0) * (cartItem.quantity || 0),2);
                var price_lb = Math.round((parseFloat(cartItem.price_lb) || 0) , 2) || 0;
                var weight = parseFloat(cartItem.size_weight) || 0;
                var price_id = cartItem.price_id || 0;
                sum += total_price;
                totalWeight += weight * (cartItem.quantity || 0);

                var itemHtml = `

                <div class="d-flex justify-content-between item">
                    <div>
                    <div id="product_price_id" style="display:none">${price_id}</div>
                        <div class="d-flex" style="gap: 30px">
                            <div class="rounded" style="overflow: hidden">
                                <img src="${cartItem.image || '{{ asset('custom/images/images-placeholder.png') }}'}" style="object-fit: cover" width="198px" height="176px" />
                            </div>

                            <div>
                                <div>
                                    ${cartItem.is_sample === "1"? '<span class="typography-h6 d-block .cart-product-type">[Sample request]</span>' : ''}
                                    <span class="typography-h5 d-block">${cartItem.name}</span>
                                    <span class="typography-body1 d-block mb-2">{{ $eventName }}</span>
                                    <span class="typography-body2">Size: ${cartItem.size_name} ${cartItem.size_weight || 0} lb <br />Quantity: ${cartItem.quantity}</span>
                                </div>

                                <div class="border mt-2 d-flex rounded py-1 count-container" style="height: fit-content;width:fit-content;border-color:var(--input-outlined-enabledBorder, rgba(0, 0, 0, 0.23))">
                                    <button class="border-0 changeAmount" data-change-type="decrease" style="background: transparent">
                                        @include('templates.basic.svgIcons.minos')
                </button>
                <span class="px-1 shown-value " id="cart-quantity-number">${cartItem.quantity || 0}</span>
                                    <button class="border-0 changeAmount" data-change-type="increase" style="background: transparent">
                                        @include('templates.basic.svgIcons.plus')
                </button>
                <input value="${cartItem.quantity || 0}" class="count-value" name="count[${key}]['count']" type="hidden" />
                                </div>
                            </div>
                        </div>
                        <div class="mt-3">
                            <span class="typography-subtitle1">
                                Origin: ${cartItem.origin || ''}<br />
                                Grade: ${cartItem.grade || ''}<br />
                                Price/lb: $${price_lb}.<br />
                                Total Units Available: ${cartItem.total_units_available || ''}<br />
                                Size: ${cartItem.size_name} ${cartItem.size_weight || 0} lb<br />
                            </span>
                        </div>
                    </div>
                    <div class="text-right">
                        <button class="border-0 remove-item-button" style="background: transparent">
                            @include('templates.basic.svgIcons.close')
                </button>
                <div class="typography-h5 mt-1">USD$${total_price}</div>
                        <div class="typography-body1">USD$${price_lb}/lb</div>
                    </div>
                </div>
            `;

                $('#cartItems').append(itemHtml);
            });

            // Update subtotal amount
            $('#subtotalAmount').text('$' + sum);
            setGrandTotalAjax(totalWeight,sum);
            //     },
            //     error: function(xhr, status, error) {
            //         console.error(error);
            //     }
            // });
        }
        // Function to fetch and populate cart items
        function setGrandTotalAjax(total_weight,sub_total) {
            total_weight = total_weight || $('#totalWeight').text() || 0;
            sub_total = sub_total || $('#subtotalAmount').text().replace('$', '') || 0;
            offer_sheet_id = {{$event_id}};
            shipping_method = $('#shipping_method').val();
            payment_method = null;

            var grandTotal = 0;
            var shippingAndHandlingFees = 0;
            $.ajax({
                url: "{{ route('user.getShippingAndHandlingFees') }}",
                type: "GET",
                data: {
                    offer_sheet_id: offer_sheet_id,
                    total_weight: total_weight,
                    shipping_method: shipping_method,
                    payment_method: payment_method,
                    sub_total: sub_total
                },
                async: false,
                success: function(response) {
                    grandTotal = response.total_price;
                    shippingAndHandlingFees = response.shipping_and_handling_fees;
                    if (response.message)
                        $('#shippingFeeMessage').html('<div class="note p-2">' + response.message + '</div>');
                    else
                        $('#shippingFeeMessage').html('');

                },
                error: function(xhr, status, error) {
                    console.error(error);
                }
            });
            $('#grandTotalAmount').text('$' + grandTotal);
            $('#shippingAndHandlingFees').text('$' + shippingAndHandlingFees);
            $('#totalWeight').text(total_weight);


        }
        function updateEventHandlers() {



            $('.remove-item-button').click(function() {
                $(this).closest('.item').remove();
                updateCart();
            })


            /**
             * Click handler for change amount buttons.
             * Gets the clicked button's action type and container.
             * Reads the current count value and shown value elements.
             * Checks action type to increase/decrease count and update elements accordingly.
             */
            $('.changeAmount').click(function() {
                const actionType = $(this).attr('data-change-type');

                const container = $(this).closest('.count-container');

                const countInput = container.find('.count-value');

                const currentValue = Number(countInput.val());

                const shownValue = container.find('.shown-value');



                if (actionType === "increase") {

                    const newValue = currentValue + 1

                    countInput.val(newValue);

                    shownValue.text(newValue);
                    updateCart();
                }

                if (actionType === "decrease" && currentValue > 0) {

                    const newValue = currentValue - 1

                    countInput.val(newValue);

                    shownValue.text(newValue);
                    updateCart();
                }

            })
            $('#submitButton').click(function() {
                window.location.href = "{{ route('order.index',['event_id'=>$event_id]) }}";
            })

            function gatherCartData() {
                const cartItems = [];

                $('.item').each(function() {
                    const product_price_id = $(this).find('#product_price_id').text().trim();
                    const quantity = $(this).find('#cart-quantity-number').text().trim();
                    const is_sample = $(this).find('.cart-product-type')?.text().trim() === '[Sample request]';



                    const cartItem = {
                        product_price_id: Number(product_price_id),
                        quantity: Number(quantity),
                        is_sample: Boolean(is_sample) ? 1 : 0
                    };
                    cartItems.push(cartItem);
                });
                console.log(cartItems)
                return cartItems.length ? cartItems : 0;
            }


            function updateCart() {
                const carts = gatherCartData()
                // $('#cartDrawer').html(
                //     '<div class="d-flex justify-content-center align-items-center" style=""><div style="position: absolute; top: 50%;" class="spinner-border" role="status"><span class="visually-hidden">Loading...</span></div></div>' +
                //     $('#cartDrawer').html());
                $.ajax({
                    url: "{{ route('user.updateCart') }}",
                    type: "POST",
                    data: {
                        _token: "{{ csrf_token() }}",
                        carts: carts || [],
                        offer_sheet_id: {{$event_id}}
                    },
                    success: function(response) {
                        // $('#cartDrawer').find('.spinner-border').parent().remove();
                        if(typeof setCartView === 'function')
                            setCartView(response.cartView);
                        if(typeof setItemCount === 'function')
                            setItemCount(response.itemCount);
                        setCartItems(response.cartItems);
                        // $('#cartDrawer').toggleClass('open');
                    },
                    error: function(error) {
                        $('#cartDrawer').find('.spinner-border').parent().remove();

                        if (error.responseJSON && error.responseJSON.errors) {
                            const errors = error.responseJSON.errors;
                            Object.values(errors).forEach(errorMessages => {
                                errorMessages.forEach(errorMessage => {
                                    iziToast.error({
                                        title: 'Error',
                                        message: errorMessage,
                                        position: 'topRight'
                                    });
                                });
                            });
                        } else {
                            iziToast.error({
                                title: 'Error',
                                message: 'Failed to add cart',
                                position: 'topRight'
                            });
                        }
                    }
                });
            }

            $('#delete-cart-item').on('click', function() {
                const cartItem = $(this).closest('.cart-prodcut-card');
                cartItem.remove();
                updateCart()
            });
            function setCartItems(cartItems) {
                // //if this function exists in the page, call it
                // if (typeof populateCartItems === 'function') {
                console.log(cartItems);
                populateCartItems(cartItems[{{$event_id}}]);
                updateEventHandlers();
                // }

            }
        }
    </script>
@endpush
