@php
    $event_type = $event_type ?? 'm_cultivo_event';//todo remove ace event logic
    $login_type = $login_type ?? 'normal';
    $policy_id = get_policy_id();
    $codes = get_extention_keys('pusher');
@endphp

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="event_type" content="{{ $event_type }}">
    <meta name="description" content="{{ getSiteDescription() }}">
    <meta property="og:image" content="{{ getSocialImage() }}">

    <title> {{ getSiteTitle() }} - {{ $pageTitle }} </title>
    @include('partials.seo')

    <!-- <link rel="stylesheet" href="{{ asset($activeTemplateTrue . 'css/animate.css') }}"> -->
    <!-- <link rel="stylesheet" href="{{ asset($activeTemplateTrue . 'css/lightbox.min.css') }}"> -->
    <!-- <link rel="stylesheet" href="{{ asset($activeTemplateTrue . 'css/headline.min.css') }}"> -->
    <!-- <link rel="stylesheet" href="{{ asset($activeTemplateTrue . 'css/bootstrap-fileinput.css') }}"> -->
    <!-- <link rel="stylesheet" href="{{ asset($activeTemplateTrue . 'css/color.php') }}?color={{ $general->base_color }}"> -->

    <link rel="stylesheet" href="{{ asset('assets/global/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/global/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/global/css/line-awesome.min.css') }}">

    <link rel="stylesheet" href="{{ asset($activeTemplateTrue . 'css/custom.css') }}">

    @if (request()->routeIs('buyers') ||
            request()->routeIs('producers') ||
            request()->routeIs('event.details') ||
            request()->routeIs('offer.details') ||
            request()->routeIs('offer_sheet.activeOffers') ||
            request()->routeIs('product.details') ||
            request()->routeIs('event.preview'))
        <!-- buyers, producers, event details, product details pages -->
        <link rel="stylesheet" href="{{ asset($activeTemplateTrue . 'css/owl.min.css') }}">
    @endif

    @if (request()->routeIs('user.checkout.index') ||
            request()->routeIs('user.checkout.payment') ||
            request()->routeIs('event.details') ||
            request()->routeIs('event.preview'))
        <!-- Checkout and Payment pages, event details pages -->
        <link rel="stylesheet" href="{{ asset($activeTemplateTrue . 'css/select2.min.css') }}">
    @endif

    <link rel="stylesheet" href="{{ asset($activeTemplateTrue . 'css/main.min.css') }}">
    <link rel="stylesheet" href="{{ asset($activeTemplateTrue . 'css/custom.min.css') }}">

    @if (request()->routeIs('user.login'))
        <!-- MC/ACE login pages styles -->
        <link rel="stylesheet" href="{{ asset($activeTemplateTrue . 'css/login-pages.min.css') }}">
    @elseif(request()->routeIs('home'))
        <!-- Home page styles -->
        <link rel="stylesheet" href="{{ asset($activeTemplateTrue . 'css/home.min.css') }}">
    @elseif(request()->routeIs('user.checkout.index') || request()->routeIs('user.checkout.payment'))
        <!-- Checkout and Payment pages styles -->
        <link rel="stylesheet" href="{{ asset($activeTemplateTrue . 'css/checkout-styles.min.css') }}">
    @elseif(request()->routeIs('policy'))
        <!-- Terms and conditions page styles -->
        <link rel="stylesheet" href="{{ asset($activeTemplateTrue . 'css/terms-and-conditions.min.css') }}">
    @elseif(request()->routeIs('buyers'))
        <!-- Buyers page styles -->
        <link rel="stylesheet" href="{{ asset($activeTemplateTrue . 'css/buyers.min.css') }}">
    @elseif(request()->routeIs('producers'))
        <!-- Producers page styles -->
        <link rel="stylesheet" href="{{ asset($activeTemplateTrue . 'css/producers.min.css') }}">
    @elseif(request()->routeIs('product.details') || request()->routeIs('event.details') || request()->routeIs('event.preview'))
        <!-- Event/Product details pages styles -->
        <link rel="stylesheet" href="{{ asset($activeTemplateTrue . 'css/events.min.css') }}">
    @endif

    <link rel="icon" type="image/png" href="{{ getFavicon() }}">

    @stack('style-lib')

    @stack('style')

    <style>
        .dropdown {
            position: relative;
            display: inline-block;
        }

        .dropdown-content {
            display: none;
            position: absolute;
            top: 100%;
            right: 0;
            background-color: #f9f9f9;
            min-width: 160px;
            box-shadow: 0px 8px 16px 0px rgba(0, 0, 0, 0.2);
            padding: 12px 16px;
            z-index: 1;
        }

        .dropdown:hover .dropdown-content {
            display: block;
        }

        :root {
            --primary-backgroud-color: {{ getSitewidePageBackground() }} !important;
            --footer-background-color: {{ getFooterBackground() }} !important;
            --footer-links-color: {{ getFooterLinks() }} !important;
            --footer-hover-color: {{ getFooterHover() }} !important;
            --nav-background-color: {{ getNavbarBackground() }} !important;
            --nav-links-color: {{ getNavbarNavLinks() }} !important;
            --nav-icons-color: {{ getNavbarNavIcons() }} !important;
            --nav-hover-color: {{ getNavbarHover() }} !important;
            --text-h1-color: {{ getSitewideTextHeading1() }} !important;
            --text-h2-color: {{ getSitewideTextHeading2() }} !important;
            --text-h3-color: {{ getSitewideTextHeading3() }} !important;
            --text-h4-color: {{ getSitewideTextHeading4() }} !important;
            --text-h5-color: {{ getSitewideTextHeading5() }} !important;
            --text-links-color: {{ getSitewideLinksTextLinks() }} !important;
            --button-contained-background: {{ getSitewideContainedButtonsBackground() }} !important;
            --button-contained-color: {{ getSitewideContainedButtonsText() }} !important;
            --button-contained-hover: {{ getSitewideContainedButtonsHover() }} !important;
            --button-outline-background: {{ getSitewideOutlinedButtonsBackground() }} !important;
            --button-outline-color: {{ getSitewideOutlinedButtonsText() }} !important;
            --button-outline-hover: {{ getSitewideOutlinedButtonsHover() }} !important;
            --button-text-color: {{ getSitewideTextButtonsColor() }} !important;
            --button-text-hover: {{ getSitewideTextButtonsHover() }} !important;
            --body-text-color: {{ getSitewideTextBody1() }} !important;
            --main-svg-color: {{ getSitewideIconsColor() }} !important;
            --hover-svg-color: {{ getSitewideIconsHover() }} !important;
            --hover-svg-background: {{ getSitewideIconsHoverBackground() }} !important;
            --header-text-style: {{ getHeadingsFontStyle() }};
            --header-text-letter: {{ getHeadingsLetterSpacing() }};
            --header-text-transform: {{ getHeadingsTextTransform() }};
            --paragraphs-text-style: {{ getParagraphsFontStyle() }};
            --paragraphs-text-letter: {{ getParagraphsLetterSpacing() }}px;
            --paragraphs-text-transform: {{ getParagraphsTextTransform() }};
            --cards-text-style: {{ getAuctionCardNumbersFontStyle() }};
            --cards-text-letter: {{ getAuctionCardNumbersLetterSpacing() }};
            --cards-text-transform: {{ getAuctionCardNumbersTextTransform() }};
            --button-contained-radius: {{ getSiteSettingValue('custom_corners')}}px;
            --button-contained-style: {{getSiteSettingValue('button_style')}};
            --button-contained-letter_spacing:  {{getSiteSettingValue('button_letter_spacing')}}px;
            --button-contained-text_transform:  {{getSiteSettingValue('button_text_transform')}};
            --button-outlined-radius: {{ getSiteSettingValue('outlined_custom_corners')}}px;
            --button-outlined-style: {{getSiteSettingValue('outlined_button_style')}};
            --button-outlined-letter_spacing:  {{getSiteSettingValue('outlined_button_letter_spacing')}}px;
            --button-outlined-text_transform:  {{getSiteSettingValue('outlined_button_text_transform')}};
            --button-text-radius: {{ getSiteSettingValue('text_custom_corners')}}px;
            --button-text-style: {{getSiteSettingValue('text_button_style')}};
            --button-text-letter_spacing:  {{getSiteSettingValue('text_button_letter_spacing')}}px;
            --button-text-text_transform:  {{getSiteSettingValue('text_button_text_transform')}};
            --chips-color-text: {{ getSitewideChipsTextAndIcon() }};
            --chips-color-background: {{ getSitewideChipsBackground() }};
            --radio-and-checkbox: {{ getCheckBoxAndRadioActiveColor() }};
            --tabs-active-color: {{ getTabsActiveColor() }};
            --tabs-hover-color: {{ getTabsHoverColor() }};
            --progress-bar-color: {{ getBudgetProgressBarColor() }};

        }

        @font-face {
            font-family: 'headingFont';
            src: url("{{ getHeadingsFontFamily() }}") format('truetype');

        }

        @font-face {
            font-family: 'paragraphsFont';
            src: url("{{ getParagraphsFontFamily() }}") format('truetype');

        }

        @font-face {
            font-family: 'cardsFont';
            src: url("{{ getAuctionCardNumbersFontFamily() }}") format('truetype');

        }

        @font-face {
            font-family: 'buttonFont';
            src: url("{{ getFontUrlSettingValue('button_family') }}") format('truetype');
        }

        @font-face {
            font-family: 'outlinedButtonFont';
            src: url("{{ getFontUrlSettingValue('outlined_button_family') }}") format('truetype');
        }

        @font-face {
            font-family: 'textButtonFont';
            src: url("{{ getFontUrlSettingValue('text_button_family') }}") format('truetype');
        }

        h1,
        h2,
        h3,
        h4,
        h5,
        h6 {
            font-family: 'headingFont', sans-serif;
            letter-spacing: var(--header-text-letter);
            text-transform: var(--header-text-transform);
            font-style: var(--header-text-style);
        }


        p,
        span,
        a,
        label,
        div {
            letter-spacing: var(--paragraphs-text-letter);
            text-transform: var(--paragraphs-text-transform);
            font-style: var(--paragraphs-text-style);
        }


        .auction-cards-number {
            font-family: 'cardsFont', sans-serif !important;
            letter-spacing: var(--cards-text-letter);
            text-transform: var(--cards-text-transform);
            font-style: var(--cards-text-style);
        }


    </style>
    <script src="{!! config('app.SENTRY_JS_DSN') !!}" crossorigin="anonymous"></script>

