@extends('admin.layouts.app')
@section('panel')
    <div class="row">
        <div class="col-lg-12">
            <ul class="nav nav-pills my-3">
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('admin.event.index') && !request()->route()->hasParameter('type') ? 'active' : '' }}"
                        aria-current="page" href="{{ route('admin.event.index') }}">All</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('admin.event.index') && request()->route('type') === 'offerSheets' ? 'active' : '' }}"
                        href="{{ route('admin.event.index', ['type' => 'offerSheets']) }}">Offer Sheets</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('admin.event.index') && request()->route('type') === 'auctions' ? 'active' : '' }}"
                        href="{{ route('admin.event.index', ['type' => 'auctions']) }}">Auctions</a>
                </li>

            </ul>
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
                                    <th>@lang('Start Date')</th>
                                    <th>@lang('Auction End Date')</th>
                                    <th>@lang('Display End Date')</th>
                                    <th>@lang('Max End Date')</th>
                                    <th>@lang('Less Bidding Time')</th>
                                    <th>@lang('Max Bid Increment')</th>
                                    <th>@lang('Deposit')</th>
                                    <th>@lang('EventClockStartOn')</th>
                                    <th>@lang('Start Clock')</th>
                                    <th>@lang('Action')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($events as $event)
                                    <tr>
                                        <td data-label="@lang('S.N')">{{ $events->firstItem() + $loop->index }}</td>
                                        @if ($event instanceof \App\Models\Event)
                                            <td data-label="@lang('Name')"><a
                                                    href="{{ route('admin.event.edit', $event->id) }}">{{ $event->name . ' ' . $event->sname }}</a>
                                            @else
                                            <td data-label="@lang('Name')"><a
                                                    href="{{ route('admin.offer_sheet.edit', $event->id) }}">{{ $event->name . ' ' . $event->sname }}</a>
                                        @endif
                                        </td>
                                        <td data-label="@lang('Category')">{{ $event->category->name ??"" }}</td>
                                        <td data-label="@lang('Description')">
                                            {{ Str::words($event->description, 10, '...') }}
                                        </td>
                                        <td data-label="@lang('Start Date')">{{ $event->start_date }}</td>
                                        <td data-label="@lang('Auction End Date')">{{ $event->end_date }}</td>
                                        <td data-label="@lang('Display End Date')">{{ $event->display_end_date }}</td>
                                        <td data-label="@lang('Max End Date')">{{ $event->max_end_date }}</td>
                                        <td data-label="@lang('Less Bidding Time')">{{ $event->less_bidding_time }}</td>
                                        <td data-label="@lang('Max Bid Increment')">{{ $event->max_bidding_value }}</td>
                                        <td data-label="@lang('Deposit')">{{ $event->deposit }}</td>
                                        <td data-label="@lang('EventClockStartOn')">
                                            @if (isset($event->EventClockStartOn) && $event->EventClockStartOn == 0)
                                                All product have a bid
                                            @endif
                                            @if (isset($event->EventClockStartOn) && $event->EventClockStartOn == 1)
                                                Manually
                                            @endif
                                        </td>
                                        <td data-label="@lang('EventClockStartOn')">
                                            @if (isset($event->EventClockStartOn) && $event->EventClockStartOn == 0)
                                                -
                                            @endif
                                            @if (isset($event->EventClockStartOn) && $event->EventClockStartOn == 1)
                                                <a href="{{ route('admin.event.start', [$event->id]) }}"
                                                    class="btn btn-success">
                                                    Start
                                                </a>
                                            @endif
                                        </td>
                                        <td data-label="@lang('Action')">

                                            <div class="dropdown">



                                                <a data-toggle="dropdown" aria-expanded="false" data-display="static"
                                                    class="" data-toggle="tooltip"
                                                    data-original-title="@lang('Edit')">
                                                    <i class="las la-pen" style="font-size: 20px"></i>
                                                </a>



                                                <div
                                                    class="dropdown-menu dropdown-menu--sm p-0 border-0 box--shadow1 dropdown-menu-right">
                                                    @if ($event instanceof \App\Models\OfferSheet)
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
                                                    @else
                                                        <a target="_blank"
                                                            href="{{ $event->event_url ? route('event.preview', ['url' => $event->event_url]) : '' }}"
                                                            class="dropdown-menu__item d-flex align-items-center px-3 py-2">
                                                            <span class="dropdown-menu__caption">@lang('Preview')</span>
                                                        </a>
                                                        <a href="{{ route('admin.event.edit', $event->id) }}"
                                                            class="dropdown-menu__item d-flex align-items-center px-3 py-2">
                                                            <span class="dropdown-menu__caption">@lang('Edit')</span>
                                                        </a>
                                                        <a href="{{ route('admin.event.duplicate', $event->id) }}"
                                                            class="dropdown-menu__item d-flex align-items-center px-3 py-2">
                                                            <span class="dropdown-menu__caption">@lang('Duplicate')</span>
                                                        </a>
                                                        <a href="#"
                                                            class="dropdown-menu__item d-flex align-items-center px-3 py-2"
                                                            onclick="showDeleteConfirmation('{{ route('admin.event.delete', $event->id) }}')">
                                                            <span class="dropdown-menu__caption">@lang('Delete')</span>
                                                        </a>
                                                    @endif
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
    <!-- <div id="approveModal" class="modal fade" tabindex="-1" role="dialog">
                                                                                                                                            <div class="modal-dialog" role="document">
                                                                                                                                                <div class="modal-content">
                                                                                                                                                    <div class="modal-header">
                                                                                                                                                        <h5 class="modal-title">@lang('Approve Confirmation')</h5>
                                                                                                                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                                                                                                            <span aria-hidden="true">&times;</span>
                                                                                                                                                        </button>
                                                                                                                                                    </div>
                                                                                                                                                    <form action="{{ route('admin.product.approve') }}" method="POST">
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
        <form action="{{ route('admin.event.search') }}" method="GET" class="header-search-form">
            <div class="input-group has_append">
                <input type="text" name="search" class="form-control bg-white text--black"
                    placeholder="@lang('Event Name')" value="{{ $search ?? '' }}">
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
        .nav-pills .nav-link.active,
        .nav-pills .show>.nav-link {
            color: initial !important;
            background: initial !important;
            border-bottom: solid 2px #007bff;
            border-bottom-left-radius: 0;
            border-bottom-right-radius: 0;
        }

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
