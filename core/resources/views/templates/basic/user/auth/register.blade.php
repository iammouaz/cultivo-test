@extends($activeTemplate . 'layouts.frontend')
@php
    $register = getContent('register.content', true);
    $policyPages = getContent('policy_pages.element');
@endphp
@php
    $languagesMenu = get_available_languages();
@endphp
@section('content')
    <link rel="stylesheet" href="{{ asset($activeTemplateTrue . 'css/login-pages.min.css') }}">

    <main id="main-login-container" class="mcultivo-login">
        <section class="login-form-section">
            <ul style=" position: absolute; top: 1rem; right: 5rem; cursor: pointer;">
                <li class="user-icon" data-bs-toggle="Language" data-bs-placement="bottom" title="@lang('Language')">
                    <div class="custom-dropdown-container">
                        <svg id="customDropdownBtn" width="24" height="24" viewBox="0 0 24 24" fill="none"
                            xmlns="http://www.w3.org/2000/svg" aria-label="Language" class="">
                            <path
                                d="M12.87 15.07L10.33 12.56L10.36 12.53C12.1 10.59 13.34 8.36 14.07 6H17V4H10V2H8V4H1V5.99H12.17C11.5 7.92 10.44 9.75 9 11.35C8.07 10.32 7.3 9.19 6.69 8H4.69C5.42 9.63 6.42 11.17 7.67 12.56L2.58 17.58L4 19L9 14L12.11 17.11L12.87 15.07ZM18.5 10H16.5L12 22H14L15.12 19H19.87L21 22H23L18.5 10ZM15.88 17L17.5 12.67L19.12 17H15.88Z"
                                fill="#242828" fill-opacity="0.56"></path>
                        </svg>
                        {{-- <button class="custom-dropdown-toggle" id="customDropdownBtn">Dropdown</button> --}}
                        <div class="custom-dropdown-menu" id="customDropdownMenu">


                            @foreach ($languagesMenu as $languagesMenu)
                                <a href="#" data-language-id="{{ $languagesMenu->id }}"
                                    class="custom-dropdown-item">{{ $languagesMenu->name }}</a>
                            @endforeach
                        </div>
                    </div>
                </li>
            </ul>
            <form action="{{ route('user.register') }}" method="POST" onsubmit="return submitUserForm();" class="">

                <div class="form-logo text-left">
                    <a href="{{ route('home') }}">
                        <img id="logo" alt="logo">
                    </a>
                </div>

                <div class="form-title">
                    <h1>@lang('Create Account')</h1>
                </div>

                @csrf

                <div class="form-controls-container">
                    <div class="row">
                        <div class="col-sm-6 mb-3">
                            {{-- <label for="firstname">@lang('Firstname')</label> --}}
                            <input placeholder="@lang('Firstname')" type="text" id="firstname" name="firstname" class="form-control" autocomplete="off"
                                required>
                        </div>
                        <div class="col-sm-6 mb-3">
                            {{-- <label for="lastname">@lang('Lastname')</label> --}}
                            <input placeholder="@lang('Lastname')" type="text" id="lastname" name="lastname" class="form-control" autocomplete="off"
                                required>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-12 mb-3">
                            {{-- <label for="email">@lang('E-Mail Address')</label> --}}
                            <input placeholder="@lang('E-Mail Address')" type="text" id="email" name="email" class="form-control checkUser"
                                autocomplete="off" required>
                        </div>
                        <div class="col-sm-12 mb-3">
                            {{-- <label for="country-name">@lang('Country')</label> --}}
                            <select required name="country" id="country-name" class="form-control">
                                <option value="Select Country">@lang('Select Country')</option>
                                @foreach ($countries as $key => $country)
                                    <option data-mobile_code="{{ $country->dial_code }}" value="{{ $country->country }}"
                                        data-code="{{ $key }}">{{ $country->country }}</option>
                                @endforeach
                            </select>
                        </div>
                        <input type="hidden" name="mobile_code">
                        <input type="hidden" name="country_code">

                        <div class="col-sm-12 mb-3">
                            {{-- <label for="mobile">@lang('Mobile')</label> --}}
                            <div class="input-group">
                                <span class="input-group-text mobile-code bg--base text-white border-0"></span>
                                <input disabled placeholder="@lang('Select Country First')" type="tel" id="mobile"
                                    name="billing_phone" value="{{ old('billing_phone') }}" class="form-control checkUser"
                                    autocomplete="off" required>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-12 mb-3">
                            {{-- <label for="company_name">@lang('Company Name')</label> --}}
                            <input placeholder="@lang('Company Name')" type="text" id="company_name" name="company_name" value="{{ old('company_name') }}"
                                class="form-control" autocomplete="off" required>
                        </div>
                        <div class="col-sm-12 mb-3">
                            {{-- <label for="company_website">@lang('Company Website')</label> --}}
                            <input placeholder="@lang('Company Website')" type="text" id="company_website" name="company_website"
                                value="{{ old('company_website') }}" class="form-control" autocomplete="off" required>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-12 mb-3">
                            {{-- <label for="username">@lang('Username')</label> --}}
                            <input placeholder="@lang('Username')" type="text" id="username" name="username" class="form-control checkUser"
                                autocomplete="off" required>
                        </div>

                    </div>

                    <div class="row">
                        <div class="col-sm-6 mb-3 hover-input-popup">
                            {{-- <label for="password">@lang('Password')</label> --}}
                            <input placeholder="@lang('Password')" type="password" id="password" name="password" class="form-control"
                                autocomplete="off" required>
                            @if ($general->secure_password)
                                <div class="input-popup">
                                    <p class="error lower">@lang('1 small letter minimum')</p>
                                    <p class="error capital">@lang('1 capital letter minimum')</p>
                                    <p class="error number">@lang('1 number minimum')</p>
                                    <p class="error special">@lang('1 special character minimum')</p>
                                    <p class="error minimum">@lang('6 character password')</p>
                                </div>
                            @endif
                        </div>
                        <div class="col-sm-6 mb-3">
                            {{-- <label for="confirm-password">@lang('Confirm Password')</label> --}}
                            <input placeholder="@lang('Confirm Password')" type="password" id="confirm-password" name="password_confirmation"
                                class="form-control" autocomplete="off" required>
                        </div>
                    </div>

                    @php $recaptcha = loadReCaptcha() @endphp
                    @if ($recaptcha)
                        <div class="mb-3">
                            @php echo $recaptcha @endphp
                        </div>
                    @endif

                    @include($activeTemplate . 'partials.custom_captcha')

                    @if ($general->agree)
                        <div class="mb-3 d-flex gap-3 align-items-center">
                            <input class="form-check-input" style="width: 21px;" type="checkbox" id="agree" name="agree">
                            <label for="agree" class="ms-1">@lang('I agree with')
                                @foreach ($policyPages as $policyPage)
                                    <a href="{{ route('policy', [$policyPage, slug($policyPage->data_values->title)]) }}"
                                        target="_blank">
                                        {{ $policyPage->data_values->title }}@if (!$loop->last)
                                            ,
                                        @endif
                                    </a>
                                @endforeach
                            </label>
                        </div>
                    @endif

                    <div class="mb-3 d-flex gap-3 align-items-center">
                        <input class="form-check-input" type="checkbox" id="opt_in" value="1" name="opt_in">
                        <label for="opt_in" class="ms-1">@lang('Opt in to sms/whatsapp updates ')</label>
                    </div>

                    <div class="mb-3 d-flex gap-3 align-items-center">
                        <input class="form-check-input" type="checkbox" id="i_contacted_upcoming_auctions" value="1"
                            name="i_contacted_upcoming_auctions">
                        <label for="i_contacted_upcoming_auctions" class="ms-1">@lang('Notify me about future auctions')</label>
                    </div>

                    <!-- Submit button -->
                    <button type="submit" class="login-btn w-100">@lang('Register')</button>
                </div>


                <div>
                    <span>@lang('Already have an account?')  <a href="{{ route('user.login') }}" class="me-3">@lang('LOG IN')</a>
                    </span>
                    {{-- <a href="{{ route('user.password.request') }}">@lang('Forgot password ?')</a> --}}
                </div>
            </form>


            @include('templates.basic.user.auth.authIcons')

            <img class="curve-image d-none" src="{{ url('/assets/images/login/mc-curve.png') }}" alt="" />
        </section>
    </main>



    <div class="modal fade" id="existModalCenter" tabindex="-1" role="dialog" aria-labelledby="existModalCenterTitle"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="existModalLongTitle">@lang('You are with us')</h5>
                    <button type="button" class="btn text--danger modal-close" data-bs-dismiss="modal"
                        aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <h6 class="text-center">@lang('You already have an account please Sign in ')</h6>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn--danger" data-bs-dismiss="modal">@lang('Close')</button>
                    <a href="{{ route('user.login') }}" class="btn btn--base">@lang('Login')</a>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('style')
    <style>
        .form-control:disabled,
        .form-control[readonly] {
            background-color: transparent;
        }

        .country-code .input-group-prepend .input-group-text {
            background: #fff !important;
        }

        .country-code select {
            border: none;
        }

        .country-code select:focus {
            border: none;
            outline: none;
        }

        .hover-input-popup {
            position: relative;
        }

        .hover-input-popup:hover .input-popup {
            opacity: 1;
            visibility: visible;
        }

        .input-popup {
            position: absolute;
            bottom: 130%;
            left: 50%;
            width: 280px;
            background-color: #f1f1f1;
            color: #fff;
            padding: 20px;
            border-radius: 5px;
            -webkit-border-radius: 5px;
            -moz-border-radius: 5px;
            -ms-border-radius: 5px;
            -o-border-radius: 5px;
            -webkit-transform: translateX(-50%);
            -ms-transform: translateX(-50%);
            transform: translateX(-50%);
            opacity: 0;
            visibility: hidden;
            -webkit-transition: all 0.3s;
            -o-transition: all 0.3s;
            transition: all 0.3s;
        }

        .input-popup::after {
            position: absolute;
            content: '';
            bottom: -19px;
            left: 50%;
            margin-left: -5px;
            border-width: 10px 10px 10px 10px;
            border-style: solid;
            border-color: transparent transparent #1a1a1a transparent;
            -webkit-transform: rotate(180deg);
            -ms-transform: rotate(180deg);
            transform: rotate(180deg);
        }

        .input-popup p {
            padding-left: 20px;
            position: relative;
        }

        .input-popup p::before {
            position: absolute;
            content: '';
            font-family: 'Line Awesome Free';
            font-weight: 900;
            left: 0;
            top: 4px;
            line-height: 1;
            font-size: 18px;
        }

        .input-popup p.error {
            text-decoration: line-through;
        }

        .input-popup p.error::before {
            content: "\f057";
            color: #ea5455;
        }

        .input-popup p.success::before {
            content: "\f058";
            color: #28c76f;
        }
    </style>
