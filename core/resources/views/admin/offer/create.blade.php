@extends('admin.layouts.app')
@section('panel')
    <form action="{{ route('admin.offer.store') }}" method="POST" enctype="multipart/form-data">
        @csrf


        <div class="align-items-center d-flex mb-30 justify-content-between">
            <h6 class="page-title">Add Product</h6>
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
                                                <h5 class="font-weight-bold mb-3">@lang('Image') <span
                                                        class="text-danger">*</span></h5>
                                            </div>
                                            <div class="thumb mb-0 w-100">
                                                <div class="avatar-preview w-100">
                                                    <div class="profilePicPreview w-100 rounded"
                                                        style="background-image: url('{{ asset('custom/images/images-placeholder.png') }}');border:dashed 2px #AAAAAA;box-shadow:unset">


                                                        <div style="height: 0px;">
                                                            <input type="file" name="image" class="profilePicUpload"
                                                                id="image" accept=".png, .jpg, .jpeg" required />
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
                                                name="name" value="{{ old('name') }}" required />
                                        </div>
                                    </div>
                                    <div class="col-sm-12 col-xl-4 col-lg-6">
                                        <div class="form-group">
                                            <label class="w-100 font-weight-bold">@lang('Offer Sheet') <span
                                                    class="text-danger">*</span></label>
                                            <select name="offer_sheet_id" id="eventSelect" class="form-control" required>
                                                <option value="">@lang('Select One')</option>
                                                @foreach ($offer_sheets as $offer_sheet)
                                                    <option value="{{ $offer_sheet->id }}"
                                                        @if (old('offer_sheet_id') == $offer_sheet->id) selected @endif
                                                        @if (isset($offer_sheet_id) && $offer_sheet_id == $offer_sheet->id) selected @endif>
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
                                                    @if (old('merchant_id') == '0') selected @endif>@lang('Admin')
                                                </option>
                                                @foreach ($merchants as $merchant)
                                                    <option value="{{ $merchant->id }}"
                                                        @if (old('merchant_id') == $merchant->id) selected @endif>
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
                                            <textarea style="min-height: 135px" rows="5" class="form-control border-radius-5" name="short_description">{{ old('short_description') }}</textarea>
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

                        <div class="row">
                            <div class="col-lg-12">

                                <h5 class="font-weight-bold mb-3">@lang('Sizes Available')
                                    <button type="button" class="btn btn-sm btn-outline-dark float-right addSize"><i
                                            class="la la-fw la-plus"></i>@lang('Add New')
                                    </button>
                                </h5>
                                {{-- <div class="card-body"> --}}

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
                                                                    <input value={{ $oldSize['weight'] }} type="number"
                                                                        readonly class="weight_input"
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
                                                                        step="0.01" placeholder="@lang('Price (USD)')"
                                                                        required value="{{ $oldSize['price'] }}" />
                                                                </div>
                                                            </div>

                                                            <div class="col-lg-3">
                                                                <div class="form-group">
                                                                    <label
                                                                        class="w-100 font-weight-bold">@lang('Total Price USD (+Packaging)')</label>
                                                                    <div
                                                                        class="text-center mt-2 total_packaging_price_label">
                                                                        ${{ $oldSize['price'] * $oldSize['weight'] }}
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
                                        <div class="row w-100">

                                            <div class="col-lg-11 user-data px-0">
                                                <div class="form-group">
                                                    <div class="input-group mb-md-0 mb-4">
                                                        <div class="col-lg-3">
                                                            <div class="form-group">
                                                                <label class="w-100 font-weight-bold">@lang('Unit Size')
                                                                    <span class="text-danger">*</span></label>
                                                                <select name="sizes[0][unit]"
                                                                    class="form-control size_unit" required>
                                                                    <option value="">@lang('Select One')</option>
                                                                    {{-- <option value="{{ 0 }}"
                                                                    @if (old('merchant_id') == '0') selected @endif>
                                                                    @lang('Admin')
                                                                </option> --}}

                                                                    @foreach ($sizes as $size)
                                                                        <option value="{{ $size->id }}">
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
                                                                <input type="number" readonly class="weight_input"
                                                                    style="width:100%;border:none;box-shadow:unset"
                                                                    name="sizes[0][weight]" />
                                                            </div>
                                                        </div>

                                                        <div class="col-lg-3">
                                                            <div class="form-group">
                                                                <label
                                                                    class="w-100 font-weight-bold">@lang('Price/lb (USD)')</label>
                                                                <input type="number" name="sizes[0][price]"
                                                                    class="form-control price_input" min="0.00"
                                                                    step="0.01" placeholder="@lang('Price (USD)')"
                                                                    required />
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
                                    @endif

                                </div>
                            </div>
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
                            <textarea rows="3" class="form-control border-radius-5 nicEdit" name="long_description">{{ old('long_description') }}</textarea>
                        </div>
                    </div>
                </div>
            </div>


            {{-- Specifications --}}
            <div class="col-lg-12 mb-3 wrapper">
                <div class="card">
                    <div class="card-body">


                        <div class="row">
                            <div class="col-lg-12">

                                <h5 class="font-weight-bold mb-3">@lang('Specifications')
                                    <button type="button" class="btn btn-sm btn-outline-dark float-right addSpec"><i
                                            class="la la-fw la-plus"></i>@lang('Add New')
                                    </button>
                                </h5>
                                {{-- <div class="card-body"> --}}
                                <div id="specFields" class="addedField">

                                    {{-- status --}}
                                    <div class="row w-100">
                                        <div class="col-lg-11 user-data px-0">
                                            <div class="form-group">
                                                <div class="input-group mb-md-0 mb-4">
                                                    <div class="col-lg-4">
                                                        <input name="specification[0][name]" class="form-control"
                                                            type="text"
                                                            value="{{ old('specification')[0]['name'] ?? 'Status' }}"
                                                            required placeholder="@lang('Field Name')">
                                                    </div>

                                                    <div class="col-lg-4">
                                                        <input value="{{ old('specification')[0]['value'] ?? '' }}"
                                                            name="specification[0][value]" class="form-control"
                                                            type="text" required placeholder="@lang('Value')">
                                                    </div>




                                                </div>
                                            </div>
                                        </div>
                                    </div>


                                    {{-- Units Available --}}
                                    <div class="row w-100">
                                        <div class="col-lg-11 user-data px-0">
                                            <div class="form-group">
                                                <div class="input-group mb-md-0 mb-4">
                                                    <div class="col-lg-4">
                                                        <input name="specification[1][name]" class="form-control"
                                                            type="text"
                                                            value="{{ old('specification')[1]['name'] ?? 'Units Available' }}"
                                                            required placeholder="@lang('Field Name')">
                                                    </div>
                                                    {{-- done --}}

                                                    <div class="col-lg-4">
                                                        <input value="{{ old('specification')[1]['value'] ?? '' }}"
                                                            name="specification[1][value]" class="form-control"
                                                            type="text" required placeholder="@lang('Value')">
                                                    </div>




                                                </div>
                                            </div>
                                        </div>
                                    </div>


                                    {{-- Origin --}}
                                    <div class="row w-100">
                                        <div class="col-lg-11 user-data px-0">
                                            <div class="form-group">
                                                <div class="input-group mb-md-0 mb-4">
                                                    <div class="col-lg-4">
                                                        <input name="specification[2][name]" class="form-control"
                                                            type="text"
                                                            value="{{ old('specification')[2]['name'] ?? 'Origin' }}"
                                                            required placeholder="@lang('Field Name')">
                                                    </div>
                                                    {{-- done --}}

                                                    <div class="col-lg-4">
                                                        <input value="{{ old('specification')[2]['value'] ?? '' }}"
                                                            name="specification[2][value]" class="form-control"
                                                            type="text" required placeholder="@lang('Value')">
                                                    </div>




                                                </div>
                                            </div>
                                        </div>
                                    </div>


                                    {{-- Producer --}}
                                    <div class="row w-100">
                                        <div class="col-lg-11 user-data px-0">
                                            <div class="form-group">
                                                <div class="input-group mb-md-0 mb-4">
                                                    <div class="col-lg-4">
                                                        <input name="specification[3][name]" class="form-control"
                                                            type="text"
                                                            value="{{ old('specification')[3]['name'] ?? 'Producer' }}"
                                                            required placeholder="@lang('Field Name')">
                                                    </div>
                                                    {{-- done --}}

                                                    <div class="col-lg-4">
                                                        <input name="specification[3][value]"
                                                            value="{{ old('specification')[3]['value'] ?? '' }}"
                                                            class="form-control" type="text" required
                                                            placeholder="@lang('Value')">
                                                    </div>




                                                </div>
                                            </div>
                                        </div>
                                    </div>


                                    {{-- Region --}}
                                    <div class="row w-100">
                                        <div class="col-lg-11 user-data px-0">
                                            <div class="form-group">
                                                <div class="input-group mb-md-0 mb-4">
                                                    <div class="col-lg-4">
                                                        <input name="specification[4][name]" class="form-control"
                                                            type="text"
                                                            value="{{ old('specification')[4]['name'] ?? 'Region' }}"
                                                            required placeholder="@lang('Field Name')">
                                                    </div>
                                                    {{-- done --}}

                                                    <div class="col-lg-4">
                                                        <input value="{{ old('specification')[4]['value'] ?? '' }}"
                                                            name="specification[4][value]" class="form-control"
                                                            type="text" required placeholder="@lang('Value')">
                                                    </div>




                                                </div>
                                            </div>
                                        </div>
                                    </div>


                                    {{-- Altitude --}}
                                    <div class="row w-100">
                                        <div class="col-lg-11 user-data px-0">
                                            <div class="form-group">
                                                <div class="input-group mb-md-0 mb-4">
                                                    <div class="col-lg-4">
                                                        <input name="specification[5][name]" class="form-control"
                                                            type="text"
                                                            value="{{ old('specification')[5]['name'] ?? 'Altitude' }} "
                                                            required placeholder="@lang('Field Name')">
                                                    </div>
                                                    {{-- done --}}

                                                    <div class="col-lg-4">
                                                        <input value="{{ old('specification')[5]['value'] ?? '' }}"
                                                            name="specification[5][value]" class="form-control"
                                                            type="text" required placeholder="@lang('Value')">
                                                    </div>




                                                </div>
                                            </div>
                                        </div>
                                    </div>


                                    {{-- Variety --}}
                                    <div class="row w-100">
                                        <div class="col-lg-11 user-data px-0">
                                            <div class="form-group">
                                                <div class="input-group mb-md-0 mb-4">
                                                    <div class="col-lg-4">
                                                        <input name="specification[6][name]" class="form-control"
                                                            type="text"
                                                            value="{{ old('specification')[6]['name'] ?? 'Variety' }} "
                                                            required placeholder="@lang('Field Name')">
                                                    </div>
                                                    {{-- done --}}

                                                    <div class="col-lg-4">
                                                        <input value="{{ old('specification')[6]['value'] ?? '' }}"
                                                            name="specification[6][value]" class="form-control"
                                                            type="text" required placeholder="@lang('Value')">
                                                    </div>




                                                </div>
                                            </div>
                                        </div>
                                    </div>


                                    {{-- Processing Method --}}
                                    <div class="row w-100">
                                        <div class="col-lg-11 user-data px-0">
                                            <div class="form-group">
                                                <div class="input-group mb-md-0 mb-4">
                                                    <div class="col-lg-4">
                                                        <input name="specification[7][name]" class="form-control"
                                                            type="text"
                                                            value="{{ old('specification')[7]['name'] ?? 'Processing Method' }}"
                                                            required placeholder="@lang('Field Name')">
                                                    </div>
                                                    {{-- done --}}

                                                    <div class="col-lg-4">
                                                        <input value="{{ old('specification')[7]['value'] ?? '' }}"
                                                            name="specification[7][value]" class="form-control"
                                                            type="text" required placeholder="@lang('Value')">
                                                    </div>




                                                </div>
                                            </div>
                                        </div>
                                    </div>


                                    {{-- Drying --}}
                                    <div class="row w-100">
                                        <div class="col-lg-11 user-data px-0">
                                            <div class="form-group">
                                                <div class="input-group mb-md-0 mb-4">
                                                    <div class="col-lg-4">
                                                        <input name="specification[8][name]" class="form-control"
                                                            type="text"
                                                            value="{{ old('specification')[8]['name'] ?? 'Drying' }}"
                                                            required placeholder="@lang('Field Name')">
                                                    </div>
                                                    {{-- done --}}

                                                    <div class="col-lg-4">
                                                        <input value="{{ old('specification')[8]['value'] ?? '' }}"
                                                            name="specification[8][value]" class="form-control"
                                                            type="text" required placeholder="@lang('Value')">
                                                    </div>




                                                </div>
                                            </div>
                                        </div>
                                    </div>


                                    {{-- Grade --}}
                                    <div class="row w-100">
                                        <div class="col-lg-11 user-data px-0">
                                            <div class="form-group">
                                                <div class="input-group mb-md-0 mb-4">
                                                    <div class="col-lg-4">
                                                        <input name="specification[9][name]" class="form-control"
                                                            type="text"
                                                            value="{{ old('specification')[9]['name'] ?? 'Grade' }}"
                                                            required placeholder="@lang('Field Name')">
                                                    </div>
                                                    {{-- done --}}

                                                    <div class="col-lg-4">
                                                        <input value="{{ old('specification')[9]['value'] ?? '' }}"
                                                            name="specification[9][value]" class="form-control"
                                                            type="text" required placeholder="@lang('Value')">
                                                    </div>




                                                </div>
                                            </div>
                                        </div>
                                    </div>


                                    {{-- Screen --}}
                                    <div class="row w-100">
                                        <div class="col-lg-11 user-data px-0">
                                            <div class="form-group">
                                                <div class="input-group mb-md-0 mb-4">
                                                    <div class="col-lg-4">
                                                        <input name="specification[10][name]" class="form-control"
                                                            type="text"
                                                            value="{{ old('specification')[10]['name'] ?? 'Screen' }}"
                                                            required placeholder="@lang('Field Name')">
                                                    </div>
                                                    {{-- done --}}

                                                    <div class="col-lg-4">
                                                        <input value="{{ old('specification')[10]['value'] ?? '' }}"
                                                            name="specification[10][value]" class="form-control"
                                                            type="text" required placeholder="@lang('Value')">
                                                    </div>




                                                </div>
                                            </div>
                                        </div>
                                    </div>


                                    {{-- Harvest --}}
                                    <div class="row w-100">
                                        <div class="col-lg-11 user-data px-0">
                                            <div class="form-group">
                                                <div class="input-group mb-md-0 mb-4">
                                                    <div class="col-lg-4">
                                                        <input name="specification[11][name]" class="form-control"
                                                            type="text"
                                                            value="{{ old('specification')[11]['name'] ?? 'Harvest' }}"
                                                            required placeholder="@lang('Field Name')">
                                                    </div>
                                                    {{-- done --}}

                                                    <div class="col-lg-4">
                                                        <input value="{{ old('specification')[11]['value'] ?? '' }}"
                                                            name="specification[11][value]" class="form-control"
                                                            type="text" required placeholder="@lang('Value')">
                                                    </div>




                                                </div>
                                            </div>
                                        </div>
                                    </div>


                                    {{-- Tasting Notes --}}
                                    <div class="row w-100">
                                        <div class="col-lg-11 user-data px-0">
                                            <div class="form-group">
                                                <div class="input-group mb-md-0 mb-4">
                                                    <div class="col-lg-4">
                                                        <input name="specification[12][name]" class="form-control"
                                                            type="text"
                                                            value="{{ old('specification')[12]['name'] ?? 'Tasting Notes' }}"
                                                            required placeholder="@lang('Field Name')">
                                                    </div>
                                                    {{-- done --}}

                                                    <div class="col-lg-4">
                                                        <input value="{{ old('specification')[12]['value'] ?? '' }}"
                                                            name="specification[12][value]" class="form-control"
                                                            type="text" required placeholder="@lang('Value')">
                                                    </div>




                                                </div>
                                            </div>
                                        </div>
                                    </div>


                                    {{-- Location --}}
                                    <div class="row w-100">
                                        <div class="col-lg-11 user-data px-0">
                                            <div class="form-group">
                                                <div class="input-group mb-md-0 mb-4">
                                                    <div class="col-lg-4">
                                                        <input name="specification[13][name]" class="form-control"
                                                            type="text"
                                                            value="{{ old('specification')[13]['name'] ?? 'Location' }}"
                                                            required placeholder="@lang('Field Name')">
                                                    </div>
                                                    {{-- done --}}

                                                    <div class="col-lg-4">
                                                        <input value="{{ old('specification')[13]['value'] ?? '' }}"
                                                            name="specification[13][value]" class="form-control"
                                                            type="text" required placeholder="@lang('Value')">
                                                    </div>




                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- Diff / 100 Lb --}}
                                    <div class="row w-100">
                                        <div class="col-lg-11 user-data px-0">
                                            <div class="form-group">
                                                <div class="input-group mb-md-0 mb-4">
                                                    <div class="col-lg-4">
                                                        <input name="specification[14][name]" class="form-control"
                                                            type="text"
                                                            value="{{ old('specification')[14]['name'] ?? 'Diff / 100 Lb' }}"
                                                            required placeholder="@lang('Field Name')">
                                                    </div>
                                                    {{-- done --}}

                                                    <div class="col-lg-4">
                                                        <input value="{{ old('specification')[14]['value'] ?? '' }}"
                                                            name="specification[14][value]" class="form-control"
                                                            type="text" required placeholder="@lang('Value')">
                                                    </div>




                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- NY KCH24 @ 185 --}}
                                    <div class="row w-100">
                                        <div class="col-lg-11 user-data px-0">
                                            <div class="form-group">
                                                <div class="input-group mb-md-0 mb-4">
                                                    <div class="col-lg-4">
                                                        <input name="specification[14][name]" class="form-control"
                                                            type="text"
                                                            value="{{ old('specification')[15]['name'] ?? 'NY KCH24 @ 185' }}"
                                                            required placeholder="@lang('Field Name')">
                                                    </div>
                                                    {{-- done --}}

                                                    <div class="col-lg-4">
                                                        <input value="{{ old('specification')[15]['value'] ?? '' }}"
                                                            name="specification[14][value]" class="form-control"
                                                            type="text" required placeholder="@lang('Value')">
                                                    </div>




                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- Score --}}
                                    <div class="row w-100">
                                        <div class="col-lg-11 user-data px-0">
                                            <div class="form-group">
                                                <div class="input-group mb-md-0 mb-4">
                                                    <div class="col-lg-4">
                                                        <input name="specification[14][name]" class="form-control"
                                                            type="text"
                                                            value="{{ old('specification')[16]['name'] ?? 'Score' }}"
                                                            required placeholder="@lang('Field Name')">
                                                    </div>
                                                    {{-- done --}}

                                                    <div class="col-lg-4">
                                                        <input value="{{ old('specification')[16]['value'] ?? '' }}"
                                                            name="specification[14][value]" class="form-control"
                                                            type="text" required placeholder="@lang('Value')">
                                                    </div>




                                                </div>
                                            </div>
                                        </div>
                                    </div>



                                    @if (old('specification'))
                                        @foreach ($filterExcludedPremanent as $key => $spec)
                                            <div class="row w-100">
                                                <div class="col-lg-11 user-data px-0">
                                                    <div class="form-group">
                                                        <div class="input-group mb-md-0 mb-4">
                                                            <div class="col-lg-4">
                                                                <input
                                                                    name="specification[@json(\App\Http\Controllers\Admin\offerController::getIndexByName(old('specification'), $spec['name']))][name]"
                                                                    class="form-control" type="text"
                                                                    value="{{ $spec['name'] }}" required
                                                                    placeholder="@lang('Field Name')">
                                                            </div>
                                                            {{-- done --}}

                                                            <div class="col-lg-4">
                                                                <input value="{{ $spec['value'] }}"
                                                                    name="specification[@json(\App\Http\Controllers\Admin\offerController::getIndexByName(old('specification'), $spec['name']))][value]"
                                                                    class="form-control" type="text" required
                                                                    placeholder="@lang('Value')">
                                                            </div>

                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    @endif


                                </div>
                            </div>
                        </div>


                    </div>


                </div>
            </div>
            {{-- end of Specifications --}}

            {{-- <div class="p-2">
                <button type="submit" class="btn btn--primary box--shadow1 w-100">Create
                    Product</button>
            </div> --}}




        </div>




    </form>
