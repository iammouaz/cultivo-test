@php($cart ??= [])

<div id="cart-drawer" style="height: 100vh;" class="cart-drawer hide-scrollbar offcanvas offcanvas-end border-0" tabindex="-1">
    <div class="cart-drawer-content">
        <!-- Start cart header -->
        <div class="cart-header d-flex justify-content-between align-items-center px-4 py-3">
            <h5 class="m-0">Order Summary</h5>
            <button class="border-0 p-0 bg-transparent" type="button" data-bs-dismiss="offcanvas" aria-label="Close">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                    <path d="M19 6.41L17.59 5L12 10.59L6.41 5L5 6.41L10.59 12L5 17.59L6.41 19L12 13.41L17.59 19L19 17.59L13.41 12L19 6.41Z" fill="#242828" fill-opacity="0.56"/>
                </svg>
            </button>
        </div>
        <!-- End cart header -->

        <div class="cart-body" id="cartItems">
            <!-- Start Auction cart collapse -->
            <div class="cart-collapse mb-3">
                <p>
                    <a class="collapse-link w-100 d-flex justify-content-between align-items-center gap-x-3" data-bs-toggle="collapse" href="#auction-cart-collapse" role="button" aria-expanded="true" aria-controls="auction-cart-collapse">
                        <span class="collapse-title">auction cart</span>
                        <span class="num-of-items-widget d-flex align-items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16" fill="none">
                                <path d="M7.33366 6.00033H8.66699V4.00033H10.667V2.66699H8.66699V0.666992H7.33366V2.66699H5.33366V4.00033H7.33366V6.00033ZM4.66699 12.0003C3.93366 12.0003 3.34033 12.6003 3.34033 13.3337C3.34033 14.067 3.93366 14.667 4.66699 14.667C5.40033 14.667 6.00033 14.067 6.00033 13.3337C6.00033 12.6003 5.40033 12.0003 4.66699 12.0003ZM11.3337 12.0003C10.6003 12.0003 10.007 12.6003 10.007 13.3337C10.007 14.067 10.6003 14.667 11.3337 14.667C12.067 14.667 12.667 14.067 12.667 13.3337C12.667 12.6003 12.067 12.0003 11.3337 12.0003ZM5.40033 8.66699H10.367C10.867 8.66699 11.307 8.39366 11.5337 7.98033L14.107 3.30699L12.947 2.66699L10.367 7.33366H5.68699L2.84699 1.33366H0.666992V2.66699H2.00033L4.40033 7.72699L3.50033 9.35366C3.01366 10.247 3.65366 11.3337 4.66699 11.3337H12.667V10.0003H4.66699L5.40033 8.66699Z" />
                            </svg>
                            <span class="num-of-items">0</span>
                        </span>
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                        <path d="M12 8.29492L6 14.2949L7.41 15.7049L12 11.1249L16.59 15.7049L18 14.2949L12 8.29492Z" fill="#242828" fill-opacity="0.56"/>
                        </svg>
                    </a>
                </p>
                <div class="collapse-body show" id="auction-cart-collapse">
                    <div class="empty-cart">
                        <div class="d-flex align-items-center gap-3 mb-1">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" fill="none">
                            <path d="M9.08398 5.41671H10.9173V7.25004H9.08398V5.41671ZM9.08398 9.08337H10.9173V14.5834H9.08398V9.08337ZM10.0007 0.833374C4.94065 0.833374 0.833984 4.94004 0.833984 10C0.833984 15.06 4.94065 19.1667 10.0007 19.1667C15.0607 19.1667 19.1673 15.06 19.1673 10C19.1673 4.94004 15.0607 0.833374 10.0007 0.833374ZM10.0007 17.3334C5.95815 17.3334 2.66732 14.0425 2.66732 10C2.66732 5.95754 5.95815 2.66671 10.0007 2.66671C14.0431 2.66671 17.334 5.95754 17.334 10C17.334 14.0425 14.0431 17.3334 10.0007 17.3334Z" fill="black" fill-opacity="0.87"/>
                            </svg>
                            <h3 class="m-0">Your Order Is Empty</h3>
                        </div>
                        <p class="m-0">Explore our products and add them to your order. Your selected items will appear here.</p>
                    </div>
                    <div class="non-empty-cart">
                        <div class="cart-item w-100 d-flex align-items-start gap-3">
                            <div class="d-flex flex-column gap-3">
                                <img class="cart-item-image" src="{{ $cartItem['image'] ?? asset('custom/images/images-placeholder.png') }}" alt="cart item image" />
                                <div>
                                    <p class="cart-item-property m-0">
                                        <span>Origin:</span>
                                        <span>Colombia</span>
                                    </p>
                                    <p class="cart-item-property m-0">
                                        <span>Grade:</span>
                                        <span>Excelso EP</span>
                                    </p>
                                </div>
                            </div>
                            <div class="cart-item-details">
                                <div class="d-flex justify-content-between">
                                    <span class="cart-item-type">[Auction]</span>
                                    <button class="border-0 bg-transparent">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16" fill="none">
                                        <path d="M12.6673 4.27398L11.7273 3.33398L8.00065 7.06065L4.27398 3.33398L3.33398 4.27398L7.06065 8.00065L3.33398 11.7273L4.27398 12.6673L8.00065 8.94065L11.7273 12.6673L12.6673 11.7273L8.94065 8.00065L12.6673 4.27398Z" fill="#242828" fill-opacity="0.56"/>
                                        </svg>
                                    </button>
                                </div>
                                <h2 class="cart-item-title mb-0">Manuel Vasquez Finca La Perla</h2>
                                <p class="m-0 mb-3 cart-item-subtitle">Sierra Fermina</p>
                                <p class="m-0 cart-item-price">USD$0</p>
                                <div class="cart-item-weight d-block">Weight: 762.78 lb</div>
                                <div class="cart-item-price-per-weight">USD/lb: 17.90</div>
                            </div>
                        </div>
                        <hr class="my-4" />
                        <div class="subtotal d-flex align-items-center justify-content-between">
                            <span class="text-capitalize">Subtotal (USD)</span>
                            <span id="auction-cart-subtotal" class="text-truncate">$0</span>
                        </div>
                        <a class="checkout-link w-100 text-center" href="#">go to checkout</a>
                    </div>
                </div>
            </div>
            <!-- End Auction cart collapse -->

            <!-- Start Offer list cart collapse -->
            <div class="cart-collapse mb-3">
                <p>
                    <a class="collapse-link w-100 d-flex justify-content-between align-items-center gap-x-3" data-bs-toggle="collapse" href="#offer-list-collapse" role="button" aria-expanded="true" aria-controls="offer-list-collapse">
                        <span class="collapse-title">Offer List Products</span>
                        <span class="num-of-items-widget d-flex align-items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16" fill="none">
                                <path d="M7.33366 6.00033H8.66699V4.00033H10.667V2.66699H8.66699V0.666992H7.33366V2.66699H5.33366V4.00033H7.33366V6.00033ZM4.66699 12.0003C3.93366 12.0003 3.34033 12.6003 3.34033 13.3337C3.34033 14.067 3.93366 14.667 4.66699 14.667C5.40033 14.667 6.00033 14.067 6.00033 13.3337C6.00033 12.6003 5.40033 12.0003 4.66699 12.0003ZM11.3337 12.0003C10.6003 12.0003 10.007 12.6003 10.007 13.3337C10.007 14.067 10.6003 14.667 11.3337 14.667C12.067 14.667 12.667 14.067 12.667 13.3337C12.667 12.6003 12.067 12.0003 11.3337 12.0003ZM5.40033 8.66699H10.367C10.867 8.66699 11.307 8.39366 11.5337 7.98033L14.107 3.30699L12.947 2.66699L10.367 7.33366H5.68699L2.84699 1.33366H0.666992V2.66699H2.00033L4.40033 7.72699L3.50033 9.35366C3.01366 10.247 3.65366 11.3337 4.66699 11.3337H12.667V10.0003H4.66699L5.40033 8.66699Z" />
                            </svg>
                            <span class="num-of-items">0</span>
                        </span>
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                        <path d="M12 8.29492L6 14.2949L7.41 15.7049L12 11.1249L16.59 15.7049L18 14.2949L12 8.29492Z" fill="#242828" fill-opacity="0.56"/>
                        </svg>
                    </a>
                </p>
                <div class="collapse-body show" id="offer-list-collapse">
                    <div class="empty-cart">
                        <div class="d-flex align-items-center gap-3 mb-1">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" fill="none">
                            <path d="M9.08398 5.41671H10.9173V7.25004H9.08398V5.41671ZM9.08398 9.08337H10.9173V14.5834H9.08398V9.08337ZM10.0007 0.833374C4.94065 0.833374 0.833984 4.94004 0.833984 10C0.833984 15.06 4.94065 19.1667 10.0007 19.1667C15.0607 19.1667 19.1673 15.06 19.1673 10C19.1673 4.94004 15.0607 0.833374 10.0007 0.833374ZM10.0007 17.3334C5.95815 17.3334 2.66732 14.0425 2.66732 10C2.66732 5.95754 5.95815 2.66671 10.0007 2.66671C14.0431 2.66671 17.334 5.95754 17.334 10C17.334 14.0425 14.0431 17.3334 10.0007 17.3334Z" fill="black" fill-opacity="0.87"/>
                            </svg>
                            <h3 class="m-0">Your Order Is Empty</h3>
                        </div>
                        <p class="m-0">Explore our products and add them to your order. Your selected items will appear here.</p>
                    </div>
                    <div class="non-empty-cart">
                        <div class="cart-item w-100 d-flex align-items-start gap-3">
                            <div class="d-flex flex-column gap-3">
                                <img class="cart-item-image" src="{{ $cartItem['image'] ?? asset('custom/images/images-placeholder.png') }}" alt="cart item image" />
                                <div>
                                    <p class="cart-item-property m-0">
                                        <span>Origin:</span>
                                        <span>Colombia</span>
                                    </p>
                                    <p class="cart-item-property m-0">
                                        <span>Grade:</span>
                                        <span>Excelso EP</span>
                                    </p>
                                </div>
                            </div>
                            <div class="cart-item-details">
                                <div class="d-flex justify-content-between">
                                    <span class="cart-item-type text-uppercase">[Offerings]</span>
                                    <button class="border-0 bg-transparent">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16" fill="none">
                                        <path d="M12.6673 4.27398L11.7273 3.33398L8.00065 7.06065L4.27398 3.33398L3.33398 4.27398L7.06065 8.00065L3.33398 11.7273L4.27398 12.6673L8.00065 8.94065L11.7273 12.6673L12.6673 11.7273L8.94065 8.00065L12.6673 4.27398Z" fill="#242828" fill-opacity="0.56"/>
                                        </svg>
                                    </button>
                                </div>
                                <h2 class="cart-item-title mb-0">Manuela Diaz</h2>
                                <p class="m-0 mb-3 cart-item-subtitle">Sierra Fermina</p>
                                <p class="m-0 cart-item-price">USD$0</p>
                                <div class="cart-item-weight">Weight: 0.55 lb</div>
                                <div class="cart-item-price-per-weight">USD/lb: 17.90</div>
                                <div class="cart-item-quantity">
                                    <h6 class="mb-2">Quantity</h6>
                                    <div class="mb-2 position-relative">
                                        <select onchange="updateCart()" class="cart-item-weight-select select-with-label"
                                            aria-label="weight-select">
                                            <!-- @foreach ($cartItem['other_prices'] ?? [] as $price)
                                                <option value="{{ $price['id'] ?? 0 }}"
                                                    {{ ($cartItem['price_id'] ?? 0) == ($price['id'] ?? 1) ? 'selected' : '' }}>
                                                    {{ $price['size']['weight_LB'] ?? 0 }} LB
                                                </option>
                                            @endforeach -->
                                            <option>Test option</option>
                                        </select>
                                        <label class="cart-item-select-label" for="weight-select">Select Size</label>
                                    </div>
                                    <div class="input-spinner-cart">
                                        <input onchange="updateCart()" class="input-spinner cart-quantity-number"
                                            type="number" value="{{ $cartItem['quantity'] ?? 1 }}" min="1" max="100"
                                            step="1" />
                                    </div>
                                </div>
                            </div>
                        </div>
                        <hr class="my-4" />
                        <div class="subtotal d-flex align-items-center justify-content-between">
                            <span class="text-capitalize">Subtotal (USD)</span>
                            <span id="auction-cart-subtotal" class="text-truncate">$0</span>
                        </div>
                        <a class="checkout-link w-100 text-center" href="#">go to checkout</a>
                    </div>
                </div>
            </div>
            <!-- End Offer list cart collapse -->

            <!-- Start Grand total -->
            <div class="grand-total">
                <hr />
                <p class="d-flex justify-content-between my-4">
                    <span class="text-capitalize">grand total (USD)</span>
                    <span id="grand-total-cost" class="text-truncate">$0</span>
                </p>
            </div>
            <!-- End Grand total -->
        </div>
    </div>
