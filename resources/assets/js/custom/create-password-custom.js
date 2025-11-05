(function () {

    "use strict";

    document.addEventListener("DOMContentLoaded", () => {
        const toggleButtons = document.querySelectorAll('.show-password-button');

        toggleButtons.forEach(button => {
            button.addEventListener('click', () => {
                const inputId = button.getAttribute('data-target');
                console.log(inputId)
                togglePasswordVisibility(inputId, button);
            });
        });

        const togglePasswordVisibility = (inputId, toggleButtonElement) => {
            const passwordInput = document.getElementById(inputId);

            const isHidden = passwordInput.type === "password";
            passwordInput.type = isHidden
                ? "text"
                : "password";

            const iconElement = toggleButtonElement.querySelector('i');
            const iconClasses = iconElement.classList;

            if (iconClasses.contains("ri-eye-line")) {
                iconClasses.remove("ri-eye-line");
                iconClasses.add("ri-eye-off-line");
            } else {
                iconClasses.add("ri-eye-line");
                iconClasses.remove("ri-eye-off-line");
            }
        };
    });

})();