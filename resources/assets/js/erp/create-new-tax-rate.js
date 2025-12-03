const dropdownElements = document.querySelectorAll('.single-select');
if (dropdownElements.length > 0) {
    dropdownElements.forEach((dropdown) => {
        new Choices(dropdown, {
            searchEnabled: true,
            allowHTML: true     
        });
    });
}

const flatDate = document.querySelectorAll('.flatDate');
if (flatDate.length > 0) {
    flatDate.forEach((element) => {
        const date = element.getAttribute('date') || null;

        flatpickr(element, {
            altInput: true,
            altFormat: "F j, Y",
            dateFormat: "Y-m-d",      
            disableMobile: true,      
            defaultDate: date 
        });
    });
}

document.getElementById('tax_percentage').addEventListener('input', function() {
    const percentage = parseFloat(this.value) || 0;
    if (percentage < 0 || percentage > 100) {
        this.classList.add('is-invalid');
    } else {
        this.classList.remove('is-invalid');
    }
});

