@extends($activeTemplate . 'layouts.frontend')

@section('content')
    <!-- Product -->
    <section class="product-section pt-120 pb-120">
        <div class="container">
            <div class="mb-4 d-lg-none">
                <div class="filter-btn ms-auto">
                    <i class="las la-filter"></i>
                </div>
            </div>
            <div class="row flex-wrap-reverse">
                <!-- <div class="col-lg-4 col-xl-3">

                    <div class="mini-banner-area mt-4">
                        <div class="mini-banner">
                            @php
                                showAd('370x670');
                            @endphp
                    </div>
                    <div class="mini-banner">
    @php
        showAd('300x250');
    @endphp
                    </div>
                </div>
            </div> -->

                <div class="col-lg-12 col-xl-12 search-result">


                    <div class="row flex-wrap-reverse">
                        <div class="col-lg-12 col-xl-12 search-result">

                            <h5>@lang('Shipping info')</h5>

                            <table>
                                <tr>
                                    <td>
                                        @lang('First Name'):
                                    </td>
                                    <td>
                                        {{ $user->firstname }}
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        @lang('Last Name') :
                                    </td>
                                    <td>
                                        {{ $user->lastname }}
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        @lang('Company Name') :
                                    </td>
                                    <td>
                                        {{ $user->company_website }}
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        @lang('Phone Number') :
                                    </td>
                                    <td>
                                        {{ $user->mobile }}
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        @lang('Address') :
                                    </td>
                                    <td>
                                        {{ $user->shipping_address_1 }} / {{ $user->shipping_address_2 }}
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        @lang('Town/City') :
                                    </td>
                                    <td>
                                        {{ $user->shipping_city }}
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        @lang('Country') :
                                    </td>
                                    <td>
                                        {{ $user->shipping_country }}
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        @lang('State/Province') :
                                    </td>
                                    <td>
                                        {{ $user->shipping_state }}
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        @lang('Zip/Postcode') :
                                    </td>
                                    <td>
                                        {{ $user->shipping_zip }}
                                    </td>
                                </tr>

                                <tr>
                                    <td>
                                        @lang('Delivery Preference') :
                                    </td>
                                    <td>
                                        {{ $user->delivery }}
                                    </td>
                                </tr>


                            </table>

                            <p style="margin-top: 15px">@lang('To update your shipping information, please visit your') <a
                                    href="{{ route('user.profile.setting') }}"></a> <a
                                    href="{{ route('user.profile.setting') }}">@lang('Profile')</a></p>

                            <h5 style="margin-top: 15px">@lang('Terms and Conditions')</h5>

                            @lang('Your participation in this auction indicates your review and agreement to the following terms. Should you purchase any ACE National Winner coffee at this auction you agree to the terms and conditions of purchase and shipping included in this agreement. Any buyer of any ACE National Winner coffee through the auction or through a secondary channel agrees to the use and restrictions of the ACE National Winner® mark. Any misuse of this mark may be cause for legal proceedings.')

                            <br>

                            <input type="checkbox" id="accept">&nbsp;<span>@lang('I agree to the') <a
                                    href="{{ route('policy', ['id' => get_policy_id(), 'slug' => 'terms-and-conditions']) }}"
                                    target="_blank"> @lang('terms and conditions')</a></span><br>

                                    <div class="modal-footer" style="margin-top: 15px">
                                        <a href="{{ route('event.all') }}" class="btn btn--danger">@lang('Cancel')</a>
                                        <a href="{{ route('user.request.store', [$id]) }}" id="proceed"
                                            class="btn btn--base disabled">@lang('Proceed')</a>
                                    </div>
                        </div>
                    </div>


                </div>
            </div>
        </div>
    </section>
    <!-- Product -->
@endsection


@push('style')
    <style>
        /* Chrome, Safari, Edge, Opera */
        input::-webkit-outer-spin-button,
        input::-webkit-inner-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }

        /* Firefox */
        input[type=number] {
            -moz-appearance: textfield;
        }
    </style>
@endpush



@push('style')
    <style>
        .disabled {
            pointer-events: none;
            opacity: 0.5;
        }

        .ui-datepicker .ui-datepicker-prev,
        .ui-datepicker .ui-datepicker-next {
            color: #111;
            background-color: #fff;
            z-index: 11;
        }

        .ui-datepicker-prev {
            position: relative;
        }

        .ui-datepicker-prev::before {
            position: absolute;
            content: "\f104";
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            font-family: "Line Awesome Free";
            font-weight: 900;
        }

        .ui-datepicker-next::before {
            position: absolute;
            content: "\f105";
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            font-family: "Line Awesome Free";
            font-weight: 900;
        }

        .price-range {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            justify-content: space-between;
            font-size: 14px;
        }

        .price-range label {
            margin: 0;
            font-weight: 500;
            color: #171d1c;
        }

        .price-range input {
            height: unset;
            width: unset;
            background: transparent;
            border: none;
            text-align: right;
            font-weight: 500;
            color: #c151cc;
            padding-right: 0;
        }

        .ui-slider-range {
            height: 3px;
            background: $ base-color;
            position: relative;
            z-index: 1;
        }

        .widget .ui-state-default {
            width: 20px;
            height: 20px;
            border-radius: 50%;
            display: block;
            border: none;
            border-radius: 50%;
            background-color: $ base-color !important;
            box-shadow: 0 9px 20px 0 rgba(22, 26, 57, 0.36);
            outline: none;
            cursor: pointer;
            top: -9px;
            position: absolute;
            z-index: 1;
        }

        .widget .ui-state-default::after {
            position: absolute;
            content: "";
            width: 14px;
            height: 14px;
            border-radius: 50%;
            background: $ base-color;
            top: 3px;
            left: 3px;
            display: block;
        }

        .widget .ui-widget.ui-widget-content {
            position: relative;
            height: 3px;
            border: none;
            margin-right: 20px;
            margin-bottom: 25px;
        }

        .widget .ui-widget.ui-widget-content::after {
            position: absolute;
            content: "";
            top: 0;
            left: 0;
            height: 3px;
            background: rgba($ base-color, 0.3);
            width: calc(100% + 20px);
        }
    </style>
@endpush

@push('style-lib')
    <link rel="stylesheet" href="{{ asset($activeTemplateTrue . 'css/jquery-ui.min.css') }}">
@endpush

@push('script-lib')
    <script src="{{ asset($activeTemplateTrue . 'js/jquery-ui.min.js') }}"></script>
@endpush


@push('script')
    <script>
        (function($) {

            function modeview() {
                $(".mode-grid").click(function() {
                    $('.product-list-view').hide();
                    $('.product-grid-view').show();
                });
                $(".mode-list").click(function() {
                    $('.product-grid-view').hide();
                    $('.product-list-view').show();
                });
            }

            modeview();
            $("#accept").change(function() {
                if (this.checked) {
                    $("#proceed").removeClass("disabled");
                } else {
                    $("#proceed").addClass("disabled");
                }
            });

        })(jQuery);
    </script>
@endpush
