@extends($activeTemplate.'layouts.frontend_commerce')

@section('content')

    <section id="checkout-page">

    @if(!is_null($winningHistories) && count($winningHistories) > 0)
{{--        the submit url is the same url that lead to this page 'checkout/{event_id}' --}}
        <form id="checkout-form" action="" method="POST">
            <div class="container checkout-custom-page">
                <!-- Start Main -->
                <main class="row">
                    <h1>@lang('Checkout')</h1>

                    <!-- Start Order Steps -->
                    <section class="col-xl-7 order-steps">
                        <div class="accordion" id="checkout-accordion">
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="customer-heading">
                                    <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                            data-bs-target="#customer-collapse" aria-expanded="true"
                                            aria-controls="customer-collapse">
                                        <i class="far fa-user"></i>
                                        <span>@lang('Customer')</span>
                                    </button>
                                </h2>

                                <div id="customer-collapse" class="accordion-collapse collapse show"
                                     aria-labelledby="customer-heading"
                                     data-bs-parent="#checkout-accordion">

                                    <div class="accordion-body pb-4">
                                        <div class="d-flex">
                                            <table class="customer-info">
                                                <tr>
                                                    <td class="align-top" style="width: 40%;">@lang('Name')</td>
                                                    <td class="align-top">{{ $user->firstname." ".$user->lastname}}</td>
                                                </tr>
                                                <tr>
                                                    <td class="align-top" style="width: 40%;">@lang('Email')</td>
                                                    <td class="align-top">{{ $user->email }}</td>
                                                </tr>
                                                <tr>
                                                    <td class="align-top" style="width: 40%;">@lang('Phone')</td>
                                                    <td class="align-top">{{ $user->billing_phone }}</td>
                                                </tr>
                                                </tr>
                                                <tr>
                                                    <td class="align-top" style="width: 40%;">@lang('Company Name ')</td>
                                                    <td class="align-top">{{ $user->company_name }}</td>
                                                </tr>
                                                </tr>
                                                <tr>
                                                    <td class="align-top" style="width: 40%;">@lang('Company Website')</td>
                                                    <td class="align-top">{{ $user->company_website }}</td>
                                                </tr>
                                            </table>
                                            <div class="edit-profile-btn">
                                                <i class="las la-pencil-alt"></i>
                                                <a href="{{route('user.profile.setting')}}">
                                                    @lang('Edit Profile')
                                                </a>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>

                            <div class="accordion-item">
                                <h2 class="accordion-header" id="shipping-heading">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                            data-bs-target="#shipping-collapse" aria-expanded="false"
                                            aria-controls="shipping-collapse">
                                        <i class="las la-box " style="font-size:21px;"></i>
                                        <span>@lang('Shipping')</span>
                                    </button>
                                </h2>

                                <div id="shipping-collapse" class="accordion-collapse collapse" aria-labelledby="shipping-heading"
                                     data-bs-parent="#checkout-accordion">
                                    <div class="accordion-body">
                                        <div class="row">
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label for="shipping_first_name"
                                                           class="form--label mb-2">@lang('First Name')</label>
                                                    <input onFocus="this.select();" type="text" name="shipping_first_name"
                                                           id="shipping_first_name"
                                                           class="form-control form--control"
                                                           value="{{ auth()->user() ? auth()->user()->shipping_first_name	 : old('shipping_first_name	') }}"
                                                           @if (auth()->user()) @endif required>
                                                </div>
                                            </div>

                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label for="shipping_last_name"
                                                           class="form--label mb-2">@lang('Last Name')</label>
                                                    <input onFocus="this.select();" type="text" name="shipping_last_name" id="shipping_last_name"
                                                           class="form-control form--control"
                                                           value="{{ auth()->user() ? auth()->user()->shipping_last_name : old('shipping_last_name') }}"
                                                           @if (auth()->user()) @endif required>
                                                </div>
                                            </div>

                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label for="shipping_address_1"
                                                           class="form--label mb-2">@lang('Address 1')</label>
                                                    <input onFocus="this.select();" type="text" name="shipping_address_1" id="shipping_address_1"
                                                           class="form-control form--control"
                                                           value="{{ auth()->user() ? auth()->user()->shipping_address_1 : old('shipping_address_1') }}"
                                                           @if (auth()->user()) @endif required>
                                                </div>
                                            </div>

                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label for="shipping_address_2"
                                                           class="form--label mb-2">@lang('Address 2')</label>
                                                    <input onFocus="this.select();" type="text" name="shipping_address_2" id="shipping_address_2"
                                                           class="form-control form--control"
                                                           value="{{ auth()->user() ? auth()->user()->shipping_address_2 : old('shipping_address_2') }}"
                                                           @if (auth()->user()) @endif>
                                                </div>
                                            </div>

                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label for="shipping_city"
                                                           class="form--label mb-2">@lang('City')</label>
                                                    <input onFocus="this.select();" type="text" name="shipping_city" id="shipping_city"
                                                           class="form-control form--control"
                                                           value="{{ auth()->user() ? auth()->user()->shipping_city : old('shipping_city') }}"
                                                           @if (auth()->user()) @endif required>
                                                </div>
                                            </div>

                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label for="shipping_state"
                                                           class="form--label mb-2">@lang('State')</label>
                                                    <input onFocus="this.select();" type="text" name="shipping_state" id="shipping_state"
                                                           class="form-control form--control"
                                                           value="{{ auth()->user() ? auth()->user()->shipping_state : old('shipping_state') }}"
                                                           @if (auth()->user()) @endif required>
                                                </div>
                                            </div>

                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label for="shipping_postcode"
                                                           class="form--label mb-2">@lang('Postcode')</label>
                                                    <input onFocus="this.select();" type="text" name="shipping_postcode" id="shipping_postcode"
                                                           class="form-control form--control"
                                                           value="{{ auth()->user() ? auth()->user()->shipping_postcode : old('shipping_postcode') }}"
                                                           @if (auth()->user()) @endif required>
                                                </div>
                                            </div>

                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label for="shipping_country"
                                                           class="form--label mb-2">@lang('Country')</label>
                                                    {{--                                                    <input onFocus="this.select();" type="text" name="shipping_country" id="shipping_country"--}}
                                                    {{--                                                           class="form-control form--control"--}}
                                                    {{--                                                           value="{{ auth()->user() ? auth()->user()->shipping_country : old('shipping_country') }}"--}}
                                                    {{--                                                           @if (auth()->user()) @endif required>--}}

                                                    <div class="select-wrapper w-100">
                                                        <select name="shipping_country" id="shipping_country"
                                                                class="custom-select-element" required>
                                                            <option value=""></option>

                                                            @foreach($countries as $key => $country)
                                                                <option data-mobile_code="{{ $country->dial_code }}"
                                                                        value="{{ $country->id }}"
                                                                        data-code="{{ $key }}"
                                                                        @if($country->id ==  auth()->user()->shipping_country) selected @endif>{{ __($country->Name) }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label for="shipping_phone"
                                                           class="form--label mb-2">@lang('Phone')</label>
                                                    <input onFocus="this.select();" type="text" name="shipping_phone" id="shipping_phone"
                                                           class="form-control form--control"
                                                           value="{{ auth()->user() ? auth()->user()->shipping_phone : old('shipping_phone') }}"
                                                           @if (auth()->user()) @endif required>
                                                </div>
                                            </div>

                                            <div class="col-sm-6">
                                                <div class="form-group custom-input-container">
                                                    <input type="hidden" name="use_for_billing_info" id="use_for_billing_info_hidden" value="0">

                                                    <input type="checkbox" name="use_for_billing_info"
                                                    id="use_for_billing_info"
                                                    value="1"
                                                    onchange="setBillingInfoDisablee()"
                                                    >

                                                    <label for="use_for_billing_info"
                                                           class="form--label">
                                                        <i class="fas fa-check"></i>
                                                        <span>
                                                            @lang('Shipping and billing information are the same')
                                                        </span>
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="accordion-item" id="billing_info">
                                <h2 class="accordion-header" id="billing-heading">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                            data-bs-target="#billing-collapse" aria-expanded="false"
                                            aria-controls="billing-collapse">
                                        <i class="far fa-file-alt" style="font-size:17px;"></i>
                                        <span>@lang('Billing')</span>
                                    </button>
                                </h2>

                                <div id="billing-collapse" class="accordion-collapse collapse" aria-labelledby="billing-heading"
                                     data-bs-parent="#checkout-accordion">
                                    <div class="accordion-body">
                                        <div class="row">
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label for="billing_first_name"
                                                           class="form--label mb-2">@lang('First Name')</label>
                                                    <input onFocus="this.select();" type="text" name="billing_first_name" id="billing_first_name"
                                                           class="form-control form--control"
                                                           value="{{ auth()->user() ? auth()->user()->billing_first_name	 : old('billing_first_name	') }}"
                                                           @if (auth()->user()) @endif required>
                                                </div>
                                            </div>

                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label for="billing_last_name"
                                                           class="form--label mb-2">@lang('Last Name')</label>
                                                    <input onFocus="this.select();" type="text" name="billing_last_name" id="billing_last_name"
                                                           class="form-control form--control"
                                                           value="{{ auth()->user() ? auth()->user()->billing_last_name : old('billing_last_name') }}"
                                                           @if (auth()->user()) @endif required>
                                                </div>
                                            </div>

                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label for="billing_address_1"
                                                           class="form--label mb-2">@lang('billing Address 1')</label>
                                                    <input onFocus="this.select();" type="text" name="billing_address_1" id="billing_address_1"
                                                           class="form-control form--control"
                                                           value="{{ auth()->user() ? auth()->user()->billing_address_1 : old('billing_address_1') }}"
                                                           @if (auth()->user()) @endif required>
                                                </div>
                                            </div>

                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label for="billing_address_2"
                                                           class="form--label mb-2">@lang('billing Address 2')</label>
                                                    <input onFocus="this.select();" type="text" name="billing_address_2" id="billing_address_2"
                                                           class="form-control form--control"
                                                           value="{{ auth()->user() ? auth()->user()->billing_address_2 : old('billing_address_2') }}"
                                                           @if (auth()->user()) @endif>
                                                </div>
                                            </div>

                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label for="billing_city"
                                                           class="form--label mb-2">@lang('billing city')</label>
                                                    <input onFocus="this.select();" type="text" name="billing_city" id="billing_city"
                                                           class="form-control form--control"
                                                           value="{{ auth()->user() ? auth()->user()->billing_city : old('billing_city') }}"
                                                           @if (auth()->user()) @endif required>
                                                </div>
                                            </div>

                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label for="billing_state"
                                                           class="form--label mb-2">@lang('billing state')</label>
                                                    <input onFocus="this.select();" type="text" name="billing_state" id="billing_state"
                                                           class="form-control form--control"
                                                           value="{{ auth()->user() ? auth()->user()->billing_state : old('billing_state') }}"
                                                           @if (auth()->user()) @endif required>
                                                </div>
                                            </div>

                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label for="billing_postcode" class="form--label mb-2">
                                                        @lang('billing postcode')
                                                    </label>

                                                    <input onFocus="this.select();" type="text" name="billing_postcode" id="billing_postcode"
                                                           class="form-control form--control"
                                                           value="{{ auth()->user() ? auth()->user()->billing_postcode : old('billing_postcode') }}"
                                                           @if (auth()->user()) @endif required>
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label for="billing_country" class="form--label mb-2">
                                                           @lang('billing country')
                                                    </label>

                                                    {{--                                                    <input onFocus="this.select();" type="text" name="billing_country" id="billing_country"--}}
                                                    {{--                                                           class="form-control form--control"--}}
                                                    {{--                                                           value="{{ auth()->user() ? auth()->user()->billing_country : old('billing_country') }}"--}}
                                                    {{--                                                           @if (auth()->user()) @endif required>--}}

                                                    <div class="select-wrapper w-100">
                                                        <select name="billing_country" id="billing_country"
                                                            class="custom-select-element" required>
                                                            <option value=""></option>
                                                            @foreach($countries as $key => $country)
                                                                <option data-mobile_code="{{ $country->dial_code }}"
                                                                        value="{{ $country->id }}"
                                                                        data-code="{{ $key }}"
                                                                        @if($country->id ==  auth()->user()->billing_country) selected @endif>{{ __($country->Name) }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label for="billing_phone"
                                                           class="form--label mb-2">@lang('phone')</label>
                                                    <input onFocus="this.select();" type="text" name="billing_phone" id="billing_phone"
                                                           class="form-control form--control"
                                                           value="{{ auth()->user() ? auth()->user()->billing_phone : old('billing_phone') }}"
                                                           @if (auth()->user()) @endif required>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="accordion-item" style="display:none;">
                                <h2 class="accordion-header" id="delivery-heading">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                            data-bs-target="#delivery-collapse" aria-expanded="false"
                                            aria-controls="delivery-collapse">
                                        @lang('Delivery Preferences')
                                    </button>
                                </h2>

                                <div id="delivery-collapse" class="accordion-collapse collapse"
                                     aria-labelledby="delivery-heading"
                                     data-bs-parent="#checkout-accordion">
                                    <div class="accordion-body">
                                        <div class="form--group col-md-12">
                                            <label
                                                class="form--label-2">@lang('Which of the following best describes your business?')
                                            </label>
                                            <select name="describes_business" class="form-control">
                                                <option value="" disabled selected hidden>@lang("Please Select")</option>
                                                <option value="Cafe"
                                                        @if(auth()->user()->describes_business == "Cafe") selected @endif>
                                                    @lang('Cafe')
                                                </option>
                                                <option value="Roaster"
                                                        @if(auth()->user()->describes_business == "Roaster") selected @endif>
                                                    @lang('Roaster')
                                                </option>
                                                <option value="Importer"
                                                        @if(auth()->user()->describes_business == "Importer") selected @endif>
                                                    @lang('Importer')
                                                </option>
                                                <option value="Other"
                                                        @if(auth()->user()->describes_business == "Other") selected @endif>
                                                    @lang('Other')
                                                </option>
                                            </select>

                                        </div>

                                        <div class="form--group col-md-12">
                                            <label
                                                class="form--label-2">@lang('How many pounds (lbs) of green coffee do you buy each year?')</label>
                                            <select name="pounds_green_coffee" class="form-control">
                                                <option value="" disabled selected hidden>@lang("Please Select")</option>
                                                <option value="Less Than 10,000 lbs"
                                                        @if(auth()->user()->pounds_green_coffee == "Less Than 10,000 lbs") selected @endif>
                                                    @lang('Less Than 10,000 lbs')
                                                </option>
                                                <option value="10,000 to 100,000"
                                                        @if(auth()->user()->pounds_green_coffee == "10,000 to 100,000") selected @endif>
                                                    @lang('10,000 to 100,000')
                                                </option>
                                                <option value="100,000 to 1,000,000"
                                                        @if(auth()->user()->pounds_green_coffee == "100,000 to 1,000,000") selected @endif>
                                                    @lang('100,000 to 1,000,000')
                                                </option>
                                                <option value="1,000,000 +"
                                                        @if(auth()->user()->pounds_green_coffee == "1,000,000 +") selected @endif>
                                                    1,000,000 +
                                                </option>
                                            </select>
                                        </div>


                                        <div class="form--group col-md-12">
                                            <label
                                                class="form--label-2">@lang('Would you be interested in hosting or attending a cupping of Marketplace coffees?')</label>
                                            <select name="hosting_cupping" class="form-control">
                                                <option value="" disabled selected hidden>@lang("Please Select")</option>
                                                <option value="Hosting"
                                                        @if(auth()->user()->hosting_cupping == "Hosting") selected @endif>
                                                    @lang('Hosting')
                                                </option>
                                                <option value="Attending"
                                                        @if(auth()->user()->hosting_cupping == "Attending") selected @endif>
                                                    @lang('Attending')
                                                </option>
                                            </select>

                                        </div>
                                        <div class="form--group col-md-12">
                                            <label
                                                class="form--label-2">@lang('Delivery: Do you need any of the following?')</label>
                                            <select name="delivery" class="form-control">
                                                <option value="" disabled selected hidden>@lang("Please Select")</option>
                                                <option value="Lift gate delivery"
                                                        @if(auth()->user()->delivery == "Lift gate delivery") selected @endif>
                                                    @lang('Lift gate delivery')
                                                </option>
                                                <option value="Inside delivery"
                                                        @if(auth()->user()->delivery == "Inside delivery") selected @endif>
                                                    @lang('Inside delivery')
                                                </option>
                                                <option value="Appointment Request"
                                                        @if(auth()->user()->delivery == "Appointment Request") selected @endif>
                                                   @lang('Appointment Request')
                                                </option>
                                                <option value="Notify Request"
                                                        @if(auth()->user()->delivery == "Notify Request") selected @endif>
                                                    @lang('Notify Request')
                                                </option>
                                                <option value="None of the above"
                                                        @if(auth()->user()->delivery == "None of the above") selected @endif>
                                                    @lang('None of the above')
                                                </option>
                                            </select>
                                        </div>

                                        <div class="form--group col-md-12">
                                            <label
                                                class="form--label-2">@lang('Preferred receiving days (check all that apply)')</label>
                                            <div>
                                                <input type="checkbox" name=preferred_receiving_day[] value="Monday"
                                                       @if(in_array("Monday",is_array(json_decode(auth()->user()->preferred_receiving_day))?json_decode(auth()->user()->preferred_receiving_day):[])) selected @endif>
                                                Monday
                                                <input type="checkbox" name=preferred_receiving_day[] value="Tuesday"
                                                       @if(in_array("Tuesday",is_array(json_decode(auth()->user()->preferred_receiving_day))?json_decode(auth()->user()->preferred_receiving_day):[])) selected @endif>
                                                Tuesday
                                                <input type="checkbox" name=preferred_receiving_day[] value="Wednesday"
                                                       @if(in_array("Wednesday",is_array(json_decode(auth()->user()->preferred_receiving_day))?json_decode(auth()->user()->preferred_receiving_day):[])) selected @endif>
                                                Wednesday
                                                <input type="checkbox" name=preferred_receiving_day[] value="Thursday"
                                                       @if(in_array("Thursday",is_array(json_decode(auth()->user()->preferred_receiving_day))?json_decode(auth()->user()->preferred_receiving_day):[])) selected @endif>
                                                Thursday
                                                <input type="checkbox" name=preferred_receiving_day[] value="Friday"
                                                       @if(in_array("Friday",is_array(json_decode(auth()->user()->preferred_receiving_day))?json_decode(auth()->user()->preferred_receiving_day):[])) selected @endif>
                                                Friday
                                            </div>
                                        <!-- <select name="preferred_receiving_day[]" class="form-control" multiple>
                                                <option value="">@lang("Please Select")</option>
                                                <option value="Monday"
                                                        @if(in_array("Monday",is_array(json_decode(auth()->user()->preferred_receiving_day))?json_decode(auth()->user()->preferred_receiving_day):[])) selected @endif>
                                                    Monday
                                                </option>
                                                <option value="Tuesday"
                                                        @if(in_array("Tuesday",is_array(json_decode(auth()->user()->preferred_receiving_day))?json_decode(auth()->user()->preferred_receiving_day):[])) selected @endif>
                                                    Tuesday
                                                </option>
                                                <option value="Wednesday"
                                                        @if(in_array("Wednesday",is_array(json_decode(auth()->user()->preferred_receiving_day))?json_decode(auth()->user()->preferred_receiving_day):[])) selected @endif>
                                                    Wednesday
                                                </option>
                                                <option value="Thursday"
                                                        @if(in_array("Thursday",is_array(json_decode(auth()->user()->preferred_receiving_day))?json_decode(auth()->user()->preferred_receiving_day):[])) selected @endif>
                                                    Thursday
                                                </option>
                                                <option value="Friday"
                                                        @if(in_array("Friday",is_array(json_decode(auth()->user()->preferred_receiving_day))?json_decode(auth()->user()->preferred_receiving_day):[])) selected @endif>
                                                    Friday
                                                </option>
                                            </select> -->

                                        </div>


                                        <div class="form--group col-md-12">
                                            <label
                                                class="form--label-2">@lang('Preferred receiving times (check all that apply)')</label>
                                            <div>
                                                <input type="checkbox" name="preferred_receiving_times[]"
                                                       value="8 a.m. - 11 a.m."
                                                       @if(in_array("8 a.m. - 11 a.m.",is_array(json_decode(auth()->user()->preferred_receiving_times))?json_decode(auth()->user()->preferred_receiving_times):[])) selected @endif>
                                                8 a.m. - 11 a.m.
                                                <input type="checkbox" name="preferred_receiving_times[]"
                                                       value="11 a.m. - 2 p.m."
                                                       @if(in_array("11 a.m. - 2 p.m.",is_array(json_decode(auth()->user()->preferred_receiving_times))?json_decode(auth()->user()->preferred_receiving_times):[])) selected @endif>
                                                11 a.m. - 2 p.m.
                                                <input type="checkbox" name="preferred_receiving_times[]"
                                                       value="2 p.m. - 5 p.m."
                                                       @if(in_array("2 p.m. - 5 p.m.",is_array(json_decode(auth()->user()->preferred_receiving_times))?json_decode(auth()->user()->preferred_receiving_times):[])) selected @endif >
                                                2 p.m. - 5 p.m.
                                            </div>

                                        <!-- <select name="preferred_receiving_times[]" class="form-control" multiple>
                                                <option value="">@lang("Please Select")</option>
                                                <option value="8 a.m. - 11 a.m."
                                                        @if(in_array("8 a.m. - 11 a.m.",is_array(json_decode(auth()->user()->preferred_receiving_times))?json_decode(auth()->user()->preferred_receiving_times):[])) selected @endif>
                                                    8 a.m. - 11 a.m.
                                                </option>
                                                <option value="11 a.m. - 2 p.m."
                                                        @if(in_array("11 a.m. - 2 p.m.",is_array(json_decode(auth()->user()->preferred_receiving_times))?json_decode(auth()->user()->preferred_receiving_times):[])) selected @endif>
                                                    11 a.m. - 2 p.m.
                                                </option>
                                                <option value="2 p.m. - 5 p.m."
                                                        @if(in_array("2 p.m. - 5 p.m.",is_array(json_decode(auth()->user()->preferred_receiving_times))?json_decode(auth()->user()->preferred_receiving_times):[])) selected @endif>
                                                    2 p.m. - 5 p.m.
                                                </option>
                                            </select> -->

                                        </div>

                                        <div class="form--group col-md-12">
                                            <label
                                                class="form--label-2">@lang('EIN Number (US Businesses Only)')</label>
                                            <textarea id="other_special_delivery" name="other_special_delivery"
                                                      class="form-control"
                                                      autocomplete="off">{{ auth()->user()->other_special_delivery }}</textarea>
                                        </div>


                                    </div>
                                </div>
                            </div>
                            <div class="accordion-item border-0">
                                <h2 class="accordion-header" id="payment-heading">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                            data-bs-target="#payment-collapse" aria-expanded="false"
                                            aria-controls="payment-collapse">
                                        <i class="far fa-credit-card "></i>
                                        <span>@lang('Payment')</span>
                                    </button>
                                </h2>
                                <div id="payment-collapse" class="accordion-collapse collapse"
                                     aria-labelledby="payment-heading"
                                     data-bs-parent="#checkout-accordion">
                                    <div class="accordion-body">
                                        <div class="form--group col-md-12">
                                            <label class="form--label-2">@lang('Payment Method')</label>

                                            <div class="select-wrapper w-50">
                                                <select name="payment_method" id="payment_method" class="custom-select-element" required>
                                                    <option value="Stripe" selected>
                                                        @lang('Credit Card')
                                                    </option>

                                                    <option value="Wise">
                                                        @lang('Bank Transfer (international wire)')
                                                    </option>

                                                    <option value="bank">
                                                        @lang('Bank Transfer (No Payment Processing)')
                                                    </option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="border-element"></div>
                            </div>

                            <div class="submit-btn-container">
                                @csrf
                                <input class="submit-btn" type="submit" value="@lang('Save and checkout')"/>
                            </div>
                        </div>

                    </section>
                    <!-- End Order Steps -->

                    <!-- Start Order Summary Cart -->
                    <section class="col-xl-5 order-summary">
                        <div class="cart">
                            <header class="cart-header">
                                <h5>@lang('Order Summary')</h5>
                            </header>

                            <div class="cart-items">
                                @forelse ($winningHistories as $key => $winner)
                                    <div class="cart-item">
                                        {{--<p>{{$key}} @lang('Item')</p>--}}
                                        <div>
                                            <div class="cart-item-description">
                                                <img
                                                    src="{{getImage(imagePath()['product']['path'].'/thumb_'.$winner->product->image,imagePath()['product']['thumb'])}}"
                                                    alt="Item 1">
                                                <div>
                                                    <p>{{ $winner->product->name }}
                                                    </p>

                                                    <span>
                                                        {{ $winningHistories->firstItem() + $loop->index
                                                        }}
                                                    </span>
                                                </div>
                                            </div>
                                            <span
                                                class="cart-item-price">
                                                {{ $general->cur_sym }}{{ showAmount($winner->bid->amount * $winner->bid->product->weight) }}</span>
                                        </div>
                                    </div>
                                @empty
                                    <tr>
                                        <td class="text-muted text-center" colspan="100%">{{ __($emptyMessage) }}</td>
                                    </tr>
                                @endforelse
                            </div>
                            <div class="cart-order-info">
                                <div>
                                    <p>@lang('Subtotal')</p>
                                    <span class="num-value">{{ $general->cur_sym }}{{showAmount($sub_total)}}</span>
                                </div>

                                <div class="mt-4 mb-2">
                                    <p>@lang('Shipping and Handling')</p>
                                </div>

                                <div class="shipping-method-cost mb-4">
                                    <div class="select-wrapper">
                                        <select name="shipping_method" class="custom-select-element" required id="shipping_method">
                                        </select>
                                    </div>

                                    <span class="flex-shrink-0 num-value">
                                        {{ $general->cur_sym }}
                                        <span id="total_shipping_price">
                                        </span>
                                    </span>
                                </div>

                                <div>
                                    <p>@lang('Tax')</p>
                                    <div class="num-value">
                                        <span>{{ $general->cur_sym }}</span>
                                        <span>0</span>
                                    </div>
                                </div>

                                {{--                        <button>Coupon/Gift</button>--}}
                            </div>

                            <footer class="cart-footer">
                                <span class="total-label">Total (USD)</span>
                                <span class="price-label">{{ $general->cur_sym }}<span id="total_price"></span></span>
                            </footer>
                        </div>
                    </section>
                    <!-- End Order Summary Cart -->
                </main>
                <!-- End Main -->
            </div>
        </form>

    @else
        <div class="product-section">
            <div class="container">
                <h2 class="text-center m-0">@lang('No winning history found')</h2>
            </div>
        </div>
    @endif

    </section>
@endsection


@if (!is_null($winningHistories) && count($winningHistories) > 0)

    @push('script')
    <script>
        (function ($) {
            function formatState(state) {
            if (!state.id) {
                return state.text;
            }

            var $state = $(
                `<span class="custom-select-option">
                    <svg width="14" height="12" viewBox="0 0 14 12" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M11.8017 0.716385C12.0425 0.481382 12.363 0.351463 12.6955 0.354042C13.028 0.35662 13.3465 0.491496 13.5838 0.730207C13.8211 0.968919 13.9587 1.29279 13.9675 1.6335C13.9762 1.9742 13.8556 2.30507 13.6309 2.5563L6.80997 11.3003C6.69268 11.4298 6.55113 11.5337 6.39376 11.6058C6.2364 11.678 6.06646 11.7168 5.89411 11.7201C5.72176 11.7234 5.55053 11.691 5.39067 11.6249C5.23081 11.5588 5.08559 11.4603 4.96371 11.3353L0.44036 6.69875C0.314393 6.57843 0.213357 6.43334 0.143281 6.27213C0.0732054 6.11092 0.0355246 5.93689 0.0324871 5.76043C0.0294497 5.58397 0.0611181 5.40869 0.125602 5.24504C0.190086 5.0814 0.286066 4.93274 0.407815 4.80795C0.529563 4.68315 0.674587 4.58477 0.834234 4.51867C0.993882 4.45257 1.16488 4.42011 1.33703 4.42322C1.50919 4.42633 1.67896 4.46496 1.83624 4.53679C1.99351 4.60862 2.13506 4.71218 2.25243 4.84131L5.83214 8.50888L11.7692 0.754936C11.7799 0.741445 11.7913 0.728574 11.8034 0.716385H11.8017Z" fill="black"/>
                    </svg>
                    ` +
                state.text +
                "</span>"
            );

            return $state;
            }

            $('.custom-select-element').select2({
                placeholder: "Select an option",
                minimumResultsForSearch: Infinity,
                selectionCssClass: "custom-selection",
                dropdownCssClass: "custom-dropdown",
                templateResult: formatState,
            });
        })(jQuery);
    </script>
    @endpush

    @push('script')
        <script>
            function setBillingInfoDisablee() {
                //set Billing Info Disable When use_for_billing_info Active and clone data from shipping info
                if (!$('#use_for_billing_info').is(':checked')) {
                    //
                    // $('#billing_first_name').val($('#shipping_first_name').val());
                    // $('#billing_last_name').val($('#shipping_last_name').val());
                    // $('#billing_email').val($('#shipping_email').val());
                    // $('#billing_phone').val($('#shipping_phone').val());
                    // $('#billing_address_1').val($('#shipping_address_1').val());
                    // $('#billing_address_2').val($('#shipping_address_2').val());
                    // $('#billing_city').val($('#shipping_city').val());
                    // $('#billing_state').val($('#shipping_state').val());
                    // $('#billing_zip').val($('#shipping_zip').val());
                    // $('#billing_country').val($('#shipping_country').val());

                    $('#billing_first_name').attr('required', true);
                    $('#billing_last_name').attr('required', true);
                    $('#billing_email').attr('required', true);
                    $('#billing_phone').attr('required', true);
                    $('#billing_address_1').attr('required', true);
                    $('#billing_address_2').attr('required', true);
                    $('#billing_city').attr('required', true);
                    $('#billing_state').attr('required', true);
                    $('#billing_postcode').attr('required', true);
                    $('#billing_country').attr('required', true);
                    $('#billing_info').attr('style', 'display:block');
                    $('#use_for_billing_info_hidden').removeAttr("disabled");
                } else {
                    $('#billing_first_name').attr('required', false);
                    $('#billing_last_name').attr('required', false);
                    $('#billing_email').attr('required', false);
                    $('#billing_phone').attr('required', false);
                    $('#billing_address_1').attr('required', false);
                    $('#billing_address_2').attr('required', false);
                    $('#billing_city').attr('required', false);
                    $('#billing_state').attr('required', false);
                    $('#billing_postcode').attr('required', false);
                    $('#billing_country').attr('required', false);
                    $('#billing_info').attr('style', 'display:none');
                    $('#use_for_billing_info_hidden').attr("disabled","true");
                }
            }

            function get_shipping_methods(){
                // debugger;
                var url = `{{ route('user.checkout.get_supported_shipping_method') }}`;
                var data = {
                    '_token': `{{csrf_token()}}`,
                    'event_id': `{{ array_values($event_ids)[0] }}`,
                    'country_id': $('select[name=shipping_country] :selected').val()
                };
                $.ajax({
                    type: "POST",
                    url: url,
                    data: data,
                    success: function (response) {
                        // debugger;
                        $('#shipping_method').html("");

                        $('#total_shipping_price').html("");

                        $('#shipping_method').append("<option value=''></option>");
                        response.methods.forEach(function (item) {
                            $('#shipping_method').append("<option value='" + item.id + "'>" + item.shipping_method + "</option>");
                        });
                        $('#shipping_method').append("<option value='-1'>I will manage my own shipping</option>");
                        $('#shipping_method').append("<option value='-2'>I am part of a bidding group</option>");
                        $('#shipping_method').append("<option value='-3'>Air Freight (Request Quote)</option>");
                    },
                    error: function (error) {
                        $('#shipping_method').html("");
                        $('#shipping_method').append("<option value=''></option>");
                        $('#shipping_method').append("<option value='-1'>I will manage my own shipping</option>");
                        $('#shipping_method').append("<option value='-2'>I am part of a bidding group</option>");
                        $('#shipping_method').append("<option value='-3'>Air Freight (Request Quote)</option>");

                        $('#total_shipping_price').html("");
                        $('#total_price').html("");
                    }
                })
            }

            get_shipping_methods();
            $('#shipping_country').change(get_shipping_methods);

            $('#shipping_method').change(get_shipping_and_handling_price);
            $('#payment_method').change(get_shipping_and_handling_price);

            function get_shipping_and_handling_price(){
                // debugger;
                var url = `{{ route('user.checkout.get_shipping_price') }}`;
                var data = {
                    '_token': `{{csrf_token()}}`,
                    'event_id': `{{ array_values($event_ids)[0] }}`,
                    'shipping_region_id': $('select[name=shipping_method] :selected').val(),
                    'shipping_country_id': $('select[name=shipping_country] :selected').val(),
                    'payment_method': $('select[name=payment_method] :selected').val(),
                };
                $.ajax({
                    type: "POST",
                    url: url,
                    data: data,
                    success: function (response) {
                        // debugger;
                        $('#total_shipping_price').html(response.total_shipping_price);
                        $('#total_price').html(response.total_price);
                    },
                    error: function (error) {
                        // debugger;
                        $('#total_shipping_price').html("");
                        $('#total_price').html("");
                    }
                })
            }

            get_shipping_and_handling_price();
        </script>

    @endpush
@endif

