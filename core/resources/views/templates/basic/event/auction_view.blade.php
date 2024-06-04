@if(auth()->check())
    <div class="table-card mb-4">
        <div class="table-card-title d-flex align-items-center gap-3">
            <svg width="26" height="23" viewBox="0 0 26 23" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M12.6087 8.82847L11.1997 6.00885C10.8498 5.31065 10.243 4.30668 9.38402 3.49499C8.53924 2.69592 7.52739 2.15217 6.30435 2.15217C3.66283 2.15217 1.57609 4.24206 1.57609 6.75434C1.57609 8.66298 2.44924 10.0105 4.52022 12.0658C5.05136 12.5922 5.65658 13.1611 6.32641 13.7884C8.07272 15.4275 10.2446 17.467 12.6087 20.1936C14.9728 17.467 17.1447 15.4275 18.891 13.7884C19.5608 13.1611 20.1676 12.5906 20.6972 12.0658C22.7682 10.0105 23.6413 8.66298 23.6413 6.75434C23.6413 4.24206 21.5546 2.15217 18.913 2.15217C17.6884 2.15217 16.6782 2.69592 15.8334 3.49499C14.9744 4.30668 14.3676 5.31065 14.0177 6.00885L12.6087 8.82847ZM13.2265 21.8974C13.1506 21.9873 13.056 22.0596 12.9493 22.1091C12.8426 22.1587 12.7264 22.1844 12.6087 22.1844C12.491 22.1844 12.3748 22.1587 12.2681 22.1091C12.1614 22.0596 12.0668 21.9873 11.9909 21.8974C9.46756 18.8997 7.1838 16.7562 5.30353 14.9925C2.04891 11.9365 0 10.0153 0 6.75434C0 3.34211 2.8212 0.57608 6.30435 0.57608C8.82609 0.57608 10.5897 2.23097 11.6693 3.74086C12.0791 4.31613 12.3912 4.86934 12.6087 5.30434C12.8814 4.75992 13.1954 4.23722 13.548 3.74086C14.6277 2.2294 16.3913 0.57608 18.913 0.57608C22.3962 0.57608 25.2174 3.34211 25.2174 6.75434C25.2174 10.0153 23.1685 11.9365 19.9139 14.9925C18.0336 16.7578 15.7498 18.9012 13.2265 21.8958V21.8974Z" fill="url(#paint0_linear_2964_17)"/>
                <defs>
                <linearGradient id="paint0_linear_2964_17" x1="1.79855" y1="17.2749" x2="21.0628" y2="3.49543" gradientUnits="userSpaceOnUse">
                <stop stop-color="#008CBD"/>
                <stop offset="0.505208" stop-color="#008E8F"/>
                <stop offset="1" stop-color="#008C64"/>
                </linearGradient>
                </defs>
                </svg>
                
            <h2 class="mt-0">@lang('Your Favorites')</h2>
        </div>
        <div class="products-table-container">
            
            @foreach($products as $product)
                @if($product->is_fav)
                    @include('templates.basic.event.lot', ['product' => $product,'idSuffix' => ""])
                @endif
            @endforeach
            
        </div>
    </div>
@endif

<div class="table-card">
    <div class="table-card-title d-flex align-items-center gap-3">
        <svg width="21" height="21" viewBox="0 0 21 21" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M0 0H21V21H0V0ZM1.3125 1.3125V9.84375H9.84375V1.3125H1.3125ZM11.1562 1.3125V9.84375H19.6875V1.3125H11.1562ZM19.6875 11.1562H11.1562V19.6875H19.6875V11.1562ZM9.84375 19.6875V11.1562H1.3125V19.6875H9.84375Z" fill="url(#paint0_linear_1949_2732)"/>
            <defs>
            <linearGradient id="paint0_linear_1949_2732" x1="1.49776" y1="16.2287" x2="19.1259" y2="5.4242" gradientUnits="userSpaceOnUse">
            <stop stop-color="#008CBD"/>
            <stop offset="0.505208" stop-color="#008E8F"/>
            <stop offset="1" stop-color="#008C64"/>
            </linearGradient>
            </defs>
            </svg>
            
        <h2 class="mt-0">@lang('All lots')</h2>
    </div>
    <div class="products-table-container">
        
        @foreach($products as $product)
            @if(!$product->is_fav)
                @include('templates.basic.event.lot', ['product' => $product,'idSuffix' => ""])
            @endif
        @endforeach
        
    </div>
</div>
