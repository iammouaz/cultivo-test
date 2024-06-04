@extends($activeTemplate . 'layouts.frontend', ['event_type' => $product->event->event_type,'login_type'=>$product->event->login_type??"normal"])
@php
    $event??=$product->event;
    $score = 0;
    $variety = '';
    $process = '';
    $weight = 0;
    $rank = '';
    $total_price = 0;
    $display_score = false;
    $display_variety = false;
    $display_process = false;
    $display_weight = false;
    $display_rank = false;
    $price = 0;
    if ($product->product_specification) {
        foreach ($product->product_specification as $spec) {
            if (strtoupper($spec->spec_key) == 'SCORE') {
                $score = $spec->Value;
            }
            if (strtoupper($spec->spec_key) == 'VARIETY') {
                $variety = $spec->Value;
            }
            if (strtoupper($spec->spec_key) == 'PROCESS') {
                $process = $spec->Value;
            }
            if (strtoupper($spec->spec_key) == 'WEIGHT') {
                $weight = $spec->Value;
            }
            if (strtoupper($spec->spec_key) == 'RANK') {
                $rank = $spec->Value;
            }
        }
    }

    $price = isset($product->max_bid()->amount) ? $product->max_bid()->amount : $product->price;
    $total_price = $weight * $price;
    $policy_id = get_policy_id();
    $codes = get_extention_keys('pusher');
    $heroPrimaryButtonColor = getHeroPrimaryButtonColor($event);

@endphp

