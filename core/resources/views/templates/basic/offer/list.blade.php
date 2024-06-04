@extends($activeTemplate.'layouts.frontend_commerce')

@section('content')
@php
    $countries = json_decode(file_get_contents(resource_path('/views/templates/basic/partials/country.json')));

@endphp
<section class="product-section pt-120 pb-120">
    <div class="container">
        <h2 class="text-center">@lang("COMING SOON")</h2>
    </div>
</section>

<!-- Product -->
{{-- <section class="product-section pt-120 pb-120">
    <div class="container">
        <div class="mb-4 d-lg-none">
            <div class="filter-btn ms-auto">
                <i class="las la-filter"></i>
            </div>
        </div>
        <div class="row flex-wrap-reverse">
            <div class="col-lg-4 col-xl-3">
                <aside class="search-filter">
                    <div class="bg--section pb-5 pb-lg-0">
                        <div class="filter-widget pt-3 pb-2">
                            <h4 class="title m-0"><i class="las la-random"></i>@lang('Filters')</h4>
                            <span class="close-filter-bar d-lg-none">
                                <i class="las la-times"></i>
                            </span>
                        </div>

                        <div class="filter-widget">
                            <h6 class="sub-title">@lang('Sort by')</h6>
                            <form>
                                <div class="form-check form--check">
                                    <input class="form-check-input sorting" value="created_at" type="radio" name="radio" id="radio1">
                                    <label for="radio1">@lang('Date')</label>
                                </div>
                                <div class="form-check form--check">
                                    <input class="form-check-input sorting" value="price" type="radio" name="radio" id="radio2">
                                    <label for="radio2">@lang('Price')</label>
                                </div>
                                <div class="form-check form--check">
                                    <input class="form-check-input sorting" value="name" type="radio" name="radio" id="raqdio3">
                                    <label for="raqdio3">@lang('Name')</label>
                                </div>
                            </form>
                        </div>

                        <div class="filter-widget">
                            <h6 class="sub-title">@lang('By Price')</h6>

                            <div class="widget">
                                <div id="slider-range"></div>
                                <div class="price-range d-flex flex-wrap">
                                    <label for="amount">@lang('Price') :</label>
                                    <input type="text" id="amount" readonly>
                                    <input type="hidden" name="min_price">
                                    <input type="hidden" name="max_price">
                                </div>
                            </div>
                        </div>


                        <div class="filter-widget">
                            <h6 class="sub-title">@lang('By Country')</h6>
                            <form>
                                <div class="form-check form--check">
                                <select id="country"  class="form-select" required >
                                    <option value="All" >All</option>
                                    @foreach ($countries as $country)
                                        <option value="{{ $country->country }}" >{{ $country->country }}</option>
                                    @endforeach
                                </select>
                                </div>
                            </form>
                        </div>

                        <div class="filter-widget">
                            <h6 class="sub-title">@lang('By Quality Score')</h6>
                            <form>
                                <div class="form-check form--check">
                                <select id="quality_score"  class="form-select" required >
                                        <option value="All" >All</option>
                                        <option value="80-83" >80-83</option>
                                        <option value="84-86" >84-86</option>
                                        <option value="86+" >86+</option>
                                </select>
                                </div>
                            </form>
                        </div>

                        <div class="filter-widget">
                            <h6 class="sub-title">@lang('By Location')</h6>
                            <form>
                                <div class="form-check form--check">
                                <select id="location"  class="form-select" required >
                                        <option value="All" >All</option>
                                        <option value="At origin country" >At origin country</option>
                                        <option value="USA Warehouse" >USA Warehouse</option>
                                        <option value="EU Warehouse">EU Warehouse</option>
                                        <option value="Asia Warehouse">Asia Warehouse</option>
                                </select>
                                </div>
                            </form>
                        </div>
                    </div>
                </aside>
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
            </div>
            <div class="col-lg-8 col-xl-9 search-result">
                @include($activeTemplate.'product.filtered', ['products'=> $products])
            </div>
        </div>
    </div>
</section>
<!-- Product -->
<div class="modal fade" id="bidModal">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">@lang('Confirmation Alert')</h5>
                <button class="btn text--danger modal-close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('user.bid') }}" method="POST">
                @csrf
                <input type="hidden" class="amount" name="amount" required>
                <input type="hidden" name="product_id" id="bidproductid" value="">
                <div class="modal-body">
                    <h6 class="message"></h6>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn--danger" data-bs-dismiss="modal">@lang('No')</button>
                    <button type="submit" class="btn btn--base">@lang('Yes')</button>
                </div>
            </form>
        </div>
    </div>
</div>
<div class="modal fade" id="autobidModal">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">@lang('Current Auto Bid')</h5>
                <button class="btn text--danger modal-close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{route('user.autobidsetting.store')}}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="col-lg-12">
                        <div class="form-group">
                            <input type="number" class="form-control" placeholder="@lang('Max Bid Increment')" id="max_bid_in_" autocomplete="off" name="max_value" onchange="convert_to_dec(this)" required>
                        </div>
                    </div>
                    <div class="col-lg-12">
                        <div class="form-group">
                            <input type="number" class="form-control" placeholder="@lang('Bidding Step')" id="bidding_step_" autocomplete="off" name="step" onchange="convert_to_dec(this)" required>
                        </div>
                    </div>
                    <input type="hidden" name="product_id" id="autobidproductid" value="">

                </div>
                <div class="modal-footer">
                    <!-- <button type="button" class="btn btn--danger" data-bs-dismiss="modal">@lang('No')</button> -->
                    <button type="submit" class="btn btn--base">@lang('Submit')</button>
                </div>
            </form>
        </div>
    </div>
</div> --}}
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


