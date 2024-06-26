@php
    $testimonial = getContent('testimonial.content', true);
    $testimonials = getContent('testimonial.element');
@endphp

<section class="clients-section pt-120 pb-120 bg--section">
    <div class="container">
        <div class="section__header">
            <h3 class="section__title">{{ $testimonial->data_values->heading }}</h3>
            <p class="section__txt">
                {{ $testimonial->data_values->subheading }}
            </p>
            <div class="progress progress--bar">
                <div class="progress-bar bg--base progress-bar-striped progress-bar-animated"></div>
            </div>
        </div>
        <div class="clients__slider owl-theme owl-carousel">
            @foreach ($testimonials as $testimonial)
                <div class="client__item">
                    <div class="client__thumb">
                        <img src="{{ getImage('assets/images/frontend/testimonial/'.$testimonial->data_values->user_image, '120x120') }}" alt="winner">
                    </div>
                    <div class="client__content bg--body">
                        <div class="client__content-header">
                            <div>
                                <h6 class="client__title">{{ $testimonial->data_values->name }}</h6>
                                <span class="info text--base">{{ $testimonial->data_values->designation }}</span>
                            </div>
                            <div class="rating">
                                @for($i=0; $i<$testimonial->data_values->star; $i++)
                                    <span><i class="las la-star"></i></span>
                                @endfor
                            </div>
                        </div>
                        <p class="quote">
                            {{ $testimonial->data_values->description }}
                        </p>
                    </div>
                </div>
            @endforeach

        </div>
    </div>
</section>

@push('script')
<script>
    $(document).ready(function() {
        $(".clients__slider").owlCarousel({
        loop: true,
        margin: 30,
        responsiveClass:true,
        nav:false,
        dots: true,
        smartSpeed: 1000,
        responsive:{
            0:{
            items:1
            },
            768:{
            items:2
            },
        }
        })
    });
</script>
@endpush