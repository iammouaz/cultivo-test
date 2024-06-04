@extends($activeTemplate.'layouts.master')

@section('content')
    <div class="container-fluid">
        <div>
            <table class="table cmn--table">
                <thead>
                    <tr>
                        <th scope="col">@lang('S.N.')</th>
                        <th scope="col">@lang('Group Name')</th>
                        <th scope="col">@lang('Event Name')</th>
                        <th scope="col">@lang('Status')</th>
                        <th scope="col">@lang('Invitation Type')</th>
                        <th scope="col">@lang('Action')</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($invitations as $invitation)
                        <tr>
                            <td data-label="@lang('S.N')">{{ $invitations->firstItem() + $loop->index }}</td>
                            <td data-label="@lang('Group Name')">{{ $invitation->group->name}}</td>
                            <td data-label="@lang('Event Name')">{{ $invitation->group->event->name.' '.$invitation->group->event->sname}}</td>
                            <td data-label="@lang('Status')">
                                @if($invitation->status == 0)
                                <span class="text--small badge font-weight-normal badge--warning">@lang('Reject')</span>
                                @elseif($invitation->status == 1)
                                <span class="text--small badge font-weight-normal badge--success">@lang('Approve')</span>
                                @elseif($invitation->status == -1)
                                <span class="text--small badge font-weight-normal badge--primary">@lang('Pending')</span>
                                @endif
                            </td>
                            <td data-label="@lang('invitation_type')">
                                @if($invitation->invitation_type == 0)
                                <span class="text--small badge font-weight-normal badge--warning">@lang('Membership')</span>
                                @elseif($invitation->invitation_type == 1)
                                <span class="text--small badge font-weight-normal badge--success">@lang('Leadership')</span>
                                @endif
                            </td>
                            <td data-label="@lang('Action')">
                                @if($invitation->status==-1)
                                <a href="{{route('user.invitation.approve',[$invitation->id])}}"
                                    class="btn cmn--btn btn--sm">@lang('Approve')
                                </a>
                                <a href="{{route('user.invitation.reject',[$invitation->id])}}"
                                    class="btn cmn--btn btn--sm">@lang('Reject')
                                </a>
                                @endif
                                @if($invitation->group->leader_id==auth()->user()->id)
                                <a href="{{route('user.Group.group_users',[$invitation->group->id])}}"
                                    class="btn cmn--btn btn--sm">@lang('Show Members')
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
            {{ $invitations->links() }}
        </div>
    </div>
@endsection
