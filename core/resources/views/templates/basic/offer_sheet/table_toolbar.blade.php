@php
    $columns = get_offer_specifications();
@endphp

<style>
    .left-section,
    .right-section {
        flex: 1;
    }

    .form-check-input:focus {

        box-shadow: 0 0 0 0.25rem #FF612823 !important;
    }

    .right-section {
        background-color: white;
        border-left: 1px solid #0000001F;
        padding: 20px;
    }

    .list-group-item {
        display: flex;
        gap: 8px;
        align-items: center;
        color: #242828DE;
    }

    .list-group {
        padding-top: 8px;
    }

    svg {
        cursor: pointer;
    }

    .form-check-input:checked {
        background-color: var(--main-svg-color) !important;
        border-color: var(--main-svg-color) !important;
        widows: 16px;
        height: 16px;
    }

    .close-btn {
        cursor: pointer;
        color: red;
        margin-right: 5px;
    }


    #sortableList {
        gap: 8px;
    }

    .list-group-item+.list-group-item {
        border-top-width: 1px !important;
    }

    .gap-3 {
        gap: 12px
    }

    #toggleDiv {
        width: 350px;
        position: absolute;
        background-color: white;
        padding: 1rem;
        z-index: 3;
        right: 0;
        top: 8rem;
    }
</style>

<div class="products-table-toolbar d-flex justify-content-end gap-4 mb-3">
    <div style="position: relative;">
        <button class="filters-btn reset-button-styles change-bg-hover-effect d-flex align-items-center gap-2 p-2"
            onclick="toggleDiv()">
            <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                <g id="Icon Left">
                    <path id="Vector"
                        d="M8.33333 15H11.6667V13.3333H8.33333V15ZM2.5 5V6.66667H17.5V5H2.5ZM5 10.8333H15V9.16667H5V10.8333Z" />
                </g>
            </svg>
            <span class="text-uppercase py-2">@lang('filters')</span>
        </button>



    </div>


    <button data-bs-toggle="modal" data-bs-target="#columnCustomizationModal"
        class="edit-columns-btn reset-button-styles change-bg-hover-effect d-flex align-items-center gap-2 p-2">
        <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
            <g id="Icon Left">
                <path id="Vector"
                    d="M2.5 4.16669V15.8334H17.5V4.16669H2.5ZM6.94167 14.1667H4.16667V5.83335H6.94167V14.1667ZM11.3917 14.1667H8.61667V5.83335H11.3917V14.1667ZM15.8333 14.1667H13.0583V5.83335H15.8333V14.1667Z" />
            </g>
        </svg>
        <span class="text-uppercase">@lang('columns')</span>
    </button>

</div>
<div onclick="toggleDiv();" style="display: none" class="filter-block-window"></div>

{{-- Filter Dialog --}}
<div id="toggleDiv" style="display: none" class="positioned-div">
    <div class="d-flex p-2 gap-2 fs-5 border-bottom">
        <svg style="cursor: pointer" width="24" height="24" viewBox="0 0 24 24" fill="none"
            xmlns="http://www.w3.org/2000/svg">
            <path d="M10 18H14V16H10V18ZM3 6V8H21V6H3ZM6 13H18V11H6V13Z" fill="#242828" fill-opacity="0.56" />
        </svg>
        <span class="black-color">@lang('Filters')</span>
    </div>
    <div class="text-center py-3" id="no-filters-label">
        <h6 class="m-0">@lang('No Filters Selected')</h6>
    </div>
    <div id="rows-filter" class="border-bottom">
        <!-- Filter rows start here -->


    </div>

    <div class="d-flex gap-2 py-2 float-end">
        <button style="border: none" id="add-filter" class="d-flex gap-2 primary-outline-btn">
            <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path
                    d="M15.8334 10.8333H10.8334V15.8333H9.16669V10.8333H4.16669V9.16666H9.16669V4.16666H10.8334V9.16666H15.8334V10.8333Z"
                    fill="#FF6128" />
            </svg>
            @lang('Add Filter')
        </button>

        <button id="remove-filters" class="d-flex gap-2 primary-filled-btn">
            <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path
                    d="M15.8334 5.34166L14.6584 4.16666L10 8.825L5.34169 4.16666L4.16669 5.34166L8.82502 10L4.16669 14.6583L5.34169 15.8333L10 11.175L14.6584 15.8333L15.8334 14.6583L11.175 10L15.8334 5.34166Z"
                    fill="white" />
            </svg>

            @lang('Remove All')
        </button>
    </div>
</div>
{{-- End Filter Dialog --}}

