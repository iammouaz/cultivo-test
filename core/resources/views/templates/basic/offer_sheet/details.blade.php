@extends($activeTemplate . 'layouts.frontend_commerce')

@php
    $event = $offerSheet;
    $relatedProducts = $offers;
    $overlayColor = null;
    if ($event->hero_image_overlay !== null) {
        $overlay = json_decode($event->hero_image_overlay);
        $overlayColor = str_replace('1)', '0.5)', $overlay->color ?? null);
    }
@endphp

<style>

    .backdrop {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
    }

</style>
@section('content')
    <!-- Offer sheet -->
    <section id="offer-sheet">
        <div class="cup-progress-container d-none">
            <div style="color: var(--main-svg-color) !important;" class="spinner-border" role="status">
                <span class="sr-only">Loading...</span>
              </div>
            <p>@lang('Refreshing the bid...')</p>
        </div>

        <section class="offer-sheet-hero-section w-100" id="banner">
            <div style="background-color: {{$overlayColor ?? "none"}};" class="backdrop"></div>

            <div class="hero-section-content">
                <h1 class="title m-0">
                     {{ $event->name }}

{{--                    @lang('OUR OFFERINGS')--}}
                </h1>
                <p class="event-description">
                     {{ $event->description}}
{{--                    @lang('Explore a world of quality green coffee sourced directly from producers. Tailor your order, add samples, and seamlessly connect with sellers. Elevate your coffee experience with every selection. Start your journey below.')--}}
                </p>
            </div>
        </section>

        <section class="products-table-section container position-relative">
                    @include('templates.basic.offer_sheet.table_toolbar', [
                        'event' => $event,
                        'products' => $relatedProducts,
                    ])
            <div class="row">
                <div class="col">
                    <div id="offers_table">
                        @include('templates.basic.offer_sheet.offers_table', [
                            'event' => $event,
                            'products' => $relatedProducts,
                            'term' => $term,
                        ])
                    </div>
                </div>
            </div>
        </section>

    </section>
@endsection

@push('script')
    <script>
        function showCard(element) {
            const cardElem = element.nextElementSibling;
            cardElem.classList.remove("d-none");
        }

        function hideCard(element) {
            const cardElem = element.closest(".inner-product-card");
            cardElem.classList.add("d-none");
        }

        function hideCardOnLeave(element) {
            const cardElem = element.querySelector(".inner-product-card");
            cardElem.classList.add("d-none");
        }
    </script>

    <script>
        window.addEventListener("pageshow", function(event) {
            var historyTraversal = event.persisted ||
                (typeof window.performance != "undefined" &&
                    window.performance.navigation.type === 2);
            if (historyTraversal) {
                window.location.reload();
            }
        });
    </script>
    <!-- <script src="{{ asset($activeTemplateTrue . 'js/jquery-ui.min.js') }}"></script> -->
@endpush

<script>
    function toggleDiv() {
        var div = document.getElementById('toggleDiv');
        div.style.display = div.style.display === 'none' ? 'block' : 'none';
        var divBig = document.querySelector('.filter-block-window');
        divBig.style.display = divBig.style.display === 'none' ? 'block' : 'none';
    }
</script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://code.jquery.com/ui/1.13.1/jquery-ui.min.js"></script>

<script>
    const largeImageUrl =
        "{{ getImage(imagePath()['event']['path'] . '/' . $event['image'], imagePath()['event']['size'], false) }}";
    const mediumImageUrl =
        "{{ getImage(imagePath()['event']['path'] . '/' . $event['image'], imagePath()['event']['size'], false, 'md') }}";
    const smallImageUrl =
        "{{ getImage(imagePath()['event']['path'] . '/' . $event['image'], imagePath()['event']['size'], false, 'sm') }}";



    document.addEventListener('DOMContentLoaded', function() {
        setResponsiveImage(largeImageUrl, mediumImageUrl, smallImageUrl, document.getElementById('banner'));
    });
</script>

