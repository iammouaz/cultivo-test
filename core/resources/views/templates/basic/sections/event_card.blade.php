@php
    if (auth()->check()) {
        $userRequests = json_encode(auth()->user()->userRequestsPendingApprovalArray() ?? null);
    } else {
        $userRequests = null;
    }
    $codes = get_extention_keys('pusher');
@endphp
<div class="buyers-auction-card mx-auto mx-md-0 col-sm-9 col-md-6 col-xl-4">
    <div class="auction__item">
        <div class="auction__item-thumb">

            @if ($event_type == 'practice')
                <a href="{{ route('event.details', [$event->id, slug($event->name)]) }}" target="_blank">
                    <img src="{{ getImage(imagePath()['event']['path'] . '/thumb_practice_auction.jpg', imagePath()['event']['thumb']) }}"
                        alt="Thumbnail of the event practice">
                </a>
            @elseif($event_type == 'marketplace')
                <a href="https://shop.turnsole.tech" target="_blank">
                    <img src="{{ getImage(imagePath()['event']['path'] . '/thumb_ethiopia_select_auction.jpg', imagePath()['event']['thumb']) }}"
                        alt="Thumbnail of the marketplace event">
                </a>
            @elseif($event_type == 'fixed_order')
                <a href="{{ route('offer_sheet.activeOffers', [$event->offer_sheet_url]) }}" target="_blank">
                    <img src="{{ getImage(imagePath()['event']['path'] . '/' . $event->card_logo, imagePath()['event']['thumb']) }}"
                        alt="Thumbnail of the marketplace event">
                </a>
            @else
                <a href="{{ route('event.details', [$event->id, slug($event->name)]) }}" target="_blank">
                    <img src="{{ getImage(imagePath()['event']['path'] . '/thumb_' . $event->image, imagePath()['event']['thumb']) }}"
                        alt="Thumbnail of the event">
                </a>
            @endif
        </div>

        <div class="auction__item-content d-flex flex-column justify-content-between">
            <div class="w-100">
                <h6 class="auction__item-title w-100">
                    {{-- @if ($event_type == 'marketplace')
                        <a href="https://shop.turnsole.tech" target="_blank">Ethiopia Select</a>
                    @else --}}
                    {{-- @endif --}}

                    @if ($event_type == 'practice')
                        <a class="d-block w-100 text-truncate"
                            href="{{ route('event.details', [$event->id, slug($event->name)]) }}" target="_blank">
                            Practice Auction
                        </a>
                    @elseif($event_type == 'fixed_order')
                        <a class="d-block w-100 text-truncate"
                            href="{{ route('offer_sheet.activeOffers', [$event->offer_sheet_url]) }}" target="_blank">
                            {{ $event->name }}
                        </a>
                    @else
                        <a class="d-block w-100 text-truncate"
                            href="{{ route('event.details', [$event->id, slug($event->name)]) }}" target="_blank">
                            {{ $event->name }}
                        </a>
                    @endif
                </h6>
                <div class="auction__item-locationAndDate">
                    {{-- @if ($event_type == 'marketplace')
                        <div class="text-truncate" title="COE Jury Graded Lots">
                            <span>
                                COE Jury Graded Lots
                            </span>
                        </div>
                    @else --}}
                    {{-- @endif --}}

                    @if ($event_type == 'practice')
                        <div class="text-truncate" title="NO real bids or products">
                            <span>@lang('NO real bids or products')</span>
                        </div>
                    @else
                        <div class="text-truncate" title="{{ $event->sname }}">
                            @if($event->offer_sheet_url)

                            <span>{{$event->origins()->first()->name??''}}</span>
                            @else
                            <span>{{ $event->date }}</span>

                            @endif
                        </div>
                    @endif

                    <div class="auction-status flex-shrink-0">
                        @if ($event_type == 'upcoming')
                            <div>
                                <img src="{{ url('/assets/templates/basic/css/icons/upcoming.svg') }}"
                                    alt="upcoming event" />
                                <span class="ps-2">@lang('Upcoming')</span>
                            </div>
                        @elseif ($event_type == 'fixed_order')
                            <div>
                                {{-- <img src="{{ url('/assets/templates/basic/css/icons/upcoming.svg') }}"
                                    alt="upcoming event" /> --}}
                                @include('templates.basic.svgIcons.cart_commerce')
                                <span class="ps-2">@lang('Fixed Price')</span>
                            </div>
                        @else
                            <div>
                                <img src="{{ url('/assets/templates/basic/css/icons/live.svg') }}" alt="live event" />
                                <span class="ps-2">@lang('Live')</span>
                            </div>
                        @endif
                    </div>
                </div>
                <div class="auction__item-countdown">
                    {{-- @if ($event_type == 'marketplace')
                        Micro-lots curated by the Cup of Excellence Ethiopia National Jury will mirror several features that accompany COE winning lots including: farm information, sensory & physical analysis, and storage in a bonded warehouse to assure quality.
                    @else --}}
                    {{-- @endif --}}

                    @if ($event_type == 'practice')
                        @lang('This is a PRACTICE auction for potential bidders. Bidders can practice bidding and navigating the auction before a live auction event. There are NO real bids or products associated with this auction event. It is designed for demonstration purposes.')
                    @else
                        {{ $event->description }}
                    @endif
                </div>
            </div>

            <div class="auction__item-footer">
                <div class="btn__area">
                    @if ($event_type == 'practice')
                        <div class="div-bid-now">
                            <a class="cmn--btn auction-card-btn"
                                href="{{ route('event.details', [$event->id, slug($event->name)]) }}"
                                target="_blank">@lang('PRACTICE')</a>
                        </div>
                    @elseif($event_type == 'marketplace')
                        <div class="div-bid-now">
                            <a class="cmn--btn auction-card-btn" href="https://shop.turnsole.tech"
                                target="_blank">@lang('SHOP NOW')</a>
                        </div>
                    @else
                        @if ($event_type !== 'fixed_order')
                            <div class="div-bid-now">
                                <a class="text--btn view-coffee-link"
                                    href="{{ route('event.details', [$event->id, slug($event->name)]) }}"
                                    target="_blank">
                                    @lang('View Coffees')
                                </a>
                            </div>
                        @else
                            <div class="div-bid-now">
                                <a class="text--btn view-coffee-link"
                                    href="{{ route('offer_sheet.activeOffers', [$event->offer_sheet_url]) }}"
                                    target="_blank">
                                    @lang('View Coffees')
                                </a>
                            </div>
                        @endif
                        @if (!Auth::check() && $event_type !== 'fixed_order')
                            @php
                                $button_string_text = $event_type == 'upcoming' ? 'Register' : 'Bid Now';
                            @endphp
{{--                            @if ($event->event_type == 'ace_event')--}}
{{--                                <div class="div-bid-now mb-3 mb-md-0">--}}
{{--                                    ace event login --}}
{{--                                    <a class="cmn--btn"--}}
{{--                                        href="{{ route('user.login', ['ace_member' => 'ace_member']) }}">@lang($button_string_text)</a>--}}
{{--                                </div>--}}
{{--                            @else--}}
                                <div class="div-bid-now mb-3 mb-md-0">
                                    <a class="cmn--btn" href="{{ route('user.login',['login_type'=>$event->login_type??'normal']) }}">@lang($button_string_text)</a>
                                </div>
{{--                            @endif--}}


                        @endif

                        @if ($event_type == 'fixed_order')
                            {{--                            <button style="border-radius: 25px;" class="cmn--btn main-btn"> --}}
                            {{--                                <span>@lang('Order Now')</span> --}}
                            {{--                            </button> --}}
                            <div class="div-bid-now">
                                <a class="cmn--btn auction-card-btn"
                                    href="{{ route('offer_sheet.activeOffers', [$event->offer_sheet_url]) }}"
                                    target="_blank">@lang('Order Now')</a>
                            </div>
                        @elseif (Auth::check() && !in_array($event->id, $eventid))
                            @if (in_array($event->id, json_decode($userRequests)))
                                <div class="div-bid-now">
                                    <button style="border-radius: 25px; opacity: 0.6;" class="cmn--btn main-btn">
                                        <span id="btn-text"> @lang('Access Pending')</span>
                                    </button>
                                </div>
                            @else
                                <div class="div-bid-now">
                                    <button style="border-radius: 25px;" id="request-access-btn-{{ $event->id }}"
                                        class="cmn--btn main-btn" onclick="saveEventRequest({{ $event->id }})">
                                        <span id="btn-text-{{ $event->id }}">@lang('Request Access')</span>
                                        <span id="loader-{{ $event->id }}" style="display: none;">Loading...</span>
                                    </button>
                                </div>
                            @endif
                        @elseif(Auth::check() && in_array($event->id, $eventid))
                            <div class="div-bid-now">
                                <a class="cmn--btn auction-card-btn"
                                    href="{{ route('event.details', [$event->id, slug($event->name)]) }}"
                                    target="_blank">@lang('BID NOW')</a>
                            </div>
                        @endif
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>


