@extends($activeTemplate . 'layouts.frontend', ['event_type' => $event->event_type,'login_type'=>$event->login_type??'normal'])

@php
    $overlayColor = null;
    $is_practice = $event->practice == 1;
    $order_sample_link_ace = 'https://allianceforcoffeeexcellence.org/join-us/';
    $order_sample_link_MC = 'https://mcultivo.mybigcommerce.com/';
    $policy_id = get_policy_id();
    $codes=get_extention_keys('pusher');


    $heroShowActionName = getHeroShowActionName($event);
    $heroTextColor = getHeroTextColor($event);
    $heroPrimaryButtonColor = getHeroPrimaryButtonColor($event);
    $eventOutlinedButtonColor = getEventOutlinedButtonColor($event);


    $heroImageOverlay = getHeroImageOverlay($event);
    if ($event->hero_image_overlay !== null) {
        $overlay = json_decode($event->hero_image_overlay);
        $overlayColor = str_replace('1)', '0.5)', $overlay->color ?? null);
    }
@endphp

@php
    if (auth()->check()) {
        $userRequests = json_encode(auth()->user()->userRequestsPendingTermsAcceptArray() ?? null);
    } else {
        $userRequests = null;
    }
@endphp


@section('content')
    <style>
        .event-hero-section {
            position: relative;
        }

        .backdrop {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
        }

        .event-hero-section-content {
            position: relative;
            z-index: 1;
        }
    </style>
    <!-- Event Details -->
    <section id="event-details" @if ($event->event_type == 'm_cultivo_event') class="mc-event" @endif>

        <div class="cup-progress-container d-none">
            <div style="color: var(--main-svg-color) !important" class="spinner-border text-primary" role="status">
                <span class="sr-only">Loading...</span>
              </div>
            <p>@lang('Refreshing the bid')...</p>
        </div>

        <div class="event-hero-section w-100" id="banner">
            {{-- @if ($event->event_type == 'm_cultivo_event' && $event->banner_image) style='background-image: url("{{ getImage(imagePath()['event']['path'] . '/' . $event->banner_image, imagePath()['event']['size']) }}");' @endif
            @if ($event->event_type == 'ace_event') style='background-image: url("{{ getImage(imagePath()['event']['path'] . '/Coffee-beans-on-the-tree.jpg', imagePath()['event']['size']) }}");' @endif> --}}
            <div style="background-color: {{$overlayColor ?? "none"}};" class="backdrop"></div>

            <div class="event-hero-section-content">
                <div class="logos d-flex align-items-center justify-content-center flex-wrap">
{{--                    @if ($event->event_type == 'ace_event')--}}
{{--                        <img src="{{ getImage(imagePath()['event']['path'] . '/ace-logo-white.png', imagePath()['event']['thumb']) }}"--}}
{{--                            alt="ace logo">--}}
{{--                    @else--}}
{{--                    @endif--}}
                    @if($event->logo)
                        <img src="{{ getImage(imagePath()['event_logo']['path'] . '/' . $event->logo, imagePath()['event_logo']['thumb']) }}"
                            alt="Logo">
                    @endif
                </div>

                @if($heroShowActionName)

                    @if ($event->event_type == 'm_cultivo_event')
                        @if ($is_practice)
                            <h2 class="title mt-0" @if($heroTextColor) style="color: {{$heroTextColor}} !important;" @endif>@lang("Practice Auction")</h2>
                        @else
                            <h2 class="title mt-0" @if($heroTextColor) style="color: {{$heroTextColor}} !important;" @endif>{{ $event->name }}</h2>
                        @endif

                        @if ($is_practice)
                            <p class="event-date" @if($heroTextColor) style="color: {{$heroTextColor}} !important;" @endif>
                                @lang("NO real bids or products")
                            </p>
                        @else
                            <p class="event-date" @if($heroTextColor) style="color: {{$heroTextColor}} !important;" @endif>
                                {{ $event->date }}
                            </p>
                        @endif

                        <p class="event-description" @if($heroTextColor) style="color: {{$heroTextColor}} !important;" @endif>
                            @if ($is_practice)
                                @lang("This is a PRACTICE auction for potential bidders. Bidders can practice bidding and navigating the auction before a live auction event. There are NO real bids or products associated with this auction event. It is designed for demonstration purposes.")
                            @else
                                {{ $event->description }}
                            @endif
                        </p>
                    @else
                        @if ($is_practice)
                        <h2 class="title mt-0" @if($heroTextColor) style="color: {{$heroTextColor}};" @endif>@lang("Practice Auction")</h2>
                        @else
                        <h2  class="title mt-0" @if($heroTextColor) style="color: {{$heroTextColor}};" @endif>{{ $event->name }}</h2>
                        @endif

                        @if ($is_practice)
                        <p class="event-date" @if($heroTextColor) style="color: {{$heroTextColor}};" @endif>
                            @lang("NO real bids or products")
                        </p>
                        @else
                        <p  class="event-date" @if($heroTextColor) style="color: {{$heroTextColor}};" @endif>
                            {{ $event->date }}
                        </p>
                        @endif

                        <p class="event-description" @if($heroTextColor) style="color: {{$heroTextColor}};" @endif>
                        @if ($is_practice)
                            @lang("This is a PRACTICE auction for potential bidders. Bidders can practice bidding and navigating the auction before a live auction event. There are NO real bids or products associated with this auction event. It is designed for demonstration purposes.")
                        @else
                            {{ $event->description }}
                        @endif
                        </p>
                    @endif

                @endif

                @if (Auth::check() && in_array($event->id, $pending_event_ids))
                    <div class="div-bid-now mb-0 mt-auto hero__btns">
{{--                        @if ($event->start_date > \Carbon\Carbon::now())--}}
{{--                            @if ($event->event_type == 'ace_event')--}}
{{--                                <a class="cmn--btn order-samples me-0 me-sm-3 " style="background-color: {{$heroPrimaryButtonColor}} !important;" href="{{ $order_sample_link_ace }}"--}}
{{--                                    target="_blank">@lang('Order Samples')</a>--}}
{{--                            @else--}}
{{--                                <a class="cmn--btn order-samples me-0 me-sm-3 " style="background-color: {{$heroPrimaryButtonColor}} !important;" href="{{ $order_sample_link_MC }}"--}}
{{--                                    target="_blank">@lang('Order Samples')</a>--}}
{{--                            @endif--}}
{{--                        @endif--}}
                        <a style="background-color: {{$eventOutlinedButtonColor}} !important; padding: 16px 30px !important;" class="outline-btn access-pending position-relative overflow-visible" disabled="disabled">
                            @lang('Access Pending')
                            <div class="custom-tooltip custom-tooltip-bottom">
                                @lang('Your request has been sent. Access will be granted within 24 hours before auction day and within minutes on auction day.')
                            </div>
                        </a>
                        @if($event->show_sample_set_button)
                            @if ($event->sample_set_cart_config == 'external_url')
                                <a class="cmn--btn order-samples me-0 me-sm-5 " style="background-color: {{$heroPrimaryButtonColor}} !important; padding: 16px 30px !important;" href="{{ $event->sample_set_external_url }}" target="_blank">@lang($event->sample_set_button_lable)</a>
                            @else
                                <a class="cmn--btn order-samples me-0 me-sm-5 " style="background-color: {{$heroPrimaryButtonColor}} !important; padding: 16px 30px !important;" onclick="addSampleSetToCart(this)" href="#" >@lang($event->sample_set_button_lable)</a>
                        @endif
                    @endif
                    </div>
                @elseif(Auth::check() && !in_array($event->id, $eventid))
                    <div class="div-bid-now mb-0 mt-auto hero__btns">
{{--                        @if ($event->start_date > \Carbon\Carbon::now())--}}
{{--                            @if ($event->event_type == 'ace_event')--}}
{{--                                <a class="cmn--btn order-samples me-0 me-sm-3 " style="background-color: {{$heroPrimaryButtonColor}} !important;" href="{{ $order_sample_link_ace }}"--}}
{{--                                    target="_blank">@lang('Order Samples')</a>--}}
{{--                            @else--}}
{{--                                <a class="cmn--btn order-samples me-0 me-sm-3 " style="background-color: {{$heroPrimaryButtonColor}} !important;" href="{{ $order_sample_link_MC }}"--}}
{{--                                    target="_blank">@lang('Order Samples')</a>--}}
{{--                            @endif--}}
{{--                        @endif--}}
                        @if($event->show_sample_set_button)
                            @if ($event->sample_set_cart_config == 'external_url')
                                <a class="cmn--btn order-samples me-0 me-sm-5 " style="background-color: {{$heroPrimaryButtonColor}} !important; padding: 16px 30px !important;" href="{{ $event->sample_set_external_url }}" target="_blank">@lang($event->sample_set_button_lable)</a>
                            @else
                                <a class="cmn--btn order-samples me-0 me-sm-5 " style="background-color: {{$heroPrimaryButtonColor}} !important; padding: 16px 30px !important;" onclick="addSampleSetToCart(this)" href="#" >@lang($event->sample_set_button_lable)</a>

                            @endif
                        @endif
                        <a style="border-radius: 25px; background-color: {{$eventOutlinedButtonColor}} !important;" id="request-access-btn" class="main-btn outline-btn" onclick="saveEventRequest({{ $event->id }})">
                            <span style="text-transform: var(--button-outlined-text_transform) !important;" id="btn-text">@lang('Request Access')</span>
                            <span id="loader" style="display: none;">@lang('Loading...')</span>
                        </a>
                    </div>
                @elseif (!Auth::check())

{{--                    @if ($event->event_type == 'ace_event')--}}
{{--                        <div class="div-bid-now mb-0">--}}
{{--                            @if ($event->start_date > \Carbon\Carbon::now())--}}
{{--                                <a class="cmn--btn order-samples me-0 me-sm-3 " style="background-color: {{$heroPrimaryButtonColor}} !important; padding: 16px 30px !important;" href="{{ $order_sample_link_ace }}"--}}
{{--                                    target="_blank">@lang('Order Samples')</a>--}}
{{--                            @endif--}}
{{--                            ace event login --}}
{{--                            <a class="main-btn outline-btn" style="background-color: {{$eventOutlinedButtonColor}} !important; padding: 16px 30px !important;"--}}
{{--                                href="{{ route('user.login', ['ace_member' => 'ace_member']) }}">@lang('sign in to bid')</a>--}}
{{--                                @if($event->show_sample_set_button)--}}
{{--                                    @if ($event->sample_set_cart_config == 'external_url')--}}
{{--                                        <a class="cmn--btn order-samples me-0 me-sm-5 " style="background-color: {{$heroPrimaryButtonColor}} !important; padding: 16px 30px !important;" href="{{ $event->sample_set_external_url }}" target="_blank">@lang($event->sample_set_button_lable)</a>--}}
{{--                                    @else--}}
{{--                                        <a class="cmn--btn order-samples me-0 me-sm-5 " style=" background-color: {{$heroPrimaryButtonColor}} !important; padding: 16px 30px !important;" href="{{ route('user.login') }}" >@lang($event->sample_set_button_lable)</a>--}}

