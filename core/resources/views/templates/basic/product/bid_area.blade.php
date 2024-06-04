@if($is_event_ended)

    @php
        $company_name = "";

        if(isset($product->winner->user->company_name)){
            $company_name = $product->winner->user->company_name;
        }

        if(isset($product->winner->caption)){
                $caption = $product->winner->caption;
        }
    @endphp

    <p class="mb-4 mt-0 " style="display: block; color: #448e8f; font-weight: 600;">
        Winner: {{$caption??$company_name}}</p>
@else

@php
    $heroPrimaryButtonColor = getHeroPrimaryButtonColor($event);
    $eventOutlinedButtonColor = getEventOutlinedButtonColor($event);
@endphp

    @if ($product->status == 1 && $product->started_at < now() && $product->expired_at > now() && $product->is_in_allow_list)
        <div class="btn__area flex-column">
            @if($event->bid_status == 'open')

                <div class="cart-plus-minus input-group amount-input">
                    <span class="input-group-text bg--base border-0 currency-icon">
                        {{ $general->cur_sym }}
                    </span>
                    <input onFocus="this.select();" type="text" autocomplete="off"
                           placeholder="@lang('Enter your amount')"
                           class="form-control" id="amount" value="{{$new_bidding_value}}">
                </div>

                <!-- <p class="text--danger empty-message my-1">
                    @lang('Please enter an amount to bid')
                </p> -->

                <div id="bid_open_user_logged_in">
                    <button style="background-color: {{$heroPrimaryButtonColor}} !important;" class="cmn--btn btn--sm bid_now me-2"
                            data-cur_sym="{{ $general->cur_sym }}">@lang('Bid')</button>
                    <button style="background-color: {{$eventOutlinedButtonColor}} !important;" class="cmn--btn btn--sm auto_bid_now outline-btn"
                            data-cur_sym="{{ $general->cur_sym }}">@lang('Auto Bid')</button>
                </div>

{{--                <div id="bid_open_user_logged_out">--}}
{{--                    @if ($event->event_type == "ace_event")--}}
{{--                        <a class="cmn--btn btn--sm me-2"--}}
{{--                           href="{{route('user.login',['ace_member'=>'ace_member'])}}">@lang('Bid')</a>--}}
{{--                        <a class="cmn--btn btn--sm"--}}
{{--                           href="{{route('user.login',['ace_member'=>'ace_member'])}}">@lang('Auto Bid')</a>--}}
{{--                    @else--}}
{{--                        <a class="cmn--btn btn--sm me-2"--}}
{{--                           href="{{route('user.login')}}">@lang('Bid')</a>--}}
{{--                        <a class="cmn--btn btn--sm"--}}
{{--                           href="{{route('user.login')}}">@lang('Auto Bid')</a>--}}
{{--                    @endif--}}
{{--                </div>   --}}

                <div id="bid_open_user_logged_out">
                    @php

                        $title = __("Sign in and request access to start bidding.");

                    @endphp
                    <div id="bid_open_user_logged_in" class="d-flex align-items-center">
                        <div class="p-1" data-bs-toggle="tooltip" data-bs-placement="bottom" title="{{$title}}"
                             onmouseleave="hideBootstrapTooltip(this)">
                            <button style="background-color: {{$heroPrimaryButtonColor}} !important;" type="button" disabled class="cmn--btn btn--sm bid_now me-2" 
                                    data-cur_sym="{{ $general->cur_sym }}">@lang('Bid')</button>
                        </div>
                        <div class="p-1" data-bs-toggle="tooltip" data-bs-placement="bottom" title="{{$title}}"
                             onmouseleave="hideBootstrapTooltip(this)">
                            <button style="background-color: {{$eventOutlinedButtonColor}} !important;" type="button" disabled class="cmn--btn btn--sm auto_bid_now outline-btn" 
                                    data-cur_sym="{{ $general->cur_sym }}">@lang('Auto Bid')</button>
                        </div>
                    </div>
                </div>
            @else

                <span class="cmn--btn btn--sm mt-4"
                      href="#">@lang('Bid Paused')</span>
                {{--            @if(!\Illuminate\Support\Facades\Auth::check())--}}
                {{--                @if ($event->event_type == "ace_event")--}}
                {{--                    <a class="cmn--btn btn--sm"--}}
                {{--                       href="{{route('user.login',['ace_member'=>'ace_member'])}}">@lang('Bid')</a>--}}
                {{--                    --}}{{--                                                        <a class="cmn--btn btn--sm"--}}
                {{--                    --}}{{--                                                           href="{{route('user.login',['ace_member'=>'ace_member'])}}">@lang('Auto Bid')</a>--}}
                {{--                @else--}}
                {{--                    <a class="cmn--btn btn--sm"--}}
                {{--                       href="{{route('user.login')}}">@lang('Bid')</a>--}}
                {{--                    --}}{{--                                                        <a class="cmn--btn btn--sm"--}}
                {{--                    --}}{{--                                                           href="{{route('user.login')}}">@lang('Auto Bid')</a>--}}
                {{--                @endif--}}
                {{--            @else--}}
                {{--                <span class="cmn--btn btn--sm"--}}
                {{--                      href="#">@lang('Bid Paused')</span>--}}
                {{--            @endif--}}

            @endif

        </div>
    @else
        <div class="btn__area flex-column">
            <div class="cart-plus-minus input-group amount-input">
                    <span class="input-group-text bg--base border-0 currency-icon">
                        {{ $general->cur_sym }}
                    </span>
                <input onFocus="this.select();" type="text" autocomplete="off"
                       placeholder="@lang('Enter your amount')" disabled
                       class="form-control" id="amount" value="{{$new_bidding_value}}">
            </div>
            @php

                $title = __("Request access to participate in the auction.");

            @endphp
            <div id="bid_open_user_logged_in" class="d-flex align-items-center">
                <div class="p-1" data-bs-toggle="tooltip" data-bs-placement="bottom" title="{{$title}}"
                     onmouseleave="hideBootstrapTooltip(this)">
                    <button style="background-color: {{$heroPrimaryButtonColor}} !important;" type="button" disabled class="cmn--btn btn--sm bid_now me-2" 
                            data-cur_sym="{{ $general->cur_sym }}">@lang('Bid')</button>
                </div>
                <div class="p-1" data-bs-toggle="tooltip" data-bs-placement="bottom" title="{{$title}}"
                     onmouseleave="hideBootstrapTooltip(this)">
                    <button style="background-color: {{$eventOutlinedButtonColor}} !important;" type="button" disabled class="outline-btn cmn--btn btn--sm auto_bid_now" 
                            data-cur_sym="{{ $general->cur_sym }}">@lang('Auto Bid')</button>
                </div>
            </div>
        </div>
    @endif
@endif
