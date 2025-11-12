document.addEventListener('DOMContentLoaded', function () {

    const dateRanges = document.querySelectorAll('.daterange');
    if (dateRanges.length > 0) {
        dateRanges.forEach((element) => {
            // Get date_from and date_to attributes
            const dateFrom = element.getAttribute('date_from') || null;
            const dateTo = element.getAttribute('date_to') || null;

            // Determine default date range (only if both have value)
            const defaultDates = dateFrom && dateTo ? [dateFrom, dateTo] : null;

            // Initialize Flatpickr
            flatpickr(element, {
                mode: "range",            
                dateFormat: "Y-m-d",      
                disableMobile: true,      
                defaultDate: defaultDates 
            });
        });
    }

    // Confirm before clearing logs
    document
        .querySelector('#clearModal form')
        .addEventListener('submit', function (e) {
            const days = document
                .getElementById('days')
                .value;
            if (days && !confirm(`Are you sure you want to delete all activity logs older than ${days} days? This action cannot be undone.`)) {
                e.preventDefault();
            }
        });

    // Quick filter badges
    document
        .querySelectorAll('.filter-badge')
        .forEach(badge => {
            badge.addEventListener('click', function (e) {
                e.preventDefault();
                window.location.href = this.href;
            });
        });
});