</div>
@push('script')
    <script>
        let OfferSheetCarts = [];
        let AuctionCarts = [];
        let AuctionSampleSetCarts = [];
        $(document).ready(function() {

            // Call the function to populate cart items initially
            setInitialCartItems()
            populateCartItems();
            updateEventHandlers();

            // Other event handlers and functions
            // $('#shipping_method').change(function() {
            //     setGrandTotalAjax();
            // });
        });
        function updateCartItems(offerSheetCarts, auctionCarts=null,auctionSampleSetCarts=null){

            @if(config('app.env')=='local')
                console.log('updateOfferingCartItems called');
                console.log('offerSheetCarts:');
                console.log(offerSheetCarts);
                console.log('auctionCarts:');
                console.log(auctionCarts);
                console.log('auctionSampleSetCarts:');
                console.log(auctionSampleSetCarts);
            @endif
            OfferSheetCarts = offerSheetCarts??OfferSheetCarts;
            AuctionCarts = auctionCarts??AuctionCarts;
            AuctionSampleSetCarts = auctionSampleSetCarts??AuctionSampleSetCarts;
            @if(config('app.env')=='local')
            console.log('updateOfferingCartItems called');
            console.log('OfferSheetCarts:');
            console.log(OfferSheetCarts);
            console.log('AuctionCarts:');
            console.log(AuctionCarts);
            console.log('AuctionSampleSetCarts:');
            console.log(AuctionSampleSetCarts);
            @endif
            populateCartItems();

        }
        function populateCartItems() {
            var carts = OfferSheetCarts.concat(AuctionCarts).concat(AuctionSampleSetCarts);//todo improve this function
            if(typeof setItemCount === 'function'){
                setItemCount(carts.length);
            }
            @if(config('app.env')=='local')
                console.log('populateCartItems called');
                console.log(carts);
            @endif
            // Clear existing items
            $('#cartItems').empty();
            var grandTotal = 0;
            // carts??= localStorage.getItem('carts');

            // localStorage.setItem('carts', JSON.stringify(carts));
            var noCarts = true;
            $.each(carts,function(key,cart){
                var sum = 0;
                var totalWeight = 0;
                noCarts = false;
                var itemHtml = '';
                if (cart.event_type == "offer_sheet")
                    itemHtml += ` <div class="cart-collapse mb-3">
                <p>
                    <div class="event-id" style="display:none">${cart.event_id}</div>
                    <a class="collapse-link w-100 d-flex justify-content-between align-items-center gap-x-3" data-bs-toggle="collapse" href="#offer-list-collapse" role="button" aria-expanded="true" aria-controls="offer-list-collapse">
                        <span class="collapse-title">Offerings (${cart.event_name})</span>
                        <span class="num-of-items-widget d-flex align-items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16" fill="none">
                                <path d="M7.33366 6.00033H8.66699V4.00033H10.667V2.66699H8.66699V0.666992H7.33366V2.66699H5.33366V4.00033H7.33366V6.00033ZM4.66699 12.0003C3.93366 12.0003 3.34033 12.6003 3.34033 13.3337C3.34033 14.067 3.93366 14.667 4.66699 14.667C5.40033 14.667 6.00033 14.067 6.00033 13.3337C6.00033 12.6003 5.40033 12.0003 4.66699 12.0003ZM11.3337 12.0003C10.6003 12.0003 10.007 12.6003 10.007 13.3337C10.007 14.067 10.6003 14.667 11.3337 14.667C12.067 14.667 12.667 14.067 12.667 13.3337C12.667 12.6003 12.067 12.0003 11.3337 12.0003ZM5.40033 8.66699H10.367C10.867 8.66699 11.307 8.39366 11.5337 7.98033L14.107 3.30699L12.947 2.66699L10.367 7.33366H5.68699L2.84699 1.33366H0.666992V2.66699H2.00033L4.40033 7.72699L3.50033 9.35366C3.01366 10.247 3.65366 11.3337 4.66699 11.3337H12.667V10.0003H4.66699L5.40033 8.66699Z" />
                            </svg>
                            <span class="num-of-items">${cart.product_count}</span>
                        </span>
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                        <path d="M12 8.29492L6 14.2949L7.41 15.7049L12 11.1249L16.59 15.7049L18 14.2949L12 8.29492Z" fill="#242828" fill-opacity="0.56"/>
                        </svg>
                    </a>
                </p>
                <div class="collapse-body show" id="offer-list-collapse">`;
                else if(cart.event_type == "auction")
                    itemHtml += `
<div class="cart-collapse mb-3">
                <p>
                    <div class="event-id" style="display:none">${cart.event_id}</div>
                    <a class="collapse-link w-100 d-flex justify-content-between align-items-center gap-x-3" data-bs-toggle="collapse" href="#auction-cart-collapse" role="button" aria-expanded="true" aria-controls="auction-cart-collapse">
                        <span class="collapse-title">auction cart (${cart.event_name})</span>
                        <span class="num-of-items-widget d-flex align-items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16" fill="none">
                                <path d="M7.33366 6.00033H8.66699V4.00033H10.667V2.66699H8.66699V0.666992H7.33366V2.66699H5.33366V4.00033H7.33366V6.00033ZM4.66699 12.0003C3.93366 12.0003 3.34033 12.6003 3.34033 13.3337C3.34033 14.067 3.93366 14.667 4.66699 14.667C5.40033 14.667 6.00033 14.067 6.00033 13.3337C6.00033 12.6003 5.40033 12.0003 4.66699 12.0003ZM11.3337 12.0003C10.6003 12.0003 10.007 12.6003 10.007 13.3337C10.007 14.067 10.6003 14.667 11.3337 14.667C12.067 14.667 12.667 14.067 12.667 13.3337C12.667 12.6003 12.067 12.0003 11.3337 12.0003ZM5.40033 8.66699H10.367C10.867 8.66699 11.307 8.39366 11.5337 7.98033L14.107 3.30699L12.947 2.66699L10.367 7.33366H5.68699L2.84699 1.33366H0.666992V2.66699H2.00033L4.40033 7.72699L3.50033 9.35366C3.01366 10.247 3.65366 11.3337 4.66699 11.3337H12.667V10.0003H4.66699L5.40033 8.66699Z" />
                            </svg>
                            <span class="num-of-items">${cart.product_count}</span>
                        </span>
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                        <path d="M12 8.29492L6 14.2949L7.41 15.7049L12 11.1249L16.59 15.7049L18 14.2949L12 8.29492Z" fill="#242828" fill-opacity="0.56"/>
                        </svg>
                    </a>
                </p>
                <div class="collapse-body show" id="auction-cart-collapse">`
                else//auction sample set
                    itemHtml += `<div class="cart-collapse mb-3">
                <p>
                    <div class="event-id" style="display:none">${cart.event_id}</div>
                    <a class="collapse-link w-100 d-flex justify-content-between align-items-center gap-x-3" data-bs-toggle="collapse" href="#auction-cart-collapse" role="button" aria-expanded="true" aria-controls="auction-cart-collapse">
                        <span class="collapse-title">auction cart (${cart.event_name})</span>
                        <span class="num-of-items-widget d-flex align-items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16" fill="none">
                                <path d="M7.33366 6.00033H8.66699V4.00033H10.667V2.66699H8.66699V0.666992H7.33366V2.66699H5.33366V4.00033H7.33366V6.00033ZM4.66699 12.0003C3.93366 12.0003 3.34033 12.6003 3.34033 13.3337C3.34033 14.067 3.93366 14.667 4.66699 14.667C5.40033 14.667 6.00033 14.067 6.00033 13.3337C6.00033 12.6003 5.40033 12.0003 4.66699 12.0003ZM11.3337 12.0003C10.6003 12.0003 10.007 12.6003 10.007 13.3337C10.007 14.067 10.6003 14.667 11.3337 14.667C12.067 14.667 12.667 14.067 12.667 13.3337C12.667 12.6003 12.067 12.0003 11.3337 12.0003ZM5.40033 8.66699H10.367C10.867 8.66699 11.307 8.39366 11.5337 7.98033L14.107 3.30699L12.947 2.66699L10.367 7.33366H5.68699L2.84699 1.33366H0.666992V2.66699H2.00033L4.40033 7.72699L3.50033 9.35366C3.01366 10.247 3.65366 11.3337 4.66699 11.3337H12.667V10.0003H4.66699L5.40033 8.66699Z" />
                            </svg>
                            <span class="num-of-items">${cart.product_count || 1}</span>
                        </span>
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                        <path d="M12 8.29492L6 14.2949L7.41 15.7049L12 11.1249L16.59 15.7049L18 14.2949L12 8.29492Z" fill="#242828" fill-opacity="0.56"/>
                        </svg>
                    </a>
                </p>
                <div class="collapse-body show" id="auction-cart-collapse">`
                $.each(cart.items, function (key, cartItem) {
                    var total_price = Math.round((cartItem.price || 0) * (cartItem.quantity || 0), 2);
                    var price_lb = Math.round((parseFloat(cartItem.price_lb) || 0), 2) || 0;
                    var weight = parseFloat(cartItem.size_weight) || 0;
                    var price_id = cartItem.price_id || 0;
                    var is_sample = (cartItem.is_sample ||0 )==="1";
                    sum += total_price;
                    totalWeight += weight * (cartItem.quantity || 0);
                    if(cart.event_type == "offer_sheet")
                        itemHtml += `
<div class="cart-item w-100 d-flex align-items-start gap-3">
<div class="product_price_id" style="display:none">${price_id}</div>
<div class="cart-product-type" style="display:none">${is_sample}</div>
        <div class="d-flex flex-column gap-3">
            <img class="cart-item-image" src="${cartItem.image || '{{asset('custom/images/images-placeholder.png')}}'}" alt="cart item image" />
            <div>
                <p class="cart-item-property m-0">
                    <span>Origin:</span>
                    <span>${cartItem.origin || ''}</span>
                </p>
                <p class="cart-item-property m-0">
                    <span>Grade:</span>
                    <span>${cartItem.grade || ''}</span>
                </p>
            </div>
        </div>
        <div class="cart-item-details">
            <div class="d-flex justify-content-between">
                <span class="cart-item-type text-uppercase">${cartItem.type || ''}</span>
                <button class="border-0 bg-transparent remove-item-button">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16" fill="none">
                        <path style="fill: black !important;" d="M12.6673 4.27398L11.7273 3.33398L8.00065 7.06065L4.27398 3.33398L3.33398 4.27398L7.06065 8.00065L3.33398 11.7273L4.27398 12.6673L8.00065 8.94065L11.7273 12.6673L12.6673 11.7273L8.94065 8.00065L12.6673 4.27398Z" fill="#242828" fill-opacity="0.56"/>
                    </svg>
                </button>
            </div>
            <h2 class="cart-item-title mb-0">${cartItem.name || ''} ${is_sample?'(sample request)':''}</h2>
            <p class="m-0 mb-3 cart-item-subtitle">${cartItem.origin || ''}</p>
            <p class="m-0 cart-item-price">USD$${total_price}</p>
            <div class="cart-item-weight">Weight: ${weight.toFixed(2)} lb</div>
            <div class="cart-item-price-per-weight">USD/lb: ${price_lb.toFixed(2)}</div>
            <div class="cart-item-quantity">
                <h6 class="mb-2">Select Size</h6>
                <div class="mb-2 position-relative">
                    <select onchange="updateCart()" class="cart-item-weight-select select-with-label"
                        aria-label="weight-select">
                        ${cartItem.other_prices.map(price => `
                            <option value="${price.id}" ${price.id == price_id ? 'selected' : ''}>
                                ${price.size.weight_LB} LB
                            </option>
                        `).join('')}

                    </select>

                </div>
                <div class="input-spinner-cart">
                    <input class="input-spinner cart-quantity-number"
                        type="number" value="${cartItem.quantity || 1}" min="1" max="100"
                        step="1" aria-label="quantity-select"/>
                        <label class="cart-item-select-label" for="quantity-select">Quantity</label>
                </div>
            </div>
        </div>
    </div>
    <hr class="my-4" />`;
                    else if(cart.event_type == "auction")
                        itemHtml += `

                    <div class="non-empty-cart">
                <div class="cart-item w-100 d-flex align-items-start gap-3">
                    <div class="d-flex flex-column gap-3">
                    <img class="cart-item-image" src="${cartItem.image || '{{asset('custom/images/images-placeholder.png')}}'}" alt="cart item image" />
                    <div>
                    <p class="cart-item-property m-0">
                    <span>Origin:</span>
                <span>${cartItem.origin || ''}</span>
            </p>
                <p class="cart-item-property m-0">
                    <span>Grade:</span>
                    <span>${cartItem.grade || ''}</span>
                </p>
            </div>
            </div>
                <div class="cart-item-details">
                    <div class="d-flex justify-content-between">
                        <span class="cart-item-type">${cartItem.type || ''}</span>

                    </div>
                    <h2 class="cart-item-title mb-0">${cartItem.name || ''}</h2>
                    <p class="m-0 mb-3 cart-item-subtitle">${cartItem.origin || ''}</p>
                    <p class="m-0 cart-item-price">USD$${total_price}</p>
                    <div class="cart-item-weight d-block">Weight: ${weight.toFixed(2)} lb</div>
                    <div class="cart-item-price-per-weight">USD/lb: ${price_lb.toFixed(2)}</div>
                </div>
            </div>
                <hr class="my-4" />
                    `;
                    else//auction sample set
                        itemHtml +=  `

                    <div class="non-empty-cart">
                <div class="cart-item w-100 d-flex align-items-start gap-3">
                    <div class="d-flex flex-column gap-3">
                    <img class="cart-item-image" src="${cartItem.image || '{{asset('custom/images/images-placeholder.png')}}'}" alt="cart item image" />
                </div>
                <div class="cart-item-details">
                    <div class="d-flex justify-content-between">
                        <span class="cart-item-type">${cartItem.type || ''}</span>
                        <button class="border-0 bg-transparent remove-sample-item-button">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16" fill="none">
                                <path d="M12.6673 4.27398L11.7273 3.33398L8.00065 7.06065L4.27398 3.33398L3.33398 4.27398L7.06065 8.00065L3.33398 11.7273L4.27398 12.6673L8.00065 8.94065L11.7273 12.6673L12.6673 11.7273L8.94065 8.00065L12.6673 4.27398Z" fill="#242828" fill-opacity="0.56"/>
                            </svg>
                        </button>
                    </div>
                    <h2 class="cart-item-title mb-0">Sample Set</h2>
                    <p class="m-0 mb-3 cart-item-subtitle">${cartItem.event_name || ''}</p>
                    <p class="m-0 cart-item-price">USD$${total_price}</p>
                    <div class="cart-item-weight d-block">Box: ${cartItem.number_of_samples_per_box} Samples</div>
                    <div class="cart-item-price-per-weight">${cartItem.weight_per_sample_grams}gr each</div>
                    <div class="cart-item-quantity">
                <h6 class="mb-2">Select Size</h6>

                <div class="input-spinner-cart">
                    <input class="input-spinner sample-cart-quantity-number"
                        type="number" value="${cartItem.quantity || 1}" min="1" max="100"
                        step="1" aria-label="quantity-select"/>
                        <label class="cart-item-select-label" for="quantity-select">Quantity</label>
                </div>
            </div>
                </div>
            </div>
                <hr class="my-4" />
                    `;

                });
                if(cart.event_type == "offer_sheet")
                    itemHtml+=` <div class="subtotal d-flex align-items-center justify-content-between">
                            <span class="text-capitalize">Subtotal (USD)</span>
                            <span id="auction-cart-subtotal-offer-${cart.event_id}" class="text-truncate">$${sum}</span>
                        </div>
                        <a class="checkout-link w-100 text-center" href="{{url('/checkout/order')}}/${cart.event_id}">go to checkout</a>
                    </div>
                </div>
            </div>`;
                else if(cart.event_type == "auction")
                    itemHtml+=`
    <hr class="my-4" />
                        <div class="subtotal d-flex align-items-center justify-content-between">
                            <span class="text-capitalize">Subtotal (USD)</span>
                            <span id="auction-cart-subtotal-auction-${cart.event_id}" class="text-truncate">$${sum}</span>
                        </div>
                        <a class="checkout-link w-100 text-center" href="{{url('user/checkout')}}/${cart.event_id}">go to checkout</a>
                    </div>
                </div>
            </div>`;
                else //auction sample set
                    itemHtml+=`
    <hr class="my-4" />
                        <div class="subtotal d-flex align-items-center justify-content-between">
                            <span class="text-capitalize">Subtotal (USD)</span>
                            <span id="auction-cart-subtotal-auction-${cart.event_id}" class="text-truncate">$${sum}</span>
                        </div>
                        <a class="checkout-link w-100 text-center" href="{{url('/checkout_sample_set/order')}}/${cart.event_id}">go to checkout</a>
                    </div>
                </div>
            </div>`;

                $('#cartItems').append(itemHtml);
                grandTotal += sum;

            });
            if(noCarts)
                $('#cartItems').append(`

<div class="cart-collapse mb-3">
                <p>
                    <a class="collapse-link w-100 d-flex justify-content-between align-items-center gap-x-3" data-bs-toggle="collapse" href="#auction-cart-collapse" role="button" aria-expanded="true" aria-controls="auction-cart-collapse">
                        <span class="collapse-title">Offerings</span>
                        <span class="num-of-items-widget d-flex align-items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16" fill="none">
                                <path d="M7.33366 6.00033H8.66699V4.00033H10.667V2.66699H8.66699V0.666992H7.33366V2.66699H5.33366V4.00033H7.33366V6.00033ZM4.66699 12.0003C3.93366 12.0003 3.34033 12.6003 3.34033 13.3337C3.34033 14.067 3.93366 14.667 4.66699 14.667C5.40033 14.667 6.00033 14.067 6.00033 13.3337C6.00033 12.6003 5.40033 12.0003 4.66699 12.0003ZM11.3337 12.0003C10.6003 12.0003 10.007 12.6003 10.007 13.3337C10.007 14.067 10.6003 14.667 11.3337 14.667C12.067 14.667 12.667 14.067 12.667 13.3337C12.667 12.6003 12.067 12.0003 11.3337 12.0003ZM5.40033 8.66699H10.367C10.867 8.66699 11.307 8.39366 11.5337 7.98033L14.107 3.30699L12.947 2.66699L10.367 7.33366H5.68699L2.84699 1.33366H0.666992V2.66699H2.00033L4.40033 7.72699L3.50033 9.35366C3.01366 10.247 3.65366 11.3337 4.66699 11.3337H12.667V10.0003H4.66699L5.40033 8.66699Z" />
                            </svg>
                            <span class="num-of-items">0</span>
                        </span>
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                        <path d="M12 8.29492L6 14.2949L7.41 15.7049L12 11.1249L16.59 15.7049L18 14.2949L12 8.29492Z" fill="#242828" fill-opacity="0.56"/>
                        </svg>
                    </a>
                </p>
                <div class="collapse-body show" id="auction-cart-collapse">

                    <div class="empty-cart">
                        <div class="d-flex align-items-center gap-3 mb-1">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" fill="none">
                            <path d="M9.08398 5.41671H10.9173V7.25004H9.08398V5.41671ZM9.08398 9.08337H10.9173V14.5834H9.08398V9.08337ZM10.0007 0.833374C4.94065 0.833374 0.833984 4.94004 0.833984 10C0.833984 15.06 4.94065 19.1667 10.0007 19.1667C15.0607 19.1667 19.1673 15.06 19.1673 10C19.1673 4.94004 15.0607 0.833374 10.0007 0.833374ZM10.0007 17.3334C5.95815 17.3334 2.66732 14.0425 2.66732 10C2.66732 5.95754 5.95815 2.66671 10.0007 2.66671C14.0431 2.66671 17.334 5.95754 17.334 10C17.334 14.0425 14.0431 17.3334 10.0007 17.3334Z" fill="black" fill-opacity="0.87"/>
                            </svg>
                            <h3 class="m-0">Your Order Is Empty</h3>
                        </div>
                        <p class="m-0">Explore our products and add them to your order. Your selected items will appear here.</p>
                    </div>
<hr class="my-4" />
                        <div class="subtotal d-flex align-items-center justify-content-between">
                            <span class="text-capitalize">Subtotal (USD)</span>
                            <span id="auction-cart-subtotal" class="text-truncate">$0</span>
                        </div>
                        <a class="checkout-link w-100 text-center" disabled href="#">go to checkout</a>
                    </div>
                </div>
`);
            $('#grand-total-cost').text('$' + grandTotal);

        }
        function updateEventHandlers() {



            $('.remove-item-button').click(function() {
                const event= $(this).closest('.cart-collapse');
                $(this).closest('.cart-item').remove();
                updateCart(event);
            })
            $(".cart-quantity-number").on('input', function() {
                const event= $(this).closest('.cart-collapse');
                updateCart(event);
            });
            $(".cart-item-weight-select").on('change', function() {
                const event= $(this).closest('.cart-collapse');
                updateCart(event);
            });
            $('.remove-sample-item-button').click(function() {
                const event= $(this).closest('.cart-collapse');
                $(this).closest('.cart-item').remove();
                updateSampleSetCart(event);
            })
            $(".sample-cart-quantity-number").on('input', function() {
                const event= $(this).closest('.cart-collapse');
                updateSampleSetCart(event);
            });

        }


            function gatherCartData(event_cart) {
                const cartItems = [];

                event_cart.find('.cart-item').each(function() {
                    const product_price_id = $(this).find('.cart-item-weight-select').val().trim();
                    const quantity = $(this).find('.cart-quantity-number').val().trim();
                    const is_sample = $(this).find('.cart-product-type')?.text().trim() === '1';

                    const cartItem = {
                        product_price_id: Number(product_price_id),
                        quantity: Number(quantity),
                        is_sample: Boolean(is_sample) ? 1 : 0,

                    };
                    cartItems.push(cartItem);
                });
                @if(config('app.env')=='local')
                    console.log('gatherCartData called');
                    console.log(cartItems);
                @endif
                return cartItems.length ? cartItems : 0;
            }
            function gatherSampleSetCartData(event_cart) {
                const cartItems = [];

                event_cart.find('.cart-item').each(function() {
                    const event_id = event_cart.find('.event-id').text().trim();
                    const quantity = $(this).find('.sample-cart-quantity-number').val().trim();

                    const cartItem = {
                        event_id: Number(event_id),
                        quantity: Number(quantity),

                    };
                    cartItems.push(cartItem);
                });
                @if(config('app.env')=='local')
                    console.log('gatherCartData called');
                    console.log(cartItems);
                @endif
                return cartItems.length ? cartItems : 0;
            }


            function updateCart(event_cart) {
                const carts = gatherCartData(event_cart)
                const event_id = event_cart.find('.event-id').text().trim();

                // $('#cartDrawer').html(
                //     '<div class="d-flex justify-content-center align-items-center" style=""><div style="position: absolute; top: 50%;" class="spinner-border" role="status"><span class="visually-hidden">Loading...</span></div></div>' +
                //     $('#cartDrawer').html());
                $.ajax({
                    url: "{{ route('user.updateCart') }}",
                    type: "POST",
                    data: {
                        _token: "{{ csrf_token() }}",
                        carts: carts || [],
                        offer_sheet_id: event_id
                    },
                    success: function(response) {
                        // $('#cartDrawer').find('.spinner-border').parent().remove();
                        // if(typeof setCartView === 'function')
                        //     setCartView(response.cartView);
                        // if(typeof setItemCount === 'function')
                        //     setItemCount(response.itemCount);
                        setCartItems(response.cartItems);
                        // $('#cartDrawer').toggleClass('open');
                    },
                    error: function(error) {
                        $('#cartDrawer').find('.spinner-border').parent().remove();

                        if (error.responseJSON && error.responseJSON.errors) {
                            const errors = error.responseJSON.errors;
                            Object.values(errors).forEach(errorMessages => {
                                errorMessages.forEach(errorMessage => {
                                    iziToast.error({
                                        title: 'Error',
                                        message: errorMessage,
                                        position: 'topRight'
                                    });
                                });
                            });
                        } else {
                            iziToast.error({
                                title: 'Error',
                                message: 'Failed to add cart',
                                position: 'topRight'
                            });
                        }
                    }
                });
            }
            function updateSampleSetCart(event_cart) {
                const carts = gatherSampleSetCartData(event_cart)
                const event_id = event_cart.find('.event-id').text().trim();

                // $('#cartDrawer').html(
                //     '<div class="d-flex justify-content-center align-items-center" style=""><div style="position: absolute; top: 50%;" class="spinner-border" role="status"><span class="visually-hidden">Loading...</span></div></div>' +
                //     $('#cartDrawer').html());
                $.ajax({
                    url: "{{ route('user.updateCartSampleSet') }}",
                    type: "POST",
                    data: {
                        _token: "{{ csrf_token() }}",
                        carts: carts || [],
                        event_id: event_id
                    },
                    success: function(response) {
                        // $('#cartDrawer').find('.spinner-border').parent().remove();
                        // if(typeof setCartView === 'function')
                        //     setCartView(response.cartView);
                        // if(typeof setItemCount === 'function')
                        //     setItemCount(response.itemCount);
                        setSampleSetCartItems(response.cartItems);
                        // $('#cartDrawer').toggleClass('open');
                    },
                    error: function(error) {
                        $('#cartDrawer').find('.spinner-border').parent().remove();
                        @if(config('app.env')=='local')
                        console.log("error is");
                        console.log(error.responseJSON);
                        @endif
                        if (error.responseJSON.error && error.responseJSON.error.length > 0) {

                            iziToast.error({
                                title: 'Error',
                                message: error.responseJSON.error,
                                position: 'topRight'
                            });

                        } else {

                            if (error.responseJSON && error.responseJSON.errors) {
                                const errors = error.responseJSON.errors;
                                Object.values(errors).forEach(errorMessages => {
                                    errorMessages.forEach(errorMessage => {
                                        iziToast.error({
                                            title: 'Error',
                                            message: errorMessage,
                                            position: 'topRight'
                                        });
                                    });
                                });
                            } else {
                                iziToast.error({
                                    title: 'Error',
                                    message: 'Failed to add cart',
                                    position: 'topRight'
                                });
                            }
                        }


                    }
                });
            }

            $('#delete-cart-item').on('click', function() {
                const event_cart = $(this).closest('.cart-collapse');
                const cartItem = $(this).closest('.cart-item');
                cartItem.remove();
                updateCart(event_cart)
            });
            function setCartItems(cartItems) {
                @if(config('app.env')=='local')
                    console.log('setCartItems called');
                    console.log(cartItems);
                @endif
                //convert to array
                cartItems = Object.values(cartItems);
                updateCartItems(cartItems);
                updateEventHandlers();

            }
            function setSampleSetCartItems(cartItems) {
                @if(config('app.env')=='local')
                    console.log('setCartItems called');
                    console.log(cartItems);
                @endif
                //convert to array
                cartItems = Object.values(cartItems);
                updateCartItems(null,null,cartItems);
                updateEventHandlers();

            }
            function getAuctionCartItems(carts){
                return carts.filter(cart => cart.event_type == 'auction');//auction carts aren't changed by user
            }
            function getOfferSheetCartItems(carts){
                return carts.filter(cart => cart.event_type == 'offer_sheet');//auction carts aren't changed by user
            }
            function getAuctionSampleSetCartItems(carts){
                return carts.filter(cart => cart.event_type == 'auction_sample_set');//auction carts aren't changed by user
            }
            function getInitialCartItems(){
                return {!! json_encode(\App\Http\Controllers\CartController::getCarts()) !!};
            }
            function setInitialCartItems(){
                const initialCarts = getInitialCartItems();
                OfferSheetCarts = getOfferSheetCartItems(initialCarts);
                AuctionCarts = getAuctionCartItems(initialCarts);
                AuctionSampleSetCarts = getAuctionSampleSetCartItems(initialCarts);
            }
    </script>
<script src="{{ asset('assets/global/js/product-details.js') }}"></script>
@endpush