<script>
    $(document).ready(function() {
        subscribeToEvents();


        function getRowsData() {
            const rows = $('.filter-row');
            const dataArray = [];

            rows.each(function() {
                const row = $(this);
                const column = row.find('.form-select:eq(0)').val();
                const operator = row.find('.form-select:eq(1)').val();
                const value = row.find('.form-select:eq(2)').val();

                const dataObject = {
                    column: column,
                    operator: operator,
                    value: value
                };

                dataArray.push(dataObject);
            });

            return dataArray;
        }

        function subscribeToEvents() {
            let debounceTimer;
            $('#table-search').on('input', function() {
                clearTimeout(debounceTimer);
                debounceTimer = setTimeout(function() {
                    updateSearchInfo();
                }, 500);
            });
            $('#page-size-select').on('change', function() {
                updatePageSize();
            });
            $('#prevButton').on('click', function() {
                prevPageCustom();
            });
            $('#nextButton').on('click', function() {
                console.log('next')
                nextPageCustom();
            });
        }

        function updateSearchInfo() {
            logRowsData()
        }

        function updatePageSize() {
            logRowsData()
        }

        function prevPageCustom() {
            logRowsData(-1)
        }

        function nextPageCustom() {
            logRowsData(1)
        }

        function setLoadingInProductTable() {
            $('#offers_table').html(
                '<div class="d-flex justify-content-center align-items-center" style=""><div style="position: absolute; top: 50%;" class="spinner-border" role="status"><span class="visually-hidden">Loading...</span></div></div>' +
                $('#offers_table').html());
        }

        function logRowsData(pageOffset = 0) {
            const rowsData = getRowsData();
            const currentPage = parseInt($('#currentPage').val()) + pageOffset;
            const perPage = $('#page-size-select').val();
            const search_key = $('#table-search').val();
            const filter = rowsData.length > 0 ? JSON.stringify(rowsData) : null;
            setLoadingInProductTable()
            $.ajax({
                url: "{{ route('offer_sheet.activeOffersTableView', $url) }}",
                type: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                    currentPage: currentPage,
                    perPage: perPage,
                    search_key: search_key,
                    filter: filter
                },
                success: function(response) {
                    $('#offers_table').html(response.offerTableView);
                    subscribeToEvents();
                }
            })

        }

        function handleRowChanges() {
            logRowsData();

            $('#rows-filter').on('change', '.form-select', function() {
                logRowsData();
            });
        }

        $('#add-filter').on('click', function() {
            const defaultRow = $('.filter-row').first();
            const newRow = defaultRow.clone().css('display', 'flex');
            $('#rows-filter').append(newRow);
            handleRowChanges();
        });

        $('#remove-filters').on('click', function() {
            $('.filter-row').not(':first').remove();
            logRowsData();
        });


        $('#rows-filter').on('click', '.close-button', function() {
            const rowToRemove = $(this).closest('.filter-row');
            if (!rowToRemove.is(':first-child')) {
                rowToRemove.remove();
                logRowsData();
            }
        });


        handleRowChanges();


        $("#applyChangesBtn").click(function() {
            const orderedColumns = $("#sortableList li").map(function() {
                const hasHiddenClass = $(this).hasClass('d-none');
                if (hasHiddenClass) {
                    return;
                } else {
                    return $(this).data("column");
                }
            }).get();
            reorderTableColumns([...orderedColumns, 'Actions']);

            $("#columnCustomizationModal").modal("hide");
        });

        function reorderTableColumns(orderedColumns) {
            const rows = $(".products-table tbody tr");

            rows.each(function() {
                const row = $(this);
                const cells = row.children(); // Get all cells in the row

                orderedColumns.forEach(function(column) {
                    const cell = row.find(`[data-row="${column}"]`);
                    row.append(cell
                        .remove()); // Remove and append the cell to the end of the row
                });
            });

            // Reordering the header columns
            const headerRow = $(".products-table thead tr").eq(0);

            const currentColumns = headerRow.find('th').map(function() {
                const column = $(this).data("column");
                if (column) {
                    return column;
                } else {
                    console.log("Missing data-column attribute in <th> element!");
                    return null;
                }
            }).get();

            const reorderedHeaders = orderedColumns.filter(column => currentColumns.includes(column));

            reorderedHeaders.forEach(function(column) {
                const th = headerRow.find(`th[data-column="${column}"]`);
                headerRow.append(th.detach());
            });

            // Hide columns not present in orderedColumns
            currentColumns.forEach(function(column) {
                const thToHide = headerRow.find(`th[data-column="${column}"]`);
                const indexToHide = thToHide.index();
                if (!orderedColumns.includes(column)) {
                    thToHide.hide();
                } else {
                    thToHide.show(); // Ensure that the column is shown if it's in orderedColumns
                }
                // Show the corresponding cells in the rows
                rows.each(function() {
                    const cellToHide = $(this).children().eq(indexToHide);
                    if (!orderedColumns.includes(column)) {
                        cellToHide.hide();
                    } else {
                        cellToHide
                            .show(); // Ensure that the cell is shown if its column is in orderedColumns
                    }
                });
            });
        }

    });
</script>
