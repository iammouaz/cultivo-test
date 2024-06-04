@extends($activeTemplate.'layouts.frontend')

@section('content')

<section class="home-page d-none d-md-block">
        <div class="layer1">
            <div class="layer1-buyers">
                <img src="{{url('/assets/images/frontend/home/hands-holding-green-coffee.png')}}" alt="Hands holding green coffee" />
            </div>
            <div class="layer1-producers">
                <img src="{{url('/assets/images/frontend/home/hands-holding-coffee-cherries.png')}}" alt="Hands holding coffee cherries" />
            </div>
        </div>

        <div class="layer2">
            <div class="layer2-producers">
                <img class="bounce-in-top" src="{{url('/assets/images/frontend/home/hands-holding-coffee-cherries.png')}}" alt="Hands holding coffee cherries" />
            </div>
            <div class="layer2-buyers">
                <img class="bounce-in-bottom" src="{{url('/assets/images/frontend/home/hands-holding-green-coffee.png')}}" alt="Hands holding green coffee" />
            </div>
        </div>

        <div class="layer3">
            <div class="container position-relative h-100">
                <div class="layer3-buyers">
                   @lang('Access quality coffees from around the world')
                </div>
                <div class="layer3-producers">
                    @lang("Technology to unlock coffee's potential")
                </div>
            </div>
        </div>

        <div class="layer4">
            <div class="container position-relative h-100">
                <a class="layer4-producers d-none d-md-block bounce-in-top2" href="/producers" onmouseenter="showProducersSection()" onmouseleave="hideProducersSection()">
                    <p>
                        <span>@lang('for producers')</span>
                        <span class="go-to-link">
                            <span>@lang('learn more')</span>
                            <i class="fas fa-angle-right"></i>
                            <i class="fas fa-angle-right d-none d-lg-inline"></i>
                            <i class="fas fa-angle-right d-none d-lg-inline"></i>
                        </span>
                    </p>
                </a>

                <a class="layer4-buyers d-none d-md-block bounce-in-bottom2" href="/buyers" onmouseenter="showBuyersSection()" onmouseleave="hideBuyersSection()">
                    <p>
                        <span>@lang('for buyers')</span>
                        <span class="go-to-link">
                            <span>@lang('JOIN AUCTIONS')</span>
                            <i class="fas fa-angle-right"></i>
                            <i class="fas fa-angle-right d-none d-lg-inline"></i>
                            <i class="fas fa-angle-right d-none d-lg-inline"></i>
                        </span>
                    </p>
                </a>

                <!-- notification -->
                <!-- <a href="/buyers" class="live-auction-notification d-none d-md-flex">
                    <div class="notification-icon">
                        <img src="{{url('/assets/templates/basic/css/icons/white-gavel.svg')}}" alt="gavel icon" />
                    </div>
                    
                    <div class="notification-text">
                        <p>Join Auctions</p>
                        <p>View all events</p>
                    </div>
                </a> -->
            </div>
        </div>
</section>

<section class="home-page-mobile d-md-none">
    <div class="mobile-producers">
        <a class="mobile-producers-link bounce-in-l" href="/producers">
            <img src="{{url('/assets/images/frontend/home/hands-holding-coffee-cherries-mobile.png')}}" alt="Hands holding coffee cherries" />
            <p>
                <span>@lang('for producers')</span>
                <span class="go-to-link">
                    <span class="me-3">@lang('learn more')</span>
                    <i class="fas fa-angle-right"></i>
                </span>
            </p>
        </a>
    </div>
    <div class="mobile-buyers position-relative">
        <a class="mobile-buyers-link bounce-in-r" href="/buyers">
            <img src="{{url('/assets/images/frontend/home/hands-holding-green-coffee-mobile.png')}}" alt="Hands holding green coffee" />
            <p>
                <span>@lang('for buyers')</span>
                <span class="go-to-link">
                    <span class="me-3">@lang('JOIN AUCTIONS')</span>
                    <i class="fas fa-angle-right"></i>
                </span>
            </p>
        </a>
    </div>
</section>

@endsection

@push('script')
<script>
    function addClassToElement(selector,className) {
        const element = document.querySelector(selector);
        element.classList.add(className);
    }

    function removeClassFromElement(selector,className) {
        const element = document.querySelector(selector);
        element.classList.remove(className);
    }

    function showProducersSection() {
        // addClassToElement(".live-auction-notification","hide-element");
        addClassToElement(".layer4-buyers","hide-element");
        addClassToElement(".layer3-producers","show-element");
        addClassToElement(".layer2-buyers","hide-element");
    }

    function hideProducersSection() {
        // removeClassFromElement(".live-auction-notification","hide-element");
        removeClassFromElement(".layer4-buyers","hide-element");
        removeClassFromElement(".layer3-producers","show-element");
        removeClassFromElement(".layer2-buyers","hide-element");
    }

    function showBuyersSection() {
        addClassToElement(".layer4-producers","hide-element");
        addClassToElement(".layer3-buyers","show-element");
        addClassToElement(".layer2-producers","hide-element");
    }

    function hideBuyersSection() {
        removeClassFromElement(".layer4-producers","hide-element");
        removeClassFromElement(".layer3-buyers","show-element");
        removeClassFromElement(".layer2-producers","hide-element");
    }

    // $(document).ready(function(){
    // $(".live-auction-notification").addClass("bounce-in-right");
    // })
</script>
@endpush