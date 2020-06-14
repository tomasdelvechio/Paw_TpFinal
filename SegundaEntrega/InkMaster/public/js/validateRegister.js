var window = window || {},
    document = document || {},
    console = console || {};
document.addEventListener("DOMContentLoaded", function() {

    //validate username
    validate_username = document.querySelector(".usernamejs");
    validate_username.addEventListener("blur", function() {
        var username = /^(?=.{8,20}$)(?![_.])(?!.*[_.]{2})[a-zA-Z0-9._ ]+(?<![_.])$/;
        if (username.exec(validate_username.value) || (validate_username.value === "")) {
            validate_username.style.border = "#ffffff";
            validate_username.style.background = "#ffffff";
        } else {
            validate_username.style.background = "#e05f5f";
        }
    });

    //validate password
    validate_password = document.querySelector(".passwordjs");
    validate_password.addEventListener("blur", function() {
        var password = /(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}/;
        if (password.exec(validate_password.value) || (validate_password.value === "")) {
            validate_password.style.border = "#ffffff";
            validate_password.style.background = "#ffffff";
        } else {
            validate_password.style.background = "#e05f5f";
        }
    });

    //validate that the confirm password is the same that the password input 
    validate_password = document.querySelector(".passwordjs");
    validate_confirmPassword = document.querySelector(".confirmPasswordjs");
    validate_confirmPassword.addEventListener("blur", function() {
        var password = /(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}/;
        if (((password.exec(validate_confirmPassword.value) && (validate_password.value == validate_confirmPassword.value))) || (validate_confirmPassword.value === "")) {
            validate_password.style.border = "#ffffff";
            validate_password.style.background = "#ffffff";
        } else {
            validate_password.style.background = "#e05f5f";
        }
    });

    //validate name
    validate_name = document.querySelector(".namejs");
    validate_name.addEventListener("blur", function() {
        var name = /^[a-zA-Z ]{3,30}$/;
        if (name.exec(validate_name.value) || (validate_name.value === "")) {
            validate_name.style.border = "#ffffff";
            validate_name.style.background = "#ffffff";
        } else {
            validate_name.style.background = "#e05f5f";
        }
    });

    //validate surname 
    validate_surname = document.querySelector(".surnamejs");
    validate_surname.addEventListener("blur", function() {
        var surname = /^[a-zA-Z ]{3,30}$/;
        if (surname.exec(validate_surname.value) || (validate_surname.value === "")) {
            validate_surname.style.border = "#ffffff";
            validate_surname.style.background = "#ffffff";
        } else {
            validate_surname.style.background = "#e05f5f";
        }
    });

    //validate identification number 
    validate_identificationNumber = document.querySelector(".identificationNumberjs");
    validate_identificationNumber.addEventListener("blur", function() {
        var identificationNumber = /^\d{8}(?:[-\s]\d{4})?$/;
        if (identificationNumber.exec(validate_identificationNumber.value) || (validate_identificationNumber.value === "")) {
            validate_identificationNumber.style.border = "#ffffff";
            validate_identificationNumber.style.background = "#ffffff";
        } else {
            validate_identificationNumber.style.background = "#e05f5f";
        }
    });

    //validate phone 
    validate_phone = document.querySelector(".phonejs");
    validate_phone.addEventListener("blur", function() {
        var phone = /^(?:(?:00)?549?)?0?(?:11|[2368]\d)(?:(?=\d{0,2}15)\d{2})??\d{8}$/;
        if (phone.exec(validate_phone.value) || (validate_phone.value === "")) {
            validate_phone.style.border = "#ffffff";
            validate_phone.style.background = "#ffffff";
        } else {
            validate_phone.style.background = "#e05f5f";
        }
    });

    //validate address
    validate_address = document.querySelector(".addressjs");
    validate_address.addEventListener("blur", function() {
        var address = /^[a-zA-Z0-9 ]{3,50}$/;
        if (address.exec(validate_address.value) || (validate_address.value === "")) {
            validate_address.style.border = "#ffffff";
            validate_address.style.background = "#ffffff";
        } else {
            validate_address.style.background = "#e05f5f";
        }
    });

    //validate email
    validate_email = document.querySelector(".emailjs");
    validate_email.addEventListener("blur", function() {
        var email = /^[a-zA-Z0-9 ]{3,50}$/;
        if (email.exec(validate_email.value) || (validate_email.value === "")) {
            validate_email.style.border = "#ffffff";
            validate_email.style.background = "#ffffff";
        } else {
            validate_email.style.background = "#e05f5f";
        }
    });

    //validate local
    validate_local = document.querySelector(".localjs");
    validate_local.addEventListener("blur", function() {
        var local = /^[a-zA-Z0-9 ]{3,50}$/;
        if (local.exec(validate_local.value) || (validate_local.value === "")) {
            validate_local.style.border = "#ffffff";
            validate_local.style.background = "#ffffff";
        } else {
            validate_local.style.background = "#e05f5f";
        }
    });

    //validate description
    validate_description = document.querySelector(".descriptionjs");
    validate_description.addEventListener("blur", function() {
        var description = /^[a-zA-Z0-9 ]{3,50}$/;
        if (description.exec(validate_description.value) || (validate_description.value === "")) {
            validate_description.style.border = "#ffffff";
            validate_description.style.background = "#ffffff";
        } else {
            validate_description.style.background = "#e05f5f";
        }
    });

});