<div class="modal fade " id="columnCustomizationModal" tabindex="-1" aria-labelledby="columnCustomizationModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div style="background-color: white;" class="modal-header">
                <div class="d-flex gap-1 justify-content-center align-items-center">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                        xmlns="http://www.w3.org/2000/svg">
                        <path d="M3 5V19H21V5H3ZM8.33 17H5V7H8.33V17ZM13.67 17H10.34V7H13.67V17ZM19 17H15.67V7H19V17Z"
                            fill="black" fill-opacity="0.54" />
                    </svg>
                    <h5 style="color: #242828DE;" class="modal-title" id="columnCustomizationModalLabel">
                        @lang('Customize Columns')</h5>

                </div>
                {{-- <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button> --}}
            </div>
            <!-- Add this to your modal body -->
            <div style="padding: 0;" class="modal-body">

                <div class="d-flex">
                    <div class="left-section">
                        <div class="input-group border-bottom mb-3">
                            <div class="input-group-prepend">
                                <div style="background-color: white; border: 0;" class="input-group-text h-100">
                                    <i style="color:#2428288F;" class="fas fa-search"></i>
                                </div>
                            </div>
                            <input id="searchInput" style="border: 0; color: #24282899;"
                                onfocus="this.style.backgroundColor = '#ffffff';"
                                onblur="this.style.backgroundColor = '#fffff';" type="text" style=""
                                class="form-control" placeholder="@lang('Search')">
                        </div>
                        <span
                            style="font-size: 12px; color: #24282899; font-weight: 600; padding-left: 20px; padding-right: 20px;">@lang('PRODUCT DETAILS')</span>
                        <ul style="padding-left: 20px; padding-right: 20px;" id="itemsList">
                            <li data-checkbox="Product Name" id="originItem" style="font-size: 16px"
                                class="py-1 d-flex align-items-center gap-2">
                                <input class="form-check-input" checked type="checkbox" id="productNameCheckbox">
                                <label for="productNameCheckbox">@lang('Product Name')</label>
                            </li>

                            <li data-checkbox="Unit Size" id="originItem" style="font-size: 16px"
                                class="py-1 d-flex align-items-center gap-2">
                                <input class="form-check-input" checked type="checkbox" id="unitSizeCheckbox">
                                <label for="unitSizeCheckbox">@lang('Unit Size')</label>
                            </li>
                            @forelse($columns as $column)
                                @php
                                    $checkboxName = strtolower(str_replace(' ', '', $column)) . 'Checkbox';
                                @endphp
                                <li data-checkbox="{{ $column }}" style="font-size: 16px"
                                    class="py-1 d-flex align-items-center gap-2">
                                    <input class="form-check-input" checked type="checkbox" id="{{ $checkboxName }}">
                                    <label for="{{ $checkboxName }}">{{ __($column) }}</label>
                                </li>

                            @empty
                                <div></div>
                            @endforelse


                        </ul>
                    </div>

                    <div class="right-section">
                        <span style="font-size: 16px; color: #242828DE; font-weight: 700; margin-bottom: 8px;">
                            @lang(' Reorder selected columns')</span>
                        <ul id="sortableList" class="list-group">
                            <!-- List items for draggable columns -->
                            <li class="list-group-item" data-column="Product Name">
                                <svg width="10" height="16" viewBox="0 0 10 16" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="M4 14C4 15.1 3.1 16 2 16C0.9 16 0 15.1 0 14C0 12.9 0.9 12 2 12C3.1 12 4 12.9 4 14ZM2 6C0.9 6 0 6.9 0 8C0 9.1 0.9 10 2 10C3.1 10 4 9.1 4 8C4 6.9 3.1 6 2 6ZM2 0C0.9 0 0 0.9 0 2C0 3.1 0.9 4 2 4C3.1 4 4 3.1 4 2C4 0.9 3.1 0 2 0ZM8 4C9.1 4 10 3.1 10 2C10 0.9 9.1 0 8 0C6.9 0 6 0.9 6 2C6 3.1 6.9 4 8 4ZM8 6C6.9 6 6 6.9 6 8C6 9.1 6.9 10 8 10C9.1 10 10 9.1 10 8C10 6.9 9.1 6 8 6ZM8 12C6.9 12 6 12.9 6 14C6 15.1 6.9 16 8 16C9.1 16 10 15.1 10 14C10 12.9 9.1 12 8 12Z"
                                        fill="black" fill-opacity="0.23" />
                                </svg>@lang('Product Name')
                                <svg style="cursor: pointer; margin-left: auto" onclick="removeItem(this)"
                                    width="24" height="24" viewBox="0 0 24 24" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="M19 6.41L17.59 5L12 10.59L6.41 5L5 6.41L10.59 12L5 17.59L6.41 19L12 13.41L17.59 19L19 17.59L13.41 12L19 6.41Z"
                                        fill="black" fill-opacity="0.23" />
                                </svg>

                            </li>
                            <!-- Add list items for other columns -->
                            <li class="list-group-item" data-column="Unit Size">
                                <svg width="10" height="16" viewBox="0 0 10 16" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="M4 14C4 15.1 3.1 16 2 16C0.9 16 0 15.1 0 14C0 12.9 0.9 12 2 12C3.1 12 4 12.9 4 14ZM2 6C0.9 6 0 6.9 0 8C0 9.1 0.9 10 2 10C3.1 10 4 9.1 4 8C4 6.9 3.1 6 2 6ZM2 0C0.9 0 0 0.9 0 2C0 3.1 0.9 4 2 4C3.1 4 4 3.1 4 2C4 0.9 3.1 0 2 0ZM8 4C9.1 4 10 3.1 10 2C10 0.9 9.1 0 8 0C6.9 0 6 0.9 6 2C6 3.1 6.9 4 8 4ZM8 6C6.9 6 6 6.9 6 8C6 9.1 6.9 10 8 10C9.1 10 10 9.1 10 8C10 6.9 9.1 6 8 6ZM8 12C6.9 12 6 12.9 6 14C6 15.1 6.9 16 8 16C9.1 16 10 15.1 10 14C10 12.9 9.1 12 8 12Z"
                                        fill="black" fill-opacity="0.23" />
                                </svg>Unit Size
                                <svg style="cursor: pointer; margin-left: auto" onclick="removeItem(this)"
                                    width="24" height="24" viewBox="0 0 24 24" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="M19 6.41L17.59 5L12 10.59L6.41 5L5 6.41L10.59 12L5 17.59L6.41 19L12 13.41L17.59 19L19 17.59L13.41 12L19 6.41Z"
                                        fill="black" fill-opacity="0.23" />
                                </svg>

                            </li>
                            @forelse($columns as $column)
                                <li class="list-group-item" data-column={{ $column }}>
                                    <svg width="10" height="16" viewBox="0 0 10 16" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M4 14C4 15.1 3.1 16 2 16C0.9 16 0 15.1 0 14C0 12.9 0.9 12 2 12C3.1 12 4 12.9 4 14ZM2 6C0.9 6 0 6.9 0 8C0 9.1 0.9 10 2 10C3.1 10 4 9.1 4 8C4 6.9 3.1 6 2 6ZM2 0C0.9 0 0 0.9 0 2C0 3.1 0.9 4 2 4C3.1 4 4 3.1 4 2C4 0.9 3.1 0 2 0ZM8 4C9.1 4 10 3.1 10 2C10 0.9 9.1 0 8 0C6.9 0 6 0.9 6 2C6 3.1 6.9 4 8 4ZM8 6C6.9 6 6 6.9 6 8C6 9.1 6.9 10 8 10C9.1 10 10 9.1 10 8C10 6.9 9.1 6 8 6ZM8 12C6.9 12 6 12.9 6 14C6 15.1 6.9 16 8 16C9.1 16 10 15.1 10 14C10 12.9 9.1 12 8 12Z"
                                            fill="black" fill-opacity="0.23" />
                                    </svg>{{ __($column) }}
                                    <svg style="cursor: pointer; margin-left: auto" onclick="removeItem(this)"
                                        width="24" height="24" viewBox="0 0 24 24" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M19 6.41L17.59 5L12 10.59L6.41 5L5 6.41L10.59 12L5 17.59L6.41 19L12 13.41L17.59 19L19 17.59L13.41 12L19 6.41Z"
                                            fill="black" fill-opacity="0.23" />
                                    </svg>

                                </li>
                            @empty
                                <div></div>
                            @endforelse

                            <li class="list-group-item d-none" data-column="Actions"> <svg width="10"
                                    height="16" viewBox="0 0 10 16" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="M4 14C4 15.1 3.1 16 2 16C0.9 16 0 15.1 0 14C0 12.9 0.9 12 2 12C3.1 12 4 12.9 4 14ZM2 6C0.9 6 0 6.9 0 8C0 9.1 0.9 10 2 10C3.1 10 4 9.1 4 8C4 6.9 3.1 6 2 6ZM2 0C0.9 0 0 0.9 0 2C0 3.1 0.9 4 2 4C3.1 4 4 3.1 4 2C4 0.9 3.1 0 2 0ZM8 4C9.1 4 10 3.1 10 2C10 0.9 9.1 0 8 0C6.9 0 6 0.9 6 2C6 3.1 6.9 4 8 4ZM8 6C6.9 6 6 6.9 6 8C6 9.1 6.9 10 8 10C9.1 10 10 9.1 10 8C10 6.9 9.1 6 8 6ZM8 12C6.9 12 6 12.9 6 14C6 15.1 6.9 16 8 16C9.1 16 10 15.1 10 14C10 12.9 9.1 12 8 12Z"
                                        fill="black" fill-opacity="0.23" />
                                </svg></li>
                        </ul>
                    </div>
                </div>


            </div>

            <!-- Add the rest of the modal code and JavaScript as provided in the previous example -->

            <div class="modal-footer">
                <button style="border: 0;" class="primary-outline-btn me-2" data-bs-dismiss="modal">
                    @lang('Cancel')
                </button>
                <button style="width: 160px;" class="primary-filled-btn" id="applyChangesBtn">
                    @lang('Apply Columns')
                </button>
            </div>
        </div>
    </div>
