@extends('admin.layouts.app')

@section('panel')
   

   

    <div class="row mb-none-30">
        <div class="col-xl-3 col-lg-5 col-md-5 mb-30">
                <div class="card b-radius--10 overflow-hidden box--shadow1">
                    <div class="card-body p-0">
                        <div class="p-3 bg--white">
                            <div class="">
                                <img src="{{ getImage(imagePath()['profile']['admin']['path'].'/'.$admin->image, null, true)}}" alt="@lang('Profile Image')" class="b-radius--10 w-100">
                            </div>
                            <h4 class="">{{$admin->name}}</h4>
                            <span class="text--small">@lang('Joined At') <strong>{{showDateTime($admin->created_at,'d M, Y h:i A')}}</strong></span>
                        </div>
                    </div>
                </div>

            <div class="card b-radius--10 overflow-hidden mt-30 box--shadow1">
                <div class="card-body">
                    <h5 class="mb-20 text-muted">@lang('Admin information')</h5>
                    <ul class="list-group">

                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            @lang('Username')
                            <span class="font-weight-bold">{{$admin->username}}</span>
                        </li>

                       
                    </ul>
                </div>
            </div>
           
        </div>

        
    </div>



   

@endsection
