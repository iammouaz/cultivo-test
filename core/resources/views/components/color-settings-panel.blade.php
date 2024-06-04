<style>
    .rotate-180 {
        transform: rotate(180deg)
    }

    .outlined-button {
        border: 1px solid black;
        text-align: center;
        padding: 8px 16px;
        cursor: pointer;

    }

    .contained-button {
        cursor: pointer;
        padding: 8px 16px;

    }

    .text-button {
        cursor: pointer;
        padding: 8px 16px;
    }
</style>

<div class="colorPanelContainer">
    <div class="">
        <div class="card-header" id="headingOne">
            <h5 class="mb-0 d-flex align-items-center justify-content-between">

                <button type="button" class="btn colorPanelToggleButton">
                    {{ $title }}
                </button>

                <button type="button" class="btn chevron-button  colorPanelToggleButton">
                    @include('templates.basic.svgIcons.chevron-down')
                </button>

            </h5>
        </div>

        <div class="collapse show colorPanel" aria-labelledby="headingOne">
            <div class="card-body">
                <div class="row" style="gap: 20px">
                    @foreach ($itemList as $colorItem)
                        @if (isset($colorItem['show_contained_button']) && $colorItem['show_contained_button'])
                            <div class="contained-button mx-auto">CONTAINED</div>
                        @endif
                        @if (isset($colorItem['show_outlined_button']) && $colorItem['show_outlined_button'])
                            <div class="outlined-button mx-auto">OUTLINED</div>
                        @endif

                        @if (isset($colorItem['show_text_button']) && $colorItem['show_text_button'])
                            <div class="text-button mx-auto">TEXT</div>
                        @endif
                        <div class="col-12">
                            <x-color-picker :removeGlassEffect="$colorItem['removeGlassEffect']" :value="$colorItem['value']" title="{{ $colorItem['title'] }}"
                                name="{{ $colorItem['name'] }}"></x-color-picker>

                        </div>
                    @endforeach



                </div>
            </div>
        </div>
    </div>

</div>

