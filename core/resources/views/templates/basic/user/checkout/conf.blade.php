@extends($activeTemplate.'layouts.master')

@section('content')
    <div class="container-fluid">
        <div>

            <section class="product-section pt-120 pb-120">
                <div class="container">
                    <div class="mb-4 d-lg-none">
                        <div class="filter-btn ms-auto">
                            <i class="las la-filter"></i>
                        </div>
                    </div>
                    <div class="row flex-wrap-reverse">
                        <div class="col-lg-12 col-xl-12 search-result">
                            <h3 class="title">Dear {{auth()->user()->fullname}}</h3><br>
                            <p>
                            </p><br>
                            <p>@lang("Please review and confirm the following information to complete your registration in the event:")</p>
                            <h5>@lang("Shipping info")</h5>

                            <table>
                                <tr>
                                    <td>
                                        @lang("First Name:")
                                    </td>
                                    <td>
                                        {{$user->firstname}}
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        @lang("Last Name :")
                                    </td>
                                    <td>
                                        {{$user->lastname}}
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        @lang("Company Name :")
                                    </td>
                                    <td>
                                        {{$user->company_name}}
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        @lang("Company Website :")
                                    </td>
                                    <td>
                                        {{$user->company_website}}
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        @lang("Phone Number :")
                                    </td>
                                    <td>
                                        {{$user->mobile}}
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        @lang("Address :")
                                    </td>
                                    <td>
                                        {{$user->billing_address_1}} / {{$user->billing_address_2}}
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        @lang("Town/City :")
                                    </td>
                                    <td>
                                        {{$user->billing_city}}
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                       @lang(" Country :")
                                    </td>
                                    <td>
                                        {{$user->billing_country}}
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        @lang("State/Province :")
                                    </td>
                                    <td>
                                        {{$user->billing_state}}
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        @lang("Zip/Postcode :")
                                    </td>
                                    <td>
                                        {{$user->billing_zip}}
                                    </td>
                                </tr>
{{--                                <tr>--}}
{{--                                    <td>--}}
{{--                                        Company/Tax Identification Number--}}
{{--                                    </td>--}}
{{--                                    <td>--}}
{{--                                        {{$user->ein_number}}--}}
{{--                                    </td>--}}
{{--                                </tr>--}}
{{--                                <tr>--}}
{{--                                    <td>--}}
{{--                                        Delivery Preference :--}}
{{--                                    </td>--}}
{{--                                    <td>--}}
{{--                                        {{$user->delivery}}--}}
{{--                                    </td>--}}
{{--                                </tr>--}}

{{--                                <tr>--}}
{{--                                    <td>--}}
{{--                                        Preferred receiving days :--}}
{{--                                    </td>--}}
{{--                                    <td>--}}
{{--                                        @foreach(json_decode($user->preferred_receiving_day) as $day)--}}
{{--                                            {{$day}} ,--}}
{{--                                        @endforeach--}}
{{--                                    </td>--}}
{{--                                </tr>--}}
{{--                                <tr>--}}
{{--                                    <td>--}}
{{--                                        Preferred receiving times :--}}
{{--                                    </td>--}}
{{--                                    <td>--}}
{{--                                        @foreach(json_decode($user->preferred_receiving_times) as $time)--}}
{{--                                            {{$time}} ,--}}
{{--                                        @endforeach--}}
{{--                                    </td>--}}
{{--                                </tr>--}}
{{--                                <tr>--}}
{{--                                    <td>--}}
{{--                                        Any other special delivery instructions? :--}}
{{--                                    </td>--}}
{{--                                    <td>--}}
{{--                                        {{$user->other_special_delivery}}--}}
{{--                                    </td>--}}
{{--                                </tr>--}}
                            </table>


                            <p>{{__("To update your billing information, please visit your")}}  <a href="{{route('user.profile.setting')}}"></a> <a
                                    href="{{route('user.profile.setting')}}">Profile</a></p>

                            <h5 style="margin-top: 15px">{{__("Terms and Conditions")}}</h5>

                            {{__("Your participation in this auction indicates your review and agreement to the following
                            terms. Should you purchase any ACE National Winner coffee at this auction you agree to the
                            terms and conditions of purchase and shipping included in this agreement. Any buyer of any
                            ACE National Winner coffee through the auction or through a secondary channel agrees to the
                            use and restrictions of the ACE National Winner® mark. Any misuse of this mark may be cause
                            for legal proceedings.")}}


                            <div >
                                <input type="checkbox" id="accept">&nbsp;<span>{{__("I agree to the")}} <a href="{{route('policy',['id'=>get_policy_id(),'slug'=>'terms-and-conditions'])}}" target="_blank"> {{__('terms and conditions')}}</a></span><br>
                            </div>

                            <div class="card-footer">
                                <a href="{{route('user.checkout.order')}}"
                                   class="btn btn--primary btn-block"> @lang('Order Now') </a>
                                <a href="{{route('user.checkout.order',['payment'=>'payment_later'])}}"
                                   class="btn btn--primary btn-block"> @lang('Order With Pay Later') </a>
                                <a href="{{ URL::previous()}}" class="btn btn--primary btn-block"> @lang('Cancel') </a>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

        </div>
    </div>
@endsection
