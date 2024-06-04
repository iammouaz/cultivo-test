<style>
    .links {
        color: #FF6128
    }

    .order-login-alert {
        background-color: #FF61280A;
    }

    .login-alert-title {
        display: block;
        font-size: 20px;
        font-weight: 500;
        line-height: 32px;
        text-align: left;
        color: #242828DE;
    }
</style>

@if ($page === 'header')
    <a class="login-link" onclick="showDrawer2()" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Log in">
        @include('templates.basic.svgIcons.user')
    </a>
@endif



@if ($page === 'order')
    <div class="w-100 d-flex align-items-center justify-content-between order-login-alert mt-4 px-3">
        <div class=" p-2 rounded ">

            <span class="login-alert-title">
                Already have an account?
            </span>
            <span class="text-secondary">
                Log in now to expedite the checkout process. Your information will be pre-filled for a faster
                experience.
            </span>
        </div>
        <a class="cmn--btn main-btn" data-bs-toggle="offcanvas" onclick="showDrawer2()" role="button"
            aria-controls="offcanvasExample">
            my account
        </a>
    </div>
@endif




<div class="offcanvas offcanvas-end" tabindex="-1" id="login-drawer" aria-labelledby="offcanvasExampleLabel">

    <div class="offcanvas-header modal-header px-4">
        <h5 class="offcanvas-title">My Account</h5>
        <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>

    <div id="login-drawer-div" class="offcanvas-body">
        <div>
            Save time on future orders! Log in or create an account for a faster checkout experience.
        </div>

        <form id="login-form" method="POST" action="{{ route('user.copan_login') }}" class="">
            @csrf
            <div class="mt-3">
                <input required class="form-control form-control-lg py-4 mb-2" name="email" type="email"
                    value="{{ old('email') }}" placeholder="Email*" aria-label=".form-control-lg example"
                    style="font-size:16px;background-color: white !important;color:black" autocomplete="off" />
                <input required class="form-control form-control-lg py-4" name="password" type="password"
                    placeholder="Password*" aria-label=".form-control-lg example"
                    style="font-size:16px;background-color: white !important;color:black" autocomplete="off" />
            </div>

            <button form="login-form" type="submit" class="btn btn-warning w-100 text-white border-0 mt-4"
                style="background: #FF6128;box-shadow: 0px 3px 1px -2px #24282833, 0px 1px 5px 0px #2428281F, 0px 2px 2px 0px #24282824;border-radius:0%">@lang('LOGIN')</button>

        </form>
        <div class="text-right">
            <x-create-account-drawer />
            <x-forgot-password-drawer />
            {{-- <button type="button" class="btn btn-link px-0 px-2 links" onclick="window.location.href='{{ route('user.register') }}'"></button> --}}
        </div>

    </div>
</div>