@endpush
@push('script-lib')
    <script src="{{ asset('assets/global/js/secure_password.js') }}"></script>
@endpush
@push('script')
    <script>
        "use strict";

        $(document).ready(function() {
            var dropdownBtn = $('#customDropdownBtn');
            var dropdownMenu = $('#customDropdownMenu');
            var siteSetting = @json(getSiteSettingValue('login_form_background_color'));

            var formDiv = $('.login-form-section')
            if('{{getSiteSettingValue('login_form_background_color')}}' && !JSON.parse(siteSetting)?.is_no_color) {
              addGlassEffect(rgbaStringToObject('{{getSiteSettingValue('login_form_background_color')}}'),formDiv)
            }


            dropdownBtn.on('click', function() {
                dropdownMenu.toggle();
            });

            // Close the dropdown if the user clicks outside of it
            $(document).on('click', function(event) {
                if (!$(event.target).closest('.custom-dropdown-container').length) {
                    dropdownMenu.hide();
                }
            });

            $('.custom-dropdown-item').on('click', function(e) {
                e.preventDefault();

                // Extract the language ID from the data attribute
                var languageId = $(this).data('language-id');

                // Make an AJAX request to the update.display.language route
                $.ajax({
                    type: 'POST',
                    url: '{{ route('update.display.language') }}',
                    data: {
                        _token: "{{ csrf_token() }}",
                        language_id: languageId
                    },
                    success: function(response) {},
                    error: function(xhr, status, error) {}
                    // reload the page
                }).done(function() {
                    location.reload();
                });
            });

        });

        function submitUserForm() {
            var response = grecaptcha.getResponse();
            if (response.length == 0) {
                document.getElementById('g-recaptcha-error').innerHTML =
                    '<span class="text-danger">@lang('Captcha field is required.')</span>';
                return false;
            }
            return true;
        }
        (function($) {


            const largeLoginImageUrl =
                "{{ getLoginImage() }}"
            const mediumLoginImageUrl =
                "{{ getLoginImage('md') }}";
            const smallLoginImageUrl =
                "{{ getLoginImage('sm') }}";




            setResponsiveImage(largeLoginImageUrl, mediumLoginImageUrl, smallLoginImageUrl, document.getElementById(
                    'main-login-container'),
                false);

            const largeImageUrl =
                "{{ getHomePageLogo() }}"
            const mediumImageUrl =
                "{{ getHomePageLogo('md') }}";
            const smallImageUrl =
                "{{ getHomePageLogo('sm') }}";




            setResponsiveImage(largeImageUrl, mediumImageUrl, smallImageUrl, document.getElementById('logo'),
                true);

            $(document).ready(function() {
                @if ($mobile_code)
                    $(`option[data-code={{ $mobile_code }}]`).attr('selected', '');
                @endif

                $('select[name=country]').change(function() {
                    $('input[name=mobile_code]').val($('select[name=country] :selected').data(
                        'mobile_code'));
                    $('input[name=country_code]').val($('select[name=country] :selected').data('code'));
                    $('.mobile-code').text('+' + $('select[name=country] :selected').data(
                        'mobile_code'));
                    $('input[name=billing_phone]').removeAttr("disabled");
                    $('input[name=billing_phone]').removeAttr("placeholder");
                });

                @if ($general->secure_password)
                    $('input[name=password]').on('input', function() {
                        secure_password($(this));
                    });
                @endif

                $('.checkUser').on('focusout', function(e) {
                    var url = '{{ route('user.checkUser') }}';
                    var value = $(this).val();
                    var token = '{{ csrf_token() }}';
                    if ($(this).attr('name') == 'mobile') {
                        var mobile = `${$('.mobile-code').text().substr(1)}${value}`;
                        var data = {
                            mobile: mobile,
                            _token: token
                        };
                    }
                    if ($(this).attr('name') == 'email') {
                        var data = {
                            email: value,
                            _token: token
                        };
                    }
                    if ($(this).attr('name') == 'username') {
                        var data = {
                            username: value,
                            _token: token
                        };
                    }
                    $.post(url, data, function(response) {
                        if (response['data'] && response['type'] == 'email') {
                            $('#existModalCenter').modal('show');
                        } else if (response['data'] != null) {
                            $(`.${response['type']}Exist`).text(
                                `${response['type']} already exist`);
                        } else {
                            $(`.${response['type']}Exist`).text('');
                        }
                    });
                });

                setTimeout(() => {
                    $(`option[value='Select Country']`).attr('selected', '');
                }, 500);
            });
        })(jQuery);
    </script>
@endpush
