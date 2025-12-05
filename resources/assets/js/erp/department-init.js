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
    const departmentForm = document.getElementById('departmentForm');
    if (departmentForm) {
        departmentForm.addEventListener('submit', async function (e) {
            const departmentNameInput = document.getElementById('department_name');
            const departmentName = departmentNameInput?.value.trim() ?? '';

            if (!departmentName) {
                e.preventDefault();

                await Swal.fire({
                    icon: 'warning',
                    title: 'Validation',
                    text: 'Please enter a department name.',
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
    }


    // Assigned Manager Form
    const assignedManagerForm = document.getElementById('assignedManagerForm');
    if (assignedManagerForm) {
        assignedManagerForm.addEventListener('submit', async function (e) {
            const managerSelect = document.getElementById('modal_manager_id');
            const selectedManager = managerSelect?.value.trim() ?? '';

            if (!selectedManager) {
                e.preventDefault();

                await Swal.fire({
                    icon: 'warning',
                    title: 'Validation',
                    text: 'Please select a manager first.',
                    confirmButtonText: 'OK'
                });

                managerSelect?.focus();
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
    }

});