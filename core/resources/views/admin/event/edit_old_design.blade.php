@extends('admin.layouts.app')
@section('panel')
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <form action="{{ route('admin.event.update', $event->id) }}" method="POST"
                      enctype="multipart/form-data">
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
                                                 style="background-image: url('{{getImage(imagePath()['event']['path'].'/'.$event->image,imagePath()['event']['size'])}}')"></div>
                                        </div>
                                        <div class="avatar-edit">
                                            <input type="file" name="image" class="profilePicUpload" id="image"
                                                   accept=".png, .jpg, .jpeg"/>
                                            <label for="image" class="bg--primary"><i class="la la-pencil"></i></label>
                                        </div>
                                    </div>
                                    @if ($event->status == 'active')
                                        <button type="submit" class="btn btn--primary btn-block" style="margin-top:-15px;">@lang('Submit')</button>
                                        <a class="btn btn--primary btn-block" href="{{ route('admin.product.create',['id'=>$event->id]) }}" style="margin-top:50px;"><i class="fa fa-fw fa-plus"></i>@lang('Add Product')</a>
                                        <input type="hidden"  form="event_product" name="event_id" value="{{$event->id}}">
                                        <button type="submit" form="event_product" class="btn btn--primary btn-block" style="margin-top:5px;">
                                            <i class="menu-icon lab la-product-hunt"></i>
                                            <span style="color:#ffffff;">@lang("Event's Products")</span>

                                            <span class="badge pill ml-auto" style="background-color:#ffffff;font-weight: bold;">{{$event->products->count()}}</span>

                                        </button>
                                        <a class="btn btn-success btn-block" href="{{route('admin.event.start',['id'=>$event->id])}}" style="margin-top:50px;">
                                            @lang('Start Clock')
                                        </a>
                                        <button type="button" onclick="checkAndEndEvent()"
                                        class="btn btn--primary btn-block" style="background-color:red !important;margin-top:50px;"> @lang('End Event') </button>
                                    @endif
                                </div>

                                <div class="content">
                                    <div class="row mb-none-15">
                                        <div class="col-sm-12 col-xl-4 col-lg-6 mb-15">
                                            <div class="form-group">
                                                <label class="w-100 font-weight-bold">@lang('Name') <span class="text-danger">*</span></label>
                                                <input type="text" class="form-control " placeholder="@lang('Event Name')" name="name" value="{{ old('name',$event->name) }}" required/>
                                            </div>
                                        </div>
                                        <div class="col-sm-12 col-xl-4 col-lg-6 mb-15">
                                            <div class="form-group">
                                                <label class="w-100 font-weight-bold">@lang('Secondary Name')</label>
                                                <input type="text" class="form-control " placeholder="@lang('Secondary Name')" name="sname" value="{{ old('sname',$event->sname) }}"/>
                                            </div>
                                        </div>
                                        <div class="col-sm-12 col-xl-4 col-lg-6 mb-15">
                                            <div class="form-group">
                                                <label class="w-100 font-weight-bold">@lang('Event Bid Status') <span
                                                        class="text-danger">*</span></label>
                                                <select name="bid_status" class="form-control">
                                                    <option value="open"
                                                            @if($event->bid_status=='open') selected @endif >Open
                                                    </option>
                                                    <option value="closed"
                                                            @if($event->bid_status=='closed') selected @endif>Closed

                                                    </option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-sm-12 col-xl-4 col-lg-6">
                                            <div class="form-group">
                                                <label class="font-weight-bold">@lang('Category') <span
                                                        class="text-danger">*</span></label>
                                                <select name="category" class="form-control" required>
                                                    <option value="">@lang('Select One')</option>
                                                    @foreach ($categories as $category)
                                                        <option
                                                            value="{{ $category->id }}" {{ $event->category_id == $category->id ? 'Selected':'' }}>{{ $category->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-sm-12 col-xl-4 col-lg-6 mb-15">
                                            <div class="form-group">
                                                <label class="w-100 font-weight-bold">@lang('Event Type') <span
                                                        class="text-danger">*</span></label>
                                                <select name="event_type" class="form-control">
                                                    <option value="m_cultivo_event"
                                                            @if($event->event_type == "m_cultivo_event") selected @endif>
                                                        M Cultivo Event
                                                    </option>
                                                    <option value="ace_event"
                                                            @if($event->event_type == "ace_event") selected @endif>ACE
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
                                                        <option value="1"
                                                                @if($event->practice=='1') selected @endif >YES
                                                        </option>
                                                        <option value="0"
                                                                @if($event->practice=='0') selected @endif>NO
                                                        </option>
                                                    </select>
                                                </div>
                                            </div>



                                        <div class="col-sm-12 col-xl-4 col-lg-6 mb-15 started_at">
                                            <div class="form-group">
                                                <label class="w-100 font-weight-bold">@lang('Start Date') <span class="text-danger">*</span></label>
                                                <input type="text" name="start_date" placeholder="@lang('Select Date & Time')" id="startDateTime" data-position="bottom left" class="form-control border-radius-5 d-picker" value="{{ old('start_date',$event->start_date) }}" autocomplete="off" required/>
                                            </div>
                                        </div>
                                        <div class="col-sm-12 col-xl-4 col-lg-6 mb-15">
                                            <div class="form-group">
                                                <label class="w-100 font-weight-bold">@lang('Auction End Date') <span class="text-danger">*</span></label>
                                                <input type="text" name="end_date" placeholder="@lang('Select Date & Time')" id="endDateTime" data-position="bottom left" class="form-control border-radius-5 d-picker" value="{{ old('end_date',$event->end_date)  }}" autocomplete="off" required/>
                                            </div>
                                        </div>
                                        <div class="col-sm-12 col-xl-4 col-lg-6 mb-15">
                                            <div class="form-group">
                                                <label class="w-100 font-weight-bold">@lang('Display End Date') <span class="text-danger">*</span></label>
                                                <input type="text" name="display_end_date" placeholder="@lang('Select Date & Time')" id="displayEndDateTime" data-position="bottom left" class="form-control border-radius-5 d-picker" value="{{ old('display_end_date',$event->display_end_date)  }}" autocomplete="off" required/>
                                            </div>
                                        </div>
                                        <div class="col-sm-12 col-xl-4 col-lg-6 mb-15">
                                            <div class="form-group">
                                                <label class="w-100 font-weight-bold">@lang('Max End Date')</label>
                                                <input type="text" name="max_end_date" placeholder="@lang('Select Date & Time')" id="maxendDateTime" data-position="bottom left" class="form-control border-radius-5 d-picker" value="{{ old('max_end_date',$event->max_end_date)  }}" autocomplete="off"/>
                                            </div>
                                        </div>
                                        <div class="col-sm-12 col-xl-4 col-lg-6 mb-15">
                                            <div class="form-group">
                                                <label class="w-100 font-weight-bold">@lang('Less Bidding Time (m)') <span class="text-danger">*</span></label>
                                                <input type="number" class="form-control " placeholder="@lang('Less Bidding Time')" name="less_bidding_time" min="1" value="{{ old('less_bidding_time',$event->less_bidding_time) }}" required/>
                                            </div>
                                        </div>


                                        <div class="col-sm-12 col-xl-4 col-lg-6 mb-15">
                                            <div class="form-group">
                                                <label class="w-100 font-weight-bold">@lang('Deposit Percentage (%)') <span class="text-danger">*</span></label>
                                                <input type="number" class="form-control " placeholder="@lang('Deposit Percentage(%)')" name="deposit" min="0" value="{{ old('deposit',$event->deposit) }}" step=".01" required/>
                                            </div>
                                        </div>
                                        <div class="col-sm-12 col-xl-4 col-lg-6 mb-15">
                                            <div class="form-group">
                                                <label class="w-100 font-weight-bold">@lang('Max Bid Increment') <span class="text-danger">*</span></label>
                                                <div class="input-group has_append">
                                                <input type="text" class="form-control " placeholder="0" name="max_bidding_value" value="{{ old('max_bidding_value',$event->max_bidding_value) }}" required/>
                                                <div class="input-group-append">
                                                    <span class="input-group-text">{{$general->cur_text}}</span>
                                                </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-12 col-xl-4 col-lg-6 mb-15">
                                            <div class="form-group">
                                                <label class="w-100 font-weight-bold">@lang('Event Clock Starts On')
                                                    <span class="text-danger">*</span></label>
                                                <div class="form-check">
                                                <input type="radio" class="form-check-input" placeholder="@lang('All product have a bid')" name="EventClockStartOn" value="0" @if(old('EventClockStartOn',$event->EventClockStartOn)==0) checked @endif/>
                                                <label class="form-check-label">
                                                All product have a bid
                                                </label>
                                                </div>
                                                <div class="form-check">
                                                <input type="radio" class="form-check-input" placeholder="@lang('Manually')" name="EventClockStartOn" value="1" @if(old('EventClockStartOn',$event->EventClockStartOn)==1) checked @endif/>
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
                                                <textarea rows="4" class="form-control border-radius-5" name="description">{{ old('description',$event->description) }}</textarea>
                                            </div>
                                        </div>

                                        <div class="col-12">
                                            <div class="form-group">
                                                <label class="font-weight-bold">@lang('Agreement')<span
                                                        class="text-danger">*</span></label>
                                                <textarea rows="10" cols="50" id="area1" name="agreement">{{ old('agreement',$event->agreement) }}</textarea>
                                            </div>
                                        </div>

                                    </div>
                                </div>

                                <?php $j = 1;?>
                                @foreach ($shippingregions as $shippingregion)
                                    <div class="col-lg-12 regiondata addregionhere" data-shippingregions="{{$j}}">
                                        <div class="card border--primary mt-3">
                                            <h5 class="card-header bg--primary  text-white">@lang('Shipping Region')
                                                <button class="btn btn--danger btn-lg removeRegion float-right w-20"
                                                        type="button">
                                                    <i class="fa fa-times"></i>
                                                </button>
                                            </h5>
                                            <div class="row col-12 ">
                                                <div class="form-group  col-6">
                                                    <label class="w-100 font-weight-bold">@lang('Region name') <span
                                                            class="text-danger">*</span></label>
                                                    <select required class="form-control "
                                                            name="shippingregions[{{$j}}][region_name]">
                                                        <option value="">@lang('Select Region')</option>
                                                        @foreach($regions as $region)
                                                            <option value="{{ $region->id }}" @if($region->id == $shippingregion->region_name) selected @endif>{{ $region->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="form-group col-6">
                                                    <label class="w-100 font-weight-bold">@lang('Shipping method') <span
                                                            class="text-danger">*</span></label>
                                                    <select required class="form-control "
                                                            name="shippingregions[{{$j}}][shipping_method]">
                                                        <option value="">@lang('Select shipping method')</option>
                                                        <option value="Air Freight (expedited)" @if($shippingregion->shipping_method == 'Air Freight (expedited)') selected @endif>@lang('Air Freight (expedited)')</option>

                                                        <option
                                                            value="Air Freight (Palletized)"  @if($shippingregion->shipping_method == 'Air Freight (Palletized)') selected @endif>@lang('Air Freight (Palletized)')</option>
                                                        <option value="Ocean Freight"  @if($shippingregion->shipping_method == 'Ocean Freight') selected @endif>@lang('Ocean Freight')</option>

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
                                                    <?php $i = 1;?>
                                                    @foreach ($rangesarray[$shippingregion->id] as $range)
                                                        <div class="col-md-12 user-data">
                                                            <div class="form-group">
                                                                <div class="input-group mb-md-0 mb-4">
                                                                    <div class="col-md-3">
                                                                        <label
                                                                            class="w-100 font-weight-bold">@lang('From')
                                                                            <span class="text-danger"></span></label>
                                                                        <input
                                                                            name="shippingranges[{{$j}}][{{ $i }}][from]"
                                                                            value="{{$range['from']}}"
                                                                            class="form-control" type="text" required
                                                                            placeholder="@lang('From')">
                                                                    </div>
                                                                    <div class="col-md-3">
                                                                        <label
                                                                            class="w-100 font-weight-bold">@lang('Up To')
                                                                            <span class="text-danger"></span></label>
                                                                        <input
                                                                            name="shippingranges[{{$j}}][{{ $i }}][up_to]"
                                                                            value="{{$range['up_to']}}"
                                                                            class="form-control" type="text" required
                                                                            placeholder="@lang('Up To')">
                                                                    </div>
                                                                    <div class="col-md-3">
                                                                        <label
                                                                            class="w-100 font-weight-bold">@lang('Cost')
                                                                            <span class="text-danger"></span></label>
                                                                        <input
                                                                            name="shippingranges[{{$j}}][{{ $i }}][cost]"
                                                                            value="{{$range['cost']}}"
                                                                            class="form-control" type="text" required
                                                                            placeholder="@lang('Cost')">
                                                                    </div>
                                                                    <div
                                                                        class="col-md-2 mt-md-0 mt-2 text-right align-self-end">
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
                                                        <?php $i++;?>
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <?php $j++;?>
                                @endforeach
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
                                                <?php $i = 1;?>
                                                @foreach ($fees as $fee)
                                                <div class="row col-12 mb-15 user-data">
                                                    <div class="form-group col-5 mb-4">
                                                        <label class="w-100 font-weight-bold">@lang('Country name') <span
                                                                class="text-danger">*</span></label>
                                                        <select required class="form-control "
                                                            name="fees[{{$i}}][country_id]">
                                                            <option value="">@lang('Select Country')</option>
                                                            @foreach($countries as $country)
                                                            <option value="{{ $country->id }}" @if($country->id ==  $fee->country_id) selected @endif>{{ $country->Name }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div class="form-group col-4 mb-4">
                                                        <label class="w-100 font-weight-bold">@lang('Credit Card Processing %') <span
                                                                class="text-danger">*</span></label>
                                                        <input type="number" min=".01" max="100" step=".01" value="{{$fee->fee_value}}" required class="form-control"
                                                            name="fees[{{$i}}][fee_value]" placeholder="@lang('Credit Card Processing %')"/>
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
                                                <?php $i++;?>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                            @if ($event->status == 'active')

                            <div class="card border--primary mt-3">
                                <button type="button"
                                        class="btn btn-sm btn-outline-light bg--primary text-white addRegionData"><i
                                        class="la la-fw la-plus"></i>@lang('Add New Region')
                                </button>
                                {{-- <a href="{{ route('admin.group.index',[$event->id]) }}"
                                   class="btn btn--primary btn-block"><i
                                        class="la la-fw la-plus"></i>@lang('Add Bidding Group') </a> --}}
                            </div>
                            @endif

                        </div>
                    </div>


                </form>
                <form id="event_product" action="{{route('admin.product.filter_by_event',['scope'=>'all'])}}" method="Post" class="form-inline float-sm-left bg--white">
                @csrf
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

@push('script')
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>

        function checkAndEndEvent() {
            {{--//{{ route('admin.event.end',[$event->id]) }}--}}

            $.get("{{route('admin.event.checkIfAllProductHasBid',[$event->id])}}", function(data, status){
                if(data.is_all_has_bid){
                    window.location.href = "{{ route('admin.event.end',[$event->id]) }}";
                }
                else {

                    Swal.fire({
                        title: 'Warning!',
                        iconHtml: '?',
                        text: 'There are products that do not have bids, Do you want to end the event anyway?',
                        type: 'question',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Yes'
                    }).then((result) => {
                        if (result.value) {
                            window.location.href = "{{ route('admin.event.end',['id'=>$event->id,'agree_end_event'=>true]) }}";
                        }
                    })

                }
            });
        }


        (function ($) {
            "use strict";

            var specCount = `{{ $rangesarray ? count($rangesarray) : 0 }}`;
            specCount = parseInt(specCount);
            specCount = specCount ? specCount + 2 : 2;
            var regionCount = `{{ $shippingregions ? count($shippingregions) : 0 }}`;
            regionCount = parseInt(regionCount);
            regionCount = regionCount ? regionCount + 1 : 1;
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
                                <label class="w-100 font-weight-bold">@lang('From') <span class="text-danger"></span></label>
                                <input name="shippingranges[${tempregion}][${specCount}][from]" class="form-control" type="text" required placeholder="@lang('From')">
                            </div>
                            <div class="col-md-3">
                                <label class="w-100 font-weight-bold">@lang('Up To') <span class="text-danger"></span></label>
                                <input name="shippingranges[${tempregion}][${specCount}][up_to]" class="form-control" type="text" required placeholder="@lang('Up To')">
                            </div>
                            <div class="col-md-3">
                                <label class="w-100 font-weight-bold">@lang('Cost') <span class="text-danger"></span></label>
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
            <div class="row col-12">
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
                                    <label class="w-100 font-weight-bold">@lang('From') <span class="text-danger"></span></label>
                                                    <input name="shippingranges[${regionCount}][${specCount}][from]" class="form-control" type="text" required placeholder="@lang('From')">
                                                </div>
                                                <div class="col-md-3">
                                                    <label class="w-100 font-weight-bold">@lang('Up To') <span class="text-danger"></span></label>
                                                    <input name="shippingranges[${regionCount}][${specCount}][up_to]" class="form-control" type="text" required placeholder="@lang('Up To')">
                                                </div>
                                                <div class="col-md-3">
                                                    <label class="w-100 font-weight-bold">@lang('Cost') <span class="text-danger"></span></label>
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
@endpush
