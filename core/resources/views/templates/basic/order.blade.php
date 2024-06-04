@extends($activeTemplate . 'layouts.frontend')
@php
    $page = 'order';
    $sampleSetCarts??=null;
$carts??=[];
@endphp

<script type="text/javascript" src="https://cdn.jsdelivr.net/jquery/latest/jquery.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js" defer></script>
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
<link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
</head>

<style>
    .invalid-input {
        border: 1px solid red !important;
    }

    .inner-hero {
        display: none;
    }

    .order-title {
        font-size: 48px;
        font-weight: 600;
        line-height: 56px;
        letter-spacing: 0px;
        text-align: left;
        display: block;
        padding: 36px 0;
    }

    .order-container-section {
        padding: 0 64px;
    }

    .order-container-section .order-content-container {
        display: flex;
        gap: 130px
    }

    .details-accordion {
        flex: 0.5;
    }

    .arrow-icon {
        float: right;
        transition: transform 0.3s ease;
    }

    /* Rotate the arrow icon when the accordion is collapsed */
    .collapsed .arrow-icon {
        transform: rotate(-180deg);
    }

    .order-details-input {
        height: 56px;
    }

    .collapse .card-body .form-group {
        margin-bottom: 16px !important;
    }

    .card-header-order {
        background-color: white !important;
        border-bottom: none !important;
    }

    .form-check-input:checked {
        background-color: #FF6128 !important;
        border-color: #FF6128 !important;
    }

    .order-login-alert {
        background-color: #FF61280A;
    }

    .error-message {
        border-top: 1px solid red;
        padding-top: 10px;
        color: red;
        display: none;
    }

    .cart-price-container {
        flex-direction: column;

    }

    .cart-price {
        font-size: 16px;
        font-weight: 600 !important;
        line-height: 24px;
        text-align: right;
        color: var(--text-primary-color)
    }

    .cart-grand-total {
        font-size: 20px;
        font-weight: 600;
        line-height: 32px;
        text-align: left;
        color: var(--text-primary-color)
    }
    /*hide preferred days times and it's nested elements*/
    .preferred-days-times, .preferred-days-times * {
        display: none;{{-- todo enable these and fix saving to exclude the non-checked items--}}
    }
</style>
<link rel="stylesheet" href="{{ asset($activeTemplateTrue . 'css_commerce/main.css') }}">
@php
    $event = \App\Models\Event::find($event_id);
    $sample_set_cart_config = $event->sample_set_cart_config ??'';
//    dd($event->sample_set_cart_config);
@endphp

