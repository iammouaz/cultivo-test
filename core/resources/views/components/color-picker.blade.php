<div id="{{ $name }}" class="d-flex align-items-center justify-content-between w-100 color-input-container"
    style="gap: 10px">
    <div style="font-weight: 600">{!! $title !!}</div>
    <div class="color-panel-trigger"
        style="flex-shrink:0;width:32px;height:32px;border-radius:100%;cursor:pointer;background-color:{{ $value['color'] ?? "" }}"
        data-toggle="color-popover">
        @if ($value['is_no_color'] ?? null)
            <div class="no-color-icon"></div>
        @endif
    </div>
    <input type="hidden" class="color-value" name="{{ $name }}[color]" value="{{ $value['color'] ?? '' }}" />
    @if (!$removeGlassEffect)
        <input type="hidden" class="glass-effect-value" name="{{ $name }}[is_with_glass_effect]"
            value="{{ $value['is_with_glass_effect'] ?? null ? 'true' : 'false' }}" />
    @endif

    <input type="hidden" class="no-color-value" name="{{ $name }}[is_no_color]"
        value="{{ $value['is_no_color'] ?? null ? 'true' : 'false' }}" />




</div>

<style>
    .text_color {
        -webkit-appearance: none;
        border: none;
        width: 32px;
        height: 32px;
    }

    .text_color::-webkit-color-swatch-wrapper {
        padding: 0;
    }

    .text_color::-webkit-color-swatch {
        border: none;
    }

    .IroBox {
        border-radius: 0px !important
    }

    .no-color-icon {
        width: 32px;
        height: 32px;
        border: 1px solid #AAAAAA;
        border-radius: 100%;
        position: relative;
    }

    .no-color-icon::after {
        content: "";
        height: 100%;
        width: 1px;
        position: absolute;
        top: 0;
        left: 50%;
        transform: translateX(-50%) rotate(-45deg);
        background-color: #FF0000;
    }
</style>


