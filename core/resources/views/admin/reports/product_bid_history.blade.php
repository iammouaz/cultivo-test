@extends('admin.layouts.app')
@section('panel')
    <div class="row">
        <div class="col-lg-12">
            <div class="card b-radius--10 ">
                <div class="card-body p-0">
                    <div class="table-responsive--sm table-responsive">
                        <table class="table table--light style--two">
                            <thead>
                            <tr>
                                <th>@lang('S.N.')</th>
                                <th>@lang('User')</th>
                                <th>@lang('Company Name')</th>
                                <th>@lang('Country')</th>
                                <th>@lang('State')</th>
                                <th>@lang('City')</th>
                                <th>@lang('Address 1')</th>
                                <th>@lang('Event Name')</th>
                                <th>@lang('Rank')</th>
                                <th>@lang('Product')</th>
                                <th>@lang('New Bid')</th>
                                <th>@lang('Previous Bid')</th>
                                <th>@lang('User Previous Bid')</th>
                                <th>@lang('Created at')</th>
                                <th>@lang('Updated at')</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($logs as $log)
                                <tr>
                                    <td data-label="@lang('S.N')">{{ $logs->firstItem() + $loop->index }}</td>
                                    <td data-label="@lang('User')">
                                        <span class="font-weight-bold">{{ $log->user->fullname }}</span>
                                    </td>
                                    <td data-label="@lang('Company Name')">
                                        <span class="font-weight-bold">{{ $log->user->company_name }}</span>
                                    </td>
                                    <td data-label="@lang('Country')">
                                        <span class="font-weight-bold">
                                            @if(isset($log->user->country))
                                            {{ $log->user->country->Name }}
                                            @endif
                                        </span>
                                    </td>
                                    <td data-label="@lang('State')">
                                        <span class="font-weight-bold">{{ $log->user->billing_state }}</span>
                                    </td>
                                    <td data-label="@lang('City')">
                                        <span class="font-weight-bold">{{ $log->user->billing_city }}</span>
                                    </td>
                                    <td data-label="@lang('Address 1')">
                                        <span class="font-weight-bold">{{ $log->user->billing_address_1 }}</span>
                                    </td>
                                    <td data-label="@lang('Event Name')">
                                        <span class="font-weight-bold">{{ $log->product->event->name.' '. $log->product->event->sname }}</span>
                                    </td>
                                    <td data-label="@lang('Product Rank')">
                                        @if(count($log->product->product_specification)>0)
                                        @foreach($log->product->product_specification as $spec)
                                        @if(strtoupper($spec->spec_key)=='RANK')
                                        <span class="font-weight-bold">
                                            {{$spec->Value}}
                                        </span>                                       
                                        @endif
                                        @endforeach
                                        @endif
                                    </td>
                                    <td data-label="@lang('Product')">
                                        <span class="font-weight-bold">{{ $log->product->name }}</span>
                                    </td>
                                    <td data-label="@lang('New Bid')">
                                        {{ $log->new_bid }}
                                    </td>
                                    <td data-label="@lang('Previous Bid')">
                                        {{ $log->previous_bid }}
                                    </td>
                                    <td data-label="@lang('Previous Bid')">
                                        {{ $log->user_previous_bid }}
                                    </td>
                                    <td data-label="@lang('Previous Bid')">
                                        <span class="font-weight-bold">{{ $log->created_at }}</span>
                                    </td>
                                    <td data-label="@lang('Previous Bid')">
                                        <span class="font-weight-bold">{{ $log->updated_at }}</span>
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
                <div class="card-footer py-4">
                    {{ paginateLinks($logs) }}
                </div>
            </div><!-- card end -->
        </div>
    </div>
@endsection
@push('breadcrumb-plugins')

    <div class="d-flex flex-wrap justify-content-sm-end header-search-wrapper">
        @if(Session::has('filename'))
        <a class="btn btn--primary box--shadow1 text--small"
        href="{{asset('/exports/'.Session::get('filename'))}}"><i class="fa fa-fw fa-file-download"></i>@lang('Click Here to Download File')</a>
        @endif
        <form action="" method="GET" class="form-inline float-sm-left bg--white">
            @csrf
            <div class="input-group has_append">
                <select name="event_id" id="event" class="form-control">
                    <option value="0">ALL Events</option>
                    @foreach($events as $event)
                        <option value="{{$event->id}}"
                                @if($event_id==$event->id) selected @endif>{{$event->name.' '.$event->sname}}</option>
                    @endforeach
                </select>
                @if($event_id)
                    <select name="product_id" id="event" class="form-control">
                        <option value="0">ALL Product</option>
                        @foreach($products as $product)
                            <option value="{{$product->id}}"
                                    @if($product_id==$product->id) selected @endif>{{$product->name}}</option>
                        @endforeach
                    </select>

                @else
                <?php $event_id=0 ?>
                @endif
                <div class="input-group-append">
                    <button class="btn btn--primary" type="submit"><i class="fa fa-filter"></i></button>
                </div>
            </div>
        </form>

        <a class="btn btn--primary box--shadow1 text--small"
           href="{{route('admin.bid.history.export',['event_id'=>$event_id,'product_id'=>$product_id])}}" id="export"
           onclick="exportTasks(event.target);"><i class="fa fa-fw fa-file-export"></i>@lang('Export to CSV')</a>

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


        @media (max-width: 400px) {
            .header-search-form {
                width: 100%
            }
        }
    </style>
@endpush
@push('script')
    <script>
        function exportTasks(_this) {
            let _url = $(_this).data('href');
            window.location.href = _url;
        }
    </script>
@endpush
