const loginNumber = 27;

describe('Event - Bid', () => {

    beforeEach(() => {
        cy.login(loginNumber);
    });

    function bidAutobidTest() {
        cy.visit(Cypress.env('EVENT_URL'));


        cy.wait(1000);

        cy.get('.cmn--btn.btn--sm.bid_now.bid-with-anim').filter(':visible').then($buttons => {
            const randomIndex = Math.floor(Math.random() * $buttons.length);
            cy.wrap($buttons[randomIndex]).click({ force: true});

            cy.wait(2000);

            // Save the value of the input with id "amount"
            cy.get('#amount').invoke('val').then(amountValue => {
                cy.log('Amount Value:', amountValue);

                // Click on the submit button
                cy.get('.modal_submit').click({ force: true});
                cy.wait(2000);

                // Confirm the bid
                cy.get('#bid_form > .modal-footer > .btn--base').click({ force: true});
                cy.wait(5000);


                // Verify if the same button row has "Highest Bidder" text
                if (Cypress.env('CHECK_PRICE_MISMATCH')) {
                    cy.wrap($buttons[randomIndex]).closest('div.auction__item').within(() => {
                        cy.contains('Highest Bidder').then(($highestBidder) => {
                            if ($highestBidder.length) {
                                cy.get('.text-truncate.final-price').invoke('text').then(priceText => {
                                    const price = parseFloat(priceText.replace(/[^0-9.-]+/g, "")); // Remove any non-numeric characters
                                    if (price !== parseFloat(amountValue)) {
                                        cy.log('Price mismatch: Amount Value:', amountValue, ' Final Price:', price);
                                        cy.task('log', `Price mismatch: Amount Value: ${amountValue}, Final Price: ${price}`);
                                        // Fail the test
                                        assert.strictEqual(price, parseFloat(amountValue), `Price mismatch: Amount Value: ${amountValue}, Final Price: ${price}`);
                                    }
                                });
                            } else {
                                cy.log('Highest Bidder does not exist');
                                cy.task('log', 'Highest Bidder does not exist');
                            }
                        });
                    });
                }

                cy.get('.cmn--btn.btn--sm.auto_bid_now.bid-with-anim').then($buttons => {
                    const randomIndex = Math.floor(Math.random() * $buttons.length);
                    cy.wrap($buttons[randomIndex]).click({ force: true});
                });
                cy.wait(500);

                cy.get('.d-flex.final-total-price.animate-value > .text-truncate').first().should('exist').invoke('text').then((priceText) => {
                    const price = parseFloat(priceText.replace(/[^0-9.-]+/g, "")); // Remove any non-numeric characters
                    const max_bid = price + 10;
                    cy.get('#max_bid_in_').type(max_bid.toString(), { force: true });
                });
                cy.wait(500);

                cy.get('#bidding_step_').type('0.1', { force: true });
                cy.wait(500);
                cy.get('#autobid_form > .modal-footer > .btn--base').click({ force: true});
                cy.wait(5000);
            });
        });
    }

    Cypress._.times(100, (i) => {
        it(`Bid - Autobid Iteration ${i + 1}`, () => {
            bidAutobidTest();
        });
    });
});
