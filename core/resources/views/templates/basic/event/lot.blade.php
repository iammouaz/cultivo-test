@php
    $score = 0;
    $variety = "";
    $process = "";
    $weight = 0;
    $rank = "";
    $total_price = 0;
    $display_score = false;
    $display_variety = false;
    $display_process = false;
    $display_weight = false;
    $display_rank = false;
    $price = 0;

    if ($product->product_specification){
        foreach ($product->product_specification as $spec) {
            if (strtoupper($spec->spec_key) == 'SCORE') {
                $score = $spec->Value;
                $display_score = ($spec->is_display == 1) ? true : false;
            }
            if (strtoupper($spec->spec_key) == 'VARIETY') {
                $variety = $spec->Value;
                $display_variety = ($spec->is_display == 1) ? true : false;
            }
            if (strtoupper($spec->spec_key) == 'PROCESS') {
                $process = $spec->Value;
                $display_process = ($spec->is_display == 1) ? true : false;
            }
            if (strtoupper($spec->spec_key) == 'WEIGHT') {
                $weight = $spec->Value;
                $display_weight = ($spec->is_display == 1) ? true : false;
            }
            if (strtoupper($spec->spec_key) == 'RANK') {
                $rank = $spec->Value;
                $display_rank = ($spec->is_display == 1) ? true : false;
            }
        }
    }

    $price = isset($product->max_bid()->amount) ? $product->max_bid()->amount : $product->price;
    $total_price = $weight * $price;

    $is_in_allow_product_for_logged_in_user = $product->is_in_allow_list;
    $product_bg_class = $product->color_class;
@endphp

