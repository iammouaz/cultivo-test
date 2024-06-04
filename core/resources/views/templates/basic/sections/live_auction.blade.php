@php
    // $liveAuction = getContent('live_auction.content', true);
    // $liveProducts= \App\Models\Product::live()->latest()->limit(8)->get();

    $eventid = get_allowed_events(Auth::id());
    $liveEvents = \App\Models\Event::live()->get();
    $practiceEvents = \App\Models\Event::practice()->get();
    $upcomingEvents = \App\Models\Event::Upcoming()->get();
    $marketEvents = \App\Models\Event::MarketPlace()->get();
    $policy_id = get_policy_id();
@endphp

<section class="buyers-auctions-section">
    <!-- Start event register Modal -->
    <div class="modal fade" id="auctionRegisterModal">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">@lang('Confirmation Alert')</h5>
                    <button class="btn text--danger modal-close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true"></span>
                    </button>
                </div>

                <form id="auctionRegisterForm" action="" data-action="{{ route('user.request.store', ['']) }}"
                    method="GET">
                    <div class="modal-body" id="agree_ace_modal" style="display: none;">

                        @lang('I agree to the')
                        <a id="bidder_agreement_a_tag" href="" target="_blank">@lang('Bidder Agreement')
                        </a>
                        and
                        <a href="{{ route('policy', [$policy_id, 'terms-and-conditions']) }}"
                            target="_blank">@lang('terms and conditions')
                        </a>
                    </div>
                    <div class="modal-body" id="agree_mc_modal" style="display: none;">

                        @lang('I agree to the')
                        <a href="{{ route('policy', [$policy_id, 'terms-and-conditions']) }}"
                            target="_blank">@lang('terms and conditions')
                        </a>
                    </div>
                    <div class="modal-footer gap-2">
                        <button type="button" class="btn btn--danger"
                            data-bs-dismiss="modal">@lang('No')</button>
                        <button type="submit" class="btn btn--base">@lang('Yes')</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- End event register Modal -->

    <div class="top-curve d-none d-sm-block"></div>
    <h2 class="auctions-section-title d-none d-sm-flex align-items-center justify-content-center">
        @include('templates.basic.partials.explore_text')
        <img src="{{ url('/assets/templates/basic/css/icons/down-arrow.gif') }}" alt="down arrow icon" />
    </h2>
    <div class="container glass-card">
        <h2 class="auctions-section-title d-flex d-sm-none align-items-center justify-content-center">
            @include('templates.basic.partials.explore_text')
            <img src="{{ url('/assets/templates/basic/css/icons/down-arrow.gif') }}" alt="down arrow icon" />
        </h2>

        <div class="row m-5 tabs">

            <ul class="nav nav-tabs justify-content-center">
                <li class="nav-item" id="auction-tab-switch">
                    <a data-target="auction-tab" class="nav-link active" aria-current="page">AUCTIONS</a>
                </li>

                <li class="nav-item" id="offers-tab-switch">
                    <a data-target="offers-tab" class="nav-link">OFFER SHEETS</a>
                </li>
            </ul>

        </div>

        {{-- Renders auction tab content. --}}
        <div class="row mx-4 mb-5 pb-5 tab" id="auction-tab">
            @forelse ($liveEvents as $event)
                @include($activeTemplate . 'sections.event_card', [
                    'event' => $event,
                    'event_type' => 'live',
                ])
            @empty
            @endforelse

            {{-- Start Ethiopia Event --}}
            @forelse ($marketEvents as $event)
                @include($activeTemplate . 'sections.event_card', [
                    'event' => $event,
                    'event_type' => 'marketplace',
                ])
            @empty
            @endforelse
            {{-- End Ethiopia Event --}}

            @forelse ($practiceEvents as $event)
                @include($activeTemplate . 'sections.event_card', [
                    'event' => $event,
                    'event_type' => 'practice',
                ])
            @empty
            @endforelse

            @forelse ($upcomingEvents as $event)
                @include($activeTemplate . 'sections.event_card', [
                    'event' => $event,
                    'event_type' => 'upcoming',
                ])
            @empty
            @endforelse
        </div>

        {{-- Renders offers tab content. --}}
        <div class="row mx-4 mb-5 pb-5 tab d-none" id="offers-tab">

            @foreach ($offer_sheets->items() as $offer_sheet)
                @include($activeTemplate . 'sections.event_card', [
                    'event' => $offer_sheet,
                    'event_type' => 'fixed_order',
                ])
            @endforeach

        </div>

        <!-- <div class="text-center mt-sm-5 mt-4">
            <a href="{{ route('event.all') }}" class="cmn--btn">@lang('View All')</a>
        </div> -->
    </div>
</section>



<style>
    .nav-tabs .nav-link {
        color: rgba(255, 255, 255, 0.75);
        cursor: pointer;
        border: none;
    }

    .nav-tabs .nav-link:hover {
        border: none;
        color: rgba(255, 255, 255, 0.75)
    }

    .nav-tabs .nav-link.active {
        color: #fff;
        background-color: transparent;
        border: none;
        border-bottom: solid 2px #fff;
    }
</style>

<script src="{{asset('assets/global/js/jquery-3.6.0.min.js')}}"></script>
<script>
    $(document).ready(function() {

        $('.nav-link').click(function() {
            $('.nav-link').removeClass('active');
            $(this).addClass('active');

            const targetTabId = $(this).attr("data-target");

            $('.tab').addClass('d-none');

            $('#' + targetTabId).removeClass('d-none');


        })
    })
</script>