@section('content')
    <!-- Product details -->
    <section class="order-container-section pb-5">



        @if ($user === null)
            <x-login-drawer :page="$page" />
        @endif


        <h2 class="order-title">@lang('Order Summary')</h2>
        <div class="order-content-container">

            <form class="details-accordion" id="orderForm" method="post" action="{{ $sampleSetCarts?route('sample_set_order.store') :route('order.store') }}">
                @csrf
                <div class="container mt-5 details-accordion">
                    <div id="accordion">
                        <div class="card">
                            <div class="card-header card-header-order" id="headingOne">
                                <h5 class="mb-0">
                                    <button class="btn w-100" data-toggle="collapse" data-target="#customer"
                                        aria-expanded="true" aria-controls="customer">
                                        <span class="float-left">
                                            @lang('Customer')
                                        </span>
                                        <i class="fas fa-chevron-down arrow-icon"></i>
                                    </button>
                                </h5>
                            </div>

                            <div id="customer" class="collapse show shadow" aria-labelledby="headingOne"
                                data-parent="#accordion">
                                <div class="card-body">
                                    <div class="form-group">
                                        <input value="{{ $user['firstname'] ?? '' }}" type="text"
                                            style="background-color: white !important;"
                                            class="form-control order-details-input" placeholder=@lang('First Name*')
                                            name="customer_first_name" required />
                                    </div>
                                    <div class="form-group">
                                        <input value="{{ $user['lastname'] ?? '' }}" type="text"
                                            style="background-color: white !important;"
                                            class="form-control order-details-input" placeholder=@lang('Last Name*')
                                            name="customer_last_name" required />
                                    </div>
                                    <div class="form-group">
                                        <input value="{{ $user['email'] ?? '' }}" type="email"
                                            style="background-color: white !important;"
                                            class="form-control order-details-input" placeholder=@lang('Email Address*')
                                            name="customer_email" required />
                                    </div>
                                    <div class="form-group">
                                        <input value="{{ $user['mobile'] ?? '' }}" type="tel"
                                            style="background-color: white !important;"
                                            class="form-control order-details-input" placeholder=@lang('Mobile*')
                                            name="customer_phone" required />
                                    </div>
                                    <div class="form-group">
                                        <input value="{{ $user['company_name'] ?? '' }}" type="text"
                                            style="background-color: white !important;"
                                            class="form-control order-details-input" placeholder=@lang('Company Name*')
                                            name="customer_company_name" required />
                                    </div>
                                    <div class="form-group">
                                        <input value="{{ $user['company_website'] ?? '' }}" type="text"
                                            style="background-color: white !important;"
                                            class="form-control order-details-input" placeholder=@lang('Company Website*')
                                            name="customer_company_website" required />
                                    </div>
                                </div>
                            </div>

                        </div>
                        <!-- Add more accordion items as needed -->
                    </div>
                    <div class="card mt-3">
                        <div class="card-header card-header-order" id="headingTwo">
                            <h5 class="mb-0">
                                <button class="btn w-100" data-toggle="collapse" data-target="#shipping"
                                    aria-expanded="false" aria-controls="shipping">
                                    <span class="float-left">
                                        @lang('Shipping & Delivery')
                                    </span>
                                    <i class="fas fa-chevron-down arrow-icon"></i>
                                </button>
                            </h5>
                        </div>

                        <div id="shipping" class="collapse show shadow" aria-labelledby="headingTwo"
                            data-parent="#accordion">
                            <div class="card-body">
                                <div class="form-group">
                                    <div class="form-check py-2">
                                        <input class="form-check-input" type="radio" name="shipping_is_business"
                                            id="residential" value="0">
                                        <label class="form-check-label" for="residential">@lang('Residential')</label>
                                    </div>
                                    <div class="form-check py-2">
                                        <input class="form-check-input" type="radio" name="shipping_is_business"
                                            id="Business" value="1">
                                        <label class="form-check-label" for="Business">@lang('Business')</label>
                                    </div>

                                </div>
                                <div class="form-group">
                                    <input value="{{ $user['shipping_first_name'] ?? '' }}" type="text"
                                        style="background-color: white !important;"
                                        class="form-control order-details-input" placeholder=@lang('First Name*')
                                        name="shipping_first_name" required />
                                </div>
                                <div class="form-group">
                                    <input value="{{ $user['shipping_last_name'] ?? '' }}" type="text"
                                        style="background-color: white !important;"
                                        class="form-control order-details-input" placeholder=@lang('Last Name*')
                                        name="shipping_last_name" required />
                                </div>
                                <div class="form-group">
                                    <input value="{{ $user['shipping_address_1'] ?? '' }}" type="text"
                                        style="background-color: white !important;"
                                        class="form-control order-details-input" placeholder=@lang('Address 1*')
                                        name="shipping_address1" required />
                                </div>
                                <div class="form-group">
                                    <input value="{{ $user['shipping_address_2'] ?? '' }}" type="text"
                                        style="background-color: white !important;"
                                        class="form-control order-details-input" placeholder=@lang('Address 2')
                                        name="shipping_address2" />
                                </div>
                                <div class="form-group">
                                    <select style="background-color: white !important;"
                                        class="form-control order-details-input" name="shipping_country">
                                        <option value="" disabled selected>@lang('Select Country*')</option>
                                        @foreach ($countries as $country)
                                            <option id={{ $country['id'] }} value={{ $country['id'] }}>
                                                {{ $country['Name'] }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <input value="{{ $user['shipping_city'] ?? '' }}" type="text"
                                        style="background-color: white !important;"
                                        class="form-control order-details-input" placeholder=@lang('City*')
                                        name="shipping_city" required />
                                </div>
                                <div class="form-group">
                                    <input value="{{ $user['shipping_state'] ?? '' }}" type="text"
                                        style="background-color: white !important;"
                                        class="form-control order-details-input" placeholder="@lang('State*')"
                                        name="shipping_state" required />
                                </div>
                                <div class="form-group">
                                    <input value="{{ $user['shipping_postcode'] ?? '' }}" type="text"
                                        style="background-color: white !important;"
                                        class="form-control order-details-input" placeholder="@lang('Zip/Post Code*')"
                                        name="shipping_zip" required />
                                </div>
                                <div class="form-group">
                                    <input value="{{ $user['shipping_phone'] ?? '' }}" type="text"
                                        style="background-color: white !important;"
                                        class="form-control order-details-input" placeholder="@lang('Phone*')"
                                        name="shipping_phone" required />
                                </div>
                                <div class="form-group">
                                    <input value="{{ $user['shipping_EIN_number'] ?? '' }}" type="text"
                                        style="background-color: white !important;"
                                        class="form-control order-details-input" placeholder="@lang('EIN Number (Only US Businesses)')"
                                        name="shipping_EIN_number" />
                                </div>
                                <div class="form-group form-check pb-2">
                                    <input type="checkbox" class="form-check-input" id="sameShippingBilling"
                                        name="same_shipping_billing">
                                    <label class="form-check-label" for="sameShippingBilling">
                                        @lang('Shipping and Billing info are the same')</label>
                                </div>


{{--                                <h4 class="fs-6 pb-4">@lang('Delivery')</h4>--}}


{{--                                <span class="mb-4 text-secondary">@lang('Do you need any of the following?')</span>--}}
{{--                                <div class="form-group form-check  pt-4">--}}
{{--                                    <input type="checkbox" class="form-check-input" id="liftGateDelivery"--}}
{{--                                        name="is_lift_gate_delivery" />--}}
{{--                                    <label class="form-check-label text-secondary" for="liftGateDelivery">--}}
{{--                                        @lang('Lift gate delivery')</label>--}}
{{--                                </div>--}}

{{--                                <div class="form-group form-check">--}}
{{--                                    <input type="checkbox" class="form-check-input" id="insideDelivery"--}}
{{--                                        name="is_inside_delivery" />--}}
{{--                                    <label class="form-check-label text-secondary" for="insideDelivery">--}}
{{--                                        @lang('Inside delivery')</label>--}}
{{--                                </div>--}}

{{--                                <div class="form-group form-check">--}}
{{--                                    <input type="checkbox" class="form-check-input" id="appointmentRequest"--}}
{{--                                        name="is_appointment_request" />--}}
{{--                                    <label class="form-check-label text-secondary" for="appointmentRequest">--}}
{{--                                        @lang('Appointment Request')</label>--}}
{{--                                </div>--}}

{{--                                <div class="form-group form-check">--}}
{{--                                    <input type="checkbox" class="form-check-input" id="notifyRequest"--}}
{{--                                        name="is_notify_request" />--}}
{{--                                    <label class="form-check-label text-secondary" for="notifyRequest">--}}
{{--                                        @lang('Notify Request')</label>--}}
{{--                                </div>--}}

{{--                                <h6 class="mb-4 text-secondary preferred-days-times">@lang('Preferred receiving days and time')</h6>--}}

{{--                                <div class="form-group form-check d-flex align-items-center justify-content-between preferred-days-times">--}}
{{--                                    <div class="d-flex align-items-center">--}}
{{--                                        <input type="checkbox" class="form-check-input" id="mondayDelivery" name="delivery_date[]" value="Monday" />--}}
{{--                                        <label class="form-check-label text-secondary" for="mondayDelivery">Monday</label>--}}
{{--                                    </div>--}}
{{--                                    <div class="d-flex align-items-center">--}}
{{--                                        <div style="width: 300px;">--}}
{{--                                            <input style="background-color: white !important;" type="text" class="form-control date-input timepicker" name="delivery_date[Monday]" id="mondayDateTime" disabled />--}}
{{--                                        </div>--}}
{{--                                    </div>--}}
{{--                                </div>--}}

{{--                                <div class="form-group form-check d-flex align-items-center justify-content-between preferred-days-times">--}}
{{--                                    <div class="d-flex align-items-center">--}}
{{--                                        <input type="checkbox" class="form-check-input" id="tuesdayDelivery" name="delivery_date[]" value="Tuesday" />--}}
{{--                                        <label class="form-check-label text-secondary" for="tuesdayDelivery">Tuesday</label>--}}
{{--                                    </div>--}}
{{--                                    <div class="d-flex align-items-center">--}}
{{--                                        <div style="width: 300px;">--}}
{{--                                            <input style="background-color: white !important;" type="text" class="form-control date-input timepicker" name="delivery_date[Tuesday]" id="tuesdayDateTime" disabled />--}}
{{--                                        </div>--}}
{{--                                    </div>--}}
{{--                                </div>--}}

{{--                                <div class="form-group form-check d-flex align-items-center justify-content-between preferred-days-times">--}}
{{--                                    <div class="d-flex align-items-center">--}}
{{--                                        <input type="checkbox" class="form-check-input" id="wednesdayDelivery" name="delivery_date[]" value="Wednesday" />--}}
{{--                                        <label class="form-check-label text-secondary" for="wednesdayDelivery">Wednesday</label>--}}
{{--                                    </div>--}}
{{--                                    <div class="d-flex align-items-center">--}}
{{--                                        <div style="width: 300px;">--}}
{{--                                            <input style="background-color: white !important;" type="text" class="form-control date-input timepicker" name="delivery_date[Wednesday]" id="wednesdayDateTime" disabled />--}}
{{--                                        </div>--}}
{{--                                    </div>--}}
{{--                                </div>--}}

{{--                                <div class="form-group form-check d-flex align-items-center justify-content-between preferred-days-times">--}}
{{--                                    <div class="d-flex align-items-center">--}}
{{--                                        <input type="checkbox" class="form-check-input" id="thursdayDelivery" name="delivery_date[]" value="Thursday" />--}}
{{--                                        <label class="form-check-label text-secondary" for="thursdayDelivery">Thursday</label>--}}
{{--                                    </div>--}}
{{--                                    <div class="d-flex align-items-center">--}}
{{--                                        <div style="width: 300px;">--}}
{{--                                            <input style="background-color: white !important;" type="text" class="form-control date-input timepicker" name="delivery_date[Thursday]" id="thursdayDateTime" disabled />--}}
{{--                                        </div>--}}
{{--                                    </div>--}}
{{--                                </div>--}}

{{--                                <div class="form-group form-check d-flex align-items-center justify-content-between preferred-days-times">--}}
{{--                                    <div class="d-flex align-items-center">--}}
{{--                                        <input type="checkbox" class="form-check-input" id="fridayDelivery" name="delivery_date[]" value="Friday" />--}}
{{--                                        <label class="form-check-label text-secondary" for="fridayDelivery">Friday</label>--}}
{{--                                    </div>--}}
{{--                                    <div class="d-flex align-items-center">--}}
{{--                                        <div style="width: 300px;">--}}
{{--                                            <input style="background-color: white !important;" type="text" class="form-control timepicker date-input" name="delivery_date[Friday]" id="fridayTimeRange" disabled />--}}
{{--                                        </div>--}}
{{--                                    </div>--}}
{{--                                </div>--}}
                                <!-- Repeat the above form-group for Tuesday, Wednesday, Thursday, Friday -->

{{--                                <div class="form-group">--}}
{{--                                    <label for="specialDeliveryInstructions">--}}
{{--                                        @lang('Any other special delivery instructions?')</label>--}}
{{--                                    <textarea value="" style="resize: none;" rows="3" style="background-color: white !important;"--}}
{{--                                        class="form-control" id="specialDeliveryInstructions" rows="3" name="special_delivery_instruction"></textarea>--}}
{{--                                </div>--}}
                            </div>
                        </div>
                    </div>

                    <div id="billing-accourdion" class="card mt-3">
                        <div class="card-header card-header-order" id="headingTwo">
                            <h5 class="mb-0">
                                <button class="btn w-100" data-toggle="collapse" data-target="#billing"
                                    aria-expanded="false" aria-controls="billing">
                                    <span class="float-left">
                                        @lang('Billing')
                                    </span>
                                    <i class="fas fa-chevron-down arrow-icon"></i>
                                </button>
                            </h5>
                        </div>
                        <div id="billing" class="collapse show shadow" aria-labelledby="headingThree"
                            data-parent="#accordion">
                            <div class="card-body">
                                <div class="form-group">
                                    <input value="{{ $user['billing_first_name'] ?? '' }}" type="text"
                                        style="background-color: white !important;"
                                        class="form-control order-details-input" placeholder="@lang('First Name*')"
                                        name="billing_first_name" required />
                                </div>
                                <div class="form-group">
                                    <input value="{{ $user['billing_last_name'] ?? '' }}" type="text"
                                        style="background-color: white !important;"
                                        class="form-control order-details-input" placeholder="@lang('Last Name*')"
                                        name="billing_last_name" required />
                                </div>
                                <div class="form-group">
                                    <input value="{{ $user['billing_address_1'] ?? '' }}" type="text"
                                        style="background-color: white !important;"
                                        class="form-control order-details-input" placeholder="@lang('Billing Address 1*')"
                                        name="billing_address1" required />
                                </div>
                                <div class="form-group">
                                    <input value="{{ $user['billing_address_2'] ?? '' }}" type="text"
                                        style="background-color: white !important;"
                                        class="form-control order-details-input" placeholder="@lang('Billing Address 2')"
                                        name="billing_address2" />
                                </div>
                                <div class="form-group">
                                    <select value="{{ $user['billing_country'] ?? '' }}"
                                        style="background-color: white
                                        !important;"
                                        class="form-control order-details-input" name="billing_country">
                                        <option value="" disabled selected>@lang('Select Country*')</option>
                                        @foreach ($countries as $country)
                                            <option id={{ $country['id'] }} value={{ $country['id'] }}>
                                                {{ $country['Name'] }}</option>
                                        @endforeach
                                        <!-- Add country options here -->
                                    </select>
                                </div>
                                <div class="form-group">
                                    <input value="{{ $user['billing_city'] ?? '' }}" type="text"
                                        style="background-color: white !important;"
                                        class="form-control order-details-input" placeholder="@lang('City*')"
                                        name="billing_city" required />
                                </div>
                                <div class="form-group">
                                    <input value="{{ $user['billing_state'] ?? '' }}" type="text"
                                        style="background-color: white !important;"
                                        class="form-control order-details-input" placeholder="@lang('State*')"
                                        name="billing_state" required />
                                </div>
                                <div class="form-group">
                                    <input value="{{ $user['billing_postcode'] ?? '' }}" type="text"
                                        style="background-color: white !important;"
                                        class="form-control order-details-input" placeholder="@lang('Zip/Post Code*')"
                                        name="billing_zip" required />
                                </div>
                                <div class="form-group">
                                    <input value="{{ $user['billing_phone'] ?? '' }}" type="text"
                                        style="background-color: white !important;"
                                        class="form-control order-details-input" placeholder="@lang('Phone*')"
                                        name="billing_phone" required />
                                </div>
                            </div>
                        </div>
                    </div>


                    <div class="card my-3">
                        <div class="card-header card-header-order" id="headingThree">
                            <h5 class="mb-0">
                                <button class="btn w-100" data-toggle="collapse" data-target="#payment"
                                    aria-expanded="false" aria-controls="payment">
                                    <span class="float-left">
                                        @lang('Payment')
                                    </span>
                                    <i class="fas fa-chevron-down arrow-icon"></i>
                                </button>
                            </h5>
                        </div>
                        <div id="payment" class="collapse show shadow" aria-labelledby="headingThree"
                            data-parent="#accordion">
                            <div class="card-body">
                                <div class="form-group">
                                    <div class="form-check py-2">
                                        <input class="form-check-input" type="radio" name="payment_method"
                                            id="creditCard" value="Stripe" selected>
                                        <label class="form-check-label" for="creditCard">@lang('Credit Card')</label>
                                    </div>
                                    <div class="form-check py-2">
                                        <input class="form-check-input" type="radio" name="payment_method"
                                            id="bankTransferIntl" value="Wise">
                                        <label class="form-check-label" for="bankTransferIntl">
                                            @lang('Bank Transfer (International Wire)')</label>
                                    </div>
                                    @if(!$sampleSetCarts)
                                        <div class="form-check py-2">
                                            <input class="form-check-input" value="bank" type="radio"
                                                name="payment_method" id="bankTransferNoProcessing">
                                            <label class="form-check-label" for="bankTransferNoProcessing">
                                                @lang('Bank Transfer (No Payment Processing)')</label>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <div id="errorMessage" class="error-message" style="display: none;">
                        @lang('Please fill in all required fields.')
                    </div>

                    <a id="placeOrderBtn" class="cmn--btn w-100 my-1">
                        @lang('PLACE ORDER')
                    </a>
                </div>

            </form>


            @if (count($carts) > 0 || ($sampleSetCarts && count($sampleSetCarts)>0) )
                <div class="details-accordion">
                    <h3 class="bold">@lang('Products')</h3>
                    @php($sum = 0)
                    @if($sampleSetCarts)
                        @foreach($sampleSetCarts as $cart)
                        @php($total_price = ($cart['price'] ?? 0) * ($cart['quantity'] ?? 0))
                        @php($price_lb = round(floatval($cart['total_package_weight_Lb'] ?? 0), 2))
                        @php($sum += $total_price)

                        <div class="cart-prodcut-card border-bottom">
                            <div class="d-flex justify-content-between py-3">
                            </div>
                            <div>
                                <div class="d-flex gap-3 pb-2">
                                    <img class="cart-image" src="{{ $cart['image'] }}" width="198px" />
                                    <div class="w-100 px-4">
                                        <span class="cart-product-type">


                                                [Sample Set]

                                        </span>
                                        <div class="d-flex w-100 justify-content-between align-items-center gap-10">
                                            <span class="pb-1">{{ $cart['name'] }}</span>
                                            <span class="cart-price">USD$ {{ round($total_price, 2) }}</span>
                                        </div>
                                        <div class="d-flex cart-price-container">
                                            <span>Box: {{ $cart['number_of_samples_per_box'].' Samples '.$cart['number_of_samples_per_box']. 'grams each' }} Samples </span>
                                            <span>Size : {{ $cart['total_package_weight_Lb'] }} lb</span>
                                            <span>Quantity : {{ $cart['quantity'] }}</span>
{{--                                            <span>Price/Lb : USD${{ round(floatval($cart['price_lb'] ?? 0)) }} /lb</span>--}}

                                        </div>
                                    </div>
                                </div>

                            </div>


{{--                            <div class="cart-details-container">--}}

{{--                                <span class="cart-product-details"> @lang('Box'): {{ $cart['origin'] }}</span>--}}
{{--                                <span class="cart-product-details"> @lang('Grade'): {{ $cart['grade'] }}</span>--}}
{{--                                <span class="cart-product-details">@lang('Price/lb'): ${{ round($price_lb, 2) }}.</span>--}}
{{--                                <span class="cart-product-details">@lang('Total Units Available'):--}}
{{--                                    {{ $cart['total_units_available'] }}</span>--}}
{{--                                <span class="cart-product-details">@lang('Size'):--}}
{{--                                    {{ $cart['size_weight'] }}@lang('lb Bag')</span>--}}

{{--                            </div>--}}
                        </div>
                    @endforeach
                        @else
                    @foreach ($carts as $cart)
                        @php($total_price = ($cart['price'] ?? 0) * ($cart['quantity'] ?? 0))
                        @php($price_lb = round(floatval($cart['price'] ?? 0) / floatval($cart['size_weight'] ?? 1), 2) ?? 0)
                        @php($sum += $total_price)

                        <div class="cart-prodcut-card border-bottom">
                            <div class="d-flex justify-content-between py-3">
                            </div>
                            <div>
                                <div class="d-flex gap-3 pb-2">
                                    <img class="cart-image" src="{{ $cart['image'] }}" width="198px" />
                                    <div class="w-100 px-4">
                                        <span class="cart-product-type">

                                            @if ($cart['is_sample'] === 1)
                                                [Sample Request]
                                            @else
                                                [Product]
                                            @endif
                                        </span>
                                        <div class="d-flex w-100 justify-content-between align-items-center gap-10">
                                            <span class="pb-1">{{ $cart['name'] }}</span>
                                            <span class="cart-price">USD$ {{ round($total_price, 2) }}</span>
                                        </div>
                                        <div class="d-flex cart-price-container">
                                            <span>Size : {{ $cart['size_weight'] }} lb</span>
                                            <span>Quantity : {{ $cart['quantity'] }}</span>
                                            <span>Price/Lb : USD${{ round(floatval($cart['price_lb'] ?? 0)) }} /lb</span>

                                        </div>
                                    </div>
                                </div>

                            </div>


                            <div class="cart-details-container">

                                <span class="cart-product-details"> @lang('Origin'): {{ $cart['origin'] }}</span>
                                <span class="cart-product-details"> @lang('Grade'): {{ $cart['grade'] }}</span>
                                <span class="cart-product-details">@lang('Price/lb'): ${{ round($price_lb, 2) }}.</span>
                                <span class="cart-product-details">@lang('Total Units Available'):
                                    {{ $cart['total_units_available'] }}</span>
                                <span class="cart-product-details">@lang('Size'):
                                    {{ $cart['size_weight'] }}@lang('lb Bag')</span>

                            </div>
                        </div>
                    @endforeach
                    @endif
                    <div>
                        <div class="d-flex justify-content-between py-2">
                            <span>@lang('Subtotal')</span>
                            <span>{{ round($sum, 2) }}</span>
                        </div>
                        <div>
                            <span>
                                @lang('Shipping and Handling')
                            </span>
                            <div class="d-flex justify-content-between py-1">

                                <div class="select-wrapper">
                                    <select required name="shipping_method" class="form-select" required
                                        id="shipping_method_1">
                                        <option selected>@lang('Please select a shipping country')</option>
                                    </select>
                                </div>

                                <div id="total_shipping_price">
                                    0
                                </div>
                            </div>
                            <div class="d-flex justify-content-between py-2">
                                <span class="cart-grand-total">@lang('Grand Total (USD)')</span>
                                <span id="total_price" class="cart-grand-total">{{ round($sum, 2) }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            @else
                <div class="details-accordion">
                    <h3 class="bold">@lang('Products')</h3>
                    <p>@lang('No items available in the cart.')</p>
                </div>
            @endif
        </div>
    </section>
@endsection




@push('script')
    <script>
        $(document).ready(function() {

    $('.date-input').each(function() {
        $(this).daterangepicker({
            timePicker: true,
            timePicker24Hour: true,
            locale: {
                format: 'YYYY-MM-DD HH:mm:ss'
            }
        });
    });

            var carts = {!! json_encode($carts) !!};
            var sampleSetCarts = {!! json_encode($sampleSetCarts) !!};



            function get_shipping_and_handling_price() {
                @if($sampleSetCarts)
                const product_prices = Object.keys(sampleSetCarts).map(key => ({
                    id: sampleSetCarts[key].sample_set_id,
                    quantity: parseInt(sampleSetCarts[key].quantity)
                }));
                @else
                const product_prices = Object.keys(carts).map(key => ({
                    id: carts[key].product_price_id,
                    quantity: parseInt(carts[key].quantity)
                }));
                @endif

                const selectedOptionId = $('select[name=shipping_country] :selected').attr('id');

                var url = `{{ $sampleSetCarts?route('sample_set_order.get_shipping_price') :route('user.get_shipping_price') }}`;
                var data = {
                    '_token': `{{ csrf_token() }}`,
                    @if($sampleSetCarts)
                    'sample_sets': product_prices,
                    @else
                    'product_prices': product_prices,
                    @endif
                    'shipping_country_id': selectedOptionId,
                    'shipping_region_id': $('#shipping_method_1').val() ?? null,
                    'payment_method': $('input[name="payment_method"]:checked').val() ?? null,
                };
                if ($('#shipping_method_1').val()) {
                    $.ajax({
                        type: "POST",
                        url: url,
                        data: data,
                        success: function(response) {
                            // debugger;
                            $('#total_shipping_price').html(response.total_shipping_price);
                            $('#total_price').html(response.total_price);
                        },
                        error: function(error) {
                            // debugger;
                            $('#total_shipping_price').html("");
                            $('#total_price').html("");
                        }
                    })
                }

            }
            $('#shipping_method_1').on('change', function() {
                get_shipping_and_handling_price()
            });

           $('#bankTransferNoProcessing').on('change', function() {
                get_shipping_and_handling_price()
            });
            $('#creditCard').on('change', function() {
                get_shipping_and_handling_price()
            });
            $('#bankTransferIntl').on('change', function() {
                get_shipping_and_handling_price()
            });
            function get_shipping_methods() {



                const selectedOptionId = $('select[name=shipping_country] :selected').attr('id');

                // debugger;
                var url = `{{ $sampleSetCarts?route('sample_set_order.get_supported_shipping_method'):route('user.order.get_supported_shipping_method') }}`;
                var data = {
                    '_token': `{{ csrf_token() }}`,
                    'event_id': `{{ $event_id ?? 0 }}`,
                    'country_id': selectedOptionId
                };
                $.ajax({
                    type: "POST",
                    url: url,
                    data: data,
                    success: function(response) {
                        // debugger;
                        $('#shipping_method_1').html("");
                        console.log($('#shipping_method_1'))

                        $('#total_shipping_price').html("");

                        $('#shipping_method_1').append("<option value=''></option>");
                        response.methods.forEach(function(item) {
                            $('#shipping_method_1').append("<option value='" + item.id + "'>" +
                                item.shipping_method + "</option>");
                        });
                        @if(!$sampleSetCarts)
                            $('#shipping_method_1').append(
                                "<option value='-1'>@lang('I will manage my own shipping')</option>");
                            $('#shipping_method_1').append(
                                "<option value='-3'>@lang('Air Freight (Request Quote)')</option>");
                        @endif
                        get_shipping_and_handling_price();
                    },
                    error: function(error) {
                        get_shipping_and_handling_price();
                        $('#shipping_method_1').html("");

                        $('#shipping_method_1').append("<option value=''></option>");
                        @if(!$sampleSetCarts)
                            $('#shipping_method_1').append(
                                "<option value='-1'>@lang('I will manage my own shipping')</option>");
                            $('#shipping_method_1').append(
                                "<option value='-3'>@lang('Air Freight (Request Quote)')</option>");
                        @endif
                        $('#total_shipping_price').html("");
                        $('#total_price').html("");
                    }
                })
            }


            $("select[name=shipping_country]").on('change', function() {
                get_shipping_methods()
            });


            $('#creditCard').prop('checked', true);
            $('#residential').prop('checked', true);

            $('#placeOrderBtn').on('click', function(e) {
                e.preventDefault(); // Prevent the default form submission

                const inputValues = {};

                $('.details-accordion input, .details-accordion select, .details-accordion textarea, .details-accordion .form-check-input')
                    .each(function() {
                        const name = $(this).attr('name');
                        const value = $(this).val();
                        const type = $(this).attr('type');

                        if (name === 'payment_method') {
                            inputValues[name] = $('input[name="payment_method"]:checked').val();
                        } else if (type === 'checkbox') {
                            const isChecked = $(this).is(':checked');
                            const checkboxValue = $(this).attr('value');

                            if (isChecked) {
                                inputValues[name] = 1;
                            } else {
                                inputValues[name] = 0;
                            }
                        } else {
                            inputValues[name] = value;
                        }
                    });

                let keysToRemove = [
                    'delivery_date[Friday]',
                    'delivery_date[Monday]',
                    'delivery_date[Thursday]',
                    'delivery_date[Tuesday]',
                    'delivery_date[Wednesday]'
                ];

                keysToRemove.forEach(key => delete inputValues[key]);

                // Convert delivery_date to JSON string
                const deliveryDates = {};
                $('.details-accordion .date-input').each(function() {
                    const checkboxValue = $(this).attr('name').replace('delivery_date[', '')
                        .replace(']', '');
                    const dateValue = $(this).val();
                    if (dateValue) {
                        deliveryDates[checkboxValue] = dateValue;
                    }
                });
                inputValues['delivery_date'] = JSON.stringify(deliveryDates);
                @if(config('app.env') == 'local')
                    console.log('delivery dates')
                console.log(deliveryDates)
                console.log('input values')
                console.log(inputValues['delivery_date'])
                @endif
                @if($sampleSetCarts)
                const product_prices = Object.keys(sampleSetCarts).map(key => ({
                    id: sampleSetCarts[key].sample_set_id,
                    quantity: parseInt(sampleSetCarts[key].quantity)
                }));
                @else
                const product_prices = Object.keys(carts).map(key => ({
                    id: carts[key].product_price_id,
                    quantity: parseInt(carts[key].quantity)
                }));
                @endif

                const addProducts = {
                    ...inputValues,
                    @if($sampleSetCarts)
                    sample_sets: product_prices,
                    @else
                    product_prices: product_prices,
                    @endif
                    shipping_region_id: $('#shipping_method_1').val()
                };
                if ($('#orderForm')[0].checkValidity() && $("#shipping_method_1").val()) {
                    $.ajax({
                        type: 'POST',
                        url: '{{ $sampleSetCarts?route('sample_set_order.store'): route('order.store') }}',
                        data: {
                            _token: "{{ csrf_token() }}",
                            ...addProducts
                        },
                        success: function(response) {
                            @if($sample_set_cart_config   != 'payment_process')
                            iziToast.success({
                                title: "@lang('Order has been sent')",
                                position: "topRight"
                            });
                            @endif
                            window.location.href = response.redirectUrl;
                        },
                        error: function(error) {
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
                                    message: "@lang('Failed to place order')",
                                    position: 'topRight'
                                });
                            }
                        }
                    });
                    $('#errorMessage').hide();
                    $('#orderForm :input').removeClass('invalid-input');
                    $("#shipping_method_1").removeClass('invalid-input');
                } else {
                    const form = $('#orderForm')[0];
                    let invalidInputs = [];
                    $(form).find(':input').each(function() {
                        if (!this.checkValidity()) {
                            invalidInputs.push(this.name);
                            $(this).addClass(
                                'invalid-input');
                        } else {
                            const indexToRemove = invalidInputs.indexOf(this.name);
                            if (indexToRemove !== -1) {
                                invalidInputs.splice(indexToRemove, 1);

                            }
                            $(this).removeClass('invalid-input');
                        }
                    });
                    if (!$("#shipping_method_1").val()) {
                        $("#shipping_method_1").addClass('invalid-input');
                    } else {
                        $("#shipping_method_1").removeClass('invalid-input');
                    }


                    $('#errorMessage').show();
                    $('#errorMessage').html(`
                        Please fill in all required fields.
                        <ul id="errorList"></ul>
                    `);

                    invalidInputs.forEach(error => {
                        $('#errorList').append(`<li>${error}</li>`);
                    });


                }
            });
            $('#sameShippingBilling').on('change', function() {
                if (this.checked) {
                    copyShippingToBilling();
                    $('#billing-accourdion').addClass('d-none')
                } else {
                    clearBillingFields();
                    $('#billing-accourdion').removeClass('d-none')
                }
            });

            function copyShippingToBilling() {
                const shippingFields = {
                    'shipping_first_name': 'billing_first_name',
                    'shipping_last_name': 'billing_last_name',
                    'shipping_address1': 'billing_address1',
                    'shipping_address2': 'billing_address2',
                    'shipping_country': 'billing_country',
                    'shipping_city': 'billing_city',
                    'shipping_state': 'billing_state',
                    'shipping_zip': 'billing_zip',
                    'shipping_phone': 'billing_phone',
                    'shipping_EIN_number': 'billing_EIN_number' // If both shipping and billing have this field
                    // Add more fields as needed...
                };

                // Copy values from shipping to billing fields
                Object.keys(shippingFields).forEach(function(shippingField) {
                    const billingField = shippingFields[shippingField];
                    const shippingValue = $('[name="' + shippingField + '"]').val();
                    $('[name="' + billingField + '"]').val(shippingValue);
                });
            }

            function clearBillingFields() {
                const billingFields = [
                    'billing_first_name',
                    'billing_last_name',
                    'billing_address1',
                    'billing_address2',
                    'billing_country',
                    'billing_city',
                    'billing_state',
                    'billing_zip',
                    'billing_phone',
                    'billing_EIN_number' // If both shipping and billing have this field
                    // Add more fields as needed...
                ];

                // Clear values from billing fields
                billingFields.forEach(function(field) {
                    $('[name="' + field + '"]').val('');
                });
            }


    $('.form-check-input').change(function() {
        var associatedInput = $(this).closest('.form-group').find('.timepicker');
        if ($(this).is(':checked')) {
            associatedInput.prop('disabled', false);
        } else {
            associatedInput.prop('disabled', true);
            associatedInput.val('');
        }
    });
        });
    </script>
@endpush
