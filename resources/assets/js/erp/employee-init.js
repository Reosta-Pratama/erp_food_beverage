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

});
