@extends($activeTemplate.'layouts.frontend',['event_type'=>'ace_event'])
@php
    $login = getContent('login.content', true);
@endphp
@section('content')
    <main class="ace-login d-flex flex-wrap flex-lg-nowarp">
        <section class="login-image-section d-none d-lg-block">
            <div class="text-center">
                <a href="https://allianceforcoffeeexcellence.org/join-us/" target="_blank">
                    <img src="{{ getImage(imagePath()['logoIcon']['path'] . '/ace-logo.png') }}"
                            alt="ACE logo">
                </a>
                <p>
                    Discover and reward exceptional quality coffee farmers
                </p>
            </div>
        </section>

        <section class="for-mobile-only w-100 d-flex flex-column align-items-center justifiy-content-center d-lg-none pt-3 pb-3">
            <a href="https://allianceforcoffeeexcellence.org/join-us/" target="_blank">
                <img src="{{ getImage(imagePath()['logoIcon']['path'] . '/ace-logo.png') }}"
                        alt="ACE logo">
            </a>
            <p class="text-center pt-2">
                Discover and reward exceptional quality coffee farmers
            </p>
        </section>

        <section class="login-form-section pt-5 pb-5 pt-lg-0 pb-lg-0">
            <form method="POST" action="{{ route('user.login') }}" onsubmit="return submitUserForm();">
                <div class="form-title">
                    <h1>Log In to Auctions</h1>
                    <h2>Please use your ACE membership log in</h2>
                </div>
                @csrf
                <div class="form-controls-container">
                    <div class="mb-3">
                        <label for="name">@lang('Username')/@lang('Email')</label>
                        <input onFocus="this.select();" type="text" id="name" name="username" value="{{ old('username') }}"
                                class="form-control mt-1" autocomplete="off" required>
                    </div>

                    <div class="mb-3">
                        <label for="password">@lang('Password')</label>
                        <input onFocus="this.select();" type="password" id="password" name="password" class="form-control mt-1" autocomplete="off"
                                required>
                    </div>

                    <input type="hidden" name="login_type" value="ace_member">

                    @php $recaptcha = loadReCaptcha() @endphp
                    @if($recaptcha)
                        <div class="mb-3">
                            @php echo $recaptcha @endphp
                        </div>
                    @endif
                    @include($activeTemplate . 'partials.custom_captcha')

                    <div class="mb-3">
                        <div class="form-check d-flex">
                            <input class="form-check-input me-2" type="checkbox" name="remember" id="remember"
                                {{ old('remember') ? 'checked' : '' }}>
                            <label for="remember">
                                @lang('Remember me')
                            </label>
                        </div>
                    </div>

                    <button type="submit" class="login-btn w-100">@lang('Log in')</button>
                </div>

                <div>
                    <a href="https://allianceforcoffeeexcellence.org/join-us/" class="ace-secondary-color me-3">
                        @lang('Create Account')
                    </a>

                    <a href="{{ route('user.password.request') }}" class="ace-secondary-color">
                        @lang('Forgot password?')
                    </a>
                </div>
            </form>

            <div class="login-social-icons">
                <a href="https://www.instagram.com/cupofexcellence/" target="_blank">
                    @include("templates.basic.svgIcons.instagram")
                </a>

                <a href="https://www.linkedin.com/company/alliance-for-coffee-excellence-inc.-ace-" target="_blank">
                    @include("templates.basic.svgIcons.linkedin")
                </a>

                <a href="mailto:support@cupofexcellence.org" target="_blank">
                    @include("templates.basic.svgIcons.envelope")
                </a>
            </div>

            <img class="curve-image" src="{{url('/assets/images/login/ace-curve.png')}}" alt="" />
        </section>
    </main>
@endsection

@push('script')
    <script>
        "use strict";

        function submitUserForm() {
            var response = grecaptcha.getResponse();
            if (response.length == 0) {
                document.getElementById('g-recaptcha-error').innerHTML =
                    '<span class="text-danger">@lang("Captcha field is required.")</span>';
                return false;
            }
            return true;
        }
    </script>
@endpush
