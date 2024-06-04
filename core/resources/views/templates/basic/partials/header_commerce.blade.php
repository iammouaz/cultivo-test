@php
    $header = getContent('header.content', true);
    $user_id = false;
    if (Auth::user()) {
        $user_id = Auth::user()->id;
    }
@endphp
@php
    $category = getContent('categories.content', true);
    $categories = \App\Models\Category::where('status', 1)->orderBy('position', 'ASC')->limit(10)->get();
@endphp
@php
    $languagesMenu = get_available_languages();
@endphp
@php
    if (auth()->check()) {
        $userRequests = json_encode(auth()->user()->userRequestsPendingApprovalArray() ?? null);
    } else {
        $userRequests = null;
    }
    $codes = get_extention_keys('pusher');
@endphp

<!-- Header -->
<!-- <div class="header-top bg--section">
    <div class="container">
        <div class="header__top__wrapper justify-content-end">
            <ul>
                <li>
                    <span class="name">@lang('Email'): </span><a href="mailto:{{ $header->data_values->email }}" class="text--base">{{ $header->data_values->email }}</a>
                </li>
                <li>
                    <span class="name">@lang('Call Us'): </span><a href="tel:{{ $header->data_values->mobile }}" class="text--base">{{ $header->data_values->mobile }}</a>
                </li>
            </ul>
            <form id="search-form" action="{{ route('product.search') }}" class="search-form">
                <div class="input-group input--group">
                    <input type="text" class="form-control" name="search_key" value="{{ request()->search_key }}"
                           placeholder="@lang('Product Name')">
                    <button type="submit" class="cmn--btn"><i class="las la-search"></i></button>
                </div>
            </form>
        </div>
    </div>
</div> -->

<style>
    .primary-header {
        /* typography/body1 */
        font-family: Lato;
        font-size: 16px;
        font-style: normal;
        font-weight: 400;
        line-height: 150%;
        /* 24px */
        letter-spacing: 0.15px;
        color: rgba(36, 40, 40, 0.87)
    }

    .secondary-header {
        font-feature-settings: 'clig' off, 'liga' off;
        /* typography/body2 */
        font-family: Lato;
        font-size: 14px;
        font-style: normal;
        font-weight: 400;
        line-height: 143%;
        /* 20.02px */
        letter-spacing: 0.17px;
        color: rgba(36, 40, 40, 0.60)
    }

    .chip {
        background: rgba(0, 142, 143, 0.08);
        border-radius: 100px
    }


    .cart-item {
        cursor: pointer;
    }

    .elevation-8 {
        box-shadow: 0px 5px 5px -3px rgba(36, 40, 40, 0.2), 0px 8px 10px 1px rgba(36, 40, 40, 0.14), 0px 3px 14px 2px rgba(36, 40, 40, 0.12);
    }

    .dropdown-menu {
        position: absolute;
        top: 100%;
        left: 0;
        display: none;
        float: left;
        min-width: 10rem;
        padding: 0.5rem 0;
        margin: 0.125rem 0 0;
        font-size: 1rem;
        color: #212529;
        text-align: left;
        list-style: none;
        background-color: #fff;
        border: 1px solid rgba(0, 0, 0, 0.15);
        border-radius: 0.25rem;
    }

    .dropdown-menu-right {
        left: auto;
        right: 0;
    }

    .dropdown-toggle::after {
        display: none;
    }

    .nav-link-color {
        color: var(--nav-links-color) !important;
    }

    .nav-link-color:hover {
        color: var(--nav-hover-color) !important;
    }
