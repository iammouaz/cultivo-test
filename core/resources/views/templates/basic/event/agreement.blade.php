@extends($activeTemplate.'layouts.frontend')
@section('content')
<section class="pt-120 pb-120">
	<div class="container">
        @php
            echo $agreement
        @endphp
    </div>
</section>
@endsection