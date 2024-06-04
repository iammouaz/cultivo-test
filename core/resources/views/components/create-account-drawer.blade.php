<div>
    <!-- Act only according to that maxim whereby you can, at the same time, will that it should become a universal law. - Immanuel Kant -->
</div>
<style>
    .links {
        color: #FF6128;
        text-decoration: underline
    }
</style>


<a style="background-color: white; color: #FF6128; border: 0;"  class="btn btn-primary" onclick="showDrawer1()" role="button">
   @lang('Create an account')
</a>
{{-- <button type="button" onclick="showDrawer1()">@lang('Create an account')</button> --}}


<div class="offcanvas offcanvas-end" tabindex="-1" id="create-account-drawer" aria-labelledby="offcanvasExampleLabel">

    <div class="offcanvas-header modal-header px-4">
        <h5 class="offcanvas-title">My Account</h5>
        <button onclick="closeCreate()" type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>

    <div class="offcanvas-body">
        <div>
            Please enter the information below to create your account
        </div>

        <form id="create-account-form" action="{{ route('user.copan_register') }}" method="POST">
            @csrf
            <div class="mt-3">
                <input required class="form-control form-control-lg py-4 mb-3" type="text" placeholder="First Name*"
                    aria-label=".form-control-lg example"
                    style="font-size:16px;background-color: white !important;color:black" name="firstname" autocomplete="off" value="{{old('firstname')}}"/>
                <input required class="form-control form-control-lg py-4 mb-3" type="text" placeholder="Last Name*"
                    aria-label=".form-control-lg example"
                    style="font-size:16px;background-color: white !important;color:black" name="lastname" autocomplete="off" value="{{old('lastname')}}"/>
                <input required class="form-control form-control-lg py-4 mb-3" type="tel" placeholder="Mobile*"
                    aria-label=".form-control-lg example"
                    style="font-size:16px;background-color: white !important;color:black"  name="mobile" autocomplete="off" value="{{ old('mobile') }}"/>
                <input required class="form-control form-control-lg py-4 mb-3" type="text"
                    placeholder="Company Name*" aria-label=".form-control-lg example"
                    style="font-size:16px;background-color: white !important;color:black" name="company_name" autocomplete="off" value="{{ old('company_name') }}"/>
                <input required class="form-control form-control-lg py-4 mb-3" type="url"
                    placeholder="Company Website*" aria-label=".form-control-lg example"
                    style="font-size:16px;background-color: white !important;color:black" name="company_website" autocomplete="off" value="{{ old('company_website') }}" />
                <input required class="form-control form-control-lg py-4 mb-3" type="email"
                    placeholder="Email Address*" aria-label=".form-control-lg example"
                    style="font-size:16px;background-color: white !important;color:black" name="email" autocomplete="off" value="{{ old('email') }}" />
                <input required class="form-control form-control-lg py-4 mb-3" type="password" placeholder="Password*"
                    aria-label=".form-control-lg example"
                    style="font-size:16px;background-color: white !important;color:black" name="password" autocomplete="off"/>
                <input required class="form-control form-control-lg py-4" type="password"
                    placeholder="Confirm Password*" aria-label=".form-control-lg example"
                    style="font-size:16px;background-color: white !important;color:black" name="password_confirmation" autocomplete="off"/>


                <div class="form-check my-3 mx-2">
                    <input required class="form-check-input" type="checkbox" value="1" id="flexCheckDefault" name="agree">
                    <label class="form-check-label" for="flexCheckDefault">
                        I agree with the <a type="button" class="p-0 links">Terms and
                            Conditions</a>
                    </label>
                </div>
            </div>

            <button form="create-account-form"  type="submit" class="btn btn-warning w-100 text-white border-0 mt-4"
                style="background: #FF6128;box-shadow: 0px 3px 1px -2px #24282833, 0px 1px 5px 0px #2428281F, 0px 2px 2px 0px #24282824;border-radius:0%" >create&continue</button>
        </form>

    </div>
</div>
