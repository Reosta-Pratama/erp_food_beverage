(function () {
    "use strict";

    const htmlElement = document.documentElement;
    const toggleButton = document.querySelector(
        '[data-bs-toggle="mobile-sidebar"]'
    );
    const sidebar = document.querySelector('#sidebar');

    // Function to set attribute based on screen size
    function handleMobileAttribute() {
        const isMobile = window.innerWidth < 992;

        if (isMobile && !htmlElement.hasAttribute('data-mobile')) {
            htmlElement.setAttribute('data-mobile', 'close');
        } else if (!isMobile) {
            htmlElement.removeAttribute('data-mobile');
        }
    }

    // Function to toggle data-mobile between "open" and "close"
    function toggleMobileSidebar(event) {
        const current = htmlElement.getAttribute('data-mobile');

        if (current === 'open') {
            htmlElement.setAttribute('data-mobile', 'close');
        } else {
            htmlElement.setAttribute('data-mobile', 'open');
        }

        event.stopPropagation(); // prevent bubbling to document click
    }

    // Function to close sidebar when clicking outside of it
    function handleClickOutside(event) {
        const isSidebarOpen = htmlElement.getAttribute('data-mobile') === 'open';

        if (isSidebarOpen && sidebar && !sidebar.contains(event.target) && !toggleButton.contains(event.target)) {
            htmlElement.setAttribute('data-mobile', 'close');
        }
    }

    document.addEventListener('DOMContentLoaded', function () {
        handleMobileAttribute();
        window.addEventListener('resize', handleMobileAttribute);

        if (toggleButton) {
            toggleButton.addEventListener('click', toggleMobileSidebar);
        }

        // Add click listener to detect outside clicks
        document.addEventListener('click', handleClickOutside);
    });
    
})();
