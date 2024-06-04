@php
$sampleSet = $order->sampleSets()->first()??null;
if ($sampleSet) {
    $sampleSet->quantity = $sampleSet->pivot->quantity;
}
$allow_product_payment = config('app.allow_product_payment');
@endphp
<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirmed</title>


    <style>
        .w-100 {
            width: 100%
        }

        .text-center {
            text-align: center
        }

        .header-title {
            font-size: 48px;
            font-weight: 600;
            line-height: 56px;
            letter-spacing: 0px;


        }

        .content-main-header {

            font-size: 34px;
            font-weight: 600;
            line-height: 42px;
            letter-spacing: 0.25px;
            text-align: left;
            margin: 0px;
            margin-block: 5px;

        }

        body {
            background: #FAFAFA !important;
            font-family: "Lato", sans-serif;
        }

        .card {
            background: white;
            padding: 20px;
            box-shadow: 0px 5px 5px -3px #24282833, 0px 8px 10px 1px #24282824, 0px 3px 14px 2px #2428281F;
            box-sizing: border-box;
        }

        .text-right {
            text-align: right
        }

        .text-left {
            text-align: left
        }

        .info-paragraph {
            color: #242828DE;
            font-size: 16px;
            font-weight: 500;
            line-height: 24px;
            letter-spacing: 0.15000000596046448px;
        }

        .responsive-card {
            width: 100%;
        }

        @media (min-width: 856px) {
            .responsive-card {
                width: 856px
            }
        }
        .preferred-days-times, .preferred-days-times * {
            display: none;{{-- todo enable these and fix saving to exclude the non-checked items--}}
        }
    </style>
</head>

