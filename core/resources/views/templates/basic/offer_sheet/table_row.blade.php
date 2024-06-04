@php
    $score = 0;
    $variety = '';
    $process = '';
    $weight = 0;
    $rank = '';
    $total_price = 0;
    $display_score = false;
    $display_variety = false;
    $display_process = false;
    $display_weight = false;
    $display_rank = false;
    $price = 0;
    $origin = null;
    $altitude = null;
    $drying = null;
    $grade = null;
    $harvest = null;
    $location = null;
    $pricePerUoM = null;
    $process = null;
    $producer = null;
    $region = null;
    $score = null;
    $screen = null;
    $unitSize = null;
    $taste = null;
    $variety = null;
    $unitsAvailable = null;
    $size_price = null;
    $sizes_weight = null;
    $columns = get_offer_specifications();

    if ($product->offer_specification) {
        foreach ($product->offer_specification as $spec) {
            if (strtoupper($spec->spec_key) == 'ORIGIN') {
                //done
                $origin = $spec->Value;
            }
            elseif (strtoupper($spec->spec_key) == 'ALTITUDE') {
                //done
                $altitude = $spec->Value;
            }
            elseif (strtoupper($spec->spec_key) == 'DRYING') {
                //done
                $drying = $spec->Value;
            }
            elseif (strtoupper($spec->spec_key) == 'GRADE') {
                //done
                $grade = $spec->Value;
            }
            elseif (strtoupper($spec->spec_key) == 'HARVEST') {
                //done
                $harvest = $spec->Value;
            }
            elseif (strtoupper($spec->spec_key) == 'LOCATION') {
                //done
                $location = $spec->Value;
            }

            elseif (strtoupper($spec->spec_key) == 'PROCESSING METHOD') {
                //done
                $process = $spec->Value;
            }
            elseif (strtoupper($spec->spec_key) == 'PRODUCER') {
                //done
                $producer = $spec->Value;
            }
            elseif (strtoupper($spec->spec_key) == 'REGION') {
                //done
                $region = $spec->Value;
            }
            elseif (strtoupper($spec->spec_key) == 'SCORE') {
                $score = $spec->Value;
            }
            elseif (strtoupper($spec->spec_key) == 'SCREEN') {
                //done
                $screen = $spec->Value;
            }
            //            if (strtoupper($spec->spec_key) == 'UNIT SIZE') { // filled from prices table
            //                $unitSize = $spec->Value;
            //            }
            elseif (strtoupper($spec->spec_key) == 'TASTING NOTES') {
                //done
                $taste = $spec->Value;
            }
            elseif (strtoupper($spec->spec_key) == 'VARIETY') {
                //done
                $variety = $spec->Value;
            }
            elseif (strtoupper($spec->spec_key) == 'UNITS AVAILABLE') {
                //done
                $unitsAvailable = $spec->Value;
            }elseif (strtoupper($spec->spec_key) == 'STATUS') {
                //done
                $status = $spec->Value;
            }
        }
    }
//    $status = $product->status;
    $weight = $product->sizes_weight;
    $price = $product->prices->max("price")??0;
    // dd($price);
    $price_id = $product->price_id;
    $total_price = $weight * $price;

@endphp

<style>

.hidden-menu {
    display: none;
    position: absolute;
    background-color: #000;
    color: white;
    border: 1px solid #ccc;
    padding: 5px;
    z-index: 1;
    height: -webkit-fill-available;
    overflow-y: auto;
    left: 70px;
    border-radius: 8px;
    transform: translateX(45px);
}

.hidden-menu div {
    padding: 5px;
}

.more-sizes {
    cursor: pointer;
}
.more-sizes:hover + .hidden-menu {
    display: block;
}
</style>

<tr id="product_id_{{ $product->id }}" class="d-flex align-items-center">
    <td class="inner-product-container flex-shrink-0" data-row="Product Name">
        <div onmouseleave="hideCardOnLeave(this)">
            <a href="{{ route('offer.details', [$product->id,slug( $product->name)]) }}"
                class="auction__item-title d-block text-truncate" onmouseover="showCard(this)">{{ $product->name }}</a>
            @include('templates.basic.offer_sheet.product_popup_cart', [
                '$product' => $product,
                'idSuffix' => $idSuffix,
                'score' => $score,
                'status' => $status,
                'producer' => $producer,
                'region' => $region,
                'process_method' => $process,
                'variety' => $variety,
                'screen' => $screen,
            ])
        </div>
    </td>
    <td class="text-truncate flex-shrink-0" data-row="Price/Lb" title="{{ $price }}">
        ${{ round($price) }}</td>
        <td style="width: 210px;" class="text-truncate flex-shrink-0 d-flex gap-2 justify-items-center align-items-center position-relative" data-row="Unit Size" title="{{ $product->prices->first()->size->weight_LB??'' }}"> {{ $product->prices->first()->size->weight_LB??'' }} {{ $product->prices->first()->size->size??'' }}
            <?php
            $length = count($product->prices);
            if ($length > 1) {
                echo '<span style="color: var(--chips-color-text) !important; background-color: var(--chips-color-background) !important;border-radius: 16px;" class="badge badge-primary badge-pill more-sizes">+ ' . ($length - 1) . '</span>';
            }
            ?>
