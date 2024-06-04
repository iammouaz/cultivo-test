@php
        $title = __("Sign in and request access to start bidding.");
        if(Auth::check() && !$is_in_allow_product_for_logged_in_user) {
                $title = __("Request access to participate in the auction.");
        }
        $heroPrimaryButtonColor = getHeroPrimaryButtonColor($event);
    $eventOutlinedButtonColor = getEventOutlinedButtonColor($event);
@endphp

<div class="p-1" data-bs-toggle="tooltip" data-bs-placement="bottom" title="{{$title}}" onmouseleave="hideBootstrapTooltip(this)">
        <button disabled
        style="background-color: {{$heroPrimaryButtonColor}} !important;"
                class="cmn--btn btn--sm bid_now bid-with-anim">
                @lang('Bid')
        </button>
</div>
<div class="p-1" data-bs-toggle="tooltip" data-bs-placement="bottom" title="{{$title}}" onmouseleave="hideBootstrapTooltip(this)">
        <button disabled
        style="background-color: {{$eventOutlinedButtonColor}} !important;"
                class="outline-btn cmn--btn btn--sm auto_bid_now bid-with-anim">
                @lang('Auto Bid')
        </button>
</div>
<span style="display: none;" class="pe-none cmn--btn btn--sm bid-paused-button">@lang('Bid Paused')</span>