</style>
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<div style="background-color: {{ getNavbarBackground() }}; height: 64px" class="header-bottom">
  
    <div class="container">
        <div class="header-wrapper d-flex flex-wrap align-items-center justify-content-between ">
            <div class="logo" style="width: 20%;">
                <a href="{{ getHomePageUrl() }}">
                    <img style="max-height: 50px" id="logo" alt="M Cultivo logo">
                </a>
                @if (config('app.APP_SHOW_MESSAGE', false))
                    <strong>{{ config('app.APP_MESSAGE') }}</strong>
                @endif
            </div>
            <nav style="margin-right: auto; margin-left: auto;" class="navbar navbar-expand-lg navbar-light">
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav mr-auto">
                        @if (getNewAuctionId() && getNewAuctionLabel() && ($event = \App\Models\Event::find(getNewAuctionId())) != null)
                            <li class="nav-item dropdown">
                                <a class="nav-link nav-link-color"
                                    href="{{ route('event.details', [$event->id, slug($event->name)]) }}">{{ getNewAuctionLabel() }}</a>
                            </li>
                        @endif
                        @if (getPastAuctionLabel())

                            <li class="nav-item dropdown">
                                <a class="nav-link nav-link-color dropdown-toggle" href="#"
                                    id="navbarDropdownPastAuctions" role="button" data-toggle="dropdown"
                                    aria-haspopup="true" aria-expanded="false">
                                    {{ getPastAuctionLabel() }}
                                </a>
                                <div style="max-height: 550px; overflow-y: auto; overflow-x: hidden;"
                                    class="dropdown-menu" aria-labelledby="navbarDropdownPastAuctions">
                                    @foreach (\App\Models\Event::whereIn('id', getPastAuctionIds())->get() as $event)
                                        <a class="dropdown-item"
                                            href="{{ route('event.details', [$event->id, slug($event->name)]) }}">{{ $event->name }}</a>
                                    @endforeach
                                </div>
                            </li>
                        @endif

                        @foreach (is_array(getNavbarLinks()) ? getNavbarLinks() : [] as $link)
                            <li class="nav-item dropdown">
                                @if (!($link['is_menu'] ?? null))
                                    <a class="nav-link nav-link-color"
                                        href="{{ $link['with_iframe'] ?? 'off' == 'on' ? route('external', ['link' => $link['url']]) : $link['url'] }}">{{ $link['label'] ?? '' }}</a>
                                @else
                                    <a class="nav-link nav-link-color dropdown-toggle" href="#"
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

                    </ul>
                </div>
            </nav>

            <div class="menu-area">
                <div class="menu-close">
                    <svg width="21" height="21" viewBox="0 0 21 21" fill="none"
                        xmlns="http://www.w3.org/2000/svg">
                        <path
                            d="M20.7087 2.348L18.6524 0.291748L10.5003 8.44383L2.34824 0.291748L0.291992 2.348L8.44408 10.5001L0.291992 18.6522L2.34824 20.7084L10.5003 12.5563L18.6524 20.7084L20.7087 18.6522L12.5566 10.5001L20.7087 2.348Z"
                            fill="black" fill-opacity="0.56" />
                    </svg>
                </div>

                <a class="menu-logo" href="{{ route('home') }}">
                    <img style="max-width: 100px"
                        src="{{ getImage(imagePath()['logoIcon']['path'] . '/m-cultivo.svg') }}" alt="M Cultivo logo">
                </a>

                <ul class="menu gap-2">
                    <!-- <li>
                        <a href="{{ route('home') }}">@lang('HOME')</a>
                    </li> -->
                    <!-- <li>
                        <a href="https://marketplace.mcultivo.com/">@lang('Marketplace')</a>
                    </li> -->
                    <!-- <li class="nav-item dropdown auctions-nav">
                        <a class="nav-link dropdown-toggle" href="{{ route('event.all') }}" id="navbarDropdownMenuLink"
                           data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">@lang('AUCTIONS')</a>
                        <div class="dropdown-menu dropdown-menu-auctions" aria-labelledby="navbarDropdownMenuLink">
                            @foreach ($categories as $category)
@if ($category->id == 8)
<a class="dropdown-item"
                                                           href="{{ route('product.all') }}">{{ $category->name }}</a>
@else
<a class="dropdown-item"
                                                           href="{{ route('category.events', [$category->id]) }}">{{ $category->name }}</a>
