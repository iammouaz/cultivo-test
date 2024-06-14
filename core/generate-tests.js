const fs = require('fs');
const path = require('path');

// Load the template file
const template = fs.readFileSync(path.join(__dirname, 'test-template.js'), 'utf8');

// Define the correct directory for test files
const outputDir = path.join(__dirname, 'cypress', 'e2e');

// Ensure the directory exists
if (!fs.existsSync(outputDir)) {
    fs.mkdirSync(outputDir, { recursive: true });
}

for (let i = 1; i <= 40; i++) {
    const testContent = template.replace('{LOGIN_NUMBER}', i);
    const filePath = path.join(outputDir, `event-bid-login-${i}.cy.js`);
    
    fs.writeFileSync(filePath, testContent, 'utf8');
    console.log(`Generated test file: event-bid-login-${i}.cy.js`);
}
