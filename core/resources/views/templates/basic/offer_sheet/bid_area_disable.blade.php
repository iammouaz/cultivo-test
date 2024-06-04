{{--todo check the need for this view--}}
@php
        $title = __("Sign in and request access to start bidding.");
        if(Auth::check() && !$is_in_allow_product_for_logged_in_user) {
                $title = __("Request access to participate in the auction.");
        }
@endphp

<div class="p-1" data-bs-toggle="tooltip" data-bs-placement="bottom" title="{{$title}}" onmouseleave="hideBootstrapTooltip(this)">
        <button disabled
                class="cmn--btn btn--sm bid_now bid-with-anim">
                @lang('Bid')
        </button>
</div>
<div class="p-1" data-bs-toggle="tooltip" data-bs-placement="bottom" title="{{$title}}" onmouseleave="hideBootstrapTooltip(this)">
        <button disabled
                class="outline-btn cmn--btn btn--sm auto_bid_now bid-with-anim">
                @lang('Auto Bid')
        </button>
</div>
