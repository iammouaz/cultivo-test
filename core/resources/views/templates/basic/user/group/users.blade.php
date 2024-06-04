@extends($activeTemplate.'layouts.master')

@section('content')
    <div class="container-fluid">
        <div>
        
            <table class="table cmn--table">
                <thead>
                    <tr>
                        <th scope="col">@lang('S.N.')</th>
                        <th scope="col">@lang('User Name')</th>
                        <th scope="col">@lang('Status')</th>
                        <th scope="col">@lang('Make Leader')</th>
                        
                    </tr>
                </thead>
                <tbody>
                    @forelse ($users as $user)
                    
                        <tr>
                            <td data-label="@lang('S.N')">{{ $users->firstItem() + $loop->index }}</td>
                            <td data-label="@lang('User Name')">{{ $user->fullname}}</td>
                            <td data-label="@lang('Status')">
                                @if($user->pivot->status == 0)
                                <span class="text--small badge font-weight-normal badge--warning">@lang('Reject')</span>
                                @elseif($user->pivot->status == 1)
                                <span class="text--small badge font-weight-normal badge--success">@lang('Approve')</span>
                                @elseif($user->pivot->status == -1)
                                <span class="text--small badge font-weight-normal badge--primary">@lang('Pending')</span>
                                @endif
                            </td>
                            <td data-label="@lang('Make Leader')">
                                @if($user->pivot->status==1 && $user->id!=auth()->user()->id)
                                <a href="{{route('user.Group.leader',[$id,$user->id])}}" class="icon-btn btn--success mr-1" data-toggle="tooltip" data-original-title="@lang('Make Leader')">
                                    <i class="las la-user text--shadow"></i>
                                </a>
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
         
            {{ $users->links() }}
        </div>
    </div>
@endsection
