document.addEventListener('DOMContentLoaded', function () {

    // Logo preview functionality
    const logoInput = document.getElementById('logo');
    const logoPreview = document.getElementById('logoPreview');
    const logoContainer = document.querySelector('.logo-preview-container');

    // File input change handler
    logoInput.addEventListener('change', function (e) {
        const file = e
            .target
            .files[0];

        if (file) {
            // Validate file type
            const validTypes = ['image/jpeg', 'image/jpg', 'image/png'];
            if (!validTypes.includes(file.type)) {
                alert('Please select a valid image file (JPG, JPEG, or PNG)');
                logoInput.value = '';
                return;
            }

            // Validate file size (2MB)
            if (file.size > 2 * 1024 * 1024) {
                alert('File size must not exceed 2MB');
                logoInput.value = '';
                return;
            }

            // Preview image
            const reader = new FileReader();
            reader.onload = function (e) {
                logoPreview.innerHTML = `<img src="${e.target.result}" alt="Logo Preview">`;
            };
            reader.readAsDataURL(file);
        }
    });

    // Drag and drop functionality
    logoContainer.addEventListener('dragover', function (e) {
        e.preventDefault();
        e.stopPropagation();

        // Lighter highlight on dragover
        logoContainer.style.borderColor = '#985ffd';
        logoContainer.style.background = '#f3e9ff';
    });

    logoContainer.addEventListener('dragleave', function (e) {
        e.preventDefault();
        e.stopPropagation();

        // Slightly darker than dragover
        logoContainer.style.borderColor = '#7a33db';
        logoContainer.style.background = '#e8d6ff';
    });

    logoContainer.addEventListener('drop', function (e) {
        e.preventDefault();
        e.stopPropagation();

        // Also darker than dragover (like dragleave)
        logoContainer.style.borderColor = '#7a33db';
        logoContainer.style.background = '#e8d6ff';

        const files = e.dataTransfer.files;
        if (files.length > 0) {
            logoInput.files = files;

            // Trigger change event
            const event = new Event('change', { bubbles: true });
            logoInput.dispatchEvent(event);
        }
    });

    // Form validation
    const form = document.getElementById('companyProfileForm');
    form.addEventListener('submit', function (e) {
        const companyName = document
            .getElementById('company_name')
            .value
            .trim();

        if (!companyName) {
            e.preventDefault();
            alert('Company name is required');
            document
                .getElementById('company_name')
                .focus();
            return false;
        }
    });

});