document.addEventListener('DOMContentLoaded', function() {
    
    // Handle CRUD checkbox visual feedback
    const radios = document.querySelectorAll('.crud-checkbox');
    radios.forEach(radio => {
        const card = radio.closest('.crud-checkbox-card');

        if (radio.checked) {
            card.classList.add('checked');
        }

        radio.addEventListener('change', function () {
            // Remove checked class from all
            radios.forEach(r => {
                r.closest('.crud-checkbox-card').classList.remove('checked');
            });

            // Add checked class to selected
            if (this.checked) {
                card.classList.add('checked');
            }
        });
    });

    // Form validation
    document.getElementById('uomForm').addEventListener('submit', function(e) {
        const checkboxes = document.querySelectorAll('.crud-checkbox');
        const isChecked = Array.from(checkboxes).some(cb => cb.checked);

        if (!isChecked) {
            e.preventDefault();
            Swal.fire({
                icon: 'warning',
                title: 'No Unit Selected',
                text: 'Please select unit type',
                confirmButtonText: 'OK'
            });
            return false;
        }
    });

});