{{--todo check the need for this view--}}
@if($is_event_ended)
        @php
        $company_name = "";

        if(isset($product->winner->user->company_name)){
                $company_name = $product->winner->user->company_name;
        }
        @endphp

        <p class="winner-label mb-4 mt-0 w-100 text-truncate fw-bold" style="color: #402020;z-index: 1" title="Winner: {{$company_name}}">
                Winner: {{$company_name}}
        </p>
@else
    @if($event->bid_status == 'open')

        @if ($product->event->event_type == 'ace_event')
            <button
{{--            ace event login --}}
                onclick="window.location.href = '{{route('user.login',['ace_member'=>'ace_member'])}}'"
                id="bid_open_user_logged_out_{{$product->id}}"
                class="cmn--btn btn--sm bid_now bid-with-anim">@lang('Bid')</button>
                <button
{{--            ace event login --}}
                onclick="window.location.href = '{{route('user.login',['ace_member'=>'ace_member'])}}'"
                id="auto_bid_open_user_logged_out_{{$product->id}}"
                class="outline-btn cmn--btn btn--sm auto_bid_now bid-with-anim">@lang('Auto Bid')</button>
        @else
            <button onclick="window.location.href = '{{route('user.login')}}'" id="bid_open_user_logged_out_{{$product->id}}"
                    class="cmn--btn btn--sm bid_now bid-with-anim">@lang('Bid')</button>
            <button onclick="window.location.href = '{{route('user.login')}}'" id="auto_bid_open_user_logged_out_{{$product->id}}"
                    class="outline-btn cmn--btn btn--sm auto_bid_now bid-with-anim">@lang('Auto Bid')</button>
        @endif

        <button class="cmn--btn btn--sm bid_now bid-with-anim" id="bid_open_user_logged_in_{{$product->id}}"
                data-cur_sym="{{ $general->cur_sym }}">@lang('Bid')</button>
        <button class="outline-btn cmn--btn btn--sm auto_bid_now bid-with-anim" id="auto_bid_open_user_logged_in_{{$product->id}}"
                data-cur_sym="{{ $general->cur_sym }}">@lang('Auto Bid')</button>


        <div class="highest-bidder">
                <div class="d-inline-flex align-items-center gap-2">
                        <svg width="33" height="45" viewBox="0 0 33 45" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M20.7422 2.43L16.0481 0L11.3541 2.43L6.12844 3.22313L3.76032 7.94813L0 11.6606L0.860626 16.875L0 22.0894L3.76032 25.8019L6.12844 30.5269L11.3541 31.32L16.0481 33.75L20.7422 31.32L25.9678 30.5269L28.336 25.8019L32.0963 22.0894L31.2357 16.875L32.0963 11.6606L28.336 7.94813L25.9678 3.22313L20.7422 2.43ZM24.106 5.78532L26.0297 9.62438L29.0841 12.6394L28.3866 16.875L29.0841 21.1106L26.0297 24.1257L24.106 27.9647L19.8591 28.6088L16.0481 30.5832L12.2372 28.6088L7.99032 27.9647L6.06657 24.1257L3.01219 21.1106L3.7125 16.875L3.00938 12.6394L6.06657 9.62438L7.99032 5.78532L12.2372 5.14125L16.0481 3.16688L19.8619 5.14125L24.106 5.78532Z" fill="url(#paint0_diamond_1703_75)"/>
                                <path d="M4.7981 33.1707V45L16.0481 42.1875L27.2981 45V33.1707L21.6225 34.0313L16.0481 36.9169L10.4737 34.0313L4.7981 33.1707Z" fill="url(#paint1_diamond_1703_75)"/>
                                <defs>
                                <radialGradient id="paint0_diamond_1703_75" cx="0" cy="0" r="1" gradientUnits="userSpaceOnUse" gradientTransform="translate(17.0849 10.2509) rotate(112.7) scale(25.7588 22.9006)">
                                <stop stop-color="#DAA900" stop-opacity="0.17"/>
                                <stop offset="1" stop-color="#DAA900"/>
                                </radialGradient>
                                <radialGradient id="paint1_diamond_1703_75" cx="0" cy="0" r="1" gradientUnits="userSpaceOnUse" gradientTransform="translate(16.7749 36.7636) rotate(129.917) scale(10.8596 13.3467)">
                                <stop stop-color="#DAA900" stop-opacity="0.17"/>
                                <stop offset="1" stop-color="#DAA900"/>
                                </radialGradient>
                                </defs>
                                </svg>

                        <span>@lang('Highest Bidder')</span>
                </div>

                <button class="autobid-settings auto_bid_now align-baseline ms-2" id="auto_bid_open_user_logged_in_{{$product->id}}"
                data-cur_sym="{{ $general->cur_sym }}">
                        <i class="fas fa-pencil-alt"></i>
                        <span>@lang('Edit Autobid')</span>
                </button>
        </div>

    @else

        @if ($product->event->event_type == 'ace_event')
                <button
{{--                ace event login --}}
                onclick="window.location.href = '{{route('user.login',['ace_member'=>'ace_member'])}}'"
                id="bid_open_user_logged_out_{{$product->id}}"
                class="cmn--btn btn--sm bid_now bid-with-anim">@lang('Bid')</button>
                <button
{{--                ace event login --}}
                onclick="window.location.href = '{{route('user.login',['ace_member'=>'ace_member'])}}'"
                id="auto_bid_open_user_logged_out_{{$product->id}}"
                class="outline-btn cmn--btn btn--sm auto_bid_now bid-with-anim">@lang('Auto Bid')</button>
        @else
            <button onclick="window.location.href = '{{route('user.login')}}'" id="bid_open_user_logged_out_{{$product->id}}"
                    class="cmn--btn btn--sm bid_now bid-with-anim">@lang('Bid')</button>
            <button onclick="window.location.href = '{{route('user.login')}}'" id="auto_bid_open_user_logged_out_{{$product->id}}"
                    class="outline-btn cmn--btn btn--sm auto_bid_now bid-with-anim">@lang('Auto Bid')</button>
        @endif

        <a class="cmn--btn btn--sm bid-paused-button" id="bid_open_user_logged_in_{{$product->id}}"
              href="#">@lang('Bid Paused')</a>

    @endif

@endif