</head>

<body>

    @stack('fbComment')

    <main class="main-body">
        @include($activeTemplate . 'partials.fatal_error_overlay')
        @include($activeTemplate . 'partials.preloader')

        <div class="overlay"></div>
        <a href="#0" class="scrollToTop"><i class="las la-angle-up"></i></a>

        @if (
            !request()->routeIs('user.login') &&
                !request()->routeIs('user.register') &&
                !request()->routeIs('user.authorization') &&
                !request()->routeIs('user.password.request') &&
                !request()->routeIs('user.password.code.verify') &&
                !request()->routeIs('user.password.reset'))
{{--            @if ($event_type == 'ace_event' && (request()->routeIs('event.details') || request()->routeIs('event.preview')))--}}
{{--                @include($activeTemplate . 'partials.ACE_header', ['icons_theme' => 'light'])--}}
{{--            @elseif($event_type == 'ace_event' && request()->routeIs('product.details'))--}}
{{--                @include($activeTemplate . 'partials.ACE_header', ['icons_theme' => 'dark'])--}}
{{--            @else--}}
                @include($activeTemplate . 'partials.header',['login_type' => $login_type??"normal"])
{{--            @endif--}}
        @endif

        @if (
            !request()->routeIs('home') &&
                !request()->routeIs('user.login') &&
                !request()->routeIs('user.register') &&
                !request()->routeIs('user.authorization') &&
                !request()->routeIs('user.password.request') &&
                !request()->routeIs('user.password.code.verify') &&
                !request()->routeIs('user.password.reset') &&
                !request()->routeIs('vendor.profile') &&
                !request()->routeIs('admin.profile.view') &&
                !request()->routeIs('merchant.profile.view') &&
                !request()->routeIs('about.us') &&
                !request()->routeIs('contact') &&
                !request()->routeIs('product.details') &&
                !request()->routeIs('event.details') &&
                !request()->routeIs('policy') &&
                !request()->routeIs('category.events') &&
                !request()->routeIs('user.checkout.index') &&
                !request()->routeIs('user.checkout.payment') &&
                !request()->routeIs('buyers') &&
                !request()->routeIs('producers') &&
                !request()->routeIs('external') &&
                !request()->routeIs('event.preview'))
            @include($activeTemplate . 'partials.breadcrumb')
        @endif

        @yield('content')

        @if (
                !request()->routeIs('user.authorization') &&
                !request()->routeIs('user.password.request') &&
                !request()->routeIs('user.password.code.verify') &&
                !request()->routeIs('user.password.reset'))
            @if ($event_type == 'ace_event')
                @include($activeTemplate . 'partials.ACE_footer')
            @else
                @include($activeTemplate . 'partials.footer')
            @endif
        @endif

    </main>

    @php
        $cookie = App\Models\Frontend::where('data_keys', 'cookie.data')->first();
    @endphp

    @if (@$cookie->data_values->status && !Cookie::has('cookie_accepted'))
        <div class="cookies-card bg--default text-center cookies--dark radius--10px">
            <div class="cookies-card__icon">
                <i class="fas fa-cookie-bite" style="color:white"></i>
            </div>
            <p class="mt-4 mb-0 cookies-card__content"> @php echo @$cookie->data_values->description @endphp <a class="d-inline"
                    href="{{ @$cookie->data_values->link }}">@lang('Read Policy')</a></p>
            <div class="cookies-card__btn mt-4">
                <button class="cookies-btn btn--base w-100" id="allow-cookie">@lang('Allow')</button>
            </div>
        </div>
    @endif

    <div class="modal fade" id="sessionModal">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">@lang('session expired')</h5>
                    <!-- <button class="btn text--danger modal-close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button> -->
                </div>
                <div class="modal-body">
                    <h6 class="message">@lang('Your session is about to expire. Are you still there?')</h6>
                </div>
                <div class="modal-footer gap-2">
                    <button id="no_session" class="btn btn--danger">@lang('No')</button>
                    <button id="yes_session" class="btn btn--base">@lang('Yes')</button>
                </div>
            </div>
        </div>
    </div>

    <!-- <script src="{{ asset($activeTemplateTrue . 'js/lightbox.min.js') }}"></script> -->
    <!-- <script src="{{ asset($activeTemplateTrue . 'js/headline.min.js') }}"></script> -->
    <!-- <script src="{{ asset('assets/global/js/sticky.min.js') }}"></script> -->
    <!-- <script src="{{ asset('assets/global/js/jquery.multi-select.js') }}"></script> -->

    <script src="{{ asset('assets/global/js/jquery-3.6.0.min.js') }}"></script>
    <script src="{{ asset('assets/global/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset($activeTemplateTrue . 'js/rafcounter.min.js') }}"></script>

    @if (request()->routeIs('buyers') ||
            request()->routeIs('producers') ||
            request()->routeIs('event.details') ||
            request()->routeIs('event.preview') ||
            request()->routeIs('offer.details') ||
            request()->routeIs('offer_sheet.activeOffers') ||
            request()->routeIs('product.details'))
        <!-- buyers, producers, event details, product details pages -->
        <script src="{{ asset($activeTemplateTrue . 'js/owl.min.js') }}"></script>
    @endif

    <script src="{{ asset($activeTemplateTrue . 'js/masonry.pkgd.min.js') }}"></script>

    @if (request()->routeIs('event.details') || request()->routeIs('product.details') || request()->routeIs('event.preview'))
        <!-- event details, product details pages -->
        <script src="{{ asset($activeTemplateTrue . 'js/countdown.min.js') }}"></script>
        <!-- jquery.final-countdown depends on the kinetic package -->
        <script src="{{ asset($activeTemplateTrue . 'js/kinetic-v5.1.0.js') }}"></script>
        <script src="{{ asset($activeTemplateTrue . 'js/jquery.final-countdown.js') }}"></script>
    @endif

    @if (request()->routeIs('user.checkout.index') ||
            request()->routeIs('user.checkout.payment') ||
            request()->routeIs('event.details') ||
            request()->routeIs('event.preview'))
        <!-- Checkout and Payment pages, event details pages -->
        <script src="{{ asset($activeTemplateTrue . 'js/select2.min.js') }}"></script>
    @endif

    <script src="{{ asset($activeTemplateTrue . 'js/main.js') }}"></script>

    @stack('script-lib')

    @stack('script')

    @include('partials.plugins')

    @include('partials.notify')

    <script>
        function hideBootstrapTooltip(parentElem) {
            if (parentElem) {
                try {
                    var tooltip = bootstrap.Tooltip.getInstance(parentElem);
                    tooltip.hide();
                } catch (err) {
                    Sentry.captureException(err);
                }
            }
        }

        function initBootstrapTooltips() {
            try {
                var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
                var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
                    return new bootstrap.Tooltip(tooltipTriggerEl, {
                        offset: "0,7"
                    })
                })
            } catch (err) {
                Sentry.captureException(err);
            }
        }
        initBootstrapTooltips();
    </script>

    <script>
        (function($) {
            "use strict";
            $(".langSel").on("change", function() {
                window.location.href = "{{ route('home') }}/change/" + $(this).val();
            });

            var url = `{{ route('cookie.accept') }}`;
            $('#allow-cookie').on('click', function() {
                $.ajax(url, {
                    type: "GET",
                    timeout: AJAX_TIMEOUT
                }).done(function(data, textStatus, jqXHR) {
                    $('.cookies-card').hide();
                }).fail(function(jqXHR, textStatus, errorThrown) {
                    ajaxErrorHandler(jqXHR, errorThrown);
                });
            });
        })(jQuery);
    </script>

    @if (Auth::check())
        <script>
            try {
                var dt = new Date();
                dt.setHours(dt.getHours() + 2);
                var countForLogin = 0;
                var modal = $('#sessionModal');

                var intervalId = window.setInterval(function() {
                    checkSessionTimeOut();
                }, 3000);

                function checkSessionTimeOut() {
                    var dt_now = new Date();
                    dt_now.setMinutes(dt_now.getMinutes() + 1);
                    if (dt_now > dt) {
                        countForLogin++;
                        modal.modal('show');
                        if (countForLogin == 21) {
                            window.location.href = "/logout";
                        }
                    }
                }
                $("#yes_session").click(function() {
                    window.location.reload(1);
                });
                $("#no_session").click(function() {
                    window.location.href = "/logout";
                });
                checkSessionTimeOut();
            } catch (err) {
                Sentry.captureException(err);
            }
        </script>
    @endif

</body>

</html>
