<div class="event-card mx-auto mx-md-0 col-sm-9 col-md-6 col-xl-4">
    <div class="auction__item">
        <div class="auction__item-thumb">
            <a href="{{ route('event.details', [$event->id, slug($event->name)]) }}">
                <img src="{{getImage(imagePath()['event']['path'].'/thumb_'.$event->image,imagePath()['event']['thumb'])}}"
                    alt="event">
            </a>
        </div>
        <div class="auction__item-content d-flex flex-column">
            <div class="flex-grow-1">
                <h6 class="auction__item-title">
                    <a href="{{ route('event.details', [$event->id, slug($event->name)]) }}">
                        <span>{{ $event->name }}</span>
                        @if($event->practice)
                        <span class="practice-label">@lang('Practice')</span>
                        @endif
                    </a>
                </h6>
                <div class="auction__item-locationAndDate">
                    {{$event->sname}}
                </div>
                <div class="auction__item-countdown">
                    {{ $event->description }}
                </div>
            </div>

            <div class="auction__item-footer">
                <div class="btn__area">
                    <div class="div-bid-now">
                        @if(in_array($event->id,$eventid))
                        <a class="cmn--btn enter-event-btn"
                            href="{{ route('event.details', [$event->id, slug($event->name)]) }}">
                            @lang('Enter the event')
                        </a>
                        @else
                        <a class="cmn--btn"
                            href="{{ route('event.details', [$event->id, slug($event->name)]) }}">
                            @lang('View Coffees')
                        </a>
                        @endif
                    </div>

                    @if(Auth::check() && !in_array($event->id,$eventid))
                        <div class="div-bid-now">
                            <a class="cmn--btn" onclick="showAccessRequestModal({{$event->id}})">
                                @lang('Request Bid Access')
                            </a>
                        </div>
                    @elseif (!Auth::check() )
{{--                        ace event login --}}
{{--                        @if ($event->event_type == 'ace_event')--}}
{{--                        <div class="div-bid-now">--}}
{{--                            <a class="cmn--btn"--}}
{{--                                href="{{route('user.login',['ace_member'=>'ace_member'])}}">@lang('Request--}}
{{--                                Bid Access')</a>--}}
{{--                        </div>--}}
{{--                        @else--}}
                        <div class="div-bid-now">
                            <a class="cmn--btn" href="{{route('user.login',['login_type'=>$event->login_type??'normal'])}}">@lang('Request Bid
                                Access')</a>
                        </div>
{{--                        @endif--}}
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
