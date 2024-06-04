@extends($activeTemplate.'layouts.frontend')

@section('content')
<section class="product-section pt-120 pb-120">
    <div class="container">
        <div class="row justify-content-center">
            @forelse ($products as $product)
                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 mb-6">
                        <div class="slide-item">
                            <div class="auction__item bg--body">
                                <div class="auction__item-thumb">
                                    <a href="{{ route('product.details', [$product->id, slug($product->name)]) }}">
                                        <img src="{{getImage(imagePath()['product']['path'].'/thumb_'.$product->image,imagePath()['product']['thumb'])}}" alt="auction">
                                    </a>
                                    <span class="total-bids">
                                        <span><i class="las la-gavel"></i></span>
                                        <span>@lang('x') {{ ($product->total_bid) }} @lang('Bids')</span>
                                    </span>
                                </div>
                                <div class="auction__item-content">
                                    <h6 class="auction__item-title">
                                        <a href="{{ route('product.details', [$product->id, slug($product->name)]) }}">{{ $product->name }}</a>
                                    </h6>
                                    <div class="auction__item-countdown">
                                        <div class="inner__grp">
                                            
                                            <ul class="countdown" data-date="{{ showDateTime($product->expired_at, 'm/d/Y H:i:s') }}">
                                                <li>
                                                    <span class="days">00</span>
                                                </li>
                                                <li>
                                                    <span class="hours">00</span>
                                                </li>
                                                <li>
                                                    <span class="minutes">00</span>
                                                </li>
                                                <li>
                                                    <span class="seconds">00</span>
                                                </li>
                                            </ul>
                                        </div>
                                        <div class="total-price">
                                                {{ $general->cur_sym }}{{ showAmount($product->price) }}
                                        </div>
                                    </div>
                                </div>
                                <div class="product-content">
                                        <div class="auction__item-footer">
                                            <a href="{{ route('product.details', [$product->id, slug($product->name)]) }}" class="cmn--btn w-100">@lang('Details')</a>
                                        </div>
                                </div>  
                            </div>
                        </div>
                </div>
            @empty
                <div class="text-center">
                    <p>{{ $emptyMessage }}</p>
                </div>
            @endforelse
        </div>
        {{ $products->links() }}
    </div>
</section>
@endsection