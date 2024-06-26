@extends($activeTemplate.'layouts.master')

@section('content')
    <div class="container-fluid">
        <div>
            <table class="table cmn--table">
                <thead>
                    <tr>
                        <th scope="col">@lang('S.N.')</th>
                        <th scope="col">@lang('Product Name')</th>
                        <th scope="col">@lang('Product Price')</th>
                        <th scope="col">@lang('Bid Amount')</th>
                        <th scope="col">@lang('Bid Time')</th>
                        <th scope="col">@lang('Action')</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($winningHistories as $winner)     
                        <tr>
                            <td data-label="@lang('S.N')">{{ $winningHistories->firstItem() + $loop->index }}</td>
                            <td data-label="@lang('Product Name')">{{ $winner->product->name }}</td>
                            <td data-label="@lang('Product Price')">{{ $general->cur_sym }}{{ showAmount($winner->product->price) }}</td>
                            <td data-label="@lang('Bid Amount')">{{ $general->cur_sym }}{{ showAmount($winner->bid->amount) }}</td>
                            <td data-label="@lang('Bid Time')">{{ showDateTime($winner->bid->created_at) }} <br> {{ diffForHumans($winner->bid->created_at) }}</td>
                            <td data-label="@lang('Action')">
                                <a href="{{ route('product.details', [$winner->product->id, slug($winner->product->name)]) }}" target="__blank" class="btn cmn--btn btn--sm">@lang('View')</a>
                                @if($group_id=user_in_group($winner->product->event,auth()->user()->id))
                                @if(find_group_leader($group_id)==auth()->user()->id)
                                <a href="{{ route('user.product.show_users', [$winner->product->id]) }}"  class="btn cmn--btn btn--sm">@lang('Show Members')</a>
                                @endif
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td class="text-muted text-center" colspan="100%">{{ $emptyMessage }}</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            {{ $winningHistories->links() }}
        </div>
    </div>
@endsection