<div class="hidden-menu">
    <?php
    $firstItem = true;
    foreach ($product->prices as $key => $price) {
        if ($firstItem) {
            $firstItem = false;
            continue; 
        }
        
        echo '<div>' . $price->size->weight_LB  . $price->size->size  . '</div>';
    }
    ?>
</div>

        </td>
        @forelse($columns as $column)
        @php
            $value = null;
            foreach($product->offer_specification as $offerSpec) {
                if($offerSpec->spec_key === $column) {
                    $value = $offerSpec->Value;
                    break;
                }
            }
        @endphp
        <td class="text-truncate flex-shrink-0" data-row="{{ $column }}" title="{{ $value }}">{{ $value }}</td>
    @empty
    <td class="w-100 text-center"></td>

    @endforelse
    {{-- <td class="text-truncate flex-shrink-0" data-row="Origin" title="{{ $origin }}">{{ $origin }}</td> --}}

    <td data-row="Actions" style="border-left: 1px solid rgba(0, 0, 0, 0.12); box-shadow: 0px 2px 4px -1px rgba(36, 40, 40, 0.2);" class="flex-shrink-0">
<div class="d-flex gap-3 justify-content-center">
    @if(isset($product->sample_price) && $product->offerSheet->show_add_sample_button !== 0)
    <button style="border-radius: 24px;" class="primary-outline-btn me-2" onclick="addToCart({{ $product->sample_price->id }}, 1, '1', this)">
        <div class="spinner-border text-primary loader d-none" role="status"
            style="width: 1rem; height: 1rem; color:var(--primary-color) !important">
            <span class="sr-only">@lang('Loading...')</span>
        </div>
       @lang(' Add Sample')
    </button>
    @endif

    {{-- <button style="border-radius: 24px;" class="primary-filled-btn" onclick="addToCart({ $product->prices[1]->id}}, 1, '0', this)">
        <div class="spinner-border text-primary loader d-none" role="status"
            style="width: 1rem; height: 1rem; color: white !important">
            <span class="sr-only">Loading...</span>
        </div>
        Order
    </button> --}}
    @if($product->offerSheet->show_add_order_button !== 0 && $product->prices->count() === 1)

    <button style="border-radius: 24px;" class="primary-filled-btn"  onclick="addToCart('<?php echo $product->prices->first()->id  ?>', 1, '0', this.parentNode.previousElementSibling)">
        <div class="spinner-border text-primary loader d-none" role="status"
            style="width: 1rem; height: 1rem; color: white !important">
            <span class="sr-only">@lang('Loading...')</span>
        </div>
        @lang('Order')
    </button>
    @else
    <div class="dropdown" style="position: relative; display: inline-block;">
        <button style="border-radius: 24px;" class="primary-filled-btn" onclick="toggleDropdown(event, '{{ $product->id }}')">
            <div class="spinner-border text-primary loader d-none" role="status"
                style="width: 1rem; height: 1rem; color: white !important">
                <span class="sr-only">@lang('Loading...')</span>
            </div>
            @lang('Order')
            <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M13.825 6.91251L10 10.7292L6.175 6.91251L5 8.08751L10 13.0875L15 8.08751L13.825 6.91251Z" fill="white"/>
                </svg>

        </button>

    </div>
    @endif
    </div>
    <div id="dropdownMenu-{{$product->id}}" class="dropdown-menu" style="position: relative; top: 3px; left: 100px; display: none; background-color: #fff; border-radius: 8px; box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2); opacity: 0; transition: opacity 0.3s ease; gap: 10px; flex-direction: column;">
        <?php foreach ($product->prices as $order_price): ?>
            <div style="cursor: pointer; font-size: 14px; opacity: 0.8;" class="dropdown-item" onclick="addToCart('<?php echo $order_price->id ?>', 1, '0', this.parentNode.previousElementSibling)">
                <?php echo $order_price->size->weight_LB; ?> @lang('LB') - <?php echo $order_price->size->weight_LB; ?>
            </div>
        <?php endforeach; ?>
    </div>
    </td>

</tr>



@push('script')
<script>
    function toggleDropdown(event, id) {
        console.log(id)
        var dropdownMenu = document.getElementById(`dropdownMenu-${id}`);
        if (dropdownMenu.style.display === 'flex') {
            dropdownMenu.style.opacity = '0';
            setTimeout(function() {
                dropdownMenu.style.display = 'none';
            }, 300); // Same duration as the transition in CSS
        } else {
            dropdownMenu.style.display = 'flex';
            setTimeout(function() {
                dropdownMenu.style.opacity = '1';
            }, 10); // Delay to ensure the display property is set before transitioning
        }
        event.stopPropagation();
    }

    window.onclick = function(event) {
        if (!event.target.matches('.primary-filled-btn')) {
            var dropdowns = document.getElementsByClassName("dropdown-menu");
            for (var i = 0; i < dropdowns.length; i++) {
                var openDropdown = dropdowns[i];
                if (openDropdown.style.display === 'flex') {
                    openDropdown.style.opacity = '0';
                    setTimeout(function() {
                        openDropdown.style.display = 'none';
                    }, 300); // Same duration as the transition in CSS
                }
            }
        }
    }
</script>

<script>
    $(document).ready(function() {
        $('.more-sizes').hover(function() {
            $(this).next('.hidden-menu').toggle();
        });
    });
</script>
@endpush
