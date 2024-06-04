@extends('admin.layouts.app')
@section('panel')
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <form action="{{ route('admin.offer.import-offer-from-csv') }}" method="POST"
                      enctype="multipart/form-data">
                    @csrf
                    <div class="card-body">

                        {{--                                <div class="form-group">--}}
                        {{--                                    <label class="font-weight-bold">@lang('Image') <span class="text-danger">*</span></label>--}}
                        {{--                                    <div class="thumb">--}}
                        {{--                                        <div class="avatar-preview">--}}
                        {{--                                            <div class="profilePicPreview" style="background-image: url('{{getImage(imagePath()['product']['path'],imagePath()['product']['size'])}}')"></div>--}}
                        {{--                                        </div>--}}
                        {{--                                        <div class="avatar-edit">--}}
                        {{--                                            <input type="file" name="image" class="profilePicUpload" id="image" accept=".png, .jpg, .jpeg"/>--}}
                        {{--                                            <label for="image" class="bg--primary"><i class="la la-pencil"></i></label>--}}
                        {{--                                        </div>--}}
                        {{--                                    </div>--}}
                        {{--                                </div>--}}
                        <input type="hidden" name="uuid" id="uuid" value="{{Str::uuid()}}">
                        <input type="file" name="csv_file" id="csv_file" required>

                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn--primary btn-block"
                                style="margin-top:-15px;">@lang('Submit')</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection


@push('breadcrumb-plugins')

    <a href="{{ route('admin.offer.template.download') }}" class="btn btn-sm btn--primary box--shadow1 text--small"><i
            class="la la-fw la-download"></i> @lang('Download Template') </a>
    <a href="{{ route('admin.offer.index') }}" class="btn btn-sm btn--primary box--shadow1 text--small"><i
            class="la la-fw la-backward"></i> @lang('Go Back') </a>
@endpush


