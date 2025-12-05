document.addEventListener('DOMContentLoaded', function () {

    const dropdownElements = document.querySelectorAll('.single-select');
    if (dropdownElements.length > 0) {
        dropdownElements.forEach((dropdown) => {
            new Choices(dropdown, {
                searchEnabled: true,
                allowHTML: true
            });
        });
    }

    // Create & Update Form
    const positionForm = document.getElementById('positionForm');
    positionForm.addEventListener('submit', async function(e) {
        const positionName = document.getElementById('position_name').value.trim();
        const departmentId = document.getElementById('department_id').value;

        if (!positionName) {
            e.preventDefault();

            await Swal.fire({
                icon: 'warning',
                title: 'Validation',
                text: 'Please enter a position name.',
                confirmButtonText: 'OK'
            });

            return;
        }

        if (!departmentId) {
            e.preventDefault();

            await Swal.fire({
                icon: 'warning',
                title: 'Validation',
                text: 'Please select a department.',
                confirmButtonText: 'OK'
            });

            return;
        }
        
        // Show loading state
        const submitBtn = this.querySelector('button[type="submit"]');
        if (submitBtn) {
            submitBtn.disabled = true;
            submitBtn.innerHTML = `
                <span class="spinner-border spinner-border-sm me-1"></span> Saving...
            `;
        }
    });

});