{{--                                    @endif--}}
{{--                                @endif--}}
{{--                        </div>--}}
{{--                    @else--}}
                        <div class="div-bid-now mb-0 mt-auto hero__btns">
{{--                            @if ($event->start_date > \Carbon\Carbon::now())--}}
{{--                                <a class="cmn--btn order-samples me-0 me-sm-3 " style="background-color: {{$heroPrimaryButtonColor}} !important;" href="{{ $order_sample_link_MC }}"--}}
{{--                                    target="_blank">@lang('Order Samples')</a>--}}
{{--                            @endif--}}
{{--                            ace event login --}}
                            <a class="main-btn outline-btn" style="background-color: {{$eventOutlinedButtonColor}} !important; padding: 16px 30px !important;" href="{{ route('user.login', ['login_type'=>$event->login_type??'normal']) }}">@lang('sign in to bid')</a>
                            @if($event->show_sample_set_button)
                                @if ($event->sample_set_cart_config == 'external_url')
                                    <a class="cmn--btn order-samples me-0 me-sm-5 " style="background-color: {{$heroPrimaryButtonColor}} !important; padding: 16px 30px !important;" href="{{ $event->sample_set_external_url }}" target="_blank">@lang($event->sample_set_button_lable)</a>
                                @else
                                    <a class="cmn--btn order-samples me-0 me-sm-5 " style=" background-color: {{$heroPrimaryButtonColor}} !important; padding: 16px 30px !important;"  href="{{ route('user.login') }}" >@lang($event->sample_set_button_lable)</a>
                                @endif
                            @endif
                        </div>
{{--                    @endif--}}
                @else
                    @if($event->show_sample_set_button)
                        <div class="div-bid-now mb-0 mt-auto hero__btns">
                            @if ($event->sample_set_cart_config == 'external_url')
                                <a class="cmn--btn order-samples me-0 me-sm-5 " style="background-color: {{$heroPrimaryButtonColor}} !important; padding: 16px 30px !important;" href="{{ $event->sample_set_external_url }}" target="_blank">@lang($event->sample_set_button_lable)</a>
                            @else

                                <a class="cmn--btn order-samples me-0 me-sm-5 " style=" background-color: {{$heroPrimaryButtonColor}} !important; padding: 16px 30px !important;" onclick="addSampleSetToCart(this)"  href="#" >@lang($event->sample_set_button_lable)</a>
                            @endif
                        </div>
                    @endif
                @endif
                <!-- <div class="max-banner my-4">
                                                        @php
                                                            showAd('780x80');
                                                        @endphp
                                                        </div> -->
            </div>
        </div>

        <div class="container position-relative">
            <div class="row">
                <h4 class="title my-4" id="all_winners_go_to_cart"
                    @if (!$is_event_ended) style="display: none" @endif>
                    @if (!get_is_stop_cart())
                        @lang('All Winners please proceed to your') <a
                            href="{{ route('user.checkout.index', ['event_id' => $event->id]) }}">@lang('cart')</a>
                        @lang('for')
                        @lang('checkout').
                    @else
                        @lang('All Winners please check your emails.')
                    @endif
                </h4>

                <h4 class="title my-4" id="live_disable">
                    @lang('The live update mode has been suspended, please') <a href="{{ route('user.login') }}">@lang('login')</a>
                    @lang('to continue watching the live update.')
                </h4>
            </div>

            <div class="row auction-data-overview">
                <ul id='owl-one' class="owl-theme owl-carousel auction-data-list">
                    <li class="shadow-sm slide-item d-flex flex-column align-items-center">
                        <svg width="50" height="50" viewBox="0 0 26 26" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M3.83504 26C4.13914 25.9156 4.44324 25.865 4.73045 25.7469C5.35554 25.5107 5.84548 25.1057 6.26785 24.5827C8.64997 21.6637 11.0152 18.7279 13.4142 15.8257C13.9041 15.2183 14.5292 14.729 15.1205 14.1722C15.4584 14.5097 15.8301 14.864 16.1849 15.2352C16.2187 15.2689 16.2187 15.3871 16.1849 15.4545C15.7794 16.6188 16.2356 17.7661 17.3337 18.3229C18.1784 18.7616 19.2259 18.5929 19.9693 17.8505C21.7432 16.0957 23.5171 14.3241 25.291 12.5356C25.9499 11.8607 26.1695 11.0676 25.8654 10.1565C25.5782 9.36349 24.9869 8.8573 24.1422 8.67171C23.7198 8.58734 23.3144 8.62109 22.9258 8.77294C22.7906 8.82356 22.7062 8.80669 22.6048 8.70545C20.8478 6.93381 19.0739 5.17905 17.3168 3.40741C17.2492 3.33992 17.2155 3.20494 17.2324 3.13745C17.6378 2.07447 17.2492 0.960866 16.3369 0.370321C15.4078 -0.220225 14.2758 -0.102116 13.448 0.707775C11.6572 2.46254 9.88326 4.23418 8.12624 6.02269C6.90983 7.2544 7.3322 9.19476 8.90338 9.80218C9.42711 10.0047 9.96773 10.0047 10.4915 9.80218C10.6266 9.75156 10.7111 9.76843 10.8294 9.86967C11.1672 10.2071 11.5051 10.5446 11.8599 10.8989C11.7923 10.9833 11.7248 11.0339 11.6741 11.1014C10.728 12.1137 9.68053 12.9911 8.59928 13.8685C6.20027 15.8089 3.80125 17.7661 1.40224 19.7065C0.658882 20.3139 0.168945 21.0563 0.0337887 22.018C0.0337887 22.0518 0 22.1024 0 22.1361C0 22.3892 0 22.6423 0 22.8954C0.0168953 22.946 0.0337887 23.0135 0.0506821 23.0641C0.253416 24.1946 0.861618 25.0551 1.89218 25.5782C2.26386 25.7638 2.70311 25.8481 3.10858 25.9831C3.32821 26 3.58163 26 3.83504 26ZM17.2661 14.0879C15.4753 12.2993 13.7014 10.5277 11.9444 8.77294C13.3466 7.3725 14.7827 5.93832 16.168 4.55476C17.925 6.30952 19.7158 8.09803 21.4898 9.86967C20.0875 11.2701 18.6684 12.6874 17.2661 14.0879ZM11.8768 15.3027C11.8261 15.3702 11.7754 15.4377 11.7079 15.5052C9.98463 17.6311 8.2445 19.7571 6.52126 21.8831C5.94685 22.5748 5.40623 23.3004 4.79803 23.9415C3.81815 24.9539 1.99355 24.4646 1.60497 23.0979C1.35156 22.1868 1.63876 21.4781 2.34833 20.8876C4.51082 19.1328 6.67331 17.378 8.81891 15.6233C9.44401 15.1171 10.086 14.594 10.6942 14.1047C11.0997 14.5097 11.4713 14.8977 11.8768 15.3027ZM24.4801 10.8483C24.497 11.1689 24.3449 11.3713 24.1591 11.5569C22.419 13.278 20.6957 15.0159 18.9725 16.7369C18.9049 16.8044 18.8542 16.855 18.7866 16.9056C18.3643 17.2262 17.773 17.0237 17.6209 16.5175C17.5027 16.1632 17.6547 15.8932 17.9081 15.657C19.6145 13.936 21.3377 12.1981 23.0778 10.4771C23.1454 10.4096 23.213 10.3421 23.2806 10.2915C23.534 10.1228 23.7874 10.1059 24.0577 10.2409C24.3111 10.3759 24.4463 10.5952 24.4801 10.8483ZM15.847 2.29381C15.8639 2.56378 15.7119 2.74938 15.5429 2.93498C13.8028 4.67287 12.0626 6.41076 10.3394 8.13178C10.2887 8.18239 10.238 8.23301 10.1705 8.28363C9.73121 8.60421 9.12301 8.40174 8.98785 7.86181C8.90338 7.52436 9.03854 7.27127 9.27506 7.05192C10.9814 5.3309 12.7215 3.59301 14.4617 1.85512C14.5292 1.78763 14.5968 1.72014 14.6644 1.66952C14.9178 1.51767 15.1712 1.50079 15.4415 1.63578C15.7119 1.77076 15.8301 1.9901 15.847 2.29381ZM12.9581 14.0879C12.6033 13.7335 12.2485 13.3961 11.9275 13.0755C12.2654 12.738 12.6033 12.3837 12.9243 12.0631C13.2622 12.4006 13.6169 12.7549 13.9548 13.0924C13.6338 13.4129 13.279 13.7673 12.9581 14.0879Z" fill="url(#paint0_linear_1191_2280)"/>
                            <defs>
                            <linearGradient id="paint0_linear_1191_2280" x1="24.1456" y1="20.0927" x2="2.32034" y2="6.71568" gradientUnits="userSpaceOnUse">
                            <stop stop-color="#008CBD"/>
                            <stop offset="0.505208" stop-color="#008E8F"/>
                            <stop offset="1" stop-color="#008C64"/>
                            </linearGradient>
                            </defs>
                            </svg>


                        <div class="countdown-area" id="countdown_area_2">
                            @if ($event->start_status == 'started')
                                @include('templates/basic/product/time_countdown', ['event' => $event])
                            @else
                                <div id="start_counter" style="display: none">{{ $event->start_counter }}</div>

                                <ul class="countdown sidebar-countdown">
                                    <li>
                                        <span class="days auction-cards-number">00</span>
                                    </li>
                                    <li>
                                        <span class="hours auction-cards-number">00</span>
                                    </li>
                                    <li>
                                        <span class="minutes auction-cards-number">00</span>
                                    </li>
                                    <li>
                                        <span class="seconds auction-cards-number">00</span>
                                    </li>
                                </ul>
                            @endif
                        </div>

                        <p class="paragraph-level-2">@lang('Auction Timer')</p>
                    </li>
                    <li class="shadow-sm slide-item d-flex flex-column align-items-center">
                        <svg width="56" height="56" viewBox="0 0 26 26" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M3.83504 26C4.13914 25.9156 4.44324 25.865 4.73045 25.7469C5.35554 25.5107 5.84548 25.1057 6.26785 24.5827C8.64997 21.6637 11.0152 18.7279 13.4142 15.8257C13.9041 15.2183 14.5292 14.729 15.1205 14.1722C15.4584 14.5097 15.8301 14.864 16.1849 15.2352C16.2187 15.2689 16.2187 15.3871 16.1849 15.4545C15.7794 16.6188 16.2356 17.7661 17.3337 18.3229C18.1784 18.7616 19.2259 18.5929 19.9693 17.8505C21.7432 16.0957 23.5171 14.3241 25.291 12.5356C25.9499 11.8607 26.1695 11.0676 25.8654 10.1565C25.5782 9.36349 24.9869 8.8573 24.1422 8.67171C23.7198 8.58734 23.3144 8.62109 22.9258 8.77294C22.7906 8.82356 22.7062 8.80669 22.6048 8.70545C20.8478 6.93381 19.0739 5.17905 17.3168 3.40741C17.2492 3.33992 17.2155 3.20494 17.2324 3.13745C17.6378 2.07447 17.2492 0.960866 16.3369 0.370321C15.4078 -0.220225 14.2758 -0.102116 13.448 0.707775C11.6572 2.46254 9.88326 4.23418 8.12624 6.02269C6.90983 7.2544 7.3322 9.19476 8.90338 9.80218C9.42711 10.0047 9.96773 10.0047 10.4915 9.80218C10.6266 9.75156 10.7111 9.76843 10.8294 9.86967C11.1672 10.2071 11.5051 10.5446 11.8599 10.8989C11.7923 10.9833 11.7248 11.0339 11.6741 11.1014C10.728 12.1137 9.68053 12.9911 8.59928 13.8685C6.20027 15.8089 3.80125 17.7661 1.40224 19.7065C0.658882 20.3139 0.168945 21.0563 0.0337887 22.018C0.0337887 22.0518 0 22.1024 0 22.1361C0 22.3892 0 22.6423 0 22.8954C0.0168953 22.946 0.0337887 23.0135 0.0506821 23.0641C0.253416 24.1946 0.861618 25.0551 1.89218 25.5782C2.26386 25.7638 2.70311 25.8481 3.10858 25.9831C3.32821 26 3.58163 26 3.83504 26ZM17.2661 14.0879C15.4753 12.2993 13.7014 10.5277 11.9444 8.77294C13.3466 7.3725 14.7827 5.93832 16.168 4.55476C17.925 6.30952 19.7158 8.09803 21.4898 9.86967C20.0875 11.2701 18.6684 12.6874 17.2661 14.0879ZM11.8768 15.3027C11.8261 15.3702 11.7754 15.4377 11.7079 15.5052C9.98463 17.6311 8.2445 19.7571 6.52126 21.8831C5.94685 22.5748 5.40623 23.3004 4.79803 23.9415C3.81815 24.9539 1.99355 24.4646 1.60497 23.0979C1.35156 22.1868 1.63876 21.4781 2.34833 20.8876C4.51082 19.1328 6.67331 17.378 8.81891 15.6233C9.44401 15.1171 10.086 14.594 10.6942 14.1047C11.0997 14.5097 11.4713 14.8977 11.8768 15.3027ZM24.4801 10.8483C24.497 11.1689 24.3449 11.3713 24.1591 11.5569C22.419 13.278 20.6957 15.0159 18.9725 16.7369C18.9049 16.8044 18.8542 16.855 18.7866 16.9056C18.3643 17.2262 17.773 17.0237 17.6209 16.5175C17.5027 16.1632 17.6547 15.8932 17.9081 15.657C19.6145 13.936 21.3377 12.1981 23.0778 10.4771C23.1454 10.4096 23.213 10.3421 23.2806 10.2915C23.534 10.1228 23.7874 10.1059 24.0577 10.2409C24.3111 10.3759 24.4463 10.5952 24.4801 10.8483ZM15.847 2.29381C15.8639 2.56378 15.7119 2.74938 15.5429 2.93498C13.8028 4.67287 12.0626 6.41076 10.3394 8.13178C10.2887 8.18239 10.238 8.23301 10.1705 8.28363C9.73121 8.60421 9.12301 8.40174 8.98785 7.86181C8.90338 7.52436 9.03854 7.27127 9.27506 7.05192C10.9814 5.3309 12.7215 3.59301 14.4617 1.85512C14.5292 1.78763 14.5968 1.72014 14.6644 1.66952C14.9178 1.51767 15.1712 1.50079 15.4415 1.63578C15.7119 1.77076 15.8301 1.9901 15.847 2.29381ZM12.9581 14.0879C12.6033 13.7335 12.2485 13.3961 11.9275 13.0755C12.2654 12.738 12.6033 12.3837 12.9243 12.0631C13.2622 12.4006 13.6169 12.7549 13.9548 13.0924C13.6338 13.4129 13.279 13.7673 12.9581 14.0879Z" fill="url(#paint0_linear_1191_2280)"/>
                            <defs>
                            <linearGradient id="paint0_linear_1191_2280" x1="24.1456" y1="20.0927" x2="2.32034" y2="6.71568" gradientUnits="userSpaceOnUse">
                            <stop stop-color="#008CBD"/>
                            <stop offset="0.505208" stop-color="#008E8F"/>
                            <stop offset="1" stop-color="#008C64"/>
                            </linearGradient>
                            </defs>
                            </svg>

                        <p class="auction-data-card-title heading-2 auction-cards-number" id="total_bid_count">{{$total_bid_count}}</p>
                        <p class="paragraph-level-2">@lang('Total Number of Bids')</p>
                    </li>
                    <li class="shadow-sm slide-item d-flex flex-column align-items-center">
                        <svg width="33" height="56" viewBox="0 0 33 56" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M0 39.124C0.587584 45.792 6.00686 50.524 14.2569 51.136V56H18.3977V51.136C27.41 50.42 33 45.384 33 37.936C33 31.576 29.2403 27.896 21.2642 25.824L18.3977 25.076V9.868C22.8523 10.308 25.8577 12.724 26.616 16.332H32.4521C31.7931 9.932 26.3381 5.34 18.3977 4.832V0H14.2569V4.932C6.55871 5.852 1.27442 10.82 1.27442 17.556C1.27442 23.372 5.1096 27.488 11.839 29.224L14.2608 29.872V45.996C9.69911 45.316 6.55871 42.796 5.80041 39.124H0ZM13.4628 23.78C9.32194 22.728 7.11056 20.48 7.11056 17.316C7.11056 13.54 9.90556 10.752 14.2569 10.004V23.984L13.4628 23.784V23.78ZM19.7794 31.268C24.889 32.56 27.1321 34.704 27.1321 38.344C27.1321 42.732 23.8527 45.656 18.3977 46.1V30.92L19.7794 31.264V31.268Z" fill="url(#paint0_linear_1949_2966)"/>
                            <defs>
                            <linearGradient id="paint0_linear_1949_2966" x1="2.35362" y1="43.2765" x2="36.0637" y2="31.1011" gradientUnits="userSpaceOnUse">
                            <stop stop-color="#008CBD"/>
                            <stop offset="0.505208" stop-color="#008E8F"/>
                            <stop offset="1" stop-color="#008C64"/>
                            </linearGradient>
                            </defs>
                            </svg>

                        <p class="auction-data-card-title heading-2 auction-cards-number"
                           id="total_auction_value">{{$total_auction_value}}</p>
                        <p class="paragraph-level-2">@lang('Total Auction Value')</p>
                    </li>
                    <li class="shadow-sm slide-item d-flex flex-column align-items-center">
                        <svg width="66" height="56" viewBox="0 0 66 56" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M2.92282 0C2.25616 0.0769231 1.65359 0.448718 1.30744 1.02564C0.961286 1.60256 0.897183 2.30769 1.15359 2.9359L4.02539 10.1154C4.17923 10.5128 4.46129 10.859 4.80744 11.1026C5.15359 11.3462 5.58949 11.4744 6.01257 11.4744H27.551V15.7821H20.3715C16.4356 15.7821 13.1921 19.0256 13.1921 22.9615V48.8077C13.1921 52.7436 16.4356 55.9872 20.3715 55.9872H46.2177C50.1536 55.9872 53.3972 52.7436 53.3972 48.8077V22.9744C53.3972 19.0385 50.1536 15.7949 46.2177 15.7949H39.0382V11.4872H60.5767C61.0126 11.4872 61.4356 11.359 61.7818 11.1154C62.1408 10.8718 62.41 10.5256 62.5639 10.1282L65.4356 2.94872C65.6921 2.28205 65.6151 1.53846 65.2177 0.948718C64.8459 0.358974 64.1792 0 63.4741 0H3.15359C3.07667 0 2.99975 0 2.92282 0ZM6.33308 4.30769H60.2818L59.1408 7.17949H7.48693L6.33308 4.30769ZM31.8715 11.4872H34.7433V15.7949H31.8715V11.4872ZM20.3844 20.1026H46.2305C47.8587 20.1026 49.1023 21.3462 49.1023 22.9744V48.8205C49.1023 50.4487 47.8587 51.6923 46.2305 51.6923H20.3844C18.7562 51.6923 17.5126 50.4487 17.5126 48.8205V22.9744C17.5126 21.3462 18.7562 20.1026 20.3844 20.1026ZM33.3074 22.9744C26.1921 22.9744 20.3844 28.7821 20.3844 35.8974C20.3844 43.0128 26.1921 48.8205 33.3074 48.8205C40.4228 48.8205 46.2305 43.0128 46.2305 35.8974C46.2305 28.7821 40.4228 22.9744 33.3074 22.9744ZM33.3074 27.2821C38.0895 27.2821 41.9228 31.1154 41.9228 35.8974C41.9228 40.6795 38.0895 44.5128 33.3074 44.5128C28.5254 44.5128 24.6921 40.6795 24.6921 35.8974C24.6921 31.1154 28.5254 27.2821 33.3074 27.2821ZM29.4741 30.1282C28.9356 30.1923 28.4228 30.4487 28.0767 30.859C27.7177 31.2692 27.5382 31.8077 27.551 32.359C27.5767 32.9103 27.8074 33.4231 28.1921 33.8077L30.4613 36.0769C30.551 37.5769 31.7818 38.7692 33.3074 38.7692C34.8972 38.7692 36.1792 37.4872 36.1792 35.8974C36.1792 34.3846 34.9997 33.1538 33.5126 33.0513L31.2433 30.7821C30.7818 30.3077 30.128 30.0641 29.4741 30.1282Z" fill="url(#paint0_linear_1949_2961)"/>
                            <defs>
                            <linearGradient id="paint0_linear_1949_2961" x1="5.60366" y1="43.2666" x2="55.3284" y2="8.10829" gradientUnits="userSpaceOnUse">
                            <stop stop-color="#008CBD"/>
                            <stop offset="0.505208" stop-color="#008E8F"/>
                            <stop offset="1" stop-color="#008C64"/>
                            </linearGradient>
                            </defs>
                            </svg>

                        <p class="auction-data-card-title heading-2 auction-cards-number"
                           id="auction_weight_avg">{{$auction_weight_avg}}</p>
                        <p class="paragraph-level-2">@lang('Weighted Average') (US$/lb)</p>
                    </li>
                    <li class="shadow-sm slide-item d-flex flex-column align-items-center">
                        <svg width="56" height="56" viewBox="0 0 63 60" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M11.1058 56.5437C10.8035 58.2642 12.5008 59.6088 13.9965 58.8416L31.0078 50.0996L48.0152 58.8416C49.5109 59.6088 51.2082 58.2642 50.9059 56.5476L47.6897 38.2189L61.3374 25.2144C62.6162 23.9976 61.9574 21.7734 60.2447 21.5331L41.2649 18.8361L32.8019 2.06898C32.6407 1.7291 32.3863 1.44194 32.0684 1.24086C31.7505 1.03978 31.382 0.933044 31.0058 0.933044C30.6297 0.933044 30.2612 1.03978 29.9433 1.24086C29.6253 1.44194 29.371 1.7291 29.2098 2.06898L20.7468 18.84L1.76703 21.537C0.0581561 21.7772 -0.604469 24.0015 0.670406 25.2182L14.322 38.2227L11.1058 56.5515V56.5437ZM30.1127 45.8216L15.8294 53.1609L18.5187 37.8275C18.5817 37.4745 18.557 37.1115 18.4469 36.7703C18.3367 36.4291 18.1445 36.1201 17.887 35.8706L6.62628 25.1369L22.3278 22.9049C22.6529 22.8558 22.9613 22.7286 23.2265 22.5342C23.4917 22.3399 23.7059 22.0841 23.8507 21.7889L31 7.6141L38.1572 21.7889C38.3019 22.0841 38.5161 22.3399 38.7813 22.5342C39.0465 22.7286 39.3549 22.8558 39.68 22.9049L55.3815 25.133L44.1208 35.8667C43.8627 36.1166 43.6701 36.4262 43.56 36.7682C43.4498 37.1101 43.4255 37.4739 43.4892 37.8275L46.1784 53.1609L31.8952 45.8216C31.619 45.6792 31.3127 45.6049 31.002 45.6049C30.6912 45.6049 30.385 45.6792 30.1088 45.8216H30.1127Z" fill="url(#paint0_linear_1949_2957)"/>
                            <defs>
                            <linearGradient id="paint0_linear_1949_2957" x1="4.42727" y1="45.8552" x2="54.5877" y2="13.0635" gradientUnits="userSpaceOnUse">
                            <stop stop-color="#008CBD"/>
                            <stop offset="0.505208" stop-color="#008E8F"/>
                            <stop offset="1" stop-color="#008C64"/>
                            </linearGradient>
                            </defs>
                            </svg>

                        <p class="auction-data-card-title heading-2 auction-cards-number" id="highest_bid">{{$highest_bid}}</p>
                        <p class="paragraph-level-2">@lang('Highest lot price') (US$/lb)</p>
                    </li>
                </ul>
            </div>
        </div>

        <div class="container position-relative">
            @if ($event->event_type == 'ace_event')
                @include('templates.basic.event.countdown_widget_ACE', ['event' => $event])
            @else
                @include('templates.basic.event.countdown_widget', ['event' => $event])
            @endif
            <div class="row auction-tabs">
                <div class="col">
                    <ul class="event-details-tabs nav nav-tabs nav--tabs mb-4">
                        <li onclick="auctionViewTabClicked({{ $event->id }})">
                            <a href="#auction-view" class="active" data-bs-toggle="tab">
                                @lang('Auction')
                            </a>
                        </li>
                        <li onclick="overviewTabClicked({{ $event->id }})">
                            <a href="#overview" data-bs-toggle="tab">
                                @lang('Details')
                            </a>
                        </li>
                        @if (Auth::check())
                            <li onclick="dashboardViewTabClicked({{ $event->id }})">
                                <a href="#dashboard" data-bs-toggle="tab">@lang('Dashboard')</a>
                            </li>
                        @endif
                    </ul>

                    <div id="tabs-content" class="tab-content position-relative">
                        <div class="tab-pane fade show active" id="auction-view">
                            @include('templates.basic.event.auction_view', [
                                'event' => $event,
                                'products' => $relatedProducts,
                            ])
                        </div>

                        <div class="tab-pane fade" id="overview"></div>

                        <div class="tab-pane fade" id="dashboard"></div>

                        <div class="tab-loader d-none">
                            <div></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @if ($event->event_type == 'ace_event')
            <div class="contact-info d-flex flex-column align-items-center justify-content-center">
                <img src="{{ getImage(imagePath()['event']['path'] . '/ace-logo-white.png', imagePath()['event']['thumb']) }}"
                    alt="ace logo">
                <div class="d-flex align-items-center gap-4 mt-2 mb-2">
                    <a class="facebook-icon" href="https://www.facebook.com/CupofExcellence/" target="_blank">
                        @include('templates.basic.svgIcons.facebook')
                    </a>
                    <a class="instagram-icon" href="https://www.instagram.com/cupofexcellence/" target="_blank">
                        @include('templates.basic.svgIcons.instagram')
                    </a>
                    <a class="youtube-icon"
                        href="https://www.youtube.com/channel/UCJFO3t1mSj9cU-OhBRO5mPw?view_as=subscriber"
                        target="_blank">
                        @include('templates.basic.svgIcons.youtube')
                    </a>
                    <a class="linkedin-icon"
                        href="https://www.linkedin.com/company/alliance-for-coffee-excellence-inc.-ace-" target="_blank">
                        @include('templates.basic.svgIcons.linkedin')
                    </a>
                </div>
                <a class="support-email" href="mailto:support@cupofexcellence.org">support@cupofexcellence.org</a>
            </div>
        @endif
    </section>
    <!-- Product -->
    <div class="modal" id="bidModalLoading">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">@lang('Please Wait...')</h5>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="bidModal_submit">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">@lang('Bid Settings')</h5>
                    <button onclick="removeHighlightEffect()" class="btn text--danger modal-close"
                        data-bs-dismiss="modal" aria-label="Close">
                    </button>
                </div>

                <div class="form-group">
                    <label class="paragraph-level-2 mb-2">@lang('Bid Per Lb')</label>
                    <div class="position-relative">
                        <input onFocus="this.select();" type="text" class="form-control" id="amount"
                            autocomplete="off" onchange="convert_to_dec(this)" value="">
                        <span class="modal-currency-code">USD</span>
                    </div>
                </div>

                <div class="modal-footer">
                    <button style="background-color: {{$heroPrimaryButtonColor}} !important; color: var(--button-contained-color) !important;" data-cur_sym="{{ $general->cur_sym }}"
                        class="modal_submit btn btn--base cmn--btn">@lang('Submit')</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="bidModal">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">@lang('Confirmation Alert')</h5>
                    <button onclick="removeHighlightEffect()" class="btn text--danger modal-close"
                        data-bs-dismiss="modal" aria-label="Close">
                    </button>
                </div>
                <form action="{{ route('user.ajax.bid.store') }}" method="POST" id="bid_form">
                    @csrf
                    <input type="hidden" class="amount" name="amount" required>
                    <input type="hidden" name="product_id" id="bidproductid" value="">
                    <div class="modal-body">
                        <p class="message"></p>
                    </div>
                    <div class="modal-footer">
                        <button   onclick="removeHighlightEffect()" type="button" class="btn btn--danger"
                            data-bs-dismiss="modal">@lang('No')</button>
                        <button style="background-color: {{$heroPrimaryButtonColor}} !important;" type="submit" class="btn btn--base cmn--btn">@lang('Yes')</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="autobidModal">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">@lang('Auto Bid Settings')</h5>
                    <button onclick="removeHighlightEffect()" class="btn text--danger modal-close"
                        data-bs-dismiss="modal" aria-label="Close">
                    </button>
                </div>
                <form action="{{ route('user.ajax.bid.store.auto_bid') }}" method="POST" id="autobid_form">
                    @csrf
                    <div class="modal-body">
                        <div class="col-lg-12">
                            <div class="form-group">
                                <label class="paragraph-level-2 mb-2">@lang('Max Bid Per Lb')</label>
                                <div class="position-relative">
                                    <input onFocus="this.select();" type="text" class="form-control" id="max_bid_in_"
                                        autocomplete="off" name="max_value" value=""
                                        @if (Auth::check() && isset($autosettings))  @endif onchange="convert_to_dec(this)"
                                        required>
                                    <span class="modal-currency-code">USD</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <div class="form-group">
                                <label class="paragraph-level-2 mb-2">@lang('Bidding Step')</label>
                                <input placeholder="@lang('Autobid increment (USD/lb)')" onFocus="this.select();" type="text"
                                    class="form-control" id="bidding_step_" autocomplete="off" name="step"
                                    value="" @if (Auth::check() && isset($autosettings))  @endif
                                    onchange="convert_to_dec(this)" required>
                            </div>
                        </div>
                        <input type="hidden" name="product_id" id="autobidproductid" value="">
                    </div>
                    <div class="modal-footer"  style="clear:both;width:100%;display:table;">
                        <p class="no-autobid-settings d-none">@lang('There is no active settings')</p>

                        <button type="button" onclick="" id="disable" style="display: none;"
                            class="btn btn--danger" style="float:left;">
                            @lang('Disable')
                        </button>

                        <button type="submit" class="btn cmn--btn btn--base" style="float:right; background-color: {{$heroPrimaryButtonColor}} !important; color: var(--button-contained-color) !important;">
                            @lang('Submit')
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>



    <div class="modal fade accessRequestModal" id="accessRequestModal{{ $event->id }}">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">@lang('Confirmation Alert')</h5>
                    <button class="btn text--danger modal-close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true"></span>
                    </button>
                </div>
                <form action="{{ route('user.request.store', [$event->id]) }}" method="GET">
                    <div class="modal-body">
                        <h6 class="message">
                            @if ($event->event_type == 'ace_event')
                                @lang('I agree to the')
                                <a href="{{ route('event.agreement', [$event->id]) }}" target="_blank">@lang('Bidder Agreement')
                                </a>
                                @lang('and')
                                <a href="{{ route('policy', [$policy_id, 'terms-and-conditions']) }}"
                                    target="_blank">@lang('terms and conditions')
                                </a>
                            @else
                                @lang('I agree to the')
                                <a href="{{ route('policy', [$policy_id, 'terms-and-conditions']) }}"
                                    target="_blank">@lang('terms and conditions')
                                </a>
                            @endif
                        </h6>
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

    <div class="modal fade" id="incorrect-bid-value-modal">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">@lang('Warning Alert')</h5>
                    <button onclick="removeHighlightEffect()" class="btn text--danger modal-close"
                        data-bs-dismiss="modal" aria-label="Close">
                    </button>
                </div>
                <div class="modal-body">
                    <h6 class="message">
                        @lang('Please enter an amount to bid')
                    </h6>
                </div>
                <div class="modal-footer">
                    <button type="button" onclick="removeHighlightEffect()" class="btn btn--base"
                        data-bs-dismiss="modal">@lang('OK')</button>
                </div>
            </div>
        </div>
    </div>

    <!-- @foreach ($relatedProducts as $product)
    <input type="hidden" id="old_bid_status_{{ $product->id }}" value="{{ $event->bid_status }}">
    @endforeach -->
    <input type="hidden" id="old_bid_status" value="{{ $event->bid_status }}">

@endsection

@push('style')
    <style>
        /* Chrome, Safari, Edge, Opera */
        input::-webkit-outer-spin-button,
        input::-webkit-inner-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }

        /* Firefox */
        input[type=number] {
            -moz-appearance: textfield;
        }
    </style>
    @if ($event->event_type == 'ace_event')
        <link rel="stylesheet" href="{{ asset($activeTemplateTrue . 'css/ace-theme.min.css') }}">
    @endif
@endpush

@push('script')
    <script src="https://js.pusher.com/7.2/pusher.min.js"></script>
    <script>
        const largeImageUrl =
            "{{ getImage(imagePath()['event']['path'] . '/' . $event->banner_image, imagePath()['event']['size'], false) }}";
        const mediumImageUrl =
            "{{ getImage(imagePath()['event']['path'] . '/' . $event->banner_image, imagePath()['event']['size'], false, 'md') }}";
        const smallImageUrl =
            "{{ getImage(imagePath()['event']['path'] . '/' . $event->banner_image, imagePath()['event']['size'], false, 'sm') }}";




        setResponsiveImage(largeImageUrl, mediumImageUrl, smallImageUrl, document.getElementById('banner'));
    </script>
    <script>


        function addSampleSetToCart(button) {
            // var loader = $(button).find('.loader');
            // loader.removeClass('d-none'); // Show loader

            $.ajax({
                url: "{{ route('user.addSampleSetToCart') }}",
                type: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                    event_id: {{ $event->id }},
                    quantity: 1,

                },
                success: function(response) {
                    iziToast.success({
                        title: "@lang('Added to Cart')",
                        position: "topRight"
                    });
                    setSampleSetCartItems(response.cartItems);
                    // setItemCount(response.itemCount);
                },
                error: function(error) {
                    @if(config('app.env')=='local')
                    console.log("error is");
                    console.log(error.responseJSON);
                    @endif
                    if (error.responseJSON.error && error.responseJSON.error.length > 0) {

                        iziToast.error({
                            title: 'Error',
                            message: error.responseJSON.error,
                            position: 'topRight'
                        });

                    } else {
                        if (error.responseJSON && error.responseJSON.errors) {
                            const errors = error.responseJSON.errors;
                            Object.values(errors).forEach(errorMessages => {
                                errorMessages.forEach(errorMessage => {
                                    iziToast.error({
                                        title: 'Error',
                                        message: errorMessage,
                                        position: 'topRight'
                                    });
                                });
                            });
                        } else {
                            iziToast.error({
                                title: 'Error',
                                message: "@lang('Failed to add cart')",
                                position: 'topRight'
                            });
                        }
                    }
                },
                complete: function() {
                    // loader.addClass('d-none'); // Hide loader on completion
                }
            });
        }
        (function($) {
            try {
                $('.countdown-widget-btn').on('click', function() {
                    $('#event-details .countdown-widget-container').toggleClass('collapsed');
                    $('#event-details .countdown-widget-container .countdown-widget-btn').toggleClass(
                        'collapsed');
                    $('#event-details .countdown-widget-container .sidebar-countdown').toggleClass('collapsed');
                })
                $('.countdown').each(function() {
                    var date = $(this).data('date');
                    if (date) {
                        $(this).countdown({
                            date: date,
                            offset: +6,
                            day: 'Day',
                            days: 'Days'
                        });
                    }
                });
                $('.countdown').final_countdown({
                    start: '1362139200',
                    end: '1388461320',
                    now: '1387461319',
                    selectors: {
                        value_seconds: '.clock-seconds .val',
                        canvas_seconds: 'canvas_seconds',
                        value_minutes: '.clock-minutes .val',
                        canvas_minutes: 'canvas_minutes',
                        value_hours: '.clock-hours .val',
                        canvas_hours: 'canvas_hours',
                        value_days: '.clock-days .val',
                        canvas_days: 'canvas_days'
                    },
                    seconds: {
                        borderColor: '#008CBD',
                        borderWidth: '6'
                    },
                    minutes: {
                        borderColor: '#008CBD',
                        borderWidth: '6'
                    },
                    hours: {
                        borderColor: '#008CBD',
                        borderWidth: '6'
                    },
                    days: {
                        borderColor: '#008CBD',
                        borderWidth: '6'
                    }
                }, function() {
                    // Finish callback
                });
            } catch (error) {
                Sentry.captureException(error);
            }
        })(jQuery);
    </script>

    <script>
        const currentUserId = ('{{ Auth::id() }}' === '') ? 0 : '{{ Auth::id() }}';
        const productsInfo = [];
        @foreach ($relatedProducts as $prod)
            var prodInfo = {
                id: {{ $prod->id }},
                isHighest: currentUserId == {{ $prod->last_bid_userid }},
                totalPrice: {{ $prod->final_total_price }},
                totalWeight: {{ $prod->weight }},
            };
            productsInfo.push(prodInfo);
        @endforeach

        function openCountdownWidget() {
            $('#event-details .countdown-widget-container').removeClass('collapsed');
            $('#event-details .countdown-widget-container .countdown-widget-btn').removeClass('collapsed');
            $('#event-details .countdown-widget-container .sidebar-countdown').removeClass('collapsed');
        }

        function getActiveTabId() {
            const activeTabUrl = $('.event-details-tabs').find('a.active').prop('href');
            const activeTabId = activeTabUrl.split("#").pop();

            return activeTabId;
        }


        function parseToNumber(str) {
            return parseFloat(str.replaceAll(',', ''));
        }
    </script>

    <script>
        function showAccessRequestModal(eventid) {
            $('#accessRequestModal' + eventid).modal('show');
        }

        function convert_to_dec(selectObject) {
            try {
                var numb = selectObject.value;
                var number_pars = parseFloat(numb) || 0;
                var number_converted = number_pars.toFixed(2);
                selectObject.value = number_converted;
            } catch (err) {
                Sentry.captureException(err);
            }
        }

        function convert_number_format(selectObject) {
            try {
                var numb = selectObject.value;
                if (numb) {
                    const convertedNum = numb.replaceAll(",", "");
                    var number_converted = parseFloat(convertedNum).toLocaleString("en-US", {
                        minimumFractionDigits: 2,
                        maximumFractionDigits: 2
                    });
                    selectObject.value = number_converted;
                    return;
                }
                selectObject.value = "0.00";
            } catch (err) {
                Sentry.captureException(err);
            }
        }

        function getDecimalValue(value) {
            try {
                let parsedValue = parseFloat(value) || 0;
                let decimalValue = parsedValue.toFixed(2);
                return decimalValue;
            } catch (err) {
                Sentry.captureException(err);
            }
        }

        try {
            var total_auction_value = parseFloat($('#total_auction_value').text()).toLocaleString("en-US", {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            });

            var auction_weight_avg = parseFloat($('#auction_weight_avg').text()).toLocaleString("en-US", {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            });

            var highest_bid = parseFloat($('#highest_bid').text()).toLocaleString("en-US", {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            });

            $('#total_auction_value').text(total_auction_value);
            $('#auction_weight_avg').text(auction_weight_avg);
            $('#highest_bid').text(highest_bid);
        } catch (err) {
            Sentry.captureException(err);
        }

        function showCard(element) {
            const cardElem = element.nextElementSibling;
            cardElem.classList.remove("d-none");
        }

        function hideCard(element) {
            const cardElem = element.closest(".inner-product-card");
            cardElem.classList.add("d-none");
        }

        function hideCardOnLeave(element) {
            const cardElem = element.querySelector(".inner-product-card");
            cardElem.classList.add("d-none");
        }

        function toggleFavoriteStatus(product_id, btnElem = null) {
            hideBootstrapTooltip(btnElem);
            var requestData = {
                _token: "{{ csrf_token() }}",
                product_id: product_id
            }
            var url = "{{ route('user.fav.toggle') }}";
            $.ajax(url, {
                    data: requestData,
                    type: "POST",
                    timeout: AJAX_TIMEOUT
                }).done(function(data, textStatus, jqXHR) {
                    try {
                        const favIconElements = document.getElementsByClassName("favorite-icon-" + product_id)
                        for (let i = 0; i < favIconElements.length; i++) {
                            if (data.is_add) {
                                favIconElements.item(i).classList.add("heart-icon-color");
                            } else {
                                favIconElements.item(i).classList.remove("heart-icon-color");
                            }
                        }
                        const activeTabId = getActiveTabId();
                        if (activeTabId === "auction-view") {
                            auctionViewTabClicked({{ $event->id }});
                        } else if (activeTabId === "dashboard") {
                            dashboardViewTabClicked({{ $event->id }});
                        }
                        initBootstrapTooltips();
                    } catch (error) {
                        showFatalErrorOverlay(error);
                    }
                })
                .fail(function(jqXHR, textStatus, errorThrown) {
                    ajaxErrorHandler(jqXHR, errorThrown);
                })
        }

        function overviewTabClicked(event_id) {
            const auctionViewElement = document.querySelector("#auction-view");
            auctionViewElement.innerHTML = "";
            const dashboardElement = document.querySelector("#dashboard");
            dashboardElement.innerHTML = "";
            const tabLoaderElement = document.querySelector(".tab-loader");
            tabLoaderElement.classList.remove("d-none");

            var requestData = {
                _token: "{{ csrf_token() }}",
                event_id: event_id
            };
            var url = "{{ route('event_views.overview') }}";
            $.ajax(url, {
                    data: requestData,
                    type: "POST",
                    timeout: AJAX_TIMEOUT
                }).done(function(data, textStatus, jqXHR) {
                    try {
                        tabLoaderElement.classList.add("d-none");
                        const overviewElement = document.querySelector("#overview");
                        overviewElement.innerHTML = data.overview_view;
                        const allScoresElems = document.querySelectorAll('.score-property');
                        for (let i = 0; i < allScoresElems.length; i++) {
                            let scoreValue = allScoresElems[i].textContent;
                            let number_pars = parseFloat(scoreValue) || 0;
                            let number_converted = number_pars;
                            if (number_pars > 0 && number_pars < 100) {
                                number_converted = number_pars.toFixed(2);
                            }
                            allScoresElems[i].textContent = number_converted;
                        }
                        reInitAfterTabClicked();
                    } catch (error) {
                        showFatalErrorOverlay(error);
                    }
                })
                .fail(function(jqXHR, textStatus, errorThrown) {
                    tabLoaderElement.classList.add("d-none");
                    ajaxErrorHandler(jqXHR, errorThrown);
                })
        }

        function auctionViewTabClicked(event_id) {
            const overviewElement = document.querySelector("#overview");
            overviewElement.innerHTML = "";
            const dashboardElement = document.querySelector("#dashboard");
            dashboardElement.innerHTML = "";
            const tabLoaderElement = document.querySelector(".tab-loader");
            tabLoaderElement.classList.remove("d-none");

            var requestData = {
                _token: "{{ csrf_token() }}",
                event_id: event_id
            };
            var url = "{{ route('event_views.auction_view') }}";
            $.ajax(url, {
                    data: requestData,
                    type: "POST",
                    timeout: AJAX_TIMEOUT
                }).done(function(data, textStatus, jqXHR) {
                    try {
                        tabLoaderElement.classList.add("d-none");
                        const auctionViewElement = document.querySelector("#auction-view");
                        auctionViewElement.innerHTML = data.auction_view;
                        reInitAfterTabClicked();
                        initBootstrapTooltips();
                    } catch (error) {
                        showFatalErrorOverlay(error);
                    }
                })
                .fail(function(jqXHR, textStatus, errorThrown) {
                    tabLoaderElement.classList.add("d-none");
                    ajaxErrorHandler(jqXHR, errorThrown);
                })
        }

        let toRate = 1;
        let weightUnit = "LB";

        function convertBySelectedCurrencyRate(value, toRate, fromRate = 1) {
            // fromRate (USD) Rate
            const result = ((toRate / fromRate) * value);
            return result;
        };

        function updateBudgetProgressBar(budgetValue, spentBudgetValue) {
            let progress = budgetValue === 0 ? 0 : (spentBudgetValue / budgetValue) * 100;
            progress = +progress.toFixed(2);

            const budgetProgressBarElem = $(".budget-progress-bar");
            const budgetLabelElem = $("#remaining-budget-label");
            if (progress < 0) {
                progress = 0;
                budgetLabelElem.text("Remaining");
            } else if (progress < 100) {
                budgetProgressBarElem.removeClass("alert-color");
                budgetLabelElem.text("Remaining");
            } else if (progress >= 100) {
                progress = 100;
                budgetProgressBarElem.addClass("alert-color");
                budgetLabelElem.text("Over Budget");
            }
            budgetProgressBarElem.css("width", `${progress}%`);

            const spentBudgetElem = $(".spent-budget");
            if (progress < 15) {
                spentBudgetElem.css("left", "0");
                spentBudgetElem.css("right", "unset");
            } else {
                spentBudgetElem.css("left", "unset");
                spentBudgetElem.css("right", "0");
            }
        }

        function convertWeight(totalWeightValue, selectedWeightUnit) {
            // convert to Kg
            let convertedTotalWeight = totalWeightValue * 0.45359237;
            if (selectedWeightUnit === "LB") {
                convertedTotalWeight = totalWeightValue;
            } else if (selectedWeightUnit === "Box (30Kg)") {
                convertedTotalWeight /= 30;
            }
            return convertedTotalWeight;
        }

        function dashboardViewTabClicked(event_id) {
            const auctionViewElement = document.querySelector("#auction-view");
            auctionViewElement.innerHTML = "";

            const overviewElement = document.querySelector("#overview");
            overviewElement.innerHTML = "";

            const tabLoaderElement = document.querySelector(".tab-loader");
            tabLoaderElement.classList.remove("d-none");

            var requestData = {
                _token: "{{ csrf_token() }}",
                event_id: event_id
            };
            var url = "{{ route('event_views.dashboard_view') }}";
            $.ajax(url, {
                    data: requestData,
                    type: "POST",
                    timeout: AJAX_TIMEOUT
                }).done(function(data, textStatus, jqXHR) {
                    try {
                        tabLoaderElement.classList.add("d-none");
                        const dashboardElement = document.querySelector("#dashboard");
                        dashboardElement.innerHTML = data.dashboard_view;
                        const winLotCountElem = $("input[name='win_lot_count']");
                        const winLotCount = +winLotCountElem?.val() || 0;
                        // Your Current Liability Carousel
                        $('#owl-two').owlCarousel({
                            loop: false,
                            dots: false,
                            checkVisible: false,
                            autoplay: false,
                            autoWidth: true,
                            responsive: {}
                        });
                        // Current Winning Lots Carousel
                        $('#owl-three').owlCarousel({
                            loop: false,
                            dots: false,
                            nav: winLotCount > 4,
                            navText: ["<div class='prev-slide'><i class='las la-angle-double-left'></i></div>",
                                "<div class='next-slide'><i class='las la-angle-double-right'></i></div>"
                            ],
                            checkVisible: false,
                            autoplay: false,
                            margin: 0,
                            autoWidth: winLotCount > 0,
                            responsive: {},
                        });
                        (function($) {
                            function formatState(state) {
                                if (!state.id) {
                                    return state.text;
                                }
                                var $state = $(
                                    `<span class="custom-select-option">
                            <svg width="14" height="12" viewBox="0 0 14 12" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M11.8017 0.716385C12.0425 0.481382 12.363 0.351463 12.6955 0.354042C13.028 0.35662 13.3465 0.491496 13.5838 0.730207C13.8211 0.968919 13.9587 1.29279 13.9675 1.6335C13.9762 1.9742 13.8556 2.30507 13.6309 2.5563L6.80997 11.3003C6.69268 11.4298 6.55113 11.5337 6.39376 11.6058C6.2364 11.678 6.06646 11.7168 5.89411 11.7201C5.72176 11.7234 5.55053 11.691 5.39067 11.6249C5.23081 11.5588 5.08559 11.4603 4.96371 11.3353L0.44036 6.69875C0.314393 6.57843 0.213357 6.43334 0.143281 6.27213C0.0732054 6.11092 0.0355246 5.93689 0.0324871 5.76043C0.0294497 5.58397 0.0611181 5.40869 0.125602 5.24504C0.190086 5.0814 0.286066 4.93274 0.407815 4.80795C0.529563 4.68315 0.674587 4.58477 0.834234 4.51867C0.993882 4.45257 1.16488 4.42011 1.33703 4.42322C1.50919 4.42633 1.67896 4.46496 1.83624 4.53679C1.99351 4.60862 2.13506 4.71218 2.25243 4.84131L5.83214 8.50888L11.7692 0.754936C11.7799 0.741445 11.7913 0.728574 11.8034 0.716385H11.8017Z" fill="black"/>
                            </svg>
                            ` +
                                    state.text +
                                    "</span>"
                                );
                                return $state;
                            }
                            const selectConfig = {
                                placeholder: "Select",
                                minimumResultsForSearch: Infinity,
                                selectionCssClass: "dashboard-custom-selection",
                                dropdownCssClass: "dashboard-custom-dropdown",
                                templateResult: formatState,
                            }
                            $('#dashboard-tab-weight').select2({
                                ...selectConfig
                            });
                            $('#dashboard-tab-currency').select2({
                                ...selectConfig,
                                minimumResultsForSearch: 0,
                            });
                            const isUserAllowCookies = !!getCookie("cookie_accepted");
                            const currentWeightUintCookie = isUserAllowCookies ? getCookie("weight_unit") : "";
                            const currentCurrencyCookie = isUserAllowCookies ? getCookie("currency") : "";
                            $("#dashboard-tab-weight").change(function(e) {
                                try {
                                    const selectedWeightUnit = e.target.value;
                                    weightUnit = e.target.value;
                                    if (isUserAllowCookies && (!currentWeightUintCookie ||
                                            currentWeightUintCookie != selectedWeightUnit)) {
                                        setCookie("weight_unit", selectedWeightUnit, 365);
                                    }
                                    const totalWeightElem = $("#total-weight");
                                    const totalWeightValue = parseFloat(totalWeightElem.attr("data-lb"));
                                    let convertedTotalWeight = convertWeight(totalWeightValue,
                                        selectedWeightUnit);
                                    totalWeightElem.attr("data-current", convertedTotalWeight);
                                    totalWeightElem.text(parseFloat(convertedTotalWeight).toLocaleString(
                                        "en-US", {
                                            minimumFractionDigits: 2,
                                            maximumFractionDigits: 2
                                        }));
                                    const totalLiabilityElem = $("#total-liability");
                                    const totalLiabilityValue = parseFloat(totalLiabilityElem.attr(
                                        "data-current"));
                                    let newAveragePriceValue = convertedTotalWeight === 0 ? 0 :
                                        totalLiabilityValue / convertedTotalWeight;
                                    const averagePriceElem = $("#average-price");
                                    newAveragePriceValue = parseFloat(newAveragePriceValue).toLocaleString(
                                        "en-US", {
                                            minimumFractionDigits: 2,
                                            maximumFractionDigits: 2
                                        });
                                    averagePriceElem.text(newAveragePriceValue);
                                    $(".weight-code").each(function() {
                                        $(this).text(` ${selectedWeightUnit}`);
                                    })
                                } catch (err) {
                                    Sentry.captureException(err);
                                }
                            });
                            $("#dashboard-tab-currency").change(function(e) {
                                const selectedCurrency = e.target.value;
                                if (isUserAllowCookies && (!currentCurrencyCookie ||
                                        currentCurrencyCookie != selectedCurrency)) {
                                    setCookie("currency", selectedCurrency, 365);
                                }

                                var requestData = {
                                    _token: "{{ csrf_token() }}",
                                    currency_code: selectedCurrency,
                                };
                                var url = "{{ route('user.get_exchange_rate') }}";
                                $.ajax(url, {
                                        data: requestData,
                                        type: "POST",
                                        timeout: AJAX_TIMEOUT
                                    }).done(function(data, textStatus, jqXHR) {
                                        try {
                                            // toRate (selected currency) Rate
                                            toRate = parseFloat(data.exchange_rate);
                                            const totalLiabilityElem = $("#total-liability");
                                            const totalLiabilityValue = parseFloat(totalLiabilityElem
                                                .attr("data-usd"));
                                            let convertedTotalLiability = convertBySelectedCurrencyRate(
                                                totalLiabilityValue, toRate);
                                            totalLiabilityElem.attr("data-current",
                                                convertedTotalLiability)
                                            const totalWeightElem = $("#total-weight");
                                            const totalWeightValue = parseFloat(totalWeightElem.attr(
                                                "data-current"));
                                            const averagePriceElem = $("#average-price");
                                            let convertedAveragePrice = totalWeightValue === 0 ? 0 :
                                                convertedTotalLiability / totalWeightValue;
                                            convertedAveragePrice = parseFloat(convertedAveragePrice)
                                                .toLocaleString("en-US", {
                                                    minimumFractionDigits: 2,
                                                    maximumFractionDigits: 2
                                                });
                                            averagePriceElem.text(convertedAveragePrice);
                                            convertedTotalLiability = parseFloat(
                                                convertedTotalLiability).toLocaleString("en-US", {
                                                minimumFractionDigits: 2,
                                                maximumFractionDigits: 2
                                            });
                                            totalLiabilityElem.text(convertedTotalLiability);
                                            const budgetElem = $("#budget");
                                            const budgetValue = parseFloat(budgetElem.attr("data-usd"));
                                            let convertedBudget = convertBySelectedCurrencyRate(
                                                budgetValue, toRate);
                                            convertedBudget = parseFloat(convertedBudget)
                                                .toLocaleString("en-US", {
                                                    minimumFractionDigits: 2,
                                                    maximumFractionDigits: 2
                                                });
                                            budgetElem.val(convertedBudget);
                                            const remainingBudgetElem = $("#remaining-budget-value");
                                            const remainingBudgetValue = parseFloat(remainingBudgetElem
                                                .attr("data-usd"));
                                            let convertedRemainingBudgetValue =
                                                convertBySelectedCurrencyRate(remainingBudgetValue,
                                                    toRate);
                                            convertedRemainingBudgetValue = parseFloat(
                                                convertedRemainingBudgetValue).toLocaleString(
                                                "en-US", {
                                                    minimumFractionDigits: 2,
                                                    maximumFractionDigits: 2
                                                });
                                            remainingBudgetElem.text(convertedRemainingBudgetValue);
                                            const spentBudgetElem = $("#spent-budget-value");
                                            const spentBudgetValue = parseFloat(spentBudgetElem.attr(
                                                "data-usd"));
                                            let convertedSpentBudgetValue =
                                                convertBySelectedCurrencyRate(spentBudgetValue, toRate);
                                            convertedSpentBudgetValue = parseFloat(
                                                convertedSpentBudgetValue).toLocaleString("en-US", {
                                                minimumFractionDigits: 2,
                                                maximumFractionDigits: 2
                                            });
                                            spentBudgetElem.text(convertedSpentBudgetValue);
                                            updateBudgetProgressBar(budgetValue, spentBudgetValue);
                                            $(".currency-code").each(function() {
                                                $(this).text(`(${selectedCurrency})`);
                                            })
                                        } catch (error) {
                                            showFatalErrorOverlay(error);
                                        }
                                    })
                                    .fail(function(jqXHR, textStatus, errorThrown) {
                                        ajaxErrorHandler(jqXHR, errorThrown);
                                    })

                            });

                            $('#dashboard-tab-weight').val(currentWeightUintCookie || "LB").trigger('change');
                            $('#dashboard-tab-currency').val(currentCurrencyCookie || "USD").trigger('change');

                            $("#save-budget-btn").on("click", function submitBudget(e) {
                                const budgetElem = $("#budget");
                                let enteredBudget = budgetElem.val();
                                // convert to USD
                                const sendingBudget = convertBySelectedCurrencyRate(parseToNumber(
                                    enteredBudget), 1, toRate);
                                $(".save-budget-progress").removeClass("d-none");
                                const saveBudgetBtn = $(this);
                                saveBudgetBtn.addClass("pe-none");

                                var requestData = {
                                    _token: "{{ csrf_token() }}",
                                    event_id: event_id,
                                    budget: sendingBudget,
                                };
                                var url = "{{ route('user.ajax.submit_new_budget') }}";
                                $.ajax(url, {
                                        data: requestData,
                                        type: "POST",
                                        timeout: AJAX_TIMEOUT
                                    }).done(function(data, textStatus, jqXHR) {
                                        try {
                                            $(".save-budget-progress").addClass("d-none");
                                            saveBudgetBtn.removeClass("pe-none");
                                            iziToast.success({
                                                message: "@lang('Your Budget Added Successfully')",
                                                position: "topRight"
                                            });
                                            // new USD budget
                                            const budgetValue = sendingBudget;
                                            budgetElem.attr("data-usd", budgetValue);
                                            budgetElem.val(enteredBudget);
                                            const spentBudgetValueElem = $("#spent-budget-value");
                                            let spentBudgetValue = spentBudgetValueElem.attr(
                                                "data-usd");
                                            spentBudgetValue = parseFloat(spentBudgetValue);
                                            const remainingBudgetValueElem = $(
                                                "#remaining-budget-value");
                                            let remainingValue = budgetValue - spentBudgetValue;
                                            remainingBudgetValueElem.attr("data-usd", remainingValue);
                                            let convertedRemainingBudgetValue =
                                                convertBySelectedCurrencyRate(remainingValue, toRate);
                                            convertedRemainingBudgetValue = parseFloat(
                                                convertedRemainingBudgetValue).toLocaleString(
                                                "en-US", {
                                                    minimumFractionDigits: 2,
                                                    maximumFractionDigits: 2
                                                });
                                            remainingBudgetValueElem.text(
                                                convertedRemainingBudgetValue);
                                            updateBudgetProgressBar(budgetValue, spentBudgetValue);
                                        } catch (error) {
                                            showFatalErrorOverlay(error);
                                        }
                                    })
                                    .fail(function(jqXHR, textStatus, errorThrown) {
                                        $(".save-budget-progress").addClass("d-none");
                                        saveBudgetBtn.removeClass("pe-none");
                                        ajaxErrorHandler(jqXHR, errorThrown);
                                    })

                            });
                        })(jQuery);
                        reInitAfterTabClicked();
                        initBootstrapTooltips();
                    } catch (error) {
                        showFatalErrorOverlay(error);
                    }
                })
                .fail(function(jqXHR, textStatus, errorThrown) {
                    tabLoaderElement.classList.add("d-none");
                    ajaxErrorHandler(jqXHR, errorThrown);
                })

        }

        function updateBidAreaElements(isOpenedEvent, productId, lastBidUserId) {
            var activeTabId = getActiveTabId();
            var userId = ('{{ Auth::id() }}' === '') ? 0 : '{{ Auth::id() }}';
            var idd = $('#product_' + productId + '_price');

            if (!isOpenedEvent) {
                idd.closest('.auction__item-content_bid').find('.bid_now').hide();
                idd.closest('.auction__item-content_bid').find('.auto_bid_now.bid-with-anim').hide();
                idd.closest('.auction__item-content_bid').find('.highest-bidder').hide();
                if (activeTabId !== "overview") {
                    idd.closest('.auction__item-content_bid').find('.bid-paused-button').show();
                } else {
                    idd.closest('.auction__item-content_bid').find('.auction__item-numOfBids').show();
                }
            } else if (isOpenedEvent && userId == lastBidUserId) {
                idd.closest('.auction__item-content_bid').find('.bid_now').hide();
                idd.closest('.auction__item-content_bid').find('.auto_bid_now.bid-with-anim').hide();
                idd.closest('.auction__item-content_bid').find('.bid-paused-button').hide();
                idd.closest('.auction__item-content_bid').find('.highest-bidder').show();
                if (activeTabId === "overview") {
                    idd.closest('.auction__item-content_bid').find('.auction__item-numOfBids').hide();
                    idd.closest('.auction__item-content_bid').find('.autobid-settings').hide();
                }
            } else {
                // opened event and non highest bidder
                if (activeTabId === "overview") {
                    idd.closest('.auction__item-content_bid').find('.bid_now').hide();
                    idd.closest('.auction__item-content_bid').find('.auto_bid_now.bid-with-anim').hide();
                    idd.closest('.auction__item-content_bid').find('.auction__item-numOfBids').show();
                } else {
                    idd.closest('.auction__item-content_bid').find('.bid_now').show();
                    idd.closest('.auction__item-content_bid').find('.auto_bid_now.bid-with-anim').show();
                }
                idd.closest('.auction__item-content_bid').find('.highest-bidder').hide();
                idd.closest('.auction__item-content_bid').find('.bid-paused-button').hide();
            }
        }

        function checkHighestBidders(isTabClicked) {
            try {
                var oldBidStatusElement = $('#old_bid_status');
                var bidStatus = oldBidStatusElement.val();
                var isOpenedEvent = bidStatus == "open";
                if (!isTabClicked) {
                    @foreach ($event->products as $product)
                        @if (!$product->is_in_allow_list)
                            @continue
                        @endif
                        var last_bid_userid = {{ $product->max_bid() ? $product->max_bid()->user_id : -1 }};
                        updateBidAreaElements(isOpenedEvent, {{ $product->id }}, last_bid_userid);
                    @endforeach
                } else {
                    var userid = ('{{ Auth::id() }}' === '') ? 0 : '{{ Auth::id() }}';
                    var requestData = {
                        _token: "{{ csrf_token() }}",
                        user_id: userid, //not used, added any data to prevent csrf token error
                    };
                    var url = "{{ route('event_views.getProducts', ['event_id' => $event->id]) }}";
                    $.ajax(url, {
                            data: requestData,
                            type: "GET",
                            timeout: AJAX_TIMEOUT
                        }).done(function(data, textStatus, jqXHR) {
                            try {
                                data = data.products;
                                for (var i = 0; i < data.length; i++) {
                                    var productId = data[i].id;
                                    var last_bid_userid = data[i].last_bid_user_id;
                                    updateBidAreaElements(isOpenedEvent, productId, last_bid_userid);
                                }
                            } catch (error) {
                                showFatalErrorOverlay(error);
                            }
                        })
                        .fail(function(jqXHR, textStatus, errorThrown) {
                            ajaxErrorHandler(jqXHR, errorThrown);
                        })

                }
            } catch (error) {
                showFatalErrorOverlay(error);
            }
        }

        // function checkBidLogin(product_id) {
        //     try {
        //         if ({{ \Illuminate\Support\Facades\Auth::check() == 1 ? 1 : 0 }} === 1) {
        //             $("#bid_open_user_logged_out_" + product_id).attr("hidden", true);
        //             $("#auto_bid_open_user_logged_out_" + product_id).attr("hidden", true);
        //         } else {
        //             $("#bid_open_user_logged_in_" + product_id).attr("hidden", true);
        //             $("#auto_bid_open_user_logged_in_" + product_id).attr("hidden", true);
        //         }
        //     } catch (error) {
        //         showFatalErrorOverlay(error);
        //     }
        // }

        function autopopupbidnow() {
            @if (!Auth::check())
                return;
            @endif

            $('.auto_bid_now').off('click');

            $('.auto_bid_now').on('click', function() {
                var productid = $(this).closest('.div-bid-now').find('.idproduct').val();

                var auction_element = $(this).closest(".auction__item");
                auction_element.addClass('highlighted__auction');

                $('#autobidproductid').val(productid);

                var requestData = {
                    productid: productid,
                    _token: "{{ csrf_token() }}"
                };
                var url = "{{ route('user.autobidsetting.show') }}";
                $.ajax(url, {
                        data: requestData,
                        type: "POST",
                        timeout: AJAX_TIMEOUT
                    }).done(function(data, textStatus, jqXHR) {
                        try {
                            if (data.status === "active") {
                                $('.no-autobid-settings').addClass("d-none");
                                $('#max_bid_in_').val(data.max_value);
                                $('#bidding_step_').val(data.step);
                                $('#disable').attr("onclick", "disableAutoBid(" + productid + ");");
                                $('#disable').show();
                            } else {
                                $('.no-autobid-settings').removeClass("d-none");
                                $('#max_bid_in_').val("");
                                $('#bidding_step_').val("");
                                $('#disable').attr("onclick", "");
                                $('#disable').hide();
                            }
                            var modal = $('#autobidModal');
                            modal.modal('show');
                        } catch (error) {
                            showFatalErrorOverlay(error);
                        }
                    })
                    .fail(function(jqXHR, textStatus, errorThrown) {
                        ajaxErrorHandler(jqXHR, errorThrown);
                    })

            });
        }

        $("#autobid_form").submit(function(e) {
            e.preventDefault();
            var form = $("#autobid_form");
            var actionUrl = form.attr('action');
            var modal = $('#autobidModal');
            modal.modal('hide');
            $(".cup-progress-container").removeClass("d-none");
            openCountdownWidget();
            var requestData = form.serialize();
            var url = actionUrl;
            $.ajax(url, {
                    data: requestData,
                    type: "POST",
                    timeout: AJAX_TIMEOUT
                }).done(function(data, textStatus, jqXHR) {
                    try {
                        removeHighlightEffect();
                        $(".cup-progress-container").addClass("d-none");
                        iziToast.success({
                            message: "@lang('Your AutoBid Settings Added Successfully')",
                            position: "topRight"
                        });
                    } catch (error) {
                        showFatalErrorOverlay(error);
                    }
                })
                .fail(function(jqXHR, textStatus, errorThrown) {
                    removeHighlightEffect();
                    var bidModalLoading = $('#bidModalLoading');
                    bidModalLoading.modal('hide');
                    $(".cup-progress-container").addClass("d-none");
                    ajaxErrorHandler(jqXHR, errorThrown);
                })

        });

        function disableAutoBid(product_id) {
            var modal = $('#autobidModal');
            modal.modal('hide');
            $(".cup-progress-container").removeClass("d-none");

            var requestData = "product_id=" + product_id + "&max_value=0&step=0&is_disable=1&_token={{ csrf_token() }}";
            var url = "{{ route('user.ajax.bid.store.auto_bid') }}";
            $.ajax(url, {
                    data: requestData,
                    type: "POST",
                    timeout: AJAX_TIMEOUT
                }).done(function(data, textStatus, jqXHR) {
                    try {
                        $(".cup-progress-container").addClass("d-none");
                        iziToast.success({
                            message: "@lang('Your Auto Bid Settings Disabled Successfully')",
                            position: "topRight"
                        });
                    } catch (error) {
                        showFatalErrorOverlay(error);
                    }
                })
                .fail(function(jqXHR, textStatus, errorThrown) {
                    $(".cup-progress-container").addClass("d-none");
                    ajaxErrorHandler(jqXHR, errorThrown);
                })
                .always(function(jqXHR, textStatus, errorThrown) {
                    removeHighlightEffect();
                })

        }


        function popupbidnow() {
            @if (!Auth::check())
                return;
            @endif

            $(document).on('click', '.bid_now', function() {
                try {
                    let productid = $(this).closest('.div-bid-now').find('.idproduct').val();
                    $('#bidproductid').val(productid);

                    let auction_element = $(this).closest(".auction__item");
                    auction_element.addClass('highlighted__auction');

                    let suggestedValue = $(this).closest('.div-bid-now').find('.suggested-value').val();

                    let decimalSuggestedValue = getDecimalValue(suggestedValue);

                    $('#amount').val(decimalSuggestedValue);

                    let bidModal_submit = $('#bidModal_submit');
                    bidModal_submit.modal('show');
                } catch (error) {
                    showFatalErrorOverlay(error);
                }
            });

            $(document).on('click', '.modal_submit', function() {
                try {
                    var bidModal_submit = $('#bidModal_submit');
                    bidModal_submit.modal('hide');

                    var am = $('#amount').val();

                    if (!am || am == 0) {
                        var incorrectBidValueModal = $('#incorrect-bid-value-modal');
                        incorrectBidValueModal.modal('show');
                        return;
                    }

                    var modal = $('#bidModal');

                    var cur_sym = $(this).data('cur_sym');

                    const productId = $('#bidproductid').val();

                    var product_weight = $('#product_' + productId + '_weight').text();

                    var total_price = parseFloat(product_weight) * parseFloat(am);

                    var formatter = new Intl.NumberFormat('en-US', {
                        minimumFractionDigits: 2,
                        maximumFractionDigits: 2
                    });

                    total_price = formatter.format(total_price);

                    amount = formatter.format(am);

                    modal.find('.message').html("@lang('Are you sure you want to place a bid of ')" + '<span class="msg_number">' + 'US$' + amount +
                        '/Lb' + '</span>' + "@lang('. for a total bid of') " + '<span class="msg_number">' + 'US$' +
                        total_price + '</span>');

                    modal.find('.amount').val(am);
                    modal.modal('show');
                } catch (err) {
                    Sentry.captureException(err);
                }
            });
        }

        $("#bid_form").submit(function(e) {
            e.preventDefault();
            var form = $(this);
            var actionUrl = form.attr('action');
            var modal = $('#bidModal');
            modal.modal('hide');
            $(".cup-progress-container").removeClass("d-none");

            var requestData = form.serialize();
            var url = actionUrl;
            $.ajax(url, {
                    data: requestData,
                    type: "POST",
                    timeout: AJAX_TIMEOUT
                }).done(function(data, textStatus, jqXHR) {
                    try {
                        $('.amount').val("");
                        removeHighlightEffect();
                        $(".cup-progress-container").addClass("d-none");
                        iziToast.success({
                            message: "@lang('Your Bid Added Successfully')",
                            position: "topRight"
                        });
                    } catch (error) {
                        showFatalErrorOverlay(error);
                    }
                })
                .fail(function(jqXHR, textStatus, errorThrown) {
                    $('.amount').val("");
                    removeHighlightEffect();
                    $(".cup-progress-container").addClass("d-none");
                    ajaxErrorHandler(jqXHR, errorThrown);
                })

        });


        function applyAnimate(iddElement) {
            try {
                const closestAuctionItem = iddElement.closest('.auction__item');
                if (!closestAuctionItem.hasClass('auction__item__animate')) {
                    closestAuctionItem.addClass('auction__item__animate');
                    closestAuctionItem.find('.animate-value').addClass('val__animate');
                }
                removeAnimate(iddElement);
            } catch (err) {
                Sentry.captureException(err);
            }
        }

        function removeAnimate(iddElement) {
            try {
                const closestAuctionItem = iddElement.closest('.auction__item');

                const time = 10000;
                setTimeout(() => {
                    closestAuctionItem.removeClass('auction__item__animate');
                    closestAuctionItem.find('.animate-value').removeClass('val__animate');
                }, time);
            } catch (err) {
                Sentry.captureException(err);
            }
        }

        function reInitAfterTabClicked() {
            try {
                @if (!Auth::check())
                    return;
                @endif

                autopopupbidnow();

                /*
                @foreach ($relatedProducts as $product)
                    checkBidLogin({{ $product->id }});
                @endforeach
                */

                checkHighestBidders(true);
            } catch (error) {
                showFatalErrorOverlay(error);
            }
        }

        function initializeCounter() {
            $('.countdown').each(function() {
                var date = $(this).data('date');
                if(date) {
                    $(this).countdown({
                        date: date,
                        offset: +6,
                        day: 'Day',
                        days: 'Days'
                    });
                }
            });
        }

        (function($) {
            @if (!stop_live_in_not_auth_users() || \Illuminate\Support\Facades\Auth::check())
                try {


                    var pusher = new Pusher('{{ $codes['PUSHER_APP_KEY'] }}', {
                        cluster: '{{ $codes['PUSHER_APP_CLUSTER'] }}'
                    });

                    var channel = pusher.subscribe('event.{{ $event->id }}');
                    channel.bind('event.{{ $event->id }}', function(data) {
                        refreshDataeventAjax(JSON.parse(data.data));
                    });
                } catch (error) {
                    showFatalErrorOverlay(error);
                }
                $('#live_disable').attr('hidden', true);
            @endif

            @foreach ($relatedProducts as $product)
                @if (!stop_live_in_not_auth_users() || \Illuminate\Support\Facades\Auth::check())
                    try {
                        var channel_product_{{ $product->id }} = pusher.subscribe('product.{{ $product->id }}');
                        channel_product_{{ $product->id }}.bind('product.{{ $product->id }}', function(data) {
                            updateProduct(JSON.parse(data.data));
                        });

                        var channel_product_auto_bid_{{ $product->id }} = pusher.subscribe(
                            'autobidstart.{{ $product->id }}');
                        channel_product_auto_bid_{{ $product->id }}.bind('autobidstart.{{ $product->id }}',
                            function(
                                data) {
                                $(".cup-progress-container").removeClass("d-none");
                            });

                        var channel_product_auto_bid_end_{{ $product->id }} = pusher.subscribe(
                            'autobidend.{{ $product->id }}');
                        channel_product_auto_bid_end_{{ $product->id }}.bind('autobidend.{{ $product->id }}',
                            function(
                                data) {
                                $(".cup-progress-container").addClass("d-none");
                            });
                    } catch (error) {
                        showFatalErrorOverlay(error);
                    }
                @endif

                // checkBidLogin({{ $product->id }});
            @endforeach

            function refreshDataeventAjax(response) {
                try {
                    var oldBidStatusElement = $('#old_bid_status');
                    var old_bid_status = oldBidStatusElement.val();
                    var current_event_status = response.bid_status;
                    var isEventStatusChanged = old_bid_status != current_event_status;
                    if (isEventStatusChanged) {
                        oldBidStatusElement.val(current_event_status);
                    }

                    var event_start_type = response.event_start_type;
                    var event_view = response.event_view;
                    var start_counter = response.start_counter;
                    var is_event_ended = response.is_event_ended == 1;
                    var productsInfo = response.products_info;
                    var isOpenedEvent = current_event_status == "open";

                    if (is_event_ended) {
                        $('#all_winners_go_to_cart').show();
                    } else {
                        $('#all_winners_go_to_cart').hide();
                    }

                    if (event_start_type === "started") {
                        var old = $('#start_counter').html();
                        if (parseInt(old) < start_counter) {
                            $('#countdown_area').html(event_view);
                            $('#countdown_area_2').html(event_view);
                            initializeCounter();
                        }
                    }

                    var total_bid_count = response.total_bid_count;
                    total_bid_count = parseFloat(total_bid_count).toLocaleString("en-US", {
                        minimumFractionDigits: 0,
                        maximumFractionDigits: 0
                    });

                    var total_auction_value = response.total_auction_value;
                    total_auction_value = parseFloat(total_auction_value).toLocaleString("en-US", {
                        minimumFractionDigits: 2,
                        maximumFractionDigits: 2
                    });

                    var auction_weight_avg = response.auction_weight_avg;
                    auction_weight_avg = parseFloat(auction_weight_avg).toLocaleString("en-US", {
                        minimumFractionDigits: 2,
                        maximumFractionDigits: 2
                    });

                    var highest_bid = response.highest_bid;
                    highest_bid = parseFloat(highest_bid).toLocaleString("en-US", {
                        minimumFractionDigits: 2,
                        maximumFractionDigits: 2
                    });

                    $('#total_bid_count').html(total_bid_count);
                    $('#total_auction_value').html(total_auction_value);
                    $('#auction_weight_avg').html(auction_weight_avg);
                    $('#highest_bid').html(highest_bid);
                    @if (Auth::check())
                        if (!is_event_ended && isEventStatusChanged) {
                            for (var i = 0; i < productsInfo.length; i++) {
                                var productId = productsInfo[i].id;
                                var last_bid_userid = productsInfo[i].highest_bidder_id;
                                updateBidAreaElements(isOpenedEvent, productId, last_bid_userid);
                            }
                            // autopopupbidnow();
                        }
                    @endif
                } catch (error) {
                    showFatalErrorOverlay(error);
                }
            }

            function updateProductsInfo(product) {
                try {
                    const userid = ('{{ Auth::id() }}' === '') ? 0 : '{{ Auth::id() }}';
                    const isHighestBidder = userid == product.last_bid_userid;
                    const newTotalPrice = parseToNumber(product.total_price);

                    const productIndex = productsInfo.findIndex(productInfo => productInfo.id === product.product_id);
                    productsInfo[productIndex] = {
                        ...productsInfo[productIndex],
                        isHighest: isHighestBidder,
                        totalPrice: newTotalPrice,
                    };

                    const activeTabId = getActiveTabId();
                    if (activeTabId === "dashboard") {
                        const filteredProductsInfo = productsInfo.filter(productInfo => productInfo.isHighest);

                        if (filteredProductsInfo.length) {
                            let newLiabilityValue = filteredProductsInfo.reduce(
                                (accumulator, productInfo) => accumulator + productInfo.totalPrice, 0);

                            let totalWeightValue = filteredProductsInfo.reduce(
                                (accumulator, productInfo) => accumulator + productInfo.totalWeight, 0);

                            const totalLiabilityElem = $("#total-liability");
                            totalLiabilityElem.attr("data-usd", newLiabilityValue);
                            let convertedTotalLiability = convertBySelectedCurrencyRate(newLiabilityValue, toRate);
                            totalLiabilityElem.attr("data-current", convertedTotalLiability);

                            const totalWeightElem = $("#total-weight");
                            totalWeightElem.attr("data-lb", totalWeightValue);
                            totalWeightValue = convertWeight(totalWeightValue, weightUnit);
                            totalWeightElem.attr("data-current", totalWeightValue);
                            totalWeightElem.text(parseFloat(totalWeightValue).toLocaleString("en-US", {
                                minimumFractionDigits: 2,
                                maximumFractionDigits: 2
                            }));

                            const averagePriceElem = $("#average-price");

                            let convertedAveragePrice = totalWeightValue === 0 ? 0 : convertedTotalLiability /
                                totalWeightValue;
                            convertedAveragePrice = parseFloat(convertedAveragePrice).toLocaleString("en-US", {
                                minimumFractionDigits: 2,
                                maximumFractionDigits: 2
                            });
                            averagePriceElem.text(convertedAveragePrice);

                            convertedTotalLiability = parseFloat(convertedTotalLiability).toLocaleString("en-US", {
                                minimumFractionDigits: 2,
                                maximumFractionDigits: 2
                            });
                            totalLiabilityElem.text(convertedTotalLiability);

                            const budgetElem = $("#budget");
                            let budgetValue = parseFloat(budgetElem.attr("data-usd"));
                            let spentBudgetValue = newLiabilityValue;
                            let remainingValue = budgetValue - spentBudgetValue;

                            const spentBudgetValueElem = $("#spent-budget-value");
                            spentBudgetValueElem.attr("data-usd", spentBudgetValue);
                            let convertedSpentBudgetValue = convertBySelectedCurrencyRate(spentBudgetValue, toRate);
                            convertedSpentBudgetValue = parseFloat(convertedSpentBudgetValue).toLocaleString("en-US", {
                                minimumFractionDigits: 2,
                                maximumFractionDigits: 2
                            });
                            spentBudgetValueElem.text(convertedSpentBudgetValue);

                            const remainingBudgetValueElem = $("#remaining-budget-value");
                            remainingBudgetValueElem.attr("data-usd", remainingValue);
                            let convertedRemainingBudgetValue = convertBySelectedCurrencyRate(remainingValue, toRate);
                            convertedRemainingBudgetValue = parseFloat(convertedRemainingBudgetValue).toLocaleString(
                                "en-US", {
                                    minimumFractionDigits: 2,
                                    maximumFractionDigits: 2
                                });
                            remainingBudgetValueElem.text(convertedRemainingBudgetValue);

                            updateBudgetProgressBar(budgetValue, spentBudgetValue);
                        } else {
                            let newLiabilityValue = "0.00";
                            let totalWeightValue = "0.00";
                            let averagePriceValue = "0.00";

                            const totalLiabilityElem = $("#total-liability");
                            totalLiabilityElem.attr("data-usd", newLiabilityValue);
                            totalLiabilityElem.attr("data-current", newLiabilityValue);
                            totalLiabilityElem.text(newLiabilityValue);

                            const totalWeightElem = $("#total-weight");
                            totalWeightElem.attr("data-lb", totalWeightValue);
                            totalWeightElem.attr("data-current", totalWeightValue);
                            totalWeightElem.text(totalWeightValue);

                            const averagePriceElem = $("#average-price");
                            averagePriceElem.text(averagePriceValue);

                            const budgetElem = $("#budget");
                            let budgetValue = parseFloat(budgetElem.attr("data-usd"));
                            let spentBudgetValue = "0.00";
                            let remainingValue = "0.00";

                            const spentBudgetValueElem = $("#spent-budget-value");
                            spentBudgetValueElem.attr("data-usd", spentBudgetValue);
                            spentBudgetValueElem.text(spentBudgetValue);

                            const remainingBudgetValueElem = $("#remaining-budget-value");
                            remainingBudgetValueElem.attr("data-usd", remainingValue);
                            remainingBudgetValueElem.text(remainingValue);

                            updateBudgetProgressBar(budgetValue, 0);
                        }
                    }

                } catch (error) {
                    showFatalErrorOverlay(error);
                }
            }

            function applyProductChanges(product, idd, idSuffix, activeTabId) {
                try {
                    let isOpenedEvent = product.bid_status == "open";
                    const temp_max_bid = idd.html()?.trim();

                    if (temp_max_bid != product.amount) {
                        applyAnimate(idd);
                    }

                    idd.html(product.amount);
                    idd.attr("title", product.amount);

                    const productBidsCountElem = $('#product_' + product.product_id + '_bids_count' + idSuffix);
                    productBidsCountElem.html(product.bid_count);
                    productBidsCountElem.attr("title", product.bid_count);

                    const productBidsElem = $('#product_' + product.product_id + '_bids' + idSuffix);
                    productBidsElem.html(product.bid_count);
                    productBidsElem.attr("title", product.bid_count);

                    const productTotalPriceElem = $('#product_' + product.product_id + '_total_price' + idSuffix);
                    productTotalPriceElem.html(product.total_price);
                    productTotalPriceElem.attr("title", product.total_price);

                    const userid = ('{{ Auth::id() }}' === '') ? 0 : '{{ Auth::id() }}';
                    const closestAuctionItemContentBid = idd.closest('.auction__item-content_bid');

                    if (activeTabId === "auction-view" || (activeTabId === "dashboard" && !idSuffix)) {
                        let newSuggestedValue = product.new_bidding_value;
                        let newSuggestedValueDecimal = getDecimalValue(newSuggestedValue);
                        closestAuctionItemContentBid.find('.suggested-value').val(newSuggestedValueDecimal);
                    }

                    if (userid == product.last_bid_userid) {
                        if (isOpenedEvent) {
                            closestAuctionItemContentBid.find('.bid_now').hide();
                            closestAuctionItemContentBid.find('.auto_bid_now.bid-with-anim').hide();
                            closestAuctionItemContentBid.find('.highest-bidder').show();
                            if (activeTabId === "overview") {
                                closestAuctionItemContentBid.find('.auction__item-numOfBids').hide();
                                closestAuctionItemContentBid.find('.autobid-settings').hide();
                            }
                        }

                        if (idSuffix === "_clone1" && activeTabId === "dashboard") {
                            const currentWinningLotsCount = productsInfo.filter(productInfo => productInfo.isHighest)
                                .length;
                            const isNavEnabled = currentWinningLotsCount > 4;
                            const currentWinningLostsCarouselConfig = {
                                loop: false,
                                dots: false,
                                nav: isNavEnabled,
                                navText: ["<div class='prev-slide'><i class='las la-angle-double-left'></i></div>",
                                    "<div class='next-slide'><i class='las la-angle-double-right'></i></div>"
                                ],
                                checkVisible: false,
                                autoplay: false,
                                margin: 0,
                                autoWidth: true,
                                responsive: {},
                            }
                            $('#product_id_' + product.product_id + "_clone1").removeClass('d-none');
                            const owlThreeElem = $('#owl-three');
                            owlThreeElem.owlCarousel('destroy');
                            owlThreeElem.owlCarousel(currentWinningLostsCarouselConfig);
                        }
                    } else {
                        if (isOpenedEvent) {
                            if (activeTabId === "overview") {
                                closestAuctionItemContentBid.find('.bid_now').hide();
                                closestAuctionItemContentBid.find('.auto_bid_now.bid-with-anim').hide();
                            } else {
                                closestAuctionItemContentBid.find('.bid_now').show();
                                closestAuctionItemContentBid.find('.auto_bid_now.bid-with-anim').show();
                            }
                            closestAuctionItemContentBid.find('.auction__item-numOfBids').show();
                            closestAuctionItemContentBid.find('.highest-bidder').hide();
                        }

                        if (idSuffix === "_clone1" && activeTabId === "dashboard") {
                            const currentWinningLotsCount = productsInfo.filter(productInfo => productInfo.isHighest)
                                .length;
                            const isNavEnabled = currentWinningLotsCount > 4;
                            const currentWinningLostsCarouselConfig = {
                                loop: false,
                                dots: false,
                                nav: isNavEnabled,
                                navText: ["<div class='prev-slide'><i class='las la-angle-double-left'></i></div>",
                                    "<div class='next-slide'><i class='las la-angle-double-right'></i></div>"
                                ],
                                checkVisible: false,
                                autoplay: false,
                                margin: 0,
                                autoWidth: true,
                                responsive: {},
                            }
                            $('#product_id_' + product.product_id + "_clone1").addClass('d-none');
                            const owlThreeElem = $('#owl-three');
                            owlThreeElem.owlCarousel('destroy');
                            owlThreeElem.owlCarousel(currentWinningLostsCarouselConfig);
                        }
                    }
                } catch (error) {
                    showFatalErrorOverlay(error);
                }
            }

            function updateProduct(product) {
                try {
                    updateProductsInfo(product);

                    // const oldBidStatusElement = $('#old_bid_status_' + product.product_id);
                    // let old_bid_status = oldBidStatusElement.val();

                    let bid_view = product.bid_view;

                    if (product.is_event_ended) {
                        $('#bid_area_' + product.product_id).html(bid_view);
                        // oldBidStatusElement.val(product.bid_status);
                    }

                    if (product.max_bid) {
                        const activeTabId = getActiveTabId();
                        const originalIdd = $('#product_' + product.product_id + '_price');

                        if (originalIdd.length > 0) {
                            applyProductChanges(product, originalIdd, "", activeTabId);
                        }

                        if (activeTabId === "dashboard") {
                            const cloneIdd1 = $('#product_' + product.product_id + '_price_clone1');

                            if (cloneIdd1.length > 0) {
                                applyProductChanges(product, cloneIdd1, "_clone1", activeTabId);
                            }
                        }
                    }
                    // checkBidLogin(product.product_id);
                    // autopopupbidnow();
                } catch (error) {
                    showFatalErrorOverlay(error);
                }
            }

            @if (Auth::check())
                checkHighestBidders(false);
                autopopupbidnow();
                popupbidnow();
            @endif
        })
        (jQuery);

        function removeHighlightEffect() {
            const highlightedAuction = $('.highlighted__auction');
            highlightedAuction.removeClass('highlighted__auction');
        }
    </script>

    <script>
        window.addEventListener("pageshow", function(event) {
            var historyTraversal = event.persisted ||
                (typeof window.performance != "undefined" &&
                    window.performance.navigation.type === 2);
            if (historyTraversal) {
                window.location.reload();
            }
        });

        $(document).ready(function() {
            try {
                $('#owl-one').owlCarousel({
                    loop: false,
                    dots: false,
                    checkVisible: false,
                    autoplay: false,
                    autoWidth: true,
                    responsive: {}
                });
            } catch (err) {
                Sentry.captureException(err);
            }
        });
    </script>

    <script src="{{ asset($activeTemplateTrue . 'js/jquery-ui.min.js') }}"></script>
    <script>
        $(function() {
            $(".countdown-wrapper").draggable({
                cancel: ".countdown-widget-btn"
            });
        });
    </script>

    <script>
        function submitNewScore(product_id, btnElem) {
            try {
                const userScoreElem = btnElem.closest(`.user-score-${product_id}`);
                const userScoreInputElem = userScoreElem.querySelector(`.user-score-input`);
                const userScoreValue = userScoreInputElem.value;
                const invalidScoreValueElem = userScoreElem.querySelector(".invalid-score-value");
                if (+userScoreValue < 1 || +userScoreValue > 100) {
                    invalidScoreValueElem.classList.remove("d-none");
                    return;
                }
                const scoreProgressElem = userScoreElem.querySelector(".score-progress");
                scoreProgressElem.classList.remove("d-none");

                var requestData = {
                    product_id: product_id,
                    score: userScoreValue,
                    _token: "{{ csrf_token() }}"
                };
                var url = "{{ route('user.ajax.submit_new_score') }}";
                $.ajax(url, {
                        data: requestData,
                        type: "POST",
                        timeout: AJAX_TIMEOUT
                    }).done(function(data, textStatus, jqXHR) {
                        try {
                            iziToast.success({
                                message: "@lang('Your Score Added Successfully')",
                                position: "topRight"
                            });
                            scoreProgressElem.classList.add("d-none");
                            invalidScoreValueElem.classList.add("d-none");
                            const userScoreElements = document.querySelectorAll(`.user-score-${product_id}`);
                            for (let i = 0; i < userScoreElements.length; ++i) {
                                const userScoreElement = userScoreElements[i];
                                const userScoreInputElem = userScoreElement.querySelector(`.user-score-input`);
                                userScoreInputElem.value = userScoreValue;
                                const userScoreValueElem = userScoreElement.querySelector(".user-score-value");
                                userScoreValueElem.textContent = userScoreValue;
                                userScoreValueElem.dataset.score = userScoreValue;
                                const oldUserScoreValue = userScoreValueElem.dataset.score;
                                if (oldUserScoreValue == "false") {
                                    const userScoreValueContainerElem = userScoreElement.querySelector(
                                        ".user-score-value-container");
                                    userScoreValueContainerElem.classList.remove("d-none");
                                    const showModalBtnElem = userScoreElement.querySelector(
                                        ".show-user-score-modal-btn");
                                    const customTooltipElem = showModalBtnElem.querySelector(".custom-tooltip");
                                    customTooltipElem.innerHTML = "Edit your score";
                                    const iElem = showModalBtnElem.querySelector("i");
                                    iElem.classList.replace("fa-plus", "fa-pencil-alt");
                                }
                            }
                            const userModal = userScoreElem.querySelector(".user-score-modal");
                            userModal.classList.add("d-none");
                        } catch (error) {
                            showFatalErrorOverlay(error);
                        }
                    })
                    .fail(function(jqXHR, textStatus, errorThrown) {
                        scoreProgressElem.classList.add("d-none");
                        invalidScoreValueElem.classList.add("d-none");
                        ajaxErrorHandler(jqXHR, errorThrown);
                    })
            } catch (error) {
                showFatalErrorOverlay(error);
            }
        }

        function saveEventRequest(event_id) {

            $('#request-access-btn').prop('disabled', true);
            $('#btn-text').hide();
            $('#loader').show();

            $.ajax({
                url: '{{ url('user/store-request') }}/' + event_id,
                type: 'GET',
                success: function(response) {
                    iziToast.success({
                        message: "@lang('Your request has been submitted, your access will be granted once the Admin approves it.')",
                        position: "topRight"
                    });
                    var button = $(`#request-access-btn`);
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
                    $('#request-access-btn').prop('disabled', false);
                    $('#loader').hide();
                    $('#btn-text').show();
                }
            });
        }

        function showUserScoreModal(productId, elem) {
            try {
                const userScoreElem = elem.closest(`.user-score-${productId}`);
                const modalElem = userScoreElem.querySelector(".user-score-modal");
                modalElem.classList.remove("d-none");
            } catch (err) {
                Sentry.captureException(err);
            }
        }

        function hideUserScoreModal(productId, elem) {
            try {
                const userScoreElem = elem.closest(`.user-score-${productId}`);
                const modalElem = userScoreElem.querySelector(".user-score-modal");
                modalElem.classList.add("d-none");
            } catch (err) {
                Sentry.captureException(err);
            }
        }


        (function($) {
            var userRequests = {!! $userRequests ?? 'null' !!};

            // Replace $event->id with the actual ID you want to check
            var currentEventId = {!! $event ?? 'null' !!};
            // Check if the currentEventId is in the userRequests array
            if (userRequests && userRequests.includes(currentEventId.id)) {
                $('#eventModalContainer').html(`
        <div style="opacity: 0.5;" class="modal-backdrop"></div>
            <div style="top: 30vh;" class="modal d-block ">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">@lang('Access Granted!')</h5>
                            <button onclick="$('#eventModalContainer').html('')" class="btn text--danger modal-close" data-bs-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true"></span>
                            </button>
                        </div>
                        <div style='color: #242828DE;' class="modal-body">
                            <h6 class="message">
                            {!! __("Your access to ' :event ' auction is approved!", ['event' => $event->name]) !!}
                            </h6>
                            <p class="my-4">
                                {!! __(
                                    'Before you start bidding, please review and accept our <a style="color: ${currentEventId.event_type !== "ace_event" ? "var(--primary-color)" : "var(--secondary-color)"} !important;  text-decoration: underline !important;"  target="_blank;" href="../../agreement/:EventId"> Bidder Agreement </a> and <a style="color: ${currentEventId.event_type !== "ace_event" ? "var(--primary-color)" : "var(--secondary-color)"} !important;  text-decoration: underline !important;"  target="_blank" href="../../page/:Policy/terms-and-conditions">Terms and Conditions</a>',
                                    [
                                        'EventId' => $event->id,
                                        'Policy' => $policy_id,
                                        'TermsAndConditions' =>
                                            '<a style="color: var(--primary-color) !important; text-decoration: underline !important;"  target="_blank" href="../../page/' .
                                            $policy_id .
                                            '/terms-and-conditions">' .
                                            __('Terms and Conditions') .
                                            '</a>',
                                        'BidderAgreement' =>
                                            '<a style="color: var(--primary-color)  !important; text-decoration: underline !important; text-decoration: underline !important;"  target="_blank" href="../../agreement/:EventId">' .
                                            __('Bidder Agreement') .
                                            '</a>',
                                    ],
                                ) !!}

                            </p>
                        </div>
                        <div class="modal-footer gap-2">
                            <a class="text--btn" style="user-select:none;cursor: pointer;" onclick="$('#eventModalContainer').html('')"  data-bs-dismiss="modal">@lang('Decline')</a>
                            <a style="user-select:none;cursor: pointer;" onclick="acceptEventTerms(${currentEventId.id})"><button  style="background-color: var(--button-contained-background) !important" class="btn btn--base cmn--btn">@lang('Accept')</button></a>
                        </div>
                    </div
                </div>
            </div>
        `);
            }
        })(jQuery);
    </script>
@endpush
