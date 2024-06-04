@extends('admin.layouts.app')
@section('panel')
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <form action="{{ route('admin.product.update', $product->id) }}" method="POST" enctype="multipart/form-data">
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
                                                style="background-image: url('{{ getImage(imagePath()['product']['path'] . '/' . $product->image, imagePath()['product']['size'], false, 'sm') }}')">
                                            </div>
                                        </div>
                                        <div class="avatar-edit" style="bottom:40px">
                                            <input type="file" name="image" class="profilePicUpload" id="image"
                                                accept=".png, .jpg, .jpeg" />
                                            <label for="image" class="bg--primary"><i class="la la-pencil"></i></label>
                                        </div>
                                        <div class="mt-2 font-small text-center">@lang('images between 1500 pixels and 2500 pixels wide')</div>
                                    </div>

                                    <button type="submit" class="btn btn--primary btn-block"
                                        style="margin-top:-15px;">@lang('Submit')</button>
                                </div>

                                <div class="content">
                                    <div class="row mb-none-15">
                                        <div class="col-sm-12 col-xl-4 col-lg-6">
                                            <div class="form-group">
                                                <label class="font-weight-bold">@lang('Name') <span
                                                        class="text-danger">*</span></label>
                                                <input type="text" class="form-control " placeholder="@lang('Product Name')"
                                                    name="name" value="{{ old('name', $product->name) }}" required />
                                            </div>
                                        </div>
                                        <div class="col-sm-12 col-xl-4 col-lg-6">
                                            <div class="form-group">
                                                <label class="font-weight-bold">@lang('Event') <span
                                                        class="text-danger">*</span></label>
                                                <select name="event_id" class="form-control" required>
                                                    <option value="">@lang('Select One')</option>
                                                    @foreach ($events as $event)
                                                        <option value="{{ $event->id }}"
                                                            {{ $product->event_id == $event->id ? 'Selected' : '' }}>
                                                            {{ $event->name . ' ' . $event->sname }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-sm-12 col-xl-4 col-lg-6 mb-15">
                                            <div class="form-group">
                                                <label class="w-100 font-weight-bold">@lang('Merchant') <span
                                                        class="text-danger">*</span></label>
                                                <select name="merchant_id" class="form-control" required>
                                                    <option value="">@lang('Select One')</option>
                                                    <option value="{{ 0 }}"
                                                        {{ $product->merchant_id == 0 ? 'Selected' : '' }}>
                                                        @lang('Admin')
                                                    </option>
                                                    @foreach ($merchants as $merchant)
                                                        <option value="{{ $merchant->id }}"
                                                            {{ $product->merchant_id == $merchant->id ? 'Selected' : '' }}>
                                                            {{ $merchant->firstname }} {{ $merchant->lastname }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-sm-12 col-xl-4 col-lg-6">
                                            <label class="font-weight-bold">@lang('Price') <span
                                                    class="text-danger">*</span></label>
                                            <div class="input-group has_append">
                                                <input type="text" class="form-control" placeholder="0" name="price"
                                                    value="{{ getAmount(old('price', $product->price)) }}" required />
                                                <div class="input-group-append">
                                                    <span class="input-group-text">{{ $general->cur_text }}</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-12 col-xl-4 col-lg-6 mb-15">
                                            <label class="w-100 font-weight-bold">@lang('Max Auto Bid Price')</label>
                                            <div class="input-group has_append">
                                                <input type="text" class="form-control" placeholder="0"
                                                    name="max_auto_bid_price"
                                                    value="{{ old('max_auto_bid_price', $product->max_auto_bid_price) }}" />
                                                <div class="input-group-append">
                                                    <span class="input-group-text">{{ $general->cur_text }}</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-12 col-xl-4 col-lg-6 mb-15">
                                            <label class="w-100 font-weight-bold">@lang('Max Auto Bid Steps') </label>
                                            <div class="input-group has_append">
                                                <input type="text" class="form-control" placeholder="0"
                                                    name="max_auto_bid_steps"
                                                    value="{{ old('max_auto_bid_steps', $product->max_auto_bid_steps) }}" />
                                            </div>
                                        </div>

                                        <div class="col-sm-12 col-xl-4 col-lg-6 mb-15">
                                            <div class="form-group">
                                                <label class="w-100 font-weight-bold">@lang('Less Bidding Value') <span
                                                        class="text-danger">*</span></label>
                                                <div class="input-group has_append">
                                                    <input type="text" class="form-control " placeholder="0"
                                                        name="less_bidding_value"
                                                        value="{{ old('less_bidding_value', $product->less_bidding_value) }}"
                                                        required />
                                                    <div class="input-group-append">
                                                        <span class="input-group-text">{{ $general->cur_text }}</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-12 col-xl-4 col-lg-6 mb-15">
                                            <div class="form-group">
                                                <label class="w-100 font-weight-bold">@lang('Product Color')</label>
                                                <select name="color_class" class="form-control">
                                                    <option value="">@lang('Select One')</option>
                                                    @foreach ($colors as $key => $color)
                                                        <option value="{{ $key }}"
                                                            @if (old('color_class', $product->color_class) == $key) selected @endif>
                                                            {{ $color }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-sm-12 col-xl-4 col-lg-6 mb-15"></div>
                                        <div class="col-sm-12 col-xl-4 col-lg-6">
                                            <div class="form-group">
                                                <label class="font-weight-bold">@lang('Schedule') <span
                                                        class="text-danger">*</span></label>
                                                <select name="schedule" class="form-control" required>
                                                    <option value="1"
                                                        {{ $product->started_at > now() ? 'Selected' : '' }}>
                                                        @lang('Yes')</option>
                                                    <option value="0">@lang('No')</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-sm-12 col-xl-4 col-lg-6 started_at">
                                            <div class="form-group">
                                                <label class="font-weight-bold">@lang('Started at') <span
                                                        class="text-danger">*</span></label>
                                                <input type="text" name="started_at" placeholder="@lang('Select Date & Time')"
                                                    id="startDateTime" data-position="bottom left"
                                                    class="form-control border-radius-5"
                                                    value="{{ old('started_at', $product->started_at) }}"
                                                    autocomplete="off" required />
                                            </div>
                                        </div>
                                        <div class="col-sm-12 col-xl-4 col-lg-6">
                                            <div class="form-group">
                                                <label class="font-weight-bold">@lang('Expired at') <span
                                                        class="text-danger">*</span></label>
                                                <input type="text" name="expired_at" placeholder="@lang('Select Date & Time')"
                                                    id="endDateTime" data-position="bottom left"
                                                    class="form-control border-radius-5"
                                                    value="{{ old('expired_at', $product->expired_at) }}"
                                                    autocomplete="off" required />
                                            </div>
                                        </div>


                                        <div class="col-12">
                                            <div class="form-group">
                                                <label class="font-weight-bold">@lang('Short Description')</label>
                                                <textarea rows="4" class="form-control border-radius-5" name="short_description">{{ old('short_description', $product->short_description) }}</textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>


                            <div class="form-group mt-3">
                                <label class="font-weight-bold">@lang('Long Description')</label>
                                <textarea rows="8" class="form-control border-radius-5 nicEdit" name="long_description">{{ old('long_description', $product->long_description) }}</textarea>
                            </div>

                            <div class="row">

                                <div class="col-lg-12">
                                    <div class="card border--primary mt-3">
                                        <h5 class="card-header bg--primary  text-white">@lang('Specification')
                                            <button type="button"
                                                class="btn btn-sm btn-outline-light float-right addUserData"><i
                                                    class="la la-fw la-plus"></i>@lang('Add New')
                                            </button>
                                        </h5>

                                        <div class="card-body">
                                            <div class="row addedField">
                                                @if ($product->product_specification)
                                                    @foreach ($product->product_specification as $spec)
                                                        @if ($spec->spec_key != 'Weight')
                                                            <div class="col-md-12 user-data">
                                                                <div class="form-group">
                                                                    <div class="input-group mb-md-0 mb-4">
                                                                        <div class="col-md-4">
                                                                            <input
                                                                                name="specification[{{ $loop->iteration }}][name]"
                                                                                class="form-control" type="text"
                                                                                value="{{ $spec->spec_key }}" required
                                                                                placeholder="@lang('Field Name')">
                                                                        </div>
                                                                        <div class="col-md-4">
                                                                            <input
                                                                                name="specification[{{ $loop->iteration }}][value]"
                                                                                class="form-control" type="text"
                                                                                value="{{ $spec->Value }}" required
                                                                                placeholder="@lang('Field Value')">
                                                                        </div>
                                                                        <div class="col-md-1">
                                                                            <input
                                                                                name="specification[{{ $loop->iteration }}][is_display]"
                                                                                class="form-check-input" type="radio"
                                                                                value="1"
                                                                                {{ $spec->is_display == '1' ? 'checked' : '' }}
                                                                                required placeholder="@lang('View In Product Card')">
                                                                            <label class="form-check-label"
                                                                                for="flexRadioDefault1">display</label>
                                                                        </div>
                                                                        <div class="col-md-1">
                                                                            <input
                                                                                name="specification[{{ $loop->iteration }}][is_display]"
                                                                                class="form-check-input" type="radio"
                                                                                value="0"
                                                                                {{ $spec->is_display == '0' ? 'checked' : '' }}
                                                                                required placeholder="@lang('NO')">
                                                                            <label class="form-check-label"
                                                                                for="flexRadioDefault1">no display</label>
                                                                        </div>
                                                                        <div class="col-md-2 mt-md-0 mt-2 text-right">
                                                                            <span class="input-group-btn">
                                                                                <button
                                                                                    class="btn btn--danger btn-lg removeBtn w-100"
                                                                                    type="button">
                                                                                    <i class="fa fa-times"></i>
                                                                                </button>
                                                                            </span>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        @else
                                                            <div class="col-md-12 user-data">
                                                                <div class="form-group">
                                                                    <div class="input-group mb-md-0 mb-4">
                                                                        <div class="col-md-4">
                                                                            <input
                                                                                name="specification[{{ $loop->iteration }}][name]"
                                                                                class="form-control" readonly
                                                                                type="text"
                                                                                value="{{ $spec->spec_key }}" required
                                                                                placeholder="@lang('Field Name')">
                                                                        </div>
                                                                        <div class="col-md-4">
                                                                            <input
                                                                                name="specification[{{ $loop->iteration }}][value]"
                                                                                class="form-control" type="number"
                                                                                step="0.01" min="0.01"
                                                                                value="{{ $spec->Value }}" required
                                                                                placeholder="@lang('Field Value')">
                                                                        </div>
                                                                        <div class="col-md-1">
                                                                            <input
                                                                                name="specification[{{ $loop->iteration }}][is_display]"
                                                                                class="form-check-input" type="radio"
                                                                                value="1" checked required
                                                                                placeholder="@lang('View In Product Card')">
                                                                            <label class="form-check-label"
                                                                                for="flexRadioDefault1">display</label>
                                                                        </div>
                                                                        <div class="col-md-1">
                                                                            <input
                                                                                name="specification[{{ $loop->iteration }}][is_display]"
                                                                                class="form-check-input" type="radio"
                                                                                value="0" disabled required
                                                                                placeholder="@lang('NO')">
                                                                            <label class="form-check-label"
                                                                                for="flexRadioDefault1">no display</label>
                                                                        </div>
                                                                        <div class="col-md-2 mt-md-0 mt-2 text-right">
                                                                            <span class="input-group-btn"
                                                                                style="display: none">
                                                                                <button disabled
                                                                                    class="btn btn--danger btn-lg removeBtn w-100"
                                                                                    type="button">
                                                                                    <i class="fa fa-times"></i>
                                                                                </button>
                                                                            </span>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        @endif
                                                    @endforeach
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    {{-- <div class="card-footer">
                        <button type="submit" class="btn btn--primary btn-block">@lang('Submit')</button>
                    </div> --}}
                </form>
            </div>
        </div>
    </div>

@endsection


@push('breadcrumb-plugins')
    <a href="{{ route('admin.product.index') }}" class="btn btn-sm btn--primary box--shadow1 text--small"><i
            class="la la-fw la-backward"></i> @lang('Go Back') </a>
@endpush


@push('script-lib')
    <script src="{{ asset('assets/admin/js/vendor/datepicker.min.js') }}"></script>
    <script src="{{ asset('assets/admin/js/vendor/datepicker.en.js') }}"></script>
@endpush

@push('script')
    <script>
        (function($) {
            "use strict";

            var specCount = `{{ $product->specification ? count($product->specification) : 0 }}`;
            specCount = parseInt(specCount);
            specCount = specCount ? specCount + 1 : 1;

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