@section('content')
    <!-- Product details -->
    <section id="product-details" class="product-section">

        <div class="cup-progress-container d-none">
            <div style="color: var(--main-svg-color) !important;" class="spinner-border text-primary" role="status">
                <span class="sr-only">Loading...</span>
              </div>
            <p>@lang('Refreshing the bid...')</p>
        </div>

        <div class="container">

            <div class="row gy-md-5 justify-content-between">
                <div class="mx-md-auto mx-lg-0 col-md-7 col-xl-8 col-xxl-7">
                    <div class="back-button">
                        <a class="d-flex align-items-center gap-3"
                            href="{{ route('event.details', [$product->event_id, slug($product->event->name)]) }}">
                            <i class="fas fa-arrow-left"></i>
                            <span>@lang('Back to auction')</span>
                        </a>
                    </div>
                    <div class="product__single-item">
                        <div class="product-thumb-area d-flex flex-wrap flex-xl-nowrap">
                            <div class="product-thumb mb-md-5 mb-xl-0">
                                <img id="product-image" alt="product">
                                <div class="meta-post d-flex justify-content-center mt-2 mt-lg-4">
                                    <div class="meta-item m-0 gap-1 gap-lg-2">
                                        <svg width="33" height="45" viewBox="0 0 33 45" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M20.7422 2.43L16.0481 0L11.3541 2.43L6.12844 3.22313L3.76032 7.94813L0 11.6606L0.860626 16.875L0 22.0894L3.76032 25.8019L6.12844 30.5269L11.3541 31.32L16.0481 33.75L20.7422 31.32L25.9678 30.5269L28.336 25.8019L32.0963 22.0894L31.2357 16.875L32.0963 11.6606L28.336 7.94813L25.9678 3.22313L20.7422 2.43ZM24.106 5.78532L26.0297 9.62438L29.0841 12.6394L28.3866 16.875L29.0841 21.1106L26.0297 24.1257L24.106 27.9647L19.8591 28.6088L16.0481 30.5832L12.2372 28.6088L7.99032 27.9647L6.06657 24.1257L3.01219 21.1106L3.7125 16.875L3.00938 12.6394L6.06657 9.62438L7.99032 5.78532L12.2372 5.14125L16.0481 3.16688L19.8619 5.14125L24.106 5.78532Z" fill="url(#paint0_linear_1741_390)"/>
                                            <path d="M4.7981 33.1707V45L16.0481 42.1875L27.2981 45V33.1707L21.6225 34.0313L16.0481 36.9169L10.4737 34.0313L4.7981 33.1707Z" fill="url(#paint1_linear_1741_390)"/>
                                            <defs>
                                            <linearGradient id="paint0_linear_1741_390" x1="2.28916" y1="26.0819" x2="29.9541" y2="9.95653" gradientUnits="userSpaceOnUse">
                                            <stop stop-color="#008CBD"/>
                                            <stop offset="0.505208" stop-color="#008E8F"/>
                                            <stop offset="1" stop-color="#008C64"/>
                                            </linearGradient>
                                            <linearGradient id="paint1_linear_1741_390" x1="6.40284" y1="42.3123" x2="17.4168" y2="29.4724" gradientUnits="userSpaceOnUse">
                                            <stop stop-color="#008CBD"/>
                                            <stop offset="0.505208" stop-color="#008E8F"/>
                                            <stop offset="1" stop-color="#008C64"/>
                                            </linearGradient>
                                            </defs>
                                            </svg>

                                        <span id="highest-bidder">
                                            <span class="fw-bold">@lang('Highest Bidder'): </span>
                                            <span id="highestbidder_name">
                                                {{ __(highestbidder($product->id)) }}
                                            </span>
                                        </span>
                                    </div>

                                    <!-- <div class="meta-item me-0">
                                                                                    <span class="text--base"><i class="lar la-share-square"></i></span>
                                                                                    <ul class="social-share">
                                                                                        <li>
                                                                                            <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(url()->current()) }}" title="@lang('Facebook')" target="blank"><i class="fab fa-facebook"></i></a>
                                                                                        </li>

                                                                                        <li>
                                                                                            <a href="http://pinterest.com/pin/create/button/?url={{ urlencode(url()->current()) }}&description={{ $product->name }}&media={{ getImage('assets/images/product/' . @$product->main_image) }}" title="@lang('Pinterest')" target="blank"><i class="fab fa-pinterest-p"></i></a>
                                                                                        </li>

                                                                                        <li>
                                                                                            <a href="http://www.linkedin.com/shareArticle?mini=true&amp;url={{ urlencode(url()->current()) }}&amp;title={{ $product->name }}&amp;summary={{ shortDescription($product->summary) }}" title="@lang('Linkedin')" target="blank"><i class="fab fa-linkedin"></i></a>
                                                                                        </li>

                                                                                        <li>
                                                                                            <a href="https://twitter.com/intent/tweet?text={{ $product->name }}%0A{{ url()->current() }}" title="@lang('Twitter')" target="blank">
                                                                                                <i class="fab fa-twitter"></i>
                                                                                            </a>
                                                                                        </li>
                                                                                    </ul>
                                                                                </div>-->
                                </div>
                            </div>

                            <div class="product-content">
                                <h5 class="title heading-3 mt-0 mb-0 text-capitalize">{{ $rank }}
                                    {{ $product->name }}</h5>
                                <span id="total_price" hidden>{{ $total_price }}</span>
                                <!-- <div class="ratings mb-4">
                                                                                @php echo displayAvgRating($product->avg_rating); @endphp
                                                                                ({{ $product->review_count }})
                                                                        </div>-->
                                <h3 class="paragraph-level-1 mb-3 mb-lg-4 pb-3 mt-0 fw-normal orgianl-auction-title">
                                    {{ __(shortDescription($product->event->name)) }}
                                    <!-- {{ __(shortDescription($product->short_description)) }} -->
                                </h3>
                                <!-- <p class="mb-4 mt-0">
                                                                        Event : <a class="btn btn-primary-facebook" href="{{ route('event.details', [$product->event_id, slug($product->event->name)]) }}">{{ $product->event->name }}</a>
                                                                    </p> -->
                                <div class="product-price mb-0">
                                    <p id="bid_price">
                                        @if ($product->bids != '[]')
                                            <?php $amount = 0; ?>
                                            @foreach ($product->bids->where('product_id', $product->id) as $pp)
                                                <?php $temp = $pp->amount;
                                                if ($amount < $temp) {
                                                    $amount = $temp;
                                                }
                                                ?>
                                            @endforeach
                                            US${{ showAmount($amount) }}/lb
                                            <?php $amount = 0; ?>
                                        @else
                                            US${{ showAmount($product->price) }}/lb
                                        @endif
                                    </p>
                                    <div class="d-flex align-items-center flex-shrink-0">
                                        <svg width="26" height="26" viewBox="0 0 26 26" fill="#292A2A" fill-opacity="0.75" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M3.83504 26C4.13914 25.9156 4.44324 25.865 4.73045 25.7469C5.35554 25.5107 5.84548 25.1057 6.26785 24.5827C8.64997 21.6637 11.0152 18.7279 13.4142 15.8257C13.9041 15.2183 14.5292 14.729 15.1205 14.1722C15.4584 14.5097 15.8301 14.864 16.1849 15.2352C16.2187 15.2689 16.2187 15.3871 16.1849 15.4545C15.7794 16.6188 16.2356 17.7661 17.3337 18.3229C18.1784 18.7616 19.2259 18.5929 19.9693 17.8505C21.7432 16.0957 23.5171 14.3241 25.291 12.5356C25.9499 11.8607 26.1695 11.0676 25.8654 10.1565C25.5782 9.36349 24.9869 8.8573 24.1422 8.67171C23.7198 8.58734 23.3144 8.62109 22.9258 8.77294C22.7906 8.82356 22.7062 8.80669 22.6048 8.70545C20.8478 6.93381 19.0739 5.17905 17.3168 3.40741C17.2492 3.33992 17.2155 3.20494 17.2324 3.13745C17.6378 2.07447 17.2492 0.960866 16.3369 0.370321C15.4078 -0.220225 14.2758 -0.102116 13.448 0.707775C11.6572 2.46254 9.88326 4.23418 8.12624 6.02269C6.90983 7.2544 7.3322 9.19476 8.90338 9.80218C9.42711 10.0047 9.96773 10.0047 10.4915 9.80218C10.6266 9.75156 10.7111 9.76843 10.8294 9.86967C11.1672 10.2071 11.5051 10.5446 11.8599 10.8989C11.7923 10.9833 11.7248 11.0339 11.6741 11.1014C10.728 12.1137 9.68053 12.9911 8.59928 13.8685C6.20027 15.8089 3.80125 17.7661 1.40224 19.7065C0.658882 20.3139 0.168945 21.0563 0.0337887 22.018C0.0337887 22.0518 0 22.1024 0 22.1361C0 22.3892 0 22.6423 0 22.8954C0.0168953 22.946 0.0337887 23.0135 0.0506821 23.0641C0.253416 24.1946 0.861618 25.0551 1.89218 25.5782C2.26386 25.7638 2.70311 25.8481 3.10858 25.9831C3.32821 26 3.58163 26 3.83504 26ZM17.2661 14.0879C15.4753 12.2993 13.7014 10.5277 11.9444 8.77294C13.3466 7.3725 14.7827 5.93832 16.168 4.55476C17.925 6.30952 19.7158 8.09803 21.4898 9.86967C20.0875 11.2701 18.6684 12.6874 17.2661 14.0879ZM11.8768 15.3027C11.8261 15.3702 11.7754 15.4377 11.7079 15.5052C9.98463 17.6311 8.2445 19.7571 6.52126 21.8831C5.94685 22.5748 5.40623 23.3004 4.79803 23.9415C3.81815 24.9539 1.99355 24.4646 1.60497 23.0979C1.35156 22.1868 1.63876 21.4781 2.34833 20.8876C4.51082 19.1328 6.67331 17.378 8.81891 15.6233C9.44401 15.1171 10.086 14.594 10.6942 14.1047C11.0997 14.5097 11.4713 14.8977 11.8768 15.3027ZM24.4801 10.8483C24.497 11.1689 24.3449 11.3713 24.1591 11.5569C22.419 13.278 20.6957 15.0159 18.9725 16.7369C18.9049 16.8044 18.8542 16.855 18.7866 16.9056C18.3643 17.2262 17.773 17.0237 17.6209 16.5175C17.5027 16.1632 17.6547 15.8932 17.9081 15.657C19.6145 13.936 21.3377 12.1981 23.0778 10.4771C23.1454 10.4096 23.213 10.3421 23.2806 10.2915C23.534 10.1228 23.7874 10.1059 24.0577 10.2409C24.3111 10.3759 24.4463 10.5952 24.4801 10.8483ZM15.847 2.29381C15.8639 2.56378 15.7119 2.74938 15.5429 2.93498C13.8028 4.67287 12.0626 6.41076 10.3394 8.13178C10.2887 8.18239 10.238 8.23301 10.1705 8.28363C9.73121 8.60421 9.12301 8.40174 8.98785 7.86181C8.90338 7.52436 9.03854 7.27127 9.27506 7.05192C10.9814 5.3309 12.7215 3.59301 14.4617 1.85512C14.5292 1.78763 14.5968 1.72014 14.6644 1.66952C14.9178 1.51767 15.1712 1.50079 15.4415 1.63578C15.7119 1.77076 15.8301 1.9901 15.847 2.29381ZM12.9581 14.0879C12.6033 13.7335 12.2485 13.3961 11.9275 13.0755C12.2654 12.738 12.6033 12.3837 12.9243 12.0631C13.2622 12.4006 13.6169 12.7549 13.9548 13.0924C13.6338 13.4129 13.279 13.7673 12.9581 14.0879Z"/>
                                            </svg>


                                        <span id="total_bids" class="paragraph-level-1 d-flex align-items-center gap-1">
                                            <span>@lang('x')</span>
                                            <span title="{{ $product->total_bid }}"
                                                class="text-truncate total-bid-number">{{ $product->total_bid }}</span>
                                            <span>@lang('Bids')</span>
                                        </span>
                                    </div>
                                </div>
                                <div id="bid_area">
                                    @include('templates.basic.product.bid_area', [
                                        'product' => $product,
                                        'event' => $event,
                                        'is_event_ended' => $event->start_status == 'ended',
                                        'new_bidding_value' => $new_bidding_value,
                                    ])
                                </div>
                                <input type="hidden" id="old_bid_status" value="{{ $event->bid_status }}">
                            </div>
                        </div>

                        <h4 class="title" id="live_disable">
                            @lang('The live update mode has been suspended, please') <a href="{{ route('user.login') }}">@lang('login')</a>
                            @lang('to continue watching the live update.')
                        </h4>

                        <!-- <div class="max-banner mb-4">
                                                                @php
                                                                    showAd('780x80');
                                                                @endphp
                                                            </div> -->
                    </div>
                </div>
                <div class="mx-md-auto mx-lg-0 col-md-7 col-lg-4 col-xl-3">
                    <aside class="product-single-sidebar">
                        <div class="countdown-area" id="countdown_area">

                            @if ($product->event->start_status == 'started')
                                @include('templates/basic/product/time_countdown', [
                                    'event' => $product->event,
                                ])
                            @else
                                <div id="start_counter" style="display: none">{{ $event->start_counter }}</div>

                                <ul class="countdown sidebar-countdown">
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

                                {{-- @if ($product->event->EventClockStartOn) --}}
                                {{-- <span> --}}
                                {{-- <i class="las la-clock"></i> --}}
                                {{-- <span>@lang('The countdown starts as soon as the admin starts it')</span> --}}
                                {{-- </span> --}}
                                {{-- @else --}}
                                {{-- <span> --}}
                                {{-- <i class="las la-clock"></i> --}}
                                {{-- <span>@lang('The countdown starts as soon as the all the products have a bid')</span> --}}
                                {{-- </span> --}}
                                {{-- @endif --}}
                            @endif

                        </div>

                        <h6 class="about-seller heading-4 ">
                            @lang('About Farmer')
                        </h6>

                        <div class="seller-area mb-4">
                            @php
                                $admin = $product->admin ? true : false;

                            @endphp
                            <a href="{{ $admin ? route('admin.profile.view', [$product->admin->id, slug(@$general->merchant_profile->name)]) : route('merchant.profile.view', [$product->merchant->id, slug($product->merchant->fullname)]) }}"
                                class="seller d-flex align-items-center position-relative gap-3">
                                <div class="thumb">
                                    @if ($admin)
                                        <img src="{{ getImage(imagePath()['profile']['admin']['path'] . '/' . $general->merchant_profile->image, null, true) }}"
                                            alt="winner">
                                    @else
                                        <img src="{{ getImage(imagePath()['profile']['merchant']['path'] . '/' . $product->merchant->image, null, true) }}"
                                            alt="winner">
                                    @endif
                                </div>
                                <div class="cont">
                                    <p class="title paragraph-level-1 black-color">
                                        {{ $admin ? @$general->merchant_profile->name : $product->merchant->fullname }}</p>
                                </div>
                            </a>
                            <ul class="seller-info d-flex flex-wrap justify-content-between mt-4">
                                <li class="paragraph-level-2 pt-0 pb-0 mb-2">
                                    @lang('Since'):
                                    <span>{{ showDateTime($admin ? $product->admin->created_at : $product->merchant->created_at, 'd M Y') }}</span>
                                </li>

                                @if (!$admin)
                                    <!-- <li>
                                                                                <div class="ratings">
                                                                                    @php
                                                                                        echo displayAvgRating(
                                                                                            $star = $admin
                                                                                                ? $product->admin
                                                                                                    ->avg_rating
                                                                                                : $product->merchant
                                                                                                    ->avg_rating,
                                                                                        );
                                                                                    @endphp
                                                                        ({{ $admin ? $product->admin->review_count : $product->merchant->review_count }})
                                                                            </div>
                                                                        </li> -->
                                @endif
                                <li class="paragraph-level-1 pt-0 pb-0">
                                    @lang('Total Products') : <span
                                        class="text--base">{{ $admin ? $product->admin->products->count() : $product->merchant->products->count() }}</span>
                                </li>

                                <li class="paragraph-level-1 pt-0 pb-0">
                                    @lang('Total Sale') : <span
                                        class="text--base">{{ $admin ? $product->admin->products->sum('total_bid') : $product->merchant->products->sum('total_bid') }}</span>
                                </li>

                            </ul>
                        </div>
                        <div class="mini-banner">
                            @php
                                showAd('370x670');
                            @endphp
                        </div>
                    </aside>
                </div>
            </div>

            <div class="row gy-md-5">
                <div class="mx-md-auto mx-lg-0 col-md-7 col-xl-8 col-xxl-7">
                    <div class="product__single-tabs content paragraph-level-1">
                        <ul class="nav nav-tabs nav--tabs flex-nowrap flex-md-wrap gap-2 gap-sm-5">
                            <li>
                                <a href="#description" class="active" data-bs-toggle="tab">@lang('Description')</a>
                            </li>
                            <li>
                                <a href="#specification" data-bs-toggle="tab">@lang('Specification')</a>
                            </li>
                            <!--  <li>
                                                                            <a href="#reviews" data-bs-toggle="tab">@lang('Reviews')({{ $product->reviews->count() }})</a>
                                                                        </li>-->
                            <li>
                                <a href="#related-products" data-bs-toggle="tab">@lang('Related Products')</a>
                            </li>
                        </ul>
                        <div id="tabs-content" class="tab-content">
                            <div class="tab-pane fade show active" id="description">
                                @php
                                    echo $product->long_description;
                                @endphp
                            </div>

                            <div class="tab-pane fade" id="specification">
                                <div class="specification-wrapper">
                                    <div class="table-wrapper">
                                        <table class="specification-table">
                                            {{-- <tr> --}}
                                            {{-- <th> --}}
                                            {{-- @lang('Category')</td> --}}
                                            {{-- <td>{{ $product->category->name }}</td> --}}
                                            {{-- </tr> --}}
                                            @if ($product->product_specification)
                                                @foreach ($product->product_specification as $spec)
                                                    @if (strtoupper($spec->spec_key) == 'WEIGHT' && $spec->is_display == 1)
                                                        <tr>
                                                            <th>{{ __($spec->spec_key) }}:</th>
                                                            <td id="weight">{{ $spec->Value }}</td>
                                                        </tr>
                                                    @elseif($spec->is_display == 1)
                                                        <tr>
                                                            <th>{{ __($spec->spec_key) }}:</th>
                                                            <td>{{ $spec->Value }}</td>
                                                        </tr>
                                                    @endif
                                                @endforeach
                                            @endif
                                        </table>
                                    </div>
                                </div>
                            </div>

                            <div class="tab-pane fade" id="reviews">
                                <div class="review-area"></div>

                                @if ($product->bids->where('user_id', auth()->id())->count())
                                    @php $review = $product->reviews->where('user_id', auth()->id())->first(); @endphp
                                    <div class="add-review pt-4 pt-sm-5">
                                        <h5 class="title bold mb-3 mb-lg-4">
                                            @if ($review)
                                                @lang('Update Your Review')
                                            @else
                                                @lang('Add Review')
                                            @endif
                                        </h5>
                                        <form action="{{ route('user.product.review.store') }}" method="POST"
                                            class="review-form rating row">
                                            @csrf
                                            <input type="hidden" value="{{ $product->id }}" name="product_id">


                                            <div class="review-form-group mb-20 col-md-6 d-flex flex-wrap">
                                                <label class="review-label mb-0 me-3">@lang('Your Rating') :</label>
                                                <div class="rating-form-group">
                                                    <label class="star-label">
                                                        <input type="radio" name="rating" value="1"
                                                            {{ $review && $review->rating == 1 ? 'checked' : '' }} />
                                                        <span class="icon"><i class="las la-star"></i></span>
                                                    </label>
                                                    <label class="star-label">
                                                        <input type="radio" name="rating" value="2"
                                                            {{ $review && $review->rating == 2 ? 'checked' : '' }} />
                                                        <span class="icon"><i class="las la-star"></i></span>
                                                        <span class="icon"><i class="las la-star"></i></span>
                                                    </label>
                                                    <label class="star-label">
                                                        <input type="radio" name="rating" value="3"
                                                            {{ $review && $review->rating == 3 ? 'checked' : '' }} />
                                                        <span class="icon"><i class="las la-star"></i></span>
                                                        <span class="icon"><i class="las la-star"></i></span>
                                                        <span class="icon"><i class="las la-star"></i></span>
                                                    </label>
                                                    <label class="star-label">
                                                        <input type="radio" name="rating" value="4"
                                                            {{ $review && $review->rating == 4 ? 'checked' : '' }} />
                                                        <span class="icon"><i class="las la-star"></i></span>
                                                        <span class="icon"><i class="las la-star"></i></span>
                                                        <span class="icon"><i class="las la-star"></i></span>
                                                        <span class="icon"><i class="las la-star"></i></span>
                                                    </label>
                                                    <label class="star-label">
                                                        <input type="radio" name="rating" value="5"
                                                            {{ $review && $review->rating == 5 ? 'checked' : '' }} />
                                                        <span class="icon"><i class="las la-star"></i></span>
                                                        <span class="icon"><i class="las la-star"></i></span>
                                                        <span class="icon"><i class="las la-star"></i></span>
                                                        <span class="icon"><i class="las la-star"></i></span>
                                                        <span class="icon"><i class="las la-star"></i></span>
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="review-form-group mb-20 col-12 d-flex flex-wrap">

                                                <textarea name="description" placeholder="@lang('Write your review')..." class="form-control form--control"
                                                    id="review-comments">{{ $review ? $review->description : old('description') }}</textarea>
                                            </div>
                                            <div class="review-form-group mb-20 col-12 d-flex flex-wrap">
                                                <button type="submit" class="cmn--btn w-100">@lang('Submit Review')</button>
                                            </div>
                                        </form>
                                    </div>
                                @endif
                            </div>

                            <div class="tab-pane" id="related-products">
                                <div class="slide-wrapper">
                                    <div class="related-slider owl-theme owl-carousel">
                                        @foreach ($relatedProducts as $relatedProduct)
                                            <div class="slide-item">
                                                <div class="auction__item bg--body">
                                                    <div class="auction__item-thumb">
                                                        <a
                                                            href="{{ route('product.details', [$relatedProduct->id, slug($relatedProduct->name)]) }}">
                                                            <img src="{{ getImage(imagePath()['product']['path'] . '/thumb_' . $relatedProduct->image, imagePath()['product']['thumb']) }}"
                                                                alt="auction">
                                                        </a>
                                                    </div>
                                                    <div class="auction__item-content justify-content-between">
                                                        <div>
                                                            <h6 class="auction__item-title border-0">
                                                                <a title="{{ $relatedProduct->name }}"
                                                                    class="text-truncate"
                                                                    href="{{ route('product.details', [$product->id, slug($product->name)]) }}">{{ $relatedProduct->name }}</a>
                                                                @if (Auth::check())
                                                                    <button
                                                                        onclick="toggleFavoriteStatus({{ $relatedProduct->id }})">
                                                                        @if (!$relatedProduct->is_fav)
                                                                            <i
                                                                                class="favorite-icon-{{ $relatedProduct->id }} far fa-heart heart-icon"></i>
                                                                        @else
                                                                            <i
                                                                                class="favorite-icon-{{ $relatedProduct->id }} far fa-heart heart-icon heart-icon-color"></i>
                                                                        @endif
                                                                    </button>
                                                                @endif
                                                            </h6>
                                                            <!-- <p class="text-truncate paragraph-level-1 m-0 orgianl-auction-title">{{ $relatedProduct->event->name }}</p> -->
                                                            <div class="auction__item-properties auction__item-footer">
                                                                <div class="inner__grp">
                                                                    @if ($relatedProduct->product_specification)
                                                                        @php
                                                                            $j = 0;
                                                                            if (
                                                                                count(
                                                                                    $relatedProduct->product_specification,
                                                                                ) %
                                                                                    2 ==
                                                                                0
                                                                            ) {
                                                                                $i =
                                                                                    count(
                                                                                        $relatedProduct->product_specification,
                                                                                    ) / 2;
                                                                            } else {
                                                                                $i =
                                                                                    (count(
                                                                                        $relatedProduct->product_specification,
                                                                                    ) +
                                                                                        1) /
                                                                                    2;
                                                                            }
                                                                        @endphp
                                                                        @foreach ($relatedProduct->product_specification as $key => $spec)
                                                                            @if ($j == 0)
                                                                                <table class="table_specific">
                                                                            @endif
                                                                            @if ($j == $i)
                                                                                </table>
                                                                </div>
                                                                <div class="inner__grp">
                                                                    <table class="table_specific">
                                        @endif
                                        @if ($spec->is_display == 1 && strtoupper($spec->spec_key) != 'WEIGHT')
                                            <tr>
                                                <th>{{ __($spec->spec_key) }}:
                                                </th>
                                                <td>{{ $spec->Value }}</td>
                                            </tr>
                                        @elseif($spec->is_display == 1)
                                            <tr>
                                                <th>{{ __($spec->spec_key) }}:</th>
                                                <td id='product_{{ $product->id }}_weight'>
                                                    {{ showAmount($spec->Value) }}
                                                    lb
                                                </td>
                                            </tr>
                                        @endif
                                        @php $j++ @endphp
                                        @endforeach
                                        </table>
                                    </div>
                                    @endif
                                </div>
                            </div>
                            <div>
                                <div class="auction__item-countdown">
                                    <div class="inner__grp">
                                        <span class="total-bids d-flex align-items-center gap-1">
