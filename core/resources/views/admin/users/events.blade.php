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
                                <th>@lang('Event Name')</th>
                                {{-- <th>@lang('Date Accept')</th> --}}
                                <!-- <th>@lang('Approved')</th> -->
                                <th>@lang('Products')</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($events as $event)
                            <tr>
                                <td data-label="@lang('S.N')">{{ $events->firstItem() + $loop->index }}</td>
                                <td data-label="@lang('Event Name')">{{ $event->name.' '.$event->sname }}</td>
                                {{-- <td data-label="@lang('Date Accept')">{{ $event->pivot->date_accept }}</td> --}}
                                <td data-label="@lang('Products')">

                                    <a href="{{route('admin.users.products',[$event->id,$id])}}" class="icon-btn mr-1" data-toggle="tooltip" data-original-title="@lang('Products')">
                                        <i class="las la-pen text--shadow"></i>
                                    </a>


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
            @if ($events->hasPages())
            <div class="card-footer py-4">
                {{ paginateLinks($events) }}
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

<div class="d-flex flex-wrap justify-content-sm-end header-search-wrapper">
    <form action="{{route('admin.users.events.active')}}" method="GET" class="header-search-form">
        <div class="input-group has_append">
            <select name="status" id="event" class="form-control">
                <option value="0" @if(session()->has('filter')&& session('filter')==0) selected @endif>ALL Events</option>
                <option value="1" @if(session()->has('filter')&& session('filter')==1) selected @endif>Active</option>
            </select>
            <input type="hidden" name="user_id" value="{{$id}}">
            <div class="input-group-append">
                <button class="btn btn--primary" type="submit"><i class="fa fa-filter"></i></button>
            </div>
        </div>
    </form>
    <form action="{{route('admin.event.search')}}" method="GET" class="header-search-form">
        <div class="input-group has_append">
            <input type="text" name="search" class="form-control bg-white text--black" placeholder="@lang('Event Name')" value="{{ $search ?? '' }}">
            <input type="hidden" name="user_id" value="{{$id}}">
            <div class="input-group-append">
                <button class="btn btn--primary" type="submit"><i class="fa fa-search"></i></button>
            </div>
        </div>
    </form>
</div>

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
