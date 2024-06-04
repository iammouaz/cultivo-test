@extends($activeTemplate.'layouts.frontend')
@section('content')

    <section id="payment-page">

        @if(!is_null($winningHistories) && count($winningHistories) >0)
            <div class="container checkout-custom-page">
                <!-- Start Main -->
                <main class="row align-items-start">
                    <h1>@lang("Payment")</h1>

                    <!-- Start Pay Now -->
                    <section class="col-xl-7 pay-now">

                        <div class="card cmn--card bg--body card-deposit">
                            <!-- <div class="card-header">
                                <h5 class="card-title">@lang('Stripe Payment')</h5>
                            </div> -->
                            <div class="card-body card-body-deposit">
                                <div class="card-wrapper"></div>

                                <div id="card-element">
                                </div>

                                <div id="payment-error" >
                                </div>

                                <button class="pay-now-btn btn btn--base w-100 btn-lg btn-block text-center" onclick="pay_with_js()">@lang('PAY NOW')</button>
                                <form role="form" id="payment-form" method="POST" hidden
                                        action="{{route('user.checkout.payment.pay',['event_id'=>$event_id,'shipping_method'=>$shipping_method,'payment_method'=>$payment_method])}}">
                                    @csrf
                                    <input type="hidden" name="strip_token" id="strip_token">
                                </form>
                            </div>
                        </div>

                    </section>
                    <!-- End Pay Now -->

                    <!-- Start Order Summary Cart -->
                    <section class="col-xl-5 order-summary">
                        <div class="cart">
                            <header class="cart-header">
                                <h5>@lang("Order Summary")</h5>
                            </header>

                            <div class="cart-items">
                                @forelse ($winningHistories as $key => $winner)
                                    <div class="cart-item">
                                        {{--                                    <p>{{$key}} @lang('Item')</p>--}}
                                        <div>
                                            <div class="cart-item-description">
                                                <img
                                                    src="{{getImage(imagePath()['product']['path'].'/thumb_'.$winner->product->image,imagePath()['product']['thumb'])}}"
                                                    alt="Item 1">
                                                <div>
                                                    <p>{{ $winner->product->name }}
                                                    </p>

                                                    {{--
                                                    <span>
                                                        {{ $winningHistories->firstItem() + $loop->index
                                                        }}
                                                    </span>
                                                    --}}
                                                </div>
                                            </div>
                                            <span
                                                class="cart-item-price">
                                                {{ $general->cur_sym }}{{ showAmount($winner->bid->amount * $winner->bid->product->weight) }}
                                            </span>
                                        </div>
                                    </div>
                                @empty
                                    <tr>
                                        <td class="text-muted text-center" colspan="100%">{{ $emptyMessage }}</td>
                                    </tr>
                                @endforelse
                            </div>
                            <div class="cart-order-info">
                                <div>
                                    <p>@lang('Subtotal')</p>
                                    <span>{{ $general->cur_sym }}{{showAmount($sub_total)}}</span>
                                </div>

                                <div class="mt-4 mb-2">
                                    <p>@lang('Shipping and Handling')</p>
                                </div>

                                <div class="shipping-method-cost mb-4">
                                    <b class="flex-shrink-0">
                                        {{$region->shipping_method}}
                                    </b>

                                    <span class="flex-shrink-0">
                                        {{ $general->cur_sym }}
                                        <span
                                        id="total_shipping_price">      {{$total_shipping_price}}
                                        </span>
                                    </span>
                                </div>

                                <div>
                                    <p>@lang('Tax')</p>
                                    <div>
                                        <span>
                                            {{ $general->cur_sym }}
                                        </span>
                                        <span>
                                            0
                                        </span>
                                    </div>
                                </div>

                                {{--                        <button>Coupon/Gift</button>--}}
                            </div>

                            <footer class="cart-footer">
                                <span class="total-label">@lang("Total (USD)")</span>
                                <span class="price-label">{{ $general->cur_sym }}<span
                                        id="total_price">{{$total_price}}</span></span>
                            </footer>
                            @if (!is_null($hold_amount) )
                                <footer class="cart-footer">
                                    <span class="total-label">@lang("Deposit Amount (USD)")</span>
                                    <span class="price-label">{{ $general->cur_sym }}<span
                                            id="total_price">{{$hold_amount}}</span></span>
                                </footer>
                                <footer class="cart-footer">
                                    <span class="total-label" style="color: red">
                                        We will invoice you via email with payment instructions. Please respond immediately with your intent and timeline for payment for the coffees won. A deposit of {{$event_deposit_percentage}}% of the total amount will be held on your credit card until we receive that response. Please reach out to support@mcultivo.com directly if a credit card hold is not possible at this time.
                                    </span>
                                </footer>
                            @endif
                        </div>
                    </section>
                    <!-- End Order Summary Cart -->
                </main>
                <!-- End Main -->
            </div>
        @else
            <div class="product-section">
                <div class="container">
                    <h2 class="text-center m-0">@lang("No winning history found")</h2>
                </div>
            </div>
        @endif

    </section>

@endsection


@push('script')
    <script src="{{ asset('assets/global/js/card.js') }}"></script>

    <script>
        (function ($) {
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
        const stripe = Stripe('{{config('app.STRIPE_PUBLISHABLE_KEY')}}');
        var cardElement;

        document.addEventListener('DOMContentLoaded', (event) => {
            // debugger;

            var elements = stripe.elements({
                clientSecret: '{{$intent->client_secret}}',
            });

            // create stripe payment card with holder name
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


            stripe.createToken(cardElement).then(function (result) {
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
