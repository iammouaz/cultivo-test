@extends('admin.layouts.app')
@section('panel')
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <form action="{{ route('admin.userpermission.update', $userpermission->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="card-body">
                        <div class="payment-method-item">
                            <div class="payment-method-header">
                                <div class="content">
                                    <div class="row mb-none-15">
                                        <div class="col-sm-12 col-xl-4 col-lg-6 mb-15">
                                            <div class="form-group">
                                                <label class="w-100 font-weight-bold">@lang('Users') <span class="text-danger">*</span></label>
                                                <select  name="user_id" class="form-control" required >
                                                    @foreach ($users as $user)
                                                        <option value="{{ $user->id }}" {{ $userpermission->user_id == $user->id ? 'Selected':'' }}>{{ $user->firstname .' '. $user->lastname }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    <?php
                                            foreach($userpermission->event as $event){$events_selected[] = $event->id ;}
                                    ?> 
                                        <div class="col-sm-12 col-xl-4 col-lg-6">
                                            <div class="form-group events">
                                                <label class="font-weight-bold">@lang('Event') <span class="text-danger">*</span></label>
                                                <select id="events" name="events_id[]" class="form-control" required multiple>
                                                    @foreach ($events as $event)
                                                        <option value="{{ $event->id }}" <?php if(in_array($event->id,$events_selected)){echo 'Selected';} ?>>{{ $event->name }}</option>
                                                        <?php $id_name_event[$event->id]= $event->name;?>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <?php
                                            foreach($userpermission->products as $product){$products_selected[] = $product->id;}
                                        ?>
                                        <div class="col-sm-12 col-xl-4 col-lg-6 mb-15">
                                            <div class="form-group products">
                                                <label class="w-100 font-weight-bold">@lang('Product') <span class="text-danger">*</span></label>
                                                <select id="products" name="product_id[]" class="form-control" required multiple>
                                                    @foreach ($products as $product)
                                                        <option id="{{ $product->event_id }}" value="{{ $product->id }}" <?php if(in_array($product->id,$products_selected)){echo 'Selected';} ?>>{{'#_'.$id_name_event[$product->event_id] .'_# '.$product->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-sm-12 col-xl-4 col-lg-6 mb-15">
                                            <div class="form-group">
                                                <label class="w-100 font-weight-bold">@lang('Status') <span class="text-danger">*</span></label>
                                                <select name="status" class="form-control" required >
                                                        <option value="1" {{ $userpermission->status == 1 ? 'Selected':'' }}>Active</option>
                                                        <option value="0" {{ $userpermission->status == 0 ? 'Selected':'' }}>Pending</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn--primary btn-block">@lang('Submit')</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection


@push('breadcrumb-plugins')
    <a href="{{ route('admin.userpermission.index') }}" class="btn btn-sm btn--primary box--shadow1 text--small"><i class="la la-fw la-backward"></i> @lang('Go Back') </a>
@endpush

<!-- multiSelect style -->
@push('style')
    <style>
    .card-footer{
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

.multi-select-menuitem + .multi-select-menuitem {
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

@push('script-lib')
  <script src="{{ asset('assets/admin/js/vendor/datepicker.min.js') }}"></script>
  <script src="{{ asset('assets/admin/js/vendor/datepicker.en.js') }}"></script>
@endpush

<!-- multiSelect script -->
@push('script')
    <script>
    $(function(){
        $('#products').multiSelect();
        });
    $(function(){
        $('#users').multiSelect();
        });
    $(function(){
        $('#events').multiSelect();
    });
    </script>
@endpush
