<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>

<div id="{{ $name }}">
    <div style="opacity: 0.7">Family</div>
    <div class="color-panel-trigger" id="fontPicker">
        <ul class="nav nav-tabs" id="fontTabs">
            <li class="nav-item w-50">
                <a class="nav-link active" id="googleTab" data-toggle="tab"
                    href="#googleFonts-{{ $name }}">Google Fonts</a>
            </li>
            <li class="nav-item w-50">
                <a class="nav-link" id="customTab" data-toggle="tab" href="#customFonts-{{ $name }}">Custom</a>
            </li>
        </ul>
        <div class="tab-content">
            <div class="tab-pane fade show active" id="googleFonts-{{ $name }}">
                <select value='{{ $value }}' name={{ $name }} class="form-control"
                    id='{{ $title }}-googleFontSelect' style="width: 100%;">
                    <!-- Google Fonts options will be appended here -->
                </select>
            </div>
            <div class="tab-pane fade" id="customFonts-{{ $name }}">
                @if (strpos($value, 'https://fonts.gstatic.com') !== 0)
                    <p style="font-weight: 600;" class="py-2"> Current : {{ $value }}</p>
                @endif
                <input name="{{ $name }}" type="file" class="form-control" id="customFontUpload"
                    accept="font/*">
            </div>
        </div>
    </div>

    <!-- Font Style Menu -->
    <div>
        <label for="fontStyleSelect">Style:</label>
        <select name='{{ $style }}' class="form-control" id="fontStyleSelect-{{ $name }}">
            <option value="regular">Regular</option>
            <option value="italic">Italic</option>
            <!-- Add more font style options here -->
        </select>
    </div>

    <div style="gap: 6px;" class="d-flex gap-2">

        <!-- Letter Spacing Input -->
        <div class="w-25">
            <label for="letterSpacingInput">Letter Spacing:</label>
            <input name='{{ $letterspacing }}' type="number" class="form-control"
                id="letterSpacingInput-{{ $name }}" min="0" step="0.1">
        </div>

        <!-- Text Transform Style Menu -->
        <div class="w-75">
            <label for="textTransformSelect">Text Transform:</label>
            <select name='{{ $texttransform }}' class="form-control" id="textTransformSelect-{{ $name }}">
                <option value="none">None</option>
                <option value="uppercase">Uppercase</option>
                <option value="lowercase">Lowercase</option>
                <option value="capitalize">Capitalize</option>

            </select>
        </div>
    </div>
</div>


<style>
    /* Add your custom styles here */
    #googleFontSelect option {
        font-family: Arial, sans-serif;
        /* Default font */
    }

    .nav-tabs .nav-item .nav-link.active {
        background-color: white;
        border-color: #008E8F;
        color: #008E8F;
        border-radius: 0px;
    }

    .nav-tabs .nav-item .nav-link {
        border: 1px solid #008E8F;
        color: white;
        background-color: #008E8F;
        border-radius: 0px;
    }

    label {
        margin-bottom: 0 !important;
        opacity: 0.7;
    }
</style>

@push('script')
    <script>
        $(document).ready(function() {
            var fetching = false;
            var fontsCache = [];
            var styleValueData = "{{ $stylevalue }}";
            var letterspacingvalueData = "{{ $letterspacingvalue }}";
            var texttransformvalueData = "{{ $texttransformvalue }}";

            $('#fontStyleSelect-{{ $name }}').val(styleValueData);
            $('#letterSpacingInput-{{ $name }}').val(letterspacingvalueData);
            $('#textTransformSelect-{{ $name }}').val(texttransformvalueData);
            // Function to fetch Google Fonts
            function fetchGoogleFonts() {
                fetching = true;
                $.getJSON(
                    "https://www.googleapis.com/webfonts/v1/webfonts?key=AIzaSyC_ZsFIwz56S2VKyVQwOdqkIlUJu42iKIo",
                    function(data) {
                        var fonts = data.items;
                        var options = [];
                        var defaultValue = "{{ $value }}".toString();

                        fonts.forEach(function(font) {
                            options.push({
                                id: font.family,
                                text: font.family,
                                value: font.files.regular
                            });
                            fontsCache.push(font.family);
                        });

                        // Append options to select element
                        var $select = $('#{{ $title }}-googleFontSelect');
                        $select.empty(); // Clear existing options
                        options.forEach(function(option) {
                            $select.append($('<option>', {
                                value: option.value,
                                text: option.text
                            }));
                        });

                        if (defaultValue) {
                            // Set default value
                            $select.val(defaultValue);
                        }

                        // Initialize select2
                        $select.select2({
                            templateResult: function(data) {
                                if (!data.id) {
                                    return data.text;
                                }
                                return $("<span style='font-family: " + data.text + "'>" + data
                                    .text + "</span>");
                            }
                        });

                        fetching = false;
                    });
            }

            // Lazy load Google Fonts
            function lazyLoadFonts() {
                var observer = new IntersectionObserver(function(entries) {
                    entries.forEach(function(entry) {
                        if (entry.isIntersecting && !fetching) {
                            fetchGoogleFonts();
                        }
                    });
                }, {
                    threshold: 0.5
                });

                observer.observe(document.getElementById('{{ $title }}-googleFontSelect'));
            }

            // Initial fetch
            fetchGoogleFonts();
            lazyLoadFonts();

            // Show/hide tabs
            $("#googleTab").click(function() {
                $("#customFonts-{{ $name }}").removeClass("show active");
                $("#googleFonts-{{ $name }}").addClass("show active");
            });

            $("#customTab").click(function() {
                $("#googleFonts-{{ $name }}").removeClass("show active");
                $("#customFonts-{{ $name }}").addClass("show active");
            });



        });
    </script>
@endpush
