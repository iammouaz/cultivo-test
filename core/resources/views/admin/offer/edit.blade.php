@extends('admin.layouts.app')
@php
    $product = $offer;
@endphp
@section('panel')
    <form action="{{ route('admin.offer.update', $product->id) }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="align-items-center d-flex mb-30 justify-content-between">
            <h6 class="page-title">Update Product</h6>
            <div class="text-right  mt-3 ">
                <button type="submit" class="btn btn-primary">@lang('Submit')</button>
            </div>
        </div>

        <div class="row">
            <div class="col-12 mb-3">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-4">

                                <div class="payment-method-item">
                                    <div class="payment-method-header">

                                        <div class="form-group w-100">
                                            <div class="mb-2 w-100">
                                                <h5 class="font-weight-bold mb-3">@lang('Image')</h5>
                                            </div>
                                            <div class="thumb mb-0 w-100">
                                                <div class="avatar-preview w-100">
                                                    <div class="profilePicPreview w-100 rounded"
                                                        style="background-image: url('{{ getImage(imagePath()['product']['path'] . '/' . $product->photo, imagePath()['product']['size'], false, 'sm') }}');border:dashed 2px #AAAAAA;box-shadow:unset">


                                                        <div style="height: 0px;">
                                                            <input type="file" name="image" class="profilePicUpload"
                                                                id="image" accept=".png, .jpg, .jpeg" />
                                                            <label for="image"
                                                                style="position: absolute;top:70%;left:50%;transform:translate(-50%)">
                                                                <div class="btn  btn-light box--shadow1 text--small">
                                                                    Select Image
                                                                </div>
                                                            </label>
                                                        </div>



                                                    </div>




                                                </div>




                                            </div>
                                            <div class="mt-2 font-small text-center">@lang('images between 1500 pixels and 2500 pixels wide')</div>



                                        </div>
                                    </div>
                                </div>


                            </div>
                            <div class="col-8">

                                <div class="row">
                                    <div class="col-sm-12 col-xl-4 col-lg-6">
                                        <div class="form-group">
                                            <label class="w-100 font-weight-bold">@lang('Name') <span
                                                    class="text-danger">*</span></label>
                                            <input type="text" class="form-control " placeholder="@lang('Product Name')"
                                                name="name" value="{{ old('name', $product->name) }}" required />
                                        </div>
                                    </div>
                                    <div class="col-sm-12 col-xl-4 col-lg-6">
                                        <div class="form-group">
                                            <label class="w-100 font-weight-bold">@lang('Offer Sheet') <span
                                                    class="text-danger">*</span></label>
                                            <input type="hidden" name="offer_sheet_id"
                                                value="{{ $product->offer_sheet_id }}" />
                                            <select disabled value="{{ $product->offer_sheet_id }}" class="form-control"
                                                required>
                                                <option value="">@lang('Select One')</option>
                                                @foreach ($offer_sheets as $offer_sheet)
                                                    <option value="{{ $offer_sheet->id }}"
                                                        {{ $product->offer_sheet_id == $offer_sheet->id ? 'Selected' : '' }}>
                                                        {{ $offer_sheet->name . ' ' . $offer_sheet->sname }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-sm-12 col-xl-4 col-lg-6">
                                        <div class="form-group">
                                            <label class="w-100 font-weight-bold">@lang('Merchant') <span
                                                    class="text-danger">*</span></label>
                                            <select name="merchant_id" class="form-control" required>
                                                <option value="">@lang('Select One')</option>
                                                <option value="{{ 0 }}"
                                                    @if (old('merchant_id') !== null) @if (old('merchant_id') == 0)
                                                    selected @endif
                                                @elseif($product->merchant_id == 0) selected @endif>
                                                    @lang('Admin')

                                                </option>

                                                @foreach ($merchants as $merchant)
                                                    <option value="{{ $merchant->id }}"
                                                        @if (old('merchant_id') !== null) @if (old('merchant_id') == $merchant->id) selected @endif
                                                    @elseif($product->merchant_id == $merchant->id) selected @endif>
                                                        {{ $merchant->firstname }} {{ $merchant->lastname }}</option>
                                                @endforeach

                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-12">
                                        <div class="form-group">
                                            <label class="font-weight-bold">@lang('Short Description')</label>
                                            <textarea style="min-height: 135px" rows="5" class="form-control border-radius-5" name="short_description">{{ old('short_description', $product->short_description) }}</textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>





            {{-- sizes available --}}
            <div class="col-lg-12 mb-3 wrapper">
                <div class="card">
                    <div class="card-body">
                        <h5 class="font-weight-bold mb-3">@lang('Sizes Available')
                            <button type="button" class="btn btn-sm btn-outline-dark float-right addSize"><i
                                    class="la la-fw la-plus"></i>@lang('Add New')
                            </button>
                        </h5>
                        <div class="addedField sizes_available_entries_container">
                            @if (old('sizes'))
                                @foreach (old('sizes') as $key => $oldSize)
                                    <div class="row w-100">

                                        <div class="col-lg-11 user-data px-0">
                                            <div class="form-group">
                                                <div class="input-group mb-md-0 mb-4">
                                                    <div class="col-lg-3">
                                                        <div class="form-group">
                                                            <label class="w-100 font-weight-bold">@lang('Unit Size')
                                                                <span class="text-danger">*</span></label>
                                                            <select name="sizes[{{ $key }}][unit]"
                                                                class="form-control size_unit" required>
                                                                <option value="">@lang('Select One')</option>
                                                                {{-- <option value="{{ 0 }}"
                                                                    @if (old('merchant_id') == '0') selected @endif>
                                                                    @lang('Admin')
                                                                </option> --}}
                                                                @foreach ($sizes as $size)
                                                                    <option
                                                                        @if ($size->id == $oldSize['unit']) selected @endif
                                                                        value="{{ $size->id }}">
                                                                        {{-- @if (old('merchant_id') == $merchant->id) selected @endif> --}}
                                                                        {{ $size->size }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>

                                                    <div class="col-lg-3">
                                                        <div class="form-group">
                                                            <label class="w-100 font-weight-bold">@lang('Weight (Lb)')
                                                                <span class="text-danger">*</span></label>



                                                            <h6></h6>
                                                            <input value={{ $oldSize['weight'] }} type="number" readonly
                                                                class="weight_input"
                                                                style="width:100%;border:none;box-shadow:unset"
                                                                name="sizes[{{ $key }}][weight]" />
                                                        </div>
                                                    </div>

                                                    <div class="col-lg-3">
                                                        <div class="form-group">
                                                            <label
                                                                class="w-100 font-weight-bold">@lang('Price/lb (USD)')</label>
                                                            <input type="number"
                                                                name="sizes[{{ $key }}][price]"
                                                                class="form-control price_input" min="0.00"
                                                                step="0.01" placeholder="@lang('Price (USD)')" required
                                                                value="{{ $oldSize['price'] }}"
                                                                oninput="this.value = parseFloat(this.value).toFixed(2)" />
                                                        </div>
                                                    </div>

                                                    <div class="col-lg-3">
                                                        <div class="form-group">
                                                            <label
                                                                class="w-100 font-weight-bold">@lang('Total Price USD (+Packaging)')</label>
                                                            <div class="text-center mt-2 total_packaging_price_label">
                                                                ${{ $oldSize['price'] * $oldSize['weight'] + \App\Models\Size::find($key)->additional_cost }}
                                                            </div>
                                                        </div>
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
                                <div class="row">
                                    <div class="col-lg-12">


                                        {{-- <div class="card-body"> --}}

                                        @if ($product->prices)
                                            @foreach ($product->prices as $price)
                                                <div class="row w-100">
                                                    <input type="text" value="{{ $price->id }}"
                                                        class="d-none priceId" />
                                                    <div class="col-lg-11 user-data px-0">
                                                        <div class="form-group">
                                                            <div class="input-group mb-md-0 mb-4">
                                                                <div class="col-lg-3">
                                                                    <div class="form-group">
                                                                        <label
                                                                            class="w-100 font-weight-bold">@lang('Unit Size')
                                                                            <span class="text-danger">*</span></label>
                                                                        <select name="sizes[{{ $loop->iteration }}][unit]"
                                                                            class="form-control size_unit" required>
                                                                            <option value="">@lang('Select One')
                                                                            </option>
                                                                            {{-- <option value="{{ 0 }}"
                                                                            @if (old('merchant_id') == '0') selected @endif>
                                                                            @lang('Admin')
                                                                        </option> --}}
                                                                            @foreach ($sizes as $size)
                                                                                <option value="{{ $size->id }}"
                                                                                    @if ($size->id == $price->size_id) selected @endif>
                                                                                    {{ $size->size }}
                                                                                </option>
                                                                            @endforeach
                                                                        </select>
                                                                    </div>
                                                                </div>

                                                                <div class="col-lg-3">
                                                                    <div class="form-group">
                                                                        <label
                                                                            class="w-100 font-weight-bold">@lang('Weight (Lb)')
                                                                            <span class="text-danger">*</span></label>

                                                                        <input type="number" readonly
                                                                            class="weight_input"
                                                                            style="width:100%;border:none;box-shadow:unset"
                                                                            name="sizes[{{ $loop->iteration }}][weight]"
                                                                            value="{{ $price->size->weight_LB ?? null }}" />


                                                                    </div>
                                                                </div>

                                                                <div class="col-lg-3">
                                                                    <div class="form-group">
                                                                        <label
                                                                            class="w-100 font-weight-bold">@lang('Price/lb (USD)')</label>
                                                                        <input type="number" min="0.00"
                                                                            step="0.01"
                                                                            name="sizes[{{ $loop->iteration }}][price]"
                                                                            class="form-control price_input"
                                                                            placeholder="@lang('Price (USD)')"
                                                                            value="{{ number_format($price->price, 2, '.', '') }}"
                                                                            required />

                                                                    </div>
                                                                </div>

                                                                <div class="col-lg-3">
                                                                    <div class="form-group">
                                                                        <label
                                                                            class="w-100 font-weight-bold">@lang('Total Price USD (+Packaging)')</label>
                                                                        <div
                                                                            class="text-center mt-2 total_packaging_price_label">
                                                                            ${{ $price->product_total_price }}
                                                                        </div>
                                                                    </div>
                                                                </div>




                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="col-lg-1  text-right align-self-center">
                                                        <span id="delete-box" class="input-group-btn">
                                                            <i class="las la-trash deleteButton"
                                                                style="font-size: 28px;cursor:pointer"></i>
                                                        </span>
                                                    </div>



                                                </div>
                                            @endforeach
                                        @endif

                                    </div>
                                </div>
                            @endif
                        </div>

                    </div>


                </div>
            </div>
            {{-- end of sizes available --}}




            <div class="col-12 mb-3">
                <div class="card">
                    <div class="card-body">
                        <div class="form-group">
                            <div class="mb-2 w-100">
                                <h5 class="font-weight-bold mb-3">@lang('Story')</h5>
                            </div>
                            <textarea rows="3" class="form-control border-radius-5 nicEdit" name="long_description">{{ old('long_description', $product->long_description) }}</textarea>
                        </div>
                    </div>
                </div>
            </div>


            {{-- Specifications --}}
            <div class="col-lg-12 mb-3 wrapper">
                <div class="card">
                    <div id="specFields" class="card-body">
                        <h5 class="font-weight-bold mb-3">@lang('Specifications')
                            <button type="button" class="btn btn-sm btn-outline-dark float-right addSpec"><i
                                    class="la la-fw la-plus"></i>@lang('Add New')
                            </button>
                        </h5>
                        <div class="addedField">
                            @if (old('specification'))
                                @foreach (old('specification') as $key => $spec)
                                    <div class="row w-100">
                                        <div class="col-lg-11 user-data px-0">
                                            <div class="form-group">
                                                <div class="input-group mb-md-0 mb-4">
                                                    <div class="col-lg-4">
                                                        <input name="specification[{{ $key }}][name]"
                                                            class="form-control" type="text"
                                                            value="{{ $spec['name'] }}" required
                                                            placeholder="@lang('Field Name')">
                                                    </div>
                                                    {{-- done --}}

                                                    <div class="col-lg-4">
                                                        <input value="{{ $spec['value'] }}"
                                                            name="specification[{{ $key }}][value]"
                                                            class="form-control" type="text" required
                                                            placeholder="@lang('Value')">
                                                    </div>

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            @else
                                <div class="row">
                                    <div class="col-lg-12">


                                        {{-- <div class="card-body"> --}}

                                        @if ($product->offer_specification)
                                            @foreach ($product->offer_specification as $spec)
                                                <div class="row w-100">

                                                    <div class="col-lg-11 user-data px-0">
                                                        <div class="form-group">
                                                            <div class="input-group mb-md-0 mb-4">
                                                                <div class="col-lg-4">
                                                                    <input
                                                                        name="specification[{{ $loop->iteration }}][name]"
                                                                        class="form-control" type="text"
                                                                        value="{{ $spec->spec_key }}" required
                                                                        placeholder="@lang('Field Name')">
                                                                </div>

                                                                <div class="col-lg-4">
                                                                    <input
                                                                        name="specification[{{ $loop->iteration }}][value]"
                                                                        class="form-control" type="text"
                                                                        value="{{ $spec->Value }}" required
                                                                        placeholder="@lang('Field Value')">
                                                                </div>




                                                            </div>
                                                        </div>
                                                    </div>




                                                </div>
                                            @endforeach
                                        @endif

                                    </div>
                                </div>
                            @endif
                        </div>



                    </div>


                </div>
            </div>
            {{-- end of Specifications --}}

            {{-- <div class="p-2">
                <button type="submit" class="btn btn--primary box--shadow1 w-100">Update
                    Product</button>
            </div> --}}


        </div>




    </form>
@endsection


@push('breadcrumb-plugins')
    <a href="{{ route('admin.offer.index') }}" class="btn btn-sm btn--primary box--shadow1 text--small"><i
            class="la la-fw la-backward"></i> @lang('Go Back') </a>
@endpush

@push('style')
    <style>
        .payment-method-item .payment-method-header .thumb .avatar-edit {
            bottom: auto;
            top: 175px;
        }


        .row.align-items-center.mb-30.justify-content-between {
            display: none;
        }
    </style>
@endpush

@push('script-lib')
    <script src="{{ asset('assets/admin/js/vendor/datepicker.min.js') }}"></script>
    <script src="{{ asset('assets/admin/js/vendor/datepicker.en.js') }}"></script>
@endpush

@push('script')
    <script>
        (function($) {
            "use strict";
            var sizesIndex =
                @if (old('sizes'))
                    {{ count(old('sizes')) }}
                @else
                    `{{ $product->prices ? count($product->prices) : 0 }}`
                @endif ;


            var eventSizesArray = @json($sizes->toArray());
            sizesIndex = parseInt(sizesIndex);
            sizesIndex = sizesIndex ? sizesIndex + 1 : 1;


            var specIndex =
                @if (old('specification'))
                    {{ count(old('specification')) }}
                @else
                    `{{ $product->specification ? count($product->specification) : 0 }}`
                @endif ;;
            specIndex = parseInt(specIndex);
            specIndex = specIndex ? specIndex + 1 : 1;

            var specCount = 8;
            // Create start date
            var start = new Date(),
                prevDay,
                startHours = 0;

            // 09:00 AM
            start.setHours(0);
            start.setMinutes(0);

            // If today is Saturday or Sunday set 10:00 AM
            if ([6, 0].indexOf(start.getDay()) != -1) {
                start.setHours(10);
                startHours = 10
            }

            // Add an event listener for the 'blur' event
            $(document).on("blur", ".price_input", function() {
                $(this).val(Number($(this).val()).toFixed(2)).trigger('change');

            })

            const updatePackagingPrice = (current_row) => {
                const price_input = current_row.find('.price_input');

                const current_size = current_row.find('.size_unit');

                const current_weight_lb = eventSizesArray.find(item => item.id == current_size.val())
                    ?.weight_LB || 0

                const additional_cost = eventSizesArray.find(item => item.id == current_size.val())
                    ?.additional_cost || 0

                const total_price = (Number(price_input.val()) * Number(current_weight_lb)) +
                    Number(additional_cost)

                current_row.find('.total_packaging_price_label').text(`$${total_price.toFixed(2)}`);
            }

            $(document).on('change', ".price_input", function() {
                const current_row = $(this).closest('.row');

                updatePackagingPrice(current_row)
            });


            $(document).on("change", ".size_unit", function() {

                const current_row = $(this).closest('.row');

                updatePackagingPrice(current_row)

                const weight_input = current_row.find('.weight_input');

                const current_weight_lb = eventSizesArray.find(item => item.id == $(this).val())
                    .weight_LB

                weight_input.val(current_weight_lb)

            })


            // date and time picker
            $('#startDateTime').datepicker({
                timepicker: true,
                language: 'en',
                dateFormat: 'dd-mm-yyyy',
                startDate: start,
                minHours: startHours,
                maxHours: 23,
                onSelect: function(fd, d, picker) {
                    // Do nothing if selection was cleared
                    if (!d) return;

                    var day = d.getDay();

                    // Trigger only if date is changed
                    if (prevDay != undefined && prevDay == day) return;
                    prevDay = day;

                    // If chosen day is Saturday or Sunday when set
                    // hour value for weekends, else restore defaults
                    if (day == 6 || day == 0) {
                        picker.update({
                            minHours: 0,
                            maxHours: 23
                        })
                    } else {
                        picker.update({
                            minHours: 0,
                            maxHours: 23
                        })
                    }
                }
            });

            // date and time picker
            $('#endDateTime').datepicker({
                timepicker: true,
                language: 'en',
                dateFormat: 'dd-mm-yyyy',
                startDate: start,
                minHours: startHours,
                maxHours: 23,
                onSelect: function(fd, d, picker) {
                    // Do nothing if selection was cleared
                    if (!d) return;

                    var day = d.getDay();

                    // Trigger only if date is changed
                    if (prevDay != undefined && prevDay == day) return;
                    prevDay = day;

                    // If chosen day is Saturday or Sunday when set
                    // hour value for weekends, else restore defaults
                    if (day == 6 || day == 0) {
                        picker.update({
                            minHours: 0,
                            maxHours: 23
                        })
                    } else {
                        picker.update({
                            minHours: 0,
                            maxHours: 23
                        })
                    }
                }
            });


            $('input[name=currency]').on('input', function() {
                $('.currency_symbol').text($(this).val());
            });

            $('.addUserData').on('click', function() {
                var html = `
                    <div class="col-md-12 user-data">
                        <div class="form-group">
                            <div class="input-group mb-md-0 mb-4">
                                <div class="col-md-4">
                                    <input name="specification[${specCount}][name]" class="form-control" type="text" required placeholder="@lang('Field Name')">
                                </div>
                                <div class="col-md-4">
                                    <input name="specification[${specCount}][value]" class="form-control" type="text" required placeholder="@lang('Field Value')">
                                </div>
                                <div class="col-md-1">
                                    <input name="specification[${specCount}][is_display]" class="form-check-input" type="radio" value="1" checked required placeholder="@lang('View In Product Card')">
                                    <label class="form-check-label" for="flexRadioDefault1">display</label>
                                </div>
                                <div class="col-md-1">
                                    <input name="specification[${specCount}][is_display]" class="form-check-input" type="radio" value="0"  required placeholder="@lang('NO')">
                                    <label class="form-check-label" for="flexRadioDefault1">no display</label>
                                </div>
                                <div class="col-md-2 mt-md-0 mt-2 text-right">
                                    <span class="input-group-btn">
                                        <button class="btn btn--danger btn-lg removeBtn w-100" type="button">
                                            <i class="fa fa-times"></i>
                                        </button>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>`;
                $('.addedField').append(html);
                specCount += 1;
            });


            $(document).on('click', '.addSpec', function() {

                var wrapper = $(this).closest('.wrapper');

                var html = `
                <div class="row w-100">

<div class="col-lg-11 user-data px-0">
    <div class="form-group">
        <div class="input-group mb-md-0 mb-4">
            <div class="col-lg-4">
                <input name="specification[${specIndex}][name]" class="form-control"
                    type="text" value="" required
                    placeholder="@lang('Field Name')">
            </div>

            <div class="col-lg-4">
                <input name="specification[${specIndex}][value]" class="form-control"
                    type="text"  placeholder="@lang('Field Value')">
            </div>

            
        </div>
    </div>
</div>




</div>
`

                wrapper.find('.addedField').append(html);

                specIndex++


            })





            $(document).on('click', '.addSize', function() {

                var wrapper = $(this).closest('.wrapper');

                var html = `
<div class="row w-100">

<div class="col-lg-11 user-data px-0">
<div class="form-group">
<div class="input-group mb-md-0 mb-4">
<div class="col-lg-3">
<div class="form-group">
    <label class="w-100 font-weight-bold">@lang('Unit Size') <span
            class="text-danger">*</span></label>
    <select name="sizes[${sizesIndex}][unit]" class="form-control size_unit" required>
        <option value="">@lang('Select One')</option>

${eventSizesArray.map(item => `
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                               <option value="${item.id}">
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        ${item.size}
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                               </option>`).join("")}
    </select>
</div>
</div>

<div class="col-lg-3">
<div class="form-group">
    <label class="w-100 font-weight-bold">@lang('Weight (Lb)') <span
            class="text-danger">*</span></label>

            <input type="number" readonly class="weight_input"
                                                style="width:100%;border:none;box-shadow:unset"
                                                name="sizes[${sizesIndex}][weight]" />


</div>
</div>

<div class="col-lg-3">
<div class="form-group">
    <label class="w-100 font-weight-bold">@lang('Price/lb (USD)')</label>
    <input type="number" name="sizes[${sizesIndex}][price]" class="form-control price_input"
        placeholder="@lang('Price (USD)')"  min="0.00"  step="0.01"
        value="{{ old('price') }}" required />

</div>
</div>


<div class="col-lg-3">
<div class="form-group">
 <label
     class="w-100 font-weight-bold">@lang('Total Price USD (+Packaging)')</label>
 <div class="text-center mt-2 total_packaging_price_label">
     $0</div>
</div>
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
`

                wrapper.find('.addedField').append(html);

                sizesIndex++

            })




            $(document).on('click', ".deleteButton", function() {
                var deleteButton = $(this);
                var numOfChild = deleteButton.closest('.sizes_available_entries_container')?.children()?.length;
                if (numOfChild === 1) {
                    iziToast.error({
                        message: 'You can\'t delete this size',
                        position: "topRight"
                    });
                    return;
                }

                var rowToDelete = deleteButton.closest('.row');
                var deleteBox = deleteButton.closest('#delete-box');

                var priceId = deleteButton.closest('.row').find('.priceId').val();

                if (priceId) {
                    deleteBox.html(
                        '<div class="spinner-border text-primary" role="status"><span class="sr-only">Loading...</span></div>'
                    );

                    $.ajax({
                        url: '/admin/product/prices/' + priceId + '/check_price_delete',
                        method: 'GET',
                        success: function(response) {
                            if (response?.status === 'true') {
                                rowToDelete.remove();
                            } else {
                                iziToast.error({
                                    title: 'Error',
                                    message: 'Cannot be deleted',
                                    position: 'topRight'
                                });
                            }
                        },
                        error: function(xhr, status, error) {
                            iziToast.error({
                                title: 'Error',
                                message: 'Something went wrong',
                                position: 'topRight'
                            });
                            deleteBox.html(
                                '<i class="las la-trash deleteButton" style="font-size: 28px;cursor:pointer"></i>'
                            );
                        },
                        complete: function() {
                            deleteBox.html(
                                '<i class="las la-trash deleteButton" style="font-size: 28px;cursor:pointer"></i>'
                            );
                        }
                    });
                } else {
                    rowToDelete.remove();
                }
            });


            $('#specFields .row.w-100').each(function() {
                const rowText = $(this).find('.input-group input:first-of-type').val();
                console.log($(this))
                debugger
                if (
                    rowText === 'Origin' ||
                    rowText === 'Grade' ||
                    rowText === 'Units Available' ||
                    rowText === 'Location' ||
                    rowText === 'Tasting Notes' ||
                    rowText === 'Status' ||
                    rowText === 'Producer' ||
                    rowText === 'Region' ||
                    rowText === 'Processing Method' ||
                    rowText === 'Variety' ||
                    rowText === 'Screen' ||
                    rowText === "Diff / 100 Lb" ||
                    rowText === "NY KCH24 @ 185" ||
                    rowText === "Score"
                ) {
                    $(this).find('.input-group input').eq(0).prop('readonly', true);
                } else {

                    $(this).find('.input-group').append(
                        '<div class="col-lg-1"><i class="las la-trash deleteSpec" style="font-size: 28px; cursor: pointer;"></i></div>'
                    );
                }
            });

            $(document).on('click', '.deleteSpec', function() {
                $(this).closest('.row').remove();
            });



            $(document).on('click', '.removeBtn', function() {
                $(this).closest('.user-data').remove();
            });

            @if (old('currency'))
                $('input[name=currency]').trigger('input');
            @endif

            $("[name=schedule]").on('change', function(e) {
                var schedule = e.target.value;

                if (schedule != 1) {
                    $("[name=started_at]").attr('disabled', true);
                    $('.started_at').css('display', 'none');
                } else {
                    $("[name=started_at]").attr('disabled', false);
                    $('.started_at').css('display', 'block');
                }
            }).change();

        })(jQuery);
    </script>
@endpush
