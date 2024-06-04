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
                            <th>@lang('User Name')</th>
                            <th>@lang('Event')</th>
                            <th>@lang('Action')</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($userpermissions as $userpermission)
                        <tr>
                            <td data-label="@lang('User Name')">{{ $userpermission->firstname.' '.$userpermission->lastname }}</td>
                            <td data-label="@lang('Event')">{{ $userpermission->event }}</td>
                            <td data-label="@lang('Action')">
                                <a href="#" style="cursor: not-allowed;" class="icon-btn mr-1" data-toggle="tooltip" data-original-title="@lang('Edit')">
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
            @if ($userpermissions->hasPages())
                <div class="card-footer py-4">
                    {{ paginateLinks($userpermissions) }}
                </div>
            @endif
        </div>
    </div>
</div>

@endsection

@push('breadcrumb-plugins')

<div class="d-flex flex-wrap justify-content-sm-end header-search-wrapper">
    <form action="" method="GET" class="header-search-form">
        <div class="input-group has_append">
            <input type="text" name="search" class="form-control bg-white text--black" placeholder="@lang('User Permission')" value="{{ $search ?? '' }}">
            <div class="input-group-append">
                <button class="btn btn--primary" type="submit"><i class="fa fa-search"></i></button>
            </div>
        </div>
    </form>
    <a class="btn btn--primary box--shadow1 text--small" href="{{ route('admin.userpermission.create') }}"><i class="fa fa-fw fa-plus"></i>@lang('Add New')</a>
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
