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
                            <th>@lang('User Email')</th>
                            <th>@lang('Company Name')</th>
                            <th>@lang('Event Name')</th>
                            
                            <th>@lang('Status')</th>
                            <th>@lang('Action')</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($requests as $request)
                        <tr>
                            <td data-label="@lang('S.N')">{{ $requests->firstItem() + $loop->index }}</td>
                            <td data-label="@lang('User Name')">{{ $request->user->fullname }}</td>
                            <td data-label="@lang('User Name')">{{ $request->user->email }}</td>
                            <td data-label="@lang('User Name')">{{ $request->user->company_name }}</td>
                            <td data-label="@lang('Event Name')">{{ $request->event->name.' '.$request->event->sname }}</td>
                            
                            <td data-label="@lang('Status')">

                             @if($request->status == 0)
                             <span class="text--small badge font-weight-normal badge--warning">@lang('Reject')</span>
                             @elseif($request->status == 1)
                             <span class="text--small badge font-weight-normal badge--success">@lang('Approve')</span>
                             @elseif($request->status == -1)
                             <span class="text--small badge font-weight-normal badge--primary">@lang('Pending')</span>
                             @endif

                            </td>
                            <td data-label="@lang('Action')">
                                @if($request->status==-1)
                                <a href="{{route('admin.request.approve',[$request->event_id,$request->user_id])}}" class="icon-btn btn--success mr-1" data-toggle="tooltip" data-original-title="@lang('Approve')">
                                    <i class="las la-check text--shadow"></i>
                                </a>
                                <a href="{{route('admin.request.reject',[$request->id])}}" class="icon-btn btn--danger mr-1" data-toggle="tooltip" data-original-title="@lang('Reject')">
                                    <i class="las la-ban text--shadow"></i>
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
                    </table><!-- table end -->
                </div>
            </div>
            @if ($requests->hasPages())
                <div class="card-footer py-4">
                    {{ paginateLinks($requests) }}
                </div>
            @endif
        </div>
    </div>
</div>


{{-- APPROVE MODAL --}}
<!--<div id="approveModal" class="modal fade" tabindex="-1" role="dialog">
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
                    <p>@lang('Are you sure to') <span class="font-weight-bold">@lang('approve')</span> <span class="font-weight-bold withdraw-amount text-success"></span> @lang('this event') <span class="font-weight-bold withdraw-user"></span>?</p>
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

<!-- <div class="d-flex flex-wrap justify-content-sm-end header-search-wrapper">
    <form action="" method="GET" class="header-search-form">
        <div class="input-group has_append">
            <input type="text" name="search" class="form-control bg-white text--black" placeholder="@lang('Event')" value="{{ $search ?? '' }}">
            <div class="input-group-append">
                <button class="btn btn--primary" type="submit"><i class="fa fa-search"></i></button>
            </div>
        </div>
    </form>
    <a class="btn btn--primary box--shadow1 text--small" href="{{ route('admin.event.create') }}"><i class="fa fa-fw fa-plus"></i>@lang('Add New')</a>
</div> -->

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
