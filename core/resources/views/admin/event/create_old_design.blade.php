@extends('admin.layouts.app')
@section('panel')
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <form action="{{ route('admin.event.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="card-body">
                    <div class="payment-method-item">
                        <div class="payment-method-header">
                            <div class="form-group">
                                <label class="font-weight-bold">@lang('Image') <span
                                        class="text-danger">*</span></label>
                                <div class="thumb">
                                    <div class="avatar-preview">
                                        <div class="profilePicPreview"
                                            style="background-image: url('{{getImage(imagePath()['event']['path'],imagePath()['event']['size'])}}')">
                                        </div>
                                    </div>
                                    <div class="avatar-edit">
                                        <input type="file" name="image" class="profilePicUpload" id="image"
                                            accept=".png, .jpg, .jpeg" />
                                        <label for="image" class="bg--primary"><i class="la la-pencil"></i></label>
                                    </div>
                                </div>
                                <button type="submit" class="btn btn--primary btn-block" style="margin-top:-15px;">@lang('Submit')</button>
                            </div>

                            <div class="content">
                                <div class="row mb-none-15">
                                    <div class="col-sm-12 col-xl-4 col-lg-6 mb-15">
                                        <div class="form-group">
                                            <label class="w-100 font-weight-bold">@lang('Name') <span
                                                    class="text-danger">*</span></label>
                                            <input type="text" class="form-control " placeholder="@lang('Event Name')"
                                                name="name" value="{{ old('name') }}" required />
                                        </div>
                                    </div>
                                    <div class="col-sm-12 col-xl-4 col-lg-6 mb-15">
                                        <div class="form-group">
                                            <label class="w-100 font-weight-bold">@lang('Secondary Name')</label>
                                            <input type="text" class="form-control " placeholder="@lang('Secondary Name')"
                                                name="sname" value="{{ old('sname') }}"/>
                                        </div>
                                    </div>
                                    <div class="col-sm-12 col-xl-4 col-lg-6 mb-15">
                                        <div class="form-group">
                                            <label class="w-100 font-weight-bold">@lang('Event Bid Status') <span
                                                    class="text-danger">*</span></label>
                                            <select name="bid_status" class="form-control">
                                                <option value="open" @if(old('bid_status')=='open' ) selected @endif>
                                                    Open
                                                </option>
                                                <option value="closed" @if(old('bid_status')=='closed' ) selected
                                                    @endif>Closed
                                                    Event
                                                </option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-sm-12 col-xl-4 col-lg-6 mb-15">
                                        <div class="form-group">
                                            <label class="w-100 font-weight-bold">@lang('Category') <span
                                                    class="text-danger">*</span></label>
                                            <select name="category" class="form-control" required>
                                                <option value="">@lang('Select One')</option>
                                                @foreach ($categories as $category)
                                                <option value="{{ $category->id }}" @if(old('category')==$category->id)
                                                    selected @endif>{{ $category->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-sm-12 col-xl-4 col-lg-6 mb-15">
                                        <div class="form-group">
                                            <label class="w-100 font-weight-bold">@lang('Event Type') <span
                                                    class="text-danger">*</span></label>
                                            <select name="event_type" class="form-control">
                                                <option value="m_cultivo_event" @if(old('event_type')=='m_cultivo_event'
                                                    ) selected @endif>M
                                                    Cultivo Event
                                                </option>
                                                <option value="ace_event" @if(old('event_type')=='ace_event' ) selected
                                                    @endif>ACE
                                                    Event
                                                </option>
                                            </select>
                                        </div>
                                    </div>


                                    <div class="col-sm-12 col-xl-4 col-lg-6 mb-15">
                                        <div class="form-group">
                                            <label class="w-100 font-weight-bold">@lang('Practice Event') <span
                                                    class="text-danger">*</span></label>
                                            <select name="practice" class="form-control">
                                                <option value="0" @if(old('practice')=='0' ) selected @endif>NO
                                                </option>
                                                <option value="1" @if(old('practice')=='1' ) selected @endif>YES
                                                </option>

                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-sm-12 col-xl-4 col-lg-6 mb-15 started_at">
                                        <div class="form-group">
                                            <label class="w-100 font-weight-bold">@lang('Start Date') <span
                                                    class="text-danger">*</span></label>
                                            <input type="text" name="start_date"
                                                placeholder="@lang('Select Date & Time')" id="startDateTime"
                                                data-position="bottom left" class="form-control border-radius-5 d-picker"
                                                value="{{ old('start_date') }}" autocomplete="off" required />
                                        </div>
                                    </div>
                                    <div class="col-sm-12 col-xl-4 col-lg-6 mb-15">
                                        <div class="form-group">
                                            <label class="w-100 font-weight-bold">@lang('Auction End Date') <span
                                                    class="text-danger">*</span></label>
                                            <input type="text" name="end_date" placeholder="@lang('Select Date & Time')"
                                                id="endDateTime" data-position="bottom left"
                                                class="form-control border-radius-5 d-picker" value="{{ old('end_date') }}"
                                                autocomplete="off" required />
                                        </div>
                                    </div>
                                    <div class="col-sm-12 col-xl-4 col-lg-6 mb-15">
                                        <div class="form-group">
                                            <label class="w-100 font-weight-bold">@lang('Display End Date') <span
                                                    class="text-danger">*</span></label>
                                            <input type="text" name="display_end_date" placeholder="@lang('Select Date & Time')"
                                                id="displayEndDateTime" data-position="bottom left"
                                                class="form-control border-radius-5 d-picker" value="{{ old('display_end_date') }}"
                                                autocomplete="off" required />
                                        </div>
                                    </div>
                                    <div class="col-sm-12 col-xl-4 col-lg-6 mb-15">
                                        <div class="form-group">
                                            <label class="w-100 font-weight-bold">@lang('Max End Date')</label>
                                            <input type="text" name="max_end_date"
                                                placeholder="@lang('Select Date & Time')" id="maxendDateTime"
                                                data-position="bottom left" class="form-control border-radius-5 d-picker"
                                                value="{{ old('max_end_date') }}" autocomplete="off" />
                                        </div>
                                    </div>


                                    <div class="col-sm-12 col-xl-4 col-lg-6 mb-15">
                                        <div class="form-group">
                                            <label class="w-100 font-weight-bold">@lang('Less Bidding Time (m)')
                                                <span class="text-danger">*</span></label>
                                            <input type="number" class="form-control "
                                                placeholder="@lang('Less Bidding Time')" name="less_bidding_time"
                                                min="1" value="{{ old('less_bidding_time') }}" required />
                                        </div>
                                    </div>


                                    <div class="col-sm-12 col-xl-4 col-lg-6 mb-15">
                                        <div class="form-group">
                                            <label class="w-100 font-weight-bold">@lang('Deposit Percentage (%)')
                                                <span class="text-danger">*</span></label>
                                            <input type="number" class="form-control " step=".01"
                                                placeholder="@lang('Deposit Percentage(%)')" name="deposit" min="0"
                                                value="{{ old('deposit') }}" required />
                                        </div>
                                    </div>
                                    <div class="col-sm-12 col-xl-4 col-lg-6 mb-15">
                                        <div class="form-group">
                                            <label class="w-100 font-weight-bold">@lang('Max Bid Increment') <span
                                                    class="text-danger">*</span></label>
                                            <div class="input-group has_append">
                                                <input type="text" class="form-control " placeholder="0"
                                                    name="max_bidding_value" value="{{ old('max_bidding_value') }}"
                                                    required />
                                                <div class="input-group-append">
                                                    <span class="input-group-text">{{ $general->cur_text}}</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-12 col-xl-4 col-lg-6 mb-15">
                                        <div class="form-group">
                                            <label class="w-100 font-weight-bold">@lang('Event Clock Starts On')
                                                <span class="text-danger">*</span></label>
                                            <div class="form-check">
                                                <input type="radio" class="form-check-input"
                                                    placeholder="@lang('All product have a bid')"
                                                    name="EventClockStartOn" value="0" @if(!old('EventClockStartOn'))
                                                    checked @endif />
                                                <label class="form-check-label">
                                                    All product have a bid
                                                </label>
                                            </div>
                                            <div class="form-check">
                                                <input type="radio" class="form-check-input"
                                                    placeholder="@lang('Manually')" name="EventClockStartOn" value="1"
                                                    @if(old('EventClockStartOn')) checked @endif />
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
                                            <textarea rows="4" class="form-control border-radius-5"
                                                name="description">{{ old('description') }}</textarea>
                                        </div>
                                    </div>

                                    <div class="col-12">
                                        <div class="form-group">
                                            <label class="font-weight-bold">@lang('Agreement')<span
                                                    class="text-danger">*</span></label>
                                            <textarea rows="10" cols="50" id="area1"
                                                name="agreement">{{ old('agreement') }}</textarea>
                                        </div>
                                    </div>

                                </div>
                            </div>


                            <div class="col-lg-12 regiondata addregionhere" data-shippingregions="1">
                                <div class="card border--primary mt-3">
                                    <h5 class="card-header bg--primary  text-white">@lang('Shipping Region')
                                        <button class="btn btn--danger btn-lg removeRegion float-right w-20"
                                            type="button">
                                            <i class="fa fa-times"></i>
                                        </button>
                                    </h5>
                                    <div class="row col-12 mb-15">
                                        <div class="form-group col-6">
                                            <label class="w-100 font-weight-bold">@lang('Region name') <span
                                                    class="text-danger">*</span></label>
                                            <select required class="form-control "
                                                name="shippingregions[1][region_name]">
                                                <option value="">@lang('Select Region')</option>
                                                @foreach($regions as $region)
                                                <option value="{{ $region->id }}">{{ $region->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="form-group col-6">
                                            <label class="w-100 font-weight-bold">@lang('Shipping method') <span
                                                    class="text-danger">*</span></label>
                                            <select required class="form-control "
                                                name="shippingregions[1][shipping_method]">
                                                <option value="">@lang('Select shipping method')</option>
                                                <option value="Air Freight (expedited)">@lang('Air Freight (expedited)')
                                                </option>
                                                <option value="Air Freight (Palletized)">@lang('Air Freight
                                                    (Palletized)')</option>
                                                <option value="Ocean Freight">@lang('Ocean Freight')</option>

                                            </select>
                                        </div>
                                    </div>
                                    <h5 class="card-header bg--primary  text-white">@lang('Ranges')
                                        <button type="button"
                                            class="btn btn-sm btn-outline-light float-right addUserData"><i
                                                class="la la-fw la-plus"></i>@lang('Add New Ranges')
                                        </button>
                                    </h5>
                                    <div class="card-body">
                                        <div class="row addedField">

                                            <div class="col-md-12 user-data">
                                                <div class="form-group">
                                                    <div class="input-group mb-md-0 mb-4">
                                                        <div class="col-md-3">
                                                            <label class="w-100 font-weight-bold">@lang('From')
                                                                <span class="text-danger">*</span></label>
                                                            <input name="shippingranges[1][1][from]"
                                                                class="form-control" type="text" required
                                                                placeholder="@lang('From')">
                                                        </div>
                                                        <div class="col-md-3">
                                                            <label class="w-100 font-weight-bold">@lang('Up To')
                                                                <span class="text-danger">*</span></label>
                                                            <input name="shippingranges[1][1][up_to]"
                                                                class="form-control" type="text" required
                                                                placeholder="@lang('Up To')">
                                                        </div>
                                                        <div class="col-md-3">
                                                            <label class="w-100 font-weight-bold">@lang('Cost')
                                                                <span class="text-danger">*</span></label>
                                                            <input name="shippingranges[1][1][cost]"
                                                                class="form-control" type="text" required
                                                                placeholder="@lang('Cost')">
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

                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-12 regiondata">
                            <div class="card border--primary mt-3">
                            <h5 class="card-header bg--primary  text-white">@lang('Handling Fee')
                                    <button type="button"
                                    class="btn btn-sm btn-outline-light float-right addFee"><i
                                        class="la la-fw la-plus"></i>@lang('Add New Fees')
                                    </button>
                                </h5>
                                <div class="card-body">
                                    <div class="row newFee">
                                        <div class="row col-12 mb-15 user-data">
                                            <div class="form-group col-5 mb-4">
                                                <label class="w-100 font-weight-bold">@lang('Country name') <span
                                                        class="text-danger">*</span></label>
                                                <select required class="form-control "
                                                    name="fees[1][country_id]">
                                                    <option value="">@lang('Select Country')</option>
                                                    @foreach($countries as $country)
                                                    <option value="{{ $country->id }}">{{ $country->Name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="form-group col-4 mb-4">
                                                <label class="w-100 font-weight-bold">@lang('Credit Card Processing %') <span
                                                        class="text-danger">*</span></label>
                                                <input type="number" min=".01" max="100" step=".01" required class="form-control"
                                                    name="fees[1][fee_value]" placeholder="@lang('Credit Card Processing %')"/>
                                            </div>
                                            <div class="col-md-2 mt-md-0 mt-2 text-right align-self-end mb-4">
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
                            </div>
                         </div>

                        </div>


                        <div class="card border--primary mt-3">
                            <button type="button"
                                class="btn btn-sm btn-outline-light bg--primary text-white addRegionData"><i
                                    class="la la-fw la-plus"></i>@lang('Add New Region')
                            </button>
                        </div>


                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection


@push('breadcrumb-plugins')
<a href="{{ route('admin.event.index') }}" class="btn btn-sm btn--primary box--shadow1 text--small"><i
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



<!-- multiSelect style -->
@push('style')
<style>
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
    (function ($) {
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
                onSelect: function (fd, d, picker) {
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
            $(document).on('click', '.addUserData', function () {
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

            $(document).on('click', '.addFee', function () {
                // var tempregion = $(this).closest('.regiondata').attr('data-feeregions');
                var html = `
                <div class="row col-12 mb-15 user-data">
                                        <div class="form-group col-5 mb-4">
                                            <label class="w-100 font-weight-bold">@lang('Country name') <span
                                                    class="text-danger">*</span></label>
                                            <select required class="form-control "
                                                name="fees[${specCount}][country_id]">
                                                <option value="">@lang('Select Country')</option>
                                                @foreach($countries as $country)
                                                <option value="{{ $country->id }}">{{ $country->Name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="form-group col-4 mb-4">
                                            <label class="w-100 font-weight-bold">@lang('Credit Card Processing %') <span
                                                    class="text-danger">*</span></label>
                                            <input type="number" min=".01" max="100" step=".01" required class="form-control"
                                                name="fees[${specCount}][fee_value]" placeholder="@lang('Credit Card Processing %')"/>
                                        </div>
                                        <div class="col-md-2 mt-md-0 mt-2 text-right align-self-end mb-4">
                                            <span class="input-group-btn">
                                                <button class="btn btn--danger btn-lg removeBtn w-100"
                                                    type="button">
                                                    <i class="fa fa-times"></i>
                                                </button>
                                            </span>
                                        </div>
                                    </div>
                `;
                $(this).closest('.regiondata').find('.newFee').append(html);
                specCount += 1;
            });

            $(document).on('click', '.removeBtn', function () {
                $(this).closest('.user-data').remove();
            });

            $('.addRegionData').on('click', function () {
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
                                                    @foreach($regions as $region)
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
            <div class="card-body">
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
                        </div>
                    </div>`;
                $('.addregionhere').append(html);
                regionCount += 1;
            });

            $(document).on('click', '.removeRegion', function () {
                $(this).closest('.regiondata').remove();
            });
        })(jQuery);
</script>
<script type="text/javascript">
    bkLib.onDomLoaded(function () {
            new nicEditor().panelInstance('area1');
        });
</script>
<script>
    $(function () {
            $('#country').multiSelect();
        });
</script>
@endpush
