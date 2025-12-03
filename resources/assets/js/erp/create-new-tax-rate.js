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

function formatNumber(num) {
    return parseFloat(num).toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ",");
}

const countPercentageInput = document.querySelector('.count_percentage');
if (countPercentageInput) {
    countPercentageInput.addEventListener('input', function () {
        const percentage = parseFloat(this.value) || 0;

        if (percentage < 0 || percentage > 100) {
            this.classList.add('is-invalid');
        } else {
            this.classList.remove('is-invalid');
        }

        updateCalculator();
    });
}

function updateCalculator() {
    const baseAmount = parseFloat(document.getElementById('calc_base')?.value) || 0;
    const taxPercentage = parseFloat(document.getElementById('tax_percentage')?.value) || 0;
    const taxAmount = baseAmount * (taxPercentage / 100);
    const totalAmount = baseAmount + taxAmount;

    document.getElementById('calc_base_display').textContent = formatNumber(baseAmount);
    document.getElementById('calc_percentage').textContent = taxPercentage.toFixed(2);
    document.getElementById('calc_tax').textContent = formatNumber(taxAmount);
    document.getElementById('calc_total').textContent = formatNumber(totalAmount);
}

const calcBaseInput = document.getElementById('calc_base');
if (calcBaseInput) {
    calcBaseInput.addEventListener('input', updateCalculator);
}

document.addEventListener('DOMContentLoaded', updateCalculator);
