const dropdownElements = document.querySelectorAll('.single-select');
if (dropdownElements.length > 0) {
    dropdownElements.forEach((dropdown) => {
        new Choices(dropdown, {searchEnabled: true});
    });
}

// Warn user about unsaved changes
let formChanged = false;
const form = document.getElementById('userForm');
const inputs = form.querySelectorAll('input, select, textarea');

inputs.forEach(input => {
    input.addEventListener('change', () => {
        formChanged = true;
    });
});

window.addEventListener('beforeunload', (e) => {
    if (formChanged) {
        e.preventDefault();
        e.returnValue = '';
    }
});

form.addEventListener('submit', () => {
    formChanged = false;
});