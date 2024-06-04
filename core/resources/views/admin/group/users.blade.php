@extends('admin.layouts.app')
@section('panel')
<div class="row">
    <div class="col-lg-12">
        <div class="card b-radius--10 ">
            <div class="card-body p-0">
                <div class="table-responsive--md  table-responsive">
                    <table class="table table--light style--two">
                        <thead>
                        <tr>
                            <th>@lang('S.N.')</th>
                            <th>@lang('User Name')</th>
                            <th>@lang('Status')</th>
                            <th>@lang('Invitation Type')</th>
                            <th>@lang('Action')</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($users as $user)
                        <tr>
                            <td data-label="@lang('S.N')">{{ $users->firstItem() + $loop->index }}</td>
                            <td data-label="@lang('User Name')">{{ $user->fullname }}</td>
                            <td data-label="@lang('Status')">
                                @if($user->pivot->status == 0)
                                <span class="text--small badge font-weight-normal badge--warning">@lang('Reject')</span>
                                @elseif($user->pivot->status == 1)
                                <span class="text--small badge font-weight-normal badge--success">@lang('Approve')</span>
                                @elseif($user->pivot->status == -1)
                                <span class="text--small badge font-weight-normal badge--primary">@lang('Pending')</span>
                                @endif
                            </td>
                            <td data-label="@lang('invitation_type')">
                                @if($user->pivot->invitation_type == 0)
                                <span class="text--small badge font-weight-normal badge--warning">@lang('Membership')</span>
                                @elseif($user->pivot->invitation_type == 1)
                                <span class="text--small badge font-weight-normal badge--success">@lang('Leadership')</span>
                                @endif
                            </td>
                            <td data-label="@lang('Make Leader')">
                                @if($user->pivot->status==1)
                                <a href="{{route('admin.Group.leader',[$group_id,$user->id])}}" class="icon-btn btn--success mr-1" data-toggle="tooltip" data-original-title="@lang('Make Leader')">
                                    <i class="las la-user text--shadow"></i>
                                </a>
                                @endif
                            </td>
                        </tr>
                        @empty
                            <tr>
                                <td class="text-muted text-center" colspan="100%">{{ $emptyMessage}}</td>
                            </tr>
                        @endforelse

                        </tbody>
                    </table><!-- table end -->
                </div>
            </div>
            @if ($users->hasPages())
                <div class="card-footer py-4">
                    {{ paginateLinks($users) }}
                </div>
            @endif
        </div>
    </div>
</div>


{{-- APPROVE MODAL --}}
<!-- <div id="approveModal" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">@lang('Approve Confirmation')</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{route('admin.product.approve')}}" method="POST">
                @csrf
                <input type="hidden" name="id">
                <div class="modal-body">
                    <p>@lang('Are you sure to') <span class="font-weight-bold">@lang('approve')</span> <span class="font-weight-bold withdraw-amount text-success"></span> @lang('this product') <span class="font-weight-bold withdraw-user"></span>?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn--dark" data-dismiss="modal">@lang('No')</button>
                    <button type="submit" class="btn btn--primary">@lang('Yes')</button>
                </div>
            </form>
        </div>
    </div>
</div> -->

@endsection

@push('breadcrumb-plugins')
    <a href="{{ route('admin.group.index',[$event->id]) }}" class="btn btn-sm btn--primary box--shadow1 text--small"><i class="la la-fw la-backward"></i> @lang('Go Back') </a>
@endpush

@push('style')
    <style>
        .btn {
            display: inline-flex;
            justify-content: center;
            align-items: center
        }
        .header-search-wrapper {
            gap: 15px
        }
        
       
        @media (max-width:400px) {
            .header-search-form {
                width: 100%
            }
        }
    </style>
@endpush

@push('script')
    <script>
        (function ($) {
            "use strict";

            $('.approveBtn').on('click', function () {
                var modal = $('#approveModal');
                modal.find('input[name=id]').val($(this).data('id'));
                modal.modal('show');
            });
        })(jQuery);
    </script>
@endpush
