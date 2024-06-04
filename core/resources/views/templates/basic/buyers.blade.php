@extends($activeTemplate.'layouts.frontend')

@php
    $banner = getContent('banner.content', true);
    $features = getContent('feature.element');
@endphp

@section('content')

<!-- Start - old Hero section -->
<!-- <section class="banner-section bg--overlay bg_img" data-background="{{ getImage('assets/images/frontend/banner/'.$banner->data_values->background_image, '1920x1280') }}"> -->

<!-- <section class="banner-section bg--overlay bg_img">
	<div class="banner__inner">
		<div class="container">
			<div class="banner__content">
				<h2 class="banner__title">
					<span>{{ $banner->data_values->heading }}</span>
				</h2>
				<p class="banner__content-txt">{{ $banner->data_values->subheading }}</p>
				<div class="btn__grp">
					<a href="{{ $banner->data_values->button_url }}" class="cmn--btn">{{ $banner->data_values->button }}</a>
					<a href="{{ $banner->data_values->link_url }}" class="cmn--btn active">{{ $banner->data_values->link }}</a>
				</div>
			</div>
		</div>
	</div>
</section> -->
<!-- End - old Hero section -->

<section class="buyers-carousel owl-theme owl-carousel">
        <div class="slide-item">
           <img src="{{ getImage('assets/images/frontend/banner/coffee-tree.jpg') }}" alt="best coffee trees" />

           <div class="buyers-slide-text">
               <h1 class="buyers-slide-title text-uppercase">
                    <!-- {{ $banner->data_values->heading }} -->
                    @lang("DISCOVER THE BEST COFFEE PRODUCERS IN THE WORLD")
                </h1>
                <p class="buyers-slide-description">
                    <!-- {{ $banner->data_values->subheading }} -->
                    @lang("A streamlined approach to purchasing coffee from new, direct relationships")
                </p>
           </div>
        </div>

        <!-- <div class="slide-item">
           <img src="{{ getImage('assets/images/frontend/banner/ethiopia-select-coffees.jpg') }}" alt="A woman selects the best coffee beans" />

           <div class="buyers-slide-text">
               <h1 class="buyers-slide-title text-uppercase">
                    ETHIOPIA SELECT COFFEES
                </h1>
                <p class="buyers-slide-description">
                    Micro-lots curated by the Cup of Excellence Ethiopia National Jury will mirror several features that accompany COE winning lots including: farm information, sensory & physical analysis, and storage in a bonded warehouse to assure quality.
                </p>
           </div>
        </div> -->
</section>

@if($sections->secs ?? null)
    @foreach(json_decode($sections->secs) as $sec)
        @include($activeTemplate.'sections.'.$sec)
    @endforeach
@endif


<section class="additional-support-section">
    <div class="container">
        <div class="d-flex align-items-center justify-content-between flex-wrap flex-xl-nowrap">
            <div class="mb-4 mb-xl-0">
                <h2>
                   @lang('NEED ADDITIONAL SOURCING SUPPORT?')
                </h2>
                <p>@lang('Access our network of coffee producers and establish long-term relationships')</p>
            </div>
            <div class="flex-shrink-0">
                <a href="https://calendar.app.google/Nk6UFCR5RCqezzg8A" target="_blank">@lang('BOOK A CALL')</a>
            </div>
        </div>
    </div>
</section>

@endsection

<!-- <section class="feature-section pb-60 ">
    <div class="container">
        <div class="feature__wrapper">
            <div class="row g-4">
                @foreach ($features as $feature)
                <div class="col-lg-3 col-sm-6">
                    <div class="feature__item bg--section">
                        <div class="feature__item-icon">
                           @php
                               echo $feature->data_values->feature_icon
                           @endphp
                        </div>
                        <h6 class="feature__item-title">{{ $feature->data_values->title }}</h6>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</section> -->

@push('script')
<script>
$('.buyers-carousel').owlCarousel({
    loop: false,
    nav: false,
    dots: false,
    items: 1,
    autoplay: false,
    margin: 0,
})
</script>
@endpush
