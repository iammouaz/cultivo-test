<style>
    .links {
        color: #FF6128
    }
</style>


<a style="background-color: white; color: #FF6128; border: 0;" class="btn btn-primary" onclick="showDrawer3()" role="button">
    @lang('Forgot password?')
 </a>
{{-- <a class="btn btn-primary" data-bs-toggle="offcanvas" href="#forgot-password-drawer" role="button" type="button" onclick="showDrawer3()">@lang('Forgot password?')</a> --}}

<div class="offcanvas offcanvas-end" tabindex="-1" id="forgot-password-drawer" aria-labelledby="offcanvasExampleLabel">

    <div class="offcanvas-header modal-header px-4">
        <h5 class="offcanvas-title">Forgot Password?</h5>
        <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>

    <div class="offcanvas-body">
        <div>
            Enter your email below to receive your password reset instructions.
        </div>

        <form method="POST" action="{{ route('user.copan_password.email')}}" id="forgot-password-form">
            @csrf
            <div class="mt-3">
                <input required class="form-control form-control-lg py-4 mb-2" type="email" name="email" placeholder="Email*"
                    aria-label=".form-control-lg example"
                    style="font-size:16px;background-color: white !important;color:black">
            </div>

            <button form="forgot-password-form" type="submit" class="btn btn-warning w-100 text-white border-0 mt-4"
                style="background: #FF6128;box-shadow: 0px 3px 1px -2px #24282833, 0px 1px 5px 0px #2428281F, 0px 2px 2px 0px #24282824;border-radius:0%">send
                reset instructions
            </button>


        </form>

    </div>
</div>
