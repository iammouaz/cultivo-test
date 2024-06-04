@extends($activeTemplate.'layouts.frontend_commerce')

@section('content')
<section class="product-section pt-120 pb-120">
    <div class="container">
        <div class="row justify-content-center">
            @forelse ($products as $product)
                <div class="col-xl-3 col-lg-4 col-md-6 col-sm-10 mb-3">
                        <div class="slide-item">
                            <div class="auction__item bg--body">
                                <div class="auction__item-thumb">
                                    <a href="{{ route('product.details', [$product->id, slug($product->name)]) }}">
                                        <img src="{{getImage(imagePath()['product']['path'].'/thumb_'.$product->image,imagePath()['product']['thumb'])}}" alt="auction">
                                    </a>
                                </div>
                                <div class="auction__item-content">
                                    <h6 class="auction__item-title">
                                        <a href="{{ route('product.details', [$product->id, slug($product->name)]) }}">{{ __($product->name) }}</a>
                                    </h6>

                                    <div class="auction__item-footer d-flex justify-content-between">
                                            @if ($product->specification)
                                            <?php $j=1;?>
                                            <?php if(count($product->specification) % 2 == 0)
                                                    {
                                                        $i=(count($product->specification)/2)-2;
                                                    }else{
                                                        $i=((count($product->specification)+1)/2)-2;
                                                    }?>
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
                                                            <?php $j++;?>
                                                            @endforeach
                                                        </table>
                                                    </div>
                                                @endif
                                                @if ( $j > $i)
                                                    <div class="inner__grp">
                                                        <table class="table_specific">
                                                            @foreach ($product->specification as $key=>$spec)
                                                            @if($key <= 4)
                                                                @if ($key > $i)
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
                                            <?php $amount=0; ?>
                                                @foreach ($product->bids->where('product_id',$product->id) as $pp)
                                                <?php $temp = $pp->amount;
                                                    if($amount< $temp){
                                                        $amount =$temp;
                                                        }
                                                    ?>
                                                @endforeach
                                                {{ $general->cur_sym }}{{ showAmount($amount) }}
                                                <?php $amount=0; ?>
                                            @else
                                            {{ $general->cur_sym }}{{ showAmount($product->price) }}
                                            @endif
                                            </div>
                                        </div>
                                    </div>
                                    <div class="auction__item-footer">
                                        <div class="btn__area">
                                            <div class="cart-plus-minus input-group w-auto">
                                                <span class="input-group-text bg--base border-0 text-white">{{ $general->cur_sym }}</span>
                                                <input type="number" placeholder="@lang('Enter your amount')" class="form-control" id="amountbid" min="0" step="any">
                                            </div>
                                            <div class="div-bid-now">
                                                <button class="cmn--btn btn--sm bid_now" data-cur_sym="{{ $general->cur_sym }}">@lang('Bid')</button>
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
                    <p>{{ __($emptyMessage) }}</p>
                </div>
            @endforelse
        </div>
        {{ $products->links() }}
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
@endsection

@push('script')
<script>
    (function ($) {

function popupbidnow(){
            $('.empty-message').hide();
            $('.bid_now').on('click', function () {
                var productid = $(this).closest('.div-bid-now').find('.idproduct').val();
                $('#bidproductid').val(productid);
                var modal = $('#bidModal');
                var cur_sym = $(this).data('cur_sym');
                var amount = $(this).closest('.btn__area').find('#amountbid').val();
                modal.find('.message').html('@lang("Are you sure to bid ")'+amount+'@lang(" on this product")');
                if(!amount){
                    modal.find('.message').html('@lang("Please enter an amount to bid")');
                    $('.empty-message').show();
                }else{
                    $('.empty-message').hide();
                    modal.find('.amount').val(amount);
                    modal.modal('show');
                }
            });
        }
        popupbidnow();
    })(jQuery);

</script>
@endpush
