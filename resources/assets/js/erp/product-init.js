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
    
});