<!-- multiSelect style -->
@push('style')
    <style>
    .card-footer{
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

    .multi-select-menuitem + .multi-select-menuitem {
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


@push('style-lib')
<link rel="stylesheet" href="{{ asset($activeTemplateTrue.'css/jquery-ui.min.css') }}">
@endpush

@push('script-lib')
<script src="{{ asset($activeTemplateTrue.'js/jquery-ui.min.js') }}"></script>
@endpush

@push('script')
<script>
    (function($) {
        "use strict";
        var page = 1;
        var search_key = @json(request()->search_key);
        var sorting = '';
        var categories = [];
        var country = "All";
        var score = "All";
        var location = "All" ;
        var minPrice = parseInt(`{{ $allProducts->min('price') }}`);
        var maxPrice = parseInt(`{{ $allProducts->max('price') }}`);

        $(document).on('click', '.page-link', function(e) {
            e.preventDefault();
            page = $(this).attr('href').match(/page=([0-9]+)/)[1];;
            loadSearch();
        });

        $('.sorting').on('click', function(e) {
            sorting = e.target.value;
            loadSearch();
        });

        $("#slider-range").slider({
            range: true,
            min: minPrice,
            max: maxPrice,
            values: [minPrice, maxPrice],
            slide: function(event, ui) {
                $("#amount").val("$" + ui.values[0] + " - $" + ui.values[1]);
                $('input[name=min_price]').val(ui.values[0]);
                $('input[name=max_price]').val(ui.values[1]);
            },

            change: function() {
                minPrice = $('input[name="min_price"]').val();
                maxPrice = $('input[name="max_price"]').val();

                $('.brand-filter input:checked').each(function() {
                    brand.push(parseInt($(this).attr('value')));
                });

                loadSearch();
            }
        });
        $("#amount").val("$" + $("#slider-range").slider("values", 0) + " - $" + $("#slider-range").slider("values", 1));

        $('.category-check').click(function(e) {
            categories = [];
            var categoryArr = $('.category-check:checked:checked');
            if (e.target.value == 'All') {
                $('input:checkbox').not(this).prop('checked', false);
                categories = [];
                loadSearch();
                return 0;
            } else {
                $('#cate-00').prop('checked', false);
            }

            $.each(categoryArr, function(indexInArray, valueOfElement) {
                categories.push(valueOfElement.value);
            });

            loadSearch();
        });

        $('#country').on('change', function (e) {
            country = this.value;
            loadSearch();
        });
        $('#quality_score').on('change', function (e) {
            score = this.value;
            loadSearch();
        });
        $('#location').on('change', function (e) {
            location = this.value;
            loadSearch();
        });

        function modeview() {
            $(".mode-grid").click(function() {
                $('.product-list-view').hide();
                $('.product-grid-view').show();
            });
            $(".mode-list").click(function() {
                $('.product-grid-view').hide();
                $('.product-list-view').show();
            });
        }
        modeview();

        function autopopupbidnow() {
            $('.empty-message').hide();
            $('.auto_bid_now').on('click', function() {
                var productid = $(this).closest('.div-bid-now').find('.idproduct').val();
                $('#autobidproductid').val(productid);
                var modal = $('#autobidModal');
                modal.modal('show');
            });
        }
        autopopupbidnow();

        function popupbidnow() {
            $('.empty-message').hide();
            $('.bid_now').on('click', function() {
                var productid = $(this).closest('.div-bid-now').find('.idproduct').val();
                $('#bidproductid').val(productid);
                var modal = $('#bidModal');
                var cur_sym = $(this).data('cur_sym');
                var amount = $(this).closest('.btn__area').find('#amountbid').val();
                modal.find('.message').html('@lang("Are you sure to bid ")'+amount+'@lang(" on this product")');
                if (!amount) {
                    modal.find('.message').html('@lang("Please enter an amount to bid")');
                    $('.empty-message').show();
                } else {
                    $('.empty-message').hide();
                    modal.find('.amount').val(amount);
                    modal.modal('show');
                }
            });
        }
        popupbidnow();

        function loadSearch() {
            var viewlist = 0;
            var viewgrid = 0;
            if ($(".product-list-view").is(":hidden")) {
                viewlist = 1;
            }
            if ($(".product-grid-view").is(":hidden")) {
                viewgrid = 1;
            }
            $("#overlay, #overlay2").fadeIn(300);

            var url = `{{ route('product.search.filter') }}`;
            var data = {
                'sorting': sorting,
                'minPrice': minPrice,
                'maxPrice': maxPrice,
                'search_key': search_key,
                'categories': categories,
                'page': page,
                'country': country,
                'score': score,
                'location': location
            }

            $.ajax({
                type: "GET",
                url: url,
                data: data,
                success: function(response) {
                    $('.search-result').html(response);
                    $("#overlay, #overlay2").fadeOut(300);
                    if (viewlist == 1) {
                        $('.product-list-view').hide();
                    }
                    if (viewgrid == 1) {
                        $('.product-grid-view').hide();
                    }
                    modeview();
                    popupbidnow();
                    runCountDown();
                },
                error: function(XMLHttpRequest, textStatus, errorThrown) {
                    alert("Status: " + textStatus);
                    alert("Error: " + errorThrown);
                }
            });

        }

        function runCountDown() {
            $('.countdown').each(function() {
                var date = $(this).data('date');
                $(this).countdown({
                    date: date,
                    offset: +6,
                    day: 'Day',
                    days: 'Days'
                });
            });
        }

    })(jQuery);
</script>
@endpush
@push('script')
    <script>
        function convert_to_dec(selectObject) {
            var numb = selectObject.value;
            var number_pars = parseFloat(numb) || 0;
            var number_converted = number_pars.toFixed(2);
            selectObject.value = number_converted;
        }
    </script>
@endpush
