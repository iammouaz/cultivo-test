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
                                    <th>@lang('Product Name')</th>
                                    <th>@lang('Event Name')</th>
                                    <th>@lang('Winning Date')</th>
                                    <th>@lang('Product Delivered')</th>
                                    <th>@lang('Caption')</th>
                                    <th>@lang('Action')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($winners as $winner)
                                    <tr>
                                        <td data-label="@lang('S.N')">{{ $winners->firstItem() + $loop->index }}</td>
                                        <td data-label="@lang('User Name')">{{ $winner->user->fullname }} <br>
                                            <a href="{{ route('admin.users.detail', $winner->user->id) }}"
                                                target="_blank">{{ $winner->user->username }}</a>
                                        </td>
                                        <td data-label="@lang('Product Name')"><a
                                                href="{{ $winner->product!= null? route('product.details', [$winner->product->id, slug($winner->product->name)]):'' }}"
                                                target="_blank">{{ $winner->product->name??'' }}</a></td>
                                        <td data-label="@lang('Event Name')"><a
                                            href="{{ $winner->product!= null? route('admin.event.edit', $winner->product->event->id):'' }}"
                                            target="_blank">{{$winner->product!= null? $winner->product->event->name.' '.$winner->product->event->sname:''}}</a></td>
                                        <td data-label="@lang('Winning Date')">{{ showDateTime($winner->created_at) }}</td>
                                        <td data-label="@lang('Product Delivered')">
                                            @if ($winner->product_delivered == 0)
                                                <span
                                                    class="text--small badge font-weight-normal badge--warning">@lang('Pending')</span>
                                            @else
                                                <span
                                                    class="text--small badge font-weight-normal badge--success">@lang('Delivered')</span>
                                            @endif
                                        </td>
                                        <td style="width: 100px;" data-caption="{{ $winner->id }}" class="editable-cell"
                                            data-label="@lang('Caption')">{{ $winner->caption }}
                                            <i class="fas fa-edit ml-1"></i>

                                        </td>
                                        <td data-label="@lang('Action')">
                                            <button type="button" class="icon-btn bid-details" data-toggle="tooltip"
                                                data-original-title="@lang('Details')" data-user="{{ $winner->user }}">
                                                <i class="las la-desktop text--shadow"></i>
                                            </button>
                                            <button type="button" class="icon-btn btn--success productDeliveredBtn"
                                                data-toggle="tooltip" data-original-title="@lang('Delivered')"
                                                data-id="{{ $winner->id }}"
                                                {{ $winner->product_delivered ? 'disabled' : '' }}>
                                                <i class="las la-check text--shadow"></i>
                                            </button>
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
                @if ($winners->hasPages())
                    <div class="card-footer py-4">
                        {{ paginateLinks($winners) }}
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- User information modal --}}
    <div id="bidModal" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">@lang('User Information')</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('merchant.bid.winner') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div>
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    @lang('Name'):
                                    <span class="user-name"></span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    @lang('Mobile'):
                                    <span class="user-mobile"></span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    @lang('Email'):
                                    <span class="user-email"></span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    @lang('Address'):
                                    <span class="user-address"></span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    @lang('State'):
                                    <span class="user-state"></span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    @lang('Zip Code'):
                                    <span class="user-zip"></span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    @lang('City'):
                                    <span class="user-city"></span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    @lang('Country'):
                                    <span class="user-country"></span>
                                </li>
                            </ul>
                        </div>
                        <input type="hidden" name="bid_id">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn--dark" data-dismiss="modal">@lang('Close')</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @push('breadcrumb-plugins')

    <div class="d-flex flex-wrap justify-content-sm-end header-search-wrapper">
        <form action="{{route('admin.product.winners.filter')}}" method="Post" class="form-inline float-sm-left bg--white">
            @csrf
            <div class="input-group has_append">
                <select name="event_id" id="event" class="form-control">
                <option value="0">ALL Events</option>
                    @foreach($events as $event)
                    <option value="{{$event->id}}" @if(session()->has('event_id')&& session('event_id')==$event->id) selected @endif>{{$event->name.' '.$event->sname}}</option>
                    @endforeach
                </select>
                <div class="input-group-append">
                    <button class="btn btn--primary" type="submit"><i class="fa fa-filter"></i></button>
                </div>
            </div>
        </form>
    </div>

    @endpush
    {{-- Product Delivered Confirmation --}}
    <div id="productDeliveredModal" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">@lang('Product Delivered Confirmation')</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('admin.product.delivered') }}" method="POST">
                    @csrf
                    <input type="hidden" name="id">
                    <div class="modal-body">
                        <p>@lang('Is the product delivered')</p>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn--primary btn-block">@lang('Yes')</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection


@push('script')
    <script>
        (function($) {
            "use strict";
            $('.bid-details').click(function() {
                var modal = $('#bidModal');
                var user = $(this).data().user;
                modal.find('.user-name').text(user.firstname + ' ' + user.lastname);
                modal.find('.user-mobile').text(user.mobile);
                modal.find('.user-email').text(user.email);
                modal.find('.user-address').text(user.address.address);
                modal.find('.user-state').text(user.address.state);
                modal.find('.user-zip').text(user.address.zip);
                modal.find('.user-city').text(user.address.city);
                modal.find('.user-country').text(user.address.country);
                modal.modal('show');
            });

            $('.productDeliveredBtn').click(function() {
                var modal = $('#productDeliveredModal');
                modal.find('[name=id]').val($(this).data('id'));
                modal.modal('show');

            });

            $('#bidModal').on('hidden.bs.modal', function() {
                $('#bidModal form')[0].reset();
            });

            $(document).ready(function() {
                $('.editable-cell').on('click', function() {
                    var originalText = $(this).text();
                    var inputElement = $(
                        '<input style="width: 90px;" type="text" class="editable-input" value="' +
                        originalText + '"> <i class="fas fa-save ml-1"></i>');
                    var winnerId = $(this).data('caption');

                    $(this).html(inputElement);

                    inputElement.focus();

                    inputElement.on('blur', function() {
                        var newText = $(this).val();
                        $(this).closest('.editable-cell').html(`${newText}  <i class="fas fa-edit ml-1"></i>`);
                        $.ajax({
                            url: '/admin/product/winners/edit_caption',
                            method: 'POST',
                            data: {
                                _token: "{{ csrf_token() }}",
                                id: winnerId,
                                caption: newText
                            },
                            success: function(response) {

                            },
                            error: function(error) {
                            }
                        });
                    });
                });
            });

        })(jQuery);
    </script>
@endpush
