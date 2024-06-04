@extends('admin.layouts.app')
@push('style-lib')
    <link rel="stylesheet" href="{{ asset('assets/admin/css/switch.css') }}">
@endpush

@php

    function transformArray($array)
    {
        foreach ($array as $key => &$value) {
            if ($value === 'true') {
                $value = true;
            } elseif ($value === 'false') {
                $value = false;
            }
            // No else needed, as we only want to modify 'true' or 'false' strings
        }
        return $array;
    }

    function valueRuterner($siteSettings, $name)
    {
        $defaultSettings = [
            'color' => 'rgba(248, 35, 35, 0)',
            'is_no_color' => true,
            'is_with_glass_effect' => false,
        ];

        return transformArray(json_decode($siteSettings[$name] ?? json_encode($defaultSettings), true));
    }

@endphp

@section('panel')
    <style>
        .gapped-6 {
            gap: 6px
        }

        .clear-margin-bottom {
            margin-bottom: 0px
        }


        .border-start {
            border-left: 1px solid gray;
            border-bottom-left-radius: 2%;
        }

        .timeline-bar {
            border: solid 1px rgb(206, 212, 218);
            width: 30px;
            height: 20px;
            border-bottom-left-radius: 5px;
            border-right: none;
            border-top: none
        }

        .add_nav_link_button svg path {
            fill: white !important;
            fill-opacity: 1 !important
        }

        .profilePicPreview {
            width: 210px;
            height: 210px;
            display: block;
            border: 3px solid #f1f1f1;
            box-shadow: 0 0 5px 0 rgba(0, 0, 0, 0.25);
            border-radius: 10px;
            background-size: cover;
            background-position: center;
            position: relative;
        }

        .font-small {
            font-size: 14px
        }

        .profilePicPreview input {
            display: none
        }

        .image-remove-button {
            position: absolute;
            top: 15px;
            right: 15px;
            cursor: pointer;
            display: none;

        }
    </style>

    <div class="row">
        <div class="col-lg-12">

            <form action="{{ route('admin.settings.site_settings.update') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="row">
                    <div class="col-lg-6 d-flex flex-column" style="gap: 12px">
                        <div class="card">
                            <div class="card-body">

                                <h6 class="fw-bolder mb-3">Site Settings</h6>
                                <div class="form-group">
                                    <label class="w-100 font-weight-bold">@lang('Site Title') </label>
                                    <input id="site_title" type="text" class="form-control "
                                        placeholder="@lang('Title')" name="site_title"
                                        value="{{ $siteSettings['site_title'] ?? old('site_title') }}" />
                                </div>





                                <div class="form-group">
                                    <label class="w-100 font-weight-bold">@lang('Site Description') </label>
                                    <textarea rows="5" id="site_description" type="text" class="form-control " placeholder="@lang('Description')"
                                        name="site_description">{{ $siteSettings['site_description'] ?? old('site_description') }}</textarea>
                                </div>


                                {{-- <div class="form-group">
                                    <label class="w-100 font-weight-bold">@lang('Preview') </label>
                                    <textarea disabled rows="5" id="site_description" type="text" class="form-control "
                                        placeholder="@lang('Preview')" name="site_preview" value="{{ old('site_preview') }}"></textarea>
                                </div> --}}

                            </div>
                        </div>


                        <div class="card">
                            <div class="card-body">
                                <div class="mb-3">
                                    <h6 class="fw-bolder ">Navbar links</h6>
                                    <p>Add links to your navbar</p>
                                </div>
                                <ul id="nav_rows_container">

                                </ul>
                                <button type="button"
                                    class="btn btn-primary mt-3 add_nav_link_button">@include('templates.basic.svgIcons.plus') Add
                                    Link</button>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary mt-2 w-100">Save</button>
                    </div>
                    <div class="col-lg-6 d-flex flex-column" style="gap: 12px">
                        <div class="card">
                            <div class="card-body">
                                <h6 class="fw-bolder mb-3">Site Images</h6>

                                <div style="display: inline-block" class="mb-5">
                                    <div class="mb-2 w-100">
                                        <h6 class="mb-2 fw-bolder">@lang('Favicon')</h6>
                                    </div>
                                    <div class="thumb mb-0 w-100 image-container" style="width: 250px !important;">
                                        <div class="avatar-preview w-100">
                                            <div class="profilePicPreview w-100 rounded"
                                                style="background-image: url({{ $siteSettings['favicon'] ?? null ? getImage(imagePath()['settings']['path'] . '/' . $siteSettings['favicon']) : asset('custom/images/images-placeholder.png') }});border:dashed 2px #AAAAAA;box-shadow:unset;height:150px">
                                                <div style="height: 0px;">
                                                    <input type="file" name="favicon" class="profilePicUpload"
                                                        id="favicon" accept=".png, .jpg, .jpeg" />
                                                    <label for="favicon"
                                                        style="position: absolute;top:70%;left:50%;transform:translate(-50%)">
                                                        <div class="btn  btn-light box--shadow1 text--small">
                                                            Select Image
                                                        </div>

                                                    </label>
                                                    <div class="image-remove-button">
                                                        @include('templates.basic.svgIcons.trash')
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="m-1 font-small text-center">Recommended size is 32 x 32 px.</div>
                                </div>




                                <div class="mb-3 w-100">
                                    <div class="mb-2 w-100">
                                        <h6 class="mb-1 fw-bolder">@lang('Social Image')
                                        </h6>
                                        <div class="mb-1">Appears when a link to this site is shared on social media
                                        </div>
                                    </div>

                                    <div class="thumb mb-0 w-100 image-container">
                                        <div class="avatar-preview w-100">
                                            <div class="profilePicPreview w-100 rounded"
                                                style="background-image: url({{ $siteSettings['social_image'] ?? null ? getImage(imagePath()['settings']['path'] . '/' . $siteSettings['social_image']) : asset('custom/images/images-placeholder.png') }});border:dashed 2px #AAAAAA;box-shadow:unset;">
                                                <div style="height: 0px;">
                                                    <input type="file" name="social_image" class="profilePicUpload"
                                                        id="social_image" accept=".png, .jpg, .jpeg" />
                                                    <label for="social_image"
                                                        style="position: absolute;top:70%;left:50%;transform:translate(-50%)">
                                                        <div class="btn  btn-light box--shadow1 text--small">
                                                            Select Image
                                                        </div>

                                                    </label>
                                                    <div class="image-remove-button">
                                                        @include('templates.basic.svgIcons.trash')
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="m-1 font-small text-center">Recommended size is 1200 x 630 px.</div>
                                </div>

                                {{-- <div>
                                    <div class="mb-2 w-100">
                                        <h6 class="mb-3 fw-bolder">
                                            @lang('Preview')
                                        </h6>

                                    </div>
                                    <div class="rounded border border-dark mb-3 w-100"
                                        style="background-repeat:no-repeat;background-size:cover;background-position:center;height:250px;background-image:url('{{ asset('custom/images/images-placeholder.png') }}')">
                                    </div>
                                </div> --}}



                                <div>
                                    <div class="mb-2 w-100">
                                        <h6 class="mb-3 fw-bolder">
                                            @lang('Log In & Registration Page Image')
                                        </h6>
                                        <div>This image appears as background in the Log In and Registration Pages
                                        </div>
                                    </div>
                                    <div class="thumb mb-0 w-100 image-container">
                                        <div class="avatar-preview w-100">
                                            <div class="profilePicPreview w-100 rounded"
                                                style="background-image: url({{ $siteSettings['login_image'] ?? null ? getImage(imagePath()['settings']['path'] . '/' . $siteSettings['login_image']) : asset('custom/images/images-placeholder.png') }});border:dashed 2px #AAAAAA;box-shadow:unset;">
                                                <div style="height: 0px;">
                                                    <input type="file" name="login_image" class="profilePicUpload"
                                                        id="login_image" accept=".png, .jpg, .jpeg" />
                                                    <label for="login_image"
                                                        style="position: absolute;top:70%;left:50%;transform:translate(-50%)">
                                                        <div class="btn  btn-light box--shadow1 text--small">
                                                            Select Image
                                                        </div>

                                                    </label>
                                                    <div class="image-remove-button">
                                                        @include('templates.basic.svgIcons.trash')
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="m-1 font-small text-center">Use images between 1500 pixels and 2500 pixels
                                        wide</div>
                                </div>


                                <div>
                                    <x-color-settings-panel title=""
                                        :itemList="[
                                            [
                                                'title' => 'Glass Color',
                                                'name' => 'login_form_background_color',
                                                'removeGlassEffect' => false,
                                                'value' => valueRuterner($siteSettings, 'login_form_background_color'),
                                            ],
                                        ]"></x-color-settings-panel>
                                </div>

                                <div>
                                    <p>Social Links</p>

                                    <div class="form-group">
                                        <label class="w-100 font-weight-bold">@lang('Facebook') </label>
                                        <input id="facebook_url" type="text" class="form-control "
                                            placeholder="@lang('Http://')" name="facebook_url"
                                            value="{{ $siteSettings['facebook_url'] ?? old('facebook_url') }}" />
                                    </div>

                                    <div class="form-group">
                                        <label class="w-100 font-weight-bold">@lang('Instagram') </label>
                                        <input id="instagram_url" type="text" class="form-control "
                                            placeholder="@lang('Http://')" name="instagram_url"
                                            value="{{ $siteSettings['instagram_url'] ?? old('instagram_url') }}" />
                                    </div>

                                    <div class="form-group">
                                        <label class="w-100 font-weight-bold">@lang('Twitter') </label>
                                        <input id="twitter_url" type="text" class="form-control "
                                            placeholder="@lang('Http://')" name="twitter_url"
                                            value="{{ $siteSettings['twitter_url'] ?? old('twitter_url') }}" />
                                    </div>

                                    <div class="form-group">
                                        <label class="w-100 font-weight-bold">@lang('Linkedin') </label>
                                        <input id="linkedin_url" type="text" class="form-control "
                                            placeholder="@lang('Http://')" name="linkedin_url"
                                            value="{{ $siteSettings['linkedin_url'] ?? old('linkedin_url') }}" />
                                    </div>

                                    <div class="form-group">
                                        <label class="w-100 font-weight-bold">@lang('Youtube') </label>
                                        <input id="youtube_url" type="text" class="form-control "
                                            placeholder="@lang('Http://')" name="youtube_url"
                                            value="{{ $siteSettings['youtube_url'] ?? old('youtube_url') }}" />
                                    </div>

                                    <div class="form-group">
                                        <label class="w-100 font-weight-bold">@lang('Vimeo') </label>
                                        <input id="vimeo_url" type="text" class="form-control "
                                            placeholder="@lang('Http://')" name="vimeo_url"
                                            value="{{ $siteSettings['vimeo_url'] ?? old('vimeo_url') }}" />
                                    </div>

                                    <div class="form-group">
                                        <label class="w-100 font-weight-bold">@lang('Email') </label>
                                        <input id="email" type="text" class="form-control "
                                            placeholder="@lang('Http://')" name="email"
                                            value="{{ $siteSettings['email'] ?? old('email') }}" />
                                    </div>
                                </div>

                                {{-- <div>
                                    <div class="mb-2 w-100">
                                        <h6 class="mb-3 fw-bolder">
                                            @lang('Preview')
                                        </h6>

                                    </div>
                                    <div class="rounded border border-dark mb-3 w-100"
                                        style="background-repeat:no-repeat;background-size:cover;background-position:center;height:250px;background-image:url('{{ asset('custom/images/images-placeholder.png') }}')">
                                    </div>
                                </div> --}}


                                <h6 class="fw-bolder mb-4">Footer</h6>
                                <p style="max-width: 430px " class="mb-5">Opt for either incorporating our template
                                    footer or
                                    personalize
                                    your design by uploading
                                    your own image.</p>

                                <div class="d-flex align-items-center justify-content-between mb-3">
                                    <div>Template Footer</div>
                                    <div class="slide-switch d-flex align-items-center mx-0">

                                        <label class="switch mb-0">
                                            <input {{ $siteSettings['is_template_footer'] ?? false ? 'checked' : '' }}
                                                id="template_footer" type="checkbox">

                                            <span class="slider round"></span>
                                        </label>
                                        <label class="switch-label px-2 mb-0" for="template_footer">Show</label>
                                        <input id="template_footer_hidden" name="is_template_footer" type="hidden"
                                            value="{{ $siteSettings['is_template_footer'] ?? 0 ? 1 : 0 }}" />
                                    </div>

                                </div>


                                <div>
                                    <p>Social Links</p>

                                    <div class="form-group">
                                        <label class="w-100 font-weight-bold">@lang('Facebook') </label>
                                        <input id="facebook_url" type="text" class="form-control "
                                            placeholder="@lang('Http://')" name="footer_facebook_link"
                                            value="{{ $siteSettings['footer_facebook_link'] ?? old('footer_facebook_link') }}" />
                                    </div>

                                    <div class="form-group">
                                        <label class="w-100 font-weight-bold">@lang('Instagram') </label>
                                        <input id="instagram_url" type="text" class="form-control "
                                            placeholder="@lang('Http://')" name="footer_instagram_link"
                                            value="{{ $siteSettings['footer_instagram_link'] ?? old('footer_instagram_link') }}" />
                                    </div>

                                    <div class="form-group">
                                        <label class="w-100 font-weight-bold">@lang('Twitter') </label>
                                        <input id="twitter_url" type="text" class="form-control "
                                            placeholder="@lang('Http://')" name="footer_twitter_link"
                                            value="{{ $siteSettings['footer_twitter_link'] ?? old('footer_twitter_link') }}" />
                                    </div>

                                    <div class="form-group">
                                        <label class="w-100 font-weight-bold">@lang('Linkedin') </label>
                                        <input id="linkedin_url" type="text" class="form-control "
                                            placeholder="@lang('Http://')" name="footer_linkedin_link"
                                            value="{{ $siteSettings['footer_linkedin_link'] ?? old('footer_linkedin_link') }}" />
                                    </div>

                                    <div class="form-group">
                                        <label class="w-100 font-weight-bold">@lang('Youtube') </label>
                                        <input id="youtube_url" type="text" class="form-control "
                                            placeholder="@lang('Http://')" name="footer_youtube_link"
                                            value="{{ $siteSettings['footer_youtube_link'] ?? old('footer_youtube_link') }}" />
                                    </div>

                                    <div class="form-group">
                                        <label class="w-100 font-weight-bold">@lang('Vimeo') </label>
                                        <input id="vimeo_url" type="text" class="form-control "
                                            placeholder="@lang('Http://')" name="footer_vimeo_link"
                                            value="{{ $siteSettings['footer_vimeo_link'] ?? old('footer_vimeo_link') }}" />
                                    </div>

                                    <div class="form-group">
                                        <label class="w-100 font-weight-bold">@lang('Email') </label>
                                        <input id="email_url" type="text" class="form-control "
                                            placeholder="@lang('Http://')" name="footer_email"
                                            value="{{ $siteSettings['footer_email'] ?? old('footer_email') }}" />
                                    </div>
                                </div>


                                <div style="width:180px !important;" class="mb-3">
                                    <div class="mb-2 w-100">
                                        <h6 class="mb-3 fw-bolder">
                                            @lang('Logo')
                                        </h6>
                                    </div>
                                    <div class="thumb mb-0 w-100 image-container">
                                        <div class="avatar-preview w-100">
                                            <div class="profilePicPreview w-100 rounded"
                                                style="background-image: url({{ $siteSettings['footer_logo'] ?? null ? getImage(imagePath()['settings']['path'] . '/' . $siteSettings['footer_logo']) : asset('custom/images/images-placeholder.png') }});border:dashed 2px #AAAAAA;box-shadow:unset;height:180px;">
                                                <div style="height: 0px;">
                                                    <input type="file" name="footer_logo" class="profilePicUpload"
                                                        id="footer_logo" accept=".png, .jpg, .jpeg" />
                                                    <label for="footer_logo"
                                                        style="position: absolute;top:70%;left:50%;transform:translate(-50%)">
                                                        <div class="btn  btn-light box--shadow1 text--small"
                                                            style="font-size: 9px !important">
                                                            Select Image
                                                        </div>

                                                    </label>
                                                    <div class="image-remove-button">
                                                        @include('templates.basic.svgIcons.trash')
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="m-1 font-small text-center">Max Width 300 pixels</div>
                                </div>

                                <div class="d-flex align-items-center justify-content-between mb-3">
                                    <div>Footer Image</div>
                                    <div class="slide-switch d-flex align-items-center mx-0">

                                        <label class="switch mb-0">
                                            <input {{ $siteSettings['is_footer_image'] ?? 0 ? 'checked' : '' }}
                                                id="footer_image" type="checkbox">

                                            <span class="slider round"></span>
                                        </label>
                                        <input id="footer_image_hidden" name="is_footer_image" type="hidden"
                                            value="{{ $siteSettings['is_footer_image'] ?? 0 ? 1 : 0 }}" />
                                        <label class="switch-label px-2 mb-0" for="footer_image">Show</label>
                                    </div>

                                </div>


                                <div class="mb-3">
                                    <div class="thumb mb-0 w-100 image-container">
                                        <div class="avatar-preview w-100">
                                            <div class="profilePicPreview w-100 rounded"
                                                style="background-image: url({{ $siteSettings['footer_image'] ?? null ? getImage(imagePath()['settings']['path'] . '/' . $siteSettings['footer_image']) : asset('custom/images/images-placeholder.png') }});border:dashed 2px #AAAAAA;box-shadow:unset;">
                                                <div style="height: 0px;">
                                                    <input type="file" name="footer_image" class="profilePicUpload"
                                                        id="footer_image_input" accept=".png, .jpg, .jpeg" />
                                                    <label for="footer_image_input"
                                                        style="position: absolute;top:70%;left:50%;transform:translate(-50%)">
                                                        <div class="btn  btn-light box--shadow1 text--small">
                                                            Select Image
                                                        </div>

                                                    </label>
                                                    <div class="image-remove-button">
                                                        @include('templates.basic.svgIcons.trash')
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="m-1 font-small text-center">Use images between 1500 pixels and 2500 pixels
                                        wide</div>
                                </div>

                                <div class="form-group">
                                    <label class="w-100 font-weight-bold">@lang('Add a link to this image') </label>
                                    <input id="footer_image_url" type="url" class="form-control "
                                        placeholder="@lang('Http://')" name="footer_image_link"
                                        value="{{ $siteSettings['footer_image_link'] ?? old('footer_image_link') }}" />
                                </div>

                            </div>
                        </div>
                    </div>
                </div>



            </form>

        </div>
    </div>
