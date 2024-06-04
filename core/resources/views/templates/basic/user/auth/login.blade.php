@extends($activeTemplate . 'layouts.frontend')
@php
    $login = getContent('login.content', true);
    $login_type??="normal";
@endphp
@php
    $languagesMenu = get_available_languages();
@endphp
@section('content')

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
            <form method="POST" action="{{ route('user.login') }}" onsubmit="return submitUserForm();" class="">
                <div class="form-logo text-left">
                    <a href="{{ route('home') }}">
                        <img id="logo" alt="logo">
                    </a>
                </div>

                <div class="form-title">
                    <h1>@lang('Log In')</h1>
                </div>

                @csrf
                <div class="form-controls-container">
                    <div class="mb-3">
                        {{-- <label for="name">@lang('Username')/@lang('Email')</label> --}}
                        <input placeholder="@lang('Username')/@lang('Email')" onFocus="this.select();" type="text" id="name" name="username"
                            value="{{ old('username') }}" class="form-control mt-1" autocomplete="off" required>
                    </div>
                    <div class="mb-3">
                        {{-- <label for="password">@lang('Password')</label> --}}
                        <input  placeholder="@lang('Password')" onFocus="this.select();" type="password" id="password" name="password"
                            class="form-control mt-1" autocomplete="off" required>
                    </div>
                    <input type="hidden" name="login_type" value="{{$login_type??'normal'}}">
                    @php $recaptcha = loadReCaptcha() @endphp
                    @if ($recaptcha)
                        <div class="mb-3">
                            @php echo $recaptcha @endphp
                        </div>
                    @endif
                    @include($activeTemplate . 'partials.custom_captcha')
                    <div class="mb-3">
                        <div class="form-check d-flex">
                            <input class="form-check-input me-2" type="checkbox" name="remember" id="remember"
                                {{ old('remember') ? 'checked' : '' }}>
                            <label class="form-check-label" for="remember">
                                @lang('Remember me')
                            </label>
                        </div>
                    </div>

                    <button type="submit" class="login-btn w-100">@lang('Log in')</button>
                </div>

                <div>
                    @if($login_type == 'ace' && config('app.enable_ace_login'))
                     <a href="https://allianceforcoffeeexcellence.org/join-us/" class="me-3">@lang('Create Account')</a>
                    @else
                    <a href="{{ route('user.register') }}" class="me-3">@lang('Create Account')</a>
                    @endif
                    <a href="{{ route('user.password.request') }}">@lang('Forgot password ?')</a>

                </div>
            </form>

            @include('templates.basic.user.auth.authIcons')

            <img class="curve-image d-none" src="{{ url('/assets/images/login/mc-curve.png') }}" alt="" />
        </section>
    </main>
@endsection

@push('script')
    <script>
        "use strict";


        const largeImageUrl =
            "{{ getHomePageLogo() }}"
        const mediumImageUrl =
            "{{ getHomePageLogo('md') }}";
        const smallImageUrl =
            "{{ getHomePageLogo('sm') }}";


        const largeLoginImageUrl =
            "{{ getLoginImage() }}"
        const mediumLoginImageUrl =
            "{{ getLoginImage('md') }}";
        const smallLoginImageUrl =
            "{{ getLoginImage('sm') }}";




        setResponsiveImage(largeLoginImageUrl, mediumLoginImageUrl, smallLoginImageUrl, document.getElementById(
                'main-login-container'),
            false);




        setResponsiveImage(largeImageUrl, mediumImageUrl, smallImageUrl, document.getElementById('logo'),
            true);

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
