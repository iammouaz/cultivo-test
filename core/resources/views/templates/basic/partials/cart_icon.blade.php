<li class="cart-icon">
    <button id="openCartBtn" data-bs-toggle="tooltip" data-bs-placement="bottom" title="@lang('Cart')" class="border-0 bg-transparent">
        <a class="cart-icon-container" data-bs-toggle="offcanvas" href="#cart-drawer" role="button" aria-controls="cart-drawer">
            @include('templates.basic.svgIcons.cart')
            <span id="cartItemCount" class="item-count"></span>
        </a>
    </button>

    <!-- Conditionally include desktop or mobile cart drawer -->
    <div id="cart-drawer-view">
        <div class="d-block">
            @include('templates.basic.partials.cart')
        </div>

    </div>
</li>

@push('script')
    <script>
        function setCartView(cartView) {
            $('#cart-drawer-view').html(cartView);
        }

        function setItemCount(itemCount) {
            const itemCountElement = document.getElementById('cartItemCount');

            itemCountElement.textContent = itemCount;

            if (itemCount > 0) {
                itemCountElement.style.display = 'flex';
            } else {
                itemCountElement.style.display = 'none';
            }
        }

        function getCartItems() {
            $.ajax({
                url: "{{ route('user.getCartView') }}",
                success: function(response) {
                    setCartView(response.cartView);
                    setItemCount(response.itemCount);
                }
            });
        }

        $(document).ready(function () {
            getCartItems();

            // Ensure the tooltip hides on mobile when the cart button is clicked
            $('#openCartBtn').on('click', function() {
                if (window.innerWidth <= 768) {
                    $(this).tooltip('hide');
                }
            });
        });
    </script>
@endpush
