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
                                <th scope="col">@lang('S.N.')</th>
                                <th scope="col">@lang('User Name')</th>
                                <th scope="col">@lang('User Email')</th>
                                <th scope="col">@lang('Company Name')</th>
                                <th scope="col">@lang('Upcoming Events Date')</th>
                                <!-- <th scope="col">@lang('Product Price')</th>
                                    <th scope="col">@lang('Bid Amount')</th>
                                    <th scope="col">@lang('Bid Time')</th> -->
                                <th scope="col">@lang('Events')</th>
                                <th scope="col">@lang('Action')</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($users as $user)
                            <tr>
                                <td data-label="@lang('S.N')">{{ $users->firstItem() + $loop->index }}</td>
                                <td data-label="@lang('User')">
                                <a href="{{ route('admin.users.detail', $user->id) }}">{{$user->fullname}}</a>
                                </td>
                                <td data-label="@lang('Email-Phone')">
                                {{ $user->email }}
                                </td>
                                <td data-label="@lang('Company Name')">
                                {{ $user->company_name }}
                                </td>
                                <td data-label="@lang('Accepted On')">
                                    {{ $user->opt_in_upcoming_events_date }}
                                </td>
                                <!-- <td data-label="@lang('Country')">
                                    <span class="font-weight-bold" data-toggle="tooltip" data-original-title="{{ @$user->address->country }}">{{ $user->country_code }}</span>
                                </td> -->



                                <!-- <td data-label="@lang('Joined At')">
                                    {{ showDateTime($user->created_at) }} <br> {{ diffForHumans($user->created_at) }}
                                </td> -->


                                <!-- <td data-label="@lang('Balance')">
                                    <span class="font-weight-bold">
                                        
                                    {{ $general->cur_sym }}{{ showAmount($user->balance) }}
                                    </span>
                                </td> -->


                                <td data-label="@lang('Action')">
                                    <a href="{{ route('admin.users.events', $user->id) }}" class="icon-btn" data-toggle="tooltip" title="" data-original-title="@lang('Events')">
                                        <i class="las la-desktop text--shadow"></i>
                                    </a>
                                </td>
                                <td data-label="@lang('Action')">
                                    <a href="{{ route('admin.users.detail', $user->id) }}" class="icon-btn" data-toggle="tooltip" title="" data-original-title="@lang('Details')">
                                        <i class="las la-desktop text--shadow"></i>
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
            @if ($users->hasPages())
                <div class="card-footer py-4">
                    {{ paginateLinks($users) }}
                </div>
            @endif
        </div>
    </div>


</div>
@endsection

@push('breadcrumb-plugins')
<form action="{{ route('admin.users.search', $scope ?? str_replace('admin.users.', '', request()->route()->getName())) }}" method="GET" class="form-inline float-sm-right bg--white">
    <div class="input-group has_append">
        <input type="text" name="search" class="form-control" placeholder="@lang('Username or email or company')" value="{{ $search ?? '' }}">
        <div class="input-group-append">
            <button class="btn btn--primary mr-1" type="submit"><i class="fa fa-search"></i></button>
            <a class="btn btn--primary box--shadow1 text--small" href=""  id="export" onclick="exportTableToCSV();"><i class="fa fa-fw fa-file-export"></i>@lang('Export to CSV')</a>
        </div>
    </div>
</form>
<form action="{{route('admin.users.filter_by_event',$scope ?? str_replace('admin.users.', '', request()->route()->getName()))}}" method="GET" class="form-inline float-sm-right bg--white mr-1">
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
@endpush
@push('script')
<script>
   function exportTasks(_this) {
      let _url = $(_this).data('href');
      window.location.href = _url;
   }
   function downloadCSV(csv, filename) {
    var csvFile;
    var downloadLink;

    // CSV file
    csvFile = new Blob([csv], {type: "text/csv"});

    // Download link
    downloadLink = document.createElement("a");

    // File name
    downloadLink.download = filename;

    // Create a link to the file
    downloadLink.href = window.URL.createObjectURL(csvFile);

    // Hide download link
    downloadLink.style.display = "none";

    // Add the link to DOM
    document.body.appendChild(downloadLink);

    // Click download link
    downloadLink.click();
}
   function exportTableToCSV() {
        var pageTitle = '{{$pageTitle}}';

        if (pageTitle === 'Users who Opted-in for Upcoming Events') {
        
        var url = "{{route('admin.users.DownloadCsvApprovedUpcomingEvent')}}";

        var xhr = new XMLHttpRequest();
        xhr.open('GET', url, true);
        xhr.responseType = 'blob'; // Set the response type to blob for binary data

        xhr.onload = function () {
            if (xhr.status === 200) {
                var blob = xhr.response;

                // Create a link element, set its download attribute and trigger a click event
                var link = document.createElement('a');
                link.href = window.URL.createObjectURL(blob);
                link.download = 'approved_upcoming_events.csv'; // Set the desired file name
                document.body.appendChild(link);
                link.click();
                document.body.removeChild(link);
            } else {
                console.error('Failed to download file, status code:', xhr.status);
            }
        };
        xhr.onerror = function () {
            console.error('Network error while downloading file.');
        };

        xhr.send();
    }else{

        var csv = [];
        var rows = document.querySelectorAll("table tr");
        var dropdown=document.getElementById('event');
        var filename=dropdown.options[dropdown.selectedIndex].text;
        for (var i = 0; i < rows.length; i++) {
            var row = [], cols = rows[i].querySelectorAll("td, th");
            
            for (var j = 0; j < cols.length-2; j++) 
                row.push(cols[j].innerText);
            
            csv.push(row.join(","));        
        }
        // Download CSV file
        downloadCSV(csv.join("\n"), filename);
    }
}
</script>
@endpush