<svg width="26" height="26" viewBox="0 0 26 26" fill="#292A2A" fill-opacity="0.75" xmlns="http://www.w3.org/2000/svg">
<path d="M3.83504 26C4.13914 25.9156 4.44324 25.865 4.73045 25.7469C5.35554 25.5107 5.84548 25.1057 6.26785 24.5827C8.64997 21.6637 11.0152 18.7279 13.4142 15.8257C13.9041 15.2183 14.5292 14.729 15.1205 14.1722C15.4584 14.5097 15.8301 14.864 16.1849 15.2352C16.2187 15.2689 16.2187 15.3871 16.1849 15.4545C15.7794 16.6188 16.2356 17.7661 17.3337 18.3229C18.1784 18.7616 19.2259 18.5929 19.9693 17.8505C21.7432 16.0957 23.5171 14.3241 25.291 12.5356C25.9499 11.8607 26.1695 11.0676 25.8654 10.1565C25.5782 9.36349 24.9869 8.8573 24.1422 8.67171C23.7198 8.58734 23.3144 8.62109 22.9258 8.77294C22.7906 8.82356 22.7062 8.80669 22.6048 8.70545C20.8478 6.93381 19.0739 5.17905 17.3168 3.40741C17.2492 3.33992 17.2155 3.20494 17.2324 3.13745C17.6378 2.07447 17.2492 0.960866 16.3369 0.370321C15.4078 -0.220225 14.2758 -0.102116 13.448 0.707775C11.6572 2.46254 9.88326 4.23418 8.12624 6.02269C6.90983 7.2544 7.3322 9.19476 8.90338 9.80218C9.42711 10.0047 9.96773 10.0047 10.4915 9.80218C10.6266 9.75156 10.7111 9.76843 10.8294 9.86967C11.1672 10.2071 11.5051 10.5446 11.8599 10.8989C11.7923 10.9833 11.7248 11.0339 11.6741 11.1014C10.728 12.1137 9.68053 12.9911 8.59928 13.8685C6.20027 15.8089 3.80125 17.7661 1.40224 19.7065C0.658882 20.3139 0.168945 21.0563 0.0337887 22.018C0.0337887 22.0518 0 22.1024 0 22.1361C0 22.3892 0 22.6423 0 22.8954C0.0168953 22.946 0.0337887 23.0135 0.0506821 23.0641C0.253416 24.1946 0.861618 25.0551 1.89218 25.5782C2.26386 25.7638 2.70311 25.8481 3.10858 25.9831C3.32821 26 3.58163 26 3.83504 26ZM17.2661 14.0879C15.4753 12.2993 13.7014 10.5277 11.9444 8.77294C13.3466 7.3725 14.7827 5.93832 16.168 4.55476C17.925 6.30952 19.7158 8.09803 21.4898 9.86967C20.0875 11.2701 18.6684 12.6874 17.2661 14.0879ZM11.8768 15.3027C11.8261 15.3702 11.7754 15.4377 11.7079 15.5052C9.98463 17.6311 8.2445 19.7571 6.52126 21.8831C5.94685 22.5748 5.40623 23.3004 4.79803 23.9415C3.81815 24.9539 1.99355 24.4646 1.60497 23.0979C1.35156 22.1868 1.63876 21.4781 2.34833 20.8876C4.51082 19.1328 6.67331 17.378 8.81891 15.6233C9.44401 15.1171 10.086 14.594 10.6942 14.1047C11.0997 14.5097 11.4713 14.8977 11.8768 15.3027ZM24.4801 10.8483C24.497 11.1689 24.3449 11.3713 24.1591 11.5569C22.419 13.278 20.6957 15.0159 18.9725 16.7369C18.9049 16.8044 18.8542 16.855 18.7866 16.9056C18.3643 17.2262 17.773 17.0237 17.6209 16.5175C17.5027 16.1632 17.6547 15.8932 17.9081 15.657C19.6145 13.936 21.3377 12.1981 23.0778 10.4771C23.1454 10.4096 23.213 10.3421 23.2806 10.2915C23.534 10.1228 23.7874 10.1059 24.0577 10.2409C24.3111 10.3759 24.4463 10.5952 24.4801 10.8483ZM15.847 2.29381C15.8639 2.56378 15.7119 2.74938 15.5429 2.93498C13.8028 4.67287 12.0626 6.41076 10.3394 8.13178C10.2887 8.18239 10.238 8.23301 10.1705 8.28363C9.73121 8.60421 9.12301 8.40174 8.98785 7.86181C8.90338 7.52436 9.03854 7.27127 9.27506 7.05192C10.9814 5.3309 12.7215 3.59301 14.4617 1.85512C14.5292 1.78763 14.5968 1.72014 14.6644 1.66952C14.9178 1.51767 15.1712 1.50079 15.4415 1.63578C15.7119 1.77076 15.8301 1.9901 15.847 2.29381ZM12.9581 14.0879C12.6033 13.7335 12.2485 13.3961 11.9275 13.0755C12.2654 12.738 12.6033 12.3837 12.9243 12.0631C13.2622 12.4006 13.6169 12.7549 13.9548 13.0924C13.6338 13.4129 13.279 13.7673 12.9581 14.0879Z"/>
</svg>

                                            <span>@lang('x'){{ $relatedProduct->total_bid }}
                                                @lang('Bids')</span>
                                        </span>
                                        <div class="total-price paragraph-level-1">
                                            @if ($relatedProduct->bids != '[]')
                                                <?php $amount = 0; ?>
                                                @foreach ($relatedProduct->bids->where('product_id', $relatedProduct->id) as $pp)
                                                    <?php $temp = $pp->amount;
                                                    if ($amount < $temp) {
                                                        $amount = $temp;
                                                    }
                                                    ?>
                                                @endforeach
                                                {{ $general->cur_sym }}{{ showAmount($amount) }}
                                                <?php $amount = 0; ?>
                                            @else
                                                {{ $general->cur_sym }}{{ showAmount($relatedProduct->price) }}
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="auction__item-footer">
                                    <a style=" background-color: {{$heroPrimaryButtonColor}} !important; color: var(--button-contained-color) !important;" href="{{ route('product.details', [$relatedProduct->id, slug($relatedProduct->name)]) }}"
                                        class="cmn--btn">@lang('PRODUCT PAGE')</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        </div>
        </div>
        <!-- <div class="max-banner mt-5">
                                                                    @php
                                                                        showAd('780x80');
                                                                    @endphp
                                                                </div> -->
        </div>
        </div>
        </div>
        </div>
    </section>
    <!-- Product -->
    <div class="modal" id="bidModalLoading">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">@lang('Please Wait')</h5>

                </div>

            </div>
        </div>
    </div>

    <div class="modal fade" id="bidModal">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">@lang('Confirmation Alert')</h5>
                    <button class="text-white modal-close" data-bs-dismiss="modal" aria-label="Close">
                    </button>
                </div>
                <form action="{{ route('user.ajax.bid.store') }}" method="POST" id="bid_form">
                    @csrf
                    <input type="hidden" class="amount" name="amount" required>
                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                    <div class="modal-body">
                        <p class="message"></p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn--danger"
                            data-bs-dismiss="modal">@lang('No')</button>
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
                    <h5 class="modal-title">@lang('Auto Bid Settings')</h5>
                    <button class="btn text--danger modal-close" data-bs-dismiss="modal" aria-label="Close">
                    </button>
                </div>
                <form action="{{ route('user.ajax.bid.store.auto_bid') }}" method="POST" id="autobid_form">
                    @csrf
                    <div class="modal-body">
                        <div class="col-lg-12">
                            <div class="form-group">
                                <label class="paragraph-level-2 mb-2">@lang('Max Bid Per Lb')</label>
                                <div class="position-relative">
                                    <input onFocus="this.select();" type="text" class="form-control" id="max_bid_in_"
                                        autocomplete="off" name="max_value"
                                        @if (Auth::check() && isset($autosettings)) value="{{ $autosettings->max_value }}" @endif
                                        onchange="convert_to_dec(this)" required>
                                    <span class="modal-currency-code">@lang('USD')</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <div class="form-group">
                                <label class="paragraph-level-2 mb-2">@lang('Bidding Step')</label>
                                <input placeholder="@lang('Autobid increment (USD/lb)')" onFocus="this.select();" type="text"
                                    class="form-control" id="bidding_step_" autocomplete="off" name="step"
                                    @if (Auth::check() && isset($autosettings)) value="{{ $autosettings->step }}" @endif
                                    onchange="convert_to_dec(this)" required>
                            </div>
                        </div>
                        <input type="hidden" name="product_id" id="autobidproductid" value="{{ $product->id }}">

                    </div>
                    <div class="modal-footer" style="clear:both;width:100%;display:table;">
                        <p class="no-autobid-settings d-none">@lang('There is no active settings')</p>
                        <span onclick="disableAutoBid({{ $product->id }});" id="disable" style="display: none;"
                            class="btn btn--danger" style="float:left;">@lang('Disable')</span>
                        <button type="submit" class="btn btn--base cmn--btn" style="float:right;  background-color: {{$heroPrimaryButtonColor}} !important; color: var(--button-contained-color) !important;">@lang('Submit')</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="incorrect-bid-value-modal">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">@lang('Warning Alert')</h5>
                    <button class="btn text--danger modal-close" data-bs-dismiss="modal" aria-label="Close">
                    </button>
                </div>
                <div class="modal-body">
                    <h6 class="message">
                        @lang('Please enter an amount to bid')
                    </h6>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn--base" data-bs-dismiss="modal">@lang('OK')</button>
                </div>
            </div>
        </div>
    </div>
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

