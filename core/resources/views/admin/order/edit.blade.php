@extends('admin.layouts.app')
@section('panel')
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <form action="{{ route('admin.order.update', $order->id) }}" method="POST"
                      enctype="multipart/form-data">
                    @csrf
                    <div class="card-body">
                        <div class="payment-method-item">
                            <div class="payment-method-header addregionhere">
                                {{--show order informations price and shipping price--}}
                                <div class="col-lg-12" style="margin-top: 30px">
                                    <h3>Order Info</h3>
                                </div>
                                <div class="col-lg-12 row">
                                    @if($order->order_type == 'sample_payment')
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="">@lang('Products\' Price')</label>
                                                <input type="text" class="form-control" name="total_price_product"
                                                       value="US${{ $order->total_price_product }}" readonly>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="">@lang('Products\' Shipping Price')</label>
                                                <input type="text" class="form-control" name="shipping_price_product"
                                                       value="US${{ $order->shipping_price_product }}" readonly>

                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="">@lang('Samples\' Price')</label>
                                                <input type="text" class="form-control" name="total_price_sample"
                                                       value="US${{ $order->total_price_sample }}" readonly>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="">@lang('Samples\' Shipping Price')</label>
                                                <input type="text" class="form-control" name="shipping_price_sample"
                                                       value="US${{ $order->shipping_price_sample }}" readonly>

                                            </div>
                                        </div>
                                    @endif
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="">@lang('Total Price')</label>
                                            <input type="text" class="form-control" name="total_price"
                                                   value="US${{ $order->total_price }}" readonly>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="">@lang('Shipping Price')</label>
                                            <input type="text" class="form-control" name="shipping_price"
                                                   value="US${{ $order->shipping_price }}" readonly>

                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="">@lang('Payment Method')</label>
                                            <input type="text" class="form-control" name="payment_method"
                                                   value="{{ $order->payment_method }}" readonly>

                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="">@lang('Shipping Method')</label>
                                            <input type="text" class="form-control" name="payment_method"
                                                   value="{{ $shipping_method_name }}" readonly>

                                        </div>
                                    </div>
                                        @if($order->order_type == 'sample_payment' || $order->order_type == 'email_only_order')
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="">@lang('Paid Amount')</label>
                                                <input type="text" class="form-control" name="payment_method"
                                                       value="US${{ $order->paid_amount }}" readonly>

                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="">@lang('Order Type')</label>
                                                <input type="text" class="form-control" name="payment_method"
                                                       value="{{ $order->order_type??'Full Order' }}" readonly>

                                            </div>
                                        </div>
                                        @endif
                                </div>

                                {{--show product list--}}
                                <div class="col-lg-12" style="margin-top: 30px">
                                    <h3>Products</h3>
                                </div>
                                <div class="col-lg-12">
                                    <div class="table-responsive--md">
                                        <table class="table table--light style--two">

                                            @if($order->prices()->count()>0)
                                                <thead>
                                                <tr>
                                                    <th>@lang('S.N.')</th>
                                                    <th>@lang('Name')</th>
                                                    <th>@lang('Size')</th>
                                                    <th>@lang('Price')</th>
                                                    <th>@lang('Quantity')</th>
                                                    <th>@lang('Total Price')</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                @php
                                                    $prices = $order->prices
                                                @endphp
                                                @forelse($prices as $price)
                                                <tr>
                                                    <td data-label="@lang('S.N.')">{{ ($price->offer->id ??null)}}</td>
                                                    <td data-label="@lang('Name')">{{ ($price->offer->name ??null)}}</td>
                                                    <td data-label="@lang('Size')">{{  ($price->size->size??null) .' ( '.showAmount(($price->size->weight_LB??null)).' lb ) '}}</td>
                                                    <td data-label="@lang('Price')">US${{  showAmount($price->price)}}/lb</td>
                                                    <td data-label="@lang('Quantity')">{{  showAmount(($price->pivot->quantity??null))}}</td>
                                                    <td data-label="@lang('Total Price')">US${{showAmount(($price->pivot->quantity??null)*$price->product_total_price)}}</td>
                                                </tr>
                                                @empty
                                                    <tr>
                                                        <td class="text-muted text-center"
                                                            colspan="100%">{{ $emptyMessage }}</td>
                                                    </tr>
                                                @endforelse
                                                </tbody>
                                            @elseif($products->count()>0)
                                                <thead>
                                                <tr>
                                                    <th>@lang('S.N.')</th>
                                                    <th>@lang('Name')</th>
                                                    <th>@lang('Price')</th>
                                                    <th>@lang('Total Price')</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                @forelse($products as $product)
                                                @php
                                                    $max_bid = $product->max_bid();
                                                    $max_price = is_null($max_bid) ? $product->price : $max_bid->amount;
                                                @endphp
                                                <tr>
                                                    <td data-label="@lang('S.N.')">{{ $product->id }}</td>
                                                    <td data-label="@lang('Name')">{{ $product->name }}</td>
                                                    <td data-label="@lang('Price')">US${{  showAmount($product->price)}}/lb</td>
                                                    <td data-label="@lang('Total Price')">US${{showAmount($max_price)}}</td>
                                                </tr>
                                                @empty
                                                    <tr>
                                                        <td class="text-muted text-center"
                                                            colspan="100%">{{ $emptyMessage }}</td>
                                                    </tr>
                                                @endforelse
                                                </tbody>
                                            @elseif($order->sampleSets()->count()>0)
                                                <thead>
                                                <tr>
                                                    <th>@lang('S.N.')</th>
                                                    <th>@lang('Name')</th>
                                                    <th>@lang('Number Of Samples Per Box')</th>
                                                    <th>@lang('Weight Per Sample')</th>
                                                    <th>@lang('Price')</th>
                                                    <th>@lang('Quantity')</th>
                                                    <th>@lang('Total Price')</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                @php
                                                $sampleSets = $order->sampleSets;
                                                @endphp
                                                @forelse($sampleSets as $sampleSet)
                                                <tr>
                                                    <td data-label="@lang('S.N.')">{{ $sampleSet->id }}</td>
                                                    <td data-label="@lang('Name')">{{ ($sampleSet->event->name??'auction').' sample set' }}</td>
                                                    <td data-label="@lang('Number Of Samples Per Box')">{{ ($sampleSet->number_of_samples_per_box)}}</td>
                                                    <td data-label="@lang('Weight Per Sample')">{{ ($sampleSet->weight_per_sample_grams).' grams' }}</td>
                                                    <td data-label="@lang('Price')">US${{  showAmount($sampleSet->price)}}</td>
                                                    <td data-label="@lang('Quantity')">{{  showAmount($sampleSet->pivot->quantity??null)}}</td>
                                                    <td data-label="@lang('Total Price')">US${{showAmount(($sampleSet->pivot->quantity??0)*$sampleSet->price)}}</td>
                                                </tr>
                                                @empty
                                                    <tr>
                                                        <td class="text-muted text-center"
                                                            colspan="100%">{{ $emptyMessage }}</td>
                                                    </tr>
                                                @endforelse
                                                </tbody>
                                            @endif

                                        </table><!-- table end -->
                                    </div>
                                </div>


                                <div class="row">
                                    <div class="col-lg-12" style="margin-top: 30px">
                                        <h3>User Info</h3>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label for="firstname">@lang('First Name')</label>
                                            <input readonly type="text" class="form-control" id="firstname"
                                                   name="firstname" value="{{ $data['firstname'] }}">
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label for="lastname">@lang('Last Name')</label>
                                            <input readonly type="text" class="form-control" id="lastname"
                                                   name="lastname" value="{{  $data['lastname'] }}">
                                        </div>
                                    </div>

                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label for="email">@lang('Email')</label>
                                            <input readonly type="text" class="form-control" id="email" name="email"
                                                   value="{{  $data['email'] }}">
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label for="phone">@lang('Phone')</label>
                                            <input readonly type="text" class="form-control" id="phone" name="phone"
                                                   value="{{ $data['billing_phone'] }}">
                                        </div>
                                    </div>
                                    <div class="col-lg-12" style="margin-top: 30px">
                                        <h3>Company Info</h3>
                                    </div>

                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label for="company_name">@lang('Company Name')</label>
                                            <input readonly type="text" class="form-control" id="company_name"
                                                   name="company_name" value="{{   $data['company_name'] }}">
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label for="company_email">@lang('Company Website')</label>
                                            <input readonly type="text" class="form-control" id="company_email"
                                                   name="company_email" value="{{   $data['company_website'] }}">
                                        </div>
                                    </div>


                                    <div class="col-lg-12" style="margin-top: 30px">
                                        <h3>Shipping Address</h3>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label for="shipping_firstname">@lang('First Name')</label>
                                            <input readonly type="text" class="form-control" id="shipping_firstname"
                                                   name="shipping_firstname"
                                                   value="{{ $data['shipping_firstname'] }}">
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label for="shipping_lastname">@lang('Last Name')</label>
                                            <input readonly type="text" class="form-control" id="shipping_lastname"
                                                   name="shipping_lastname" value="{{  $data['shipping_lastname'] }}">
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label for="shipping_address">@lang('Address')</label>
                                            <input readonly type="text" class="form-control" id="shipping_address_1"
                                                   name="shipping_address_1"
                                                   value="{{ $data['shipping_address_1'] }}">
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label for="shipping_address_2">@lang('Address 2')</label>
                                            <input readonly type="text" class="form-control" id="shipping_address_2"
                                                   name="shipping_address_2"
                                                   value="{{  $data['shipping_address_2'] }}">
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label for="shipping_city">@lang('City')</label>
                                            <input readonly type="text" class="form-control" id="shipping_city"
                                                   name="shipping_city" value="{{ $data['shipping_city'] }}">
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label for="shipping_state">@lang('State')</label>
                                            <input readonly type="text" class="form-control" id="shipping_state"
                                                   name="shipping_state" value="{{ $data['shipping_state'] }}">
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label for="shipping_zip">@lang('Zip')</label>
                                            <input readonly type="text" class="form-control" id="shipping_postcode"
                                                   name="shipping_postcode" value="{{  $data['shipping_postcode'] }}">
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label for="shipping_country">@lang('Country')</label>
                                            {{-- <input readonly type="text" class="form-control" id="shipping_country"
                                                   name="shipping_country" value="{{ $data['shipping_country'] }}"> --}}
                                            <select disabled class="form-control" id="shipping_country">
                                                @foreach ($countries as $country)
                                                <option id={{ $country['id'] }} value={{ $country['id'] }} @if ($country['id']==$data['shipping_country']) selected @endif>
                                                    {{ $country['Name'] }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-lg-12" style="margin-top: 30px">
                                        <h3>Billing Address</h3>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label for="billing_firstname">@lang('First Name')</label>
                                            <input readonly type="text" class="form-control" id="billing_firstname"
                                                   name="billing_firstname"
                                                   value="{{  $data['billing_firstname'] }}">
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label for="billing_lastname">@lang('Last Name')</label>
                                            <input readonly type="text" class="form-control" id="billing_lastname"
                                                   name="billing_lastname" value="{{ $data['billing_lastname'] }}">
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label for="billing_address">@lang('Address')</label>
                                            <input readonly type="text" class="form-control" id="billing_address_1"
                                                   name="billing_address_1"
                                                   value="{{ $data['billing_address_1'] }}">
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label for="billing_address_2">@lang('Address 2')</label>
                                            <input readonly type="text" class="form-control" id="billing_address_2"
                                                   name="billing_address_2"
                                                   value="{{ $data['billing_address_2'] }}">
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label for="billing_city">@lang('City')</label>
                                            <input readonly type="text" class="form-control" id="billing_city"
                                                   name="billing_city" value="{{ $data['billing_city'] }}">
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label for="billing_state">@lang('State')</label>
                                            <input readonly type="text" class="form-control" id="billing_state"
                                                   name="billing_state" value="{{ $data['billing_state'] }}">
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label for="billing_zip">@lang('Zip')</label>
                                            <input readonly type="text" class="form-control" id="billing_postcode"
                                                   name="billing_postcode" value="{{   $data['billing_postcode'] }}">
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label for="billing_country">@lang('Country')</label>
                                            {{-- <input readonly type="text" class="form-control" id="billing_country"
                                                   name="billing_country" value="{{  $data['billing_country'] }}"> --}}
                                            <select disabled class="form-control" id="billing_country">
                                                @foreach ($countries as $country)
                                                <option id={{ $country['id'] }} value={{ $country['id'] }} @if ($country['id']==$data['billing_country']) selected @endif>
                                                    {{ $country['Name'] }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-lg-12" style="margin-top: 30px">
                                        <h3>Delivery Details</h3>
                                    </div>

                                    <div class="form--group col-md-12">
                                        <label
                                            class="form--label-2">@lang('Which of the following best describes your business?')</label>
                                        <select name="describes_business" class="form-control" disabled>
                                            <option value="">@lang("Please Select")</option>
                                            <option value="Cafe"
                                                    @if( $data['describes_business'] == "Cafe") selected @endif>Cafe
                                            </option>
                                            <option value="Roaster"
                                                    @if( $data['describes_business'] == "Roaster") selected @endif>
                                                Roaster
                                            </option>
                                            <option value="Importer"
                                                    @if( $data['describes_business'] == "Importer") selected @endif>
                                                Importer
                                            </option>
                                            <option value="Other"
                                                    @if( $data['describes_business'] == "Other") selected @endif>Other
                                            </option>
                                        </select>

                                    </div>

                                    <div class="form--group col-md-12">
                                        <label
                                            class="form--label-2">@lang('How many pounds (lbs) of green coffee do you buy each year?')</label>
                                        <select name="pounds_green_coffee" class="form-control" disabled>
                                            <option value="">@lang("Please Select")</option>
                                            <option value="Less Than 10,000 lbs"
                                                    @if( $data['pounds_green_coffee'] == "Less Than 10,000 lbs") selected @endif>
                                                Less Than 10,000 lbs
                                            </option>
                                            <option value="10,000 to 100,000"
                                                    @if( $data['pounds_green_coffee'] == "10,000 to 100,000") selected @endif>
                                                10,000 to 100,000
                                            </option>
                                            <option value="100,000 to 1,000,000"
                                                    @if( $data['pounds_green_coffee'] == "100,000 to 1,000,000") selected @endif>
                                                100,000 to 1,000,000
                                            </option>
                                            <option value="1,000,000 +"
                                                    @if( $data['pounds_green_coffee'] == "1,000,000 +") selected @endif>
                                                1,000,000 +
                                            </option>
                                        </select>

                                    </div>


                                    <div class="form--group col-md-12">
                                        <label
                                            class="form--label-2">@lang('Would you be interested in hosting or attending a cupping of Marketplace coffees?')</label>
                                        <select name="hosting_cupping" class="form-control" disabled>
                                            <option value="">@lang("Please Select")</option>
                                            <option value="Hosting"
                                                    @if($data['hosting_cupping'] == "Hosting") selected @endif>Hosting
                                            </option>
                                            <option value="Attending"
                                                    @if($data['hosting_cupping'] == "Attending") selected @endif>
                                                Attending
                                            </option>
                                        </select>

                                    </div>
                                    <div class="form--group col-md-12">
                                        <label
                                            class="form--label-2">@lang('Delivery: Do you need any of the following?')</label>
                                        <select name="delivery" class="form-control" disabled>
                                            <option value="">@lang("Please Select")</option>
                                            <option value="Lift gate delivery"
                                                    @if($data['delivery'] == "Lift gate delivery"||$data['is_lift_gate_delivery']==1 ) selected @endif>
                                                Lift gate delivery
                                            </option>
                                            <option value="Inside delivery"
                                                    @if($data['delivery']== "Inside delivery"||$data['is_inside_delivery']==1) selected @endif>
                                                Inside delivery
                                            </option>
                                            <option value="Appointment Request"
                                                    @if($data['delivery'] == "Appointment Request"||$data['is_appointment_request']==1) selected @endif>
                                                Appointment Request
                                            </option>
                                            <option value="Notify Request"
                                                    @if($data['delivery'] == "Notify Request"||$data['is_notify_request'] ==1) selected @endif>Notify
                                                Request
                                            </option>
                                            <option value="None of the above"
                                                    @if($data['delivery'] == "None of the above"||($data['is_lift_gate_delivery']==0&&$data['is_inside_delivery']==0&&$data['is_appointment_request']==0&&$data['is_notify_request']==0)) selected @endif>
                                                None of the above
                                            </option>
                                        </select>

                                    </div>


                                    <div class="form--group col-md-12">
                                        <label
                                            class="form--label-2">@lang('Preferred receiving days (check all that apply)')</label>
                                        <select name="preferred_receiving_day[]" class="form-control" multiple
                                                disabled>
                                            <option value="">@lang("Please Select")</option>
                                            <option value="Monday"
                                                    @if(in_array("Monday",is_array(json_decode($data['preferred_receiving_day']))?json_decode($data['preferred_receiving_day']):[])) selected @endif>
                                                Monday
                                            </option>
                                            <option value="Tuesday"
                                                    @if(in_array("Tuesday",is_array(json_decode($data['preferred_receiving_day']))?json_decode($data['preferred_receiving_day']):[])) selected @endif>
                                                Tuesday
                                            </option>
                                            <option value="Wednesday"
                                                    @if(in_array("Wednesday",is_array(json_decode($data['preferred_receiving_day']))?json_decode($data['preferred_receiving_day']):[])) selected @endif>
                                                Wednesday
                                            </option>
                                            <option value="Thursday"
                                                    @if(in_array("Thursday",is_array(json_decode($data['preferred_receiving_day']))?json_decode($data['preferred_receiving_day']):[])) selected @endif>
                                                Thursday
                                            </option>
                                            <option value="Friday"
                                                    @if(in_array("Friday",is_array(json_decode($data['preferred_receiving_day']))?json_decode($data['preferred_receiving_day']):[])) selected @endif>
                                                Friday
                                            </option>
                                        </select>

                                    </div>


                                    <div class="form--group col-md-12">
                                        <label
                                            class="form--label-2">@lang('Preferred receiving times (check all that apply)')</label>
                                        <select name="preferred_receiving_times[]" class="form-control" multiple
                                                disabled>
                                            <option value="">@lang("Please Select")</option>
                                            <option value="8 a.m. - 11 a.m."
                                                    @if(in_array("8 a.m. - 11 a.m.",is_array(json_decode( $data['preferred_receiving_times']))?json_decode( $data['preferred_receiving_times']):[])) selected @endif>
                                                8 a.m. - 11 a.m.
                                            </option>
                                            <option value="11 a.m. - 2 p.m."
                                                    @if(in_array("11 a.m. - 2 p.m.",is_array(json_decode( $data['preferred_receiving_times']))?json_decode( $data['preferred_receiving_times']):[])) selected @endif>
                                                11 a.m. - 2 p.m.
                                            </option>
                                            <option value="2 p.m. - 5 p.m."
                                                    @if(in_array("2 p.m. - 5 p.m.",is_array(json_decode( $data['preferred_receiving_times']))?json_decode( $data['preferred_receiving_times']):[])) selected @endif>
                                                2 p.m. - 5 p.m.
                                            </option>
                                        </select>

                                    </div>

                                    <div class="form--group col-md-12">
                                        <label
                                            class="form--label-2">@lang('EIN Number (US Businesses Only)')</label>
                                        <textarea id="other_special_delivery" name="other_special_delivery"
                                                  class="form-control" readonly
                                                  autocomplete="off">{{  $data['ein_number'] }}</textarea>
                                    </div>


                                    <div class="col-sm-12 col-xl-12 col-lg-12">
                                        <div class="form-group">
                                            <label class="font-weight-bold">@lang('Status')</label>
                                            <select name="status" class="form-control" required>
                                                <option value="">@lang('Select One')</option>
                                                <option
                                                    value="pending" {{ $order->status == "pending" ? 'Selected':'' }}>
                                                    Pending
                                                </option>
                                                <option
                                                    value="processing" {{ $order->status == "processing" ? 'Selected':'' }}>
                                                    Processing
                                                </option>
                                                <option
                                                    value="on_delivering" {{ $order->status == "on_delivering" ? 'Selected':'' }}>
                                                    On delivering
                                                </option>
                                                <option
                                                    value="completed" {{ $order->status == "completed" ? 'Selected':'' }}>
                                                    Completed
                                                </option>
                                                <option
                                                    value="cancelled" {{ $order->status == "cancelled" ? 'Selected':'' }}>
                                                    Cancelled
                                                </option>

                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-sm-12 col-xl-12 col-lg-12">
                                        <div class="form-group">
                                            <label class="font-weight-bold">@lang('Payment Status')</label>
                                            <select name="payment_status" class="form-control" required>
                                                <option value="">@lang('Select One')</option>
                                                <option
                                                    value="pending" {{ $order->payment_status == "pending" ? 'Selected':'' }}>
                                                    Pending
                                                </option>
                                                <option
                                                    value="paid" {{ $order->payment_status == "paid" ? 'Selected':'' }}>
                                                    Paid
                                                </option>
                                                <option
                                                    value="cancelled" {{ $order->payment_status == "cancelled" ? 'Selected':'' }}>
                                                    Cancelled
                                                </option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>


                        </div>

                    </div>

                    <div class="card-footer">
                        <button type="submit" class="btn btn--primary btn-block">@lang('Update Order')</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection


@push('breadcrumb-plugins')
    <a href="{{ route('admin.order.index') }}" class="btn btn-sm btn--primary box--shadow1 text--small"><i
            class="la la-fw la-backward"></i> @lang('Go Back') </a>
@endpush

@push('style')
    <style>
        .payment-method-item .payment-method-header .thumb .avatar-edit {
            bottom: auto;
            top: 175px;
        }
    </style>
@endpush

@push('script-lib')
    <script src="{{ asset('assets/admin/js/vendor/datepicker.min.js') }}"></script>
    <script src="{{ asset('assets/admin/js/vendor/datepicker.en.js') }}"></script>
@endpush


