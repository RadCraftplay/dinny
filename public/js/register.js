const form = document.querySelector("form");
const emailInput = form.querySelector("input[name=\"email\"]");
const usernameInput = form.querySelector("input[name=\"username\"]");
const passwordInput = form.querySelector("input[name=\"password\"]");
const confirmedPasswordInput = form.querySelector("input[name=\"password_repeated\"]");

function isEmail(email) {
    return /^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$/.test(email);
}

function isUsernameOk(username) {
    return /^[_a-zA-Z0-9-]+$/.test(username);
}

function isPasswordOk(password) {
    return /^.*(?=.{8,48})(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[,.<>!#$%&? "])/.test(password);
}

function arePasswordsSame(password, confirmedPassword) {
    return password === confirmedPassword;
}

function markValidation(element, condition) {
    !condition ? element.classList.add('no-valid') : element.classList.remove('no-valid');
}

function validateEmail() {
    setTimeout(function () {
            markValidation(emailInput, isEmail(emailInput.value));
        },
        1000
    );
}

function validateUsername() {
    setTimeout(function () {
            markValidation(usernameInput, isUsernameOk(usernameInput.value));
        },
        1000
    );
}

function validatePassword() {
    setTimeout(function () {
            markValidation(passwordInput, isPasswordOk(passwordInput.value));
        },
        1000
    );
}

function validateConfirmedPassword() {
    setTimeout(function () {
            const condition = arePasswordsSame(
                passwordInput.value,
                confirmedPasswordInput.value
            );
            markValidation(confirmedPasswordInput, condition);
        },
        1000
    );
}

emailInput.addEventListener('keyup', validateEmail);
usernameInput.addEventListener('keyup', validateUsername);
passwordInput.addEventListener('keyup', validatePassword);
confirmedPasswordInput.addEventListener('keyup', validateConfirmedPassword);