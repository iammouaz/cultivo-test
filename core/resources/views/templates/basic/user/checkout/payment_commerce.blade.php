@extends($activeTemplate . 'layouts.frontend_commerce')
@php
    $sampleSet = $order->sampleSets()->first()??null;
    if ($sampleSet) {
        $sampleSet->quantity = $sampleSet->pivot->quantity;
    }
@endphp
@section('content')
    <style>
        .inner-hero {
            display: none
        }

        .bg--body {
            background-image: none !important
        }

        .bg--body:before {
            background-color: transparent !important
        }

        .jp-card-container {
            width: auto
        }

        .card-input {
            border: 1px solid rgba(0, 0, 0, 0.23);
            color: rgba(36, 40, 40, 0.6);
            padding: 16px;
            border-radius: 4px;
        }
    </style>
    <section id="payment-page" style="min-height: calc(100vh - 140px);padding-block:60px">
        <div class="container">
            <div class="row">
                <div class="col-lg-6">
                    <div class="container checkout-custom-page">
                        <!-- Start Main -->
                        <main class="row align-items-start">
                            <h1 style="margin-bottom: 60px">Payment</h1>

                            <!-- Start Pay Now -->
                            <section class="col-xl-9 pay-now">

                                <div class="cmn--card bg--body card-deposit">

                                    <div class="card-body-deposit">
                                        <div class="card-wrapper"></div>

                                        <div id="card-element">
                                        </div>
                                        <div class="row mt-4">
                                            <div class="col-12">
                                                <div id="card-number"></div>
                                            </div>
                                        </div>
                                        <div class="row mt-3">
                                            <div class="col-6">
                                                <div id="cardExpiry"></div>
                                            </div>
                                            <div class="col-6">
                                                <div id="cc"></div>
                                            </div>
                                        </div>

                                        <div id="payment-error">
                                        </div>

                                        <button class="pay-now-btn btn btn--base w-100 btn-md btn-block text-center mt-4"
                                            onclick="pay_with_js()">@lang('PAY NOW')</button>
                                        {{--                                        submit action is checkOutController@pay --}}
                                        <form role="form" id="payment-form" method="POST" hidden
                                            action="{{$sampleSet? route('sample_set_order.payPost', ['order_id' => $order->id]):route('order.payPost', ['order_id' => $order->id]) }}">
                                            @csrf
                                            <input type="hidden" name="strip_token" id="strip_token">
                                            <input type="hidden" name="order_id" id="order_id"
                                                value="{{ $order->id }}">
                                        </form>
                                    </div>
                                </div>

                            </section>
                            <!-- End Pay Now -->


                        </main>
                        <!-- End Main -->
                    </div>

                </div>

                <div class="col-lg-6">
                    <table class="w-100">
                        <tbody>
                            <tr>
                                <td class="text-left">
                                    <h4 style="margin:0px">Products</h4>
                                </td>
                            </tr>
                            @if($sampleSet)
                                <tr>
                                    <td>
                                        <table class="w-100" style="border-collapse: separate;border-spacing: 0 30px;">
                                            <tbody>

                                            <tr>
                                                <td>
                                                    <img style="object-fit: cover;border-radius:12px"
                                                         src="{{ getImage(imagePath()['product']['path'] . '/' . $sampleSet->image, imagePath()['product']['size'], false, 'sm') }}"
                                                         width="178px" height="178px" />
                                                </td>
                                                <td class="w-100" style="vertical-align: baseline;padding-left:20px">
                                                    <table>
                                                        <tbody>
                                                        <tr>
                                                            <td class="text-left info-paragraph"
                                                                style="color:rgba(36, 40, 40, 0.6)">
                                                                    [Sample Set]

                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td class="text-left info-paragraph"
                                                                style="font-weight: 600">{{ $sampleSet->event->name .' Sample Set' }}
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td class="text-left info-paragraph"
                                                                style="padding-top: 20px">
                                                                {{ 'Box: '. $sampleSet->number_of_samples_per_box .' Samples' }} </td>
                                                        </tr>
                                                        <tr>
                                                            <td class="text-left info-paragraph"
                                                                style="padding-top: 20px">
                                                                {{  $sampleSet->weight_per_sample_grams .' each' }} </td>
                                                        </tr>
                                                        <tr>
                                                            <td class="text-left info-paragraph">
                                                                Quantity: {{ $sampleSet->quantity }}
                                                            </td>
                                                        </tr>
                                                        </tbody>
                                                    </table>
                                                </td>
                                                <td class="info-paragraph"
                                                    style="font-weight: 600;vertical-align:baseline">
                                                    USD${{  number_format($sampleSet->price, 2) }}
                                                </td>
                                            </tr>
                                            </tbody>
                                        </table>
                                    </td>
                                </tr>
                            @endif
                            @foreach ($order->prices as $price)
                                <tr>
                                    <td>
                                        <table class="w-100" style="border-collapse: separate;border-spacing: 0 30px;">
                                            <tbody>

                                                <tr>
                                                    <td>
                                                        <img style="object-fit: cover;border-radius:12px"
                                                            src="{{ getImage(imagePath()['product']['path'] . '/' . $price->offer->photo, imagePath()['product']['size'], false, 'sm') }}"
                                                            width="178px" height="178px" />
                                                    </td>
                                                    <td class="w-100" style="vertical-align: baseline;padding-left:20px">
                                                        <table>
                                                            <tbody>
                                                                <tr>
                                                                    <td class="text-left info-paragraph"
                                                                        style="color:rgba(36, 40, 40, 0.6)">
                                                                        @if ($price->size->is_sample)
                                                                            [Sample Request]
                                                                        @else
                                                                            [Product]
                                                                        @endif
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td class="text-left info-paragraph"
                                                                        style="font-weight: 600">{{ $price->offer->name }}
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td class="text-left info-paragraph"
                                                                        style="padding-top: 20px">
                                                                        Size:
                                                                        {{ $price->size->weight_LB }}lb</td>
                                                                </tr>
                                                                <tr>
                                                                    <td class="text-left info-paragraph">
                                                                        Quantity: {{ $price->pivot->quantity }}
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td class="text-left info-paragraph">
                                                                        Price/Lb:
                                                                        USD${{ $price->price }}/lb
                                                                    </td>
                                                                </tr>
                                                            </tbody>
                                                        </table>
                                                    </td>
                                                    <td class="info-paragraph"
                                                        style="font-weight: 600;vertical-align:baseline">
                                                        USD${{ $price->product_total_price }}
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </td>
                                </tr>
                            @endforeach



                        </tbody>
                    </table>

                    <hr style="margin-block:25px" />


                    <table class="w-100" style="margin-top: 10px">
                        <tbody>
                            <tr>
                                <td class="text-left info-paragraph" style="padding-bottom: 5px">
                                    Subtotal
                                </td>
                                <td class="text-right info-paragraph">
                                    ${{ $order->total_price - $order->shipping_price }}
                                </td>
                            </tr>


                        @if(!$allow_product_payment)
                        <tr>
                            <td class="text-left info-paragraph" style="padding-bottom: 12px">
                                Shipping and Handling (Sample)
                            </td>
                            <td class="text-right info-paragraph" style="padding-bottom: 12px">
                                ${{ number_format($order->shipping_price_sample,2) }}
                            </td>
                        </tr>
                            <tr>
                                <td class="text-left info-paragraph" style="padding-bottom: 12px">
                                    pending Payment
                                </td>
                                <td class="text-right info-paragraph" style="padding-bottom: 12px">
                                    ${{ number_format($order->total_price_product,2) }}

                                </td>
                            </tr>
                        @else
                            <tr>
                                <td class="text-left info-paragraph" style="padding-bottom: 12px">
                                    Shipping and Handling
                                </td>
                                <td class="text-right info-paragraph" style="padding-bottom: 12px">
                                    ${{ $order->shipping_price }}
                                </td>
                            </tr>
                        @endif

                            <tr>
                                <td class="text-left info-paragraph" style="font-weight: 600 !important">
                                    Grand Total (USD)
                                </td>
                                <td class="text-right info-paragraph" style="font-weight: 600 !important">
                                    {{-- ${{ $total_price }} --}}
                                    @if ($allow_product_payment)
                                        ${{ number_format($total_price,2) }}

                                    @else
                                        ${{number_format($order->total_price_sample,2)}}

                                    @endif
                                </td>
                            </tr>



                        </tbody>
                    </table>


                </div>


            </div>

        </div>


    </section>