@push('script')
    <script>
        function saveEventRequest(event_id) {

            $(`#request-access-btn-${event_id}`).prop('disabled', true);
            $(`#btn-text-${event_id}`).hide();
            $(`#loader-${event_id}`).show();

            $.ajax({
                url: '{{ url('user/store-request') }}/' + event_id,
                type: 'GET',
                success: function(response) {
                    iziToast.success({
                        message: "@lang('Your request has been submitted, your access will be granted once the Admin approves it.')",
                        position: "topRight"
                    });
                    var button = $(`#request-access-btn-${event_id}`);
                    console.log(button)
                    button.text('ACCESS PENDING');
                    button.prop('disabled', true);
                    button.css('opacity', '0.6');

                    try {
                        var pusher = new Pusher('{{ $codes['PUSHER_APP_KEY'] }}', {
                            cluster: '{{ $codes['PUSHER_APP_CLUSTER'] }}'
                        });

                        var userId = '{{ auth()->user()->id ?? null }}';
                        if (userId) {
                            var userChannel = pusher.subscribe(`request.${event_id}.user.` + userId);
                            userChannel.bind(`request.${event_id}.user.` + userId, function(data) {
                                innerModal(data)
                            });

                        }
                    } catch (error) {
                        showFatalErrorOverlay(error);
                    }
                },
                error: function(error) {
                    console.error('Error in AJAX request', error);
                    // Re-enable the button and hide loader
                    $(`#request-access-btn-${event_id}`).prop('disabled', false);
                    $('#loader').hide();
                    $('#btn-text').show();
                }
            });
        }
    </script>
@endpush
