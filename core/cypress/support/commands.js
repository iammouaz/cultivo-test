// ***********************************************
// This example commands.js shows you how to
// create various custom commands and overwrite
// existing commands.
//
// For more comprehensive examples of custom
// commands please read more here:
// https://on.cypress.io/custom-commands
// ***********************************************
//
//
// -- This is a parent command --
// Cypress.Commands.add('login', (email, password) => { ... })
//
//
// -- This is a child command --
// Cypress.Commands.add('drag', { prevSubject: 'element'}, (subject, options) => { ... })
//
//
// -- This is a dual command --
// Cypress.Commands.add('dismiss', { prevSubject: 'optional'}, (subject, options) => { ... })
//
//
// -- This will overwrite an existing command --
// Cypress.Commands.overwrite('visit', (originalFn, url, options) => { ... })


Cypress.Commands.add('login', (i) => {
    cy.visit(`${Cypress.env('CYPRESS_URL')}login`)

    cy.get('#name').should('exist');

    cy.get('#name').type(`turnsoletest${i + 1}@mailinator.com`)

    cy.get('#password').type("password")

    // cy.fixture('user-credential.json').then((usersData) => {


    // })

    cy.get('.login-btn').click()

    cy.getCookie('user_id').should('not.be.null')

})
