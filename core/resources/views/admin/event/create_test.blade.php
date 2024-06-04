@extends('admin.layouts.app')
@section('panel')
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <form action="{{ route('admin.event.store_test') }}" method="POST" enctype="multipart/form-data" id="test_form">
                @csrf
                <div class="card-body">
                    <div class="payment-method-item">
                        <div class="payment-method-header addregionhere">
                            <div class="col-lg-12">
                                <div class="row mb-none-15">
                                    <div class="col-sm-12 col-xl-3 col-lg-3 mb-15">
                                        <div class="form-group">
                                            <label class="w-100 font-weight-bold">@lang('Name') <span
                                                    class="text-danger">*</span></label>
                                            <input type="text" class="form-control " placeholder="@lang('Event Name')"
                                                name="name" value="{{ old('name') }}" required />
                                        </div>
                                    </div>
                                    <div class="col-sm-12 col-xl-3 col-lg-3 mb-15">
                                        <div class="form-group">
                                            <label class="w-100 font-weight-bold">@lang('Products Count') <span
                                                    class="text-danger">*</span></label>
                                            <input type="number" class="form-control"
                                                placeholder="@lang('Products Count')" name="products_count"
                                                value="{{ old('products_count') }}" min="1" required />
                                        </div>
                                    </div>
                                    <div class="col-sm-12 col-xl-3 col-lg-3 mb-15">
                                        <div class="form-group">
                                            <label class="w-100 font-weight-bold">@lang("User Fields' Filter")</label>
                                            <input type="text" class="form-control" id="search"
                                                placeholder="@lang('User Name')" value="{{ $search ?? '' }}"/>
                                        </div>
                                    </div>
                                    <div class="col-sm-12 col-xl-3 col-lg-3 mt-30">
                                        <div class="form-group">
                                            <button type="submit"
                                                class="btn btn--primary btn-block">@lang('Create Test Event')</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="table-responsive--md  table-responsive">
                                        <table class="table table--light style--two">
                                            <thead>
                                                <tr>
                                                    <th>@lang('User Name')</th>
                                                    <th>@lang('Full Name')</th>
                                                    <th>@lang('User Email')</th>
                                                    <th>@lang('Company Name')</th>
                                                    <th>@lang('Select')</th>
                                                </tr>
                                            </thead>
                                            <tbody id="users">
                                                @forelse($users as $user)
                                                <tr>
                                                    <td data-label="@lang('User Name')">{{ $user->username }}</td>
                                                    <td data-label="@lang('User FullName')">{{ $user->fullname }}</td>
                                                    <td data-label="@lang('User Email')">{{ $user->email }}</td>
                                                    <td data-label="@lang('Company Name')">{{ $user->company_name }}</td>

                                                    <td data-label="@lang('Select')">
                                                        <input type="checkbox" name="users[]"
                                                            value="{{$user->id}}" class="user_select">
                                                    </td>
                                                </tr>

                                                @empty
                                                <tr>
                                                    <td class="text-muted text-center" colspan="100%">
                                                        {{ $emptyMessage}}</td>
                                                </tr>
                                                @endforelse
                                            </tbody>
                                        </table><!-- table end -->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                {{-- <div class="card-footer py-4">
                    {{ paginateLinks($users) }}
                </div> --}}
            </form>
        </div>
    </div>
</div>

@endsection


@push('breadcrumb-plugins')
<div class="d-flex flex-wrap justify-content-sm-end header-search-wrapper">
    {{-- <form class="header-search-form" style="margin-right:5px;">
        <div class="input-group has_append">
            <input type="text" id="search" class="form-control bg-white text--black" placeholder="@lang('User Name')" value="{{ $search ?? '' }}"> --}}
            {{-- <div class="input-group-append">
                <button class="btn btn--primary" type="submit"><i class="fa fa-search"></i></button>
            </div> --}}
        {{-- </div>
    </form> --}}
<a href="{{ route('admin.event.index') }}" class="btn btn--primary box--shadow1 text--small"><i
        class="la la-fw la-backward"></i> @lang('Go Back') </a>
</div>
@endpush

@push('style')
<style>
    .payment-method-item .payment-method-header .thumb .avatar-edit {
        bottom: auto;
        top: 175px;
    }
</style>
@endpush

@push('script-lib')
<script src="{{ asset('assets/admin/js/vendor/datepicker.min.js') }}"></script>
<script src="{{ asset('assets/admin/js/vendor/datepicker.en.js') }}"></script>
<script>
    $(document).ready(function(){
      $("#search").on("keyup", function() {
        var value = $(this).val().toLowerCase();
        $("#users tr").filter(function() {
          $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
        });
      });
      $('#test_form').submit(function (e) {
        //check atleat 1 checkbox is checked
        if (!$('.user_select').is(':checked')) {
            //prevent the default form submit if it is not checked
            iziToast.error({
                message: 'Please Select at least one user',
                position: "topRight"
            });
            e.preventDefault();
        }
    })
    });
</script>
@endpush



<!-- multiSelect style -->
@push('style')
<style>
    .card-footer {
        margin-top: 100px;
    }

    .multi-select-container {
        display: block;
        position: relative;
    }

    .multi-select-menu {
        position: absolute;
        left: 0;
        top: 0.8em;
        z-index: 1;
        float: left;
        min-width: 100%;
        background: #fff;
        margin: 1em 0;
        border: 1px solid #aaa;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.2);
        display: none;
        max-height: 200px;
        overflow-y: overlay;
    }

    .multi-select-menuitem {
        display: block;
        font-size: 0.875em;
        padding: 0.6em 1em 0.6em 30px;
        white-space: nowrap;
    }

    .multi-select-menuitem--titled:before {
        display: block;
        font-weight: bold;
        content: attr(data-group-title);
        margin: 0 0 0.25em -20px;
    }

    .multi-select-menuitem--titledsr:before {
        display: block;
        font-weight: bold;
        content: attr(data-group-title);
        border: 0;
        clip: rect(0 0 0 0);
        height: 1px;
        margin: -1px;
        overflow: hidden;
        padding: 0;
        position: absolute;
        width: 1px;
    }

    .multi-select-menuitem+.multi-select-menuitem {
        padding-top: 0;
    }

    .multi-select-presets {
        border-bottom: 1px solid #ddd;
    }

    .multi-select-menuitem input {
        position: absolute;
        margin-top: 0.25em;
        margin-left: -20px;
    }

    .multi-select-button {
        display: inline-block;
        font-size: 0.875em;
        padding: 0.2em 0.6em;
        max-width: 16em;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        vertical-align: -0.5em;
        background-color: #fff;
        border: 1px solid #aaa;
        border-radius: 4px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.2);
        cursor: default;
    }

    .multi-select-button:after {
        content: "";
        display: inline-block;
        width: 0;
        height: 0;
        border-style: solid;
        border-width: 0.4em 0.4em 0 0.4em;
        border-color: #999 transparent transparent transparent;
        margin-left: 0.4em;
        vertical-align: 0.1em;
    }

    .multi-select-container--open .multi-select-menu {
        display: block;
    }

    .multi-select-container--open .multi-select-button:after {
        border-width: 0 0.4em 0.4em 0.4em;
        border-color: transparent transparent #999 transparent;
    }

    .multi-select-container--positioned .multi-select-menu {
        /* Avoid border/padding on menu messing with JavaScript width calculation */
        box-sizing: border-box;
    }

    .multi-select-container--positioned .multi-select-menu label {
        /* Allow labels to line wrap when menu is artificially narrowed */
        white-space: normal;
    }
</style>
@endpush