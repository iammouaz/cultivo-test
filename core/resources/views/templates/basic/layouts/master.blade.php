@php
    $policy_id=get_policy_id();
@endphp
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <title> {{ getSiteTitle() }} - {{ $pageTitle }}</title>

    @include('partials.seo')

    <link rel="stylesheet" href="{{ asset('assets/global/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset($activeTemplateTrue.'css/animate.css') }}">
    <link rel="stylesheet" href="{{asset('assets/global/css/all.min.css')}}">
    <link rel="stylesheet" href="{{asset('assets/global/css/line-awesome.min.css')}}">
    <link rel="stylesheet" href="{{ asset($activeTemplateTrue.'css/lightbox.min.css') }}">
    <link rel="stylesheet" href="{{ asset($activeTemplateTrue.'css/owl.min.css') }}">
    <link rel="stylesheet" href="{{ asset($activeTemplateTrue.'css/headline.min.css') }}">
    <link rel="stylesheet" href="{{ asset($activeTemplateTrue.'css/dashboard-main.min.css') }}">
    <link rel="stylesheet" href="{{ asset($activeTemplateTrue.'css/bootstrap-fileinput.css') }}">
    <link rel="stylesheet" href="{{ asset($activeTemplateTrue.'css/color.php')}}?color={{ $general->base_color }}">
    <link rel="stylesheet" href="{{ asset($activeTemplateTrue.'css/dashboard-custom.min.css') }}">
    <link rel="shortcut icon" href="{{ getImage(imagePath()['logoIcon']['path'] .'/favicon.png') }}" type="image/x-icon">

    @stack('style-lib')
    @stack('style')

    <script
    src="{!! config('app.SENTRY_JS_DSN') !!}"
    crossorigin="anonymous"
    ></script>
</head>

<body>

    <main class="main-body">

        @include($activeTemplate.'partials.preloader')

        <div class="overlay"></div>
        <a href="#0" class="scrollToTop"><i class="las la-angle-up"></i></a>


        <main class="dashboard-section bg--section">
            @include($activeTemplate.'partials.sidenav')
            <article class="dashboard__article">
                <div class="dashboard__header  ">
                    <div class="dashboard__header-top">
                        <a class="visit-home-btn" a href="{{ route('home') }}" target=”_blank”> <i class="fa fa-desktop"></i>
                            {{__('Visit Home')}}</a>
                        <div class="dashboard__header__bar d-lg-none">
                            <div class="header-bar">
                                <span></span>
                                <span></span>
                                <span></span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="dashboard__body">
                    <div class="container-fluid">
                        @yield('content')
                    </div>
                </div>
                @include($activeTemplate.'partials.dashboard_footer')
            </article>
        </main>


    </main>

    @stack('modal')

    <script src="{{asset('assets/global/js/jquery-3.6.0.min.js')}}"></script>
    <script src="{{asset('assets/global/js/bootstrap.min.js')}}"></script>
    <script src="{{ asset($activeTemplateTrue.'js/rafcounter.min.js') }}"></script>
    <script src="{{ asset($activeTemplateTrue.'js/lightbox.min.js') }}"></script>
    <script src="{{ asset($activeTemplateTrue.'js/owl.min.js') }}"></script>
    <script src="{{ asset($activeTemplateTrue.'js/masonry.pkgd.min.js') }}"></script>
    <script src="{{ asset($activeTemplateTrue.'js/countdown.min.js') }}"></script>
    <script src="{{ asset($activeTemplateTrue.'js/headline.min.js') }}"></script>
    <script src="{{ asset($activeTemplateTrue.'js/main.js') }}"></script>
    <script src="{{ asset($activeTemplateTrue.'js/bootstrap-fileinput.js') }}"></script>
    <script src="{{ asset($activeTemplateTrue.'js/jquery.validate.js') }}"></script>

    @stack('script-lib')
    @include('partials.notify')
    @include('partials.plugins')
    @stack('script')

    <script>
        (function($) {
            "use strict";

            $(".langSel").on("change", function() {
                window.location.href = "{{route('home')}}/change/" + $(this).val();
            });

            $("form").validate();
            $('form').on('submit', function() {
                if ($(this).valid()) {
                    $(':submit', this).attr('disabled', 'disabled');
                }
            });

        })(jQuery);

        function submitform(id) {
            document.getElementById(id).submit();
        }
    </script>
</body>
</html>
