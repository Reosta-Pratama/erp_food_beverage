(function () {

    "use strict";

    /* === Click Effect Button Wave === */
    Waves.attach(".btn-wave", ["waves-light"]);
    Waves.init();
    /* === Click Effect Button Wave === */

    /* === Card With Close Button === */
    let DIV_CARD = ".card";
    let cardRemoveBtn = document.querySelectorAll('[data-bs-toggle="card-remove"]');
    cardRemoveBtn.forEach((ele) => {
        ele.addEventListener("click", function (e) {
            e.preventDefault();
            let $this = this;
            let card = $this.closest(DIV_CARD);
            card.remove();
            return false;
        });
    });
    /* === Card With Close Button === */

    /* === Card With Fullscreen Button === */
    let cardFullscreenBtn = document.querySelectorAll(
        '[data-bs-toggle="card-fullscreen"]'
    );
    cardFullscreenBtn.forEach((ele) => {
        ele.addEventListener("click", function (e) {
            let $this = this;
            let card = $this.closest(DIV_CARD);
            card
                .classList
                .toggle("card-fullscreen");
            card
                .classList
                .remove("card-collapsed");
            e.preventDefault();
            return false;
        });
    });
    /* === Card With Fullscreen Button === */

    /* === Popover === */
    const popoverTriggerList = document.querySelectorAll(
        '[data-bs-toggle="popover"]'
    );
    const popoverList = [...popoverTriggerList].map(
        (popoverTriggerEl) => new bootstrap.Popover(popoverTriggerEl)
    );
    /* === Popover === */

    /* === Tooltip === */
    const tooltipTriggerList = document.querySelectorAll(
        '[data-bs-toggle="tooltip"]'
    );
    const tooltipList = [...tooltipTriggerList].map(
        (tooltipTriggerEl) => new bootstrap.Tooltip(tooltipTriggerEl)
    );
    /* === Tooltip === */

    /* === Scroll To Top === */
    const scrollToTop = document.querySelector(".scrollToTop");
    const $rootElement = document.documentElement;
    const $body = document.body;
    window.onscroll = () => {
        const scrollTop = window.scrollY || window.pageYOffset;
        const clientHt = $rootElement.scrollHeight - $rootElement.clientHeight;
        if (window.scrollY > 100) {
            scrollToTop.style.display = "flex";
        } else {
            scrollToTop.style.display = "none";
        }
    };
    scrollToTop.onclick = () => {
        window.scrollTo(0, 0);
    };
    /* === Scroll To Top === */

    /* === Toggle Switches === */
    let customSwitch = document.querySelectorAll(".toggle");
    customSwitch.forEach((e) => e.addEventListener("click", () => {
        e
            .classList
            .toggle("on");
    }));
    /* === Toggle Switches === */

    /* === Fullscreen === */
    const fullscreenBtn = document.getElementById("fullscreen-toggle");
    if (fullscreenBtn) {
        const icon = fullscreenBtn.querySelector("i");

        fullscreenBtn.addEventListener("click", () => {
            const docElm = document.documentElement;
            const isFullscreen = document.fullscreenElement || document.webkitFullscreenElement || document.mozFullScreenElement || document.msFullscreenElement;

            if (!isFullscreen) {
                // Enter fullscreen
                if (docElm.requestFullscreen) {
                    docElm.requestFullscreen();
                } else if (docElm.webkitRequestFullscreen) {
                    docElm.webkitRequestFullscreen();
                } else if (docElm.mozRequestFullScreen) {
                    docElm.mozRequestFullScreen();
                } else if (docElm.msRequestFullscreen) {
                    docElm.msRequestFullscreen();
                }
            } else {
                // Exit fullscreen
                if (document.exitFullscreen) {
                    document.exitFullscreen();
                } else if (document.webkitExitFullscreen) {
                    document.webkitExitFullscreen();
                } else if (document.mozCancelFullScreen) {
                    document.mozCancelFullScreen();
                } else if (document.msExitFullscreen) {
                    document.msExitFullscreen();
                }
            }
        });

        // Change icon when fullscreen changes
        document.addEventListener("fullscreenchange", toggleIcon);
        document.addEventListener("webkitfullscreenchange", toggleIcon);
        document.addEventListener("mozfullscreenchange", toggleIcon);
        document.addEventListener("MSFullscreenChange", toggleIcon);

        function toggleIcon() {
            const isFullscreen = document.fullscreenElement || document.webkitFullscreenElement || document.mozFullScreenElement || document.msFullscreenElement;
            if (icon) {
                icon
                    .classList
                    .remove("ti-window-maximize", "ti-window-minimize");
                icon
                    .classList
                    .add(
                        isFullscreen
                            ? "ti-window-minimize"
                            : "ti-window-maximize"
                    );
            }
        }
    }
    /* === Fullscreen === */

    /* === Header Search Bar === */
    const menuMap = new Map();
    document
        .querySelectorAll(
            '.sidebar-content .menu-item:not(.has-sub) .menu-link'
        )
        .forEach(link => {
            const text = link
                .textContent
                .trim();
            const href = link.getAttribute("href");

            if (text && href && href !== "#") {
                menuMap.set(text, href);
            }
        });

    const searchData = [...menuMap.keys()];
    const headerSearchBar = new autoComplete({
        selector: "#header-search",
        data: {
            src: searchData,
            cache: true
        },
        resultItem: {
            highlight: true
        },
        events: {
            input: {
                selection: (event) => {
                    const selectedText = event.detail.selection.value;
                    headerSearchBar.input.value = selectedText;

                    const targetHref = menuMap.get(selectedText);
                    if (targetHref) {
                        window.location.href = targetHref;
                    }
                }
            }
        }
    });
    /* === Header Search Bar === */

})();