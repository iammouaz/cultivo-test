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
                            <th>@lang('Name')</th>
                            <th>@lang('Description')</th>
                            <th>@lang('Leader Name')</th>
                            <th>@lang('Action')</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($groups as $group)
                        <tr>
                            <td data-label="@lang('S.N')">{{ $groups->firstItem() + $loop->index }}</td>
                            <td data-label="@lang('Name')">{{ $group->name }}</td>
                            <td data-label="@lang('Description')">{{ $group->description}}</td>
                            <td data-label="@lang('Leader Name')">
                                @if($group->leader)
                                {{ $group->leader->fullname }}
                                @else
                                -
                                @endif
                            </td>
                            <td data-label="@lang('Invite Users')">
                                <a href="{{route('admin.Invitation.index',[$group->id])}}" class="icon-btn  mr-1" data-toggle="tooltip" data-original-title="@lang('Invite')">
                                    <i class="las la-users text--shadow"></i>
                                </a>
                                <a href="{{route('admin.Group.group_users',[$group->id])}}" class="icon-btn  mr-1" data-toggle="tooltip" data-original-title="@lang('View')">
                                    <i class="las la-users text--shadow"></i>
                                </a>
                                <a href="{{route('admin.group.edit',[$group->id])}}" class="icon-btn mr-1" data-toggle="tooltip" data-original-title="@lang('Edit')">
                                    <i class="las la-pen text--shadow"></i>
                                </a>
                            
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
            @if ($groups->hasPages())
                <div class="card-footer py-4">
                    {{ paginateLinks($groups) }}
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

<div class="d-flex flex-wrap justify-content-sm-end header-search-wrapper">
    <form action="" method="GET" class="header-search-form">
        <div class="input-group has_append">
            <input type="text" name="search" class="form-control bg-white text--black" placeholder="@lang('Group')" value="{{ $search ?? '' }}">
            <div class="input-group-append">
                <button class="btn btn--primary" type="submit"><i class="fa fa-search"></i></button>
            </div>
        </div>
    </form>
    <a class="btn btn--primary box--shadow1 text--small" href="{{ route('admin.group.create',[$event->id]) }}"><i class="fa fa-fw fa-plus"></i>@lang('Add New')</a>
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