<div id="product_id_{{$product->id}}{{$idSuffix}}">
    <div @if($product_bg_class) class="auction__item {{$product_bg_class}}" @else class="auction__item" @endif >
        <div class="auction__item-content d-flex flex-wrap flex-lg-nowrap align-items-center justify-content-between">
            <div class="text-truncate" title="{{$rank}}">{{$rank}}</div>

            <div class="inner-product-container">
                <div onmouseleave="hideCardOnLeave(this)">
                    <a href="{{ route('product.details', [$product->id, slug($product->name)]) }}"
                       class="auction__item-title d-block text-truncate"
                       onmouseover="showCard(this)">{{$product->name}}</a>
                    @include('templates.basic.event.product_popup_cart',['$product' => $product,'idSuffix' => $idSuffix])
                </div>
            </div>

            <div @if(!Auth::check()) class="auction__item-content_bid flex-grow-1"
                 @else class="auction__item-content_bid" @endif>
                 <div class="d-flex product-weight">
                    <span id='product_{{$product->id}}_weight{{$idSuffix}}' class="text-truncate"
                        title="{{showAmount($product->weight)}}">
                        {{showAmount($product->weight)}}
                    </span>
                    <span class="ps-1">lb</span>
                </div>

                <div class="d-flex final-total-price animate-value">
                    <span>US{{ $general->cur_sym }}</span>
                    <span id="product_{{$product->id}}_price{{$idSuffix}}" class="text-truncate"
                          title="{{showAmount($product->final_price)}}">
                        {{showAmount($product->final_price)}}
                    </span>
                    <span>/lb</span>
                </div>

                <div class="d-flex justify-content-start total-price animate-value">
                    <span>US{{ $general->cur_sym }}</span>
                    <span id="product_{{$product->id}}_total_price{{$idSuffix}}" class="text-truncate pe-1"
                          title="{{showAmount($product->final_total_price)}}">
                        {{showAmount($product->final_total_price)}}
                    </span>
                    <span>@lang(' Total')</span>
                </div>

                <div  class="d-flex final-total-price animate-value">

                    <div>
                        <svg width="26" height="26" viewBox="0 0 26 26" fill="#292A2A" fill-opacity="0.75" xmlns="http://www.w3.org/2000/svg">
                            <path d="M3.83504 26C4.13914 25.9156 4.44324 25.865 4.73045 25.7469C5.35554 25.5107 5.84548 25.1057 6.26785 24.5827C8.64997 21.6637 11.0152 18.7279 13.4142 15.8257C13.9041 15.2183 14.5292 14.729 15.1205 14.1722C15.4584 14.5097 15.8301 14.864 16.1849 15.2352C16.2187 15.2689 16.2187 15.3871 16.1849 15.4545C15.7794 16.6188 16.2356 17.7661 17.3337 18.3229C18.1784 18.7616 19.2259 18.5929 19.9693 17.8505C21.7432 16.0957 23.5171 14.3241 25.291 12.5356C25.9499 11.8607 26.1695 11.0676 25.8654 10.1565C25.5782 9.36349 24.9869 8.8573 24.1422 8.67171C23.7198 8.58734 23.3144 8.62109 22.9258 8.77294C22.7906 8.82356 22.7062 8.80669 22.6048 8.70545C20.8478 6.93381 19.0739 5.17905 17.3168 3.40741C17.2492 3.33992 17.2155 3.20494 17.2324 3.13745C17.6378 2.07447 17.2492 0.960866 16.3369 0.370321C15.4078 -0.220225 14.2758 -0.102116 13.448 0.707775C11.6572 2.46254 9.88326 4.23418 8.12624 6.02269C6.90983 7.2544 7.3322 9.19476 8.90338 9.80218C9.42711 10.0047 9.96773 10.0047 10.4915 9.80218C10.6266 9.75156 10.7111 9.76843 10.8294 9.86967C11.1672 10.2071 11.5051 10.5446 11.8599 10.8989C11.7923 10.9833 11.7248 11.0339 11.6741 11.1014C10.728 12.1137 9.68053 12.9911 8.59928 13.8685C6.20027 15.8089 3.80125 17.7661 1.40224 19.7065C0.658882 20.3139 0.168945 21.0563 0.0337887 22.018C0.0337887 22.0518 0 22.1024 0 22.1361C0 22.3892 0 22.6423 0 22.8954C0.0168953 22.946 0.0337887 23.0135 0.0506821 23.0641C0.253416 24.1946 0.861618 25.0551 1.89218 25.5782C2.26386 25.7638 2.70311 25.8481 3.10858 25.9831C3.32821 26 3.58163 26 3.83504 26ZM17.2661 14.0879C15.4753 12.2993 13.7014 10.5277 11.9444 8.77294C13.3466 7.3725 14.7827 5.93832 16.168 4.55476C17.925 6.30952 19.7158 8.09803 21.4898 9.86967C20.0875 11.2701 18.6684 12.6874 17.2661 14.0879ZM11.8768 15.3027C11.8261 15.3702 11.7754 15.4377 11.7079 15.5052C9.98463 17.6311 8.2445 19.7571 6.52126 21.8831C5.94685 22.5748 5.40623 23.3004 4.79803 23.9415C3.81815 24.9539 1.99355 24.4646 1.60497 23.0979C1.35156 22.1868 1.63876 21.4781 2.34833 20.8876C4.51082 19.1328 6.67331 17.378 8.81891 15.6233C9.44401 15.1171 10.086 14.594 10.6942 14.1047C11.0997 14.5097 11.4713 14.8977 11.8768 15.3027ZM24.4801 10.8483C24.497 11.1689 24.3449 11.3713 24.1591 11.5569C22.419 13.278 20.6957 15.0159 18.9725 16.7369C18.9049 16.8044 18.8542 16.855 18.7866 16.9056C18.3643 17.2262 17.773 17.0237 17.6209 16.5175C17.5027 16.1632 17.6547 15.8932 17.9081 15.657C19.6145 13.936 21.3377 12.1981 23.0778 10.4771C23.1454 10.4096 23.213 10.3421 23.2806 10.2915C23.534 10.1228 23.7874 10.1059 24.0577 10.2409C24.3111 10.3759 24.4463 10.5952 24.4801 10.8483ZM15.847 2.29381C15.8639 2.56378 15.7119 2.74938 15.5429 2.93498C13.8028 4.67287 12.0626 6.41076 10.3394 8.13178C10.2887 8.18239 10.238 8.23301 10.1705 8.28363C9.73121 8.60421 9.12301 8.40174 8.98785 7.86181C8.90338 7.52436 9.03854 7.27127 9.27506 7.05192C10.9814 5.3309 12.7215 3.59301 14.4617 1.85512C14.5292 1.78763 14.5968 1.72014 14.6644 1.66952C14.9178 1.51767 15.1712 1.50079 15.4415 1.63578C15.7119 1.77076 15.8301 1.9901 15.847 2.29381ZM12.9581 14.0879C12.6033 13.7335 12.2485 13.3961 11.9275 13.0755C12.2654 12.738 12.6033 12.3837 12.9243 12.0631C13.2622 12.4006 13.6169 12.7549 13.9548 13.0924C13.6338 13.4129 13.279 13.7673 12.9581 14.0879Z"/>
                            </svg>
                            
                                                </div>
 


                    <span style="{{ $product->total_bid > 0 || !$is_in_allow_product_for_logged_in_user ? 'color: var(--primary-color);' : '' }}"
                        id="product_{{$product->id}}_bids{{$idSuffix}}" class="text-truncate auction-cards-number"
                          title="{{$product->total_bid}}">
                        {{$product->total_bid}}
                    </span>
                </div>

                @if (($product->status == 1 && $product->started_at < now() && $product->expired_at > now()) || $is_event_ended)
                    <div class="btn__area flex-shrink-0">
                        <div class="p-0 div-bid-now">
                            {{-- <div id="input-bid-with-anim" class="cart-plus-minus input-group w-auto input-bid-with-anim">
                                <span class="input-group-text bg--base border-0 text-white">
                                    {{ $general->cur_sym }}
                                </span>
                                <input type="text" class="form-control" autocomplete="off" id="amount">
                            </div> --}}
                            @if(Auth::check() && $is_in_allow_product_for_logged_in_user)
                                <div class="d-flex flex-wrap justify-content-lg-end gap-1"
                                     id="bid_area_{{ $product->id }}{{$idSuffix}}"
                                >
                                    @include('templates.basic.event.bid_area',['product'=>$product,'event'=>$event,'is_event_ended'=>$is_event_ended])
                                </div>
                            @else
                                <div class="d-flex flex-wrap justify-content-lg-end"
                                     >
                                    @include('templates.basic.event.bid_area_disable',['product'=>$product,'event'=>$event,'is_event_ended'=>$is_event_ended])
                                </div>
                            @endif
                            <input type="hidden" class="idproduct" value="{{ $product->id }}">
                            <input type="hidden" class="suggested-value"
                                   @if($product->bid_count()==0) value="{{$product->final_price}}"
                                   @else value="{{$product->final_price + $product->less_bidding_value}}" @endif >
                        </div>
                    </div>
                @endif
            </div>

            <div @if(!Auth::check()) style="width: 0px" @endif>
                @if(Auth::check())
                    <button class="product-fav-btn" onclick="toggleFavoriteStatus({{$product->id}},this)"
                            data-bs-toggle="tooltip" data-bs-placement="bottom"
                            title="@lang("Add to favorites")">
                        @if(!$product->is_fav)
                            <i class="favorite-icon-{{$product->id}} far fa-heart heart-icon black-75"></i>
                        @else
                            <i class="favorite-icon-{{$product->id}} far fa-heart heart-icon black-75 heart-icon-color"></i>
                        @endif
                    </button>
                @endif
            </div>
        </div>
    </div>
</div>
