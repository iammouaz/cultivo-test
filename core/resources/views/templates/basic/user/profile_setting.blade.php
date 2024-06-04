@extends($activeTemplate.'layouts.master')
@php
    $register = getContent('register.content', true);
    $policyPages = getContent('policy_pages.element');
@endphp
@section('content')
    <div class="ticket__wrapper bg--section">
        <div class="profile-wrapper">
            <form action="" method="post" enctype="multipart/form-data" class="row mb--25">
               {{ csrf_field() }}
                <div class="profile-user mb-xl-0">
                    <div class="thumb">
                        <img
                            src="{{ getImage(imagePath()['profile']['user']['path'] . '/' . auth()->user()->image, imagePath()['profile']['user']['size']) }}"
                            alt="user">
                    </div>
                    <div class="content">
                        <h6 class="title">@lang('Name'): {{ $user->fullname}}</h6>
                        <span class="subtitle">@lang('Username'): {{ $user->username }}</span>
                        <div class="mt-4">
                            <label class="btn btn--primary" for="profile-image">@lang('Update Profile Picture')</label>
                            <input type="file" name="image" class="form-control form--control" id="profile-image"
                                   hidden>
                        </div>
                    </div>
                    <div class="remove-image">
                        <i class="las la-times"></i>
                    </div>
                </div>
                <div class="profile-form-area row">
                    <div class="form--group col-md-12">
                        <label class="form--label-2" for="first-name">@lang('First Name')<span
                                style="color: red">*</span></label>
                        <input type="text" class="form-control form--control-2" id="first-name" name="firstname"
                               required
                               value="{{ auth()->user()->firstname }}">
                    </div>
                    <div class="form--group col-md-12">
                        <label class="form--label-2" for="last-name">@lang('Last Name')<span
                                style="color: red">*</span></label>
                        <input type="text" class="form-control form--control-2" id="last-name" name="lastname" required
                               value="{{ auth()->user()->lastname }}">
                    </div>
                    <div class="form--group col-md-12">
                        <label class="form--label-2" for="email">@lang('Email ')<span
                                style="color: red">*</span></label>
                        <input type="text" class="form-control form--control-2" id="email" required name="email"
                               value="{{ auth()->user()->email }}">
                        {{--                    </div>--}}
                        {{--                    <div class="form--group col-md-6">--}}
                        {{--                        <label class="form--label-2" for="mobile">@lang('Mobile ')<span style="color: red">*</span></label>--}}
                        {{--                        <input type="text" class="form-control form--control-2" id="mobile" required--}}
                        {{--                               value="{{ auth()->user()->mobile }}">--}}
                        {{--                    </div>--}}


                    </div>


                    {{--                <div class="profile-user mb-xl-0" style="margin-top: 50px">--}}
                    {{--                    <div class="content">--}}
                    {{--                        <h5 class="title">@lang('Password')</h5>--}}
                    {{--                    </div>--}}
                    {{--                </div>--}}

                    {{--                <div class="profile-form-area row " style="margin-top: 50px">--}}

                    {{--                    <div class="form--group col-md-6">--}}
                    {{--                        <label class="form--label-2" for="password">@lang('Password')</label>--}}
                    {{--                        <input type="password" class="form-control form--control-2" id="password" name="password"--}}
                    {{--                               placeholder="*************">--}}
                    {{--                    </div>--}}


                    {{--                    <div class="form--group col-md-6">--}}
                    {{--                        <label class="form--label-2" for="confirm_password">@lang('Confirm Password')</label>--}}
                    {{--                        <input type="password" class="form-control form--control-2" id="confirm_password"--}}
                    {{--                               name="confirm_password" placeholder="*************">--}}
                    {{--                    </div>--}}
                    {{--                </div>--}}
                    {{--                <div class="profile-user mb-xl-0">--}}
                    {{--                    <div class="content">--}}
                    {{--                        <h5 class="title">@lang('Main Address')</h5>--}}
                    {{--                    </div>--}}
                    {{--                </div>--}}
                    {{--                <div class="profile-form-area row">--}}





                    {{--                    <div class="form--group col-md-6">--}}
                    {{--                        <label class="form--label-2" for="address">@lang('Address')</label>--}}
                    {{--                        <input type="text" class="form-control form--control-2" id="address" name="address"--}}
                    {{--                               value="{{ auth()->user()->address->address }}">--}}
                    {{--                    </div>--}}
                    {{--                    <div class="form--group col-md-6">--}}
                    {{--                        <label class="form--label-2" for="state">@lang('State')</label>--}}
                    {{--                        <input type="text" class="form-control form--control-2" id="state" name="state"--}}
                    {{--                               value="{{ auth()->user()->address->state }}">--}}
                    {{--                    </div>--}}
                    {{--                    <div class="form--group col-md-4">--}}
                    {{--                        <label class="form--label-2" for="city">@lang('Zip Code')</label>--}}
                    {{--                        <input type="text" class="form-control form--control-2" id="city" name="zip"--}}
                    {{--                               value="{{ auth()->user()->address->zip }}">--}}
                    {{--                    </div>--}}
                    {{--                    <div class="form--group col-md-4">--}}
                    {{--                        <label class="form--label-2" for="city">@lang('City')</label>--}}
                    {{--                        <input type="text" class="form-control form--control-2" id="city" name="city"--}}
                    {{--                               value="{{ auth()->user()->address->city }}">--}}
                    {{--                    </div>--}}
                    {{--                    <div class="form--group col-md-4">--}}
                    {{--                        <label class="form--label-2" for="country">@lang('Country')</label>--}}
                    {{--                        <input type="text" class="form-control form--control-2" id="country"--}}
                    {{--                               value="{{ auth()->user()->address->country }}" readonly>--}}
                    {{--                    </div>--}}

                    {{--                </div>--}}
                    {{--                <div class="profile-user mb-xl-0">--}}
                    {{--                    <div class="content">--}}
                    {{--                        <h5 class="title">@lang('Shipping Informations')</h5>--}}
                    {{--                    </div>--}}
                    {{--                </div>--}}
                    {{--                <!-- <input type="hidden" name="mobile_code">--}}
                    {{--                <input type="hidden" name="shipping_country"> -->--}}
                    {{--                <div class="profile-form-area row">--}}
                    {{--                    <div class="form--group col-md-6">--}}
                    {{--                        <label class="form--label-2" for="shipping_first_name">@lang('shipping_first_name')</label>--}}
                    {{--                        <input type="text" class="form-control form--control-2" id="shipping_first_name"--}}
                    {{--                               name="shipping_first_name" value="{{ auth()->user()->shipping_first_name }}">--}}
                    {{--                    </div>--}}
                    {{--                    <div class="form--group col-md-6">--}}
                    {{--                        <label class="form--label-2" for="shipping_last_name">@lang('shipping_last_name')</label>--}}
                    {{--                        <input type="text" class="form-control form--control-2" id="shipping_last_name"--}}
                    {{--                               name="shipping_last_name" value="{{ auth()->user()->shipping_last_name }}">--}}
                    {{--                    </div>--}}
                    {{--                    <div class="form--group col-md-6">--}}
                    {{--                        <label class="form--label-2" for="billing_company_name">@lang('Billing company name')</label>--}}
                    {{--                        <input type="text" class="form-control form--control-2" id="billing_company_name"--}}
                    {{--                               name="billing_company_name" value="{{ auth()->user()->billing_company_name }}">--}}
                    {{--                    </div>--}}
                    {{--                    <div class="form--group col-md-6">--}}
                    {{--                        <label class="form--label-2" for="shipping_company">@lang('shipping_company')</label>--}}
                    {{--                        <input type="text" class="form-control form--control-2" id="shipping_company"--}}
                    {{--                               name="shipping_company" value="{{ auth()->user()->shipping_company }}">--}}
                    {{--                    </div>--}}
                    {{--                    <div class="form--group col-md-6">--}}
                    {{--                        <label class="form--label-2" for="shipping_address_1">@lang('shipping_address_1')</label>--}}
                    {{--                        <input type="text" class="form-control form--control-2" id="shipping_address_1"--}}
                    {{--                               name="shipping_address_1" value="{{ auth()->user()->shipping_address_1 }}">--}}
                    {{--                    </div>--}}
                    {{--                    <div class="form--group col-md-6">--}}
                    {{--                        <label class="form--label-2" for="shipping_address_2">@lang('shipping_address_2')</label>--}}
                    {{--                        <input type="text" class="form-control form--control-2" id="shipping_address_2"--}}
                    {{--                               name="shipping_address_2" value="{{ auth()->user()->shipping_address_2 }}">--}}
                    {{--                    </div>--}}
                    {{--                    <div class="form--group col-md-6">--}}
                    {{--                        <label class="form--label-2" for="shipping_state">@lang('shipping_state')</label>--}}
                    {{--                        <input type="text" class="form-control form--control-2" id="shipping_state"--}}
                    {{--                               name="shipping_state" value="{{ auth()->user()->shipping_state }}">--}}
                    {{--                    </div>--}}
                    {{--                    <div class="form--group col-md-4">--}}
                    {{--                        <label class="form--label-2" for="shipping_postcode">@lang('shipping_postcode')</label>--}}
                    {{--                        <input type="text" class="form-control form--control-2" id="shipping_postcode"--}}
                    {{--                               name="shipping_postcode" value="{{ auth()->user()->shipping_postcode }}">--}}
                    {{--                    </div>--}}
                    {{--                    <div class="form--group col-md-4">--}}
                    {{--                        <label class="form--label-2" for="shipping_city">@lang('shipping_city')</label>--}}
                    {{--                        <input type="text" class="form-control form--control-2" id="shipping_city" name="shipping_city"--}}
                    {{--                               value="{{ auth()->user()->shipping_city }}">--}}
                    {{--                    </div>--}}
                    {{--                    <div class="form--group col-md-4">--}}
                    {{--                        <label class="form--label-2" for="shipping_country">@lang('shipping_country')</label>--}}
                    {{--                        <select name="shipping_country" id="shipping_country" class="form-control">--}}
                    {{--                            @foreach($countries as $key => $country)--}}
                    {{--                                <option data-mobile_code="{{ $country->dial_code }}" value="{{ $country->country }}"--}}
                    {{--                                        data-code="{{ $key }}">{{ $country->country }}</option>--}}
                    {{--                            @endforeach--}}
                    {{--                        </select>--}}
                    {{--                    </div>--}}
                    {{--                    <div class="form--group col-md-4">--}}
                    {{--                        <label for="shipping_phone">@lang('shipping_phone')</label>--}}
                    {{--                        <div class="input-group">--}}
                    {{--                            <span class="input-group-text mobile-code bg--base text-white border-0"></span>--}}
                    {{--                            <input type="tel" id="shipping_phone" name="shipping_phone"--}}
                    {{--                                   value="{{ auth()->user()->shipping_phone }}" class="form-control checkUser"--}}
                    {{--                                   autocomplete="off">--}}
                    {{--                        </div>--}}
                    {{--                    </div>--}}

                </div>
                <hr style="margin-top: 20px">
                <div class="profile-user mb-xl-0" style="margin-top: 20px">
                    <div class="content">
                        <h5 class="title">@lang('Company Details')</h5>
                    </div>
                </div>

                <!-- <input type="hidden" name="mobile_code">
                <input type="hidden" name="shipping_country"> -->
                <div class="profile-form-area row">
                    <div class="form--group col-md-6">
                        <label class="form--label-2" for="billing_first_name">@lang('Company Name')<span
                                style="color: red"> * </span></label>
                        <input type="text" class="form-control form--control-2" id="company_name" required
                               name="company_name" value="{{ auth()->user()->company_name }}">
                    </div>
                    <div class="form--group col-md-6">
                        <label class="form--label-2" for="billing_last_name">@lang('Company website')<span
                                style="color: red">*</span></label>
                        <input type="text" class="form-control form--control-2" id="company_website" required
                               name="company_website" value="{{ auth()->user()->company_website }}">
                    </div>

                    <div class="form--group col-md-6">
                        <label class="form--label-2" for="billing_address_1">@lang('Address Line 1')</label>
                        <input type="text" class="form-control form--control-2" id="billing_address_1"
                               name="billing_address_1" value="{{ auth()->user()->billing_address_1 }}">
                    </div>
                    <div class="form--group col-md-6">
                        <label class="form--label-2" for="billing_address_2">@lang('Address Line 2')</label>
                        <input type="text" class="form-control form--control-2" id="billing_address_2"
                               name="billing_address_2" value="{{ auth()->user()->billing_address_2 }}">
                    </div>
                    <div class="form--group col-md-6">
                        <label class="form--label-2" for="billing_state">@lang('state')</label>
                        <input type="text" class="form-control form--control-2" id="billing_state" name="billing_state"
                               value="{{ auth()->user()->billing_state }}">
                    </div>
                    <div class="form--group col-md-6">
                        <label class="form--label-2" for="billing_postcode">@lang('postcode')</label>
                        <input type="text" class="form-control form--control-2" id="billing_postcode"
                               name="billing_postcode" value="{{ auth()->user()->billing_postcode }}">
                    </div>
                    <div class="form--group col-md-6">
                        <label class="form--label-2" for="billing_city">@lang('city')</label>
                        <input type="text" class="form-control form--control-2" id="billing_city" name="billing_city"
                               value="{{ auth()->user()->billing_city }}">
                    </div>
                    <div class="form--group col-md-6">
                        <label class="form--label-2" for="billing_country">@lang('country')</label>
                        <select name="billing_country" id="billing_country" class="form-control form--control-2">
                            @foreach($countries as $key => $country)
                                <option data-mobile_code="{{ $country->dial_code }}" value="{{ $country->id }}"
                                        data-code="{{ $key }}"
                                        @if($country->id ==  auth()->user()->billing_country) selected @endif>{{ $country->Name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form--group col-md-6">
                        <label for="billing_phone">@lang('Phone') <span style="color: red">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text billing-mobile-code bg--base text-white border-0"></span>
                            <input type="tel" id="billing_phone" name="billing_phone" required
                                   value="{{ auth()->user()->billing_phone }}"
                                   class="form-control form--control-2 checkUser"
                                   autocomplete="off">
                        </div>
                    </div>

                </div>
                <hr style="margin-top: 20px">

                <div class="profile-user mb-xl-0" style="margin-top: 20px">
                    <div class="content">
                        <h5 class="title">@lang('More Informations')</h5>
                    </div>
                </div>
                <div class="profile-form-area row">

                    <div class="form--group col-md-12">
                        <label
                            class="form--label-2">@lang('Which of the following best describes your business?')</label>
                        <select name="describes_business" class="form-control">
                            <option value="">@lang("Please Select")</option>
                            <option value="Cafe" @if(auth()->user()->describes_business == "Cafe") selected @endif>
                                Cafe
                            </option>
                            <option value="Roaster"
                                    @if(auth()->user()->describes_business == "Roaster") selected @endif>Roaster
                            </option>
                            <option value="Importer"
                                    @if(auth()->user()->describes_business == "Importer") selected @endif>Importer
                            </option>
                            <option value="Other" @if(auth()->user()->describes_business == "Other") selected @endif>
                                Other
                            </option>
                        </select>

                    </div>

                    <div class="form--group col-md-12">
                        <label
                            class="form--label-2">@lang('How many pounds (lbs) of green coffee do you buy each year?')</label>
                        <select name="pounds_green_coffee" class="form-control">
                            <option value="">@lang("Please Select")</option>
                            <option value="Less Than 10,000 lbs"
                                    @if(auth()->user()->pounds_green_coffee == "Less Than 10,000 lbs") selected @endif>
                                Less Than 10,000 lbs
                            </option>
                            <option value="10,000 to 100,000"
                                    @if(auth()->user()->pounds_green_coffee == "10,000 to 100,000") selected @endif>
                                10,000 to 100,000
                            </option>
                            <option value="100,000 to 1,000,000"
                                    @if(auth()->user()->pounds_green_coffee == "100,000 to 1,000,000") selected @endif>
                                100,000 to 1,000,000
                            </option>
                            <option value="1,000,000 +"
                                    @if(auth()->user()->pounds_green_coffee == "1,000,000 +") selected @endif>1,000,000
                                +
                            </option>
                        </select>

                    </div>


                    <div class="form--group col-md-12">
                        <label
                            class="form--label-2">@lang('Would you be interested in hosting or attending a cupping of Marketplace coffees?')</label>
                        <select name="hosting_cupping" class="form-control">
                            <option value="">@lang("Please Select")</option>
                            <option value="Hosting" @if(auth()->user()->hosting_cupping == "Hosting") selected @endif>
                                Hosting
                            </option>
                            <option value="Attending"
                                    @if(auth()->user()->hosting_cupping == "Attending") selected @endif>Attending
                            </option>
                        </select>

                    </div>


                    {{--                    <div class="form--group col-md-12">--}}
                    {{--                        <label class="form--label-2">@lang('Delivery: Do you need any of the following?')</label>--}}
                    {{--                        <select name="delivery" class="form-control">--}}
                    {{--                            <option value="">@lang("Please Select")</option>--}}
                    {{--                            <option value="Lift gate delivery" @if(auth()->user()->delivery == "Lift gate delivery") selected @endif>Lift gate delivery</option>--}}
                    {{--                            <option value="Inside delivery" @if(auth()->user()->delivery == "Inside delivery") selected @endif>Inside delivery</option>--}}
                    {{--                            <option value="Appointment Request" @if(auth()->user()->delivery == "Appointment Request") selected @endif>Appointment Request</option>--}}
                    {{--                            <option value="Notify Request" @if(auth()->user()->delivery == "Notify Request") selected @endif>Notify Request</option>--}}
                    {{--                            <option value="None of the above" @if(auth()->user()->delivery == "None of the above") selected @endif>None of the above</option>--}}
                    {{--                        </select>--}}

                    {{--                    </div>--}}


                    {{--                    <div class="form--group col-md-12">--}}
                    {{--                        <label class="form--label-2">@lang('Preferred receiving days (check all that apply)')</label>--}}
                    {{--                        <select name="preferred_receiving_day[]" class="form-control" multiple>--}}
                    {{--                            <option value="">@lang("Please Select")</option>--}}
                    {{--                            <option value="Monday" @if(in_array("Monday",is_array(json_decode(auth()->user()->preferred_receiving_day))?json_decode(auth()->user()->preferred_receiving_day):[])) selected @endif>Monday</option>--}}
                    {{--                            <option value="Tuesday" @if(in_array("Tuesday",is_array(json_decode(auth()->user()->preferred_receiving_day))?json_decode(auth()->user()->preferred_receiving_day):[])) selected @endif>Tuesday</option>--}}
                    {{--                            <option value="Wednesday" @if(in_array("Wednesday",is_array(json_decode(auth()->user()->preferred_receiving_day))?json_decode(auth()->user()->preferred_receiving_day):[])) selected @endif>Wednesday</option>--}}
                    {{--                            <option value="Thursday" @if(in_array("Thursday",is_array(json_decode(auth()->user()->preferred_receiving_day))?json_decode(auth()->user()->preferred_receiving_day):[])) selected @endif>Thursday</option>--}}
                    {{--                            <option value="Friday" @if(in_array("Friday",is_array(json_decode(auth()->user()->preferred_receiving_day))?json_decode(auth()->user()->preferred_receiving_day):[])) selected @endif>Friday</option>--}}
                    {{--                        </select>--}}

                    {{--                    </div>--}}



                    {{--                    <div class="form--group col-md-12">--}}
                    {{--                        <label class="form--label-2">@lang('Preferred receiving times (check all that apply)')</label>--}}
                    {{--                        <select name="preferred_receiving_times[]" class="form-control" multiple>--}}
                    {{--                            <option value="">@lang("Please Select")</option>--}}
                    {{--                            <option value="8 a.m. - 11 a.m." @if(in_array("8 a.m. - 11 a.m.",is_array(json_decode(auth()->user()->preferred_receiving_times))?json_decode(auth()->user()->preferred_receiving_times):[])) selected @endif>8 a.m. - 11 a.m.</option>--}}
                    {{--                            <option value="11 a.m. - 2 p.m." @if(in_array("11 a.m. - 2 p.m.",is_array(json_decode(auth()->user()->preferred_receiving_times))?json_decode(auth()->user()->preferred_receiving_times):[])) selected @endif>11 a.m. - 2 p.m.</option>--}}
                    {{--                            <option value="2 p.m. - 5 p.m." @if(in_array("2 p.m. - 5 p.m.",is_array(json_decode(auth()->user()->preferred_receiving_times))?json_decode(auth()->user()->preferred_receiving_times):[])) selected @endif>2 p.m. - 5 p.m.</option>--}}
                    {{--                              </select>--}}

                    {{--                    </div>--}}

                    {{--                    <div class="form--group col-md-12">--}}
                    {{--                        <label class="form--label-2">@lang('EIN Number (US Businesses Only)')</label>--}}
                    {{--                        <textarea id="other_special_delivery" name="other_special_delivery"--}}
                    {{--                                class="form-control"--}}
                    {{--                                  autocomplete="off">{{ auth()->user()->other_special_delivery }}</textarea>--}}
                    {{--                    </div>--}}


                    {{--                    <div class="form--group col-md-12">--}}
                    {{--                        <label class="form--label-2">@lang('Exclusive Offers')</label>--}}
                    {{--                        <input type="checkbox" id="exclusive_offers" name="exclusive_offers" value="1"--}}
                    {{--                                 @if (auth()->user()->exclusive_offers)--}}
                    {{--                                    checked--}}
                    {{--                                @endif>--}}
                    {{--                        <label for="exclusive_offers">@lang('I would like to receive updates and offers')</label>--}}


                    {{--                    </div>--}}


                </div>
                <div class="mb-3">
                    <input type="checkbox" id="agree" name="agree" value="1" @if(auth()->user()->is_agree) checked @endif>
                    <label for="agree" class="ms-1">@lang('I agree with')
                        @foreach ($policyPages as $policyPage)
                            <a href="{{ route('policy', [$policyPage, slug($policyPage->data_values->title)]) }}" target="_blank" class="primary-color">
                                {{ $policyPage->data_values->title }}@if(!$loop->last), @endif
                            </a>
                        @endforeach
                    </label>
                </div>

                <div class="mb-3">
                    <input type="checkbox" value="1" @if(auth()->user()->opt_in) checked @endif
                    name="opt_in">
                    <label for="opt_in" class="ms-1">@lang('Opt in to sms/whatsapp updates')</label>
                </div>

                <div class="mb-3">
                    <input type="checkbox" value="1"   @if(auth()->user()->i_contacted_upcoming_auctions) checked @endif
                    name="i_contacted_upcoming_auctions">
                    <label for="agree" class="ms-1">@lang('I wish to be contacted in the future regarding upcoming auctions.')</label>
                </div>

                <div class="form--group w-100 col-md-6 mb-0 text-end">
                    <button type="submit" class="cmn--btn">@lang('Update Profile')</button>
                </div>
            </form>
        </div>
    </div>

@endsection

@push('script')
    <script>




        (function ($) {
            "use strict";
            var prevImg = $('.profile-user .thumb').html();

            function proPicURL(input) {
                if (input.files && input.files[0]) {
                    var reader = new FileReader();
                    reader.onload = function (e) {
                        var preview = $('.profile-user').find('.thumb');
                        preview.html(`<img src="${e.target.result}" alt="user">`);
                        preview.addClass('has-image');
                        preview.hide();
                        preview.fadeIn(650);
                        $(".remove-image").show();
                    }
                    reader.readAsDataURL(input.files[0]);
                }
            }

            $("#profile-image").on('change', function () {
                proPicURL(this);
            });

            $(".remove-image").on('click', function () {
                $(".profile-user .thumb").html(prevImg);
                $(".profile-user .thumb").removeClass('has-image');
                $(this).hide();
            });
            @if($mobile_code)
            $(`option[data-code={{ $mobile_code }}]`).attr('selected', '');
            @endif

            $('select[name=shipping_country]').change(function () {
                $('input[name=mobile_code]').val($('select[name=shipping_country] :selected').data('mobile_code'));
                $('input[name=country_code]').val($('select[name=shipping_country] :selected').data('code'));
                $('.mobile-code').text('+' + $('select[name=shipping_country] :selected').data('mobile_code'));
            });
            $('select[name=billing_country]').change(function () {
                $('input[name=mobile_code]').val($('select[name=billing_country] :selected').data('mobile_code'));
                $('input[name=country_code]').val($('select[name=billing_country] :selected').data('code'));
                $('.billing-mobile-code').text('+' + $('select[name=billing_country] :selected').data('mobile_code'));
            });
            $('input[name=mobile_code]').val($('select[name=shipping_country] :selected').data('mobile_code'));
            $('input[name=country_code]').val($('select[name=shipping_country] :selected').data('code'));
            $('.mobile-code').text('+' + $('select[name=shipping_country] :selected').data('mobile_code'));
            $('.billing-mobile-code').text('+' + $('select[name=billing_country] :selected').data('mobile_code'));


        })(jQuery);
    </script>
@endpush