@endsection

@php

    $past_auctions_id_string_sequance = $siteSettings['past_auction_ids'] ?? null;
    $past_auction_id_list =
        isset($past_auctions_id_string_sequance) && $past_auctions_id_string_sequance
            ? explode(',', $siteSettings['past_auction_ids'])
            : [];

@endphp

@push('script')
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.2/Sortable.min.js"></script>
    <script>
        (function($) {

            let nav_items_index = 0;
            let sub_nav_items_index = 0

            // to update the navbar form with items just use this function with array of items that hold the following commented out shape
            handleAddHomePageLogo()
            handleAddNewAuctionLinkRow()
            handleAddPastAuctionLinkRow()

            @if (isset($siteSettings['navbar_links']))


                @php
                    // Decode the JSON string to a PHP array
                    $navbarLinksArray = json_decode($siteSettings['navbar_links'], true);

                    // Assuming you want to convert it to a simple indexed array
                    // This example just retrieves the values, but you might want to transform these values further depending on your structure and needs
                    $navbarLinksValues = is_array($navbarLinksArray) ? array_values($navbarLinksArray) : [];
                @endphp
                updateNavBarForm(@json($navbarLinksValues));
            @endif



            function isTrueBooleanOrStringifiedBoolean(status) {
                return status === true || status === "true" || status === "1" || status === 1 || status === "on"
            }


            function handleAddHomePageLogo() {

                const rowToAdd = `<li class="row mb-3" data-index="${nav_items_index}">
                        <div class="col-4">
                            <div class="form-group clear-margin-bottom">
                                <label class="w-100 font-weight-bold">@lang('Home Page Logo')</label>
                                <div class="d-flex gapped-6 align-items-center">



                                    <div class="thumb mb-0 w-100 image-container">
                                        <div class="avatar-preview w-100">
                                            <div class="profilePicPreview logo-preview w-100 rounded"
                                                style="background-image: url({{ $siteSettings['home_page_logo'] ?? null ? getImage(imagePath()['settings']['path'] . '/' . $siteSettings['home_page_logo']) : asset('custom/images/images-placeholder.png') }});border:dashed 2px #AAAAAA;box-shadow:unset;height:40px;">
                                                <div style="height: 0px;">
                                                    <input type="file" name="home_page_logo" class="profilePicUpload"
                                                        id="home_page_logo" accept=".png, .jpg, .jpeg" />
                                                        <div style="gap: 5px;" class="d-flex">
                                                            <label for="home_page_logo"
                                                                style="position: absolute;top:50%;left:50%;transform:translate(-50%,-50%)">
                                                            <div class="btn  btn-light box--shadow1 text--small"
                                                            style="font-size: 9px !important;white-space:nowrap">
                                                            Select Image
                                                        </div>
                                                        </label>
    </div>

                                                </div>
                                            </div>
                                        </div>
                                    </div>








                                </div>
                            </div>

                        </div>

                        <div class="col-8 url_input_container">
                            <div class="form-group clear-margin-bottom">
                                <label class="w-100 font-weight-bold">@lang('Url') </label>
                                <input id="url" type="url" class="form-control "
                                    placeholder="@lang('Url')" name="home_page_url" value="{{ $siteSettings['home_page_url'] ?? old('home_page_url') }}" />

                            </div>
                        </div>




                    </li>`

                const appendedRow = $("#nav_rows_container").append(rowToAdd);






            }


            function handleAddPastAuctionLinkRow() {
                const rowToAdd = `
                <li class="row mb-3" data-index="${nav_items_index}">
                    <div class="col-6 url_label_container">

                        <div class="form-group clear-margin-bottom">
                                                <label class="w-100 font-weight-bold">@lang('Past Auction Label')</label>
                                                <div class="d-flex gapped-6 align-items-center">
                                                    <span style="cursor: pointer" class="handle">
                                                        @include('templates.basic.svgIcons.drag')
                                                    </span>

                                                    <input id="label" type="text" class="form-control "
                                                        placeholder="@lang('Label')" name="past_auction_label" value="{{ $siteSettings['past_auction_label'] ?? old('past_auction_label') }}"
                                                        value=""  />





                                                </div>
                            </div>

                    </div>



                    <div class="col-6">
                                                <div class="form-group clear-margin-bottom">
                                                    <label class="w-100 font-weight-bold">@lang('Past Auction IDs')
                                                    </label>
                                                    <input type="hidden" name="past_auction_ids" value=""/>
                                                    <select multiple="multiple" class="past_auction_ids" >
                                                        @foreach (\App\Models\Event::all() as $auction)
                                                            <option value="{{ $auction->id }}">{{ $auction->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                    </div>

                </li>`

                const appendedRow = $("#nav_rows_container").append(rowToAdd);

                nav_items_index = nav_items_index + 1
            }


            function handleAddNewAuctionLinkRow() {

                const rowToAdd = `<li class="row mb-3" data-index="${nav_items_index}">
                                        <div class="col-4 url_label_container">
                                            <div class="form-group clear-margin-bottom">
                                                <label class="w-100 font-weight-bold">@lang('New Auction Label') <span
                                                        class="text-danger">*</span></label>
                                                <div class="d-flex gapped-6 align-items-center">
                                                    <span style="cursor: pointer" class="handle">
                                                        @include('templates.basic.svgIcons.drag')
                                                    </span>

                                                    <input required id="label" type="text" class="form-control "
                                                        placeholder="@lang('Label')" name="new_auction_label" value="{{ $siteSettings['new_auction_label'] ?? old('new_auction_label') }}"  />





                                                </div>
                                            </div>

                                        </div>
                                        <div class="col-8 url_input_container">
                                            <div class="form-group clear-margin-bottom">
                                                <label class="w-100 font-weight-bold">@lang('Auction ID') <span
                                                        class="text-danger">*</span></label>
                                                        <select name="new_auction_id" required class="form-select form-select-lg mb-3 w-100">
                                                        @foreach (\App\Models\Event::all() as $auction)
                                                            <option value="{{ $auction->id }}" {{ ($siteSettings['new_auction_id'] ?? null) == $auction->id ? 'selected' : '' }}>{{ $auction->name }}</option>
                                                        @endforeach

                                            </div>
                                        </div>
                                    </li>`

                const appendedRow = $("#nav_rows_container").append(rowToAdd);



                nav_items_index = nav_items_index + 1


            }






            function handleAddLink(label = '', url = '', is_menu = false, with_iframe = false) {

                const rowToAdd = `<li class="row main-item" data-index="${nav_items_index}">
                                        <div class="col-4 url_label_container">
                                            <div class="form-group clear-margin-bottom">
                                                <label class="w-100 font-weight-bold">@lang('Url Label') <span
                                                        class="text-danger">*</span></label>
                                                <div class="d-flex gapped-6 align-items-center">
                                                    <span style="cursor: pointer" class="handle">
                                                        @include('templates.basic.svgIcons.drag')
                                                    </span>

                                                    <input required id="label" type="text" class="form-control "
                                                        placeholder="@lang('Url Label')" name="navbar_links[${nav_items_index}][label]"
                                                        value="${label}"  />





                                                </div>
                                            </div>

                                        </div>
                                        <input class="is_menu_flag_input" type="hidden" name="navbar_links[${nav_items_index}][is_menu] value="${is_menu}"/>
                                        <div class="url_input_container col-4">

                                            <div class="form-group clear-margin-bottom">
                                                <label class="w-100 font-weight-bold">@lang('Url') <span
                                                        class="text-danger">*</span></label>
                                                <input required id="url" type="url" class="form-control "
                                                    placeholder="@lang('Url')" name="navbar_links[${nav_items_index}][url]" value="${url}"
                                                     />
                                            </div>

                                        </div>
                                        <div class="col-2 with_iframe_checkbox_container  d-flex align-items-end justif-content-center">
                                            <div data-toggle="tooltip" title="If checked, the current URL will be embedded in an iframe." data-placement="bottom" class="  d-flex align-items-end pb-2 justif-content-center" style="white-space:nowrap">
                                                <div class="form-check">
                                                <input class="form-check-input" type="checkbox" ${isTrueBooleanOrStringifiedBoolean(with_iframe) ? 'checked': ''} name="navbar_links[${nav_items_index}][with_iframe]" id="navbar_iframe_flag_${nav_items_index}">
                                                <label class="form-check-label" for="navbar_iframe_flag_${nav_items_index}">
                                                    IFrame
                                                </label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-2 d-flex align-items-end pb-2 justif-content-center" >
                                            <span class="remove-item" style="cursor: pointer;">
                                                @include('templates.basic.svgIcons.trash')
                                            </span>
                                        </div>
                                         <div class="col-12 create_dropdown_menu_button_container">
                                        <button type="button" class="btn btn-link mx-3 pb-0 mb-0 create_dropdown_menu_button">Create Dropdown Menu</button>
                                        </div>
                                        <div class="col-12 pl-5 mt-3 sub_menu_container">

                                        </div>

                                        <div class="col-12">
                                            <div class="col-12 p-0 mx-4 add_page_to_dropdown_button_container"></div>
                                        </div>



                                    </li>`

                const appendedRow = $("#nav_rows_container").append(rowToAdd);
                const old_nav_items_index = nav_items_index;
                nav_items_index = nav_items_index + 1
                var el = document.getElementById('nav_rows_container');
                var sortable = Sortable.create(el, {
                    handle: '.handle', // handle's class
                    animation: 150
                });

                return old_nav_items_index;

            }

            $("input[type='file']").change(function() {
                if ($(this).prop('files').length > 0) {
                    const container = $(this).closest('.image-container');
                    const removeButton = container.find('.image-remove-button');
                    removeButton.css('display', 'block');
                }

            })

            $(".image-remove-button").click(function() {
                const container = $(this).closest('.image-container');
                const profilePreviewContainer = container.find('.profilePicPreview');
                profilePreviewContainer.css('background-image',
                    "url('{{ asset('custom/images/images-placeholder.png') }}')");
                const fileInput = container.find('input[type="file"]');
                fileInput.replaceWith(fileInput
                    .val('').clone(true));
                $(this).css('display', 'none');

            })

            $('input[type="checkbox"]').on('click', function() {

                var container = $(this).closest('.slide-switch');
                container.find('input[type="hidden"]').val($(this).is(':checked') ? 1 : 0)

            })

            function handleAddSubMenuLinkByDataIndex(index, label = '', url = '', with_iframe = false) {

                const row = $(`[data-index="${index}"]`)

                const rowIndex = Number(row.attr('data-index'))

                const subRowToAdd = `<div class="row my-1 sub-menu-row">

                                                <div class="col-4 url_label_container">
                                                    <div class="d-flex">
                                                        <div class="timeline-bar"></div>
                                                        <input required id="url" type="text" class="form-control "
                                                            placeholder="@lang('Menu Item Label')"
                                                            name="navbar_links[${rowIndex}][menu_items][${sub_nav_items_index}][label]" value="${label}"
                                                             />
                                                    </div>
                                                </div>

                                                <div class="col-4">
                                                    <div class="form-group clear-margin-bottom">
                                                        <input required id="url" type="url" class="form-control "
                                                            placeholder="@lang('Url')"
                                                            name="navbar_links[${rowIndex}][menu_items][${sub_nav_items_index}][url]" value="${url}"
                                                             />
                                                    </div>
                                                </div>

                                        <div class="col-2 with_iframe_checkbox_container  d-flex align-items-end justif-content-center">
                                            <div data-toggle="tooltip" title="If checked, the current URL will be embedded in an iframe." data-placement="bottom" class="  d-flex align-items-end pb-2 justif-content-center" style="white-space:nowrap">
                                                <div class="form-check">
                                                <input class="form-check-input" type="checkbox" ${isTrueBooleanOrStringifiedBoolean(with_iframe) ? 'checked': ''} name="navbar_links[${rowIndex}][menu_items][${sub_nav_items_index}][with_iframe]" id="sub_navbar_iframe_flag_${nav_items_index}">
                                                <label class="form-check-label" for="with_iframe">
                                                    IFrame
                                                </label>
                                                </div>
                                            </div>
                                        </div>

                                                <div class=" col-2 d-flex align-items-center justif-content-center" >
                                                    <span class="remove-sub-item" style="cursor: pointer;">
                                                    @include('templates.basic.svgIcons.trash')
                                                    </span>
                                                </div>

                                            </div>`;

                row.find('.sub_menu_container').append(subRowToAdd);

                sub_nav_items_index = sub_nav_items_index + 1


                row.find(".create_dropdown_menu_button").remove()

            }

            function handleAddPageToDropdownButtonContainerByDataIndex(index) {
                const row = $(`[data-index="${index}"]`)
                const addPageToDropDownButton =
                    `<button type="button" class="btn btn-link mx-3 add_page_to_dropdown_button">Add Page To Dropdown</button>`
                row.find('.add_page_to_dropdown_button_container').append(
                    addPageToDropDownButton);
            }


            function handleAddDropdownMenuButtonToRowByDataIndex(index) {
                const row = $(`[data-index="${index}"]`);
                row.find(".create_dropdown_menu_button_container").html(
                    '<button type="button" class="btn btn-link mx-3 pb-0 mb-0 create_dropdown_menu_button">Create Dropdown Menu</button>'
                )

            }


            function handleRemovePageToDropdownButtonContainerByDataIndex(index) {
                const row = $(`[data-index="${index}"]`)
                row.find('.add_page_to_dropdown_button_container').html('')
            }


            function updateNavBarForm(nav_items = []) {
                nav_items.forEach(function(item) {
                    const index = handleAddLink(item.label, item.url, item.is_menu, item.with_iframe);
                    if (isTrueBooleanOrStringifiedBoolean(item.is_menu)) {
                        updateNavBarRowToDropDownStyleByDataIndex(index)
                        handleAddPageToDropdownButtonContainerByDataIndex(index);

                        (!Array.isArray(item.menu_items) ? Object.values(item.menu_items) : item
                            .menu_items).forEach(function(subItem) {
                            handleAddSubMenuLinkByDataIndex(index, subItem.label, subItem.url, subItem
                                .with_iframe);
                        })
                    }
                })
            }


            function updateNavBarRowToDropDownStyleByDataIndex(index) {
                const row = $(`[data-index="${index}"]`)
                row.find('.is_menu_flag_input').val(true)
                row.find('.url_input_container').html('');
                row.find('.url_label_container').addClass('col-10')
                row.find('.url_input_container').removeClass('col-4');
                row.find('.with_iframe_checkbox_container').removeClass('col-2');
                row.find(".with_iframe_checkbox_container").html('');


            }


            function updateNavBarRowToLinkStyleByDataIndex(index) {
                const row = $(`[data-index="${index}"]`);
                row.find('.is_menu_flag_input').val(false);
                row.find('.url_label_container').removeClass('col-10');
                row.find('.url_input_container').addClass('col-4');
                row.find('.url_input_container').html(`

                                            <div class="form-group clear-margin-bottom">
                                                <label class="w-100 font-weight-bold">@lang('Url') <span
                                                        class="text-danger">*</span></label>
                                                <input required id="url" type="url" class="form-control "
                                                    placeholder="@lang('Url')" name="navbar_links[${index}][url]" value=""
                                                     />
                                            </div>

                `);
                row.find('.with_iframe_checkbox_container').addClass('col-2');
                row.find(".with_iframe_checkbox_container").html(`
                <div data-toggle="tooltip" title="If checked, the current URL will be embedded in an iframe." data-placement="bottom" class="  d-flex align-items-end pb-2 justif-content-center" style="white-space:nowrap">
                    <div class="form-check">
                    <input class="form-check-input" type="checkbox"  name="navbar_links[${index}][with_iframe]" id="with_iframe">
                     <label class="form-check-label" for="with_iframe">
                        IFrame
                    </label>
                     </div>
                </div>
                `);

            }



            $('.add_nav_link_button').on('click', function() {
                handleAddLink()
            });

            $(document).on('click', '.create_dropdown_menu_button', function() {
                const row = $(this).closest('.row');

                const index = row.attr('data-index')

                updateNavBarRowToDropDownStyleByDataIndex(index)
                handleAddSubMenuLinkByDataIndex(index);
                handleAddPageToDropdownButtonContainerByDataIndex(index);

            })

            $(document).on('click', '.add_page_to_dropdown_button', function() {
                const row = $(this).closest('.row');

                const index = row.attr('data-index')

                handleAddSubMenuLinkByDataIndex(index);
            })

            $(document).on("click", ".remove-sub-item", function() {
                const index = $(this).closest('.main-item').attr('data-index')
                if ($(this).closest('.main-item').find('.sub-menu-row').length <= 1) {
                    updateNavBarRowToLinkStyleByDataIndex(index);
                    handleRemovePageToDropdownButtonContainerByDataIndex(index)
                    handleAddDropdownMenuButtonToRowByDataIndex(index)
                }
                $(this).closest('.row').remove();
            })

            $(document).on("click", ".remove-item", function() {
                $(this).closest('.row').remove()
            })


            $(document).ready(function() {
                $('.past_auction_ids').select2({
                    placeholder: "Select Past Auctions",
                    onChange: console.log
                });


                $('#footer_image').change(function() {
                    if ($(this).is(":checked")) {
                        $('#template_footer').prop('checked', false);
                        $('#template_footer_hidden').prop('value', 0);
                    }
                });

                $('#template_footer').change(function() {
                    if ($(this).is(":checked")) {
                        $('#footer_image').prop('checked', false);
                        $('#footer_image_hidden').prop('value', 0);

                    }
                });
                @if (isset($past_auctions_id_string_sequance) && $past_auctions_id_string_sequance && count($past_auction_id_list) > 0)

                    var pastAuctionIds = @json($past_auction_id_list);

                    $('.past_auction_ids').val(pastAuctionIds).trigger('change');
                    $('input[type="hidden"][name="past_auction_ids"]').val(
                        {{ $past_auctions_id_string_sequance }});
                @endif

                $('.past_auction_ids').on('change', function(e) {
                    // e.params.data contains the data for the selected option
                    var data = $(this).select2('data');
                    $(this).closest('.form-group').find('input[type="hidden"][name="past_auction_ids"]')
                        .val(data.map(item => item.id).join(","))
                    // Retrieves the data of the currently selected items
                    console.log("Selected value is: ", data[0].id);
                    console.log("Selected text is: ", data[0].text);


                });


            });

        })(jQuery);
    </script>
@endpush


@push('breadcrumb-plugins')
    <a href="{{ route('admin.event.index') }}" class="btn btn-sm btn--primary box--shadow1 text--small"><i
            class="la la-fw la-backward"></i> @lang('Go Back') </a>
@endpush
