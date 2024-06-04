const loginNumber = 88;

describe('Event - Bid', () => {

    beforeEach(() => {
        cy.login(loginNumber);
    });

    function bidAutobidTest() {
        cy.visit(Cypress.env('EVENT_URL'));
    
        cy.wait(1000);

        for (let i = 0; i < 50; i++) {
            cy.get('.cmn--btn.btn--sm.bid_now.bid-with-anim').first().click();
            cy.wait(500);
            cy.get('.modal_submit').click();
            cy.wait(500);
            cy.get('#bid_form > .modal-footer > .btn--base').click();
            cy.wait(500);
            cy.get('.iziToast-message').contains('Your Bid Added Successfully');
            cy.wait(1000);
            cy.get('.outline-btn.cmn--btn.btn--sm.auto_bid_now.bid-with-anim').first().click();
            cy.wait(500);
        
            cy.get('.d-flex.final-total-price.animate-value > .text-truncate').first().should('exist').invoke('text').then((priceText) => {
                const price = parseFloat(priceText.replace(/[^0-9.-]+/g, "")); // Remove any non-numeric characters
                const max_bid = price + 10;
                cy.get('#max_bid_in_').type(max_bid.toString());
            });
            cy.wait(500);
        
            cy.get('#bidding_step_').type('0.1');
            cy.wait(500);
            cy.get('#autobid_form > .modal-footer > .cmn--btn').click();
            cy.wait(500);
            cy.get('.iziToast-message').contains('Your AutoBid Settings Added Successfully');
            cy.wait(1000);
        }
        

    }

    Cypress._.times(10, (i) => {
        it(`Bid - Autobid Iteration ${i + 1}`, () => {
            bidAutobidTest();
        });
    });
});
