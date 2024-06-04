<div id="overlay">
    <div class="cv-spinner">
        <span class="spinner"></span>
    </div>
</div>
<div class="overlay-2" id="overlay2"></div>
<div class="d-flex flex-wrap justify-content-sm-between justify-content-center mb-4 row-view-mode"
     style="gap:15px 30px">
    <div class="col-lg-1 d-flex justify-content-center align-items-center">
        <button type="button" class="view-type-mode mode-grid">
            <i class="fas fa-th"></i>
        </button>
        <button type="button" class="view-type-mode mode-list">
            <i class="fas fa-list"></i>
        </button>
    </div>
    <p class="mb-0 mt-0">@lang('Showing Results'): <span>{{ $products->count() }}</span></p>
    <p class="mb-0 mt-0">@lang('Results Found'): <span>{{ $products->total() }}</span></p>
</div>
<div class="col-lg-1 row-view-mode">

</div>

<div class="row g-4 product-grid-view">
    @forelse ($products as $product)
        <div class="col-sm-6 col-xl-4">
            <div class="auction__item bg--body">
                <div class="auction__item-thumb">
                    <a href="{{ route('product.details', [$product->id, slug($product->name)]) }}">
                        <img
                            src="{{getImage(imagePath()['product']['path'].'/thumb_'.$product->image,imagePath()['product']['thumb'])}}"
                            alt="auction" >
                    </a>
                </div>
                <div class="auction__item-content">
                    <h6 class="auction__item-title">
                        <a href="{{ route('product.details', [$product->id, slug($product->name)]) }}">{{ __($product->name) }}</a>
                    </h6>
                    <div class="auction__item-footer d-flex justify-content-between">
                        @if ($product->specification)
                            <?php $j = 1; ?>
                            <?php if (count($product->specification) % 2 == 0) {
                                $i = (count($product->specification) / 2) - 2;
                            } else {
                                $i = ((count($product->specification) + 1) / 2) - 2;
                            } ?>
                            @if ( $j <= $i)
                                <div class="inner__grp">
                                    <table class="table_specific">
                                        @foreach ($product->specification as $key=>$spec)
                                            @if ($key <= $i)
                                                <tr>
                                                    <th class="small">{{ __($spec['name']) }}:</th>
                                                    <td class="small">{{ __($spec['value']) }}</td>
                                                </tr>
                                            @endif
                                            <?php $j++; ?>
                                        @endforeach
                                    </table>
                                </div>
                            @endif
                            @if ( $j > $i)
                                <div class="inner__grp">
                                    <table class="table_specific">
                                        @foreach ($product->specification as $key=>$spec)
                                            @if($key <= 4) @if ($key> $i)
                                                <tr>
                                                    <th class="small">{{ __($spec['name']) }}:</th>
                                                    <td class="small">{{ __($spec['value']) }}</td>
                                                </tr>
                                            @endif
                                            @endif
                                        @endforeach
                                    </table>
                                </div>
                            @endif
                        @endif
                    </div>
                    <div class="auction__item-countdown">
                        <div class="inner__grp">
                    <span class="total-bids">
                        <span><i class="las la-gavel"></i></span>
                        <span>@lang('x') {{ ($product->total_bid) }} @lang('Bids')</span>
                    </span>
                            <div class="total-price">
                                @if ($product->bids != '[]')
                                    <?php $amount = 0; ?>
                                    @foreach ($product->bids->where('product_id',$product->id) as $pp)
                                        <?php $temp = $pp->amount;
                                        if ($amount < $temp) {
                                            $amount = $temp;
                                        }
                                        ?>
                                    @endforeach
                                    {{ $general->cur_sym }}{{ showAmount($amount) }}
                                    <?php $amount = 0; ?>
                                @else
                                    {{ $general->cur_sym }}{{ showAmount($product->price) }}
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="auction__item-footer">
                        <div class="btn__area">
                            <div class="cart-plus-minus input-group w-auto">
                                <span
                                    class="input-group-text bg--base border-0 text-white">{{ $general->cur_sym }}</span>
                                <input type="number" placeholder="@lang('Enter your amount')" class="form-control"
                                       id="amountbid" min="0" step="any">
                            </div>
                            <div class="row">
                                <div class="col-lg-6 div-bid-now mt-1">
                                    <button class="cmn--btn btn--sm bid_now"
                                            data-cur_sym="{{ $general->cur_sym }}">@lang('Bid Now')</button>
                                    <input type="hidden" class="idproduct" value="{{ $product->id }}">
                                </div>
                                <div class="col-lg-6 div-bid-now mt-1">
                                    <button class="cmn--btn btn--sm auto_bid_now"
                                            data-cur_sym="{{ $general->cur_sym }}">@lang('Auto Bid')</button>
                                    <input type="hidden" class="idproduct" value="{{ $product->id }}">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @empty
        <div class="text-center">
            {{ __($emptyMessage) }}
        </div>
    @endforelse
