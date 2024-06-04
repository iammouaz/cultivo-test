@extends($activeTemplate . 'layouts.frontend_commerce')
@php
    $product = $offer;
    $event = $offerSheet;
    $relatedProducts = $relatedOffers;
    $pricePerUoM = null;
    $priceTotalWithPackaging = null;
    $priceObj = $product->prices()->orderBy('price', 'asc')->first();
    if ($priceObj) {
        $pricePerUoM = $priceObj->price;
        $priceTotalWithPackaging = $priceObj->product_total_price;
    }
@endphp


@push('style')
    <link rel="stylesheet" href="{{ asset($activeTemplateTrue . 'css_commerce/home.css') }}">
@endpush

@section('content')
    <!-- Product details -->
    <section id="product-details" class="product-section">

        <div class="container pt-5 pb-5">

            <div class="row gy-md-5 justify-content-between">
                <div class="mx-md-auto mx-lg-0 col-md-12 col-xl-12 col-xxl-12">

                    <span class="text-secondary"> Offerings / </span>
                    <a href="{{ route('offer_sheet.activeOffers', [$product->offerSheet->offer_sheet_url]) }}">
                        <span class="primary">{{ __($product->name) }}</span>
                    </a>

                    <div class="product__single-item p-4">
                        <div class="product-thumb-area d-flex row flex-wrap flex-xl-nowrap">
                            <div class="product-thumb mb-md-5 mb-xl-0 col-md-6 col-xl-6 col-xxl-6">
                                <img id="mainImage" src="" alt="product">


                                {{-- <div class="image-gallery">
                                    @foreach ($images as $image)
                                        <img class="gallery-image"src={{ $image['image'] }} alt="product">
                                    @endforeach

                                </div> --}}
                            </div>

                            <div class="product-content col-md-6 col-xl-6 col-xxl-6">
                                <h5 class="title heading-3 mt-0 mb-0 text-capitalize">{{ __($product->name) }}</h5>
                                <div class="d-flex gap-3 pt-2 align-items-center">

                                    <span id="total_price">USD${{ round($priceTotalWithPackaging, 2) }}</span>

                                    <span style="opacity: 0.8;"
                                        id="total_price_uom">USD${{ round($pricePerUoM, 2) }}/lb</span>
                                </div>
                                <h3
                                    class="paragraph-level-1 paragraph-level-2 mb-3 mb-lg-4 pb-3 mt-0 fw-normal orgianl-auction-title">
                                    {{ __($product->short_description) }}
                                </h3>
                                <div class="product-details-price-container">
                                    <div class="form-floating mb-3">
                                        <select id="weight-select-product" class="form-select"
                                            aria-label="weight-select-product">
                                            <option value="" disabled selected>@lang('Select size')</option>
                                            @foreach ($product->prices as $price)
                                                @if ($price->size)
                                                    <option value="{{ $price->id }}">
                                                        {{ $price->size->size . ' - ' . $price->size->weight_LB . '  lb' ?? null }}
                                                    </option>
                                                @endif
                                            @endforeach
                                        </select>

                                        <label for="weight-select-product">@lang('Size')</label>
                                    </div>
                                    <div class="input-spinner-container py-2">

                                        <input id="quantity-input" class="input-spinner" type="number" value="1"
                                            min="1" max="100" step="1" />
                                    </div>

                                    <div class="div-bid-now py-3" id="action_button_container">
                                        @if ($product->offerSheet->show_add_order_button !== 0)
                                            <a onclick="addToCartProduct(0,this)" id="addToOrderBtn"
                                                style="border-radius: 16px;" class="primary-filled-btn w-100 my-1">
                                                @lang('add to order')
                                            </a>
                                        @endif
                                        @if ($product->offerSheet->show_add_sample_button !== 0)
                                            <a onclick="addToCartProduct(1,this)" id="addSampleBtn"
                                                style="border-radius: 16px;" class="primary-outline-btn w-100 my-1">
                                                @lang('add sample')
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row gy-md-5">
                <div class="mx-md-auto mx-lg-0 col-md-7 col-xl-8 col-xxl-7">
                    <div class="product__single-tabs content paragraph-level-1">
                        <ul class="nav nav-tabs nav--tabs flex-nowrap flex-md-wrap gap-2 gap-sm-5 border-bottom">
                            <li>
                                <a href="#details" class="active text-secondary text-uppercase"
                                    data-bs-toggle="tab">@lang('Details')</a>
                            </li>

                            <li>
                                <a href="#story" class="text-secondary text-uppercase"
                                    data-bs-toggle="tab">@lang('Story')</a>
                            </li>
                        </ul>
                        <div id="tabs-content" class="tab-content">
                            <div class="tab-pane fade show active" id="details">
                                @foreach ($product->offer_specification as $spec)
                                    <div class="details-row border-bottom">
                                        <span class="p-3 item-title">{{ __($spec->spec_key) }}</span>
                                        <span class="item-details">{{ $spec->Value }}</span>
                                    </div>
                                @endforeach
                            </div>

                            <div class="tab-pane fade" id="story">
                                @php
                                    echo $product->long_description;
                                @endphp
                            </div>



                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>
