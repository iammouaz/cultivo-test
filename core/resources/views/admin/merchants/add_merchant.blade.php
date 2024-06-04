@extends('admin.layouts.app')

@section('panel')

<div class="row  mb-30">
    <div class="card mt-50">
        <div class="card-body">
            <form action="{{ route('admin.merchant.addPost') }}" method="POST" onsubmit="return submitUserForm();" class="account--form g-4">
                @csrf


                @if(session()->get('reference') != null)
                <div class="mb-3">
                    <label for="referenceBy">@lang('Reference By')</label>
                    <input type="text" name="referBy" id="referenceBy" class="form-control" value="{{session()->get('reference')}}" readonly>
                </div>
                @endif

                <div class="row">
                    <div class="col-sm-6 mb-3">
                        <label for="firstname">@lang('Firstname')<span class="text-danger">*</span></label>
                        <input type="text" id="firstname" name="firstname" class="form-control" autocomplete="off" required>
                    </div>
                    <div class="col-sm-6 mb-3">
                        <label for="lastname">@lang('Lastname')<span class="text-danger">*</span></label>
                        <input type="text" id="lastname" name="lastname" class="form-control" autocomplete="off" required>
                    </div>

                    <div class="col-sm-6 mb-3">
                        <label for="country">@lang('Country')</label>
                        <select name="country" id="country" class="form-control">
                            @foreach($countries as $key => $country)
                                <option data-mobile_code="{{ $country->dial_code }}" value="{{ $country->country }}" data-code="{{ $key }}">{{ $country->country }}</option>
                            @endforeach
                        </select>
                    </div>
                    <input type="hidden" name="mobile_code">
                    <input type="hidden" name="country_code">

                    <div class="col-sm-6 mb-3">
                        <label for="mobile">@lang('Mobile')</label>
                        <div class="input-group">
                            <span class="input-group-text mobile-code  border-0"></span>
                            <input type="tel" id="mobile" name="mobile" value="{{ old('mobile') }}" class="form-control checkUser" autocomplete="off">
                        </div>
                    </div>
                    <div class="col-sm-6 mb-3">
                        <label for="username">@lang('Username')<span class="text-danger">*</span></label>
                        <input type="text" id="username" name="username" class="form-control checkUser" autocomplete="off" required>
                    </div>
                    <div class="col-sm-6 mb-3">
                        <label for="email">@lang('E-Mail Address')</label>
                        <input type="text" id="email" name="email" class="form-control checkUser" autocomplete="off">
                    </div>
                    <div class="col-sm-6 mb-3 hover-input-popup">
                        <label for="password">@lang('Password')</label>
                        <input type="password" id="password" name="password" class="form-control" autocomplete="off">
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

                    <div class="col-sm-6 mb-3">
                        <label for="confirm-password">@lang('Confirm Password')</label>
                        <input type="password" id="confirm-password" name="password_confirmation" class="form-control" autocomplete="off">
                    </div>
                </div>

                <div class="row mt-4">
                    <div class="col-md-12">
                        <div class="form-group ">
                            <label class="form-control-label font-weight-bold">@lang('Address') </label>
                            <input class="form-control" type="text" name="address" value="{{@$merchant->address->address}}">
                        </div>
                    </div>

                    <div class="col-xl-3 col-md-6">
                        <div class="form-group">
                            <label class="form-control-label font-weight-bold">@lang('City') </label>
                            <input class="form-control" type="text" name="city" value="{{@$merchant->address->city}}">
                        </div>
                    </div>

                    <div class="col-xl-3 col-md-6">
                        <div class="form-group ">
                            <label class="form-control-label font-weight-bold">@lang('State') </label>
                            <input class="form-control" type="text" name="state" value="{{@$merchant->address->state}}">
                        </div>
                    </div>

                    <div class="col-xl-3 col-md-6">
                        <div class="form-group ">
                            <label class="form-control-label font-weight-bold">@lang('Zip/Postal') </label>
                            <input class="form-control" type="text" name="zip" value="{{@$merchant->address->zip}}">
                        </div>
                    </div>

                    <div class="col-xl-3 col-md-6">
                        <div class="form-group ">
                            <label class="form-control-label font-weight-bold">@lang('Country') </label>
                            <select name="country" class="form-control">
                                @foreach($countries as $key => $country)
                                    <option value="{{ $key }}" @if($country->country == @$merchant->address->country ) selected @endif>{{ $country->country }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>


                <div class="row">
                    <div class="form-group col-xl-4 col-md-6  col-sm-3 col-12">
                        <label class="form-control-label font-weight-bold">@lang('Status') </label>
                        <input type="checkbox" data-onstyle="-success" data-offstyle="-danger"
                                data-toggle="toggle" data-on="@lang('Active')" data-off="@lang('Banned')" data-width="100%"
                                name="status"
                                >
                    </div>

                    <div class="form-group  col-xl-4 col-md-6  col-sm-3 col-12">
                        <label class="form-control-label font-weight-bold">@lang('Email Verification') </label>
                        <input type="checkbox" data-width="100%" data-onstyle="-success" data-offstyle="-danger"
                                data-toggle="toggle" data-on="@lang('Verified')" data-off="@lang('Unverified')" name="ev"
                                >

                    </div>

                    <div class="form-group  col-xl-4 col-md-6  col-sm-3 col-12">
                        <label class="form-control-label font-weight-bold">@lang('SMS Verification') </label>
                        <input type="checkbox" data-width="100%" data-onstyle="-success" data-offstyle="-danger"
                                data-toggle="toggle" data-on="@lang('Verified')" data-off="@lang('Unverified')" name="sv"
                                >

                    </div>
                    <div class="form-group  col-md-6  col-sm-3 col-12">
                        <label class="form-control-label font-weight-bold">@lang('2FA Status') </label>
                        <input type="checkbox" data-width="100%" data-onstyle="-success" data-offstyle="-danger"
                                data-toggle="toggle" data-on="@lang('Active')" data-off="@lang('Deactive')" name="ts"
                                >
                    </div>

                    <div class="form-group  col-md-6  col-sm-3 col-12">
                        <label class="form-control-label font-weight-bold">@lang('2FA Verification') </label>
                        <input type="checkbox" data-width="100%" data-onstyle="-success" data-offstyle="-danger"
                                data-toggle="toggle" data-on="@lang('Verified')" data-off="@lang('Unverified')" name="tv"
                                >
                    </div>
                </div>

                <button type="submit" class="cmn--btn w-100 btn btn--primary btn-block btn-lg">@lang('Add Merchant')</button>
            </form>
        </div>
    </div>
</div>



    <div class="modal fade" id="existModalCenter" tabindex="-1" role="dialog" aria-labelledby="existModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="existModalLongTitle">@lang('The account has been added successfully')</h5>
            <button type="button" class="btn text--danger modal-close" data-bs-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            <h6 class="text-center">@lang('This account already exists')</h6>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn--danger" data-bs-dismiss="modal">@lang('Close')</button>
        </div>
        </div>
    </div>
    </div>
    @push('style')
<style>
    .form-control:disabled, .form-control[readonly]{
        background-color: transparent;
    }
    .country-code .input-group-prepend .input-group-text{
        background: #fff !important;
    }
    .country-code select{
        border: none;
    }
    .country-code select:focus{
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
        background-color: #1a1a1a;
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
        function submitUserForm() {
            var response = grecaptcha.getResponse();
            if (response.length == 0) {
                document.getElementById('g-recaptcha-error').innerHTML = '<span class="text-danger">@lang("Captcha field is required.")</span>';
                return false;
            }
            return true;
        }
        (function ($) {
            @if($mobile_code)
            $(`option[data-code={{ $mobile_code }}]`).attr('selected','');
            @endif

            $('select[name=country]').change(function(){
                $('input[name=mobile_code]').val($('select[name=country] :selected').data('mobile_code'));
                $('input[name=country_code]').val($('select[name=country] :selected').data('code'));
                $('.mobile-code').text('+'+$('select[name=country] :selected').data('mobile_code'));
            });
            $('input[name=mobile_code]').val($('select[name=country] :selected').data('mobile_code'));
            $('input[name=country_code]').val($('select[name=country] :selected').data('code'));
            $('.mobile-code').text('+'+$('select[name=country] :selected').data('mobile_code'));
            @if($general->secure_password)
                $('input[name=password]').on('input',function(){
                    secure_password($(this));
                });
            @endif

            $('.checkUser').on('focusout',function(e){
                var url = '{{ route('user.checkUser') }}';
                var value = $(this).val();
                var token = '{{ csrf_token() }}';
                if ($(this).attr('name') == 'mobile') {
                    var mobile = `${$('.mobile-code').text().substr(1)}${value}`;
                    var data = {mobile:mobile,_token:token}
                }
                if ($(this).attr('name') == 'email') {
                    var data = {email:value,_token:token}
                }
                if ($(this).attr('name') == 'username') {
                    var data = {username:value,_token:token}
                }
                $.post(url,data,function(response) {
                  if (response['data'] && response['type'] == 'email') {
                    $('#existModalCenter').modal('show');
                  }else if(response['data'] != null){
                    $(`.${response['type']}Exist`).text(`${response['type']} already exist`);
                  }else{
                    $(`.${response['type']}Exist`).text('');
                  }
                });
            });

        })(jQuery);

    </script>
@endpush
@endsection
