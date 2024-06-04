@if ($mode === 'edit')
    {{-- edit mode --}}

    <div class="{{ !$disableCard ? 'card-body' : '' }}">

        <div class="w-100">
            <h5 class="font-weight-bold mb-3">@lang('Shipping')</h5>
        </div>


        {{-- Shipping Region --}}
        <div class="internal-wrapper">
            @if (old('shippingregions'))
                <div class="row mb-2">
                    <div class="col-lg-12">

                        <h6 class="mb-2">@lang('Shipping Region')
                            <button type="button" class="btn btn-sm btn-outline-dark float-right addRegion"><i
                                    class="la la-fw la-plus"></i>@lang('Add New Region')
                            </button>
                        </h6>
                        {{-- <div class="card-body"> --}}
                        <div class="addedField">
                            @foreach (old('shippingregions') as $key => $shippingregion)
                                <div class="row w-100 entry" data-index="{{ $key }}">
                                    <div class="col-lg-12">
                                        <div class="col-lg-12">
                                            <div class="row">
                                                <div class="col-lg-11 user-data px-0">
                                                    <div class="form-group">
                                                        <div class="input-group mb-md-0 mb-4">
                                                            <div class="col-6">
                                                                <label class="w-100 font-weight-bold">@lang('Region name')
                                                                    <span class="text-danger">*</span></label>
                                                                <select required class="form-control"
                                                                    name="shippingregions[{{ $key }}][region_name]">
                                                                    <option value="">
                                                                        @lang('Select Region')
                                                                    </option>
                                                                    @foreach ($regions as $region)
                                                                        <option
                                                                            @if ($shippingregion['region_name'] == $region->name) selected @endif
                                                                            value="{{ $region->name }}">
                                                                            {{ $region->name }}
                                                                        </option>
                                                                    @endforeach
                                                                </select>
                                                            </div>



                                                            <div class="col-6">
                                                                <label class="w-100 font-weight-bold">@lang('Shipping method')
                                                                    <span class="text-danger">*</span></label>
                                                                <select required class="form-control "
                                                                    name="shippingregions[{{ $key }}][shipping_method]">
                                                                    <option value="">
                                                                        @lang('Select shipping method')
                                                                    </option>
                                                                    <option
                                                                        @if ($shippingregion['shipping_method'] == 'Air Freight (expedited)') selected @endif
                                                                        value="Air Freight (expedited)">
                                                                        @lang('Air Freight (expedited)')
                                                                    </option>
                                                                    <option
                                                                        @if ($shippingregion['shipping_method'] == 'Air Freight (Palletized)') selected @endif
                                                                        value="Air Freight (Palletized)">
                                                                        @lang('Air Freight (Palletized)')
                                                                    </option>
                                                                    <option
                                                                        @if ($shippingregion['shipping_method'] == 'Ocean Freight') selected @endif
                                                                        value="Ocean Freight">
                                                                        @lang('Ocean Freight')
                                                                    </option>

                                                                </select>
                                                            </div>

                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-lg-1  text-right align-self-center">
                                                    <span class="input-group-btn">
                                                        <i class="las la-trash  deleteRegion"
                                                            style="font-size: 28px;cursor:pointer"></i>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-12">
                                        <div class="row">

                                            <div class="col-lg-12">

                                                <div class="ranges">
                                                    <h6 class="mb-2">@lang('Ranges')
                                                        <button type="button"
                                                            class="position-relative btn btn-sm btn-outline-dark float-right addRange" style="right: -30px"><i
                                                                class="la la-fw la-plus"></i>@lang('Add New Range')
                                                        </button>
                                                    </h6>
                                                    {{-- <div class="card-body"> --}}
                                                    <div class="range-addedField">
                                                        @foreach (old('shippingranges')[$key] as $shippingrangeKey => $shippingrange)
                                                            <div class="row w-100">

                                                                <div class="col-lg-11 user-data px-0">
                                                                    <div class="form-group">
                                                                        <div class="input-group mb-md-0 mb-4">
                                                                            <div class="col-4">

                                                                                <label
                                                                                    class="w-100 font-weight-bold">@lang('From (Lb)')
                                                                                    <span
                                                                                        class="text-danger">*</span></label>
                                                                                <input
                                                                                    value="{{ $shippingrange['from'] }}"
                                                                                    name="shippingranges[{{ $key }}][{{ $shippingrangeKey }}][from]"
                                                                                    class="form-control" type="number"
                                                                                    required
                                                                                    placeholder="@lang('From')">

                                                                            </div>


                                                                            <div class="col-4">
                                                                                <label
                                                                                    class="w-100 font-weight-bold">@lang('Up To (Lb)')
                                                                                    <span
                                                                                        class="text-danger">*</span></label>
                                                                                <input
                                                                                    value="{{ $shippingrange['up_to'] }}"
                                                                                    name="shippingranges[{{ $key }}][{{ $shippingrangeKey }}][up_to]"
                                                                                    type="number" class="form-control"
                                                                                    type="number" required
                                                                                    placeholder="@lang('Up To')">
                                                                            </div>

                                                                            <div class="col-4">
                                                                                <label
                                                                                    class="w-100 font-weight-bold">@lang('Cost (USD)')
                                                                                    <span
                                                                                        class="text-danger">*</span></label>
                                                                                <input
                                                                                    value="{{ $shippingrange['cost'] }}"
                                                                                    name="shippingranges[{{ $key }}][{{ $shippingrangeKey }}][cost]"
                                                                                    class="form-control" type="number"
                                                                                    min="0" required
                                                                                    step="0.00000000001"
                                                                                    placeholder="@lang('Cost')">
                                                                            </div>



                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                <div class="col-lg-1  text-right align-self-center">
                                                                    <span class="input-group-btn">
                                                                        <i class="las la-trash deleteButton"
                                                                            style="font-size: 28px;cursor:pointer"></i>
                                                                    </span>
                                                                </div>




                                                            </div>
                                                        @endforeach

                                                    </div>
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-12">
                                        <hr />
                                    </div>
                                </div>
                            @endforeach

                        </div>
                    </div>


                </div>
            @else
                <div class="row mb-2">
                    <div class="col-lg-12">

                        <h6 class="mb-2">@lang('Shipping Region')
                            <button type="button" class="btn btn-sm btn-outline-dark float-right addRegion"><i
                                    class="la la-fw la-plus"></i>@lang('Add New Region')
                            </button>
                        </h6>
                        {{-- <div class="card-body"> --}}
                        <div class="addedField">
                            @foreach ($shippingregions as $key => $shippingregion)
                                <div class="row w-100 entry" data-index={{ $key }}>
                                    <div class="col-lg-12">
                                        <div class="col-lg-12">
                                            <div class="row">
                                                <div class="col-lg-11 user-data px-0">
                                                    <div class="form-group">
                                                        <div class="input-group mb-md-0 mb-4">
                                                            <div class="col-6">
                                                                <label class="w-100 font-weight-bold">@lang('Region name')
                                                                    <span class="text-danger">*</span></label>
                                                                <select required class="form-control "
                                                                    name="shippingregions[{{ $key }}][region_name]">
                                                                    <option value="">
                                                                        @lang('Select Region')
                                                                    </option>

                                                                    @foreach ($regions as $region)
                                                                        <option
                                                                            @if ($shippingregion->region_name == $region->name) selected @endif
                                                                            value="{{ $region->name }}">
                                                                            {{ $region->name }}
                                                                        </option>
                                                                    @endforeach
                                                                </select>
                                                            </div>



                                                            <div class="col-6">
                                                                <label
                                                                    class="w-100 font-weight-bold">@lang('Shipping method')
                                                                    <span class="text-danger">*</span></label>
                                                                <select required class="form-control "
                                                                    name="shippingregions[{{ $key }}][shipping_method]">
                                                                    <option value="">
                                                                        @lang('Select shipping method')
                                                                    </option>
                                                                    <option
                                                                        @if ($shippingregion->shipping_method == 'Air Freight (expedited)') selected @endif
                                                                        value="Air Freight (expedited)">
                                                                        @lang('Air Freight (expedited)')
                                                                    </option>
                                                                    <option
                                                                        @if ($shippingregion->shipping_method == 'Air Freight (Palletized)') selected @endif
                                                                        value="Air Freight (Palletized)">
                                                                        @lang('Air Freight (Palletized)')
                                                                    </option>
                                                                    <option
                                                                        @if ($shippingregion->shipping_method == 'Ocean Freight') selected @endif
                                                                        value="Ocean Freight">
                                                                        @lang('Ocean Freight')
                                                                    </option>

                                                                </select>
                                                            </div>

                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-lg-1  text-right align-self-center">
                                                    <span class="input-group-btn">
                                                        <i class="las la-trash  deleteRegion"
                                                            style="font-size: 28px;cursor:pointer"></i>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-12">
                                        <div class="row">

                                            <div class="col-lg-12">

                                                <div class="ranges">
                                                    <h6 class="mb-2">@lang('Ranges')
                                                        <button type="button"
                                                            class="position-relative btn btn-sm btn-outline-dark float-right addRange" style="right: -30px"><i
                                                                class="la la-fw la-plus"></i>@lang('Add New Range')
                                                        </button>
                                                    </h6>
                                                    {{-- <div class="card-body"> --}}
                                                    <div class="range-addedField">
                                                        @foreach ($shippingregion->range as $range_key => $range)
                                                            <div class="row w-100">

                                                                <div class="col-lg-11 user-data px-0">
                                                                    <div class="form-group">
                                                                        <div class="input-group mb-md-0 mb-4">
                                                                            <div class="col-4">

                                                                                <label
                                                                                    class="w-100 font-weight-bold">@lang('From (Lb)')
                                                                                    <span
                                                                                        class="text-danger">*</span></label>
                                                                                <input value="{{ $range->from }}"
                                                                                    name="shippingranges[{{ $key }}][{{ $range_key }}][from]"
                                                                                    class="form-control"
                                                                                    type="number" required
                                                                                    placeholder="@lang('From')">

                                                                            </div>


                                                                            <div class="col-4">
                                                                                <label
                                                                                    class="w-100 font-weight-bold">@lang('Up To (Lb)')
                                                                                    <span
                                                                                        class="text-danger">*</span></label>
                                                                                <input value="{{ $range->up_to }}"
                                                                                    name="shippingranges[{{ $key }}][{{ $range_key }}][up_to]"
                                                                                    type="number"
                                                                                    class="form-control"
                                                                                    type="number" required
                                                                                    placeholder="@lang('Up To')">
                                                                            </div>

                                                                            <div class="col-4">
                                                                                <label
                                                                                    class="w-100 font-weight-bold">@lang('Cost (USD)')
                                                                                    <span
                                                                                        class="text-danger">*</span></label>
                                                                                <input value="{{ $range->cost }}"
                                                                                    name="shippingranges[{{ $key }}][{{ $range_key }}][cost]"
                                                                                    class="form-control"
                                                                                    type="number" min="0"
                                                                                    required step="0.00000000001"
                                                                                    placeholder="@lang('Cost')">
                                                                            </div>



                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                <div class="col-lg-1  text-right align-self-center">
                                                                    <span class="input-group-btn">
                                                                        <i class="las la-trash deleteButton"
                                                                            style="font-size: 28px;cursor:pointer"></i>
                                                                    </span>
                                                                </div>




                                                            </div>
                                                        @endforeach

                                                    </div>
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-12">
                                        <hr />
                                    </div>
                                </div>
                            @endforeach

                        </div>
                    </div>


                </div>
            @endif
        </div>
        {{-- End Shipping Region --}}










        {{-- Handling fee --}}
        <div class="internal-wrapper">

            @if (old('fees'))
                @foreach (old('fees') as $key => $fee)
                    <div class="row w-100">

                        <div class="col-lg-11 user-data px-0">
                            <div class="form-group">
                                <div class="input-group mb-md-0 mb-4">
                                    <div class="col-4">
                                        <label class="w-100 font-weight-bold">@lang('Country name')
                                            <span class="text-danger">*</span></label>
                                        <select required class="form-control "
                                            name="fees[{{ $key }}][country_id]">
                                            <option value="">
                                                @lang('Select Country')
                                            </option>
                                            @foreach ($countries as $country)
                                                <option @if ($fee['country_id'] == $country->id) selected @endif
                                                    value="{{ $country->id }}">
                                                    {{ $country->Name }}
                                                </option>
                                            @endforeach
                                            <option value="999">
                                                @lang('Rest Of The World')
                                            </option>
                                        </select>
                                    </div>


                                    <div class="col-4">
                                        <label class="w-100 font-weight-bold">@lang('Payment Method')
                                            <span class="text-danger">*</span></label>
                                        <select required class="form-control "
                                            name="fees[{{ $key }}][payment_method]">
                                            <option value="">
                                                @lang('Select Payment Method')
                                            </option>
                                            <option @if ($fee['payment_method'] === 'Credit Card') selected @endif
                                                value="Credit Card">
                                                @lang('Credit Card')
                                            </option>
                                            <option @if ($fee['payment_method'] === 'Wire Transfer') selected @endif
                                                value="Wire Transfer">
                                                @lang('Wire Transfer')
                                            </option>
                                        </select>
                                    </div>

                                    <div class="col-4">
                                        <label class="w-100 font-weight-bold">@lang('Credit Card Processing %')
                                            <span class="text-danger">*</span></label>
                                        <input type="number" min=".01" max="100" step=".01" required
                                            class="form-control" name="fees[{{ $key }}][fee_value]"
                                            value="{{ $fee['fee_value'] }}" placeholder="@lang('Credit Card Processing %')" />
                                    </div>



                                </div>
                            </div>
                        </div>


                        <div class="col-lg-1  text-right align-self-center">
                            <span class="input-group-btn">
                                <i class="las la-trash deleteButton" style="font-size: 28px;cursor:pointer"></i>
                            </span>
                        </div>

                    </div>
                @endforeach
            @else
                <div class="row">
                    <div class="col-lg-12">

                        <h6 class="mb-2">@lang('Handling Fee')
                            <button type="button" class="btn btn-sm btn-outline-dark float-right addFees"><i
                                    class="la la-fw la-plus"></i>@lang('Add New Fees')
                            </button>
                        </h6>
                        {{-- <div class="card-body"> --}}

                        <div class="addedField">
                            @foreach ($event->fees as $key => $fee)
                                <div class="row w-100">

                                    <div class="col-lg-11 user-data px-0">
                                        <div class="form-group">
                                            <div class="input-group mb-md-0 mb-4">
                                                <div class="col-4">
                                                    <label class="w-100 font-weight-bold">@lang('Country name')
                                                        <span class="text-danger">*</span></label>
                                                    <select required class="form-control "
                                                        name="fees[{{ $key }}][country_id]">
                                                        <option value="{{ $fee->country->id ?? 999 }}">
                                                            {{ $fee->country->Name ?? 'Rest Of The World' }}
                                                        </option>
                                                        </option>
                                                        @foreach ($countries as $country)
                                                            <option value="{{ $country->id }}">
                                                                {{ $country->Name }}
                                                            </option>
                                                        @endforeach
                                                        <option value="999">
                                                            @lang('Rest Of The World')
                                                        </option>
                                                    </select>
                                                </div>


                                                <div class="col-4">
                                                    <label class="w-100 font-weight-bold">@lang('Payment Method')
                                                        <span class="text-danger">*</span></label>
                                                    <select required class="form-control "
                                                        name="fees[{{ $key }}][payment_method]">
                                                        <option value="{{ $fee->payment_method }}">
                                                            {{ $fee->payment_method }}
                                                        </option>
                                                        <option value="Wire Transfer">
                                                            @lang('Wire Transfer')</option>
                                                    </select>
                                                </div>

                                                <div class="col-4">
                                                    <label class="w-100 font-weight-bold">@lang('Credit Card Processing %')
                                                        <span class="text-danger">*</span></label>
                                                    <input type="number" min=".01" max="100"
                                                        step=".01" required class="form-control"
                                                        name="fees[{{ $key }}][fee_value]"
                                                        placeholder="@lang('Credit Card Processing %')"
                                                        value="{{ $fee->fee_value }}" />
                                                </div>



                                            </div>
                                        </div>
                                    </div>


                                    <div class="col-lg-1  text-right align-self-center">
                                        <span class="input-group-btn">
                                            <i class="las la-trash deleteButton"
                                                style="font-size: 28px;cursor:pointer"></i>
                                        </span>
                                    </div>

                                </div>
                            @endforeach
                        </div>

                    </div>
                </div>
            @endif
        </div>
        {{-- end of fees --}}

    </div>