<body>
    <section class="product-section" style="padding-block: 100px;">
        <div class="container">
            <table class="w-100">
                <tr>
                    <td class="text-center">
                        <h2 style="max-width: 633px;display:inline-block">
                            @if ($isClient)
                                Thank you for your order!
                            @endif
                        </h2>
                    </td>
                </tr>
                <tr>
                    <td class="text-center">
                        <p style="max-width: 633px;display:inline-block">
                            @if ($isClient)
                                Your order is confirmed. We've notified the producer, who will now prepare your items.
                                You'll receive an email with all the details shortly.
                            @else
                                You have a new fixed price order! Order #{{ $order->id }}
                            @endif
                        </p>
                    </td>
                </tr>
            </table>

            <table class="w-100">
                <tr>
                    <td class="text-center">

                        <div class="card responsive-card" style="margin-top: 50px;display:inline-block">
                            <table>
                                <tbody>
                                    <tr>
                                        <td>
                                            <h3 class="content-main-header">
                                                Order Details
                                            </h3>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>

                            <table class="w-100" style="margin-top: 10px">
                                <tbody>
                                    <tr>
                                        <td class="text-left info-paragraph">
                                            Order number
                                        </td>
                                        <td class="text-right info-paragraph">
                                            {{ $order->id }}
                                        </td>
                                    </tr>

                                    <tr>
                                        <td class="text-left info-paragraph">
                                            Date
                                        </td>
                                        <td class="text-right info-paragraph">
                                            {{ $order->created_at }}
                                        </td>
                                    </tr>


                                    <tr>
                                        <td class="text-left info-paragraph">
                                            Customer
                                        </td>
                                        <td class="text-right info-paragraph">
                                            {{ $order->customer_first_name . ' ' . $order->customer_last_name }}
                                        </td>
                                    </tr>

                                    <tr>
                                        <td class="text-left info-paragraph">
                                            Email
                                        </td>
                                        <td class="text-right info-paragraph">
                                            {{ $order->customer_email }}
                                        </td>
                                    </tr>


                                    <tr>
                                        <td class="text-left info-paragraph">
                                            Payment method
                                        </td>
                                        <td class="text-right info-paragraph">
                                            {{ $order->payment_status }}
                                        </td>
                                    </tr>

                                </tbody>
                            </table>



                            <hr style="margin-block:25px" />


                            <table class="w-100">
                                <tbody>
                                    <tr>
                                        <td class="text-left">
                                            <h4 style="margin:0px">Products</h4>
                                        </td>
                                    </tr>

                                    <tr>
                                        <td>
                                            <table class="w-100"
                                                style="border-collapse: separate;border-spacing: 0 30px;">
                                                <tbody>
                                                    @if($sampleSet)
                                                        <tr>
                                                            <td>
                                                                <img style="object-fit: cover;border-radius:12px"
                                                                     src="{{ getImage(imagePath()['product']['path'] . '/' . $sampleSet->image, imagePath()['product']['size'], false, 'sm') }}"
                                                                     width="178px" height="178px" />
                                                            </td>
                                                            <td class="w-100"
                                                                style="vertical-align: baseline;padding-left:20px">
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
                                                                            style="font-weight: 600">
                                                                            {{ $sampleSet->event->name .' Sample Set' }}</td>
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
                                                                USD${{ number_format($sampleSet->price, 2) }}
                                                            </td>
                                                        </tr>
                                                    @endif
                                                    @foreach ($order->prices as $price)
                                                        <tr>
                                                            <td>
                                                                <img style="object-fit: cover;border-radius:12px"
                                                                    src="{{ getImage(imagePath()['product']['path'] . '/' . $price->offer->photo, imagePath()['product']['size'], false, 'sm') }}"
                                                                    width="178px" height="178px" />
                                                            </td>
                                                            <td class="w-100"
                                                                style="vertical-align: baseline;padding-left:20px">
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
                                                                                style="font-weight: 600">
                                                                                {{ $price->offer->name }}</td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td class="text-left info-paragraph"
                                                                                style="padding-top: 20px">Size:
                                                                                {{ $price->size->weight_LB }}lb</td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td class="text-left info-paragraph">
                                                                                Quantity: {{ $price->pivot->quantity }}
                                                                            </td>
                                                                        </tr>
                                                                    </tbody>
                                                                </table>
                                                            </td>
                                                            <td class="info-paragraph"
                                                                style="font-weight: 600;vertical-align:baseline">
                                                                USD${{ number_format($price->product_total_price, 2) }}
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </td>
                                    </tr>





                                </tbody>
                            </table>

                            <hr style="margin-block:25px" />

                            <table class="w-100" style="margin-top: 10px">
                                <tbody>
                                    <tr>
                                        <td class="text-left" style="padding-bottom: 10px">
                                            <h4 style="margin: 0px;margin-bottom:10px">Shipping and Delivery Details
                                            </h4>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-left info-paragraph">
                                            Business Details Name
                                        </td>
                                        <td class="text-right info-paragraph">
                                            {{ $order->shipping_first_name . '' . $order->shipping_last_name }}
                                        </td>
                                    </tr>




                                    <tr>
                                        <td class="text-left info-paragraph">
                                            Country, City
                                        </td>
                                        <td class="text-right info-paragraph">
                                            {{ $order->shipping_country }}, {{ $order->shipping_city }}
                                        </td>
                                    </tr>

                                    <tr>
                                        <td class="text-left info-paragraph">
                                            Address
                                        </td>
                                        <td class="text-right info-paragraph">
                                            {{ $order->shipping_address1 }}
                                        </td>
                                    </tr>


                                    <tr>
                                        <td class="text-left info-paragraph">
                                            Zip Code
                                        </td>
                                        <td class="text-right info-paragraph">
                                            {{ $order->shipping_zip }}
                                        </td>
                                    </tr>

                                    <tr>
                                        <td class="text-left info-paragraph">
                                            @lang('Phone')
                                        </td>
                                        <td class="text-right info-paragraph">
                                            {{ $order->shipping_phone }}
                                        </td>
                                    </tr>


                                    <tr class="preferred-days-times">
                                        <td class="text-left info-paragraph">
                                            @lang('Preferred receiving day and time')
                                        </td>
                                        <td class="text-right info-paragraph">
                                            @include('templates.basic.partials.delivery_time_ranges',['delivery_time_ranges'=>$order->delivery_date])
                                        </td>
                                    </tr>



                                </tbody>
                            </table>

                            <hr style="margin-block:25px" />


                            <table class="w-100" style="margin-top: 10px">
                                <tbody>
                                    <tr>
                                        <td class="text-left info-paragraph" style="padding-bottom: 5px">
                                            @lang('Subtotal')
                                        </td>
                                        <td class="text-right info-paragraph">
                                            ${{ $order->total_price - $order->shipping_price }}
                                        </td>
                                    </tr>

                                    @if(!$allow_product_payment && !$sampleSet)
                                        <tr>
                                            <td class="text-left info-paragraph" style="padding-bottom: 12px">
                                                @lang('Shipping and Handling (Sample)')
                                            </td>
                                            <td class="text-right info-paragraph" style="padding-bottom: 12px">
                                                ${{$order->shipping_price_sample}}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="text-left info-paragraph" style="padding-bottom: 12px">
                                                @lang('Sample(s) Payment')
                                            </td>
                                            <td class="text-right info-paragraph" style="padding-bottom: 12px">
                                                ${{ number_format($order->total_price_sample,2) }}

                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="text-left info-paragraph" style="font-weight: 600 !important">
                                                @lang('Pending Grand Total (USD)')
                                            </td>
                                            <td class="text-right info-paragraph" style="font-weight: 600 !important">
                                                ${{number_format($order->total_price_product,2)}}
                                            </td>
                                        </tr>
                                    @else
                                    <tr>
                                        <td class="text-left info-paragraph" style="padding-bottom: 12px">
                                            @lang('Shipping and Handling')
                                        </td>
                                        <td class="text-right info-paragraph" style="padding-bottom: 12px">
                                            ${{ $order->shipping_price }}
                                        </td>
                                    </tr>


                                    <tr>
                                        <td class="text-left info-paragraph" style="font-weight: 600 !important">
                                            Grand Total (USD)
                                        </td>
                                        <td class="text-right info-paragraph" style="font-weight: 600 !important">
                                            ${{ $order->total_price }}
                                        </td>
                                    </tr>
                                    @endif


                                </tbody>
                            </table>

                        </div>
                    </td>
                </tr>
            </table>


        </div>
    </section>
</body>

</html>
