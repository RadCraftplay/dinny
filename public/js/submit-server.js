const form = document.querySelector("form");
const titleInput = form.querySelector("input[name=\"title\"]");
const serverTypeSelect = form.querySelector("select[name=\"service_type\"]");
const addressInput = form.querySelector("input[name=\"address\"]");
const descriptionInput = form.querySelector("input[name=\"description\"]");

function markValidation(element, condition) {
    !condition ? element.classList.add('no-valid') : element.classList.remove('no-valid');
}

function isTitleOk(title) {
    return title.length >= 3 && title.length <= 100;
}

function isAddressOk(address) {
    if (serverTypeSelect.value === "Discord") {
        return /^(https?:\/\/)?discord\.(com\/invite\/\S+|gg\/\S+)$/.test(address);
    }

    return /^(\S+:\/\/)?([a-zA-Z0-9_\-]+\.)?[a-zA-Z0-9_\-]+\.[a-zA-Z0-9_\-]+(:\d+)?(\/\S+)?$/.test(address)
}

function validateTitle() {
    setTimeout(function () {
            markValidation(titleInput, isTitleOk(titleInput.value));
        },
        1000
    );
}

function validateAddress() {
    setTimeout(function () {
            markValidation(addressInput, isAddressOk(addressInput.value));
        },
        1000
    );
}

titleInput.addEventListener('keyup', validateTitle);
serverTypeSelect.addEventListener('mouseup', validateAddress);
addressInput.addEventListener('keyup', validateAddress);