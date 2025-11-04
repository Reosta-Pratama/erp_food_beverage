(function () {

    "use strict";

    /* === Basic === */
        const phoneInput = document.querySelector("#phone");
        if (phoneInput) {
            window.intlTelInput(phoneInput, {
                utilsScript: "https://cdn.jsdelivr.net/npm/intl-tel-input@25.3.1/build/js/utils.js"
            });
        }
    /* === Basic === */

    /* === With Validation === */
        const validationPhoneInput = document.querySelector("#phone-validation");
        const validateBtn = document.querySelector("#btn");
        const errorMessage = document.querySelector("#error-msg");
        const successMessage = document.querySelector("#valid-msg");

        // Map for validation error codes returned by `getValidationError()`
        const errorMessages = [
            "Invalid number",       // 0
            "Invalid country code", // 1
            "Too short",            // 2
            "Too long",             // 3
            "Invalid number"        // 4 (fallback)
        ];

        // Initialize the intl-tel-input plugin
        const phoneInstance = window.intlTelInput(validationPhoneInput, {
            initialCountry: "id",
            utilsScript: "https://cdn.jsdelivr.net/npm/intl-tel-input@25.3.1/build/js/utils.js"
        });

        // Function to reset error/success messages and styles
        const resetValidation = () => {
            validationPhoneInput.classList.remove("error");
            errorMessage.textContent = "";
            errorMessage.classList.add("hide");
            successMessage.classList.add("hide");
        };

        // Function to display an error message
        const displayError = (message) => {
            validationPhoneInput.classList.add("error");
            errorMessage.textContent = message;
            errorMessage.classList.remove("hide");
        };

        // Handle button click: validate the phone number
        validateBtn.addEventListener("click", () => {
            resetValidation();

            const inputVal = validationPhoneInput.value.trim();

            if (!inputVal) {
                displayError("Required");
            } else if (phoneInstance.isValidNumber()) {
                successMessage.classList.remove("hide");
            } else {
                const errorCode = phoneInstance.getValidationError();
                const message = errorMessages[errorCode] || "Invalid number";
                displayError(message);
            }
        });

        // Reset validation messages on input changes
        validationPhoneInput.addEventListener("change", resetValidation);
        validationPhoneInput.addEventListener("keyup", resetValidation);
    /* === With Validation === */

    /* === With Only Countries === */
        const phoneInputEuropeOnly = document.querySelector("#phone-only-countries");
        if (phoneInputEuropeOnly) {
            window.intlTelInput(phoneInputEuropeOnly, {
                // Allow only the following European countries to be selectable
                onlyCountries: [
                    "al", "ad", "at", "by", "be", "ba", "bg", "hr", "cz", "dk", "ee",
                    "fo", "fi", "fr", "de", "gi", "gr", "va", "hu", "is", "ie", "it",
                    "lv", "li", "lt", "lu", "mk", "mt", "md", "mc", "me", "nl", "no",
                    "pl", "pt", "ro", "ru", "sm", "rs", "sk", "si", "es", "se", "ch",
                    "ua", "gb"
                ],

                // Utility script for additional features like formatting, validation, placeholders
                utilsScript: "https://cdn.jsdelivr.net/npm/intl-tel-input@25.3.1/build/js/utils.js"
            });
        }
    /* === With Only Countries === */

    /* === With Hidden Input === */
        const hiddenPhoneInput = document.querySelector("#phone-hidden-input");
        const formElement = document.querySelector("#form");
        const messageBox = document.querySelector("#message");

        // Initialize intl-tel-input plugin with hidden input support
        const phoneIntl = window.intlTelInput(hiddenPhoneInput, {
            initialCountry: "id",

            // This creates a hidden input that contains the full international phone number
            hiddenInput: () => "full_phone",

            // Load utility script for formatting, validation, etc.
            utilsScript: "https://cdn.jsdelivr.net/npm/intl-tel-input@25.3.1/build/js/utils.js"
        });

        // Form submission handler: validate phone number before submitting
        formElement.onsubmit = () => {
            if (!phoneIntl.isValidNumber()) {
                messageBox.textContent = "Invalid number. Please try again.";
                return false; // Prevent form submission
            }
        };

        // Check URL for submitted hidden input (used after form redirect or reload)
        const urlParams = new URLSearchParams(window.location.search);
        const fullPhoneFromURL = urlParams.get("full_phone");

        if (fullPhoneFromURL) {
            messageBox.textContent = `Submitted hidden input value: ${fullPhoneFromURL}`;
        }
    /* === With Hidden Input === */

    /* === With Existing Number === */
        const existingPhoneInput = document.querySelector("#phone-existing-number");

        if (existingPhoneInput) {
            window.intlTelInput(existingPhoneInput, {
                initialCountry: "id", // Default country if the number is not already complete
                utilsScript: "https://cdn.jsdelivr.net/npm/intl-tel-input@25.3.1/build/js/utils.js" // For formatting, placeholders, validation
            });
        }
    /* === With Existing Number === */

    /* === With Selected Dial Code === */
        const dialCodePhoneInput = document.querySelector("#phone-show-selected-dial-code");
        if (dialCodePhoneInput) {
            window.intlTelInput(dialCodePhoneInput, {
                initialCountry: "id",              // Set the default country to United States
                showSelectedDialCode: true,        // Show selected country dial code in the input (e.g., +1, +62)
                utilsScript: "https://cdn.jsdelivr.net/npm/intl-tel-input@25.3.1/build/js/utils.js" // Enables formatting and validation
            });
        }
    /* === With Selected Dial Code === */
    
})();