@endsection


@push('script')
    <script src="{{ asset('assets/global/js/card.js') }}"></script>

    <script>
        (function($) {
            "use strict";
            var card = new Card({
                form: '.ElementsApp',
                container: '.card-wrapper',
                formSelectors: {
                    numberInput: 'input[name="cardnumber"]',
                    expiryInput: 'input[name="exp-date"]',
                    cvcInput: 'input[name="cvc"]',
                    nameInput: 'input[name="name"]'
                },
                hideHolderName: true,
            });
        })(jQuery);
    </script>

    <script src="https://js.stripe.com/v3/"></script>

    <script>
        const stripe = Stripe('{{ config('app.STRIPE_PUBLISHABLE_KEY') }}');
        var cardElement;



        document.addEventListener('DOMContentLoaded', (event) => {
            // debugger;

            var elements = stripe.elements({

                clientSecret: '{{ $intent->client_secret }}',

            });

            cardElement = elements.create('card', {
                hidePostalCode: true,
                style: {
                    base: {

                        backgroundColor: "#F9F9F9",
                        iconColor: '#5f5f5f80',
                        color: '#292a2b',
                        lineHeight: '32px',
                        fontWeight: 400,
                        fontFamily: 'Lato, sans-serif',
                        fontSize: '16px',
                        '::placeholder': {
                            color: '#5f5f5f80',
                        },

                    },
                    empty: {
                        backgroundColor: "#F9F9F9",
                    },
                    invalid: {
                        color: '#E25950',
                    }
                }
            });


            cardElement.mount("#card-element");
        });

        function pay_with_js() {
            // debugger;

            // var form = document.getElementById('payment-form');
            // var name = form.getElementById('name');
            // var card_number = form.getElementById('cardNumber');
            // var expiry = form.getElementById('cardExpiry');
            // var cvc = form.getElementById('cardCVC');


            stripe.createToken(cardElement).then(function(result) {
                // debugger;

                if (result.error) {
                    console.log(result.error);
                    document.getElementById('payment-error').textContent = result.error.message;
                }

                if (result.token) {
                    var form = document.getElementById('payment-form');
                    document.getElementById('strip_token').value = result.token.id;
                    form.submit();
                }
            });
        }
    </script>
@endpush
