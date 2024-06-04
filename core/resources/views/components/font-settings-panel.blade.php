<style>
    .rotate-180 {
        transform: rotate(180deg)
    }

    .nav-item.active {
        border-bottom: solid 2px var(--primary);
    }

    .button-shape {
        height: 38px;
        background: rgba(170, 170, 170, 0.6);
        cursor: pointer
    }

    .button-shape.active {
        background: rgba(0, 142, 143, 1);
        background-repeat: no-repeat;
        background-position: center;
        background-image: url("data:image/svg+xml,%3Csvg width='24' height='24' viewBox='0 0 24 24' fill='none' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M20 6L9 17L4 12' stroke='white' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'/%3E%3C/svg%3E%0A");

    }

    .button-shape-container.disabled {
        cursor: not-allowed;
        /* Show a 'not allowed' cursor */
        pointer-events: none;
        /* Prevents click actions */
        opacity: 0.5;
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
                @if ($title === 'Headings')
                    <div class="d-flex align-items-center justify-content-around">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                            xmlns="http://www.w3.org/2000/svg">
                            <g clip-path="url(#clip0_42_29842)">
                                <path
                                    d="M12 22C17.5228 22 22 17.5228 22 12C22 6.47715 17.5228 2 12 2C6.47715 2 2 6.47715 2 12C2 17.5228 6.47715 22 12 22Z"
                                    stroke="#AAAAAA" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                <path d="M12 16V12" stroke="#AAAAAA" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round" />
                                <path d="M12 8H12.01" stroke="#AAAAAA" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round" />
                            </g>
                            <defs>
                                <clipPath id="clip0_42_29842">
                                    <rect width="24" height="24" fill="white" />
                                </clipPath>
                            </defs>
                        </svg>

                        <span style="font-size: 12px; opacity: 0.7; width: 70%;">
                            Fonts can affect your site’s load time. Our platform allows you to upload and select one
                            custom
                            font for headings and one custom font for paragraphs.
                        </span>
                    </div>
                @endif
                <div class="row py-2" style="gap: 20px">
                    @if ($type === 'button')
                        <div class="w-100">
                            <div class="mx-3 mb-3">
                                Button Text Styles
                            </div>
                            <div class="d-flex justify-content-center w-100 a-items-center">
                                <ul class="nav nav-underline" style="border-bottom: solid 1px var(--gray)">
                                    <li class="nav-item font-board-button active" data-target="0">
                                        <div class="nav-link" href="#">
                                            <button type="button" class="btn btn-primary">Contained</button>
                                        </div>
                                    </li>
                                    <li class="nav-item font-board-button" data-target="1">
                                        <div class="nav-link" href="#">
                                            <button type="button" class="btn btn-outline-primary">Outlined</button>
                                        </div>
                                    </li>
                                    <li class="nav-item font-board-button" data-target="2">
                                        <div class="nav-link" href="#">
                                            <button type="button" class="btn btn-link">Text</button>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                            <div class="mx-3 mt-5 mb-2 button-type">
                                Contained Button
                            </div>
                        </div>
                    @endif
                    @foreach ($itemList as $key => $fontItem)
                        <div class="font_board w-100" style="{{ $key != 0 ? 'display:none' : '' }}  "
                            data-index="{{ $key }}">
                            <div class="col-12">

                                <x-font-picker :value="$fontItem['value']" :style="$fontItem['style']"
                                    title="{{ explode(' ', $title)[0] . $key }}" name="{{ $fontItem['name'] }}"
                                    :letterspacing="$fontItem['letterspacing']" :texttransform="$fontItem['texttransform']" :letterspacingvalue="$fontItem['letterspacingvalue']" :texttransformvalue="$fontItem['texttransformvalue']"
                                    :stylevalue="$fontItem['stylevalue']"></x-font-picker>
                            </div>

                            @if ($type === 'button')
                                <div class="w-100 button-border-component" style="margin-top:70px">
                                    <div class="mb-3">
                                        Button Shape
                                    </div>
                                    <div
                                        class="row w-100 button-shape-container @if ($fontItem['is_custom_corners'] == 'true') disabled @endif">
                                        <div class="col-4">
                                            <div
                                                class="w-100 button-shape @if ($fontItem['custom_corners'] == 0) active @endif">
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div class="w-100 button-shape @if ($fontItem['custom_corners'] == 4) active @endif"
                                                style="border-radius: 4px"></div>
                                        </div>
                                        <div class="col-4">
                                            <div class="w-100 button-shape @if ($fontItem['custom_corners'] == 50) active @endif"
                                                style="border-radius: 50px"></div>
                                        </div>
                                    </div>
                                    <input name="{{ $fontItem['custom_corner_name'] }}" class="corner-input"
                                        type="hidden" value="{{ $fontItem['custom_corners'] }}" />

                                    <div class="m-3 d-flex align-items-center" style="gap: 20px">

                                        <div class="form-check">
                                            <input class="form-check-input custom-corner-flag" type="checkbox"
                                                @if ($fontItem['is_custom_corners'] == 'true') checked @endif
                                                id="{{ $fontItem['is_custom_corners_name'] }}">
                                            <input value="{{ $fontItem['is_custom_corners'] ?? false }}" type="hidden"
                                                name="{{ $fontItem['is_custom_corners_name'] }}" />
                                            <label class="form-check-label font-weight-bold"
                                                for="{{ $fontItem['is_custom_corners_name'] }}">
                                                Custom Corners?
                                            </label>
                                        </div>

                                        <div style="max-width: 200px" class="input-group">
                                            <input type="number" @if ($fontItem['is_custom_corners'] != 'true') disabled @endif
                                                class="form-control custom-corner-input"
                                                value="{{ $fontItem['custom_corners'] ?? 0 }}" placeholder="Number">
                                            <span class="input-group-text" id="basic-addon2">Px</span>
                                        </div>

                                    </div>

                                </div>
                            @endif
                        </div>
                    @endforeach




                </div>
            </div>
        </div>
    </div>

</div>


@push('script')
    <script>
        (function($) {

            if (!window.loadedScripts.includes('font-picker-script')) {

                $(document).on("change", 'input[type="checkbox"]', function() {
                    $(this).parent().find('input[type="hidden"]').val($(this).is(':checked'))
                })

                $(document).on("click", ".font-board-button", function() {

                    $('.font-board-button').removeClass("active");
                    $(this).addClass("active");
                    const container = $(this).closest(".colorPanel");
                    const fontBoard = container.find(".font_board")
                    fontBoard.css('display', 'none');
                    const dataTarget = $(this).attr('data-target');

                    container.find('[data-index="' + dataTarget + '"]').css(
                        'display', 'block');

                    $('.button-type').text(dataTarget == 0 ? 'Button Contained' : dataTarget == 1 ?
                        'Button Outlined' : 'Button Text');


                })

                $(document).on("change", ".custom-corner-flag", function() {
                    const container = $(this).closest(".button-border-component");

                    const customerCornerInput = container.find(".custom-corner-input");

                    const cornerInput = container.find(".corner-input");

                    if ($(this).prop('checked')) {
                        cornerInput.val(customerCornerInput.val());
                        customerCornerInput.prop("disabled", false);
                        container.find(".button-shape-container").addClass('disabled')
                    } else {
                        cornerInput.val(container.find('.button-shape.active').css("border-radius"));
                        customerCornerInput.prop("disabled", true)
                        container.find(".button-shape-container").removeClass('disabled')
                    }
                })

                $(document).on("change", ".custom-corner-input", function() {
                    const container = $(this).closest(".button-border-component");
                    const cornerInput = container.find(".corner-input");
                    cornerInput.val($(this).val());
                })


                $(document).on("click", ".button-shape", function() {
                    debugger
                    const container = $(this).closest(".font_board");
                    container.find(".button-shape").removeClass("active");
                    $(this).addClass("active");
                    container.find(".corner-input").val($(this).css("border-radius").replace("px", ''));
                })

                window.loadedScripts.push('color-picker-script');
            }

        })(jQuery);
    </script>
@endpush
