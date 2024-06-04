@php
    $categories = App\Models\Category::where('status', 1)->latest()->limit(4)->get();
    $policyPages = getContent('policy_pages.element');
    $contact = getContent('contact_us.content', true);
@endphp

<style>
    .footer-link-color {
        color: var(--footer-links-color) !important;
        fill: var(--footer-links-color) !important;
        font-size: 16px !important;
    }

    .footer-link-color:hover {
        color: var(--footer-hover-color) !important;
        fill: var(--footer-links-color) !important;
    }
</style>
<footer class="footer-section footer--section">
    @if (getIsTemplateFooter())
        <div class="container">
            <div class="footer-top">
                <div class="footer-wrapper px-2 px-sm-0 flex-md-nowrap align-items-center">
                    <img style="max-width: 200px;" id="footer-logo" />

                    <div class="d-flex align-items-center footer__nav-links">

                        @if (getNewAuctionId() && getNewAuctionLabel() && ($event = \App\Models\Event::find(getNewAuctionId())) != null)
                            <li class="nav-item dropdown">
                                <a class="nav-link footer-link-color"
                                    href="{{ route('event.details', [$event->id, slug($event->name)]) }}">{{ getNewAuctionLabel() }}</a>
                            </li>
                        @endif
                        @if (getPastAuctionLabel())

                            <li class="nav-item dropdown">
                                <a class="nav-link footer-link-color dropdown-toggle" href="#"
                                    id="navbarDropdownPastAuctions" role="button" data-toggle="dropdown"
                                    aria-haspopup="true" aria-expanded="false">
                                    {{ getPastAuctionLabel() }}
                                </a>
                                <div style="max-height: 550px; overflow-y: auto; overflow-x: hidden;"
                                    class="dropdown-menu" aria-labelledby="navbarDropdownPastAuctions">
                                    @foreach (\App\Models\Event::where('practice', 0)->get() as $event)
                                        <a class="dropdown-item"
                                            href="{{ route('event.details', [$event->id, slug($event->name)]) }}">{{ $event->name }}</a>
                                    @endforeach
                                </div>
                            </li>
                        @endif

                        @foreach (is_array(getNavbarLinks()) ? getNavbarLinks() : [] as $link)
                            <li class="nav-item dropdown">
                                @if (!($link['is_menu'] ?? null))
                                    <a class="nav-link footer-link-color"
                                        href="{{ $link['with_iframe'] ?? 'off' == 'on' ? route('external', ['link' => $link['url']]) : $link['url'] }}">{{ $link['label'] ?? '' }}</a>
                                @else
                                    <a class="nav-link footer-link-color dropdown-toggle" href="#"
                                        id="navbarDropdown{{ $loop->index }}" role="button" data-toggle="dropdown"
                                        aria-haspopup="true" aria-expanded="false">
                                        {{ $link['label'] ?? '' }}
                                    </a>
                                    <div class="dropdown-menu" aria-labelledby="navbarDropdown{{ $loop->index }}">
                                        @foreach (isset($link['menu_items']) && is_array($link['menu_items']) ? $link['menu_items'] : [] as $subMenu)
                                            <a class="dropdown-item"
                                                href="{{ $subMenu['with_iframe'] ?? 'off' == 'on' ? route('external', ['link' => $subMenu['url']]) : $subMenu['url'] }}">{{ $subMenu['label'] ?? '' }}</a>
                                        @endforeach
                                    </div>
                                @endif
                            </li>
                        @endforeach

                    </div>

                    <div class='all-socials'>

                        <div class="d-flex align-items-center gap-4">
                            @if (getFooterFacebookLink())
                                <a href="{{ getFooterFacebookLink() }}" target="_blank">
                                    <svg width="9" height="18" viewBox="0 0 9 18" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path class="footer-link-color"
                                            d="M2.34728 8.67128C2.28667 8.67128 2.24004 8.67128 2.19341 8.67128C1.58725 8.67128 0.981083 8.67128 0.374919 8.67128C0.295651 8.67128 0.249023 8.6573 0.249023 8.55944C0.253686 7.55749 0.249023 6.55554 0.253686 5.55359C0.253686 5.53961 0.258349 5.52097 0.263012 5.49301C0.953107 5.49301 1.64786 5.49301 2.35195 5.49301C2.35195 5.43709 2.35195 5.39515 2.35195 5.3532C2.35195 4.78466 2.35195 4.21145 2.35195 3.6429C2.35195 3.33067 2.43588 3.03707 2.52913 2.74348C2.65037 2.35668 2.85087 2.01183 3.08401 1.69027C3.28451 1.41066 3.54096 1.18231 3.81607 0.967936C4.07252 0.767547 4.35229 0.613759 4.64605 0.492594C4.83256 0.41803 5.0284 0.366768 5.22423 0.320165C5.85371 0.157058 6.49718 0.245602 7.13132 0.231621C7.60226 0.222301 8.07321 0.231621 8.53949 0.226961C8.63274 0.226961 8.66072 0.254922 8.66072 0.348127C8.66072 1.33609 8.66072 2.32872 8.66072 3.31669C8.66072 3.34465 8.65606 3.36795 8.65606 3.40989C8.60476 3.40989 8.55814 3.40989 8.51617 3.40989C7.73748 3.40989 6.95413 3.40523 6.17544 3.41455C6.02157 3.41455 5.86304 3.43319 5.73248 3.54038C5.6299 3.62426 5.55063 3.72679 5.54597 3.85261C5.53664 4.37922 5.5413 4.91048 5.5413 5.43709C5.5413 5.45107 5.54597 5.46505 5.55529 5.49301C6.59043 5.49301 7.62091 5.49301 8.67937 5.49301C8.66538 5.59554 8.65606 5.68408 8.64207 5.77262C8.62342 5.87981 8.59544 5.98699 8.58145 6.09884C8.54415 6.33185 8.52083 6.56952 8.47887 6.80253C8.44157 7.02622 8.39028 7.24992 8.34831 7.47827C8.32966 7.58079 8.31101 7.68332 8.29702 7.78584C8.26438 8.02351 8.23174 8.26584 8.19444 8.50351C8.18511 8.55944 8.15714 8.6107 8.13382 8.67594C7.27587 8.67594 6.41791 8.67594 5.54597 8.67594C5.5413 8.74119 5.53664 8.79711 5.53664 8.85303C5.53664 11.5233 5.53664 14.1983 5.53664 16.8686C5.53664 17.069 5.53664 17.069 5.33614 17.069C4.40358 17.069 3.47102 17.069 2.53846 17.069C2.35195 17.069 2.35195 17.069 2.35195 16.8779C2.35195 14.203 2.35195 11.528 2.35195 8.85303C2.34728 8.79245 2.34728 8.74119 2.34728 8.67128Z"
                                            fill="white" />
                                    </svg>
                                </a>
                            @endif

                            @if (getFooterInstagramLink())
                                <a href="{{ getFooterInstagramLink() }}" target="_blank">
                                    <svg width="17" height="17" viewBox="0 0 17 17" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path class="footer-link-color"
                                            d="M8.67004 0.638916C6.49904 0.638916 6.22604 0.648916 5.37304 0.686916C4.52004 0.726916 3.93904 0.860916 3.43004 1.05892C2.89614 1.25974 2.41254 1.57474 2.01304 1.98192C1.60612 2.38161 1.29115 2.86516 1.09004 3.39892C0.892044 3.90692 0.757044 4.48892 0.718044 5.33892C0.680044 6.19392 0.670044 6.46592 0.670044 8.63992C0.670044 10.8119 0.680044 11.0839 0.718044 11.9369C0.758044 12.7889 0.892044 13.3699 1.09004 13.8789C1.29504 14.4049 1.56804 14.8509 2.01304 15.2959C2.45704 15.7409 2.90304 16.0149 3.42904 16.2189C3.93904 16.4169 4.51904 16.5519 5.37104 16.5909C6.22504 16.6289 6.49704 16.6389 8.67004 16.6389C10.843 16.6389 11.114 16.6289 11.968 16.5909C12.819 16.5509 13.402 16.4169 13.911 16.2189C14.4446 16.018 14.9279 15.703 15.327 15.2959C15.772 14.8509 16.045 14.4049 16.25 13.8789C16.447 13.3699 16.582 12.7889 16.622 11.9369C16.66 11.0839 16.67 10.8119 16.67 8.63892C16.67 6.46592 16.66 6.19392 16.622 5.33992C16.582 4.48892 16.447 3.90692 16.25 3.39892C16.049 2.86514 15.734 2.38159 15.327 1.98192C14.9277 1.57459 14.444 1.25956 13.91 1.05892C13.4 0.860916 12.818 0.725916 11.967 0.686916C11.113 0.648916 10.842 0.638916 8.66804 0.638916H8.67104H8.67004ZM7.95304 2.08092H8.67104C10.807 2.08092 11.06 2.08792 11.903 2.12692C12.683 2.16192 13.107 2.29292 13.389 2.40192C13.762 2.54692 14.029 2.72092 14.309 3.00092C14.589 3.28092 14.762 3.54692 14.907 3.92092C15.017 4.20192 15.147 4.62592 15.182 5.40592C15.221 6.24892 15.229 6.50192 15.229 8.63692C15.229 10.7719 15.221 11.0259 15.182 11.8689C15.147 12.6489 15.016 13.0719 14.907 13.3539C14.7788 13.7013 14.5741 14.0154 14.308 14.2729C14.028 14.5529 13.762 14.7259 13.388 14.8709C13.108 14.9809 12.684 15.1109 11.903 15.1469C11.06 15.1849 10.807 15.1939 8.67104 15.1939C6.53504 15.1939 6.28104 15.1849 5.43804 15.1469C4.65804 15.1109 4.23504 14.9809 3.95304 14.8709C3.60554 14.7428 3.29117 14.5385 3.03304 14.2729C2.76679 14.0149 2.56177 13.7006 2.43304 13.3529C2.32404 13.0719 2.19304 12.6479 2.15804 11.8679C2.12004 11.0249 2.11204 10.7719 2.11204 8.63492C2.11204 6.49892 2.12004 6.24692 2.15804 5.40392C2.19404 4.62392 2.32404 4.19992 2.43404 3.91792C2.57904 3.54492 2.75304 3.27792 3.03304 2.99792C3.31304 2.71792 3.57904 2.54492 3.95304 2.39992C4.23504 2.28992 4.65804 2.15992 5.43804 2.12392C6.17604 2.08992 6.46204 2.07992 7.95304 2.07892V2.08092ZM12.941 3.40892C12.815 3.40892 12.6901 3.43375 12.5737 3.48199C12.4572 3.53024 12.3514 3.60095 12.2622 3.69009C12.1731 3.77924 12.1024 3.88507 12.0541 4.00154C12.0059 4.11801 11.981 4.24285 11.981 4.36892C11.981 4.49499 12.0059 4.61982 12.0541 4.73629C12.1024 4.85276 12.1731 4.95859 12.2622 5.04774C12.3514 5.13688 12.4572 5.2076 12.5737 5.25584C12.6901 5.30408 12.815 5.32892 12.941 5.32892C13.1957 5.32892 13.4398 5.22777 13.6199 5.04774C13.7999 4.8677 13.901 4.62352 13.901 4.36892C13.901 4.11431 13.7999 3.87013 13.6199 3.69009C13.4398 3.51006 13.1957 3.40892 12.941 3.40892ZM8.67104 4.53092C8.12611 4.52241 7.58494 4.6224 7.07902 4.82506C6.57311 5.02771 6.11256 5.32899 5.72419 5.71134C5.33583 6.09369 5.02741 6.54948 4.81688 7.05218C4.60636 7.55487 4.49794 8.09442 4.49794 8.63942C4.49794 9.18441 4.60636 9.72396 4.81688 10.2267C5.02741 10.7293 5.33583 11.1851 5.72419 11.5675C6.11256 11.9498 6.57311 12.2511 7.07902 12.4538C7.58494 12.6564 8.12611 12.7564 8.67104 12.7479C9.74959 12.7311 10.7783 12.2908 11.535 11.5222C12.2918 10.7535 12.7159 9.71809 12.7159 8.63942C12.7159 7.56074 12.2918 6.52533 11.535 5.75667C10.7783 4.988 9.74959 4.54774 8.67104 4.53092ZM8.67104 5.97192C9.37838 5.97192 10.0567 6.2529 10.5569 6.75306C11.0571 7.25322 11.338 7.93158 11.338 8.63892C11.338 9.34625 11.0571 10.0246 10.5569 10.5248C10.0567 11.0249 9.37838 11.3059 8.67104 11.3059C7.96371 11.3059 7.28535 11.0249 6.78519 10.5248C6.28503 10.0246 6.00404 9.34625 6.00404 8.63892C6.00404 7.93158 6.28503 7.25322 6.78519 6.75306C7.28535 6.2529 7.96371 5.97192 8.67104 5.97192Z"
                                            fill="white" />
                                    </svg>

                                </a>
                            @endif

                            @if (getFooterYoutubeLink())
                                <a href="{{ getFooterYoutubeLink() }}" target="_blank">
                                    <svg class="footer-link-color" width="24" height="18" viewBox="0 0 24 18"
                                        fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <g clip-path="url(#clip0_3886_271)">
                                            <path class="footer-link-color"
                                                d="M12.1874 0.252746C14.1177 0.258933 16.0418 0.342456 17.9628 0.509502C18.7176 0.574464 19.4693 0.66108 20.221 0.76007C21.1924 0.886901 22.4947 1.65407 22.7607 3.16677C23.2093 5.68792 23.3763 8.22145 23.1319 10.7704C23.0299 11.847 22.8907 12.9204 22.7484 13.9938C22.6092 15.0363 21.9997 15.7292 21.0686 16.1623C20.5366 16.4098 19.9612 16.4809 19.392 16.5614C16.4532 16.9728 13.499 17.078 10.5386 17.0037C9.23621 16.9697 7.93078 16.9078 6.63463 16.8027C5.59833 16.716 4.56202 16.5892 3.54119 16.3943C2.45539 16.184 1.62016 15.5746 1.25204 14.4919C1.08809 14.0155 1.02932 13.4958 0.96126 12.9946C0.651916 10.7859 0.608608 8.56482 0.753999 6.34373C0.815868 5.29815 0.942699 4.25875 1.08809 3.22245C1.25204 2.07169 1.98519 1.3571 3.04624 1.01064C3.64327 0.815752 4.28052 0.726042 4.9054 0.64252C7.32137 0.333176 9.75282 0.246559 12.1874 0.252746ZM12.0481 16.0664C12.0481 16.054 12.0481 16.0417 12.0512 16.0293C14.3033 16.0417 17.0069 15.8684 19.1909 15.5622C19.6642 15.4941 20.1406 15.4415 20.586 15.2436C21.2264 14.9559 21.6409 14.5104 21.7152 13.7835C21.7554 13.3937 21.8265 13.007 21.8791 12.6203C22.0678 11.219 22.1977 9.81148 22.1977 8.39777C22.1977 6.79228 22.0214 5.19916 21.7337 3.61841C21.6842 3.34619 21.6285 3.06159 21.4986 2.82649C21.2016 2.28204 20.6881 2.00363 20.0973 1.8768C19.7415 1.79947 19.3765 1.75925 19.0115 1.71285C17.4988 1.51487 15.9737 1.41279 14.4518 1.35401C12.639 1.28596 10.8231 1.28596 9.01039 1.37257C7.55647 1.44063 6.10874 1.5458 4.6703 1.75925C4.1475 1.83659 3.61853 1.89536 3.13595 2.15212C2.66884 2.40269 2.27597 2.71822 2.1677 3.26576C2.0749 3.73905 2.00066 4.22163 1.94188 4.70111C1.74699 6.36848 1.69131 8.04203 1.7439 9.71867C1.78411 10.9622 1.8862 12.1996 2.15842 13.4153C2.22957 13.7401 2.29453 14.0804 2.43374 14.3774C2.71215 14.9713 3.24731 15.2683 3.85981 15.4106C5.05697 15.6859 6.28198 15.8097 7.50389 15.8808C9.01967 15.9581 10.5355 16.0045 12.0481 16.0664Z"
                                                fill="white" />
                                            <path class="footer-link-color"
                                                d="M9.05945 12.8307C9.05945 10.0281 9.05945 7.25326 9.05945 4.44751C11.5064 5.84574 13.9347 7.2347 16.3909 8.63912C13.9378 10.0405 11.5125 11.4294 9.05945 12.8307ZM10.1143 11.0427C11.5156 10.2353 12.8953 9.44342 14.2935 8.63912C12.8891 7.83173 11.5095 7.03672 10.1143 6.23552C10.1143 7.84411 10.1143 9.43104 10.1143 11.0427Z"
                                                fill="white" />
                                        </g>
                                        <defs>
                                            <clipPath id="clip0_3886_271">
                                                <rect class="footer-link-color" width="22.5759" height="16.7726"
                                                    fill="white" transform="translate(0.670044 0.252686)" />
                                            </clipPath>
                                        </defs>
                                    </svg>

                                </a>
                            @endif

                            @if (!empty(getFooterTwitterLink()))
                                <a style="margin-left: 0; margin-right: 0;" href="{{ getFooterTwitterLink() }}" target="_blank">
                                    @include('templates.basic.svgIcons.twitter')
                                </a>
                            @endif

                            @if (getFooterLinkedinLink())
                                <a href="{{ getFooterLinkedinLink() }}" target="_blank">
                                    <svg width="18" height="17" viewBox="0 0 18 17" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path class="footer-link-color"
                                            d="M3.94162 16.6389H0.245972V5.72983H3.94162V16.6389ZM2.09454 4.27528C1.07232 4.27528 0.245972 3.46001 0.245972 2.45637C0.245972 1.45273 1.0738 0.638916 2.09454 0.638916C3.11306 0.638916 3.94162 1.45419 3.94162 2.45637C3.94162 3.46001 3.11306 4.27528 2.09454 4.27528ZM17.246 16.6389H13.693V11.3298C13.693 10.0636 13.6686 8.43528 11.8459 8.43528C9.99584 8.43528 9.71128 9.81419 9.71128 11.2382V16.6389H6.15902V5.72183H9.56936V7.21346H9.61741C10.0919 6.35528 11.2516 5.45055 12.9812 5.45055C16.5808 5.45055 17.246 7.71164 17.246 10.6513V16.6389Z"
                                            fill="white" />
                                    </svg>

                                </a>
                            @endif

                            @if (!empty(getFooterVimeoLink()))
                                <a style="margin-left: 0; margin-right: 0;" href="{{getFooterVimeoLink() }}" target="_blank">
                                    @include('templates.basic.svgIcons.vimo')
                                </a>
                            @endif

                        </div>
                        @if (getFooterEmail())
                            <a class="footer-link-color" style="font-size: 14px;" href="mailto:{{ getFooterEmail() }}">
                                {{ getFooterEmail() }}
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    @else
        <div id="footer-image-container"
            style=" min-height: 288px; background-position: center; background-size: cover;"
            class="footer-wrapper px-2 px-sm-0 flex-wrap flex-md-nowrap">

        </div>
    @endif

    <div style="background-color: white;" class="footer-middle">
        <div class="container">
            <div class="footer-middle-wrapper">
                <div class="d-flex gap-2 align-items-center">
                    <span>@lang('Powered by:')</span>
                    <div class="logo">
                        <a href="{{ route('home') }}">
                            <img style="width: 96px"
                                src="{{ getImage(imagePath()['logoIcon']['path'] . '/logo.png') }}" alt="logo">
                        </a>
                    </div>
                </div>
                <div class="cont">
                    <p><a style="opacity: 0.7" target="_blank" href="../../page/{{ get_policy_id() }}/terms-and-conditions">Terms and Conditions</a></p>
                    <p> &copy; {{ date('Y') }} <a href="{{ route('home') }}"> by {{ $general->sitename }}</a>.
                        @lang('Made with ') <a style="opacity: 0.7" href="http://mcultivo.com/">CultivoCommerce</a></p>
                </div>
            </div>
        </div>
    </div>
</footer>

@push('script')
    <script>
        @if (getIsTemplateFooter() === "1")
            const largeImageUrlFooter =
                "{{ getFooterLogo() }}"
            const mediumImageUrlFooter =
                "{{ getFooterLogo('md') }}";
            const smallImageUrlFooter =
                "{{ getFooterLogo('sm') }}";

            setResponsiveImage(largeImageUrlFooter, mediumImageUrlFooter, smallImageUrlFooter, document.getElementById('footer-logo'),
                true);
        @else
            const largeImageUrlForFooterImage =
                "{{ getFooterImage() }}"
            const mediumImageUrlForFooterImage =
                "{{ getFooterImage('md') }}";
            const smallImageUrlForFooterImage =
                "{{ getFooterImage('sm') }}";

            setResponsiveImage(largeImageUrlForFooterImage, mediumImageUrlForFooterImage, smallImageUrlForFooterImage,
                document
                .getElementById('footer-image-container'),
                false);
        @endif
    </script>
@endpush
