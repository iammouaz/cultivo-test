@extends($activeTemplate.'layouts.frontend')
@php
    $resetPassword = getContent('reset_password.content', true);
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
    
            <form method="POST" action="{{ route('user.password.update')}}" class="account--form g-4">
                @csrf
                <input type="hidden" name="email" value="{{ $email }}">
                <input type="hidden" name="token" value="{{ $token }}">

                <div class="mb-3 hover-input-popup">
                    <label for="password" class="form--label-2">@lang('Password')</label>
                    <input type="password" id="password" name="password" class="form-control" autocomplete="off" required>
                    @if($general->secure_password)
                        <div class="input-popup">
                            <p class="error lower">@lang('1 small letter minimum')</p>
                            <p class="error capital">@lang('1 capital letter minimum')</p>
                            <p class="error number">@lang('1 number minimum')</p>
                            <p class="error special">@lang('1 special character minimum')</p>
                            <p class="error minimum">@lang('6 character password')</p>
                        </div>
                    @endif
                </div>
                <div class="mb-3">
                    <label for="confirm-password" class="form--label-2">@lang('Confirm Password')</label>
                    <input type="password" id="confirm-password" name="password_confirmation" class="form-control" autocomplete="off" required>
                </div>
                <button type="submit" class="cmn--btn w-100">@lang('Submit')</button>
            </form>

            @include('templates.basic.user.auth.authIcons')

            <img class="curve-image d-none" src="{{ url('/assets/images/login/mc-curve.png') }}" alt="" />
        </section>
    </main>
@endsection
@push('style')
<style>
    .hover-input-popup {
        position: relative;
    }
    .hover-input-popup:hover .input-popup {
        opacity: 1;
        visibility: visible;
    }
    .input-popup {
        position: absolute;
        bottom: 70%;
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



const largeLoginImageUrl =
    "{{ getLoginImage() }}"
const mediumLoginImageUrl =
    "{{ getLoginImage('md') }}";
const smallLoginImageUrl =
    "{{ getLoginImage('sm') }}";




setResponsiveImage(largeLoginImageUrl, mediumLoginImageUrl, smallLoginImageUrl, document.getElementById(
        'main-login-container'),
    false);






    (function ($) {
        "use strict";
        @if($general->secure_password)
            $('input[name=password]').on('input',function(){
                secure_password($(this));
            });
        @endif
    })(jQuery);
    $(document).ready(function() {
            var dropdownBtn = $('#customDropdownBtn');
            var dropdownMenu = $('#customDropdownMenu');
            var formDiv = $('.login-form-section')
            var siteSetting = @json(getSiteSettingValue('login_form_background_color'));


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
</script>
@endpush