@endif
@endforeach
                    </div>
                </li> -->
                    <!--  <li>
                        <a href="{{ route('product.all') }}">@lang('Products')</a>
                    </li>-->
                    <!--  <li>
                        <a href="{{ route('merchants') }}">@lang('Merchants')</a>
                    </li>-->
                    <!-- <li>
                        <a href="#">@lang('Solutions')</a>
                    </li> -->
                    <!--   <li>
                        <a href="{{ route('blog') }}">@lang('Blog')</a>
                    </li>-->
                    <li @if (request()->routeIs('producers')) class="active" @endif>
                        <a href="{{ route('producers') }}">@lang('For Producers')</a>
                    </li>
                    <li @if (request()->routeIs('buyers')) class="active" @endif>
                        <a href="{{ route('buyers') }}">@lang('For Buyers')</a>
                    </li>
                    <li @if (request()->routeIs('about.us')) class="active" @endif>
                        <a href="{{ route('about.us') }}">@lang('About')</a>
                    </li>
                    <li @if (request()->routeIs('contact')) class="active" @endif>
                        <a href="{{ route('contact') }}">@lang('Contact')</a>
                    </li>
                    <li @if (request()->routeIs('blog')) class="active" @endif>
                        <a href="{{ route('blog') }}">@lang('Blog')</a>
                    </li>
                    <!-- <li>
                        <a href="https://help.mcultivo.com/" target="_blank">@lang('Support')</a>
                    </li> -->
                </ul>
                <div class="d-flex d-lg-none mt-4 justify-content-center">
                    <a class="login-link" href="{{ route('user.login') }}"
                        style="padding: 0px;display:flex !important;align-items:center;">@lang('User Login')</a>
                    <!-- <a href="{{ route('merchant.login') }}">@lang('Merchant Login')</a>-->
                </div>
            </div>
            <div class="ms-auto">
                <ul class="header-icons d-none d-lg-flex gap-3">



                    {{-- Lanugage --}}
                    <li class="user-icon" data-bs-toggle="Language" data-bs-placement="bottom"
                        title="@lang('Language')">
                        <div class="custom-dropdown-container">
                            <svg id="customDropdownBtn" width="24" height="24" viewBox="0 0 24 24"
                                fill="none" xmlns="http://www.w3.org/2000/svg" aria-label="Language"
                                class="">
                                <path
                                    d="M12.87 15.07L10.33 12.56L10.36 12.53C12.1 10.59 13.34 8.36 14.07 6H17V4H10V2H8V4H1V5.99H12.17C11.5 7.92 10.44 9.75 9 11.35C8.07 10.32 7.3 9.19 6.69 8H4.69C5.42 9.63 6.42 11.17 7.67 12.56L2.58 17.58L4 19L9 14L12.11 17.11L12.87 15.07ZM18.5 10H16.5L12 22H14L15.12 19H19.87L21 22H23L18.5 10ZM15.88 17L17.5 12.67L19.12 17H15.88Z"
                                    fill="{{ getNavbarNavIcons() }} !important"></path>
                            </svg>
                            {{-- <button class="custom-dropdown-toggle" id="customDropdownBtn">Dropdown</button> --}}
                            <div class="custom-dropdown-menu elevation-8 rounded " style="border:none"
                                id="customDropdownMenu">


                                @foreach ($languagesMenu as $languagesMenu)
                                    <a href="#" data-language-id="{{ $languagesMenu->id }}"
                                        class="custom-dropdown-item ">{{ $languagesMenu->name }}</a>
                                @endforeach
                            </div>
                        </div>
                    </li>
                    {{-- Lanugage --}}

                    {{-- help --}}
                    {{-- <li class="help-icon">
                        <a href="https://mcultivo.tawk.help/" target="_blank" data-bs-toggle="tooltip"
                           data-bs-placement="bottom" title="@lang('Help Center')">
                            @include('templates.basic.svgIcons.question_mark')
                        </a>
                    </li> --}}
                    {{-- help --}}

                    @auth
                        {{-- profile --}}
                        <li class="user-icon" data-bs-toggle="tooltip" data-bs-placement="bottom"
                            title="@lang('User Dashboard')">
                            <a href="{{ route('user.home') }}">
                                @include('templates.basic.svgIcons.user')
                            </a>
                        </li>
                        {{-- profile --}}
                    @endauth

                    {{-- Cart --}}
                    @if (!get_is_stop_cart() ?? false)
                        @include('templates.basic.partials.cart_icon')
                    @endif
                    {{-- Cart --}}

                    {{-- Login --}}
                    @if (!auth()->check() && !auth()->guard('merchant')->check())
                        <li style="display: flex;align-items: center;">
                            <a class="login-link d-none d-lg-block btn" href="{{ route('user.login') }}"
                                data-bs-toggle="tooltip" data-bs-placement="bottom" title="@lang('Log in')"
                                style="padding: 0px;display:flex !important;align-items:center;">
                                @include('templates.basic.svgIcons.user')
                            </a>
                        </li>
                        <!--<a href="{{ route('merchant.login') }}">@lang('Merchant Login')</a>-->
                    @endif
                    {{-- Login --}}

                    @auth('merchant')
                        <a href="{{ route('merchant.dashboard') }}">@lang('Merchant Dashboard')</a>
                    @endauth
                </ul>

                {{-- Lanugage Menu --}}
                <select class="language langSel " style="display: none;">
                    @foreach ($language as $item)
                        <option value="{{ $item->code }}" @if (session('lang') == $item->code) selected @endif>
                            {{ $item->name }}</option>
                    @endforeach
                </select>
                {{-- Lanugage Menu --}}
            </div>
            <div class="header-bar d-lg-none">
                <span></span>
                <span></span>
                <span></span>
            </div>
        </div>
    </div>
    <div id="eventModalContainer"></div>
