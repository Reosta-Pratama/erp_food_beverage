document.addEventListener('DOMContentLoaded', function () {

    const singleSelect = document.querySelectorAll('.single-select');
    if (singleSelect.length > 0) {
        singleSelect.forEach((dropdown) => {
            new Choices(dropdown, {
                searchEnabled: true,
                allowHTML: true
            });
        });
    }

    const dateRanges = document.querySelectorAll('.daterange');
    if (dateRanges.length > 0) {
        dateRanges.forEach((element) => {
            // Get date_from and date_to attributes
            const joinFrom = element.getAttribute('join_from') || null;
            const joinTo = element.getAttribute('join_to') || null;

            // Determine default date range (only if both have value)
            const defaultDates = joinFrom && joinTo ? [joinFrom, joinTo] : null;

            flatpickr(element, {
                mode: "range",            
                dateFormat: "Y-m-d",      
                disableMobile: true,      
                defaultDate: defaultDates 
            });
        });
    }

    const singeDate = document.querySelectorAll('.single-date');
    if (singeDate.length > 0) {
        singeDate.forEach((element) => {
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

    filterPositions();

    // Filter positions by selected department
    function filterPositions() {
        const deptSelect = document.getElementById('department_id');
        const posSelect = document.getElementById('position_id');
        const selectedDept = deptSelect.options[deptSelect.selectedIndex].text;
        const prevPosValue = posSelect.value; 

        Array.from(posSelect.options).forEach(option => {
            if (option.value === '') {
                option.hidden = false;
                return;
            }

            const optionDept = option.getAttribute('data-department') || '';
            const show = optionDept.trim() === selectedDept.trim();
            option.hidden = !show;
        });

        const stillAvailable = Array.from(posSelect.options).some(o => o.value === prevPosValue && !o.hidden);
        if (stillAvailable) {
            posSelect.value = prevPosValue;
        } else {
            posSelect.value = ''; 
        }
    }
    window.filterPositions = filterPositions;

    // Create & Update Form
    const employeeForm = document.getElementById('employeeForm');
    employeeForm.addEventListener('submit', async function(e) {
        const required = ['first_name', 'last_name', 'gender', 'join_date', 'employment_status'];
        let isValid = true;
        let lastInvalidField = '';

        required.forEach(fieldName => {
            const field = document.getElementById(fieldName);
            let value = field.value;

            if (fieldName === 'first_name' || fieldName === 'last_name') {
                value = value.trim();
                field.value = value; 
            }
            
            if (!value) {
                isValid = false;
                field.classList.add('is-invalid');
                lastInvalidField = field.getAttribute('data-name') || fieldName.replace('_', ' ');
            } else {
                field.classList.remove('is-invalid');
            }
        });

        if (!isValid) {
            e.preventDefault();

            await Swal.fire({
                icon: 'warning',
                title: 'Validation',
                text: `Please complete the required field: ${lastInvalidField}.`,
                confirmButtonText: 'OK'
            });

            return;
        }

        const submitBtn = this.querySelector('button[type="submit"]');
        if (submitBtn) {
            submitBtn.disabled = true;
            submitBtn.innerHTML = `
                <span class="spinner-border spinner-border-sm me-1"></span> Saving...
            `;
        }
    });

});
