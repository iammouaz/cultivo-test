@extends($activeTemplate.'layouts.frontend')
@php
    $resetPassword = getContent('reset_password.content', true);
@endphp
@section('content')
<div class="account-section bg_img" data-background="{{ getImage('assets/images/frontend/reset_password/'.$resetPassword->data_values->background_image, '1920x1080') }}">
    <div class="account__section-wrapper">
        <div class="account__section-content bg--section">
            <div class="w-100">
                <div class="d-flex justify-content-center">
                    <div class="logo mb-5">
                        <a href="{{ route('home') }}" class="text-center">
                            <img src="{{ getImage(imagePath()['logoIcon']['path'] . '/logo.png') }}" alt="logo">
                        </a>
                    </div>
                </div>

                <div class="section__header text--white">
                    <h4 class="mb-0">@lang('Create Password')</h4>
                </div>
                <form method="POST" class="account--form g-4">
                    @csrf
                    <div class="mb-3 hover-input-popup">
                        <label for="new-password" class="form--label-2">@lang('New Password')</label>
                        <input type="password" name="password" id="new-password" class="form-control form--control-2" required>
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
                        <label for="confirm-password" class="form--label-2">@lang('Confirm New Password')</label>
                        <input type="password" name="password_confirmation" id="confirm-password" class="form-control form--control-2" required>
                    </div>
                    <button type="submit" class="cmn--btn w-100">@lang('Create Password')</button>
                </form>
            </div>
        </div>
    </div>
</div>
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
    (function($) {
        "use strict";
        @if($general->secure_password)
        $('input[name=password]').on('input', function() {
            secure_password($(this));
        });
        @endif
    })(jQuery);
</script>
@endpush