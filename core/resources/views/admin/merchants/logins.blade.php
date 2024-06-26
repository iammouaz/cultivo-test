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
                                <th>@lang('Merchant')</th>
                                <th>@lang('Login at')</th>
                                <th>@lang('IP')</th>
                                <th>@lang('Location')</th>
                                <th>@lang('Browser | OS')</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($login_logs as $log)
                                <tr>

                                    <td data-label="@lang('Merchant')">
                                        <span class="font-weight-bold">{{ @$log->merchant->fullname }}</span>
                                        <br>
                                        <span class="small"> <a href="{{ route('admin.merchants.detail', $log->merchant) }}"><span>@</span>{{ @$log->merchant->username }}</a> </span>
                                    </td>

                                    <td data-label="@lang('Login at')">
                                        {{showDateTime($log->created_at) }} <br> {{diffForHumans($log->created_at) }}
                                    </td>

                                    <td data-label="@lang('IP')">
                                        <span class="font-weight-bold">
                                        <a href="{{route('admin.report.merchant.login.ipHistory',[$log->user_ip])}}">{{ $log->user_ip }}</a>
                                        </span>
                                    </td>

                                    <td data-label="@lang('Location')">{{ $log->city }} <br> {{ $log->country }}</td>
                                    <td data-label="@lang('Browser | OS')">
                                        {{ $log->browser }} <br> {{ $log->os}}
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
                    {{ paginateLinks($login_logs) }}
                </div>
            </div><!-- card end -->
        </div>
    </div>
@endsection

