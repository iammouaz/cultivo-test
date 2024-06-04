@php
    $process = $product->specification;
@endphp

<div class="inner-product-card d-none">
    <div class="inner-product-card-body d-flex flex-column flex-md-row position-relative">
        <img class="inner-product-card-image" src="{{getImage(imagePath()['product']['path'].'/thumb_'.$product->photo,imagePath()['product']['thumb'])}}" alt="{{$product->name}}" />

        <div class="details">
            <div class="w-100 d-flex gap-3 mb-2">
                <h2 title="{{$product->name}}" class="heading-5 text-truncate mt-0">{{$product->name}}</h2>
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
                    

                    @if(isset($score))
                        <span class="score-property">{{showAmount($score)}}</span>
                    @endif

            </div>

            <div class="properties w-100 d-flex">
                <div class="w-50">

                    @if(isset($status))
                        <div class="property d-flex w-100">
                            <span>@lang('Status:')</span>
                            <span class="w-100 text-truncate">
                                    {{$status}}
                                </span>
                        </div>
                    @endif


                    @if(isset($producer))
                        <div class="property d-flex w-100">
                            <span>@lang('Producer:')</span>
                            <span class="w-100 text-truncate">
                                    {{$producer}}
                                </span>
                        </div>
                    @endif


                    @if(isset($region))
                        <div class="property d-flex w-100">
                            <span>@lang('Region:')</span>
                            <span class="w-100 text-truncate">
                                    {{$region}}
                                </span>
                        </div>
                    @endif

                    @if(isset($process_method))
                        <div class="property d-flex w-100">
                            <span>@lang('Process:')</span>
                            <span class="w-100 text-truncate">
                                {{$process_method}}
                            </span>
                        </div>
                    @endif

                    </div>
                    <div class="w-50">

                    @if(isset($variety))
                        <div class="property d-flex w-100">
                            <span>@lang('Varieties:')</span>
                            <span class="w-100 text-truncate">
                                    {{$variety}}
                                </span>
                        </div>
                    @endif

                    @if(isset($screen))
                        <div class="property d-flex w-100">
                            <span>@lang('Screen:')</span>
                            <span class="w-100 text-truncate">
                                    {{$screen}}
                                </span>
                        </div>
                    @endif

                </div>
            </div>
        </div>
    </div>

    <div class="inner-product-card-footer">
        <div class="summary d-flex justify-content-end">
            <a class="primary-filled-btn" href="{{ route('offer.details', [$product->id, slug($product->name)]) }}">
                @lang('VISIT PROFILE')
            </a>
        </div>
    </div>
</div>
