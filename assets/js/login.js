/**
 * Apply placeholders to 
 * login forum inputs
 * 
 * localized: login_placeholders
 */
var userInput = document.getElementById('user_login');
var passInput = document.getElementById('user_pass');

try {
    userInput.setAttribute('placeholder', login_placeholders.username);
    passInput.setAttribute('placeholder', login_placeholders.password);

    document.querySelector('label[for="user_login"]').childNodes[0].textContent = '';
    document.querySelector('label[for="user_pass"]').childNodes[0].textContent = '';
} catch(e) {}


