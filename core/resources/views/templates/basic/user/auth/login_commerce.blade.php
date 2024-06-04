@extends($activeTemplate.'layouts.frontend_commerce')
@php
$login = getContent('login.content', true);
@endphp
@section('content')
    <main class="mcultivo-login">
        <section class="login-form-section">
            <form method="POST" action="{{ route('user.login') }}" onsubmit="return submitUserForm();" class="">
                <div class="form-logo text-left">
                    <a href="{{ route('home') }}" >
                        <img src="{{ getImage(imagePath()['logoIcon']['path'] . '/logo.png') }}" alt="logo">
                    </a>
                </div>

                <div class="form-title">
                    <h1>Log In</h1>
                </div>

                @csrf
                <div class="form-controls-container">
                    <div class="mb-3">
                        <label for="name">@lang('Email')</label>
                        <input onFocus="this.select();" type="text" id="name" name="username" value="{{ old('username') }}" class="form-control mt-1" autocomplete="off" required>
                    </div>
                    <div class="mb-3">
                        <label for="password">@lang('Password')</label>
                        <input onFocus="this.select();" type="password" id="password" name="password" class="form-control mt-1" autocomplete="off" required>
                    </div>
                    <!-- <input type="hidden" name="login_type" value="member"> -->
                    @php $recaptcha = loadReCaptcha() @endphp
                    @if($recaptcha)
                        <div class="mb-3">
                            @php echo $recaptcha @endphp
                        </div>
                    @endif
                    @include($activeTemplate . 'partials.custom_captcha')
                    <div class="mb-3">
                        <div class="form-check d-flex">
                            <input class="form-check-input me-2" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                            <label class="form-check-label" for="remember">
                                @lang('Remember me')
                            </label>
                        </div>
                    </div>

                    <button type="submit" class="login-btn w-100">@lang('Log in')</button>
                </div>

                <div>
                    <a href="{{ route('user.register') }}"
                        class="me-3">@lang('Create Account')</a>
                    <a href="{{ route('user.password.request') }}">@lang('Forgot password ?')</a>
                </div>
            </form>


            @include('templates.basic.user.auth.authIcons')

            <img class="curve-image d-none" src="{{url('/assets/images/login/mc-curve.png')}}" alt="" />
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
