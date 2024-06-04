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
    <div class="row">
        <div class="col-lg-12">

            <form action="" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="row">
                    <div class="col-lg-6 d-flex flex-column" style="gap: 12px">
                        <div class="card">
                            <div class="card-body">
                                <h6 class="fw-bolder mb-3">Global Text Styles</h6>
                                <div>
                                    <x-font-settings-panel title="Headings" :itemList="[
                                        [
                                            'title' => 'Family',
                                            'name' => 'head_family',
                                            'value' => getFontUrlSettingValue('head_family') ?? '',
                                            'stylevalue' => $siteSettings['head_style'] ?? '',
                                            'letterspacingvalue' => $siteSettings['head_letter_spacing'] ?? '',
                                            'texttransformvalue' => $siteSettings['head_text_transform'] ?? '',
                                            'style' => 'head_style',
                                            'letterspacing' => 'head_letter_spacing',
                                            'texttransform' => 'head_text_transform',
                                        ],
                                    ]"></x-font-settings-panel>

                                    <x-font-settings-panel title="Paragraphs" :itemList="[
                                        [
                                            'title' => 'Family',
                                            'name' => 'paragraph_family',
                                            'value' => getFontUrlSettingValue('paragraph_family') ?? '',
                                            'stylevalue' => $siteSettings['paragraph_style'] ?? '',
                                            'letterspacingvalue' => $siteSettings['paragraph_letter_spacing'] ?? '',
                                            'texttransformvalue' => $siteSettings['paragraph_text_transform'] ?? '',
                                            'style' => 'paragraph_style',
                                            'letterspacing' => 'paragraph_letter_spacing',
                                            'texttransform' => 'paragraph_text_transform',
                                        ],
                                    ]"></x-font-settings-panel>

                                    <x-font-settings-panel title="Auction Card Numbers"
                                        :itemList="[
                                            [
                                                'title' => 'Family',
                                                'name' => 'card_family',
                                                'value' => getFontUrlSettingValue('card_family') ?? '',
                                                'stylevalue' => $siteSettings['card_style'] ?? '',
                                                'letterspacingvalue' => $siteSettings['card_letter_spacing'] ?? '',
                                                'texttransformvalue' => $siteSettings['card_text_transform'] ?? '',
                                                'style' => 'card_style',
                                                'letterspacing' => 'card_letter_spacing',
                                                'texttransform' => 'card_text_transform',
                                            ],
                                        ]"></x-font-settings-panel>



                                </div>
                            </div>
                        </div>

                        <div class="card">
                            <div class="card-body">
                                <h6 class="fw-bolder mb-3">Component Styles</h6>
                                <div>
                                    <x-font-settings-panel title="Buttons" type='button'
                                        :itemList="[
                                            [
                                                'title' => 'Family',
                                                'name' => 'button_family',
                                                'value' => getFontUrlSettingValue('button_family') ?? '',
                                                'stylevalue' => $siteSettings['button_style'] ?? '',
                                                'letterspacingvalue' => $siteSettings['button_letter_spacing'] ?? '',
                                                'texttransformvalue' => $siteSettings['button_text_transform'] ?? '',
                                                'style' => 'button_style',
                                                'letterspacing' => 'button_letter_spacing',
                                                'texttransform' => 'button_text_transform',
                                                'custom_corners' => $siteSettings['custom_corners'] ?? '',
                                                'is_custom_corners' => $siteSettings['is_custom_corners'] ?? '',
                                                'is_custom_corners_name' => 'is_custom_corners',
                                                'custom_corner_name' => 'custom_corners',
                                            ],

                                            [
                                                'title' => 'Family',
                                                'name' => 'outlined_button_family',
                                                'value' => getFontUrlSettingValue('outlined_button_family') ?? '',
                                                'stylevalue' => $siteSettings['outlined_button_style'] ?? '',
                                                'letterspacingvalue' =>
                                                    $siteSettings['outlined_button_letter_spacing'] ?? '',
                                                'texttransformvalue' =>
                                                    $siteSettings['outlined_button_text_transform'] ?? '',
                                                'style' => 'outlined_button_style',
                                                'letterspacing' => 'outlined_button_letter_spacing',
                                                'texttransform' => 'outlined_button_text_transform',
                                                'custom_corners' => $siteSettings['outlined_custom_corners'] ?? '',
                                                'is_custom_corners' =>
                                                    $siteSettings['outlined_is_custom_corners'] ?? '',
                                                'is_custom_corners_name' => 'outlined_is_custom_corners',
                                                'custom_corner_name' => 'outlined_custom_corners',
                                            ],
                                            [
                                                'title' => 'Family',
                                                'name' => 'text_button_family',
                                                'value' => getFontUrlSettingValue('text_button_family') ?? '',
                                                'stylevalue' => $siteSettings['text_button_style'] ?? '',
                                                'letterspacingvalue' =>
                                                    $siteSettings['text_button_letter_spacing'] ?? '',
                                                'texttransformvalue' =>
                                                    $siteSettings['text_button_text_transform'] ?? '',
                                                'style' => 'text_button_style',
                                                'letterspacing' => 'text_button_letter_spacing',
                                                'texttransform' => 'text_button_text_transform',
                                                'custom_corners' => $siteSettings['text_custom_corners'] ?? '',
                                                'is_custom_corners' => $siteSettings['text_is_custom_corners'] ?? '',
                                                'is_custom_corners_name' => 'text_is_custom_corners',
                                                'custom_corner_name' => 'text_custom_corners',
                                            ],
                                        ]"></x-font-settings-panel>
                                </div>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary mt-2 w-100">Save</button>
                    </div>
                    <div class="col-lg-3 d-flex flex-column">
                        <div class="card">
                            <div class="card-body">
                                <h6 class="fw-bolder mb-3">Global Color Styles</h6>
                                <div class="fw-bolder mb-3">Customize Theme</div>



                                <div>
                                    <x-color-settings-panel title="Navbar" :itemList="[
                                        [
                                            'title' => 'Background',
                                            'name' => 'nav_background_color',
                                            'removeGlassEffect' => false,
                                            'value' => valueRuterner($siteSettings, 'nav_background_color'),
                                        ],
                                        [
                                            'title' => 'Nav Links',
                                            'name' => 'nav_links_color',
                                            'removeGlassEffect' => false,
                                            'value' => valueRuterner($siteSettings, 'nav_links_color'),
                                        ],
                                        [
                                            'title' => 'Nav Icons',
                                            'name' => 'nav_icons_color',
                                            'removeGlassEffect' => false,
                                            'value' => valueRuterner($siteSettings, 'nav_icons_color'),
                                        ],
                                        [
                                            'title' => 'Hover',
                                            'name' => 'nav_hover_color',
                                            'removeGlassEffect' => false,
                                            'value' => valueRuterner($siteSettings, 'nav_hover_color'),
                                        ],
                                    ]"></x-color-settings-panel>
                                </div>


                                <div>
                                    <x-color-settings-panel title="Footer" :itemList="[
                                        [
                                            'title' => 'Background',
                                            'name' => 'footer_background_color',
                                            'removeGlassEffect' => false,
                                            'value' => valueRuterner($siteSettings, 'footer_background_color'),
                                        ],
                                        [
                                            'title' => 'Links',
                                            'name' => 'footer_links_color',
                                            'removeGlassEffect' => false,
                                            'value' => valueRuterner($siteSettings, 'footer_links_color'),
                                        ],
                                        [
                                            'title' => 'Social Icons',
                                            'name' => 'footer_icons_color',
                                            'removeGlassEffect' => false,
                                            'value' => valueRuterner($siteSettings, 'footer_icons_color'),
                                        ],
                                        [
                                            'title' => 'Hover',
                                            'name' => 'footer_hover_color',
                                            'removeGlassEffect' => false,
                                            'value' => valueRuterner($siteSettings, 'footer_hover_color'),
                                        ],
                                    ]"></x-color-settings-panel>
                                </div>


                                <div>
                                    <x-color-settings-panel title="Sitewide" :itemList="[
                                        [
                                            'title' => 'Page Background',
                                            'name' => 'page_background_color',
                                            'removeGlassEffect' => false,
                                            'value' => valueRuterner($siteSettings, 'page_background_color'),
                                        ],
                                    ]"></x-color-settings-panel>
                                </div>



                                <div>
                                    <x-color-settings-panel title="Text" :itemList="[
                                        [
                                            'title' => 'Heading 1',
                                            'name' => 'text_h1_color',
                                            'removeGlassEffect' => false,
                                            'value' => valueRuterner($siteSettings, 'text_h1_color'),
                                        ],
                                        [
                                            'title' => 'Heading 2',
                                            'name' => 'text_h2_color',
                                            'removeGlassEffect' => false,
                                            'value' => valueRuterner($siteSettings, 'text_h2_color'),
                                        ],
                                        [
                                            'title' => 'Heading 3',
                                            'name' => 'text_h3_color',
                                            'removeGlassEffect' => false,
                                            'value' => valueRuterner($siteSettings, 'text_h3_color'),
                                        ],
                                        [
                                            'title' => 'Heading 4',
                                            'name' => 'text_h4_color',
                                            'removeGlassEffect' => false,
                                            'value' => valueRuterner($siteSettings, 'text_h4_color'),
                                        ],
                                        [
                                            'title' => 'Heading 5',
                                            'name' => 'text_h5_color',
                                            'removeGlassEffect' => false,
                                            'value' => valueRuterner($siteSettings, 'text_h5_color'),
                                        ],
                                        [
                                            'title' => 'Subtitle',
                                            'name' => 'text_subtitle_1_color',
                                            'removeGlassEffect' => false,
                                            'value' => valueRuterner($siteSettings, 'text_subtitle_1_color'),
                                        ],
                                        [
                                            'title' => 'Subtitle 1',
                                            'name' => 'text_subtitle_1_color',
                                            'removeGlassEffect' => false,
                                            'value' => valueRuterner($siteSettings, 'text_subtitle_1_color'),
                                        ],
                                        [
                                            'title' => 'Subtitle 2',
                                            'name' => 'text_subtitle_2_color',
                                            'removeGlassEffect' => false,
                                            'value' => valueRuterner($siteSettings, 'text_subtitle_2_color'),
                                        ],
                                        [
                                            'title' => 'Body 1',
                                            'name' => 'text_body_1_color',
                                            'removeGlassEffect' => false,
                                            'value' => valueRuterner($siteSettings, 'text_body_1_color'),
                                        ],
                                        [
                                            'title' => 'Body 2',
                                            'name' => 'text_body_2_color',
                                            'removeGlassEffect' => false,
                                            'value' => valueRuterner($siteSettings, 'text_body_2_color'),
                                        ],
                                        [
                                            'title' => 'Caption',
                                            'name' => 'text_caption_color',
                                            'removeGlassEffect' => false,
                                            'value' => valueRuterner($siteSettings, 'text_caption_color'),
                                        ],
                                        [
                                            'title' => 'Overline',
                                            'name' => 'text_overline_color',
                                            'removeGlassEffect' => false,
                                            'value' => valueRuterner($siteSettings, 'text_overline_color'),
                                        ],
                                        [
                                            'title' => 'Text Heighlight',
                                            'name' => 'text_highlight_color',
                                            'removeGlassEffect' => false,
                                            'value' => valueRuterner($siteSettings, 'text_highlight_color'),
                                        ],
                                    ]"></x-color-settings-panel>
                                </div>


                                <div>
                                    <x-color-settings-panel title="Links" :itemList="[
                                        [
                                            'title' => 'Link On Dark Background',
                                            'name' => 'links_on_dark_background_color',
                                            'removeGlassEffect' => false,
                                            'value' => valueRuterner($siteSettings, 'links_on_dark_background_color'),
                                        ],
                                        [
                                            'title' => 'Text Link',
                                            'name' => 'links_color',
                                            'removeGlassEffect' => false,
                                            'value' => valueRuterner($siteSettings, 'links_color'),
                                        ],
                                    ]"></x-color-settings-panel>
                                </div>

                                <div>
                                    <x-color-settings-panel title="Buttons" :itemList="[
                                        [
                                            'title' => 'Button Background',
                                            'name' => 'button_background_color',
                                            'removeGlassEffect' => false,
                                            'value' => valueRuterner($siteSettings, 'button_background_color'),
                                            'show_contained_button' => true,
                                        ],
                                        [
                                            'title' => 'Button Text',
                                            'name' => 'button_text_color',
                                            'value' => valueRuterner($siteSettings, 'button_text_color'),
                                            'removeGlassEffect' => true,
                                        ],
                                        [
                                            'title' => 'Hover Color',
                                            'name' => 'button_hover_background_color',
                                            'removeGlassEffect' => false,
                                            'value' => valueRuterner($siteSettings, 'button_hover_background_color'),
                                        ],
                                        [
                                            'title' => 'Button Background',
                                            'name' => 'secondary_button_background_color',
                                            'removeGlassEffect' => true,
                                            'value' => valueRuterner(
                                                $siteSettings,
                                                'secondary_button_background_color',
                                            ),
                                            'show_outlined_button' => true,
                                        ],
                                        [
                                            'title' => 'Button Text',
                                            'name' => 'secondary_button_text_color',
                                            'removeGlassEffect' => true,
                                            'value' => valueRuterner($siteSettings, 'secondary_button_text_color'),
                                        ],
                                        [
                                            'title' => 'Hover Color',
                                            'name' => 'secondary_button_hover_background_color',
                                            'removeGlassEffect' => true,
                                            'value' => valueRuterner(
                                                $siteSettings,
                                                'secondary_button_hover_background_color',
                                            ),
                                        ],
                                        [
                                            'title' => 'Button Text',
                                            'name' => 'secondary_text_color',
                                            'removeGlassEffect' => true,
                                            'value' => valueRuterner($siteSettings, 'secondary_text_color'),
                                            'show_text_button' => true,
                                        ],
                                        [
                                            'title' => 'Hover Color',
                                            'name' => 'secondary_hover_text_color',
                                            'removeGlassEffect' => true,
                                            'value' => valueRuterner($siteSettings, 'secondary_hover_text_color'),
                                        ],
                                    ]"></x-color-settings-panel>
                                </div>


                                {{-- <div>
                                    <x-color-settings-panel title="Text" :itemList="[
                                        [
                                            'title' => 'Button Text',
                                            'name' => 'secondary_button_text_color',
                                            'value' => [
                                                'color' => 'rgba(248, 35, 35, 0)',
                                                'is_no_color' => true,
                                                'is_with_glass_effect' => false,
                                            ],
                                        ],
                                        [
                                            'title' => 'Hover Color',
                                            'name' => 'secondary_hover_text_color',
                                            'value' => [
                                                'color' => 'rgba(248, 35, 35, 0)',
                                                'is_no_color' => true,
                                                'is_with_glass_effect' => false,
                                            ],
                                        ],
                                    ]"></x-color-settings-panel>
                                </div> --}}


                                <div>
                                    <x-color-settings-panel title="Icons" :itemList="[
                                        [
                                            'title' => 'Icon',
                                            'name' => 'icon_color',
                                            'removeGlassEffect' => false,
                                            'value' => valueRuterner($siteSettings, 'icon_color'),
                                        ],
                                        [
                                            'title' => 'Hover/Active',
                                            'name' => 'icon_hover_active_color',
                                            'removeGlassEffect' => false,
                                            'value' => valueRuterner($siteSettings, 'icon_hover_active_color'),
                                        ],
                                        [
                                            'title' => 'Hover Background',
                                            'name' => 'icon_hover_background_color',
                                            'removeGlassEffect' => false,
                                            'value' => valueRuterner($siteSettings, 'icon_hover_background_color'),
                                        ],
                                    ]"></x-color-settings-panel>
                                </div>


                                <div>
                                    <x-color-settings-panel title="Chips" :itemList="[
                                        [
                                            'title' => 'Text & Icon',
                                            'name' => 'chip_text_and_icon_color',
                                            'removeGlassEffect' => false,
                                            'value' => valueRuterner($siteSettings, 'chip_text_and_icon_color'),
                                        ],
                                        [
                                            'title' => 'Background',
                                            'name' => 'chip_background_color',
                                            'removeGlassEffect' => false,
                                            'value' => valueRuterner($siteSettings, 'chip_background_color'),
                                        ],
                                    ]"></x-color-settings-panel>
                                </div>


                                <div>
                                    <x-color-settings-panel title="Checkboxes & Radio Buttons"
                                        :itemList="[
                                            [
                                                'title' => 'Active',
                                                'name' => 'checkbox_and_radio_active_color',
                                                'removeGlassEffect' => false,
                                                'value' => valueRuterner($siteSettings, 'checkbox_and_radio_active_color'),
                                            ],
                                        ]"></x-color-settings-panel>
                                </div>



                                <div>
                                    <x-color-settings-panel title="Tabs" :itemList="[
                                        [
                                            'title' => 'Hover',
                                            'name' => 'tabs_hover_color',
                                            'removeGlassEffect' => false,
                                            'value' => valueRuterner($siteSettings, 'tabs_hover_color'),
                                        ],
                                        [
                                            'title' => 'Active',
                                            'name' => 'tabs_active_color',
                                            'removeGlassEffect' => false,
                                            'value' => valueRuterner($siteSettings, 'tabs_active_color'),
                                        ],
                                    ]"></x-color-settings-panel>
                                </div>

                                <div>
                                    <x-color-settings-panel title="Progress Components"
                                        :itemList="[
                                            [
                                                'title' => 'Budget Progress Bar',
                                                'name' => 'budget_progress_bar_color',
                                                'removeGlassEffect' => true,
                                                'value' => valueRuterner($siteSettings, 'budget_progress_bar_color'),
                                            ],
                                        ]"></x-color-settings-panel>
                                </div>






                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection



@push('script')
    <script>
        (function($) {

            $('.colorPanelToggleButton').on('click', function() {
                const container = $(this).closest('.colorPanelContainer')
                container.find('.colorPanel').collapse('toggle')
                container.find('.chevron-button').toggleClass('rotate-180')
            });






        })(jQuery);
    </script>
@endpush
