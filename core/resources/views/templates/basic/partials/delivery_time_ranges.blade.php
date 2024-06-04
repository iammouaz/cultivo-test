@php
$delivery_time_ranges??=json_encode([]);
$delivery_time_ranges = json_decode($delivery_time_ranges,true);
//dd($delivery_time_ranges);
//$collect = collect();
$array = [];
foreach ($delivery_time_ranges as $key=>$delivery_time_range) {
    $time=$delivery_time_range;
//    dd($time);
    //split by -
    $time = explode(' - ', $time);
    //change the date to hh:mm
    $start =  Carbon\Carbon::parse($time[0])->format('H:i');
    $end =  Carbon\Carbon::parse($time[1])->format('H:i');
    $array[] = ['day' => $key, 'start' => $start, 'end' => $end];
//    $collect->push(collect($array));
}
@endphp

{{--@section('scripts')--}}
{{--    <script>--}}
{{--        @if(config('app.env')=='local')--}}
{{--            console.log('{{dd($delivery_time_ranges, $array)}}');--}}
{{--        @endif--}}
{{--    </script>--}}
{{--@endsection--}}
{{--<ul class="list-group list-group-flush">--}}
    @foreach($array as $delivery_time_range)
{{--        <li class="list-group-item">--}}
            <div >
                <span >{{ucfirst($delivery_time_range['day'])}}</span>:&nbsp;
                <span>{{$delivery_time_range['start']}} - {{$delivery_time_range['end']}}</span>
            </div>
{{--        </li>--}}
    @endforeach
{{--</ul>--}}
