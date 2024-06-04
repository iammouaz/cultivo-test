@extends('admin.layouts.app')
@php
    $products = $offers;
@endphp
@section('panel')
    <div class="row">
        <div class="col-12 mb-3">
            <div class="d-flex justify-content-between">
                @if (request()->get('offer_sheet_id'))
                    <h6><img class="mr-2" src="/custom/images/filter-icon.svg" />{{ $offer_sheet->name }}</h6>
                @else
                    <div></div>
                @endif


                <div class="d-flex flex-wrap justify-content-sm-end header-search-wrapper">
                    <form
                        action="{{ route('admin.offer.index', $scope ?? str_replace('admin.offer.', '', request()->route()->getName())) }}"
                        method="GET" class="header-search-form">
                        <div class="input-group has_append">
                            <input type="text" name="search" class="form-control bg-white text--black"
                                placeholder="@lang('Product')" value="{{ $search ?? '' }}">
                            <div class="input-group-append">
                                <button class="btn btn--primary" type="submit"><i class="fa fa-search"></i></button>
                            </div>
                        </div>
                    </form>
                    <a class="btn btn--primary box--shadow1 text--small"
                        href="{{ route('admin.offer.create', ['id' => session('offer_sheet_id')]) }}"><i
                            class="fa fa-fw fa-plus"></i>@lang('Add New')
                    </a>
                </div>
            </div>
        </div>
        <div class="col-lg-12">
            <div class="card b-radius--10 ">
                <div class="card-body p-0">
                    <div class="table-responsive--md  table-responsive">
                        <table class="table table--light style--two">
                            <thead>
                                <tr>
                                    <th>@lang('S.N.')</th>
                                    <th>@lang('Name')</th>
                                    <th>@lang('Category')</th>
                                    <th>@lang('Description')</th>
                                    {{--                                    <th>@lang('Status')</th> --}}
                                    {{--                                    <th>@lang('Publish/Unpublish')</th> --}}
                                    <th>@lang('Action')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($products as $product)
                                    <tr>
                                        <td data-label="@lang('S.N')">{{ $products->firstItem() + $loop->index }}</td>
                                        <td data-label="@lang('Name')"><a
                                                href="{{ route('admin.offer.edit', $product->id) }}">{{ __($product->name) }}</a>
                                        </td>
                                        <td data-label="@lang('Category')">
                                            {{ isset($product->offerSheet->category) ? $product->offerSheet->category->name : '' }}
                                        </td>
                                        <td data-label="@lang('Description')">
                                            {{ Str::words($product->short_description, 10, '...') }}</td>

                                        {{--                                        <td data-label="@lang('Status')"> --}}
                                        {{--                                            @if ($product->status == 0 && $product->expired_at > now()) --}}
                                        {{--                                                <span --}}
                                        {{--                                                    class="text--small badge font-weight-normal badge--warning">@lang('Pending')</span> --}}
                                        {{--                                            @elseif($product->status == 1 && $product->started_at < now() && $product->expired_at > now()) --}}
                                        {{--                                                <span --}}
                                        {{--                                                    class="text--small badge font-weight-normal badge--success">@lang('Live')</span> --}}
                                        {{--                                            @elseif($product->status == 1 && $product->started_at > now()) --}}
                                        {{--                                                <span --}}
                                        {{--                                                    class="text--small badge font-weight-normal badge--primary">@lang('Upcoming')</span> --}}
                                        {{--                                            @else --}}
                                        {{--                                                <span --}}
                                        {{--                                                    class="text--small badge font-weight-normal badge--danger">@lang('Expired')</span> --}}
                                        {{--                                            @endif --}}
                                        {{--                                        </td> --}}


                                        {{--                                        <td data-label="@lang('Publish/Unpublish')">{{ $product->status }}</td> --}}







                                        <td data-label="@lang('Action')">
                                            <div class="dropdown">

                                                <a data-toggle="dropdown" aria-expanded="false" data-display="static"
                                                    class="" data-toggle="tooltip"
                                                    data-original-title="@lang('Edit')">
                                                    <i class="las la-pen" style="font-size: 20px"></i>
                                                </a>



                                                <div
                                                    class="dropdown-menu dropdown-menu--sm p-0 border-0 box--shadow1 dropdown-menu-right">
                                                    <a target="_blank" href="{{ route('product.all') }}"
                                                        class="dropdown-menu__item d-flex align-items-center px-3 py-2">
                                                        <span class="dropdown-menu__caption">@lang('Preview')</span>
                                                    </a>
                                                    <a href="{{ route('admin.offer.edit', $product->id) }}"
                                                        class="dropdown-menu__item d-flex align-items-center px-3 py-2">
                                                        <span class="dropdown-menu__caption">@lang('Edit')</span>
                                                    </a>
                                                    <a href="{{ route('admin.offer.duplicate', $product->id) }}"
                                                        class="dropdown-menu__item d-flex align-items-center px-3 py-2">
                                                        <span class="dropdown-menu__caption">@lang('Duplicate')</span>
                                                    </a>
                                                    <a href="#"
                                                        class="dropdown-menu__item d-flex align-items-center px-3 py-2"
                                                        onclick="showDeleteConfirmation('{{ route('admin.offer.delete', $product->id) }}')">
                                                        <span class="dropdown-menu__caption">@lang('Delete')</span>
                                                    </a>
                                                </div>

                                                <!-- Modal -->
                                                <div class="modal fade" id="confirmationModal" tabindex="-1" role="dialog"
                                                    aria-labelledby="confirmationModalLabel" aria-hidden="true">
                                                    <div class="modal-dialog" role="document">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="confirmationModalLabel">Confirm
                                                                    Deletion</h5>
                                                                <button type="button" class="close" data-dismiss="modal"
                                                                    aria-label="Close">
                                                                    <span aria-hidden="true">&times;</span>
                                                                </button>
                                                            </div>
                                                            <div class="modal-body text-left">
                                                                Are you sure you want to delete this item?
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary"
                                                                    data-dismiss="modal">Cancel</button>
                                                                <button type="button" class="btn btn-danger"
                                                                    id="deleteButton">Delete</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                            </div>

                                            {{-- <button type="button" class="icon-btn btn--success approveBtn"
                                                data-toggle="tooltip" data-original-title="@lang('Approve')"
                                                data-id="{{ $product->id }}"
                                                {{ $product->status == 1 || $product->expired_at < now() ? 'disabled' : '' }}>
                                                <i class="las la-check text--shadow"></i>
                                            </button> --}}

                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td class="text-muted text-center" colspan="100%">{{ __($emptyMessage) }}</td>
                                    </tr>
                                @endforelse

                            </tbody>
                        </table><!-- table end -->
                    </div>
                </div>
                @if ($products->hasPages())
                    <div class="card-footer py-4">
                        {{ paginateLinks($products) }}
                    </div>
                @endif
            </div>
        </div>
    </div>


    {{-- APPROVE MODAL --}}
    <div id="approveModal" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">@lang('Approve Confirmation')</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('breadcrumb-plugins')
    <div class="row">
        <div class="col-12 mb-3">
            <div class="d-flex flex-wrap justify-content-sm-end header-search-wrapper">
                <form
                    action="{{ route('admin.offer.filter_by_offer_sheet', $scope ?? str_replace('admin.offer.', '', request()->route()->getName())) }}"
                    method="GET" class="form-inline float-sm-left bg--white">
                    <div class="input-group has_append">
                        <select name="offer_sheet_id" id="offer_sheet" class="form-control">
                            <option value="0">ALL Offer Sheets</option>
                            @foreach ($offer_sheets as $offer_sheet)
                                <option value="{{ $offer_sheet->id }}" @if (session()->has('offer_sheet_id') && session('offer_sheet_id') == $offer_sheet->id) selected @endif>
                                    {{ $offer_sheet->name . ' ' . $offer_sheet->sname }}</option>
                            @endforeach
                        </select>
                        <div class="input-group-append">
                            <button class="btn btn--primary" type="submit"><i class="fa fa-filter"></i></button>
                        </div>
                    </div>
                </form>

            </div>
        </div>


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
        (function($) {
            "use strict";

            $('.approveBtn').on('click', function() {
                var modal = $('#approveModal');
                modal.find('input[name=id]').val($(this).data('id'));
                modal.modal('show');
            });
        })(jQuery);
    </script>
    <script>
        function showDeleteConfirmation(deleteUrl) {
            $('#confirmationModal').modal('show');
            $('#deleteButton').click(function() {
                window.location.href = deleteUrl; // Redirect to delete URL
            });
        }
    </script>
@endpush
