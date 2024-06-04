@extends('admin.layouts.app')
@php
    $base_url = config('app.url') . '/offer_sheet/';
@endphp


{{-- /*** Todo this need to be refactored since it being used in several places ***/ --}}
@php
    function convertBooleanValues($inputArray)
    {
        foreach ($inputArray as $key => $value) {
            // Convert "true" to true and "false" to false
            if ($value === 'true') {
                $inputArray[$key] = true;
            } elseif ($value === 'false') {
                $inputArray[$key] = false;
            }
        }
        return $inputArray;
    }
    function valueRuterner($colorValue)
    {
        $defaultColorValue = [
            'color' => 'rgba(248, 35, 35, 0)',
            'is_no_color' => true,
            'is_with_glass_effect' => false,
        ];
        return convertBooleanValues(json_decode($colorValue ?? json_encode($defaultColorValue), true));
    }

    $hero_text_color = valueRuterner(null);
    $hero_primary_button_color = valueRuterner(null);
    $hero_outlined_button_color = valueRuterner(null);
    $hero_image_overlay = valueRuterner(null);
@endphp


@section('panel')

    @push('style-lib')
        <link rel="stylesheet" href="{{ asset('assets/admin/css/switch.css') }}">
    @endpush

    <div class="row">


        <div class="col-lg-12">

            <form action="{{ route('admin.offer_sheet.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h6>Create Offer Sheet</h6>
                    <div class="d-flex " style="gap: 12px">
                        <a href="{{ route('admin.offer.index') }}"> <button type="button"
                                class="btn btn-md btn-outline-dark float-right"><i
                                    class="la la-eye px-1"></i>@lang('Preview Event')
                            </button>
                        </a>

                        <a href="{{ route('admin.product.create') }}">
                            <button type="button" class="btn btn-md  btn-secondary float-right"><i
                                    class="la la-plus px-1"></i>@lang('Add Product')
                            </button>
                        </a>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-4">
                        <div class="card">
                            <div class="card-body">
                                <div class="payment-method-item">
                                    <div class="payment-method-header">

                                        <div class="form-group w-100">
                                            <div class="mb-2 w-100">
                                                <h5 class="font-weight-bold mb-3">@lang('Banner Image') <span
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


                                        <div class="w-100">
                                            <div class="form-group mb-3 d-flex align-items-center">
                                                <x-color-picker :value="$hero_text_color" title="Text Color"
                                                    name="hero_text_color"></x-color-picker>
                                            </div>
                                            <div class="form-group mb-3 d-flex align-items-center">
                                                <x-color-picker :value="$hero_primary_button_color" title="Contained Button Color"
                                                    name="hero_primary_button_color"></x-color-picker>
                                            </div>
                                            <div class="form-group mb-3 d-flex align-items-center">
                                                <x-color-picker :value="$hero_outlined_button_color" title="Outlined Button Color"
                                                    name="hero_outlined_button_color"></x-color-picker>
                                            </div>
                                            <div class="form-group mb-3 d-flex align-items-center">
                                                <x-color-picker :value="$hero_image_overlay" title="Image Overlay"
                                                    name="hero_image_overlay"></x-color-picker>
                                            </div>
                                        </div>


                                        <div class="form-group w-100">
                                            <div class="mb-2 w-100">
                                                <h5 class="font-weight-bold mb-3">@lang('Banner Logo') <span
                                                        class="text-danger">*</span></h5>
                                            </div>
                                            <div class="thumb mb-0 w-100">
                                                <div class="avatar-preview w-100">
                                                    <div class="profilePicPreview w-100 rounded"
                                                        style="background-image: url('{{ asset('custom/images/images-placeholder.png') }}');border:dashed 2px #AAAAAA;box-shadow:unset">


                                                        <div style="height: 0px;">
                                                            <input type="file" name="banner_logo"
                                                                class="profilePicUpload" id="banner_logo"
                                                                accept=".png, .jpg, .jpeg" required />
                                                            <label for="banner_logo"
                                                                style="position: absolute;top:70%;left:50%;transform:translate(-50%)">
                                                                <div class="btn  btn-light box--shadow1 text--small">
                                                                    Select Image
                                                                </div>
                                                            </label>
                                                        </div>



                                                    </div>




                                                </div>




                                            </div>
                                            <div class="mt-2 font-small text-center">@lang('images between 180 pixels and 480 pixels wide')</div>



                                        </div>



                                        <div class="form-group w-100">
                                            <div class="mb-2 w-100">
                                                <h5 class="font-weight-bold mb-3">@lang('Card Logo') <span
                                                        class="text-danger">*</span></h5>
                                            </div>
                                            <div class="thumb mb-0 w-100">
                                                <div class="avatar-preview w-100">
                                                    <div class="profilePicPreview w-100 rounded"
                                                        style="background-image: url('{{ asset('custom/images/images-placeholder.png') }}');border:dashed 2px #AAAAAA;box-shadow:unset">


                                                        <div style="height: 0px;">
                                                            <input type="file" name="card_logo" class="profilePicUpload"
                                                                id="card_logo" accept=".png, .jpg, .jpeg" required />
                                                            <label for="card_logo"
                                                                style="position: absolute;top:70%;left:50%;transform:translate(-50%)">
                                                                <div class="btn  btn-light box--shadow1 text--small">
                                                                    Select Image
                                                                </div>
                                                            </label>
                                                        </div>



                                                    </div>




                                                </div>




                                            </div>
                                            <div class="mt-2 font-small text-center">@lang('images between 480 pixels and 640 pixels wide')</div>



                                        </div>

                                        <div class="py-1 w-100">
                                            <hr class="w-100" style="border-size:2px" />
                                        </div>



                                        <h6 class="mb-2">@lang('Buttons')</h6>
                                        <label class="w-100 font-weight-bold mt-2">@lang('Visible Button Options')</label>
                                        <div class="d-flex flex-column" style="gap: 12px">
                                            <div class="slide-switch d-flex align-items-center ">
                                                <label class="switch">
                                                    <input checked readonly name="order" id="order" type="checkbox"
                                                        value="1">
                                                    <span class="slider round"></span>
                                                </label>
                                                <label class="switch-label px-2" for="order">Add To Order</label>
                                            </div>

                                            <div class="slide-switch d-flex align-items-center ">
                                                <label class="switch">
                                                    <input readonly name="offer" id="offer" type="checkbox"
                                                        value="1">
                                                    <span class="slider round"></span>
                                                </label>
                                                <label class="switch-label px-2" for="offer">Make Offer</label>
                                            </div>

                                            <div class="slide-switch d-flex align-items-center ">
                                                <label class="switch">
                                                    <input name="sample" id="sample" type="checkbox" value="1">
                                                    <span class="slider round"></span>
                                                </label>
                                                <label class="switch-label px-2" for="sample">Add Sample</label>
                                            </div>

                                        </div>




                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>


                    <div class="col-lg-8">




                        {{-- New offer sheet --}}
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="card">
                                    <div class="card-body">

                                        <div class="w-100">
                                            <h5 class="font-weight-bold mb-3">@lang('New Offer sheet')
                                                <a href="{{ route('admin.offer.index') }}"> <button type="button"
                                                        class="btn btn-md btn-outline-dark float-right"><i
                                                            class="la la-eye px-1"></i>@lang('All Products')
                                                    </button>
                                                </a>
                                            </h5>
                                        </div>


                                        <div class="row">
                                            <div class="col-lg-12">
                                                <div class="form-group">
                                                    <label class="w-100 font-weight-bold">@lang('Name') <span
                                                            class="text-danger">*</span></label>
                                                    <input id="name" type="text" class="form-control "
                                                        placeholder="@lang('Event Name')" name="name"
                                                        value="{{ old('name') }}" required />
                                                </div>
                                            </div>




                                            <div class="col-lg-12">
                                                <div class="form-group">
                                                    <select class="origin" name="origin[]">
                                                        @foreach ($origins as $origin)
                                                            <option value="{{ $origin->id }}">{{ $origin->name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>


                                            <div class="col-lg-12">
                                                <div class="form-group">
                                                    <label class="font-weight-bold">@lang('Description')<span
                                                            class="text-danger">*</span></label>
                                                    <textarea rows="4" class="form-control border-radius-5" required name="description">{{ old('description') }}</textarea>
                                                </div>
                                            </div>


                                            <div class="col-lg-12">
                                                <div class="form-group">
                                                    <label class="w-100 font-weight-bold">@lang('Customize your Offer Sheet Url')</label>
                                                    <div class="d-flex align-items-center">
                                                        <!-- Use d-flex and align-items-center to align label and input horizontally -->
                                                        <label
                                                            class="mr-2  w-50 font-weight-bold">{{ $base_url }}</label>
                                                        <!-- Add margin-right to separate label from input -->
                                                        <input id="url" type="text" class="form-control w-50"
                                                            name="url" value="{{ old('url') ? old('url') : '' }}"
                                                            required pattern="[a-z0-9\-]+"
                                                            title="Please enter a valid URL starting with '{{ $base_url }}/'">
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-lg-12">
                                                <div class="form-group">
                                                    <label class="font-weight-bold">@lang('Order Notifications To* (Add Emails Comma Spereated)')<span
                                                            class="text-danger">*</span></label>
                                                    <textarea rows="4" class="form-control border-radius-5" required name="emails">{{ old('emails') }}</textarea>
                                                </div>
                                            </div>

                                        </div>








                                    </div>
                                </div>
                            </div>
                        </div>
                        {{-- end of New offer sheet --}}






                        {{-- Sizes available --}}




                        <div class="row mt-4 wrapper">
                            <div class="col-lg-12">
                                <div class="card">
                                    <div class="card-body">

                                        <div class="w-100">
                                            <h5 class="font-weight-bold mb-3">@lang('Sizes available')</h5>
                                        </div>



                                        <div class="row">
                                            <div class="col-lg-12">

                                                <h6 class="mb-2">@lang('Sizes')
                                                    <button type="button"
                                                        class="btn btn-sm btn-outline-dark float-right addSize"><i
                                                            class="la la-fw la-plus"></i>@lang('New Size')
                                                    </button>
                                                </h6>
                                                {{-- <div class="card-body"> --}}
                                                <div class="addedField radiobox_item_type">
                                                    @if (old('sizes'))
                                                        @foreach (old('sizes') as $key => $size)
                                                            <div class="row w-100">

                                                                <div class="col-lg-11 user-data px-0">
                                                                    <div class="form-group">
                                                                        <div class="input-group mb-md-0 mb-4">
                                                                            <div class="col-lg-3">
                                                                                <label
                                                                                    class="w-100 font-weight-bold">@lang('Size')
                                                                                    <span class="text-danger">*</span>
                                                                                </label>
                                                                                <input required
                                                                                    name="sizes[{{ $key }}][size]"
                                                                                    value="{{ $size['size'] }}"
                                                                                    class="form-control" type="text"
                                                                                    placeholder="@lang('Size')">
                                                                            </div>

                                                                            <div class="col-lg-3">
                                                                                <label class="w-100 font-weight-bold">
                                                                                    @lang('Weight (LB)')
                                                                                    <span class="text-danger">*</span>
                                                                                </label>
                                                                                <input required step="0.001"
                                                                                    name="sizes[{{ $key }}][weight]"
                                                                                    class="form-control" type="number"
                                                                                    placeholder="@lang('Weight (LB)')"
                                                                                    value="{{ $size['weight'] }}">
                                                                            </div>


                                                                            <div class="col-lg-4">
                                                                                <label class="w-100 font-weight-bold"
                                                                                    style="text-wrap:nowrap">
                                                                                    @lang('Addional Packaging Cost (USD)')
                                                                                    <span class="text-danger">*</span>
                                                                                </label>
                                                                                <input
                                                                                    {{ isset($size['is_sample']) && $size['is_sample'] ? 'disabled="disabled"' : 'required' }}
                                                                                    step="0.001"
                                                                                    name="sizes[{{ $key }}][additional_cost]"
                                                                                    class="form-control additional_cost_input"
                                                                                    type="number"
                                                                                    value="{{ $size['additional_cost'] ?? 0 }}">
                                                                            </div>

                                                                            <div class="col-lg-2 text-center">

                                                                                <label class="w-100 font-weight-bold "
                                                                                    style="margin-bottom: 15px">
                                                                                    @lang('Is Sample?')
                                                                                </label>

                                                                                <input
                                                                                    style="width: 15px;height:15px !important"
                                                                                    class="form-check-input position-relative m-0 is_sample_input"
                                                                                    name="sizes[0][is_sample]"
                                                                                    type="checkbox"
                                                                                    {{ isset($size['is_sample']) && $size['is_sample'] ? 'checked' : '' }}
                                                                                    {{-- value="{{ isset($size['is_sample']) ? $size['is_sample'] : '' }}"  --}} />
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
                                                                            <label
                                                                                class="w-100 font-weight-bold">@lang('Size')
                                                                                <span class="text-danger">*</span>
                                                                            </label>
                                                                            <input required name="sizes[0][size]"
                                                                                class="form-control" type="text"
                                                                                placeholder="@lang('Size')">
                                                                        </div>

                                                                        <div class="col-lg-3">
                                                                            <label class="w-100 font-weight-bold">
                                                                                @lang('Weight (LB)')
                                                                                <span class="text-danger">*</span>
                                                                            </label>
                                                                            <input required step="0.001"
                                                                                name="sizes[0][weight]"
                                                                                class="form-control" type="number"
                                                                                placeholder="@lang('Weight (LB)')">
                                                                        </div>

                                                                        <div class="col-lg-4">
                                                                            <label class="w-100 font-weight-bold"
                                                                                style="text-wrap: nowrap">
                                                                                @lang('Additional Packaging Cost (USD)')

                                                                            </label>
                                                                            <input required step="0.001"
                                                                                name="sizes[0][additional_cost]"
                                                                                class="form-control additional_cost_input"
                                                                                type="number" placeholder=""
                                                                                value="">
                                                                        </div>

                                                                        <div class="col-lg-2 text-center">

                                                                            <label class="w-100 font-weight-bold "
                                                                                style="margin-bottom: 15px">
                                                                                @lang('Is Sample?')
                                                                            </label>

                                                                            <input
                                                                                style="width: 15px;height:15px !important"
                                                                                id="sizes-radio"
                                                                                class="form-check-input position-relative m-0 is_sample_input"
                                                                                name="sizes[0][is_sample]" type="radio"
                                                                                value="1" />

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
                        </div>
                        {{-- end of Sizes available --}}











                        {{-- Shipping --}}
                        <div class="row mt-4 wrapper">
                            <div class="col-lg-12">
                                <div class="card">
                                    <x-regions-input :regions="$regions" :countries="$countries" />
                                    <div class="p-2">
                                        <button id="submit-button" type="submit"
                                            class="btn  btn--primary box--shadow1 w-100">Create
                                            offer
                                            sheet</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        {{-- end of Shipping --}}






                    </div>
                </div>


            </form>
        </div>
    </div>
@endsection


{{-- @push('breadcrumb-plugins')
    <a href="{{ route('admin.offer_sheet.index') }}" class="btn btn-sm btn--primary box--shadow1 text--small"><i
            class="la la-fw la-backward"></i> @lang('Go Back') </a>
@endpush --}}

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



<!-- multiSelect style -->
@push('style')
    <style>
        .card-header {
            padding: 0.30rem 1rem;
        }

        h5 {
            line-height: 1.8;
        }

        .card-footer {
            margin-top: 100px;
        }

        .multi-select-container {
            display: block;
            position: relative;
        }

        .multi-select-menu {
            position: absolute;
            left: 0;
            top: 0.8em;
            z-index: 1;
            float: left;
            min-width: 100%;
            background: #fff;
            margin: 1em 0;
            border: 1px solid #aaa;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.2);
            display: none;
            max-height: 200px;
            overflow-y: overlay;
        }

        .multi-select-menuitem {
            display: block;
            font-size: 0.875em;
            padding: 0.6em 1em 0.6em 30px;
            white-space: nowrap;
        }

        .multi-select-menuitem--titled:before {
            display: block;
            font-weight: bold;
            content: attr(data-group-title);
            margin: 0 0 0.25em -20px;
        }

        .multi-select-menuitem--titledsr:before {
            display: block;
            font-weight: bold;
            content: attr(data-group-title);
            border: 0;
            clip: rect(0 0 0 0);
            height: 1px;
            margin: -1px;
            overflow: hidden;
            padding: 0;
            position: absolute;
            width: 1px;
        }

        .multi-select-menuitem+.multi-select-menuitem {
            padding-top: 0;
        }

        .multi-select-presets {
            border-bottom: 1px solid #ddd;
        }

        .multi-select-menuitem input {
            position: absolute;
            margin-top: 0.25em;
            margin-left: -20px;
        }

        .multi-select-button {
            display: inline-block;
            font-size: 0.875em;
            padding: 0.2em 0.6em;
            max-width: 16em;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            vertical-align: -0.5em;
            background-color: #fff;
            border: 1px solid #aaa;
            border-radius: 4px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.2);
            cursor: default;
        }

        .multi-select-button:after {
            content: "";
            display: inline-block;
            width: 0;
            height: 0;
            border-style: solid;
            border-width: 0.4em 0.4em 0 0.4em;
            border-color: #999 transparent transparent transparent;
            margin-left: 0.4em;
            vertical-align: 0.1em;
        }

        .multi-select-container--open .multi-select-menu {
            display: block;
        }

        .multi-select-container--open .multi-select-button:after {
            border-width: 0 0.4em 0.4em 0.4em;
            border-color: transparent transparent #999 transparent;
        }

        .multi-select-container--positioned .multi-select-menu {
            /* Avoid border/padding on menu messing with JavaScript width calculation */
            box-sizing: border-box;
        }

        .multi-select-container--positioned .multi-select-menu label {
            /* Allow labels to line wrap when menu is artificially narrowed */
            white-space: normal;
        }
    </style>
@endpush



@push('script')
    <script>
        (function($) {
            "use strict";
            var sizeIndex = {{ old('sizes') ? count(old('sizes')) : 1 }};


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

            $('#maxendDateTime').datepicker({
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





            $(document).on('click', '.addSize', function() {

                var wrapper = $(this).closest('.wrapper');

                var html = `
                <div class="row w-100">

<div class="col-lg-11 user-data px-0">
    <div class="form-group">
        <div class="input-group mb-md-0 mb-4">
            <div class="col-lg-3">
                <label
                    class="w-100 font-weight-bold">@lang('Size')
                    <span class="text-danger">*</span>
                </label>
                <input required name="sizes[${sizeIndex}][size]"
                    class="form-control" type="text"
                    placeholder="@lang('Size')">
            </div>

            <div class="col-lg-3">
                <label class="w-100 font-weight-bold">
                    @lang('Weight (LB)')
                    <span class="text-danger">*</span>
                </label>
                <input required name="sizes[${sizeIndex}][weight]"
                    class="form-control" type="number" step="0.001"
                    placeholder="@lang('Weight (LB)')">
            </div>

            <div class="col-lg-4">
                <label class="w-100 font-weight-bold"
                    style="text-wrap: nowrap">
                    @lang('Additional Packaging Cost (USD)')

                </label>
                <input required step="0.001"
                    name="sizes[${sizeIndex}][additional_cost]"
                    class="form-control additional_cost_input" type="number"
                    placeholder="" value="">
            </div>


            <div class="col-lg-2 text-center">

                <label class="w-100 font-weight-bold "
                    style="margin-bottom: 15px">
                    @lang('Is Sample?')
                </label>

                <input id="sizes-radio" style="width: 15px;height:15px !important"
                    class="form-check-input position-relative m-0 is_sample_input"
                    name="sizes[${sizeIndex}][is_sample]" type="radio"
                    value="1" />

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


                sizeIndex++
            })











            $(document).on("change", ".is_sample_input", function() {
                const container = $(this).closest('.user-data');
                const additional_cost_input = container.find('.additional_cost_input')
                $('.additional_cost_input').attr('disabled', false);
                $('.additional_cost_input').attr("required", true);
                additional_cost_input.prop('disabled', true);
                additional_cost_input.removeAttr("required");


            })




            $(document).on('click', ".deleteButton", function() {
                $(this).closest('.row').remove();
            })



            $(document).on('click', '.removeBtn', function() {
                $(this).closest('.user-data').remove();
            });



            $(document).ready(function() {
                $('.origin').select2({
                    placeholder: "Origins",
                });
            });



            $('#practice').on('change', function() {
                if (this.value == 1) {
                    $("select[name='event_type']").val('m_cultivo_event');
                    $("select[name='event_type']").prop('disabled', 'disabled');
                } else {
                    $("select[name='event_type']").prop('disabled', false);
                }
            });
        })(jQuery);
    </script>
    <script type="text/javascript">
        bkLib.onDomLoaded(function() {
            new nicEditor().panelInstance('area1');
        });
    </script>
    <script>
        $(function() {
            $('#country').multiSelect();
        });
    </script>
    <script>
        $('#name').change(function(e) {
            $('#url').val($(this).val().replace(/\s+/g, '-').replace(
                /[^a-zA-Z0-9\-]/g, '').toLowerCase());
        });
    </script>
    <script>
        var $container = $(".radiobox_item_type");
        $container.on("click", "input[type='radio']", function() {
            //uncheck other radio buttons
            $container.find('input[type="radio"]').each(function() {
                $(this).prop('checked', false);
            });
            $(this).prop('checked', true);
        });
    </script>
    <script>
        $(document).ready(function() {
            $('#order').change(function() {
                if ($(this).is(':checked')) {
                    $('#offer').prop('checked', false);
                }
            });

            $('#offer').change(function() {
                if ($(this).is(':checked')) {
                    $('#order').prop('checked', false);
                }
            });
        });
    </script>
    <script type="text/javascript"
        src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/1.1.2/js/bootstrap-multiselect.js"></script>
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/1.1.2/css/bootstrap-multiselect.min.css"
        type="text/css" />
@endpush
