    @php
        $rank = "";
        $display_rank = false;
        if ($product->product_specification){
            foreach ($product->product_specification as $spec) {
                if (strtoupper($spec->spec_key) == 'RANK') {
                    $rank = $spec->Value;
                    $display_rank = ($spec->is_display == 1) ? true : false;
                }
            }
        }
    @endphp

    <div id="product_id_{{$product->id}}_clone1" @if(!(!is_null($product->max_bid()) && $product->max_bid()->user->id == Auth::id())) class="d-none" @endif>
        <div class="current-winning-slide d-flex flex-column align-items-center justify-content-center">
            <a href="{{ route('product.details', [$product->id, slug($product->name)]) }}">
                <img src="{{getImage(imagePath()['product']['path'].'/thumb_'.$product->image,imagePath()['product']['thumb'])}}" alt="{{$product->name}}" />
            </a>

            <a href="{{ route('product.details', [$product->id, slug($product->name)]) }}">
                <p>
                    @if($display_rank)
                    <span class="pe-1">{{$rank}}</span>
                    @endif

                    <span>{{$product->name}}</span>

                    <span id="product_{{$product->id}}_price_clone1" class="d-none">
                    </span>
                </p>
            </a>
        </div>
    </div>
