@extends($activeTemplate.'layouts.frontend')

@section('content')
<!-- Start events by category -->
<section class="events-by-category product-section pt-120 pb-120">
    <div class="container">
        <div class="row">
            <!-- <div class="col-lg-4 col-xl-3">
                <div class="mini-banner-area mt-4">
                    <div class="mini-banner">
                        @php
                            showAd('370x670');
                        @endphp
                    </div>
                    <div class="mini-banner">
                        @php
                            showAd('300x250');
                        @endphp
                    </div>
                </div>
            </div> -->

            <div class="col-lg-12 col-xl-12 search-result">
                <section class="category-details">
                    <img class="category-logo d-none d-lg-block" src="{{asset('assets/images/frontend/categories/'.$category->icon)}}" alt="category" />

                    <div class="textual-details">
                        <h1>{{$category->name}}</h1>
                        <p>
                        {{$category->description}}
                        </p>
                    </div>
                </section>

                <section class="non-practice-events">
                    <h1>@lang('AUCTION EVENTS')</h1>

                    <div class="row g-4 product-grid-view">
                        @forelse ($events as $event)
                        @if(!$event->practice)
                        @include($activeTemplate.'event.card_style')
                        <div class="modal fade" id="accessRequestModal{{$event->id}}">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">@lang('Confirmation Alert')</h5>
                                        <button class="btn text--danger modal-close" data-bs-dismiss="modal"
                                            aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <form action="{{ route('user.request.store', [$event->id]) }}" method="GET">
                                        <div class="modal-body">
                                            <h6 class="message">I agree to the <a
                                                    href="{{route('event.agreement',[$event->id])}}" target="_blank"> @lang('terms
                                                    and conditions') </a></h6>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn--danger"
                                                data-bs-dismiss="modal">@lang('No')</button>
                                            <button type="submit" class="btn btn--base">@lang('Confirm')</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        @endif
                        @empty
                        <div class="text-center">
                            {{ $emptyMessage }}
                        </div>
                        @endforelse
                    </div>
                </section>

                <section class="practice-events">
                    <h1>@lang('PRACTICE FOR AUCTION EVENTS')</h1>

                    <div class="row g-4 events-grid-view product-grid-view">
                        @forelse ($events as $event)
                        @if($event->practice)
                        @include($activeTemplate.'event.card_style')
                        <div class="modal fade" id="accessRequestModal{{$event->id}}">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">@lang('Confirmation Alert')</h5>
                                        <button class="btn text--danger modal-close" data-bs-dismiss="modal"
                                            aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <form action="{{ route('user.request.store', [$event->id]) }}" method="GET">
                                        <div class="modal-body">
                                            <h6 class="message">@lang('I agree to the') <a
                                                    href="{{route('event.agreement',[$event->id])}}" target="_blank"> @lang('terms
                                                    and conditions') </a></h6>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn--danger"
                                                data-bs-dismiss="modal">@lang('No')</button>
                                            <button type="submit" class="btn btn--base">@lang('Confirm')</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        @endif
                        @empty
                        <div class="text-center">
                            {{ $emptyMessage }}
                        </div>
                        @endforelse
                    </div>
                </section>
            </div>
        </div>
    </div>
</section>
<!-- End events by category -->

@endsection


@push('style')
<style>
    /* Chrome, Safari, Edge, Opera */
    input::-webkit-outer-spin-button,
    input::-webkit-inner-spin-button {
        -webkit-appearance: none;
        margin: 0;
    }

    /* Firefox */
    input[type=number] {
        -moz-appearance: textfield;
    }
</style>
@endpush

@push('style')
<style>
    .ui-datepicker .ui-datepicker-prev,
    .ui-datepicker .ui-datepicker-next {
        color: #111;
        background-color: #fff;
        z-index: 11;
    }

    .ui-datepicker-prev {
        position: relative;
    }

    .ui-datepicker-prev::before {
        position: absolute;
        content: "\f104";
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        font-family: "Line Awesome Free";
        font-weight: 900;
    }

    .ui-datepicker-next::before {
        position: absolute;
        content: "\f105";
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        font-family: "Line Awesome Free";
        font-weight: 900;
    }

    .price-range {
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        justify-content: space-between;
        font-size: 14px;
    }

    .price-range label {
        margin: 0;
        font-weight: 500;
        color: #171d1c;
    }

    .price-range input {
        height: unset;
        width: unset;
        background: transparent;
        border: none;
        text-align: right;
        font-weight: 500;
        color: #c151cc;
        padding-right: 0;
    }

    .ui-slider-range {
        height: 3px;
        background: $base-color;
        position: relative;
        z-index: 1;
    }

    .widget .ui-state-default {
        width: 20px;
        height: 20px;
        border-radius: 50%;
        display: block;
        border: none;
        border-radius: 50%;
        background-color: $base-color !important;
        box-shadow: 0 9px 20px 0 rgba(22, 26, 57, 0.36);
        outline: none;
        cursor: pointer;
        top: -9px;
        position: absolute;
        z-index: 1;
    }

    .widget .ui-state-default::after {
        position: absolute;
        content: "";
        width: 14px;
        height: 14px;
        border-radius: 50%;
        background: $base-color;
        top: 3px;
        left: 3px;
        display: block;
    }

    .widget .ui-widget.ui-widget-content {
        position: relative;
        height: 3px;
        border: none;
        margin-right: 20px;
        margin-bottom: 25px;
    }

    .widget .ui-widget.ui-widget-content::after {
        position: absolute;
        content: "";
        top: 0;
        left: 0;
        height: 3px;
        background: rgba($base-color, 0.3);
        width: calc(100% + 20px);
    }
</style>
@endpush

@push('style-lib')
<link rel="stylesheet" href="{{ asset($activeTemplateTrue.'css/jquery-ui.min.css') }}">
@endpush

@push('script-lib')
<script src="{{ asset($activeTemplateTrue.'js/jquery-ui.min.js') }}"></script>
@endpush


@push('script')
<script>
    (function ($) {
        function modeview(){
        $(".mode-grid").click(function(){
            $('.product-list-view').hide();
            $('.product-grid-view').show();
        });
        $(".mode-list").click(function(){
            $('.product-grid-view').hide();
            $('.product-list-view').show();
        });
        }

        modeview();
      })(jQuery);

</script>

<script>
    (function ($) {
            $('.product-list-view').hide();

            $('.countdown-container').final_countdown({
                start: '1362139200',
                end: '1388461320',
                now: '1387461319',
                seconds: {
                    borderColor: '#008CBD',
                    borderWidth: '6'
                },
                minutes: {
                    borderColor: '#008CBD',
                    borderWidth: '6'
                },
                hours: {
                    borderColor: '#008CBD',
                    borderWidth: '6'
                },
                days: {
                    borderColor: '#008CBD',
                    borderWidth: '6'
                }
            });

        })(jQuery);

        function showAccessRequestModal(eventid) {
            $('#accessRequestModal' + eventid).modal('show');
        }
</script>
@endpush