document.addEventListener('DOMContentLoaded', function () {

    const dateRanges = document.querySelectorAll('.daterange');
    if (dateRanges.length > 0) {
        dateRanges.forEach((element) => {
            // Get date_from and date_to attributes
            const dateFrom = element.getAttribute('date_from') || null;
            const dateTo = element.getAttribute('date_to') || null;

            // Determine default date range (only if both have value)
            const defaultDates = dateFrom && dateTo
                ? [dateFrom, dateTo]
                : null;

            flatpickr(element, {
                mode: "range",
                dateFormat: "Y-m-d",
                disableMobile: true,
                defaultDate: defaultDates
            });
        });
    }

    const dropdownElements = document.querySelectorAll('.single-select');
    if (dropdownElements.length > 0) {
        dropdownElements.forEach((dropdown) => {
            new Choices(dropdown, {searchEnabled: true});
        });
    }

    // Confirm before clearing
    const clearModal = document.getElementById('clearModal');
    const form = clearModal
        .querySelector('form');
    form
        .addEventListener('submit', function (e) {
            const days = document
                .getElementById('days')
                .value
                .trim();

            if (!days) {
                e.preventDefault();
                alert('Please enter number of days.');
                document
                    .getElementById('days')
                    .focus();
                return;
            }

            if (!confirm(`FINAL CONFIRMATION: Delete all audit logs older than ${days} days?\n\nThis action is PERMANENT and CANNOT be undone!`)) {
                e.preventDefault();
            }
        });

});