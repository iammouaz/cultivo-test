@php
    $process = $product->specification;
    $heroPrimaryButtonColor = getHeroPrimaryButtonColor($event);

@endphp

<div class="inner-product-card d-none">
    <div class="inner-product-card-body d-flex flex-column flex-md-row position-relative">
        <img class="inner-product-card-image" src="{{getImage(imagePath()['product']['path'].'/thumb_'.$product->image,imagePath()['product']['thumb'])}}" alt="{{$product->name}}" />

        <div class="details">
            <div class="w-100 d-flex gap-3 mb-2">
                <h2 title="{{$product->name}}" class="heading-5 text-truncate mt-0">{{$product->name}}</h2>
                @if(Auth::check())
                    <button class="product-fav-btn" onclick="toggleFavoriteStatus({{$product->id}})">
                        @if(!$product->is_fav)
                        <i class="favorite-icon-{{$product->id}} far fa-heart heart-icon black-75"></i>
                        @else
                        <i class="favorite-icon-{{$product->id}} far fa-heart heart-icon black-75 heart-icon-color"></i>
                        @endif
                    </button>
                @endif
            </div>

            <div class="d-flex align-items-center">
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
                    
                @foreach($product->product_specification as $spec)
                    @if($spec->is_display == 1 && strtoupper($spec->spec_key) === 'SCORE')
                        <span class="score-property">{{showAmount($spec->Value)}}</span>
                    @endif
                @endforeach

                <div class="user-score-{{$product->id}} d-flex align-items-center">
                    <div @if($product->logged_in_user_score !== false) class="user-score-value-container" @else class="user-score-value-container d-none" @endif>
                        <span>/</span>
                        <span class="user-score-value" data-score="{{$product->logged_in_user_score === false ? 'false': $product->logged_in_user_score}}">
                            {{showAmount($product->logged_in_user_score)}}
                        </span>
                    </div>

                    @if(Auth::check())
                    <div class="position-relative">
                        <button class="show-user-score-modal-btn" onclick="showUserScoreModal({{$product->id}},this)">
                            <i @if($product->logged_in_user_score === false) class="fas fa-plus" @else class="fas fa-pencil-alt" @endif></i>

                            <div class="custom-tooltip custom-tooltip-right">
                                @if($product->logged_in_user_score === false)
                                    @lang('Add your score')
                                @else
                                   @lang('Edit your score')
                                @endif
                            </div>
                        </button>

                        <div class="user-score-modal d-none">
                            <div class="score-progress d-none">
                                <i class="fas fa-spinner fa-pulse"></i>
                            </div>

                            <h3>@lang('Add Your Score')</h3>
                            <input class="user-score-input" type="number" value="{{$product->logged_in_user_score === false ? '' : showAmount($product->logged_in_user_score)}}"
                            onchange="convert_to_dec(this)"
                            onfocus="this.select();">

                            <span class="invalid-score-value d-none">@lang('Score must be between 1 and 100') </span>

                            <div class="user-score-modal-controls d-flex justify-content-end gap-2">
                                <button class="user-score-cancel-btn" onclick="hideUserScoreModal({{$product->id}},this)">@lang('Cancel')</button>
                                <button class="user-score-save-btn" onclick="submitNewScore({{$product->id}},this)">@lang('Save')</button>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            <div class="properties w-100 d-flex">
                <div class="w-50">
                    @foreach($product->product_specification as $spec)
                        @if($spec->is_display == 1 && strtoupper($spec->spec_key) === 'Rank')
                            <div class="property d-flex w-100">
                                <span>@lang("Rank:")</span>
                                <span title="{{$spec->Value}}" class="w-100 text-truncate">
                                    {{$spec->Value}}
                                </span>
                            </div>
                        @endif
                    @endforeach
                    @foreach($product->product_specification as $spec)
                        @if($spec->is_display == 1 && strtoupper($spec->spec_key) === 'PROCESS')
                            <div class="property d-flex w-100">
                                <span>@lang("Process:")</span>
                                <span title="{{$spec->Value}}" class="w-100 text-truncate">
                                    {{$spec->Value}}
                                </span>
                            </div>
                        @endif
                    @endforeach
                    @foreach($product->product_specification as $spec)
                        @if($spec->is_display == 1 && strtoupper($spec->spec_key) === 'VARIETY')
                            <div class="property d-flex w-100">
                                <span>@lang("Variety:")</span>
                                <span title="{{$spec->Value}}" class="w-100 text-truncate">
                                    {{$spec->Value}}
                                </span>
                            </div>
                        @endif
                    @endforeach
                </div>
                <div class="w-50">
                    @foreach($product->product_specification as $spec)
                        @if($spec->is_display == 1 && (strtoupper($spec->spec_key) === 'BOXES' || strtoupper($spec->spec_key) === 'SIZE'))
                            <div class="property d-flex w-100">
                                <span>@lang("Boxes:")</span>
                                <span title="{{$spec->Value}}" class="w-100 text-truncate">
                                    {{$spec->Value}}
                                </span>
                            </div>
                        @endif
                    @endforeach
                    @foreach($product->product_specification as $spec)
                        @if($spec->is_display == 1 && strtoupper($spec->spec_key) === 'WEIGHT')
                            <div class="property d-flex w-100">
                                <span>@lang("Weight:")</span>
                                <span title="{{showAmount($spec->Value)}} lb" class="w-100 text-truncate">
                                    {{showAmount($spec->Value)}}
                                    <span> lb</span>
                                </span>
                            </div>
                        @endif
                    @endforeach
                    @foreach($product->product_specification as $spec)
                        @if($spec->is_display == 1 && strtoupper($spec->spec_key) === 'REGION')
                            <div class="property d-flex w-100">
                                <span>@lang("Region:")</span>
                                <span title="{{$spec->Value}}" class="w-100 text-truncate">
                                    {{$spec->Value}}
                                </span>
                            </div>
                        @endif
                    @endforeach
                    @foreach($product->product_specification as $spec)
                        @if($spec->is_display == 1 && strtoupper($spec->spec_key) === 'NOTES')
                            <div class="notes-property">
                                <span title="{{$spec->Value}}">
                                    <b>@lang("Notes:")</b>
                                    {{$spec->Value}}
                                </span>
                            </div>
                        @endif
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <div class="inner-product-card-footer">
        <div class="summary d-flex flex-column flex-md-row align-items-center">
            <div class="highest-info flex-shrink-0 d-flex align-items-center">
                <svg width="33" height="45" viewBox="0 0 33 45" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M20.7422 2.43L16.0481 0L11.3541 2.43L6.12844 3.22313L3.76032 7.94813L0 11.6606L0.860626 16.875L0 22.0894L3.76032 25.8019L6.12844 30.5269L11.3541 31.32L16.0481 33.75L20.7422 31.32L25.9678 30.5269L28.336 25.8019L32.0963 22.0894L31.2357 16.875L32.0963 11.6606L28.336 7.94813L25.9678 3.22313L20.7422 2.43ZM24.106 5.78532L26.0297 9.62438L29.0841 12.6394L28.3866 16.875L29.0841 21.1106L26.0297 24.1257L24.106 27.9647L19.8591 28.6088L16.0481 30.5832L12.2372 28.6088L7.99032 27.9647L6.06657 24.1257L3.01219 21.1106L3.7125 16.875L3.00938 12.6394L6.06657 9.62438L7.99032 5.78532L12.2372 5.14125L16.0481 3.16688L19.8619 5.14125L24.106 5.78532Z" fill="url(#paint0_diamond_1703_75)"/>
                    <path d="M4.7981 33.1707V45L16.0481 42.1875L27.2981 45V33.1707L21.6225 34.0313L16.0481 36.9169L10.4737 34.0313L4.7981 33.1707Z" fill="url(#paint1_diamond_1703_75)"/>
                    <defs>
                    <radialGradient id="paint0_diamond_1703_75" cx="0" cy="0" r="1" gradientUnits="userSpaceOnUse" gradientTransform="translate(17.0849 10.2509) rotate(112.7) scale(25.7588 22.9006)">
                    <stop stop-color="#DAA900" stop-opacity="0.17"/>
                    <stop offset="1" stop-color="#DAA900"/>
                    </radialGradient>
                    <radialGradient id="paint1_diamond_1703_75" cx="0" cy="0" r="1" gradientUnits="userSpaceOnUse" gradientTransform="translate(16.7749 36.7636) rotate(129.917) scale(10.8596 13.3467)">
                    <stop stop-color="#DAA900" stop-opacity="0.17"/>
                    <stop offset="1" stop-color="#DAA900"/>
                    </radialGradient>
                    </defs>
                    </svg>
                    
                <span title="{{highestbidder($product->id)}}" class="text-truncate" id="popup_highest_bidder_product_{{$product->id}}">
                    {{highestbidder($product->id)}}
                </span>
            </div>
            <div class="bid-info d-flex align-items-center trending-animate">
                <svg width="26" height="26" viewBox="0 0 26 26" fill="#292A2A" fill-opacity="0.75" xmlns="http://www.w3.org/2000/svg">
                    <path d="M3.83504 26C4.13914 25.9156 4.44324 25.865 4.73045 25.7469C5.35554 25.5107 5.84548 25.1057 6.26785 24.5827C8.64997 21.6637 11.0152 18.7279 13.4142 15.8257C13.9041 15.2183 14.5292 14.729 15.1205 14.1722C15.4584 14.5097 15.8301 14.864 16.1849 15.2352C16.2187 15.2689 16.2187 15.3871 16.1849 15.4545C15.7794 16.6188 16.2356 17.7661 17.3337 18.3229C18.1784 18.7616 19.2259 18.5929 19.9693 17.8505C21.7432 16.0957 23.5171 14.3241 25.291 12.5356C25.9499 11.8607 26.1695 11.0676 25.8654 10.1565C25.5782 9.36349 24.9869 8.8573 24.1422 8.67171C23.7198 8.58734 23.3144 8.62109 22.9258 8.77294C22.7906 8.82356 22.7062 8.80669 22.6048 8.70545C20.8478 6.93381 19.0739 5.17905 17.3168 3.40741C17.2492 3.33992 17.2155 3.20494 17.2324 3.13745C17.6378 2.07447 17.2492 0.960866 16.3369 0.370321C15.4078 -0.220225 14.2758 -0.102116 13.448 0.707775C11.6572 2.46254 9.88326 4.23418 8.12624 6.02269C6.90983 7.2544 7.3322 9.19476 8.90338 9.80218C9.42711 10.0047 9.96773 10.0047 10.4915 9.80218C10.6266 9.75156 10.7111 9.76843 10.8294 9.86967C11.1672 10.2071 11.5051 10.5446 11.8599 10.8989C11.7923 10.9833 11.7248 11.0339 11.6741 11.1014C10.728 12.1137 9.68053 12.9911 8.59928 13.8685C6.20027 15.8089 3.80125 17.7661 1.40224 19.7065C0.658882 20.3139 0.168945 21.0563 0.0337887 22.018C0.0337887 22.0518 0 22.1024 0 22.1361C0 22.3892 0 22.6423 0 22.8954C0.0168953 22.946 0.0337887 23.0135 0.0506821 23.0641C0.253416 24.1946 0.861618 25.0551 1.89218 25.5782C2.26386 25.7638 2.70311 25.8481 3.10858 25.9831C3.32821 26 3.58163 26 3.83504 26ZM17.2661 14.0879C15.4753 12.2993 13.7014 10.5277 11.9444 8.77294C13.3466 7.3725 14.7827 5.93832 16.168 4.55476C17.925 6.30952 19.7158 8.09803 21.4898 9.86967C20.0875 11.2701 18.6684 12.6874 17.2661 14.0879ZM11.8768 15.3027C11.8261 15.3702 11.7754 15.4377 11.7079 15.5052C9.98463 17.6311 8.2445 19.7571 6.52126 21.8831C5.94685 22.5748 5.40623 23.3004 4.79803 23.9415C3.81815 24.9539 1.99355 24.4646 1.60497 23.0979C1.35156 22.1868 1.63876 21.4781 2.34833 20.8876C4.51082 19.1328 6.67331 17.378 8.81891 15.6233C9.44401 15.1171 10.086 14.594 10.6942 14.1047C11.0997 14.5097 11.4713 14.8977 11.8768 15.3027ZM24.4801 10.8483C24.497 11.1689 24.3449 11.3713 24.1591 11.5569C22.419 13.278 20.6957 15.0159 18.9725 16.7369C18.9049 16.8044 18.8542 16.855 18.7866 16.9056C18.3643 17.2262 17.773 17.0237 17.6209 16.5175C17.5027 16.1632 17.6547 15.8932 17.9081 15.657C19.6145 13.936 21.3377 12.1981 23.0778 10.4771C23.1454 10.4096 23.213 10.3421 23.2806 10.2915C23.534 10.1228 23.7874 10.1059 24.0577 10.2409C24.3111 10.3759 24.4463 10.5952 24.4801 10.8483ZM15.847 2.29381C15.8639 2.56378 15.7119 2.74938 15.5429 2.93498C13.8028 4.67287 12.0626 6.41076 10.3394 8.13178C10.2887 8.18239 10.238 8.23301 10.1705 8.28363C9.73121 8.60421 9.12301 8.40174 8.98785 7.86181C8.90338 7.52436 9.03854 7.27127 9.27506 7.05192C10.9814 5.3309 12.7215 3.59301 14.4617 1.85512C14.5292 1.78763 14.5968 1.72014 14.6644 1.66952C14.9178 1.51767 15.1712 1.50079 15.4415 1.63578C15.7119 1.77076 15.8301 1.9901 15.847 2.29381ZM12.9581 14.0879C12.6033 13.7335 12.2485 13.3961 11.9275 13.0755C12.2654 12.738 12.6033 12.3837 12.9243 12.0631C13.2622 12.4006 13.6169 12.7549 13.9548 13.0924C13.6338 13.4129 13.279 13.7673 12.9581 14.0879Z"/>
                    </svg>
                    
                    
                <span title="{{$product->total_bid}}" class="text-truncate" id="product_{{$product->id}}_bids_count{{$idSuffix}}">
                    {{$product->total_bid}}
                </span>
            </div>
            <div class="card-profile-button">
                <a style="background-color: {{$heroPrimaryButtonColor}} !important;" class="cmn--btn order-samples" href="{{ route('product.details', [$product->id, slug($product->name)]) }}">
                    @lang('VISIT PROFILE')
                </a>
            </div>
        </div>
    </div>
</div>