{{--    @if ($product->event->event_type == 'ace_event')--}}
{{--        <link rel="stylesheet" href="{{ asset($activeTemplateTrue . 'css/ace-theme.min.css') }}">--}}
{{--    @endif--}}
@endpush

@push('script')
    <script src="https://js.pusher.com/7.2/pusher.min.js"></script>

    <script>
        (function($) {
            try {
                $('.countdown').each(function() {
                    var date = $(this).data('date');
                    if (date) {
                        $(this).countdown({
                            date: date,
                            offset: +6,
                            day: 'Day',
                            days: 'Days'
                        });
                    }
                });
                $('.countdown').final_countdown({
                    start: '1362139200',
                    end: '1388461320',
                    now: '1387461319',
                    selectors: {
                        value_seconds: '.clock-seconds .val',
                        canvas_seconds: 'canvas_seconds',
                        value_minutes: '.clock-minutes .val',
                        canvas_minutes: 'canvas_minutes',
                        value_hours: '.clock-hours .val',
                        canvas_hours: 'canvas_hours',
                        value_days: '.clock-days .val',
                        canvas_days: 'canvas_days'
                    },
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
                }, function() {
                    // Finish callback
                });
            } catch (error) {
                Sentry.captureException(error);
            }
        })(jQuery);
    </script>

    <script>
        function disableAutoBid(product_id) {
            var modal = $('#autobidModal');
            modal.modal('hide');
            $(".cup-progress-container").removeClass("d-none");

            var requestData = "product_id=" + product_id + "&max_value=0&step=0&is_disable=1&_token={{ csrf_token() }}";
            var url = "{{ route('user.ajax.bid.store.auto_bid') }}";
            $.ajax(url, {
                    data: requestData,
                    type: "POST",
                    timeout: AJAX_TIMEOUT
                }).done(function(data, textStatus, jqXHR) {
                    try {
                        $(".cup-progress-container").addClass("d-none");
                        iziToast.success({
                            message: "{{ __('Your Auto Bid Settings Disabled Successfully') }}",
                            position: "topRight"
                        });
                    } catch (error) {
                        showFatalErrorOverlay(error);
                    }
                })
                .fail(function(jqXHR, textStatus, errorThrown) {
                    $(".cup-progress-container").addClass("d-none");
                    ajaxErrorHandler(jqXHR, errorThrown);
                })

        }

        (function($) {
            "use strict";

            /*
            // Product's reviews
            var pid = '{{ $product->id }}';
            loadData(pid);
            function loadData(pid, url = "{{ route('product.review.load') }}") {
                var requestData = {
                    pid: pid
                };
                $.ajax(url,{
                    data: requestData,
                    type: "GET",
                    timeout: AJAX_TIMEOUT
                }).done(function (data, textStatus, jqXHR) {
                    try {
                        $('#load_more_button').remove();
                        $('.review-area').append(data);
                    } catch(error) {
                        showFatalErrorOverlay(error);
                    }
                })
                .fail(function (jqXHR, textStatus, errorThrown) {
                    ajaxErrorHandler(jqXHR,errorThrown);
                })
            }

            $(document).on('click', '#load_more_button', function () {
                var id = $(this).data('id');
                var url = $(this).data('url');
                $('#load_more_button').html(`<b>{{ __('Loading') }} <i class="fa fa-spinner fa-spin"></i> </b>`);
                loadData(pid, url);
            });
            */

            function autopopupbidnow() {
                @if (!Auth::check())
                    {
                        return;
                    }
                @endif

                $('.auto_bid_now').off('click');

                $('.auto_bid_now').on('click', function() {
                    var productid = {{ $product->id }};
                    $('#autobidproductid').val(productid);

                    var requestData = {
                        productid: productid,
                        _token: "{{ csrf_token() }}"
                    };
                    var url = "{{ route('user.autobidsetting.show') }}";
                    $.ajax(url, {
                            data: requestData,
                            type: "POST",
                            timeout: AJAX_TIMEOUT
                        }).done(function(data, textStatus, jqXHR) {
                            try {
                                if (data.status == "active") {
                                    var disableAutoBidUrl =
                                        '{{ route('user.autobidsetting.disable', ':productid') }}';
                                    disableAutoBidUrl = disableAutoBidUrl.replace(':productid', productid);
                                    $('.no-autobid-settings').addClass("d-none");
                                    $('#max_bid_in_').val(data.max_value);
                                    $('#bidding_step_').val(data.step);
                                    $('#disable').attr("href", disableAutoBidUrl);
                                    $('#disable').show();
                                } else {
                                    $('.no-autobid-settings').removeClass("d-none");
                                    $('#max_bid_in_').val("");
                                    $('#bidding_step_').val("");
                                    $('#disable').hide();
                                }
                                var modal = $('#autobidModal');
                                modal.modal('show');
                            } catch (error) {
                                showFatalErrorOverlay(error);
                            }
                        })
                        .fail(function(jqXHR, textStatus, errorThrown) {
                            ajaxErrorHandler(jqXHR, errorThrown);
                        })

                });
            }


            $("#autobid_form").submit(function(e) {
                e.preventDefault();
                var form = $(this);
                var modal = $('#autobidModal');
                modal.modal('hide');
                $(".cup-progress-container").removeClass("d-none");

                var requestData = form.serialize();
                var url = form.attr('action');
                $.ajax(url, {
                        data: requestData,
                        type: "POST",
                        timeout: AJAX_TIMEOUT
                    }).done(function(data, textStatus, jqXHR) {
                        try {
                            $(".cup-progress-container").addClass("d-none");
                            iziToast.success({
                                message: "{{ __('Your AutoBid Settings Added Successfully') }}",
                                position: "topRight"
                            });
                        } catch (error) {
                            showFatalErrorOverlay(error);
                        }
                    })
                    .fail(function(jqXHR, textStatus, errorThrown) {
                        $(".cup-progress-container").addClass("d-none");
                        ajaxErrorHandler(jqXHR, errorThrown);
                    })

            });

            autopopupbidnow();

            function bid_now() {
                $('.bid_now').on('click', function() {
                    try {
                        var bid_now_html = $('.bid_now').html();
                        var modal = $('#bidModal');
                        var cur_sym = $(this).data('cur_sym');
                        var am = $('#amount').val();

                        if (!am || am == 0) {
                            var incorrectBidValueModal = $('#incorrect-bid-value-modal');
                            incorrectBidValueModal.modal('show');
                            return;
                        } else {
                            var am = (parseFloat($('#amount').val())).toFixed(2);
                            // modal.find('.message').html('@lang('Are you sure to bid ')'+amount+'@lang(' on this product')');
                            var total_price = (parseFloat($('#weight').text()) * am).toFixed(2);
                            // var formatter = new Intl.NumberFormat('en-US', {
                            //     minimumFractionDigits: 2,
                            //     maximumFractionDigits: 2
                            // });
                            // total_price = formatter.format(total_price);
                            // amount = formatter.format(am);
                            modal.find('.message').html('@lang('Are you sure you want to place a bid of ')' + '<span class="msg_number">' +
                                'US$' + am + '/Lb' + '</span>' + '@lang('. for a total bid of') ' +
                                '<span class="msg_number">' + 'US$' + total_price + '</span>');

                            modal.find('.amount').val(am);
                            modal.modal('show');
                        }
                    } catch (err) {
                        showFatalErrorOverlay(err)
                    }
                });
            }

            $("#bid_form").submit(function(e) {
                e.preventDefault();
                var form = $(this);
                var modal = $('#bidModal');
                modal.modal('hide');
                $(".cup-progress-container").removeClass("d-none");

                var requestData = form.serialize();
                var url = form.attr('action');
                $.ajax(url, {
                        data: requestData,
                        type: "POST",
                        timeout: AJAX_TIMEOUT
                    }).done(function(data, textStatus, jqXHR) {
                        try {
                            $(".cup-progress-container").addClass("d-none");
                            iziToast.success({
                                message: "@lang('Your Bid Added Successfully')",
                                position: "topRight"
                            });
                        } catch (error) {
                            showFatalErrorOverlay(error);
                        }
                    })
                    .fail(function(jqXHR, textStatus, errorThrown) {
                        $(".cup-progress-container").addClass("d-none");
                        ajaxErrorHandler(jqXHR, errorThrown);
                    })

            });

            bid_now();

            @if (!stop_live_in_not_auth_users() || \Illuminate\Support\Facades\Auth::check())

                $('#live_disable').attr('hidden', true);

                @if (config('app.env') == 'debug')
                    Pusher.logToConsole = true;
                @endif

                try {
                    var pusher = new Pusher('{{ $codes['PUSHER_APP_KEY'] }}', {
                        cluster: '{{ $codes['PUSHER_APP_CLUSTER'] }}'
                    });

                    {{-- var channel = pusher.subscribe('event_.{{$event->id}}'); --}}
                    {{-- channel.bind('event_.{{$event->id}}', function(data) { --}}
                    {{--    alert(JSON.stringify(data)); --}}
                    {{-- });  --}}

                    var channel = pusher.subscribe('product.{{ $product->id }}');
                    channel.bind('product.{{ $product->id }}', function(data) {
                        refreshProductData(JSON.parse(data.data));
                    });

                    var channel_event = pusher.subscribe('event.{{ $event->id }}');
                    channel_event.bind('event.{{ $event->id }}', function(data) {
                        refreshDataeventAjax(JSON.parse(data.data));
                    });

                    var channel_product_auto_bid_{{ $product->id }} = pusher.subscribe(
                        'autobidstart.{{ $product->id }}');
                    channel_product_auto_bid_{{ $product->id }}.bind('autobidstart.{{ $product->id }}', function(
                        data) {
                        $(".cup-progress-container").removeClass("d-none");
                    });

                    var channel_product_auto_bid_end_{{ $product->id }} = pusher.subscribe(
                        'autobidend.{{ $product->id }}');
                    channel_product_auto_bid_end_{{ $product->id }}.bind('autobidend.{{ $product->id }}',
                        function(data) {
                            $(".cup-progress-container").addClass("d-none");
                        });
                } catch (err) {
                    showFatalErrorOverlay(err)
                }
            @endif

            function refreshProductData(data) {
                try {
                    var response = data;
                    if (response != null) {
                        var old_bid_status = $('#old_bid_status').val();
                        var new_bid_view = data.bid_view_product;
                        if (old_bid_status !== data.bid_status) {
                            $('#bid_area').html(new_bid_view);
                            $('#old_bid_status').val(data.bid_status);
                            bid_now();
                        }

                        if (response.max_bid != null) {
                            var max_bidder = "";
                            var max_bid_user_id = response.last_bid_userid;

                            if (max_bid_user_id == {{ Auth::check() == true ? Auth::user()->id : -1 }}) {
                                max_bidder = '<span class="text--success">@lang('You')</span>';
                            } else {
                                max_bidder = '<span class="text--danger">' + '@lang('Anonymous')' + '</span>';
                            }

                            $('#bid_price').html("US$" + response.amount + "/lb");
                            $('#amount').val(response.new_bidding_value);
                            $('#highestbidder_name').html(max_bidder);

                            $('#total_bids').html(response.bid_count);
                        }

                        var event_start_type = data.event_start_type;
                        var event_view = data.event_view;
                        var start_counter = data.start_counter;
                        var is_the_time_ended = data.is_the_time_ended;

                        if (event_start_type === "started") {
                            var old = $('#start_counter').html();
                            if (parseInt(old) < start_counter) {
                                $('#countdown_area').html(event_view);
                                initializeCounter();
                            }
                        }
                        autopopupbidnow();
                        checkBidLogin();
                    }
                } catch (err) {
                    showFatalErrorOverlay(err);
                }
            }

            function refreshDataeventAjax(data) {
                try {
                    var event_start_type = data.event_start_type;
                    var event_view = data.event_view;
                    var start_counter = data.start_counter;

                    if (event_start_type === "started") {
                        var old = $('#start_counter').html();
                        if (parseInt(old) < start_counter) {
                            $('#countdown_area').html(event_view);
                            initializeCounter();
                        }
                    }
                } catch (err) {
                    showFatalErrorOverlay(err);
                }
            }

            function checkBidLogin() {
                if ({{ \Illuminate\Support\Facades\Auth::check() == 1 ? 1 : 0 }} === 1) {
                    $("#bid_open_user_logged_out").attr("hidden", true);
                } else {
                    $("#bid_open_user_logged_in").attr("hidden", true);
                }
            }
            checkBidLogin();

            function initializeCounter() {
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
            try {
                var numb = selectObject.value;
                var number_pars = parseFloat(numb) || 0;
                var number_converted = number_pars.toFixed(2);
                selectObject.value = number_converted;
            } catch (err) {
                showFatalErrorOverlay(err);
            }
        }

        function toggleFavoriteStatus(product_id) {
            var requestData = {
                _token: "{{ csrf_token() }}",
                product_id: product_id
            };
            var url = "{{ route('user.fav.toggle') }}";
            $.ajax(url, {
                    data: requestData,
                    type: "POST",
                    timeout: AJAX_TIMEOUT
                }).done(function(data, textStatus, jqXHR) {
                    try {
                        const favIconElements = document.getElementsByClassName("favorite-icon-" + product_id)
                        for (let i = 0; i < favIconElements.length; i++) {
                            if (data.is_add) {
                                favIconElements.item(i).classList.add("heart-icon-color");
                            } else {
                                favIconElements.item(i).classList.remove("heart-icon-color");
                            }
                        }
                    } catch (error) {
                        showFatalErrorOverlay(error);
                    }
                })
                .fail(function(jqXHR, textStatus, errorThrown) {
                    ajaxErrorHandler(jqXHR, errorThrown);
                })

        }
    </script>
    <script>
        window.addEventListener("pageshow", function(event) {
            var historyTraversal = event.persisted ||
                (typeof window.performance != "undefined" &&
                    window.performance.navigation.type === 2);
            if (historyTraversal) {
                window.location.reload();
            }
        });

        $(document).ready(function() {
            try {
                $('.owl-carousel').owlCarousel({
                    loop: false,
                    dots: true,
                    checkVisible: false,
                    items: 1,
                    autoplay: true,
                    margin: 0,
                    responsive: {
                        576: {
                            items: 1,
                        },
                        1200: {
                            items: 2,
                        }
                    }
                })
            } catch (err) {
                Sentry.captureException(err);
            }
        });
        (function($) {
            var userRequests = {!! $userRequests ?? 'null' !!};

            const largeImageUrl =
                "{{ getImage(imagePath()['product']['path'] . '/' . $product->image, imagePath()['product']['size'], false) }}"
            const mediumImageUrl =
                "{{ getImage(imagePath()['product']['path'] . '/' . $product->image, imagePath()['product']['size'], false, 'md') }}"
            const smallImageUrl =
                "{{ getImage(imagePath()['product']['path'] . '/' . $product->image, imagePath()['product']['size'], false, 'sm') }}";




            setResponsiveImage(largeImageUrl, mediumImageUrl, smallImageUrl, document.getElementById('product-image'),
                true);


            // Replace $event->id with the actual ID you want to check
            var currentEventId = {!! $event ?? 'null' !!};

            // Check if the currentEventId is in the userRequests array
            if (userRequests && userRequests.includes(currentEventId.id)) {
                $('#eventModalContainer').html(`
        <div style="opacity: 0.5;" class="modal-backdrop"></div>
            <div style="top: 30vh;" class="modal d-block ">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">@lang('Access Granted!')</h5>
                            <button onclick="$('#eventModalContainer').html('')" class="btn text--danger modal-close" data-bs-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true"></span>
                            </button>
                        </div>
                        <div style='color: #242828DE;' class="modal-body">
                            <h6 class="message">
                            {!! __("Your access to ' :event ' auction is approved!", ['event' => $event->name]) !!}
                            </h6>
                            <p class="my-4">
                                {!! __(
                                    'Before you start bidding, please review and accept our <a style="color: var(--primary-color)  !important; text-decoration: underline !important;"  target="_blank;" href="../../agreement/:EventId"> Bidder Agreement </a> and <a style="color: var(--primary-color) !important; text-decoration: underline !important;"  target="_blank" href="../../page/:Policy/terms-and-conditions">Terms and Conditions</a>',
                                    [
                                        'EventId' => $event->id,
                                        'Policy' => $policy_id,
                                        'TermsAndConditions' =>
                                            '<a style="color: var(--primary-color) !important; text-decoration: underline !important;"  target="_blank" href="../../page/' .
                                            $policy_id .
                                            '/terms-and-conditions">' .
                                            __('Terms and Conditions') .
                                            '</a>',
                                        'BidderAgreement' =>
                                            '<a style="color: var(--primary-color)  !important; text-decoration: underline !important; text-decoration: underline !important;"  target="_blank" href="../../agreement/:EventId">' .
                                            __('Bidder Agreement') .
                                            '</a>',
                                    ],
                                ) !!}

                            </p>
                        </div>
                        <div class="modal-footer gap-2">
                            <a class="text--btn" style="user-select:none;cursor: pointer;" onclick="$('#eventModalContainer').html('')"  data-bs-dismiss="modal">@lang('Decline')</a>
                            <a style="user-select:none;cursor: pointer;" onclick="acceptEventTerms(${currentEventId.id})"><button style="background-color: var(--button-contained-background) !important" class="btn btn--base cmn--btn">@lang('Accept')</button></a>
                        </div>
                    </div
                </div>
            </div>
        `);
            }
        })(jQuery);
    </script>
@endpush
