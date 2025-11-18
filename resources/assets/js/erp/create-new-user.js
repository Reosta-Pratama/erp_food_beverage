const dropdownElements = document.querySelectorAll('.single-select');
if (dropdownElements.length > 0) {
    dropdownElements.forEach((dropdown) => {
        new Choices(dropdown, {searchEnabled: true});
    });
}

// Password strength indicator (optional)
document
    .getElementById('password')
    .addEventListener('input', function () {
        const password = this.value;
        calculatePasswordStrength(password);
        // Add visual indicator here if needed
    });

function calculatePasswordStrength(password) {
    let strength = 0;
    if (password.length >= 8) 
        strength++;
    if (password.match(/[a-z]/) && password.match(/[A-Z]/)) 
        strength++;
    if (password.match(/\d/)) 
        strength++;
    if (password.match(/[^a-zA-Z\d]/)) 
        strength++;
    return strength;
}