@endsection

@push('style')
    <style>
        /* Chrome, Safari, Edge, Opera */
        input::-webkit-outer-spin-button,
        input::-webkit-inner-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }

        /* Firefox */
        input[type=number] {
            -moz-appearance: textfield;
        }
    </style>
@endpush

@push('script')
    <script src="{{ asset('assets/global/js/product-details.js') }}"></script>
    <script>
        const available_prices = @json($product->prices);

        $(document).ready(function() {


            const largeImageUrl =
                "{{ getImage(imagePath()['product']['path'] . '/' . $product->photo, imagePath()['product']['size'], false) }}"
            const mediumImageUrl =
                "{{ getImage(imagePath()['product']['path'] . '/' . $product->photo, imagePath()['product']['size'], false, 'md') }}";
            const smallImageUrl =
                "{{ getImage(imagePath()['product']['path'] . '/' . $product->photo, imagePath()['product']['size'], false, 'sm') }}";




            setResponsiveImage(largeImageUrl, mediumImageUrl, smallImageUrl, document.getElementById('mainImage'),
                true);




            $('.gallery-image').on('click', function() {
                var newSrc = $(this).attr('src');
                $('#mainImage').attr('src', newSrc);
            });
        });



        function addToCartProduct(isSample, button) {
            var productPriceId = document.getElementById('weight-select-product').value;
            var quantity = document.getElementById('quantity-input').value;
            addToCart(productPriceId, quantity, isSample, button)
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
                    setCartView(response.cartView);
                    setItemCount(response.itemCount);
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
                            message: "@lang('Failed to add cart')",
                            position: 'topRight'
                        });
                    }
                },
                complete: function() {
                    loader.addClass('d-none'); // Hide loader on completion
                }
            });
        }



        const handleChange = (select) => {
            var selectedValue = select.value;

            var is_sample = available_prices.find(item => Number(item.id) === Number(selectedValue))?.size.is_sample;

            var buttonContent = '';



            // $("#action_button_container").html(buttonContent);
        }
    </script>
@endpush
@push('script')
    <script>
        function convert_to_dec(selectObject) {
            var numb = selectObject.value;
            var number_pars = parseFloat(numb) || 0;
            var number_converted = number_pars.toFixed(2);
            selectObject.value = number_converted;
        }
    </script>
    <script>
        window.addEventListener("pageshow", function(event) {
            var historyTraversal = event.persisted ||
                (typeof window.performance != "undefined" &&
                    window.performance.navigation.type === 2);
            if (historyTraversal) {
                window.location.reload();
            }
        });

        // $(document).ready(function() {
        //     $('.owl-carousel').owlCarousel({
        //         loop: false,
        //         dots: true,
        //         checkVisible: false,
        //         items: 1,
        //         autoplay: true,
        //         margin: 0,
        //         responsive: {
        //             576: {
        //                 items: 1,
        //             },
        //             1200: {
        //                 items: 2,
        //             }
        //         }
        //     })
        //
        // });
    </script>
@endpush
