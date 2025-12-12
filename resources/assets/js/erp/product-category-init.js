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

    // Create & Update Form
    const categoryForm = document.getElementById('categoryForm');
    if (categoryForm) {
        categoryForm.addEventListener('submit', async function (e) {
            const category_name = document.getElementById('category_name');
            const value = category_name?.value.trim() ?? '';

            if (!value) {
                e.preventDefault();

                await Swal.fire({
                    icon: 'warning',
                    title: 'Validation',
                    text: 'Please enter category name.',
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
    }

});