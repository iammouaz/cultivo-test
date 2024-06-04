@extends('admin.layouts.app')
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

    $base_url = config('app.url') . '/auction/';
    $hero_text_color = valueRuterner(null);
    $hero_primary_button_color = valueRuterner(null);
    $hero_outlined_button_color = valueRuterner(null);
    $hero_image_overlay = valueRuterner(null);
@endphp
@push('style-lib')
    <link rel="stylesheet" href="{{ asset('assets/admin/css/switch.css') }}">
@endpush
@section('panel')
    @push('style')
        <style>
            input.text_color {
                width: 30px;
                height: 30px;
                border-radius: 100%;
                cursor: pointer;
                padding: 0px;
                overflow: hidden
            }

            .text_color {
                -webkit-appearance: none;
                border: none;
                width: 32px;
                height: 32px;
            }

            .text_color::-webkit-color-swatch-wrapper {
                padding: 0;
            }

            .text_color::-webkit-color-swatch {
                border: none;
            }

            .profilePicPreview {
                display: block;
                border: 3px solid #f1f1f1;
                box-shadow: 0 0 5px 0 rgba(0, 0, 0, 0.25);
                border-radius: 10px;
                background-size: cover;
                background-position: center;
                position: relative;
            }

            .font-small {
                font-size: 14px
            }

            .profilePicPreview input {
                display: none
            }

            .image-remove-button {
                position: absolute;
                top: 15px;
                right: 15px;
                cursor: pointer;
            }

            .custom-rounded-btn {
                border-radius: 4px;
                padding: 6px 16px;
                font-size: 16px;
                font-style: normal;
                font-weight: 400;
                line-height: 18px;
            }

            .end-event-btn {
                background: #ffffff;
                border: 2px solid #ff8749;
            }

            .end-event-btn span {
                color: #ff8749 !important;
            }

            .outlined-btn-theme {
                background: #ffffff;
                border: 2px solid #007bff;
            }

            .outlined-btn-theme span {
                color: #007bff !important;
            }

            .main-btn-theme {
                background: #007bff;
            }

            .main-btn-theme i,
            .main-btn-theme span {
                color: #ffffff !important;
            }

            .normal-label {
                font-size: 16px;
                font-style: normal;
                font-weight: 400;
                line-height: 18px;
            }

            .start-clock-btn {
                position: absolute;
                top: 0;
                right: 16px;
            }

            .start-clock-btn span {
                color: #ffffff !important;
            }

            .main-page-title {
                color: #111;
                font-size: 24px;
                font-style: normal;
                font-weight: 400;
                line-height: 26px;
            }

            .shipping-section h5 {
                display: none;
            }

            .disabled-switch {
                pointer-events: none;
                opacity: 0.4;
            }
        </style>
    @endpush

    <form action="{{ route('admin.event.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="d-flex align-items-center justify-content-between mb-4">
            <h1 class="main-page-title">@lang('Create Event')</h1>
            <div>
                <button type="submit" class="main-btn-theme custom-rounded-btn mx-3 my-0">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 18 18" fill="none">
                        <g clip-path="url(#clip0_3670_35675)">
                            <path
                                d="M0.75 9C0.75 9 3.75 3 9 3C14.25 3 17.25 9 17.25 9C17.25 9 14.25 15 9 15C3.75 15 0.75 9 0.75 9Z"
                                stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                            <path
                                d="M9 11.25C10.2426 11.25 11.25 10.2426 11.25 9C11.25 7.75736 10.2426 6.75 9 6.75C7.75736 6.75 6.75 7.75736 6.75 9C6.75 10.2426 7.75736 11.25 9 11.25Z"
                                stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                        </g>
                        <defs>
                            <clipPath id="clip0_3670_35675">
                                <rect width="18" height="18" fill="white" />
                            </clipPath>
                        </defs>
                    </svg>
                    <span class="ml-1">@lang('Publish')</span>
                </button>
                {{-- <a href="{{ route('admin.event.index') }}" class="main-btn-theme custom-rounded-btn m-0">
                    <i class="la la-fw la-backward"></i>
                    <span>@lang('Go Back')</span>
                </a> --}}
            </div>
        </div>

        <div class="row">
            <div class="col-xl-4">
                {{-- Start - Hero section --}}
                <div class="card">
                    <div class="card-body">
                        <h6 class="fw-bolder mb-3">@lang('Hero Section')</h6>
                        <div class="form-group">
                            {{-- Start - Event image picker --}}
                            <label class="font-weight-bold">@lang('Image') <span class="text-danger">*</span></label>
                            <div class="thumb mb-0 w-100 image-container">
                                <div class="avatar-preview w-100">
                                    <div class="profilePicPreview w-100 rounded"
                                        style="background-image: url('{{ getImage(imagePath()['event']['path'], imagePath()['event']['size']) }}');border:dashed 2px #AAAAAA;box-shadow:unset;height:225px">
                                        <div style="height: 0px;">
                                            <input type="file" name="image" class="profilePicUpload" id="image"
                                                accept=".png, .jpg, .jpeg" />
                                            <label for="image"
                                                style="position: absolute;top:70%;left:50%;transform:translate(-50%)">
                                                <div class="btn btn-light box--shadow1 text--small">
                                                    Select Image
                                                </div>
                                            </label>
                                            <div class="image-remove-button">
                                                <i class="fa fa-fw fa-trash-alt"></i>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="mt-2 font-small text-center">@lang('images between 1500 pixels and 2500 pixels wide')</div>
                                </div>
                            </div>
                            {{-- End - Event image picker --}}

                            {{-- Start - Event banner picker --}}
                            <div id="banner_wrapper" class="my-4">
                                <label class="font-weight-bold">@lang('Banner')</label>
                                <div class="thumb mb-0 w-100 image-container">
                                    <div class="avatar-preview w-100">
                                        <div class="profilePicPreview w-100 rounded"
                                            style="background-image: url('{{ getImage(imagePath()['event_banner_image']['path'], imagePath()['event_banner_image']['size'], false, 'sm') }}');border:dashed 2px #AAAAAA;box-shadow:unset;height:225px">
                                            <div style="height: 0px;">
                                                <input type="file" name="banner_image" class="profilePicUpload"
                                                    id="banner_input" accept=".png, .jpg, .jpeg" />
                                                <label for="banner_input"
                                                    style="position: absolute;top:70%;left:50%;transform:translate(-50%)">
                                                    <div class="btn btn-light box--shadow1 text--small">
                                                        Select Image
                                                    </div>
                                                </label>
                                                <div class="image-remove-button">
                                                    <i class="fa fa-fw fa-trash-alt"></i>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="mt-2 font-small text-center">@lang('images between 1500 pixels and 2500 pixels wide')</div>
                                    </div>
                                </div>
                            </div>
                            {{-- End - Event banner picker --}}

                            {{-- Start - Event logo picker --}}
                            <div>
                                <label class="font-weight-bold">@lang('Logo')</label>
                                <div class="thumb_logo mb-0 image-container">
                                    <div class="avatar-preview">
                                        <div class="profilePicPreview profilePicPreview_logo rounded mx-auto"
                                            style="background-image: url('{{ getImage(imagePath()['event_logo']['path'], imagePath()['event_logo']['size']) }}');border:dashed 2px #AAAAAA;box-shadow:unset;width:180px;height:170px">
                                            <div class="avatar-edit_logo" style="height: 0px;">
                                                <input type="file" name="logo" class="profilePicUpload_logo"
                                                    id="image_logo" accept=".png, .jpg, .jpeg" />
                                                <label for="image_logo"
                                                    style="position: absolute;top:70%;left:50%;transform:translate(-50%)">
                                                    <div class="btn btn-light box--shadow1"
                                                        style="font-size: 10px;padding:6px 8px;">
                                                        Select Image
                                                    </div>
                                                </label>
                                                <div class="image-remove-button">
                                                    <i class="fa fa-fw fa-trash-alt"></i>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="mt-2 font-small text-center">@lang('images between 250 pixels and 640 pixels wide')</div>
                                    </div>
                                </div>
                            </div>
                            {{-- End - Event logo picker --}}

                            <div class="form-group my-4 d-flex align-items-center">
                                <input type="checkbox" id="hero_show_action_name" name="hero_show_action_name"
                                    onchange="updateCheckboxValue(this)" />
                                <label for="hero_show_action_name"
                                    class="ml-2 font-weight-bold mb-0">@lang('Show auction name & description')</label>
                            </div>
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
                    </div>
                </div>
                {{-- End - Hero section --}}

                {{-- Start - cart configuration --}}
                <div class="mt-3 card">
                    <div class="card-body">
                        <h6 class="fw-bolder mb-3">@lang('Cart Configuration')</h6>
                        <p class="mb-3" style="color: rgba(17, 17, 17, 1);">@lang('Are you selling sample sets?')</p>
                        <div class="slide-switch d-flex align-items-center mx-0">
                            <label class="switch mb-0">
                                <input id="show-order-samples-button" name="show_sample_set_button" type="checkbox"
                                    value="1" checked>
                                <span class="slider round"></span>
                            </label>
                            <label class="switch-label px-2 mb-0"
                                for="show-order-samples-button">@lang('Sample Set Button')</label>
                        </div>
                        <div class="form-group my-3">
                            <label class="w-100 font-weight-bold" for="button-label">
                                @lang('Button Label')
                                <span class="text-danger">*</span>
                            </label>
                            <input type="text" class="form-control" placeholder="@lang('Button Label')"
                                id="button-label" name="sample_set_button_lable" value="" required />
                        </div>
                        <div class="form-group">
                            <div class="form-check d-flex align-items-center">
                                <input type="radio" class="form-check-input" name="sample_set_cart_config"
                                    value="external_url" />
                                <div class="d-flex flex-column flex-grow-1">
                                    <label class="form-check-label mb-1">
                                        @lang('External Url')
                                    </label>
                                    <input type="text" class="form-control" placeholder="https://" id="external-url"
                                        name="sample_set_external_url" value="" disabled />
                                </div>
                            </div>
                            <div class="form-check my-3">
                                <input type="radio" class="form-check-input" name="sample_set_cart_config"
                                    id="via-email" value="orders_by_email" />
                                <label class="form-check-label" for="via-email">
                                    @lang('I want to receive orders via e-mail (no payment processing)')
                                </label>
                            </div>
                            <div class="form-check">
                                <input type="radio" class="form-check-input" name="sample_set_cart_config"
                                    id="via-m-cultivo-payment" value="payment_process" checked />
                                <label class="form-check-label" for="via-m-cultivo-payment">
                                    @lang("I want to sell Sample Sets with M-Cultivo's payment processing feature")
                                </label>
                            </div>

                            <div class="py-4" id="payment-process-inputs">
                                <p class="mb-3" style="color: rgba(17, 17, 17, 1);">@lang('Are you selling sample sets?')</p>

                                <div>
                                    <div class="thumb_logo mb-0 image-container">
                                        <div class="avatar-preview">
                                            <div class="profilePicPreview profilePicPreview_logo rounded mx-auto"
                                                 style="background-image: url('{{ getImage(imagePath()['product']['path'], imagePath()['product']['size']) }}');border:dashed 2px #AAAAAA;box-shadow:unset;width:180px;height:170px">
                                                <div class="avatar-edit_logo" style="height: 0px;">
                                                    <input type="file" name="sample_set_image" class="profilePicUpload_logo"
                                                           id="sample_set_image" accept=".png, .jpg, .jpeg" />
                                                    <label for="sample_set_image"
                                                           style="position: absolute;top:70%;left:50%;transform:translate(-50%)">
                                                        <div class="btn btn-light box--shadow1"
                                                             style="font-size: 10px;padding:6px 8px;">
                                                            Select Image
                                                        </div>
                                                    </label>
                                                    <div class="image-remove-button">
                                                        <i class="fa fa-fw fa-trash-alt"></i>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="w-100 font-weight-bold">@lang('Sample Set Price') <span
                                            class="text-danger">*</span></label>
                                    <input type="text" class="form-control "
                                           name="sample_set_price" value="{{ old('sample_set_price') }}" required />
                                </div>
                                <div class="form-group">
                                    <label class="w-100 font-weight-bold">@lang('Total Sample Set package weight (Lb)') <span
                                            class="text-danger">*</span></label>
                                    <input type="text" class="form-control "
                                           name="total_package_weight_Lb" value="{{ old('total_package_weight_Lb') }}" required />
                                </div>
                                <div class="form-group">
                                    <label class="w-100 font-weight-bold">@lang('Number of samples per box') <span
                                            class="text-danger">*</span></label>
                                    <input type="text" class="form-control "
                                           name="number_of_samples_per_box" value="{{ old('number_of_samples_per_box') }}" required />
                                </div>
                                <div class="form-group">
                                    <label class="w-100 font-weight-bold">@lang('Weight per Sample (grams)') <span
                                            class="text-danger">*</span></label>
                                    <input type="text" class="form-control "
                                           name="weight_per_sample_grams" value="{{ old('weight_per_sample_grams') }}" required />
                                </div>
                                <div class="form-group">
                                    <label class="w-100 font-weight-bold">@lang('Sample Set Limit per Account') <span
                                            class="text-danger">*</span></label>
                                    <input class="input-spinner"  type="number"  min="1" max="100" step="1"
                                           name="sample_set_limit_per_account" value="{{ old('sample_set_limit_per_account') }}" required />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                {{-- End - cart configuration --}}
            </div>

            <div class="col-xl-8">
                {{-- Start - auction settings --}}
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <h6 class="fw-bolder">@lang('Auction Settings')</h6>
                        </div>
                        <div class="content">
                            <div class="row mb-none-15">
                                <div class="col-sm-12 col-lg-6 mb-15">
                                    <div class="form-group">
                                        <label class="w-100 font-weight-bold">@lang('Name') <span
                                                class="text-danger">*</span></label>
                                        <input type="text" class="form-control " placeholder="@lang('Event Name')"
                                            name="name" value="{{ old('name') }}" required />
                                    </div>
                                </div>
                                <div class="col-sm-12 col-lg-6 mb-15">
                                    <div class="form-group">
                                        <label class="w-100 font-weight-bold">@lang('Secondary Name')</label>
                                        <input type="text" class="form-control " placeholder="@lang('Secondary Name')"
                                            name="sname" value="{{ old('sname') }}" />
                                    </div>
                                </div>
                                <div class="col-sm-12 col-lg-6 mb-15">
                                    <div class="form-group">
                                        <label class="w-100 font-weight-bold">@lang('Event Bid Status') <span
                                                class="text-danger">*</span></label>
                                        <select name="bid_status" class="form-control">
                                            <option value="open" @if (old('bid_status') == 'open') selected @endif>
                                                Open
                                            </option>
                                            <option value="closed" @if (old('bid_status') == 'closed') selected @endif>Closed
                                                Event
                                            </option>
                                        </select>
                                    </div>
                                </div>
                                    <div class="col-sm-12 col-lg-6 mb-15">
                                    <div class="form-group">
                                        <label class="w-100 font-weight-bold">@lang('Login Type') <span
                                                class="text-danger">*</span></label>
                                        <select name="login_type" class="form-control">
                                            <option value="normal" @if (old('login_type') == 'normal') selected @endif>Normal login
                                            </option>
                                            <option value="ace" @if (old('login_type') == 'ace') selected @endif>ACE External Login
                                            </option>
                                        </select>
                                    </div>
                                </div>
                                {{-- <div class="col-sm-12 col-lg-6 mb-15">
                                    <div class="form-group">
                                        <label class="w-100 font-weight-bold">@lang('Event Type') <span
                                                class="text-danger">*</span></label>
                                        <select id="event_type" name="event_type" class="form-control">
                                            <option value="" selected disabled>Select Value</option>
                                            <option value="m_cultivo_event"
                                                @if (old('event_type') == 'm_cultivo_event') selected @endif>M
                                                Cultivo Event
                                            </option>
                                            <option id="ace_event" value="ace_event"
                                                @if (old('event_type') == 'ace_event') selected @endif>ACE
                                                Event
                                            </option>
                                        </select>
                                    </div>
                                </div> --}}
                                <div class="col-sm-12 col-xl-6 col-lg-6 mb-15">
                                    <div class="form-group">
                                        <label class="w-100 font-weight-bold">@lang('Practice Event') <span
                                                class="text-danger">*</span></label>
                                        <select name="practice" class="form-control" id="practice">
                                            <option value="0" @if (old('practice') == '0') selected @endif>NO
                                            </option>
                                            <option value="1" @if (old('practice') == '1') selected @endif>YES
                                            </option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-12 col-lg-6 mb-15 started_at">
                                    <div class="form-group">
                                        <label class="w-100 font-weight-bold">@lang('Start Date') <span
                                                class="text-danger">*</span></label>
                                        <input type="text" name="start_date" placeholder="@lang('Select Date & Time')"
                                            id="startDateTime" data-position="bottom left"
                                            class="form-control border-radius-5 d-picker" value="{{ old('start_date') }}"
                                            autocomplete="off" required />
                                    </div>
                                </div>
                                <div class="col-sm-12 col-lg-6 mb-15">
                                    <div class="form-group">
                                        <label class="w-100 font-weight-bold">@lang('Display End Date') <span
                                                class="text-danger">*</span></label>
                                        <input type="text" name="display_end_date" placeholder="@lang('Select Date & Time')"
                                            id="displayEndDateTime" data-position="bottom left"
                                            class="form-control border-radius-5 d-picker"
                                            value="{{ old('display_end_date') }}" autocomplete="off" required />
                                    </div>
                                </div>
                                <div class="col-sm-12 col-lg-6 mb-15">
                                    <div class="form-group">
                                        <label class="w-100 font-weight-bold">@lang('Auction End Date') <span
                                                class="text-danger">*</span></label>
                                        <input type="text" name="end_date" placeholder="@lang('Select Date & Time')"
                                            id="endDateTime" data-position="bottom left"
                                            class="form-control border-radius-5 d-picker" value="{{ old('end_date') }}"
                                            autocomplete="off" required />
                                    </div>
                                </div>
                                <div class="col-sm-12 col-lg-6 mb-15">
                                    <div class="form-group">
                                        <label class="w-100 font-weight-bold">@lang('Max End Date')</label>
                                        <input type="text" name="max_end_date" placeholder="@lang('Select Date & Time')"
                                            id="maxendDateTime" data-position="bottom left"
                                            class="form-control border-radius-5 d-picker"
                                            value="{{ old('max_end_date') }}" autocomplete="off" />
                                    </div>
                                </div>
                                <div class="col-sm-12 col-lg-6 mb-15">
                                    <div class="form-group">
                                        <label class="w-100 font-weight-bold">@lang('Less Bidding Time (m)')
                                            <span class="text-danger">*</span></label>
                                        <input type="number" class="form-control " placeholder="@lang('Less Bidding Time')"
                                            name="less_bidding_time" min="1"
                                            value="{{ old('less_bidding_time') }}" required />
                                    </div>
                                </div>
                                <div class="col-sm-12 col-lg-6 mb-15">
                                    <div class="form-group">
                                        <label class="w-100 font-weight-bold">@lang('Deposit Percentage (%)')
                                            <span class="text-danger">*</span></label>
                                        <input type="number" class="form-control " step=".01"
                                            placeholder="@lang('Deposit Percentage(%)')" name="deposit" min="0"
                                            value="{{ old('deposit') }}" required />
                                    </div>
                                </div>
                                <div class="col-sm-12 col-lg-6 mb-15">
                                    <div class="form-group">
                                        <label class="w-100 font-weight-bold">@lang('Max Bid Increment') <span
                                                class="text-danger">*</span></label>
                                        <div class="input-group has_append">
                                            <input type="text" class="form-control " placeholder="0"
                                                name="max_bidding_value" value="{{ old('max_bidding_value') }}"
                                                required />
                                            <div class="input-group-append">
                                                <span class="input-group-text">{{ $general->cur_text }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                {{-- <div class="col-sm-12 col-lg-6 mb-15">
                                    <div class="form-group">
                                        <label class="w-100 font-weight-bold">@lang('Category') <span
                                                class="text-danger">*</span></label>
                                        <select name="category" class="form-control" required>
                                            <option value="">@lang('Select One')</option>
                                            @foreach ($categories as $category)
                                                <option value="{{ $category->id }}"
                                                    @if (old('category') == $category->id) selected @endif>
                                                    {{ $category->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div> --}}
                                <div class="col-sm-12 col-xl-4 col-lg-6 mb-15">
                                    <div class="form-group">
                                        <label class="w-100 font-weight-bold">@lang('Event Clock Starts On')
                                            <span class="text-danger">*</span></label>
                                        <div class="form-check">
                                            <input type="radio" class="form-check-input"
                                                placeholder="@lang('All product have a bid')" name="EventClockStartOn" disabled
                                                value="0" />
                                            <label class="form-check-label">
                                                All product have a bid
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input type="radio" class="form-check-input"
                                                placeholder="@lang('Manually')" name="EventClockStartOn" value="1"
                                                checked />
                                            <label class="form-check-label">
                                                Manually
                                            </label>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-12">
                                    <div class="form-group">
                                        <label class="font-weight-bold">@lang('Description')<span
                                                class="text-danger">*</span></label>
                                        <textarea rows="4" class="form-control border-radius-5" name="description">{{ old('description') }}</textarea>
                                    </div>
                                </div>

                                <div class="col-12">
                                    <div class="form-group">
                                        <label class="font-weight-bold">@lang('Agreement')<span
                                                class="text-danger">*</span></label>
                                        <textarea rows="10" cols="50" id="area1" name="agreement">{{ old('agreement') }}</textarea>
                                    </div>
                                </div>

                                <div class="col-12">
                                    <div class="form-group">
                                        <label class="font-weight-bold">@lang('Emails') <span
                                                class="text-danger">*</span><span
                                                class="fs-6">@lang('(Comma separated)')</span></label>
                                        <textarea rows="10" cols="50" id="area1" name="emails"></textarea>
                                    </div>
                                </div>

                                <div class="col-sm-12 mb-15">
                                    <div class="d-flex align-items-center">
                                        <p class="m-0 ml-2 w-50 font-weight-bold">{{ $base_url }}</p>
                                        <div class="form-group w-50">
                                            <label class="w-100 font-weight-bold">
                                                @lang('Customize your Event Url')
                                                <span class="text-danger">*</span>
                                            </label>
                                            <input id="url" type="text" class="form-control" name="url"
                                                value="{{ old('url') ? old('url') : '' }}" pattern="[a-z0-9\-]+"
                                                title="Please enter a valid URL starting with '{{ $base_url }}/'">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                {{-- End - auction settings --}}

                {{-- Start - shipping --}}
                <div class="mt-3 card shipping-section">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between">
                            <h6 class="fw-bolder mb-3">@lang('Shipping')</h6>
                            <div class=" slide-switch d-flex align-items-center mx-0">
                                <label class="switch mb-0">
                                    <input id="show_shipping" name="show_shipping" type="checkbox" checked>
                                    <span class="slider round"></span>
                                </label>
                                <label class="switch-label px-2 mb-0" for="show_shipping">@lang('Show')</label>
                            </div>
                        </div>
                        <div class="row mt-4 wrapper shipping_area_container">
                            <div class="col-12">
                                <div class="card">
                                    <x-regions-input :regions="$regions" :countries="$countries" :disableCard="true" />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                {{-- End - shipping --}}
            </div>
        </div>
    </form>
@endsection

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

            var specCount = 2;
            var regionCount = 2;
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
            $('.d-picker').datepicker({
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


            $(document).on('click', '#show_shipping', function() {

                if ($(this).is(":checked")) $(".shipping_area_container").show();
                else $(".shipping_area_container").hide();
            })

            $(document).on('click', '.addUserData', function() {
                var tempregion = $(this).closest('.regiondata').attr('data-shippingregions');
                var html = `
                <div class="col-md-12 user-data">
                    <div class="form-group">
                        <div class="input-group mb-md-0 mb-4">
                            <div class="col-md-3">
                                <label class="w-100 font-weight-bold">@lang('From') <span class="text-danger">*</span></label>
                                <input name="shippingranges[${tempregion}][${specCount}][from]" class="form-control" type="text" required placeholder="@lang('From')">
                            </div>
                            <div class="col-md-3">
                                <label class="w-100 font-weight-bold">@lang('Up To') <span class="text-danger">*</span></label>
                                <input name="shippingranges[${tempregion}][${specCount}][up_to]" class="form-control" type="text" required placeholder="@lang('Up To')">
                            </div>
                            <div class="col-md-3">
                                <label class="w-100 font-weight-bold">@lang('Cost') <span class="text-danger">*</span></label>
                                <input name="shippingranges[${tempregion}][${specCount}][cost]" class="form-control" type="text" required placeholder="@lang('Cost')">
                            </div>
                            <div class="col-md-2 mt-md-0 mt-2 text-right align-self-end">
                                <span class="input-group-btn">
                                    <button class="btn btn--danger btn-lg removeBtn w-100" type="button">
                                        <i class="fa fa-times"></i>
                                    </button>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>`;
                $(this).closest('.regiondata').find('.addedField').append(html);
                specCount += 1;
            });

            $(document).on('click', '.addFee', function() {
                // var tempregion = $(this).closest('.regiondata').attr('data-feeregions');
                var html = `
                <div class="col-md-12 user-data">
                        <div class="form-group">
                            <div class="input-group mb-md-0 mb-4">
                                        <div class="col-md-3">
                                            <label class="w-100 font-weight-bold">@lang('Country name') <span
                                                    class="text-danger">*</span></label>
                                            <select required class="form-control "
                                                name="fees[${specCount}][country_id]">
                                                <option value="">@lang('Select Country')</option>
                                                @foreach ($countries as $country)
                                                <option value="{{ $country->id }}">{{ $country->Name }}</option>
                                                @endforeach
                                                <option value="999">@lang('Rest Of The World')</option>
                                            </select>
                                        </div>
                                        <div class="col-md-3">
                                                    <label class="w-100 font-weight-bold">@lang('Payment Method') <span
                                                            class="text-danger">*</span></label>
                                                    <select required class="form-control "
                                                            name="fees[${specCount}][payment_method]">
                                                            <option value="">@lang('Select Payment Method')</option>
                                                            <option value="Credit Card">@lang('Credit Card')</option>
                                                            <option value="Wire Transfer">@lang('Wire Transfer')</option>
                                                    </select>
                                        </div>
                                        <div class="col-md-4">
                                            <label class="w-100 font-weight-bold">@lang('Credit Card Processing %') <span
                                                    class="text-danger">*</span></label>
                                            <input type="number" min=".01" max="100" step=".01" required class="form-control"
                                                name="fees[${specCount}][fee_value]" placeholder="@lang('Credit Card Processing %')"/>
                                        </div>
                                        <div class="col-md-2 mt-md-0 mt-2 text-right align-self-end">
                                            <span class="input-group-btn">
                                                <button class="btn btn--danger btn-lg removeBtn w-100"
                                                    type="button">
                                                    <i class="fa fa-times"></i>
                                                </button>
                                            </span>
                                        </div>
                            </div>
                        </div>
                </div>
                `;
                $(this).closest('.regiondata').find('.newFee').append(html);
                specCount += 1;
            });

            $(document).on('click', '.removeBtn', function() {
                $(this).closest('.user-data').remove();
            });

            $('.addRegionData').on('click', function() {
                var html = `
                <div class="col-lg-12 regiondata"  data-shippingregions="${regionCount}">
                        <div class="card border--primary mt-3">
                            <h5 class="card-header bg--primary  text-white">@lang('Shipping Region')
                <button class="btn btn--danger btn-lg removeRegion float-right w-20" type="button">
                    <i class="fa fa-times"></i>
                </button>
            </h5>
            <div class="row col-12 ">
                <div class="form-group col-6">
                    <label class="w-100 font-weight-bold">@lang('Region name') <span class="text-danger">*</span></label>
                                     <select required class="form-control " name="shippingregions[${regionCount}][region_name]">
                                                    <option value="">@lang('Select Region')</option>
                                                    @foreach ($regions as $region)
                <option value="{{ $region->id }}">{{ $region->name }}</option>
                                                    @endforeach
                </select>
</div>
     <div class="form-group col-6">
                <label class="w-100 font-weight-bold">@lang('Shipping method') <span class="text-danger">*</span></label>
                                                <select  class="form-control " name="shippingregions[${regionCount}][shipping_method]">
                                                    <option value="">@lang('Select shipping method')</option>
                                                    <option value="Air Freight (expedited)">@lang('Air Freight (expedited)')</option>
                                                    <option value="Air Freight (Palletized)">@lang('Air Freight (Palletized)')</option>
                                                    <option value="Ocean Freight">@lang('Ocean Freight')</option>

                                                </select>
                                            </div>
                            </div>
                            <h5 class="card-header bg--primary  text-white">@lang('Ranges')
                <button type="button" class="btn btn-sm btn-outline-light float-right addUserData"><i class="la la-fw la-plus"></i>@lang('Add New Ranges')
                </button>
            </h5>

                <div class="row addedField">

                    <div class="col-md-12 user-data">
                        <div class="form-group">
                            <div class="input-group mb-md-0 mb-4">
                                <div class="col-md-3">
                                    <label class="w-100 font-weight-bold">@lang('From') <span class="text-danger">*</span></label>
                                                    <input name="shippingranges[${regionCount}][${specCount}][from]" class="form-control" type="text" required placeholder="@lang('From')">
                                                </div>
                                                <div class="col-md-3">
                                                    <label class="w-100 font-weight-bold">@lang('Up To') <span class="text-danger">*</span></label>
                                                    <input name="shippingranges[${regionCount}][${specCount}][up_to]" class="form-control" type="text" required placeholder="@lang('Up To')">
                                                </div>
                                                <div class="col-md-3">
                                                    <label class="w-100 font-weight-bold">@lang('Cost') <span class="text-danger">*</span></label>
                                                    <input name="shippingranges[${regionCount}][${specCount}][cost]" class="form-control" type="text" required placeholder="@lang('Cost')">
                                                </div>
                                                <div class="col-md-2 mt-md-0 mt-2 text-right align-self-end">
                                                    <span class="input-group-btn">
                                                        <button class="btn btn--danger btn-lg removeBtn w-100" type="button">
                                                            <i class="fa fa-times"></i>
                                                        </button>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                        </div>
                    </div>`;
                $('.addregionhere').append(html);
                regionCount += 1;
            });

            $(document).on('click', '.removeRegion', function() {
                $(this).closest('.regiondata').remove();
            });

            $('#practice').on('change', function() {
                if (this.value == 1) {
                    $("select[name='event_type']").val('m_cultivo_event');
                    $("select[name='event_type']").prop('disabled', 'disabled');
                } else {
                    $("select[name='event_type']").prop('disabled', false);
                }
            });

            function unsetBannerInput() {
                $('#banner_wrapper').hide();
                // Clone the file input element
                var fileInput = $('#banner_wrapper input');
                fileInput.val(null)

                $('#banner_wrapper  .profilePicPreview').css('background-image', 'none')
            }

            $("#event_type").on('change', function() {
                var selectedValue = $(this).val();

                if (selectedValue === "m_cultivo_event") {
                    $('#banner_wrapper').show()
                } else {
                    unsetBannerInput()
                }
            })

            $("#practice").on('change', function() {
                var selectedValue = $(this).val();

                if (selectedValue == 1) {
                    $('#banner_wrapper').show()
                }
            })

            // Start - Remove the selected image
            $("input[type='file']").change(function() {
                if ($(this).prop('files').length > 0) {
                    const container = $(this).closest('.image-container');
                    const removeButton = container.find('.image-remove-button');
                    removeButton.css('display', 'block');
                }
            })
            $(".image-remove-button").click(function() {
                const container = $(this).closest('.image-container');
                const profilePreviewContainer = container.find('.profilePicPreview');
                profilePreviewContainer.css('background-image',
                    "url('{{ asset('custom/images/images-placeholder.png') }}')");
                const fileInput = container.find('input[type="file"]');
                fileInput.replaceWith(fileInput
                    .val('').clone(true));
                $(this).css('display', 'none');
            })
            // End - Remove the selected image

            // Start - Cart configruation section
            $('#show-order-samples-button').change(function() {
                if ($(this).is(":checked")) {
                    $(this).val(1);

                    $('#button-label').prop('disabled', false);
                    $('#external-url').prop('disabled', false);
                    $('input[name="sample_set_cart_config"]').prop('disabled', false);

                    const value = $('input[name="sample_set_cart_config"]').val();
                    if (value !== "payment_process" && value !== "orders_by_email") {
                        removeSampleSetForm();
                    }
                } else {
                    $(this).val(0);
                    $('#button-label').prop('disabled', true);
                    $('#external-url').prop('disabled', true);
                    $('input[name="sample_set_cart_config"]').prop('disabled', true);
                    removeSampleSetForm();
                }
            });

            $('input[name="sample_set_cart_config"]').change(function() {
                var value = $(this).val();
                if (value === "external_url") {
                    $('#external-url').prop('disabled', false);
                } else {
                    $('#external-url').prop('disabled', true);
                }
            });

            $('input[name="sample_set_cart_config"]').change(function() {
                const value = $(this).val();
                if ((value === "payment_process" || value === "orders_by_email") &&  $('#payment-process-inputs').is(':empty')) {
                    appendSampleSetForm()
                }
                if((value !== "payment_process" && value !== "orders_by_email")) {
                    removeSampleSetForm()
                }
            });

            function appendSampleSetForm() {
                const html = ` <p class="mb-3" style="color: rgba(17, 17, 17, 1);">@lang('Are you selling sample sets?')</p>

                                <div>
                                    <div class="thumb_logo mb-0 image-container">
                                        <div class="avatar-preview">
                                            <div class="profilePicPreview profilePicPreview_logo rounded mx-auto"
                                                 style="background-image: url('{{ getImage(imagePath()['product']['path'], imagePath()['product']['size']) }}');border:dashed 2px #AAAAAA;box-shadow:unset;width:180px;height:170px">
                                                <div class="avatar-edit_logo" style="height: 0px;">
                                                    <input type="file" name="sample_set_image" class="profilePicUpload_logo"
                                                           id="sample_set_image" accept=".png, .jpg, .jpeg" />
                                                    <label for="sample_set_image"
                                                           style="position: absolute;top:70%;left:50%;transform:translate(-50%)">
                                                        <div class="btn btn-light box--shadow1"
                                                             style="font-size: 10px;padding:6px 8px;">
                                                            Select Image
                                                        </div>
                                                    </label>
                                                    <div class="image-remove-button">
                                                        <i class="fa fa-fw fa-trash-alt"></i>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="w-100 font-weight-bold">@lang('Sample Set Price') <span
                                            class="text-danger">*</span></label>
                                    <input type="text" class="form-control "
                                           name="sample_set_price" value="{{ old('sample_set_price') }}" required />
                                </div>
                                <div class="form-group">
                                    <label class="w-100 font-weight-bold">@lang('Total Sample Set package weight (Lb)') <span
                                            class="text-danger">*</span></label>
                                    <input type="text" class="form-control "
                                           name="total_package_weight_Lb" value="{{ old('total_package_weight_Lb') }}" required />
                                </div>
                                <div class="form-group">
                                    <label class="w-100 font-weight-bold">@lang('Number of samples per box') <span
                                            class="text-danger">*</span></label>
                                    <input type="text" class="form-control "
                                           name="number_of_samples_per_box" value="{{ old('number_of_samples_per_box') }}" required />
                                </div>
                                <div class="form-group">
                                    <label class="w-100 font-weight-bold">@lang('Weight per Sample (grams)') <span
                                            class="text-danger">*</span></label>
                                    <input type="text" class="form-control "
                                           name="weight_per_sample_grams" value="{{ old('weight_per_sample_grams') }}" required />
                                </div>
                                <div class="form-group">
                                    <label class="w-100 font-weight-bold">@lang('Sample Set Limit per Account') <span
                                            class="text-danger">*</span></label>
                                    <input class="input-spinner"  type="number"  min="1" max="100" step="1"
                                           name="sample_set_limit_per_account" value="{{ old('sample_set_limit_per_account') }}" required />
                                </div>`;
                   $('#payment-process-inputs').append(html);
                const field = $('[name="sample_set_image"]');
                console.log(field)
                field.on('change', function() {
                       console.log(this, proPicURL_logo)
                    proPicURL_logo(this) });
            }
            function removeSampleSetForm() {
                $('#payment-process-inputs').empty();
            }

            // End - Cart configruation section
        })(jQuery);

        function updateCheckboxValue(checkboxElem) {
            if (checkboxElem.checked) {
                checkboxElem.value = 1;
            } else {
                checkboxElem.value = 0;
            }
        }
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
@endpush
