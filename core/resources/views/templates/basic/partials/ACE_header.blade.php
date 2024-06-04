{{--todo check the need for this view--}}

@php
    $header = getContent('header.content', true);
    $user_id = false;
    if(Auth::user()) {
        $user_id = Auth::user()->id;
    }
@endphp
@php
    $category = getContent('categories.content', true);
    $categories = \App\Models\Category::where('status', 1)->orderBy('position','ASC')->limit(10)->get();
@endphp
@php
    if(auth()->check()){

        $userRequests = json_encode(
            auth()
                ->user()
                ->userRequestsPendingApprovalArray() ?? null,
        );
    }
    else{
        $userRequests = null;
    }
    $codes=get_extention_keys('pusher');
@endphp
    <!-- Header -->

<div class="ace-header {{$icons_theme}} position-absolute top-0 w-100 pt-3">
    <div class="container">
        <div class="d-flex justify-content-end">
                <ul class="header-icons d-flex gap-3">
                        @auth
                        <li class="help-icon">
                            <a href="https://mcultivo.tawk.help/" target="_blank" data-bs-toggle="tooltip" data-bs-placement="bottom" title="@lang("Help Center")">
                                @include("templates.basic.svgIcons.question_mark")
                            </a>
                        </li>

                        <li class="user-icon" data-bs-toggle="tooltip" data-bs-placement="bottom" title="@lang("User Dashboard")">
                            <a href="{{ route('user.home') }}">
                            @include("templates.basic.svgIcons.user")
                            </a>
                        </li>

                        @if(!get_is_stop_cart() ?? false)
                            <li class="cart-icon" data-bs-toggle="tooltip" data-bs-placement="right" title="@lang("Cart")">
                                @include("templates.basic.svgIcons.cart")

                                <div class="dropdown-content">
                                    @forelse(\App\Http\Controllers\CartController::getEvents() as $event)
                                        <a class="dropdown-item"
                                           href="{{ route('user.checkout.index',['event_id'=>$event->id]) }}">{{ $event->name }}</a>
                                    @empty
                                        <a class="dropdown-item"
                                        >@lang("Cart Empty")</a>
                                    @endforelse
                                </div>
                            </li>
                        @endif
                    @endauth

                    @auth('merchant')
                        <a href="{{ route('merchant.dashboard') }}">@lang('Merchant Dashboard')</a>
                    @endauth

                    @if (!auth()->check() && !auth()->guard('merchant')->check())
                        <li>
{{--                            ace event login --}}
                            <a class="login-link d-none d-lg-block btn" href="{{ route('user.login',['ace_member'=>'ace_member']) }}" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Log in">
                            @include("templates.basic.svgIcons.user")
                            </a>
                        </li>
                        <!--<a href="{{ route('merchant.login') }}">@lang('Merchant Login')</a>-->
                    @endif

                </ul>
                <select class="language langSel" style="display: none">
                    @foreach($language as $item)
                        <option value="{{$item->code}}"
                                @if(session('lang')==$item->code) selected @endif>{{ $item->name }}</option>
                    @endforeach
                </select>
        </div>
    </div>
</div>
<div id="eventModalContainer"></div>
<!-- Header -->

@push('script')
    <script>
       @if($user_id)
            const userIdCookie = getCookie("user_id");
            if(!userIdCookie) {
                setCookie("user_id",{{$user_id}},365);
            } else if(userIdCookie && +userIdCookie != {{$user_id}}) {
                setCookie("user_id",{{$user_id}},365);
                deleteCookie("weight_unit");
                deleteCookie("currency");
            }
        @endif

        function acceptEventTerms(event_id) {
            $.ajax({
                    url: '{{ url("user/terms-accept") }}/' + event_id,
                    type: 'POST',
                    data: {
                        _token: "{{csrf_token()}}",
                        event_id: event_id,//not used, added any data to prevent csrf token error
                    },
                    success: function(response) {
                        setTimeout(() => {
                            window.location.reload()
                        }, 500);
                    },
                    error: function(error) {
                        console.error('Error in AJAX request', error);

                    }
                });
        }

        function innerModal(data) {
            if ($('#eventModalContainer .modal').length === 0) {
                                $('#eventModalContainer').html(`
                                <div style="opacity: 0.5;" class="modal-backdrop"></div>
            <div style="top: 30vh;" class="modal d-block ">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">@lang("Access Granted!")</h5>
                            <button onclick="$('#eventModalContainer').html('')" class="btn text--danger modal-close" data-bs-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true"></span>
                            </button>
                        </div>
                        <div style='color: #242828DE;' class="modal-body">
                            <h6 class="message">
                            @lang("Your access to") "${data.data.event_name}" @lang('auction is approved!')
                                </h6>
                                <p class="my-4">
                            @lang('Before you start bidding, please review and accept our') <a style="color: var(--secondary-color) !important;  text-decoration: underline !important;"  target="_blank" href="../../agreement/${data.data.event_id}">@lang('Bidder Agreement')</a> @lang('and') <a style="color: var(--secondary-color) !important; text-decoration: underline !important;"  target="_blank" href="../../page/{{get_policy_id()}}/terms-and-conditions">@lang('Terms and Conditions')</a>
                        </p>

                            </div>
                            <div class="modal-footer gap-2">
                                <a class="text--btn" style="user-select:none;cursor: pointer;" onclick="$('#eventModalContainer').html('')"  data-bs-dismiss="modal">@lang("Decline")</a>
                            <a style="user-select:none;cursor: pointer;" onclick="acceptEventTerms(${data.data.event_id})"><button  style="background-color: var(--button-contained-background) !important" class="btn btn--base cmn--btn">@lang("Accept")</button></a>
                        </div>
                    </div>
                </div>
            </div>
    `);
                                }
        }


        (function($) {
            var userRequests = {!! $userRequests ?? 'null' !!};

            var product_event_id = {{$product->event_id??0}};


            if (userRequests && userRequests.length > 0) {
                userRequests.forEach(function(eventID) {
                    try {
                        var pusher = new Pusher('{{ $codes['PUSHER_APP_KEY']  }}', {
                            cluster: '{{ $codes['PUSHER_APP_CLUSTER']  }}'
                        });

                        var userId = '{{ auth()->user()->id ?? null }}';
                        if (userId) {
                            var userChannel = pusher.subscribe(`request.${eventID}.user.` + userId);
                            userChannel.bind(`request.${eventID}.user.` + userId, function(data) {
                                var currentUrl = window.location.href;
                                var parts = currentUrl.split('/');
                                var subpage = parts[parts.length - 3];
                                var id = parts[parts.length - 2];


                                if (id && (subpage == 'event-details' || subpage == 'product-details')) {
                                    if(id == data.data.event_id || product_event_id == data.data.event_id) {
                                        innerModal(data)
                                    }
                                } else {
                                    innerModal(data)
                                }

                            });

                        }
                    } catch (error) {
                        showFatalErrorOverlay(error);
                    }
                });
            }
        })(jQuery);
    </script>


@endpush