@else
    {{-- create mode --}}
    <div class="{{ !$disableCard ? 'card-body' : '' }}">

        <div class="w-100">
            <h5 class="font-weight-bold mb-3">@lang('Shipping')</h5>
        </div>


        {{-- Shipping Region --}}
        <div class="internal-wrapper">
            @if (old('shippingregions'))
                <div class="row mb-2">
                    <div class="col-lg-12">

                        <h6 class="mb-2">@lang('Shipping Region')
                            <button type="button" class="btn btn-sm btn-outline-dark float-right addRegion"><i
                                    class="la la-fw la-plus"></i>@lang('Add New Region')
                            </button>
                        </h6>
                        {{-- <div class="card-body"> --}}
                        <div class="addedField">
                            @foreach (old('shippingregions') as $key => $shippingregion)
                                <div class="row w-100 entry" data-index="{{ $key }}">
                                    <div class="col-lg-12">
                                        <div class="col-lg-12">
                                            <div class="row">
                                                <div class="col-lg-11 user-data px-0">
                                                    <div class="form-group">
                                                        <div class="input-group mb-md-0 mb-4">
                                                            <div class="col-6">
                                                                <label
                                                                    class="w-100 font-weight-bold">@lang('Region name')
                                                                    <span class="text-danger">*</span></label>
                                                                <select required class="form-control"
                                                                    name="shippingregions[{{ $key }}][region_name]">
                                                                    <option value="">
                                                                        @lang('Select Region')
                                                                    </option>
                                                                    @foreach ($regions as $region)
                                                                        <option
                                                                            @if ($shippingregion['region_name'] == $region->name) selected @endif
                                                                            value="{{ $region->name }}">
                                                                            {{ $region->name }}
                                                                        </option>
                                                                    @endforeach
                                                                </select>
                                                            </div>



                                                            <div class="col-6">
                                                                <label
                                                                    class="w-100 font-weight-bold">@lang('Shipping method')
                                                                    <span class="text-danger">*</span></label>
                                                                <select required class="form-control "
                                                                    name="shippingregions[{{ $key }}][shipping_method]">
                                                                    <option value="">
                                                                        @lang('Select shipping method')
                                                                    </option>
                                                                    <option
                                                                        @if ($shippingregion['shipping_method'] == 'Air Freight (expedited)') selected @endif
                                                                        value="Air Freight (expedited)">
                                                                        @lang('Air Freight (expedited)')
                                                                    </option>
                                                                    <option
                                                                        @if ($shippingregion['shipping_method'] == 'Air Freight (Palletized)') selected @endif
                                                                        value="Air Freight (Palletized)">
                                                                        @lang('Air Freight (Palletized)')
                                                                    </option>
                                                                    <option
                                                                        @if ($shippingregion['shipping_method'] == 'Ocean Freight') selected @endif
                                                                        value="Ocean Freight">
                                                                        @lang('Ocean Freight')
                                                                    </option>

                                                                </select>
                                                            </div>

                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-lg-1  text-right align-self-center">
                                                    <span class="input-group-btn">
                                                        <i class="las la-trash  deleteRegion"
                                                            style="font-size: 28px;cursor:pointer"></i>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-12">
                                        <div class="row">

                                            <div class="col-lg-12">

                                                <div class="ranges">
                                                    <h6 class="mb-2">@lang('Ranges')
                                                        <button type="button"
                                                            class="position-relative btn btn-sm btn-outline-dark float-right addRange" style="right: -30px"><i
                                                                class="la la-fw la-plus"></i>@lang('Add New Range')
                                                        </button>
                                                    </h6>
                                                    {{-- <div class="card-body"> --}}
                                                    <div class="range-addedField">
                                                        @foreach (old('shippingranges')[$key] as $shippingrangeKey => $shippingrange)
                                                            <div class="row w-100">

                                                                <div class="col-lg-11 user-data px-0">
                                                                    <div class="form-group">
                                                                        <div class="input-group mb-md-0 mb-4">
                                                                            <div class="col-4">

                                                                                <label
                                                                                    class="w-100 font-weight-bold">@lang('From (Lb)')
                                                                                    <span
                                                                                        class="text-danger">*</span></label>
                                                                                <input
                                                                                    value="{{ $shippingrange['from'] }}"
                                                                                    name="shippingranges[{{ $key }}][{{ $shippingrangeKey }}][from]"
                                                                                    class="form-control"
                                                                                    type="number" required
                                                                                    placeholder="@lang('From')">

                                                                            </div>


                                                                            <div class="col-4">
                                                                                <label
                                                                                    class="w-100 font-weight-bold">@lang('Up To (Lb)')
                                                                                    <span
                                                                                        class="text-danger">*</span></label>
                                                                                <input
                                                                                    value="{{ $shippingrange['up_to'] }}"
                                                                                    name="shippingranges[{{ $key }}][{{ $shippingrangeKey }}][up_to]"
                                                                                    type="number"
                                                                                    class="form-control"
                                                                                    type="number" required
                                                                                    placeholder="@lang('Up To')">
                                                                            </div>

                                                                            <div class="col-4">
                                                                                <label
                                                                                    class="w-100 font-weight-bold">@lang('Cost (USD)')
                                                                                    <span
                                                                                        class="text-danger">*</span></label>
                                                                                <input
                                                                                    value="{{ $shippingrange['cost'] }}"
                                                                                    name="shippingranges[{{ $key }}][{{ $shippingrangeKey }}][cost]"
                                                                                    class="form-control"
                                                                                    type="number" min="0"
                                                                                    required step="0.00000000001"
                                                                                    placeholder="@lang('Cost')">
                                                                            </div>



                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                <div class="col-lg-1  text-right align-self-center">
                                                                    <span class="input-group-btn">
                                                                        <i class="las la-trash deleteButton"
                                                                            style="font-size: 28px;cursor:pointer"></i>
                                                                    </span>
                                                                </div>




                                                            </div>
                                                        @endforeach

                                                    </div>
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-12">
                                        <hr />
                                    </div>
                                </div>
                            @endforeach

                        </div>
                    </div>


                </div>
            @else
                <div class="row mb-2">
                    <div class="col-lg-12">

                        <h6 class="mb-2">@lang('Shipping Region')
                            <button type="button" class="btn btn-sm btn-outline-dark float-right addRegion"><i
                                    class="la la-fw la-plus"></i>@lang('Add New Region')
                            </button>
                        </h6>
                        {{-- <div class="card-body"> --}}
                        <div class="addedField">
                            <div class="row w-100 entry" data-index="0">
                                <div class="col-lg-12">
                                    <div class="col-lg-12">
                                        <div class="row">
                                            <div class="col-lg-11 user-data px-0">
                                                <div class="form-group">
                                                    <div class="input-group mb-md-0 mb-4">
                                                        <div class="col-6">
                                                            <label class="w-100 font-weight-bold">@lang('Region name')
                                                                <span class="text-danger">*</span></label>
                                                            <select required class="form-control "
                                                                name="shippingregions[0][region_name]">
                                                                <option value="">
                                                                    @lang('Select Region')
                                                                </option>
                                                                @foreach ($regions as $region)
                                                                    <option value="{{ $region->name }}">
                                                                        {{ $region->name }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </div>



                                                        <div class="col-6">
                                                            <label class="w-100 font-weight-bold">@lang('Shipping method')
                                                                <span class="text-danger">*</span></label>
                                                            <select required class="form-control "
                                                                name="shippingregions[0][shipping_method]">
                                                                <option value="">
                                                                    @lang('Select shipping method')
                                                                </option>
                                                                <option value="Air Freight (expedited)">
                                                                    @lang('Air Freight (expedited)')
                                                                </option>
                                                                <option value="Air Freight (Palletized)">
                                                                    @lang('Air Freight (Palletized)')
                                                                </option>
                                                                <option value="Ocean Freight">
                                                                    @lang('Ocean Freight')
                                                                </option>

                                                            </select>
                                                        </div>

                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-1  text-right align-self-center">
                                                <span class="input-group-btn">
                                                    <i class="las la-trash  deleteRegion"
                                                        style="font-size: 28px;cursor:pointer"></i>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="row">

                                        <div class="col-lg-12">

                                            <div class="ranges">
                                                <h6 class="mb-2">@lang('Ranges')
                                                    <button type="button"
                                                        class="position-relative btn btn-sm btn-outline-dark float-right addRange" style="right: -30px"><i
                                                            class="la la-fw la-plus"></i>@lang('Add New Range')
                                                    </button>
                                                </h6>
                                                {{-- <div class="card-body"> --}}
                                                <div class="range-addedField">
                                                    <div class="row w-100">

                                                        <div class="col-lg-11 user-data px-0">
                                                            <div class="form-group">
                                                                <div class="input-group mb-md-0 mb-4">
                                                                    <div class="col-4">

                                                                        <label
                                                                            class="w-100 font-weight-bold">@lang('From (Lb)')
                                                                            <span class="text-danger">*</span></label>
                                                                        <input name="shippingranges[0][0][from]"
                                                                            class="form-control" type="number"
                                                                            required placeholder="@lang('From')">

                                                                    </div>


                                                                    <div class="col-4">
                                                                        <label
                                                                            class="w-100 font-weight-bold">@lang('Up To (Lb)')
                                                                            <span class="text-danger">*</span></label>
                                                                        <input name="shippingranges[0][0][up_to]"
                                                                            type="number" class="form-control"
                                                                            type="number" required
                                                                            placeholder="@lang('Up To')">
                                                                    </div>

                                                                    <div class="col-4">
                                                                        <label
                                                                            class="w-100 font-weight-bold">@lang('Cost (USD)')
                                                                            <span class="text-danger">*</span></label>
                                                                        <input name="shippingranges[0][0][cost]"
                                                                            class="form-control" type="number"
                                                                            min="0" required
                                                                            step="0.00000000001"
                                                                            placeholder="@lang('Cost')">
                                                                    </div>



                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="col-lg-1  text-right align-self-center">
                                                            <span class="input-group-btn">
                                                                <i class="las la-trash deleteButton"
                                                                    style="font-size: 28px;cursor:pointer"></i>
                                                            </span>
                                                        </div>




                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <hr />
                                </div>
                            </div>
                        </div>
                    </div>


                </div>
            @endif

        </div>







        {{-- Handling fee --}}
        <div class="internal-wrapper">

            <div class="row">
                <div class="col-lg-12">

                    <h6 class="mb-2">@lang('Handling Fee')
                        <button type="button" class="btn btn-sm btn-outline-dark float-right addFees"><i
                                class="la la-fw la-plus"></i>@lang('Add New Fees')
                        </button>
                    </h6>
                    {{-- <div class="card-body"> --}}
                    <div class="addedField">
                        @if (old('fees'))
                            @foreach (old('fees') as $key => $fee)
                                <div class="row w-100">

                                    <div class="col-lg-11 user-data px-0">
                                        <div class="form-group">
                                            <div class="input-group mb-md-0 mb-4">
                                                <div class="col-4">
                                                    <label class="w-100 font-weight-bold">@lang('Country name')
                                                        <span class="text-danger">*</span></label>
                                                    <select required class="form-control "
                                                        name="fees[{{ $key }}][country_id]">
                                                        <option value="">
                                                            @lang('Select Country')
                                                        </option>
                                                        @foreach ($countries as $country)
                                                            <option @if ($fee['country_id'] == $country->id) selected @endif
                                                                value="{{ $country->id }}">
                                                                {{ $country->Name }}
                                                            </option>
                                                        @endforeach
                                                        <option value="999">
                                                            @lang('Rest Of The World')
                                                        </option>
                                                    </select>
                                                </div>


                                                <div class="col-4">
                                                    <label class="w-100 font-weight-bold">@lang('Payment Method')
                                                        <span class="text-danger">*</span></label>
                                                    <select required class="form-control "
                                                        name="fees[{{ $key }}][payment_method]">
                                                        <option value="">
                                                            @lang('Select Payment Method')
                                                        </option>
                                                        <option @if ($fee['payment_method'] === 'Credit Card') selected @endif
                                                            value="Credit Card">
                                                            @lang('Credit Card')
                                                        </option>
                                                        <option @if ($fee['payment_method'] === 'Wire Transfer') selected @endif
                                                            value="Wire Transfer">
                                                            @lang('Wire Transfer')
                                                        </option>
                                                    </select>
                                                </div>

                                                <div class="col-4">
                                                    <label class="w-100 font-weight-bold">@lang('Credit Card Processing %')
                                                        <span class="text-danger">*</span></label>
                                                    <input type="number" min=".01" max="100"
                                                        step=".01" required class="form-control"
                                                        name="fees[{{ $key }}][fee_value]"
                                                        value="{{ $fee['fee_value'] }}"
                                                        placeholder="@lang('Credit Card Processing %')" />
                                                </div>



                                            </div>
                                        </div>
                                    </div>


                                    <div class="col-lg-1  text-right align-self-center">
                                        <span class="input-group-btn">
                                            <i class="las la-trash deleteButton"
                                                style="font-size: 28px;cursor:pointer"></i>
                                        </span>
                                    </div>

                                </div>
                            @endforeach
                        @else
                            <div class="row w-100">

                                <div class="col-lg-11 user-data px-0">
                                    <div class="form-group">
                                        <div class="input-group mb-md-0 mb-4">
                                            <div class="col-4">
                                                <label class="w-100 font-weight-bold">@lang('Country name')
                                                    <span class="text-danger">*</span></label>
                                                <select required class="form-control " name="fees[0][country_id]">
                                                    <option value="">
                                                        @lang('Select Country')
                                                    </option>
                                                    @foreach ($countries as $country)
                                                        <option value="{{ $country->id }}">
                                                            {{ $country->Name }}</option>
                                                    @endforeach
                                                    <option value="999">
                                                        @lang('Rest Of The World')
                                                    </option>
                                                </select>
                                            </div>


                                            <div class="col-4">
                                                <label class="w-100 font-weight-bold">@lang('Payment Method')
                                                    <span class="text-danger">*</span></label>
                                                <select required class="form-control " name="fees[0][payment_method]">
                                                    <option value="">
                                                        @lang('Select Payment Method')
                                                    </option>
                                                    <option value="Credit Card">
                                                        @lang('Credit Card')
                                                    </option>
                                                    <option value="Wire Transfer">
                                                        @lang('Wire Transfer')</option>
                                                </select>
                                            </div>

                                            <div class="col-4">
                                                <label class="w-100 font-weight-bold">@lang('Credit Card Processing %')
                                                    <span class="text-danger">*</span></label>
                                                <input type="number" min=".01" max="100" step=".01"
                                                    required class="form-control" name="fees[0][fee_value]"
                                                    placeholder="@lang('Credit Card Processing %')" />
                                            </div>



                                        </div>
                                    </div>
                                </div>


                                <div class="col-lg-1  text-right align-self-center">
                                    <span class="input-group-btn">
                                        <i class="las la-trash deleteButton"
                                            style="font-size: 28px;cursor:pointer"></i>
                                    </span>
                                </div>

                            </div>
                        @endif

                    </div>
                </div>
            </div>

        </div>
        {{-- end of fees --}}

    </div>
@endif

@push('script')
    <script>
        (function($) {

            @if ($mode === 'edit')
                let shippingRegionIndex =
                    {{ old('shippingregions') ? count(old('shippingregions')) : count($event->shippingregions) }};

                // the following php code flatten the ranges arrays from the region and get the count of it
                let shippingRangeIndex =
                    {{ old('shippingranges')? count(old('shippingranges')): count(array_merge(...array_map(function ($value) {return $value['shipping_ranges'];}, $shippingregions->toArray()))) }};


                let feesIndex = {{ old('fees') ? count(old('fees')) : count($event->fees) }};
            @else
                let shippingRegionIndex = {{ old('shippingregions') ? count(old('shippingregions')) : 1 }};
                let shippingRangeIndex = {{ old('shippingranges') ? count(old('shippingranges')) : 1 }};
                let feesIndex = {{ old('fees') ? count(old('fees')) : 1 }};
            @endif








            $(document).on('click', '.addRegion', function() {

                var wrapper = $(this).closest('.internal-wrapper');

                var html = `
<div class="row w-100 entry" data-index="${shippingRegionIndex}">
                                            <div class="col-lg-12">
                                                <div class="col-lg-12">
                                                    <div class="row">
                                                        <div class="col-lg-11 user-data px-0">
                                                            <div class="form-group">
                                                                <div class="input-group mb-md-0 mb-4">
                                                                    <div class="col-6">
                                                                        <label
                                                                            class="w-100 font-weight-bold">@lang('Region name')
                                                                            <span
                                                                                class="text-danger">*</span></label>
                                                                        <select required
                                                                            class="form-control "
                                                                            name="shippingregions[${shippingRegionIndex}][region_name]">
                                                                            <option value="">
                                                                                @lang('Select Region')
                                                                            </option>
                                                                            @foreach ($regions as $region)
                                                                                <option
                                                                                    value="{{ $region->name }}">
                                                                                    {{ $region->name }}
                                                                                </option>
                                                                            @endforeach
                                                                        </select>
                                                                    </div>



                                                                    <div class="col-6">
                                                                        <label
                                                                            class="w-100 font-weight-bold">@lang('Shipping method')
                                                                            <span
                                                                                class="text-danger">*</span></label>
                                                                        <select required
                                                                            class="form-control "
                                                                            name="shippingregions[${shippingRegionIndex}][shipping_method]">
                                                                            <option value="">
                                                                                @lang('Select shipping method')
                                                                            </option>
                                                                            <option
                                                                                value="Air Freight (expedited)">
                                                                                @lang('Air Freight (expedited)')
                                                                            </option>
                                                                            <option
                                                                                value="Air Freight (Palletized)">
                                                                                @lang('Air Freight (Palletized)')
                                                                            </option>
                                                                            <option value="Ocean Freight">
                                                                                @lang('Ocean Freight')</option>

                                                                        </select>
                                                                    </div>

                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div
                                                            class="col-lg-1  text-right align-self-center">
                                                            <span class="input-group-btn">
                                                                <i class="las la-trash deleteRegion"
                                                                    style="font-size: 28px;cursor:pointer"></i>
                                                            </span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-12">
                                                <div class="row">

                                                    <div class="col-lg-12">

                                                        <div class="ranges">
                                                            <h6 class="mb-2">@lang('Ranges')
                                                                <button type="button"
                                                                    class="position-relative btn btn-sm btn-outline-dark float-right addRange" style="right: -30px"><i
                                                                        class="la la-fw la-plus"></i>@lang('Add New Range')
                                                                </button>
                                                            </h6>
                                                            {{-- <div class="card-body"> --}}
                                                            <div class="range-addedField">
                                                                <div class="row w-100">

                                                                    <div class="col-lg-11 user-data px-0">
                                                                        <div class="form-group">
                                                                            <div
                                                                                class="input-group mb-md-0 mb-4">
                                                                                <div class="col-4">

                                                                                    <label
                                                                                        class="w-100 font-weight-bold">@lang('From (Lb)')
                                                                                        <span
                                                                                            class="text-danger">*</span></label>
                                                                                    <input
                                                                                        name="shippingranges[${shippingRegionIndex}][0][from]"
                                                                                        class="form-control"
                                                                                        type="number"
                                                                                        required
                                                                                        placeholder="@lang('From')">

                                                                                </div>


                                                                                <div class="col-4">
                                                                                    <label
                                                                                        class="w-100 font-weight-bold">@lang('Up To (Lb)')
                                                                                        <span
                                                                                            class="text-danger">*</span></label>
                                                                                    <input
                                                                                        name="shippingranges[${shippingRegionIndex}][0][up_to]"
                                                                                        type="number"
                                                                                        class="form-control"
                                                                                        type="number"
                                                                                        required
                                                                                        placeholder="@lang('Up To')">
                                                                                </div>

                                                                                <div class="col-4">
                                                                                    <label
                                                                                        class="w-100 font-weight-bold">@lang('Cost (USD)')
                                                                                        <span
                                                                                            class="text-danger">*</span></label>
                                                                                    <input
                                                                                        name="shippingranges[${shippingRegionIndex}][0][cost]"
                                                                                        class="form-control"
                                                                                        type="number"
                                                                                        min="0"
                                                                                        required
                                                                                        step="0.00000000001"
                                                                                        placeholder="@lang('Cost')">
                                                                                </div>



                                                                            </div>
                                                                        </div>
                                                                    </div>

                                                                    <div
                                                                        class="col-lg-1  text-right align-self-center">
                                                                        <span class="input-group-btn">
                                                                            <i class="las la-trash deleteButton"
                                                                                style="font-size: 28px;cursor:pointer"></i>
                                                                        </span>
                                                                    </div>




                                                                </div>
                                                            </div>
                                                        </div>

                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-12">
                                                <hr />
                                            </div>
                                        </div>
                                    </div>
`

                wrapper.find('.addedField').append(html);

                shippingRegionIndex++


            })



            $(document).on("click", ".addRange", function() {

                var parentIndex = $(this).closest(".entry").attr('data-index');

                console.log(parentIndex)

                var wrapper = $(this).closest('.ranges')

                var html = `   <div class="row w-100">

<div class="col-lg-11 user-data px-0">
<div class="form-group">
<div class="input-group mb-md-0 mb-4">
<div class="col-4">

<label
class="w-100 font-weight-bold">@lang('From (Lb)')
<span class="text-danger">*</span></label>
<input name="shippingranges[${parentIndex}][${shippingRangeIndex}][from]"
class="form-control" type="number" required
placeholder="@lang('From')">

</div>


<div class="col-4">
<label
class="w-100 font-weight-bold">@lang('Up To (Lb)')
<span class="text-danger">*</span></label>
<input name="shippingranges[${parentIndex}][${shippingRangeIndex}][up_to]"
class="form-control" type="number"  required
placeholder="@lang('Up To')">
</div>

<div class="col-4">
<label
class="w-100 font-weight-bold">@lang('Cost (USD)')
<span class="text-danger">*</span></label>
<input name="shippingranges[${parentIndex}][${shippingRangeIndex}][cost]"
class="form-control" type="number" min="0"  step="0.00000000001" required
placeholder="@lang('Cost')">
</div>



</div>
</div>
</div>


<div class="col-lg-1  text-right align-self-center">
<span class="input-group-btn">
<i class="las la-trash deleteButton"
style="font-size: 28px;cursor:pointer"></i>
</span>
</div>

</div>`

                wrapper.find('.range-addedField').append(html);

                shippingRangeIndex++
            })




            $(document).on("click", ".addFees", function() {

                var wrapper = $(this).closest('.internal-wrapper');

                var html = `    <div class="row w-100">

<div class="col-lg-11 user-data px-0">
<div class="form-group">
<div class="input-group mb-md-0 mb-4">
<div class="col-4">
<label
    class="w-100 font-weight-bold">@lang('Country name')
    <span class="text-danger">*</span></label>
<select required class="form-control "
    name="fees[${feesIndex}][country_id]">
    <option value="">@lang('Select Country')
    </option>
    @foreach ($countries as $country)
        <option value="{{ $country->id }}">
            {{ $country->Name }}</option>
    @endforeach
    <option value="999">@lang('Rest Of The World')
    </option>
</select>
</div>


<div class="col-4">
<label
    class="w-100 font-weight-bold">@lang('Payment Method')
    <span class="text-danger">*</span></label>
<select required class="form-control "
    name="fees[${feesIndex}][payment_method]">
    <option value="">@lang('Select Payment Method')
    </option>
    <option value="Credit Card">
        @lang('Credit Card')
    </option>
    <option value="Wire Transfer">
        @lang('Wire Transfer')</option>
</select>
</div>

<div class="col-4">
<label
    class="w-100 font-weight-bold">@lang('Credit Card Processing %')
    <span class="text-danger">*</span></label>
<input type="number" min=".01"
    max="100" step=".01" required
    class="form-control"
    name="fees[${feesIndex}][fee_value]"
    placeholder="@lang('Credit Card Processing %')" />
</div>



</div>
</div>
</div>


<div class="col-lg-1  text-right align-self-center">
<span class="input-group-btn">
<i class="las la-trash deleteButton"
style="font-size: 28px;cursor:pointer"></i>
</span>
</div>

</div>`

                wrapper.find('.addedField').append(html);

                feesIndex++
            })



            $(document).on('click', ".deleteButton", function() {
                $(this).closest('.row').remove();
            })

            $(document).on("click", ".deleteRegion", function() {

                $(this).closest('.entry').remove()
            })

        })(jQuery)
    </script>
@endpush
