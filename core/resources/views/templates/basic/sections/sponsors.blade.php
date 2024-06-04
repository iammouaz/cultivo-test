@php
    $sponsors = getContent('sponsors.element');
@endphp
<section class="sponsors-section">
    <div class="container">
        <div class="partner-slider owl-theme owl-carousel">
            @foreach ($sponsors as $sponsor)     
                <div class="partner-thumb">
                    <img src="{{ getImage('assets/images/frontend/sponsors/'.$sponsor->data_values->image, '350x150') }}" alt="partner">
                    <img src="{{ getImage('assets/images/frontend/sponsors/'.$sponsor->data_values->image, '350x150') }}" alt="partner">
                </div>
            @endforeach
        </div>
    </div>
</section>

@push('script')
$(document).ready(function() {
    $('.partner-slider').owlCarousel({
      loop: true,
      nav: false,
      dots: false,
      items: 2,
      autoplay: true,
      margin: 15,
      responsive: {
        768: {
          items: 3,
          margin: 30,
        },
        992: {
          items: 4,
        },
        1200: {
          items: 6,
        }
      }
    })
})
@endpush