</div>
<!-- Header -->

@push('script')
    <script>
        function setCartView(carts) {
            $('.cart-icon').html(carts);
        }

        function setItemCount(itemCount) {
            const cartIconContainer = document.getElementById('cartIconContainer');
            const itemCountElement = document.getElementById('cartItemCount');

            itemCountElement.textContent = itemCount;

            if (itemCount > 0) {
                itemCountElement.style.display = 'flex';
            } else {
                itemCountElement.style.display = 'none';
            }
        }

        function getCartItems() {
            $.ajax({
                url: "{{ route('user.getCartView') }}",
                success: function(response) {
                    @if(config('app.env')=='local')
                    console.log(response)
                    @endif
                    setItemCount(response.itemCount);
                    @if(config('app.env')=='local')
                    console.log('set cart items is being called.')
                    @endif
                    //fire set cart items event
                    setCartItems(response.cartItems);

                }
            });
        }

        function addToCart(product_price_id, quantity, is_sample, button) {
            var loader = $(button).find('.loader');
            loader.removeClass('d-none'); // Show loader

            $.ajax({
                url: "{{ route('user.addToCart') }}",
                type: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                    product_price_id,
                    quantity,
                    is_sample
                },
                success: function(response) {
                    iziToast.success({
                        title: "@lang('Added to Cart')",
                        position: "topRight"
                    });
                    setItemCount(response.itemCount);
                    @if(config('app.env')=='local')
                    console.log('set cart items is being called.')
                    @endif
                    //fire set cart items event
                    setCartItems(response.cartItems);

                },
                error: function(error) {
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
                            message: 'Failed to add cart',
                            position: 'topRight'
                        });
                    }
                },
                complete: function() {
                    loader.addClass('d-none'); // Hide loader on completion
                }
            });
        }




        @if ($user_id)
            const userIdCookie = getCookie("user_id");
            if (!userIdCookie) {
                setCookie("user_id", {{ $user_id }}, 365);
            } else if (userIdCookie && +userIdCookie != {{ $user_id }}) {
                setCookie("user_id", {{ $user_id }}, 365);
                deleteCookie("weight_unit");
                deleteCookie("currency");
            }
        @endif
    </script>
@endpush

