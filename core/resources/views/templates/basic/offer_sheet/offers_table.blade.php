@php
    $products= $offers;
    $currentPage = $products->currentPage();
    $perPage = $products->perPage();
    $firstItem = $products->firstItem();
    $lastItem = $products->lastItem();
    $total = $products->total();
    $lastPage = $products->lastPage();
    $columns = get_offer_specifications($event->id??null)
@endphp

<div class="table-card">
    <div class="table-actions d-flex align-items-center justify-content-between gap-3">
        <div class="products-table-search">
            <button class="table-search-btn reset-button-styles"><i class="fas fa-search"></i></button>
            <label for="table-search" class="d-none">@lang('Search')</label>
            {{-- todo lang --}}
            <input type="text" id="table-search" name="table-serach" placeholder={{__('Search...')}} class="table-serach-input"
                onfocus="this.select()" value="{{ $term ?? '' }}" />
        </div>
        @csrf
        <input type="hidden" id="currentPage" name="currentPage" value="{{ $currentPage }}">
        <input type="hidden" id="perPage" name="perPage" value="{{ $perPage }}">
        <div class="products-table-pagination d-flex align-items-center gap-4">
            <div class="page-size d-flex align-items-center gap-2">
                <label>@lang('Rows per page:')</label>
                <div class="weight-select-container change-bg-hover-effect">
                    <select class="table-row-per-page" id="page-size-select">
                        <option value="20" {{ $perPage == '20' ? 'selected' : '' }}>
                            20
                        </option>
                        <option value="30" {{ $perPage == '30' ? 'selected' : '' }}>
                            30
                        </option>
                        <option value="40" {{ $perPage == '40' ? 'selected' : '' }}>
                            40
                        </option>
                    </select>
                </div>
            </div>
            <div class="total-rows">
                <span>{{ $firstItem }}</span>
                <span>-</span>
                <span>{{ $lastItem }}</span>
                <span class="px-1">@lang('of')</span>
                <span>{{ $total }}</span>
            </div>
            <div class="table-navigation-btns d-flex align-items-center gap-4">
                <button id="prevButton" class="reset-button-styles p-0" {{ $currentPage == 1 ? 'disabled' : '' }}>
                    <i class="fas fa-chevron-left"></i>
                </button>
                <button id="nextButton" class="reset-button-styles p-0"
                    {{ $currentPage == $lastPage ? 'disabled' : '' }}>
                    <i class="fas fa-chevron-right"></i>
                </button>
            </div>
        </div>
    </div>
    <div class="products-table-container table-responsive">
        <table class="products-table w-100">
            <thead>
                <tr class="d-flex">
                    <th scope="col" data-column="Product Name">@lang('Product Name')</th>
                    <th scope="col" data-column="Price/Lb">@lang('Price/Lb')</th>
                    <th style="width: 210px;" scope="col" data-column="Unit Size">@lang('Unit Size')</th>  
                    @foreach($columns as $item)
                    {{-- todo lang --}}
                        <th scope="col" data-column="{{ $item }}">{{ __($item) }}</th>
                    @endforeach
                    <th style="border-left: 1px solid rgba(0, 0, 0, 0.12); box-shadow: 0px 2px 4px -1px rgba(36, 40, 40, 0.2);text-align: center;" scope="col" data-column="Actions">@lang('Actions')</th>
                </tr>
            </thead>
            <tbody>
                @forelse($products as $product)
                    @include('templates.basic.offer_sheet.table_row', ['product' => $product, 'idSuffix' => ''])
                @empty
                    <tr>
                        <td class="w-100 text-center">@lang('No products yet')</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
