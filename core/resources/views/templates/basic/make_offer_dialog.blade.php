<style>
    .modal-header {
        color: var(--text-primary, rgba(36, 40, 40, 0.87));
        font-feature-settings: 'clig' off,
            'liga' off;
        /* typography/h6 */
        font-family: Lato;
        font-size: 20px;
        font-style: normal;
        font-weight: 500;
        line-height: 160%;
        /* 32px */
        letter-spacing: 0.15px;
        color: rgba(36, 40, 40, 0.87)
    }

    .modal-body {
        border-top: 1px solid var(--divider, rgba(0, 0, 0, 0.12));
        border-bottom: 1px solid var(--divider, rgba(0, 0, 0, 0.12));
    }

    .typography-h6 {
        color: var(--text-primary, rgba(36, 40, 40, 0.87));
        font-feature-settings: 'clig' off, 'liga' off;
        font-family: Lato;
        font-size: 34px;
        font-style: normal;
        font-weight: 500;
        line-height: 123.5%;
        /* 41.99px */
        letter-spacing: 0.25px;
    }

    .modal-dialog {
        max-width: 100%;
        width: 900px;
    }

    .typography-paragraph {
        color: var(--text-secondary, rgba(36, 40, 40, 0.60));
        font-feature-settings: 'clig' off, 'liga' off;
        /* typography/body1 */
        font-family: Lato;
        font-size: 16px;
        font-style: normal;
        font-weight: 400;
        line-height: 150%;
        /* 24px */
        letter-spacing: 0.15px;
    }

    .changeAmount {
        padding: 4px 16px
    }

    .helper-text {
        color: var(--text-secondary, rgba(36, 40, 40, 0.60));
        font-feature-settings: 'clig' off, 'liga' off;
        /* components/helper-text */
        font-family: Lato;
        font-size: 12px;
        font-style: normal;
        font-weight: 400;
        line-height: 166%;
        /* 19.92px */
        letter-spacing: 0.4px;
    }

    .form-control:focus {
        background: transparent
    }

    .form-control {
        color: black !important;
    }

    .button-primary {
        color: var(--primary-contrast, #FFF);
        /* components/button-medium */
        font-family: Lato;
        font-size: 14px;
        font-style: normal;
        font-weight: 500;
        line-height: 24px;
        /* 171.429% */
        letter-spacing: 0.4px;
        text-transform: uppercase;
        border-radius: 24px;
        background: var(--primary-main, #008E8F) !important;
        border: none !important;
        /* elevation/2 */
        box-shadow: 0px 3px 1px -2px rgba(36, 40, 40, 0.20), 0px 2px 2px 0px rgba(36, 40, 40, 0.14), 0px 1px 5px 0px rgba(36, 40, 40, 0.12);
    }

    .result-label {
        color: var(--text-primary, rgba(36, 40, 40, 0.87));
        font-feature-settings: 'clig' off, 'liga' off;
        /* typography/h5 */
        font-family: Lato;
        font-size: 24px;
        font-style: normal;
        font-weight: 400;
        line-height: 133.4%;
        /* 32.016px */

    }

    .input-prefix-container {
        display: flex;
        align-items: center
    }

    .input-prefix-container input {
        border-right: none;
        border-top-right-radius: 0px;
        border-bottom-right-radius: 0px;
    }

    .input-prefix-container .prefix-container {
        border: 1px solid #ced4da;
        border-left: none;
        border-top-right-radius: .3rem;
        border-bottom-right-radius: .3rem;
        padding: 11px;
        color: var(--text-secondary, rgba(36, 40, 40, 0.60));
        font-feature-settings: 'clig' off, 'liga' off;
        /* typography/body1 */
        font-family: Lato;
        font-size: 16px;
        font-style: normal;
        font-weight: 400;
        line-height: 150%;
        /* 24px */
        letter-spacing: 0.15px;
    }
</style>

<!-- Button trigger modal -->
{{-- This button is just for demo puroposes please remove it when you integrate the offer modal in the platform --}}
<button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modal">
    Launch modal
</button>

<!-- Modal -->
<div class="modal fade" id="modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header" style="background: transparent">
                <h1 class="fs-5 m-0" id="exampleModalLabel">Make Offer</h1>
            </div>
            <div class="modal-body">
                <div class="d-flex" style="gap: 20px">
                    <img src="https://picsum.photos/200/300" style="flex-shrink: 0;border-radius: 4px;" width="396px"
                        height="417.233px" />
                    <div>
                        <h6 class="typography-h6">Jose Eustasio Rivera Pérez</h6>
                        <p class="typography-paragraph ">
                            Indulge in the exquisite craftsmanship of a dedicated farmer from Acatenango, Guatemala.
                            Nestled at high altitudes, this green coffee boasts a unique terroir, blending volcanic soil
                            and optimal climate.
                        </p>

                        <select id="size-input" class="form-select form-select-lg" style="margin-top: 45px"
                            aria-label="Default select example">
                            <option selected>Select Size</option>
                            <option value="1">One</option>
                            <option value="2">Two</option>
                            <option value="3">Three</option>
                        </select>

                        <div class="border mt-2 d-flex rounded py-1 count-container"
                            style="height: fit-content;width:fit-content;border-color:var(--input-outlined-enabledBorder, rgba(0, 0, 0, 0.23)) !important;align-items:center">
                            <button class="border-0 changeAmount" data-change-type="decrease"
                                style="background: transparent">
                                @include('templates.basic.svgIcons.minos')
                            </button>
                            <span class="px-1 shown-value">0</span>
                            <button class="border-0 changeAmount" data-change-type="increase"
                                style="background: transparent">
                                @include('templates.basic.svgIcons.plus')
                            </button>
                            <input id="count-input" value="0" class="count-value" name="count" type="hidden" />
                        </div>

                        <div style="margin-top: 30px">
                            <div class="input-prefix-container">
                                <input min="0" id="price-input" class="form-control form-control-lg"
                                    type="text" placeholder="Your Offer" aria-label=".form-control-lg example">
                                <div class="prefix-container">USD/LB</div>
                            </div>
                            <p class="helper-text px-2 py-1">What’s your offer for this coffee in USD/LB?</p>
                        </div>


                        <div class="d-flex" style="gap: 15px">
                            <h6 class="result-label">USD$</h6>
                            <h6 class="result-label" id="price-label">5.00</h6>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" data-bs-dismiss="modal" data-bs-target="#modal" class="btn text-uppercase"
                    style="rgba(255, 255, 255, 1)">Cancel</button>
                <button type="button" style="width: fit-content"
                    class="btn btn-primary button-primary text-uppercase">Update
                    Offer</button>
                {{-- <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary">Save changes</button> --}}
            </div>
        </div>
    </div>
</div>


<script>
    $(document).ready(function() {
        /**
         * Click handler for change amount buttons. 
         * Gets the clicked button's action type and container.
         * Reads the current count value and shown value elements.
         * Checks action type to increase/decrease count and update elements accordingly.
         */
        $('.changeAmount').click(function() {
            const actionType = $(this).attr('data-change-type');

            const container = $(this).closest('.count-container');

            const countInput = container.find('.count-value');

            const currentValue = Number(countInput.val());

            const shownValue = container.find('.shown-value');



            if (actionType === "increase") {

                const newValue = currentValue + 1

                countInput.val(newValue);

                shownValue.text(newValue);
            }

            if (actionType === "decrease" && currentValue > 0) {

                const newValue = currentValue - 1

                countInput.val(newValue);

                shownValue.text(newValue);

            }
            updatePriceLabel()

        })

        $(document).on('change', '#size-input', updatePriceLabel)
        $(document).on('change', '#count-input', updatePriceLabel)
        $(document).on('change', '#price-input', updatePriceLabel)

        function updatePriceLabel() {
            const price = $("#price-input").val();
            const size = $('#size-input').val();
            const count = $("#count-input").val();

            const value = Number(price) * Number(size) * Number(count)

            const priceLabel = $("#price-label");

            priceLabel.html(value)
        }
    })
</script>