@push('script')
    <script src="https://cdn.jsdelivr.net/npm/@jaames/iro@5"></script>
    <script>
        (function($) {

            function convertRGBAToRGBAString(rgb, alpha) {
                return `rgba(${rgb.r}, ${rgb.g}, ${rgb.b}, ${alpha})`;
            }


            function addGlassEffect(rgbColor, element = undefined) {
                const colorTrigger = element ?? colorInputContainer.find(".color-panel-trigger");
                // Convert the base color to two different RGBA strings with varying alpha values for the gradient
                const colorTop = convertRGBAToRGBAString(rgbColor,
                    0.5); // 50% transparency
                const colorBottom = convertRGBAToRGBAString(rgbColor,
                    0.1); // 10% transparency

                // Update the CSS styles dynamically with the new color values
                colorTrigger.css('border-bottom',
                    `1px solid rgba(${rgbColor.r}, ${rgbColor.g}, ${rgbColor.b}, 1)`
                );
                colorTrigger.css('background',
                    `linear-gradient(180deg, ${colorTop} 24.31%, ${colorBottom} 90.92%)`);
                colorTrigger.css('box-shadow', '0px 4px 4px 0px rgba(0, 0, 0, 0.25)');
                colorTrigger.css('backdrop-filter', 'blur(15px)');
            }

            function rgbaStringToObject(rgbaStr) {
                // Use a regular expression to extract the numeric values from the string.
                const match = rgbaStr.match(/rgba?\((\d+),\s*(\d+),\s*(\d+),?\s*(\d*\.?\d+)?\)/i);

                if (match) {
                    return {
                        r: parseInt(match[1], 10),
                        g: parseInt(match[2], 10),
                        b: parseInt(match[3], 10),
                        a: match[4] !== undefined ? parseFloat(match[4]) : 1 // Default alpha to 1 if not specified
                    };
                } else {
                    throw new Error('Invalid RGBA color string');
                }
            }


            if (!window.loadedScripts.includes('color-picker-script')) {

                // this var will be filled with the container of button that triggerd the color panel
                var colorInputContainer = undefined;
                var colorPicker = undefined;

                $('[data-toggle="color-popover"]').on('shown.bs.popover', function() {

                    colorPicker = new iro.ColorPicker('#picker', {
                        layout: [{
                            component: iro.ui.Box,
                            options: {}
                        }, {
                            component: iro.ui.Slider,
                            options: {
                                // can also be 'saturation', 'value', 'red', 'green', 'blue', 'alpha' or 'kelvin'
                                sliderType: 'hue'
                            }
                        }, ],
                        width: 250,
                        color: colorInputContainer.find('.color-value').val(),
                    });


                    const noColorValue = colorInputContainer.find(
                        ".no-color-value").val() === "true"

                    $(".no-color-checkbox").prop('checked', noColorValue)

                    $(".glass-effect-checkbox").prop('checked', colorInputContainer.find(
                        ".glass-effect-value").val() === "true")

                    $(".color-value-label").val(colorPicker.color[$(".selected-color-system").val()])

                    if (noColorValue) {

                        $(".color-value-label").prop("disabled", true)
                        $(".selected-color-system").prop("disabled", true)
                    }


                    if (colorInputContainer.find(".no-color-value").val() === "true") {
                        disableColorPicker()
                        $(".glass-effect-checkbox").prop('checked', false);
                        $(".glass-effect-checkbox").prop('disabled', true);
                    }

                    colorPicker.on("color:change", (state) => {
                        const isGlassEffectEnabled = colorInputContainer.find(".glass-effect-value")
                            .val() === "true";
                        colorInputContainer.find(".color-value").val(adjustAlpha(state.rgbaString,
                            colorInputContainer.find(
                                ".no-color-value").val() !== "true" ? 1 : 0)).trigger("change");
                        if (isGlassEffectEnabled) {
                            addGlassEffect(state.rgb)
                        } else {
                            colorInputContainer.find('.color-panel-trigger').css('background-color',
                                state
                                .hexString);
                        }
                        $(".color-value-label").val(state[$(".selected-color-system").val()])
                    });
                })

                $('[data-toggle="color-popover"]').popover({
                    html: true,
                    sanitize: false,
                    content: `<div class="popover-container">
                           <div id="picker"></div>
                           
                           <div class="d-flex align-items-center my-2 w-100" style="gap: 5px;max-width:250px">
                            <div style="flex-shrink:0">
                                <select class="form-select selected-color-system w-100">
                                  <option selected value="rgbString">RGB</option>
                                  <option value="hexString">Hex</option>
                                </select>

                            </div>
                            <div class="input-group">
                              <input type="text" class="color-value-label form-control"/>
                            </div>
                            </div>
                        <div class="mt-2">
                           
                            @if (!$removeGlassEffect)
                           <div class="form-check">
                              <input class="form-check-input glass-effect-checkbox" type="checkbox" value="" id="glass_effect" />
                              <label class="form-check-label fw-bolder" for="glass_effect">
                               Add Glass effect
                              </label>
                           </div>
                           @endif
                          
                           <div class="form-check">
                              <input  class="form-check-input no-color-checkbox" type="checkbox"  id="no_color" />
                              <label class="form-check-label fw-bolder" for="no_color">
                               No Color
                              </label>
                           </div>
                      

                     </div>
                        </div>`,
                    template: '<div style="max-width:600px;filter: drop-shadow(0px 4px 4px rgba(0, 0, 0, 0.25));" class="card" role="tooltip"><div class="popover-body"></div></div>'
                })


                $(document).on('click', function(e) {
                    $('[data-toggle="color-popover"]').each(function() {
                        // Hide any open popovers when the anywhere in the document is clicked
                        if (!$(this).is(e.target) && $(this).has(e.target).length === 0 && $(
                                '.popover-container')
                            .has(e.target).length === 0 && $(this).closest('.popover-container')
                            .length === 0) {
                            $(this).popover('hide');
                        }
                    });
                });

                $(document).on("click", ".color-panel-trigger", function() {
                    colorInputContainer = $(this).closest('.color-input-container');
                })

                $(document).on("change", '.glass-effect-checkbox', function() {
                    const currentColor = colorInputContainer.find('.color-value').val();
                    const checked = $(this).prop('checked');
                    colorInputContainer.find(".glass-effect-value").val(checked).trigger("change")
                    if (checked) {

                        addGlassEffect(colorPicker.color.rgb)
                    } else {
                        removeGlassEffect()
                    }
                })

                $(document).on("change", ".color-value-label", function() {
                    debugger
                    const stringColor = $(this).val();
                    const rgbaStringColor = convertToRgba(stringColor);
                    const rgbaObject = rgbaStringToObject(rgbaStringColor);
                    colorPicker.color.set(rgbaStringColor);
                    $(".selected-color-system").val(identifyColorFormat(stringColor) + "String")

                })

                $(document).on("change", '.no-color-checkbox', function() {
                    const colorTrigger = colorInputContainer.find(".color-panel-trigger");
                    const glassEffectCheckbox = $('.glass-effect-checkbox');
                    const colorSystemInput = $(".selected-color-system")
                    const colorSystemLabel = $(".color-value-label")
                    const checked = $(this).prop('checked');
                    const colorValueInput = colorInputContainer.find('.color-value');
                    colorInputContainer.find('.no-color-value').val(checked).trigger('change');
                    removeGlassEffect()
                    if (checked) {

                        colorSystemInput.attr('disabled', true)
                        colorSystemLabel.attr('disabled', true)
                        glassEffectCheckbox.attr('disabled', true);
                        glassEffectCheckbox.prop('checked', false);

                        AdjustColorTriggerNoColorIcon(true)
                        disableColorPicker()

                        colorValueInput.val(adjustAlpha(colorValueInput.val(), 0)).trigger("change")

                    } else {
                        colorSystemInput.attr('disabled', false)
                        colorSystemLabel.attr('disabled', false)
                        glassEffectCheckbox.prop('disabled', false);
                        AdjustColorTriggerNoColorIcon(false)
                        disableColorPicker(false);
                        colorValueInput.val(adjustAlpha(colorValueInput.val(), 1)).trigger("change");
                        colorTrigger.css('background-color', colorValueInput.val());

                    }
                })


                $(document).on("change", '.selected-color-system', function() {
                    const selectedColorSystem = $(this).val();
                    $(".color-value-label").val(colorPicker.color[selectedColorSystem])
                })



                function disableColorPicker(disable = true) {
                    const colorPickerElement = document.getElementById('picker');
                    if (disable) {
                        colorPickerElement.style.pointerEvents = 'none';
                        colorPickerElement.style.opacity = '0.4'; // Optional: makes it look disabled
                    } else {
                        colorPickerElement.style.pointerEvents = '';
                        colorPickerElement.style.opacity = '1'; // Restore opacity
                    }
                }







                function removeGlassEffect() {
                    const colorTrigger = colorInputContainer.find(".color-panel-trigger");
                    const colorValue = colorInputContainer.find(".color-value").val();
                    const NoColorInputValue = colorInputContainer.find(".no-color-value").val();
                    // Resetting the styles to remove the glass effect
                    colorTrigger.css('border-bottom', ''); // Remove custom border-bottom
                    colorTrigger.css('background', NoColorInputValue == 'true' ? '' :
                        colorValue); // Remove custom background
                    colorTrigger.css('box-shadow', ''); // Remove custom box-shadow
                    colorTrigger.css('backdrop-filter', ''); // Remove backdrop-filter

                }



                function adjustAlpha(rgbaString, newAlpha) {
                    // Regular expression to match the numbers in the RGBA string
                    const rgbaRegex = /rgba\((\d+),\s*(\d+),\s*(\d+),\s*(\d*\.?\d+)\)/;

                    // Attempt to match the regex pattern against the input string
                    const matches = rgbaString.match(rgbaRegex);

                    if (matches && newAlpha >= 0 && newAlpha <= 1) {
                        // Construct a new RGBA string with the original color values and the new alpha
                        const newRgbaString = `rgba(${matches[1]}, ${matches[2]}, ${matches[3]}, ${newAlpha})`;
                        return newRgbaString;
                    } else {
                        // If the input doesn't match or alpha is out of bounds, return the original string
                        console.error('Invalid RGBA string or alpha value. Returning original string.');
                        return rgbaString;
                    }
                }




                function AdjustColorTriggerNoColorIcon(status) {
                    const colorTrigger = colorInputContainer.find(".color-panel-trigger");
                    if (status) {
                        colorTrigger.html(` <div class="no-color-icon"></div>`);
                        colorTrigger.css('background-color', 'transparent');
                    } else {
                        colorTrigger.html('');
                        colorTrigger.css('background-color', colorInputContainer.find(".color-value").val());
                    }
                }




                window.loadedScripts.push('color-picker-script');


            }
            @if ($value['is_with_glass_effect']?? null && !$value['is_no_color'] ?? null)
                addGlassEffect(rgbaStringToObject('{{ $value['color'] }}'), $("#{{ $name }}").find(
                    '.color-panel-trigger'));
            @endif

        })(jQuery)
    </script>
@endpush