</div>

<div class="row g-4 product-list-view">
    @forelse ($products as $product)
        @php
        $score = 0;
            $variety = "";
            $process = "";
            $weight = 0;
            $rank = "";
            $total_price = 0;
            $price = 0;
            if ($product->specification){
                foreach ($product->specification as $spec) {
                    if ($spec['name'] == 'Score') {
                        $score = $spec['value'];
                    }
                    if ($spec['name'] == 'Variety') {
                        $variety = $spec['value'];
                    }
                    if ($spec['name'] == 'Process') {
                        $process = $spec['value'];
                    }
                    if ($spec['name'] == 'Weight') {
                        $weight = $spec['value'];
                    }
                    if ($spec['name'] == 'Rank') {
                        $rank = $spec['value'];
                    }
                }
            }

            $price = isset($product->max_bid()->amount) ? $product->max_bid()->amount : $product->price;
          $total_price = $weight * $price;
        @endphp
        <div class="col-sm-6 col-xl-4">
            <div class="auction__item bg--body">
                <div class="auction__item-thumb">
                    <a href="{{ route('product.details', [$product->id, slug($product->name)]) }}">
                        <img
                            src="{{getImage(imagePath()['product']['path'].'/thumb_'.$product->image,imagePath()['product']['thumb'])}}"
                            alt="auction">
                    </a>
                </div>
                <div class="auction__item-content">
                    <h6 class="auction__item-title">
                        <a href="{{ route('product.details', [$product->id, slug($product->name)]) }}">{{$rank}}  {{ __($product->name) }}</a>
                    </h6>
                    <div class="auction__item-countdown">
                        <div class="inner__grp">
                            <table class="table_specific">

                                <tr>
                                    <th class="small">Score</th>
                                    <td class="small">{{ $score }}</td>
                                </tr>
                                <tr>
                                    <td class="small">{{$variety}}  </td>
                                    <td class="small">{{ $process }} </td>
                                </tr>

                            </table>
                            <table class="table_specific">

                                <tr>
                                    <th class="small">Weight: </th>
                                    <td class="small">{{ $weight }} lb</td>
                                </tr>
                                <tr>
                                    <th class="small">@lang('Total'):</th>
                                    <td class="small">US{{ $general->cur_sym }}{{ showAmount($total_price) }}</td>
                                </tr>

                            </table>
                        </div>
                    </div>
                </div>
                <div class="auction__item-content_bid">
                    @if ($product->status == 1 && $product->started_at < now() && $product->expired_at > now())
                        <div class="btn__area">
                            {{--                            <div class="cart-plus-minus input-group w-auto">--}}
                            {{--                                <span--}}
                            {{--                                    class="input-group-text bg--base border-0 text-white">{{ $general->cur_sym }}</span>--}}
                            {{--                                <input type="number" placeholder="@lang('Enter your amount')" class="form-control"--}}
                            {{--                                       id="amountbid" min="0" step="any">--}}
                            {{--                            </div>--}}
                            <div class="custom_total">
                                <div class="total-price">
                                    @if ($product->bids != '[]')
                                        <?php $amount = 0; ?>
                                        @foreach ($product->bids->where('product_id',$product->id) as $pp)
                                            <?php $temp = $pp->amount;
                                            if ($amount < $temp) {
                                                $amount = $temp;
                                            }
                                            ?>
                                        @endforeach
                                        US{{ $general->cur_sym }}{{ showAmount($amount) }}/lb
                                        <?php $amount = 0; ?>
                                    @else
                                        US{{ $general->cur_sym }}{{ showAmount($product->price) }}/lb
                                    @endif
                                </div>
                            </div>
                            <div class="custom_total bids_count">
                                <span class="total-bids">
                                <span><i class="las la-gavel"></i></span>
                                <span>@lang('x') {{ ($product->total_bid) }} @lang('Bids')</span>
                            </span>
                            </div>
                            <div class="div-bid-now" style="padding: 18px 0 0 !important;">
                                <button class="cmn--btn btn--sm bid_now"
                                        data-cur_sym="{{ $general->cur_sym }}">@lang('Bid')</button>
                                <input type="hidden" class="idproduct" value="{{ $product->id }}">
                            </div>

                            {{--                            <div class="div-bid-now mt-2">--}}
                            {{--                                <button class="cmn--btn btn--sm auto_bid_now"--}}
                            {{--                                        data-cur_sym="{{ $general->cur_sym }}">@lang('Auto Bid')</button>--}}
                            {{--                                <input type="hidden" class="idproduct" value="{{ $product->id }}">--}}
                            {{--                            </div>--}}
                            <span class="text--danger empty-message">@lang('Please enter an amount to bid')</span>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    @empty
        <div class="text-center">
            {{ __($emptyMessage) }}
        </div>
    @endforelse
</div>

{{ $products->links() }}

@push('script')
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
    </script>
@endpush
