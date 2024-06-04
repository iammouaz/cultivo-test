@extends('admin.layouts.app')
@section('panel')
    <div class="row">

        @php
            $events = $offerSheets;
        @endphp





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
                                    {{-- <th>@lang('Status')</th>
                                    <th>@lang('Publish/Unpublish')</th> --}}
                                    <th>@lang('Action')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($events as $event)
                                    <tr>
                                        <td data-label="@lang('S.N')">{{ $events->firstItem() + $loop->index }}</td>
                                        <td data-label="@lang('Name')"><a
                                                href="{{ route('admin.offer_sheet.edit', $event->id) }}">{{ $event->name . ' ' . $event->sname }}</a>
                                        </td>
                                        <td data-label="@lang('Category')">{{ $event->category->name ?? null }}</td>
                                        <td data-label="@lang('Description')">{{ Str::words($event->description, 10, '...') }}
                                        </td>
                                        {{-- <td data-label="@lang('Status')">@lang($event->status ? 'Published' : 'Unpublished')</td>
                                        <td data-label="@lang('Publish/Unpublish')">{{ $event->start_status }}</td> --}}


                                        <td data-label="@lang('Action')">
                                            <div class="dropdown">

                                                <a data-toggle="dropdown" aria-expanded="false" data-display="static"
                                                    class="" data-toggle="tooltip"
                                                    data-original-title="@lang('Edit')">
                                                    <i class="las la-pen" style="font-size: 20px"></i>
                                                </a>



                                                <div
                                                    class="dropdown-menu dropdown-menu--sm p-0 border-0 box--shadow1 dropdown-menu-right">
                                                    <a target="_blank"
                                                        href="{{ route('offer_sheet.activeOffers', $event->offer_sheet_url) }}"
                                                        class="dropdown-menu__item d-flex align-items-center px-3 py-2">
                                                        <span class="dropdown-menu__caption">@lang('Preview')</span>
                                                    </a>
                                                    <a href="{{ route('admin.offer_sheet.edit', $event->id) }}"
                                                        class="dropdown-menu__item d-flex align-items-center px-3 py-2">
                                                        <span class="dropdown-menu__caption">@lang('Edit')</span>
                                                    </a>
                                                    <a href="{{ route('admin.offer_sheet.duplicate', $event->id) }}"
                                                        class="dropdown-menu__item d-flex align-items-center px-3 py-2">
                                                        <span class="dropdown-menu__caption">@lang('Duplicate')</span>
                                                    </a>
                                                    <a href="#"
                                                        class="dropdown-menu__item d-flex align-items-center px-3 py-2"
                                                        onclick="showDeleteConfirmation('{{ route('admin.offer_sheet.delete', $event->id) }}')">
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
                @if ($events->hasPages())
                    <div class="card-footer py-4">
                        {{ paginateLinks($events) }}
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection

@push('breadcrumb-plugins')
    <div class="d-flex flex-wrap justify-content-sm-end header-search-wrapper">
        <form action="{{ route('admin.offer_sheet.search') }}" method="GET" class="header-search-form">
            <div class="input-group has_append">
                <input type="text" name="search" class="form-control bg-white text--black"
                    placeholder="@lang('Offer Sheet Name')" value="{{ $search ?? '' }}">
                <div class="input-group-append">
                    <button class="btn btn--primary" type="submit"><i class="fa fa-search"></i></button>
                </div>
            </div>
        </form>






        <div class="dropdown">

            <a data-toggle="dropdown" aria-expanded="false" data-display="static" href="#"
                class="btn btn--primary box--shadow1 text--small btn-lg py-2" data-toggle="tooltip"
                data-original-title="@lang('Edit')">
                <i class="fa fa-fw fa-plus"></i>@lang('Add New')
            </a>



            <div class="dropdown-menu dropdown-menu--sm p-0 border-0 box--shadow1 dropdown-menu-right">
                <a href="{{ route('admin.offer_sheet.create') }}"
                    class="dropdown-menu__item d-flex align-items-center px-3 py-2">
                    <span class="dropdown-menu__caption">@lang('Offer sheet')</span>
                </a>
                <a href="{{ route('admin.event.create') }}"
                    class="dropdown-menu__item d-flex align-items-center px-3 py-2">
                    <span class="dropdown-menu__caption">@lang('Event')</span>
                </a>
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