@endsection


{{-- @push('breadcrumb-plugins')
    <button type="submit" href="{{ route('admin.offer.index') }}"
        class="btn btn-sm btn--primary box--shadow1 text--small"><i class="la la-fw la-backward"></i> @lang('Go Back')
    </button>
@endpush --}}

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
            var sizesIndex = 1
            var specIndex = {{ old('specification') ? count(old('specification')) : 17 }};
            var specCount = 8;
            var eventSizesArray = [];


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

            <div class="col-lg-1   align-self-center">
                <span class="input-group-btn">
                    <i class="las la-trash deleteButton"
                        style="font-size: 28px;cursor:pointer"></i>
                </span>
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
                        placeholder="@lang('Price/lb (USD)')"  min="0.00"  step="0.01"
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



            $(document).ready(function() {



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

                let $eventSelect = $('#eventSelect');
                $eventSelect.change(function() {
                    var selectedEventId = $(this).val();
                    if (!selectedEventId) return;
                    $.ajax({
                        url: '/admin/offer_sheet/' + selectedEventId + '/sizes',
                        type: 'GET',
                        success: function(response) {
                            if (response.status === "true") {
                                var sizes = response.sizes;
                                eventSizesArray = response.sizes

                                // Clear existing options
                                $('select[name="sizes[0][unit]"]').empty();
                                $('select[name="sizes[0][weight]"]').empty();

                                // Populate options dynamically based on response
                                sizes.forEach(function(size, index) {
                                    $('select[name="sizes[0][unit]"]').append($(
                                        '<option>', {
                                            value: size.id,
                                            text: size.size,
                                            selected: index === 0 ?
                                                "selected" : undefined
                                        }));

                                    if (index === 0)
                                        $('input[name="sizes[0][weight]"]').val(size
                                            .weight_LB);


                                });


                                // remove all childern execpt first one
                                sizesIndex = 1

                                $(".sizes_available_entries_container").children(
                                    ":not(:first-child)").remove();


                                //

                            } else {
                                console.error("Error: " + response.message);
                            }
                        },
                        error: function(xhr, status, error) {
                            console.error(error);
                        }
                    });
                });
                var event = new Event('change');
                $eventSelect.get(0).dispatchEvent(event);
            });





            $(document).on('click', ".deleteButton", function() {
                var numOfChild = $(this).closest('.sizes_available_entries_container')?.children()?.length;
                if (numOfChild === 1) {
                    iziToast.error({
                        message: 'You can\'t delete this size',
                        position: "topRight"
                    });
                    return;
                }
                $(this).closest('.row').remove();
            })

            $('#specFields .row.w-100').each(function() {
                const rowText = $(this).find('.input-group input:first-of-type').val();

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
                    rowText === "NY KCH24 @ 185" ||
                    rowText === "Diff / 100 Lb" ||
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
