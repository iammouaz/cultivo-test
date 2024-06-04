@extends($activeTemplate.'layouts.master')

@section('content')
<div class="container-fluid">
    <div>

        <table class="table cmn--table">
            <thead>
                <tr>
                    <th scope="col">@lang('S.N.')</th>
                    <th scope="col">@lang('User Name')</th>
                    <th scope="col">@lang('Set Lot')</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($users as $user)

                <tr>
                    <td data-label="@lang('S.N')">{{ $users->firstItem() + $loop->index }}</td>
                    <td data-label="@lang('User Name')">{{ $user->fullname}}</td>

                    <td data-label="@lang('Set Lot')">
                        <form id="form{{$user->id}}" method="POST" action="{{route('user.lot.store')}}">
                            @csrf
                            <input type="hidden" name="user_id" value="{{$user->id}}">
                            <input type="hidden" name="event_id" value="{{$event->id}}">
                            <input type="hidden" name="product_id" value="{{$product->id}}">
                            <input type="hidden" name="group_id" value="{{$group_id}}">
                            <input type="number" placeholder="@lang('Lot')" name="lot" id min="1"/>
                            <button onclick="submitform('form{{$user->id}}');" class="btn btn-success btn-md mr-1">Set Lot</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td class="text-muted text-center" colspan="100%">{{ $emptyMessage }}</td>
                </tr>
                @endforelse
            </tbody>
        </table>

        {{ $users->links() }}
    </div>
</div>
@endsection