@push('script')
    <script src="https://js.pusher.com/7.2/pusher.min.js"></script>
    <script>
        $(document).ready(function() {
            var dropdownBtn = $('#customDropdownBtn');
            var dropdownMenu = $('#customDropdownMenu');

            dropdownBtn.on('click', function() {
                dropdownMenu.toggle();
            });

            var navbar = $('.header-bottom')
          if('{{getColorSettingValue('nav_background_color')['is_with_glass_effect']}}') {
              addGlassEffect(rgbaStringToObject('{{getNavbarBackground()}}'),navbar)
            }
            // Close the dropdown if the user clicks outside of it
            $(document).on('click', function(event) {
                if (!$(event.target).closest('.custom-dropdown-container').length) {
                    dropdownMenu.hide();
                }
            });

            $('.custom-dropdown-item').on('click', function(e) {
                e.preventDefault();

                // Extract the language ID from the data attribute
                var languageId = $(this).data('language-id');

                // Make an AJAX request to the update.display.language route
                $.ajax({
                    type: 'POST',
                    url: '{{ route('update.display.language') }}',
                    data: {
                        _token: "{{ csrf_token() }}",
                        language_id: languageId
                    },
                    success: function(response) {},
                    error: function(xhr, status, error) {}
                    // reload the page
                }).done(function() {
                    location.reload();
                });
            });

        });

        function acceptEventTerms(event_id) {
            $.ajax({
                url: '{{ url('user/terms-accept') }}/' + event_id,
                type: 'POST',
                data: {
                    _token: "{{ csrf_token() }}",
                    event_id: event_id, //not used, added any data to prevent csrf token error
                },
                success: function(response) {
                    setTimeout(() => {
                        window.location.reload()
                    }, 500);
                },
                error: function(error) {
                    console.error('Error in AJAX request', error);

                }
            });
        }

        function innerModal(data) {
            if ($('#eventModalContainer .modal').length === 0) {
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
                            @lang('Your access to') "${data.data.event_name}" @lang('auction is approved!')
                </h6>
                <p class="my-4">
@lang('Before you start bidding, please review and accept our') <a style="color: var(--primary-color) !important; text-decoration: underline !important;"  target="_blank" href="../../agreement/${data.data.event_id}">@lang('Bidder Agreement')</a> @lang('and') <a style="color: var(--primary-color) !important; text-decoration: underline !important;"  target="_blank" href="../../page/{{ get_policy_id() }}/terms-and-conditions">@lang('Terms and Conditions')</a>
                        </p>

                            </div>
                            <div class="modal-footer gap-2">
                                <a class="text--btn" style="user-select:none;cursor: pointer;" onclick="$('#eventModalContainer').html('')"  data-bs-dismiss="modal">@lang('Decline')</a>
                            <a style="user-select:none;cursor: pointer;" onclick="acceptEventTerms(${data.data.event_id})"><button style="background-color: var(--button-contained-background) !important" class="btn btn--base cmn--btn">@lang('Accept')</button></a>
                        </div>
                    </div>
                </div>
            </div>
    `);
            }
        }


        (function($) {

            const largeImageUrl =
                "{{ getHomePageLogo() }}"
            const mediumImageUrl =
                "{{ getHomePageLogo('md') }}";
            const smallImageUrl =
                "{{ getHomePageLogo('sm') }}";




            setResponsiveImage(largeImageUrl, mediumImageUrl, smallImageUrl, document.getElementById('logo'),
                true);


            var userRequests = {!! $userRequests ?? 'null' !!};

            var product_event_id = {{ $product->event_id ?? 0 }};

            if (userRequests && userRequests.length > 0) {
                userRequests.forEach(function(eventID) {
                    try {
                        var pusher = new Pusher('{{ $codes['PUSHER_APP_KEY'] }}', {
                            cluster: '{{ $codes['PUSHER_APP_CLUSTER'] }}'
                        });

                        var userId = '{{ auth()->user()->id ?? null }}';
                        if (userId) {
                            var userChannel = pusher.subscribe(`request.${eventID}.user.` + userId);
                            userChannel.bind(`request.${eventID}.user.` + userId, function(data) {
                                var currentUrl = window.location.href;
                                // Split the URL by '/'
                                var parts = currentUrl.split('/');
                                var subpage = parts[parts.length - 3];

                                // Get the ID from the URL
                                var id = parts[parts.length - 2];


                                if (id && (subpage == 'event-details' || subpage ==
                                        'product-details')) {
                                    if (id == data.data.event_id || product_event_id == data.data
                                        .event_id) {
                                        innerModal(data)
                                    }
                                } else {
                                    innerModal(data)
                                }

                            });

                        }
                    } catch (error) {
                        showFatalErrorOverlay(error);
                    }
                });
            }
        })(jQuery);
    </script>
@endpush