</div>

@push('script')
    <script src="https://code.jquery.com/ui/1.13.1/jquery-ui.min.js"></script>

    <script>
        function addFilter() {

            const filtersContainer = $('#rows-filter');

            const filter = ` <div class="filter-row p-3 gap-3">
                    <svg class="close-button" width="24" height="24" viewBox="0 0 24 24" fill="none"
                        xmlns="http://www.w3.org/2000/svg">
                        <path
                            d="M19 6.41L17.59 5L12 10.59L6.41 5L5 6.41L10.59 12L5 17.59L6.41 19L12 13.41L17.59 19L19 17.59L13.41 12L19 6.41Z"
                            fill="#FF6128" />
                    </svg>

                    <div class="form-outline-select">

                        <select class="form-select" aria-label="Select">

                            <option selected>Columns</option>
                            <option value="Product Name">Product Name</option>

                            <option value="Price/Lb">Price/Lb</option>

                            <option value="Unit Size">Unit Size</option>
                            @forelse($columns as $column)
                                <option value={{ $column }}>{{ $column }}</option>



                            @empty
                                <div></div>
                            @endforelse


                        </select>
                        <label class="form-label">Columns</label>
                    </div>
                    <div class="form-outline-select">
                        <select class="form-select" aria-label="Select">
                            <option selected>Operator</option>
                            <option value="Contain">Contain</option>
                            <option value="Equal">Equal</option>
                        </select>
                        <label class="form-label">Operator</label>
                    </div>
                    <div class="form-outline-select">

                        <input style='background-image: none' type="text" class="form-select">
                        <label class="form-label">Value</label>
                    </div>

                </div>`

            filtersContainer.append(filter);
        }

        function moveItem($item, from, to) {
            $item.detach().appendTo(to);
        }

        function removeItem(element) {
            const dataColumnValue = $(element).closest('li').attr('data-column');

            const $selectedItem = $(`.left-section li:contains("${dataColumnValue}") input[type="checkbox"]`);
            $selectedItem.prop('checked', false);

            $(element).closest('li').addClass('d-none')
        }


        function ShowNoFiltersLabel(status) {
            if (status) $('#no-filters-label').removeClass('d-none');
            if (!status) $('#no-filters-label').addClass('d-none');
        }


        $(document).ready(function() {
            $("#sortableList").sortable({
                handle: "svg",
                cursor: "move",
                axis: "y",
                update: function(event, ui) {
                    console.log($("#sortableList").sortable("toArray"));
                }
            }).disableSelection();

        });

        $(document).ready(function() {
            $('.left-section input[type="checkbox"]').on('change', function() {
                const $this = $(this);
                const $label = $this.next('label');
                const itemName = $label.text();
                const $selectedItem = $(`.right-section li:contains("${itemName}")`);

                if ($this.prop('checked')) {
                    $selectedItem.removeClass('d-none');

                } else {
                    $selectedItem.addClass('d-none');
                }
            });

            $('#remove-filters').on('click', function() {

                $('.right-section li').detach().appendTo('.left-section');
                $('.left-section input[type="checkbox"]').prop('checked', false);
                $('#rows-filter').empty();
                ShowNoFiltersLabel(true);
                window.logRowsData()
            });

            $(document).on('click', '#add-filter', function() {
                ShowNoFiltersLabel(false)
                addFilter()
            });

            function isEmptyFilterList() {

                return $('.filter-row').length === 0;
            }

            // $('#add-filter').on('click', function() {
            //     console.log('add filter');
            //     // const $selectedOption = $('#rows-filter .filter-row').first().clone();
            //     // $selectedOption.find('select').val('Columns');
            //     // $selectedOption.find('input').val('');
            //     // $selectedOption.appendTo('#rows-filter');
            //     addFilter()
            // });

            $('#rows-filter').on('click', '.close-button', function() {
                $(this).closest('.filter-row').remove();
                if (isEmptyFilterList()) ShowNoFiltersLabel(true);
                window.logRowsData()
            });

            $('#searchInput').on('input', function() {
                var searchText = $(this).val().toLowerCase().trim();
                console.log(searchText)

                $('#itemsList > li').each(function() {
                    var text = $(this).text().toLowerCase().trim();

                    if (text.includes(searchText)) {
                        $(this).removeClass('d-none')
                    } else {
                        $(this).addClass('d-none')
                    }
                });
            });
        });
    </script>
@endpush
