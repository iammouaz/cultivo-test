<div id="start_counter" style="display: none">{{$event->start_counter}}</div>

<ul class="countdown sidebar-countdown"
    data-date="{{ showDateTime($event->end_date, 'm/d/Y H:i:s') }}"
    data-start="{{ showDateTime(now(), 'm/d/Y H:i:s') }}"
>
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

