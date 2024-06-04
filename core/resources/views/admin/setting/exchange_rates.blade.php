@extends('admin.layouts.app')

@section('panel')
    <div class="row">
        <div class="col-md-12">
            <div class="card b-radius--10 ">
                <div class="card-body">
                    <div class="table-responsive--sm table-responsive">
                        <table class="table table--light style--two custom-data-table">
                            <thead>
                                <tr>
                                    <th>@lang('S.N.')</th>
                                    <th>@lang('Currency Code')</th>
                                    <th>@lang('Base Currency')</th>
                                    <th>@lang('Exchange Rate')</th>
                                    <th>@lang('Exchange Date')</th>
                                    <th>@lang('Created_at')</th>
                                    <th>@lang('Updated_at')</th>
                                </tr>
                            </thead>
                            <tbody>
                            @foreach($currencies as $currency)
                                <tr>
                                    <td data-label="@lang('S.N')">{{ $currencies->firstItem() + $loop->index }}</td>
                                    <td data-label="@lang('Currency Code')">{{ $currency->Currency_Code}}</td>
                                    <td data-label="@lang('Base Currency')">{{ $currency->Base_Currency }}</td>
                                    <td data-label="@lang('Exchange Rate')">{{ $currency->Exchange_Rate }}</td>
                                    <td data-label="@lang('Exchange Date')">{{ $currency->Exchange_Date }}</td>
                                    <td data-label="@lang('Created_at')">{{ $currency->created_at }}</td>
                                    <td data-label="@lang('Updated_at')">{{ $currency->updated_at }}</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                @if ($currencies->hasPages())
                <div class="card-footer py-4">
                    {{ paginateLinks($currencies) }}
                </div>
            @endif
            </div>
        </div>
    </div>
@endsection
@push('breadcrumb-plugins')

<div class="d-flex flex-wrap justify-content-sm-end header-search-wrapper">
    <a class="btn btn--primary box--shadow1 text--small" href="{{ route('admin.exchange') }}"><i class="fa fa-fw fa-plus"></i>@lang('Get Exchange Rate')</a>
</div>

@endpush

@push('script')
    
@endpush
