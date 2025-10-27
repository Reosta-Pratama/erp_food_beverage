(function () {

    "use strict";

    /* === Simplebar === */
    const sidebarScrollEl = document.getElementById('sidebar-content');
    if(sidebarScrollEl) {
        new SimpleBar(sidebarScrollEl, { autoHide: true });
    }
    /* === Simplebar === */
        

    const ANIMATION_DURATION = 500;

    // === First-level toggle ===
    const firstLevelItems = document.querySelectorAll(
        ".sidebar-content .menu > ul > .menu-item.has-sub > a"
    );

    firstLevelItems.forEach(link => {
        link.addEventListener("click", function (e) {
            e.preventDefault();

            const parentItem = this.closest(".menu-item.has-sub");
            const submenu = parentItem.querySelector(".child1");

            // Tutup semua item lain di level pertama
            document
                .querySelectorAll(".sidebar-content .menu > ul > .menu-item.has-sub")
                .forEach(item => {
                    if (item !== parentItem) {
                        item
                            .classList
                            .remove("open");

                        // Reset semua inner .open dan child2 di dalamnya
                        item
                            .querySelectorAll(".menu-item.has-sub.open")
                            .forEach(inner => {
                                inner
                                    .classList
                                    .remove("open");
                            });

                        item
                            .querySelectorAll(".child2")
                            .forEach(child2 => {
                                slideUp(child2);
                            });

                        const otherSubmenu = item.querySelector(".child1");
                        if (otherSubmenu) 
                            slideUp(otherSubmenu);
                        }
                    });

            // Toggle current
            const isOpen = parentItem
                .classList
                .contains("open");
            parentItem
                .classList
                .toggle("open");

            if (!isOpen && submenu) {
                slideDown(submenu);
            } else if (isOpen && submenu) {
                // Reset inner level saat ditutup
                parentItem
                    .querySelectorAll(".menu-item.has-sub.open")
                    .forEach(inner => {
                        inner
                            .classList
                            .remove("open");
                    });
                parentItem
                    .querySelectorAll(".child2")
                    .forEach(child2 => {
                        slideUp(child2);
                    });
                slideUp(submenu);
            }
        });
    });

    // === Inner-level toggle ===
    const innerLevelItems = document.querySelectorAll(
        ".sidebar-content .menu > ul > .menu-item.has-sub .menu-item.has-sub > a"
    );

    innerLevelItems.forEach(link => {
        link.addEventListener("click", function (e) {
            e.preventDefault();

            const parentItem = this.closest(".menu-item.has-sub");
            const submenu = parentItem.querySelector(".child2");

            // Tutup semua inner-level lain di dalam .child1 yang sama
            const allInnerItems = parentItem
                .closest(".child1")
                .querySelectorAll(".menu-item.has-sub");

            allInnerItems.forEach(item => {
                if (item !== parentItem) {
                    item
                        .classList
                        .remove("open");
                    const otherSubmenu = item.querySelector(".child2");
                    if (otherSubmenu) 
                        slideUp(otherSubmenu);
                    }
                });

            // Toggle current
            const isOpen = parentItem
                .classList
                .contains("open");
            parentItem
                .classList
                .toggle("open");

            if (!isOpen && submenu) {
                slideDown(submenu);
            } else if (isOpen && submenu) {
                slideUp(submenu);
            }
        });
    });

    // === Utility: Slide Up ===
    function slideUp(element) {
        element.style.height = element.scrollHeight + 'px';
        element.offsetHeight; // force reflow
        element.style.overflow = 'hidden';
        element.style.transition = `height ${ANIMATION_DURATION}ms ease`;
        element.style.height = '0';

        setTimeout(() => {
            element.style.display = 'none';
            element
                .style
                .removeProperty('height');
            element
                .style
                .removeProperty('transition');
            element
                .style
                .removeProperty('overflow');
        }, ANIMATION_DURATION);
    }

    // === Utility: Slide Down ===
    function slideDown(element) {
        element
            .style
            .removeProperty('display');
        let display = window
            .getComputedStyle(element)
            .display;
        if (display === 'none') 
            display = 'flex'; // atau 'block' jika sesuai strukturmu
        element.style.display = display;

        const height = element.scrollHeight + 'px';

        element.style.height = '0';
        element.style.overflow = 'hidden'; // Tambahkan ini agar isi tidak bocor
        element.offsetHeight; // force reflow
        element.style.transition = `height ${ANIMATION_DURATION}ms ease`;

        requestAnimationFrame(() => {
            element.style.height = height;
        });

        setTimeout(() => {
            element
                .style
                .removeProperty('height');
            element
                .style
                .removeProperty('transition');
            element
                .style
                .removeProperty('overflow');
        }, ANIMATION_DURATION);
    }

    // === Utility: Open Instant ===
    function openInstant(element) {
        element
            .style
            .removeProperty('display');
        const display = window
            .getComputedStyle(element)
            .display;
        element.style.display = display === 'none'
            ? 'flex'
            : display;
        element.style.height = 'auto';
        element.style.opacity = '1';
        element
            .style
            .removeProperty('transition');
    }

    // === Smooth Scroll Helper ===
    function smoothScrollTo(container, target, duration = 300) {
        const containerRect = container.getBoundingClientRect();
        const targetRect = target.getBoundingClientRect();

        const start = container.scrollTop;
        const offset = targetRect.top - containerRect.top;
        const end = start + offset - container.clientHeight / 2 + target.offsetHeight / 2;
        const change = end - start;
        const startTime = performance.now();

        function easeInOutQuad(t) {
            return t < 0.5
                ? 2 * t * t
                : -1 + (4 - 2 * t) * t;
        }

        function animateScroll(currentTime) {
            const elapsed = currentTime - startTime;
            const progress = Math.min(elapsed / duration, 1);
            container.scrollTop = start + change * easeInOutQuad(progress);

            if (progress < 1) {
                requestAnimationFrame(animateScroll);
            }
        }

        requestAnimationFrame(animateScroll);
    }

    // === Auto Expand with Animation ===
    document.addEventListener("DOMContentLoaded", () => {
        const currentPath = window.location.pathname;
        const menuLinks = document.querySelectorAll(
            ".sidebar-content .menu-item a.menu-link"
        );

        let activeMenuItem = null;

        menuLinks.forEach(link => {
            const linkPath = link.getAttribute("href");
            if (!linkPath || linkPath === "#" || linkPath.startsWith("javascript:")) 
                return;
            
            const linkUrl = new URL(link.href, window.location.origin);
            if (linkUrl.pathname === currentPath) {
                const menuItem = link.closest(".menu-item");
                if (!menuItem) 
                    return;
                
                if (!menuItem.classList.contains("has-sub")) {
                    menuItem
                        .classList
                        .add("active");
                    activeMenuItem = menuItem;
                }

                const child1 = link.closest(".child1");

                if (child1) {
                    const firstLevel = link.closest(
                        ".sidebar-content .menu > ul > .menu-item.has-sub"
                    );
                    const innerLevel = link.closest(".menu-item.has-sub");

                    if (firstLevel) {
                        const submenu1 = firstLevel.querySelector(".child1");
                        firstLevel
                            .classList
                            .add("open");
                        if (submenu1) 
                            openInstant(submenu1);
                        }
                    
                    if (innerLevel && innerLevel !== firstLevel) {
                        const submenu2 = innerLevel.querySelector(".child2");
                        innerLevel
                            .classList
                            .add("open");
                        if (submenu2) 
                            openInstant(submenu2);
                        }
                    } else {
                    const firstLevel = link.closest(
                        ".sidebar-content .menu > ul > .menu-item.has-sub"
                    );
                    if (firstLevel) {
                        const submenu1 = firstLevel.querySelector(".child1");
                        firstLevel
                            .classList
                            .add("open");
                        if (submenu1) 
                            openInstant(submenu1);
                        }
                    }
            }
        });

        if (activeMenuItem) {
            const sidebarSimpleBar = SimpleBar.instances.get(document.getElementById('sidebar-content'));
            const scrollElement = sidebarSimpleBar ? sidebarSimpleBar.getScrollElement() : document.querySelector(".sidebar-content");

            requestAnimationFrame(() => {
                setTimeout(() => {
                    smoothScrollTo(scrollElement, activeMenuItem, 300);
                }, 500);
            });
        }

        const htmlElement = document.documentElement;
        const toggleButton = document.querySelector('[data-bs-toggle="sidebar"]');
        const sidebar = document.querySelector('#sidebar');

        // Toggle sidebar: add/remove data-toggled="sidebar-close"
        if (toggleButton) {
            toggleButton.addEventListener('click', function () {
                const isClosed = htmlElement.getAttribute('data-toggled') === 'sidebar-close';

                if (isClosed) {
                    htmlElement.removeAttribute('data-toggled');
                } else {
                    htmlElement.setAttribute('data-toggled', 'sidebar-close');
                }
            });
        }

        // Sidebar hover: add/remove data-sidebar="open"
        if (sidebar) {
            sidebar.addEventListener('mouseenter', () => {
                // Only add data-sidebar="open" if sidebar is already closed
                const isClosed = htmlElement.getAttribute('data-toggled') === 'sidebar-close';
                if (isClosed) {
                    htmlElement.setAttribute('data-sidebar', 'open');
                }
            });

            sidebar.addEventListener('mouseleave', () => {
                htmlElement.removeAttribute('data-sidebar');
            });
        }

        // === Responsive Reset ===
        function handleResponsiveSidebarReset() {
            if (window.innerWidth < 992) {
                htmlElement.removeAttribute('data-toggled');
                htmlElement.removeAttribute('data-sidebar');
            }
        }

        handleResponsiveSidebarReset();
        window.addEventListener('resize', handleResponsiveSidebarReset);
    });

})();