@push('script')
    <script>
        if (!window.loadedScripts.includes('button-color-picker-script')) {
            (function($) {

                // contained normal case
                $('input[name="button_background_color[color]"]').on('change', function() {
                    const containedButton = $(".contained-button");


                    const container = containedButton.parent();

                    const isGlassEffectColor = container.find(
                        'input[name="button_background_color[is_with_glass_effect]"]').val();

                    const colorValue = $(this).val()


                    if (isGlassEffectColor !== "true")
                        containedButton.css("background-color", $(this).val());
                    else
                        addGlassEffect(rgbaStringToObject(colorValue), containedButton)

                });

                $('input[name="button_background_color[is_no_color]').on("change", function() {
                    const containedButton = $(".contained-button");

                    const container = containedButton.parent();

                    const colorValue = container.find('input[name="button_background_color[color]"]').val();

                    const isNoColor = container.find('input[name="button_background_color[is_no_color]"]')
                        .val();

                    removeGlassEffect(containedButton, colorValue, isNoColor);
                })


                $('input[name="button_background_color[is_with_glass_effect]"]').on('change', function() {
                    const containedButton = $(".contained-button");

                    const container = containedButton.parent();

                    const colorValue = container.find('input[name="button_background_color[color]"]').val();

                    const isNoColor = container.find('input[name="button_background_color[is_no_color]"]')
                        .val();

                    if ($(this).val() == "true") {
                        addGlassEffect(rgbaStringToObject(colorValue), containedButton)
                    } else {
                        removeGlassEffect(containedButton, colorValue, isNoColor);
                    }
                });


                // contained text color
                $('input[name="button_text_color[color]"]').on('change', function() {
                    const containedButton = $(".contained-button");
                    containedButton.css("color", $(this).val());
                })



                // contained color hover 
                $('input[name="button_hover_background_color[color]"]').on('change', function() {
                    const containedButton = $(".contained-button");

                    const container = containedButton.parent();

                    const isGlassEffectColor = container.find(
                        'input[name="button_hover_background_color[is_with_glass_effect]"]').val();


                    const isNoColor = container.find(
                        'input[name="button_hover_background_color[is_no_color]"]').val();

                    const colorValue = $(this).val()


                    if (isGlassEffectColor !== "true") {
                        if (isNoColor === "true") {
                            removeBackgroundHoverColor('contained-button-background-hover')
                        } else {
                            generateBackgroundHoverColor(colorValue, containedButton,
                                'contained-button-background-hover');
                        }
                    } else
                        addGlassEffectWithHover(rgbaStringToObject(colorValue), containedButton,
                            'glass-hover-for-contained-button', 'glass-hover-for-contained-button')
                });

                $('input[name="button_hover_background_color[is_no_color]').on("change", function() {
                    const containedButton = $(".contained-button");

                    const container = containedButton.parent();

                    const colorValue = container.find('input[name="button_hover_background_color[color]"]')
                        .val();

                    const isNoColor = container.find('input[name="button_hover_background_color[is_no_color]"]')
                        .val();

                    removeHoverGlassEffect('glass-hover-for-contained-button');
                })


                $('input[name="button_hover_background_color[is_with_glass_effect]"]').on('change', function() {

                    const containedButton = $(".contained-button");

                    const container = containedButton.parent();

                    const colorValue = container.find('input[name="button_hover_background_color[color]"]')
                        .val();

                    const isNoColor = container.find('input[name="button_hover_background_color[is_no_color]"]')
                        .val();

                    if ($(this).val() == "true") {

                        addGlassEffectWithHover(rgbaStringToObject(colorValue), containedButton,
                            'glass-hover-for-contained-button', 'glass-hover-for-contained-button')
                    } else {

                        removeHoverGlassEffect('glass-hover-for-contained-button');
                        generateBackgroundHoverColor(colorValue, containedButton,
                            'contained-button-background-hover');
                    }
                });
                //////////


                // outlined normal case
                $('input[name="secondary_button_background_color[color]"]').on('change', function() {
                    const outlinedButton = $(".outlined-button");


                    const container = outlinedButton.parent();

                    const isGlassEffectColor = container.find(
                        'input[name="secondary_button_background_color[is_with_glass_effect]"]').val();

                    const colorValue = $(this).val()


                    if (isGlassEffectColor !== "true")
                        outlinedButton.css("background-color", $(this).val());
                    else
                        addGlassEffect(rgbaStringToObject(colorValue), outlinedButton)

                });

                $('input[name="secondary_button_background_color[is_no_color]').on("change", function() {
                    const outlinedButton = $(".outlined-button");

                    const container = outlinedButton.parent();

                    const colorValue = container.find('input[name="secondary_button_background_color[color]"]')
                        .val();

                    const isNoColor = container.find(
                            'input[name="secondary_button_background_color[is_no_color]"]')
                        .val();

                    removeGlassEffect(outlinedButton, colorValue, isNoColor);
                })


                $('input[name="secondary_button_background_color[is_with_glass_effect]"]').on('change', function() {
                    const outlinedButton = $(".outlined-button");

                    const container = outlinedButton.parent();

                    const colorValue = container.find('input[name="secondary_button_background_color[color]"]')
                        .val();

                    const isNoColor = container.find(
                            'input[name="secondary_button_background_color[is_no_color]"]')
                        .val();

                    if ($(this).val() == "true") {
                        addGlassEffect(rgbaStringToObject(colorValue), outlinedButton)
                    } else {
                        removeGlassEffect(outlinedButton, colorValue, isNoColor);
                    }
                });


                // outlined text color
                $('input[name="secondary_button_text_color[color]"]').on('change', function() {
                    const outlinedButton = $(".outlined-button");
                    outlinedButton.css("color", $(this).val());
                })



                // outlined color hover 
                $('input[name="secondary_button_hover_background_color[color]"]').on('change', function() {
                    const outlinedButton = $(".outlined-button");

                    const container = outlinedButton.parent();

                    const isGlassEffectColor = container.find(
                        'input[name="secondary_button_hover_background_color[is_with_glass_effect]"]').val();


                    const isNoColor = container.find(
                        'input[name="secondary_button_hover_background_color[is_no_color]"]').val();

                    const colorValue = $(this).val()


                    if (isGlassEffectColor !== "true") {
                        if (isNoColor === "true") {
                            removeBackgroundHoverColor('outlined-button-background-hover')
                        } else {
                            generateBackgroundHoverColor(colorValue, outlinedButton,
                                'outlined-button-background-hover');
                        }
                    } else
                        addGlassEffectWithHover(rgbaStringToObject(colorValue), outlinedButton,
                            'glass-hover-for-outlined-button', 'glass-hover-for-outlined-button')
                });

                $('input[name="secondary_button_hover_background_color[is_no_color]').on("change", function() {
                    const outlinedButton = $(".outlined-button");

                    const container = outlinedButton.parent();

                    const colorValue = container.find(
                            'input[name="secondary_button_hover_background_color[color]"]')
                        .val();

                    const isNoColor = container.find(
                            'input[name="secondary_button_hover_background_color[is_no_color]"]')
                        .val();

                    removeHoverGlassEffect('glass-hover-for-outlined-button');
                })


                $('input[name="secondary_button_hover_background_color[is_with_glass_effect]"]').on('change',
                    function() {

                        const outlinedButton = $(".outlined-button");

                        const container = outlinedButton.parent();

                        const colorValue = container.find(
                                'input[name="secondary_button_hover_background_color[color]"]')
                            .val();

                        const isNoColor = container.find(
                                'input[name="secondary_button_hover_background_color[is_no_color]"]')
                            .val();

                        if ($(this).val() == "true") {

                            addGlassEffectWithHover(rgbaStringToObject(colorValue), outlinedButton,
                                'glass-hover-for-outlined-button', 'glass-hover-for-outlined-button')
                        } else {

                            removeHoverGlassEffect('glass-hover-for-outlined-button');
                            generateBackgroundHoverColor(colorValue, outlinedButton,
                                'outlined-button-background-hover');
                        }
                    });



                ///////



                // text-button text color
                $('input[name="secondary_text_color[color]"]').on('change', function() {
                    const textButton = $(".text-button");
                    textButton.css("color", $(this).val());
                })

                // text-button text hover color
                $('input[name="secondary_hover_text_color[color]"]').on('change', function() {
                    const textButton = $(".text-button");

                    const container = textButton.parent();


                    const isNoColor = container.find('input[name="secondary_text_color[is_no_color]"]')
                        .val();

                    if (isNoColor !== "true") {
                        addHoverColorChange($(this).val(), textButton, 'text-hover-effect', 'text-hover-effect')
                    } else {
                        removeStyleByDataId('text-hover-effect')
                    }
                })





                function generateBackgroundHoverColor(stringColor, element, dataID) {
                    $(`[data-id='${dataID}']`).remove();
                    var style = $(`<style data-id="${dataID}"></style>`).appendTo('head');
                    // Add a rule to it
                    style.html(`.dynamic-hover:hover { background: ${stringColor} !important; }`);

                    // Apply the new class to elements
                    element.addClass('dynamic-hover');
                }

                function removeBackgroundHoverColor(dataID) {
                    $(`[data-id='${dataID}']`).remove()
                }

                window.loadedScripts.push('button-color-picker-script');




            })(jQuery)
        }
    </script>
@endpush
