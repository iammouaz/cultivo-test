<div class="row g-md-3 product-list-view gap-4 gap-lg-0">
    @forelse ($relatedProducts as $product)
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
        <div id="product_id_{{$product->id}}">
            <div @if($product_bg_class) class="auction__item {{$product_bg_class}} auction__item-custom" @else class="auction__item auction__item-custom" @endif>
                <div class="auction__item-thumb auction__item-thumb-custom">

                    <a href="{{ route('product.details', [$product->id, slug($product->name)]) }}">
                        <img
                            src="{{getImage(imagePath()['product']['path'].'/thumb_'.$product->image,imagePath()['product']['thumb'])}}"
                            alt="auction">
                    </a>
                    <button @if(!Auth::check()) disabled @endif class="product-fav-btn" onclick="toggleFavoriteStatus({{$product->id}})">
                        @if(!$product->is_fav)
                            <i class="favorite-icon-{{$product->id}} far fa-heart heart-icon black-75"></i>
                        @else
                            <i class="favorite-icon-{{$product->id}} far fa-heart heart-icon black-75 heart-icon-color"></i>
                        @endif
                    </button>
                </div>
                <div class="auction__item-content auction__item-content-custom">
                    <div class="auction__item-attributes mb-lg-3 mb-xl-0">
                        <div class="auction__item-countdown auction__item-countdown-custom">
                            <h6 class="text-truncate auction__item-title auction__item-title-custom">
                                <a class="text-capitalize"
                                   title="@if($display_rank){{$rank}}@endif {{ $product->name }}"
                                   href="{{ route('product.details', [$product->id, slug($product->name)]) }}">
                                    @if($display_rank)
                                        {{$rank}}
                                    @endif {{ $product->name }}
                                </a>
                            </h6>
                        </div>
                        <div class="acution__item-properties flex-wrap flex-lg-nowrap gap-lg-4">
                            @if($display_score)
                                <div class="acution__item-property gap-2">
                                    <svg width="26" height="17" viewBox="0 0 26 17" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M22.1548 14.4835H15.1633H4.00717H1.44676C0.255378 14.4835 0.255378 16.3124 1.44676 16.3124H8.44349H19.5996H22.16C23.3462 16.3124 23.3462 14.4835 22.1548 14.4835Z" fill="url(#paint0_linear_1949_3212)"/>
                                        <path d="M21.6061 2.68991V2.04719C21.6061 1.08573 20.8276 0.312378 19.8713 0.312378H3.77727C2.81581 0.312378 2.03723 1.09095 2.03723 2.04719V3.41623C2.03723 8.80878 6.42652 13.1981 11.8191 13.1981C14.4265 13.1981 16.757 12.1948 18.4971 10.5436C19.2286 11.0923 20.143 11.411 21.1045 11.411C23.5291 11.411 25.4468 9.44627 25.4468 7.06874C25.4468 4.79572 23.759 2.96685 21.6061 2.68991ZM11.8243 11.3796C7.43501 11.3796 3.87133 7.81596 3.87133 3.42668V2.14125H19.7825V3.42145C19.7773 7.81074 16.2136 11.3796 11.8243 11.3796ZM21.1045 9.55078C20.6029 9.55078 20.143 9.41492 19.7355 9.13798C20.6969 7.81074 21.3344 6.25881 21.5643 4.51878C22.7557 4.70166 23.6649 5.75196 23.6649 6.98514C23.6179 8.45346 22.4788 9.55078 21.1045 9.55078Z" fill="url(#paint1_linear_1949_3212)"/>
                                        <defs>
                                        <linearGradient id="paint0_linear_1949_3212" x1="2.15766" y1="15.8968" x2="2.60681" y2="12.5107" gradientUnits="userSpaceOnUse">
                                        <stop stop-color="#008CBD"/>
                                        <stop offset="0.505208" stop-color="#008E8F"/>
                                        <stop offset="1" stop-color="#008C64"/>
                                        </linearGradient>
                                        <linearGradient id="paint1_linear_1949_3212" x1="3.70684" y1="10.2704" x2="15.7759" y2="-3.16831" gradientUnits="userSpaceOnUse">
                                        <stop stop-color="#008CBD"/>
                                        <stop offset="0.505208" stop-color="#008E8F"/>
                                        <stop offset="1" stop-color="#008C64"/>
                                        </linearGradient>
                                        </defs>
                                        </svg>
                                        

                                    <span class="acution__item-property-value score-property paragraph-level-2">{{ $score }}
                            </span>
                                </div>
                            @endif
                            @if($display_variety)
                                <div class="acution__item-property gap-2">
                                    <svg width="24" height="21" viewBox="0 0 24 21" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M1.70783 18.1839C2.18162 18.175 2.6655 18.1301 3.10507 18.1051C9.11906 22.1833 13.6054 20.6224 16.3046 18.5982C21.6071 14.5228 24.2603 5.21619 22.6834 0.396056C22.6187 0.235837 22.5156 0.0971315 22.3481 0.0500637C22.1805 0.00299585 21.9844 0.0576517 21.8816 0.170803C19.7621 2.17713 16.0563 4.17681 12.4723 6.13328C7.04532 9.08912 1.93161 11.8489 1.54978 14.8857C1.47466 15.6587 1.65738 16.4331 2.12894 17.0756C1.57924 17.1019 0.993637 17.1182 0.454021 17.1086M3.62799 15.0876C3.97034 12.5821 9.04815 9.81218 14.0016 7.11712C17.3225 5.33855 19.6318 4.23026 21.9003 2.33682C21.0508 9.64252 18.4788 14.7037 14.5306 17.6812C12.299 19.4429 9.37202 19.9571 6.66549 19.158C5.59441 18.8571 5.64057 18.4559 4.63914 17.8841C11.7388 16.6958 17.9346 12.2903 19.4649 8.12996C19.5764 7.87078 19.4386 7.58026 19.1495 7.46031L19.1136 7.45022C18.8443 7.37457 18.5897 7.52255 18.4798 7.77575C16.6979 12.5561 9.80741 16.2629 3.55368 17.0368C2.97872 16.3717 3.54985 15.7565 3.62799 15.0876Z" fill="url(#paint0_linear_1949_3314)"/>
                                        <defs>
                                        <linearGradient id="paint0_linear_1949_3314" x1="21.9117" y1="5.46221" x2="2.71209" y2="9.03391" gradientUnits="userSpaceOnUse">
                                        <stop stop-color="#008CBD"/>
                                        <stop offset="0.505208" stop-color="#008E8F"/>
                                        <stop offset="1" stop-color="#008C64"/>
                                        </linearGradient>
                                        </defs>
                                        </svg>
                                        

                                    <span class="text-truncate acution__item-property-value paragraph-level-2"
                                          title="{{ $variety }}">
                            {{ __($variety) }}
                            </span>
                                </div>
                            @endif
                            @if($display_process)
                                <div class="acution__item-property gap-2">
                                    <svg width="21" height="24" viewBox="0 0 21 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M14.0152 4.40898L14.0145 4.41134L14.0144 4.41128C13.9322 4.62995 13.8528 4.85089 13.7733 5.07216C13.6404 5.44195 13.5072 5.81269 13.3597 6.17542M14.0152 4.40898L13.321 6.15982M14.0152 4.40898C14.0056 6.02774 13.6455 7.56253 12.9091 8.99267L12.9091 8.99269C12.37 10.0414 11.6759 10.9907 10.9017 11.8945L10.9016 11.8946C10.6399 12.2008 10.3771 12.5046 10.1143 12.8083C9.6173 13.3828 9.12056 13.957 8.63246 14.5466M14.0152 4.40898L8.63246 14.5466M13.3597 6.17542C13.3596 6.17545 13.3596 6.17548 13.3596 6.17551L13.321 6.15982M13.3597 6.17542C13.3597 6.1754 13.3597 6.17537 13.3597 6.17534L13.321 6.15982M13.3597 6.17542C12.7115 7.78871 11.8743 9.31404 10.7824 10.6741L10.7823 10.6742C10.242 11.3437 9.66836 11.9862 9.09421 12.6294C8.94085 12.8012 8.78746 12.973 8.63465 13.1454L8.6346 13.1454C8.12678 13.7164 7.63334 14.299 7.27204 14.97C6.61456 16.1911 6.57806 17.494 6.80927 18.8325M13.321 6.15982C12.6742 7.77007 11.8388 9.29171 10.7499 10.648C10.2103 11.3166 9.63757 11.9582 9.06351 12.6012C8.91006 12.7731 8.75652 12.9451 8.60348 13.1178C8.09557 13.6889 7.59919 14.2746 7.23538 14.9503C6.53874 16.2441 6.53414 17.6272 6.80491 19.0408C6.80511 18.9711 6.80657 18.9016 6.80927 18.8325M6.80927 18.8325C6.8362 18.1433 6.98664 17.483 7.2387 16.8417L7.23871 16.8417C7.57075 15.9979 8.05814 15.2373 8.63246 14.5466M6.80927 18.8325C6.82103 18.9005 6.83348 18.9687 6.84657 19.0369C6.8492 18.2793 7.00228 17.5571 7.27746 16.857C7.60755 16.0181 8.09237 15.2613 8.66453 14.5731M12.9462 9.01173C13.6902 7.56681 14.0516 6.01558 14.057 4.37976C14.0581 4.36033 14.0572 4.34165 14.0563 4.32398C14.0563 4.32355 14.0563 4.32311 14.0563 4.32268L12.9462 9.01173ZM12.9462 9.01173C12.4051 10.0643 11.7089 11.0162 10.9333 11.9216M12.9462 9.01173L10.9333 11.9216M10.9333 11.9216C10.6712 12.2283 10.4082 12.5323 10.1454 12.8361C9.64857 13.4103 9.15238 13.9839 8.66453 14.5731M10.9333 11.9216L8.63246 14.5466M8.63246 14.5466L8.66453 14.5731M8.63246 14.5466C8.63247 14.5466 8.63249 14.5465 8.63251 14.5465L8.66453 14.5731" fill="url(#paint0_linear_4234_3470)" stroke="url(#paint1_linear_4234_3470)" stroke-width="0.083291"/>
                                        <path style="fill: unset !important;" d="M6.07319 20.5522C5.70388 20.3785 5.32348 20.2088 4.9577 20.0276C4.82558 19.9563 4.69301 19.8663 4.58615 19.7608C3.72637 18.8686 3.24204 17.7848 2.98599 16.5875C2.64651 15.0195 2.72065 13.4529 3.08205 11.9016C3.66978 9.37994 4.85029 7.18306 6.68787 5.35045C7.75025 4.28538 8.96741 3.45878 10.4174 3.01781C11.4529 2.69485 12.4907 2.62148 13.5374 2.92049C14.8054 3.27752 15.7334 4.06376 16.4048 5.18032C17.1268 6.38509 17.4081 7.70477 17.4452 9.09366C17.4925 10.5978 17.226 12.0648 16.7347 13.4813C16.0511 15.424 15.0083 17.1519 13.5417 18.6067C12.4718 19.6682 11.2546 20.4948 9.79356 20.9398C9.06126 21.166 8.3072 21.2624 7.53405 21.1841C7.43696 21.1752 7.33189 21.1442 7.24898 21.1052C6.85705 20.9209 6.46512 20.7366 6.07319 20.5522Z" stroke="var(--main-svg-color)" stroke-width="1.16607" stroke-miterlimit="10"/>
                                        <defs>
                                        <linearGradient id="paint0_linear_4234_3470" x1="7.28763" y1="15.2669" x2="10.1613" y2="16.29" gradientUnits="userSpaceOnUse">
                                        <stop stop-color="#008CBD"/>
                                        <stop offset="0.505208" stop-color="#008E8F"/>
                                        <stop offset="1" stop-color="#008C64"/>
                                        </linearGradient>
                                        <linearGradient id="paint1_linear_4234_3470" x1="7.28763" y1="15.2669" x2="10.1613" y2="16.29" gradientUnits="userSpaceOnUse">
                                        <stop stop-color="#008CBD"/>
                                        <stop offset="0.505208" stop-color="#008E8F"/>
                                        <stop offset="1" stop-color="#008C64"/>
                                        </linearGradient>
                                        <linearGradient id="paint2_linear_4234_3470" x1="2.72336" y1="14.1594" x2="16.9642" y2="14.6552" gradientUnits="userSpaceOnUse">
                                        <stop stop-color="#008CBD"/>
                                        <stop offset="0.505208" stop-color="#008E8F"/>
                                        <stop offset="1" stop-color="#008C64"/>
                                        </linearGradient>
                                        </defs>
                                        </svg>
                                        

                                    <span class="text-truncate acution__item-property-value paragraph-level-2"
                                          title="{{ $process }}">
                            {{ __($process) }}
                            </span>
                                </div>
                            @endif
                            @if($display_weight)
                                <div class="acution__item-property gap-2">
                                    <svg width="28" height="25" viewBox="0 0 28 25" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M0.82546 0.312378C0.53968 0.345353 0.281379 0.50473 0.132993 0.752039C-0.0153922 0.999348 -0.042871 1.30162 0.0670443 1.57091L1.2981 4.64854C1.36404 4.8189 1.48495 4.96729 1.63334 5.07171C1.78172 5.17613 1.96858 5.23109 2.14994 5.23109H11.3828V7.07766H8.30519C6.618 7.07766 5.22757 8.46809 5.22757 10.1553V21.2348C5.22757 22.9219 6.618 24.3124 8.30519 24.3124H19.3847C21.0719 24.3124 22.4623 22.9219 22.4623 21.2348V10.1608C22.4623 8.47359 21.0719 7.08316 19.3847 7.08316H16.307V5.23658H25.5399C25.7268 5.23658 25.9081 5.18162 26.0565 5.0772C26.2104 4.97279 26.3258 4.8244 26.3918 4.65403L27.6228 1.5764C27.7327 1.29062 27.6997 0.97187 27.5294 0.719064C27.37 0.466259 27.0842 0.312378 26.782 0.312378H0.924383C0.891409 0.312378 0.858434 0.312378 0.82546 0.312378ZM2.28733 2.15895H25.4135L24.9244 3.39001H2.78195L2.28733 2.15895ZM13.2349 5.23658H14.4659V7.08316H13.2349V5.23658ZM8.31069 8.92974H19.3902C20.0881 8.92974 20.6212 9.46282 20.6212 10.1608V21.2402C20.6212 21.9382 20.0881 22.4713 19.3902 22.4713H8.31069C7.61273 22.4713 7.07964 21.9382 7.07964 21.2402V10.1608C7.07964 9.46282 7.61273 8.92974 8.31069 8.92974ZM13.8504 10.1608C10.8003 10.1608 8.31069 12.6504 8.31069 15.7005C8.31069 18.7507 10.8003 21.2402 13.8504 21.2402C16.9006 21.2402 19.3902 18.7507 19.3902 15.7005C19.3902 12.6504 16.9006 10.1608 13.8504 10.1608ZM13.8504 12.0074C15.9003 12.0074 17.5436 13.6506 17.5436 15.7005C17.5436 17.7504 15.9003 19.3937 13.8504 19.3937C11.8005 19.3937 10.1573 17.7504 10.1573 15.7005C10.1573 13.6506 11.8005 12.0074 13.8504 12.0074ZM12.2072 13.2274C11.9764 13.2549 11.7565 13.3648 11.6081 13.5407C11.4543 13.7165 11.3773 13.9474 11.3828 14.1837C11.3938 14.42 11.4927 14.6398 11.6576 14.8047L12.6304 15.7775C12.6688 16.4205 13.1964 16.9316 13.8504 16.9316C14.5319 16.9316 15.0815 16.382 15.0815 15.7005C15.0815 15.052 14.5759 14.5244 13.9384 14.4805L12.9656 13.5077C12.7678 13.3044 12.4875 13.1999 12.2072 13.2274Z" fill="url(#paint0_linear_1949_3169)"/>
                                        <defs>
                                        <linearGradient id="paint0_linear_1949_3169" x1="1.97465" y1="18.8595" x2="23.2901" y2="3.78815" gradientUnits="userSpaceOnUse">
                                        <stop stop-color="#008CBD"/>
                                        <stop offset="0.505208" stop-color="#008E8F"/>
                                        <stop offset="1" stop-color="#008C64"/>
                                        </linearGradient>
                                        </defs>
                                        </svg>
                                        

                                    <span
                                        class="text-truncate acution__item-property-value weight-property paragraph-level-2"
                                        title="{{ $weight }} lb">
                            <span id='product_{{$product->id}}_weight'>{{ showAmount($weight) }}</span>
                            @lang("lb")
                            </span>
                                </div>
                            @endif

                            <div class="auction__item-total-container gap-2">
                                <span class="auction__item-total-title fw-bold ">@lang('Total'):</span>
                                <span class="text-truncate auction__item-total-value animate-value paragraph-level-2">
                            US{{ $general->cur_sym }}
                                <span id="product_{{$product->id}}_total_price" title="{{ showAmount($total_price) }}">
                                    {{ showAmount($total_price) }}
                                </span>
                            </span>
                            </div>
                        </div>
                    </div>

                    <div class="mt-3 mt-lg-0 auction__item-content_bid auction__item-content_bid-custom">
                        @if (($product->status == 1 && $product->started_at < now() && $product->expired_at > now()) || $is_event_ended)
                            <div class="btn__area gap-2 gap-lg-4 gap-xl-0 w-100">

                                {{-- <div class="cart-plus-minus input-group w-auto">--}}
                                {{-- <span--}}
                                {{-- class="input-group-text bg--base border-0 text-white">{{ $general->cur_sym }}</span>--}}
                                {{-- <input type="number" placeholder="@lang('Enter your amount')" class="form-control"--}}
                                {{-- id="amountbid" min="0" step="any">--}}
                                {{-- </div>--}}

                                <div class="custom_total gap-lg-4 gap-xl-3">
                                    <div class="d-flex total-price total-price-custom animate-value">
                                        @if ($product->bids != '[]')
                                                <?php $amount = 0; ?>
                                            @foreach ($product->bids->where('product_id',$product->id) as $pp)
                                                    <?php $temp = $pp->amount;
                                                    if ($amount < $temp) {
                                                        $amount = $temp;
                                                    }
                                                    ?>
                                            @endforeach
                                            <span>US$</span>
                                            <span id="product_{{$product->id}}_price" class="text-truncate"
                                                  title="{{ showAmount($amount) }}">
                                        {{ showAmount($amount) }}
                                    </span>
                                            <span>/lb</span>
                                                <?php $amount = 0; ?>
                                        @else
                                            <span>US$</span>
                                            <span id="product_{{$product->id}}_price" class="text-truncate"
                                                  title="{{ showAmount($product->price) }}">{{ showAmount($product->price) }}</span>
                                            <span>/lb</span>
                                        @endif
                                    </div>

                                        <div
                                            class="auction__item-numOfBids justify-content-start justify-content-xl-end align-items-center">
                                            <svg width="26" height="26" viewBox="0 0 26 26" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path d="M3.83504 26C4.13914 25.9156 4.44324 25.865 4.73045 25.7469C5.35554 25.5107 5.84548 25.1057 6.26785 24.5827C8.64997 21.6637 11.0152 18.7279 13.4142 15.8257C13.9041 15.2183 14.5292 14.729 15.1205 14.1722C15.4584 14.5097 15.8301 14.864 16.1849 15.2352C16.2187 15.2689 16.2187 15.3871 16.1849 15.4545C15.7794 16.6188 16.2356 17.7661 17.3337 18.3229C18.1784 18.7616 19.2259 18.5929 19.9693 17.8505C21.7432 16.0957 23.5171 14.3241 25.291 12.5356C25.9499 11.8607 26.1695 11.0676 25.8654 10.1565C25.5782 9.36349 24.9869 8.8573 24.1422 8.67171C23.7198 8.58734 23.3144 8.62109 22.9258 8.77294C22.7906 8.82356 22.7062 8.80669 22.6048 8.70545C20.8478 6.93381 19.0739 5.17905 17.3168 3.40741C17.2492 3.33992 17.2155 3.20494 17.2324 3.13745C17.6378 2.07447 17.2492 0.960866 16.3369 0.370321C15.4078 -0.220225 14.2758 -0.102116 13.448 0.707775C11.6572 2.46254 9.88326 4.23418 8.12624 6.02269C6.90983 7.2544 7.3322 9.19476 8.90338 9.80218C9.42711 10.0047 9.96773 10.0047 10.4915 9.80218C10.6266 9.75156 10.7111 9.76843 10.8294 9.86967C11.1672 10.2071 11.5051 10.5446 11.8599 10.8989C11.7923 10.9833 11.7248 11.0339 11.6741 11.1014C10.728 12.1137 9.68053 12.9911 8.59928 13.8685C6.20027 15.8089 3.80125 17.7661 1.40224 19.7065C0.658882 20.3139 0.168945 21.0563 0.0337887 22.018C0.0337887 22.0518 0 22.1024 0 22.1361C0 22.3892 0 22.6423 0 22.8954C0.0168953 22.946 0.0337887 23.0135 0.0506821 23.0641C0.253416 24.1946 0.861618 25.0551 1.89218 25.5782C2.26386 25.7638 2.70311 25.8481 3.10858 25.9831C3.32821 26 3.58163 26 3.83504 26ZM17.2661 14.0879C15.4753 12.2993 13.7014 10.5277 11.9444 8.77294C13.3466 7.3725 14.7827 5.93832 16.168 4.55476C17.925 6.30952 19.7158 8.09803 21.4898 9.86967C20.0875 11.2701 18.6684 12.6874 17.2661 14.0879ZM11.8768 15.3027C11.8261 15.3702 11.7754 15.4377 11.7079 15.5052C9.98463 17.6311 8.2445 19.7571 6.52126 21.8831C5.94685 22.5748 5.40623 23.3004 4.79803 23.9415C3.81815 24.9539 1.99355 24.4646 1.60497 23.0979C1.35156 22.1868 1.63876 21.4781 2.34833 20.8876C4.51082 19.1328 6.67331 17.378 8.81891 15.6233C9.44401 15.1171 10.086 14.594 10.6942 14.1047C11.0997 14.5097 11.4713 14.8977 11.8768 15.3027ZM24.4801 10.8483C24.497 11.1689 24.3449 11.3713 24.1591 11.5569C22.419 13.278 20.6957 15.0159 18.9725 16.7369C18.9049 16.8044 18.8542 16.855 18.7866 16.9056C18.3643 17.2262 17.773 17.0237 17.6209 16.5175C17.5027 16.1632 17.6547 15.8932 17.9081 15.657C19.6145 13.936 21.3377 12.1981 23.0778 10.4771C23.1454 10.4096 23.213 10.3421 23.2806 10.2915C23.534 10.1228 23.7874 10.1059 24.0577 10.2409C24.3111 10.3759 24.4463 10.5952 24.4801 10.8483ZM15.847 2.29381C15.8639 2.56378 15.7119 2.74938 15.5429 2.93498C13.8028 4.67287 12.0626 6.41076 10.3394 8.13178C10.2887 8.18239 10.238 8.23301 10.1705 8.28363C9.73121 8.60421 9.12301 8.40174 8.98785 7.86181C8.90338 7.52436 9.03854 7.27127 9.27506 7.05192C10.9814 5.3309 12.7215 3.59301 14.4617 1.85512C14.5292 1.78763 14.5968 1.72014 14.6644 1.66952C14.9178 1.51767 15.1712 1.50079 15.4415 1.63578C15.7119 1.77076 15.8301 1.9901 15.847 2.29381ZM12.9581 14.0879C12.6033 13.7335 12.2485 13.3961 11.9275 13.0755C12.2654 12.738 12.6033 12.3837 12.9243 12.0631C13.2622 12.4006 13.6169 12.7549 13.9548 13.0924C13.6338 13.4129 13.279 13.7673 12.9581 14.0879Z" fill="url(#paint0_linear_1191_2280)"/>
                                                <defs>
                                                <linearGradient id="paint0_linear_1191_2280" x1="24.1456" y1="20.0927" x2="2.32034" y2="6.71568" gradientUnits="userSpaceOnUse">
                                                <stop stop-color="#008CBD"/>
                                                <stop offset="0.505208" stop-color="#008E8F"/>
                                                <stop offset="1" stop-color="#008C64"/>
                                                </linearGradient>
                                                </defs>
                                                </svg>
                                                
                                            <span id="auction__item-total-bids" class="paragraph-level-2"
                                                  class="animate-value">
                                        @lang('x')
                                        <span id="product_{{$product->id}}_bids_count" class="text-truncate"
                                              title="{{ ($product->total_bid) }}">
                                        {{ ($product->total_bid) }}
                                        </span>
                                    </span>
                                        </div>

                                </div>
                                <div class="p-0 w-100 lg-w-auto xxl-w-100 div-bid-now mt-xl-3">
                                    <div id="input-bid-with-anim"
                                         class="cart-plus-minus input-group w-auto input-bid-with-anim">
                                    <span class="input-group-text bg--base border-0 text-white">
                                        {{ $general->cur_sym }}
                                    </span>
                                        <input type="text" class="form-control" autocomplete="off" id="amount">
                                    </div>
                                    @if(Auth::check() && $is_in_allow_product_for_logged_in_user)
                                        <div
                                            class="w-100 lg-w-auto xxl-w-100 d-flex flex-wrap justify-content-end gap-1"
                                            id="bid_area_{{ $product->id }}"
                                            s>
                                            @include('templates.basic.event.bid_area',['product'=>$product,'event'=>$event,'is_event_ended'=>$is_event_ended])
                                        </div>
                                    @else
{{--                                        <div--}}
{{--                                            class="w-100 lg-w-auto xxl-w-100 d-flex flex-wrap justify-content-end gap-1"--}}
{{--                                            id="bid_area_{{ $product->id }}"--}}
{{--                                            s>--}}
{{--                                            @include('templates.basic.event.bid_area_disable',['product'=>$product,'event'=>$event,'is_event_ended'=>$is_event_ended])--}}
{{--                                        </div>--}}
                                    @endif


                                    <input type="hidden" class="idproduct" value="{{ $product->id }}">
                                </div>

                                {{-- <div class="div-bid-now mt-2">--}}
                                {{-- <button class="cmn--btn btn--sm auto_bid_now"--}}
                                {{-- data-cur_sym="{{ $general->cur_sym }}">@lang('Auto Bid')</button>--}}
                                {{-- <input type="hidden" class="idproduct" value="{{ $product->id }}">--}}
                                {{-- </div>--}}
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    @empty
        <div class="text-center">
            {{-- {{ $emptyMessage }}--}}
        </div>
    @endforelse
</div>
