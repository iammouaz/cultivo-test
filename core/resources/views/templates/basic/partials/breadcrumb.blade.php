@php

    $breadcrumb = getContent('breadcrumb.content', true);
@endphp

<section class="hero-section inner-hero" style="background: url({{ getImage('assets/images/frontend/breadcrumb/'.$breadcrumb->data_values->background_image, '1920x240') }})center;background-size: cover;">
    <div class="container">
        <div class="hero-content text-center">

            @if (Illuminate\Support\Facades\Route::is('product.all'))
                <div class="banner__content">
                    <h3 class="banner__title text-white">{{ $pageTitle }}</h3>
                    <p class="text-white">
                        @lang("M-Cultivo’s general Marketplace features coffees for sale in a traditional e-commerce platform, no bidding or registration needed. Our goal is to bring more high-quality coffees to more buyers, while offering an approachable way for roasters of any size or experience to participate in discovering incredible coffees with high standards for traceability and financial transparency. You can find more information on how the Marketplace operates")
                        <a class="text-primary" href="{{ route('about.us')}}">@lang("here")</a> . </p>
                </div>
            @elseif(Illuminate\Support\Facades\Route::is('event.all'))
                <div class="banner__content">
                    <h3 class="banner__title text-white">{{ $pageTitle }}</h3>
                    <p class="text-white">@lang("M-Cultivo offers updated, streamlined experiences for the world's premier specialty coffee auctions. These auctions offer buyers access to the best coffees in the world, vetted by the most rigorous and trusted evaluation processes. Below you'll find upcoming auctions from Cup of Excellence and the Alliance for Coffee Excellence, as well as Private Auctions from producers. For more information about the registration, bidding, and post-auction process, please see our FAQ.")</p>
                </div>
            @else
            <h3 class="hero-title text--base">{{ $pageTitle }}</h3>
            @endif
        </div>
    </div>
</section>
<!-- Hero -->
