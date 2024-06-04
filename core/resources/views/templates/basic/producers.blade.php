@extends($activeTemplate.'layouts.frontend')

@section('content')

<main class="producers-page">
    <!-- Start Hero section -->
    <section class="producers-hero-section">
        <div class="container position-relative h-100">
            <div class="producers-hero-content">
                <h1>@lang('UNLOCKING POTENTIAL FOR COFFEE PRODUCERS')</h1>
                <p>@lang('We take a partnership approach to create a more equitable coffee supply chain.')</p>
                <a class="producers-link-btn bg-primary-color" href="https://platform.mcultivo.com/login.php" target="_blank">@lang('sign in')</a>
            </div>

            <div class="image-owner-link">
                <span>@lang('Photo'):</span>
                <a href="https://www.kulaproject.org/" target="_blank">@lang("Kula Project")</a>
            </div>
        </div>
    </section>
    <!-- End Hero section -->

    <!-- Start Services section -->
    <section class="producers-services-section">
        <h2>@lang('Our integrated services support coffee production from cherry to final green sale')</h2>

        <nav class="producers-services-nav d-flex align-items-center justify-content-xl-center ms-2 ms-lg-0">
            <a class="m-cultivo-app-link" href="#m-cultivo-app">
                <img src="{{url('/assets/images/frontend/producers/m-cultivo-app-button-hover.png')}}" alt="" />
                <img src="{{url('/assets/images/frontend/producers/m-cultivo-app-button.png')}}" alt="" />
                <i class="service-link-icon">
                    @include("templates.basic.svgIcons.screen")
                </i>

                <div>
                    <h3 class="nav-link-title">CultivoPro</h3>
                    <p class="nav-link-description">
                       @lang('A practical, digital infrastructure for streamlined coffee production')
                    </p>
                </div>
            </a>
            <a class="cultivo-capital-link" href="#cultivo-capital">
                <img src="{{url('/assets/images/frontend/producers/cultivo-capital-button-hover.png')}}" alt="" />
                <img src="{{url('/assets/images/frontend/producers/cultivo-capital-button.png')}}" alt="" />
                <i class="service-link-icon">
                    @include("templates.basic.svgIcons.dollar")
                </i>

                <div>
                    <h3 class="nav-link-title">CultivoCapital</h3>
                    <p class="nav-link-description">
                        @lang('Reliable, flexible capital deployed at strategic points during harvest')
                    </p>
                </div>
            </a>
            <a class="market-access-link" href="#market-access">
                <img src="{{url('/assets/images/frontend/producers/market-access-button-hover.png')}}" alt="" />
                <img src="{{url('/assets/images/frontend/producers/market-access-button.png')}}" alt="" />
                <i class="service-link-icon">
                    @include("templates.basic.svgIcons.market")
                </i>

                <div>
                    <h3 class="nav-link-title">CultivoCommerce</h3>
                    <p class="nav-link-description">
                    @lang('Online auctions connecting producers with high-value buyers')
                    </p>
                </div>
            </a>
        </nav>

        <div id="m-cultivo-app" class="producers-service flex-wrap flex-lg-nowrap">
            <img src="{{url('/assets/images/frontend/producers/smiling-employee-of-the-kula-project.jpg')}}"  alt="Smiling employee of the Cola project" />

            <div class="producers-service-details">
                <div class="producers-service-title d-flex align-items-center gap-3 gap-lg-4">
                    @include("templates.basic.svgIcons.screen")
                    <h2>CultivoPro</h2>
                </div>

                <ul class="producers-service-points">
                    <li class="p-0 d-flex gap-3">
                        <i class="fas fa-check"></i>
                        <p>@lang('Improve consistency and traceability in your harvest processing')</p>
                    </li>
                    <li class="p-0 d-flex gap-3">
                        <i class="fas fa-check"></i>
                        <p>@lang('Save time and money with real-time production data')</p>
                    </li>
                    <li class="p-0 d-flex gap-3">
                        <i class="fas fa-check"></i>
                        <p>@lang('Increase sales to buyers that understand your unique offering')</p>
                    </li>
                </ul>

                <a class="producers-link-btn" href="https://calendar.app.google/FJPGF8FkjG7MMkyw8" target="_blank">
                    @lang('BOOK A DEMO')
                </a>
            </div>
        </div>

        <div id="cultivo-capital" class="producers-service flex-wrap flex-lg-nowrap">
            <img src="{{url('/assets/images/frontend/producers/truck-unloads-a-load-of-coffee.jpg')}}"  alt="A truck unloads a load of coffee" />

            <div class="producers-service-details">
                <div class="producers-service-title d-flex align-items-center gap-3 gap-lg-4">
                    @include("templates.basic.svgIcons.dollar")
                    <h2>CultivoCapital</h2>
                </div>

                <ul class="producers-service-points">
                    <li class="p-0 d-flex gap-3">
                        <i class="fas fa-check primary-color"></i>
                        <p>@lang('Grow beyond your current banking relationships')</p>
                    </li>
                    <li class="p-0 d-flex gap-3">
                        <i class="fas fa-check primary-color"></i>
                        <p>@lang('Buy more coffee at the opportune time')</p>
                    </li>
                    <li class="p-0 d-flex gap-3">
                        <i class="fas fa-check primary-color"></i>
                        <p>@lang('Gain additional time to seek the right buyers')</p>
                    </li>
                </ul>

                <a class="producers-link-btn" href="https://calendar.app.google/FJPGF8FkjG7MMkyw8" target="_blank">
                    @lang('BOOK A CALL')
                </a>
            </div>
        </div>

        <div id="market-access" class="producers-service flex-wrap flex-lg-nowrap">
            <img src="{{url('/assets/images/frontend/producers/someone-pours-coffee.jpg')}}"  alt="Someone pours coffee" />

            <div class="producers-service-details">
                <div class="producers-service-title d-flex align-items-center gap-3 gap-lg-4">
                    @include("templates.basic.svgIcons.market")
                    <h2>CultivoCommerce</h2>
                </div>

                <ul class="producers-service-points">
                    <li class="p-0 d-flex gap-3">
                        <i class="fas fa-check blue-color"></i>
                        <p>@lang('Earn higher prices for your coffee')</p>
                    </li>
                    <li class="p-0 d-flex gap-3">
                        <i class="fas fa-check blue-color"></i>
                        <p>@lang('Discover new long-term buying relationships')</p>
                    </li>
                    <li class="p-0 d-flex gap-3">
                        <i class="fas fa-check blue-color"></i>
                        <p>@lang('Become an internationally recognized brand')</p>
                    </li>
                </ul>

                <a class="producers-link-btn" href="https://calendar.app.google/FJPGF8FkjG7MMkyw8" target="_blank">
                    @lang('SCHEDULE AUCTIONS')
                </a>
            </div>
        </div>
    </section>
    <!-- End Services section -->

    <!-- Start Case Studies section -->
        <section class="producers-case-studies">
            <div class="container">
                <div class="case-studies-carousel owl-theme owl-carousel">
                    <div class="slide-item">
                        <div class="slide-title">
                            <h2>@lang('Case Studies')</h2>
                            <img src="{{url('/assets/images/frontend/producers/la-hermosa-logo.png')}}" alt="La Hermosa logo" />
                        </div>

                        <div class="slide-milestones position-relative d-flex flex-column flex-lg-row  align-items-center align-items-lg-start justify-content-center justify-content-lg-between">
                            <div class="milestone d-flex d-lg-block">
                                <div class="milestone-icon d-flex justify-content-center position-relative">
                                    @include("templates.basic.svgIcons.screen")
                                </div>
                                <div class="milestone-description">
                                    <strong>@lang("SEPTEMBER 2022")</strong>
                                    <h3 class="m-0">
                                        Migration to CultivoPro
                                    </h3>
                                    <p class="mt-3">
                                        La Hermosa migrated off of rudimentary excel spreadsheets and onto CultivoPro's integrated platform with automated reporting.
                                    </p>
                                </div>
                            </div>
                            <div class="milestone d-flex d-lg-block">
                                <div class="milestone-icon d-flex justify-content-center position-relative">
                                    @include("templates.basic.svgIcons.dollar")
                                </div>
                                <div class="milestone-description">
                                    <strong>@lang("OCTOBER 2022")</strong>
                                    <h3 class="m-0">
                                        @lang("Capital Access")
                                    </h3>
                                    <p class="mt-3">
                                        La Hermosa qualified for financing through CultivoCapital, which allowed for more patient sale of their coffee.
                                    </p>
                                </div>
                            </div>
                            <div class="milestone d-flex d-lg-block">
                                <div class="milestone-icon d-flex justify-content-center position-relative">
                                    @include("templates.basic.svgIcons.market")
                                </div>
                                <div class="milestone-description">
                                    <strong>@lang("JUNE 2023")</strong>
                                    <h3 class="m-0">
                                        @lang("Private Auction")
                                    </h3>
                                    <p class="mt-3">
                                        La Hermosa will host their first auction on the CultivoCommerce platform, putting their quality on display to the world.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="slide-item">
                        <div class="slide-title">
                            <h2>@lang("Case Studies")</h2>
                            <img class="long-image" src="{{url('/assets/images/frontend/producers/long-miles-logo.png')}}" alt="Long Miles logo" />
                        </div>

                        <div class="slide-milestones position-relative d-flex flex-column flex-lg-row  align-items-center align-items-lg-start justify-content-center  justify-content-lg-between">
                            <div class="milestone d-flex d-lg-block">
                                <div class="milestone-icon d-flex justify-content-center position-relative">
                                    @include("templates.basic.svgIcons.screen")
                                </div>
                                <div class="milestone-description">
                                    <strong>@lang("NOVEMBER 2020")</strong>
                                    <h3 class="m-0">
                                        Migration to CultivoPro
                                    </h3>
                                    <p class="mt-3">
                                        Long Miles Coffee transitioned from processing 100k+ pen and paper transactions to CultivoPro, expediting farmer payments.
                                    </p>
                                </div>
                            </div>
                            <div class="milestone d-flex d-lg-block">
                                <div class="milestone-icon d-flex justify-content-center position-relative">
                                    @include("templates.basic.svgIcons.dollar")
                                </div>
                                <div class="milestone-description">
                                    <strong>@lang("AUGUST 2022")</strong>
                                    <h3 class="m-0">
                                        @lang("Capital Access")
                                    </h3>
                                    <p class="mt-3">
                                        Long Miles Coffee utilized CultivoCapital to raise and deploy sufficient capital to pay their entire farmer network in less than 2 weeks.
                                    </p>
                                </div>
                            </div>
                            <div class="milestone d-flex d-lg-block">
                                <div class="milestone-icon d-flex justify-content-center position-relative">
                                    @include("templates.basic.svgIcons.market")
                                </div>
                                <div class="milestone-description">
                                    <strong>@lang("DECEMBER 2022")</strong>
                                    <h3 class="m-0">
                                        @lang("Private Auction")
                                    </h3>
                                    <p class="mt-3">
                                        Long Miles Coffee hosted their first auction on the CultivoCommerce platform, resulting in multiple new buyer relationships and $50k in revenue.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="slide-item">
                        <div class="slide-title">
                            <h2>Case Studies</h2>
                            <img src="{{url('/assets/images/frontend/producers/los-pirineos-coffee.png')}}" alt="Los Pirineos coffee logo" />
                        </div>

                        <div class="slide-milestones position-relative d-flex flex-column flex-lg-row  align-items-center align-items-lg-start justify-content-center  justify-content-lg-between">
                            <div class="milestone d-flex d-lg-block">
                                <div class="milestone-icon d-flex justify-content-center position-relative">
                                    @include("templates.basic.svgIcons.screen")
                                </div>
                                <div class="milestone-description">
                                    <strong>@lang("OCTOBER 2022")</strong>
                                    <h3 class="m-0">
                                        Migration to CultivoPro
                                    </h3>
                                    <p class="mt-3">
                                        Los Pirineos started using the CultivoPro app to process, tag, and track their high-end lots.
                                    </p>
                                </div>
                            </div>
                            <div class="milestone d-flex d-lg-block">
                                <div class="milestone-icon d-flex justify-content-center position-relative">
                                    @include("templates.basic.svgIcons.dollar")
                                </div>
                                <div class="milestone-description">
                                    <strong>@lang("FEBRUARY 2023")</strong>
                                    <h3 class="m-0">
                                        @lang("Capital Access")
                                    </h3>
                                    <p class="mt-3">
                                        Los Pirineos leveraged CultivoCapital's financing option to quickly boost their production mid-way through harvest.
                                    </p>
                                </div>
                            </div>
                            <div class="milestone d-flex d-lg-block">
                                <div class="milestone-icon d-flex justify-content-center position-relative">
                                    @include("templates.basic.svgIcons.market")
                                </div>
                                <div class="milestone-description">
                                    <strong>@lang("JULY 2023")</strong>
                                    <h3 class="m-0">
                                        @lang("Private Auction")
                                    </h3>
                                    <p class="mt-3">
                                        Los Pirineos is set to host their first private auction on the CultivoCommerce platform, promoting to a global network of buyers.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="text-center">
                    <button class="next-slide-btn">
                        <span class="next-slide-btn-title">@lang("Long Miles Coffee")</span>
                        <i class="fas fa-arrow-right ms-2"></i>
                    </button>
                </div>
            </div>
        </section>
    <!-- End Case Studies section -->
</main>

@endsection

@push('script')
<script>
    const owl = $('.case-studies-carousel');
    owl.owlCarousel({
      loop: false,
      nav: false,
      dots: true,
      items: 1,
      autoplay: false,
      margin: 0,
      mouseDrag: false,
      rewind: true,
    })

    const TITLES = ["Long Miles Coffee","Los Pirineos","La Hermosa"];

    function updateCarouselNextButtonTitle(carouselElem,index) {
        $(".next-slide-btn-title").text(TITLES[index]);
        carouselElem.trigger('next.owl.carousel');
    }

    let currentTitleIndex = 0;

    const carouselDotElems = $('.case-studies-carousel .owl-dots .owl-dot');
    carouselDotElems.each(function(index) {
        $(this).on('click',function() {
            currentTitleIndex = index;
            updateCarouselNextButtonTitle(owl,index);
        })
    })

    $('.next-slide-btn').click(function() {
        ++currentTitleIndex;
        if(currentTitleIndex > 2) currentTitleIndex = 0;
        updateCarouselNextButtonTitle(owl,currentTitleIndex);
    })
</script>
@endpush
