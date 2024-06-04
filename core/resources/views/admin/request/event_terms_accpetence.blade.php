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
                            <th>@lang('Event Name')</th>        
                            <th>@lang('Accepted On')</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($requests as $request)
                        <tr>
                            <td data-label="@lang('S.N')">{{ $requests->firstItem() + $loop->index }}</td>
                            <td data-label="@lang('User Name')">{{ $request->user->fullname }}</td>
                            <td data-label="@lang('Event Name')">{{ $request->event->name.' '.$request->event->sname }}</td>
                            <td data-label="@lang('Accepted On')">{{$request->date_accept}}</td>
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
            @if ($requests->hasPages())
                <div class="card-footer py-4">
                    {{ paginateLinks($requests) }}
                </div>
            @endif
        </div>
    </div>
</div>




@endsection

@push('breadcrumb-plugins')
<div class="d-flex flex-wrap justify-content-sm-end header-search-wrapper">
    <form action="{{route('admin.request.accept_event_terms')}}" method="GET" class="header-search-form">
      
        <div class="input-group has_append">
            <select name="event_id" id="event" class="form-control">
                    <option value="0">ALL Events</option>
                    @foreach($events as $event)
                    <option value="{{$event->id}}" @if(session()->has('event_id')&& session('event_id')==$event->id) selected @endif>{{$event->name.' '.$event->sname}}</option>
                    @endforeach
            </select>
            <select name="user_id" id="user" class="form-control">
                <option value="0">ALL Users</option>
                @foreach($users as $user)
                <option value="{{$user->id}}" @if(session()->has('user_id')&& session('user_id')==$user->id) selected @endif>{{$user->fullname}}</option>
                @endforeach
            </select>
            <div class="input-group-append">
                <button class="btn btn--primary" type="submit"><i class="fa fa-filter"></i></button>
            </div>
        </div>
    </form>
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
 
@endpush
