@extends($activeTemplate .'layouts.frontend')
@php
    $emailVerify = getContent('email_verify.content', true);
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


        <form method="POST" action="{{ route('user.verify.email')}}" class="account--form g-4">
            @csrf
            <div class="section__header text--white">
                <h6 class="mb-0">@lang('Verify Your Email'): {{auth()->user()->email}}</h6>
            </div>
            <div class="mb-3">
                <label for="code" class="form--label-2">@lang('Verification Code')</label>
                <input type="text" id="code" name="email_verified_code" value="{{ old('email_verified_code') }}" class="form-control"  maxlength="7" autocomplete="off">
            </div>
            <button type="submit" class="cmn--btn w-100">@lang('Submit')</button>
            <div class="mt-4 text-center text--white">
                @lang('Please check including your Junk/Spam Folder. if not found, you can') <a href="{{route('user.send.verify.code')}}?type=email" class="forget-pass text--base"> @lang('Resend code')</a>
                @if ($errors->has('resend'))
                    <br/>
                    <small class="text-danger">{{ $errors->first('resend') }}</small>
                @endif
            </div>
        </form>



        @include('templates.basic.user.auth.authIcons')

        <img class="curve-image d-none" src="{{ url('/assets/images/login/mc-curve.png') }}" alt="" />
    </section>
</main>
@endsection

@push('script')
<script>
    (function($){
        "use strict";
        $('#code').on('input change', function () {
          var xx = document.getElementById('code').value;

              $(this).val(function (index, value) {
                 value = value.substr(0,7);
                  return value.replace(/\W/gi, '').replace(/(.{3})/g, '$1 ');
              });

      });
    })(jQuery)
</script>
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



        function submitUserForm() {
            var response = grecaptcha.getResponse();
            if (response.length == 0) {
                document.getElementById('g-recaptcha-error').innerHTML =
                    '<span class="text-danger">@lang('Captcha field is required.')</span>';
                return false;
            }
            return true;
        }

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
