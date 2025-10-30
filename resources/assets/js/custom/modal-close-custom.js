(function () {

    "use strict";

    const modal = document.getElementById('formmodal');
    modal.addEventListener('show.bs.modal', (event) => {
        const button = event.relatedTarget;
        const recipient = button?.getAttribute('data-bs-whatever') || '';
        modal.querySelector('.modal-title').textContent = `New message to ${recipient}`;
        modal.querySelector('.modal-body input').value = recipient;
    });

    /* === Animation === */
        /* === Showing === */
        document
            .querySelectorAll(".modal-effect")
            .forEach(e => {
                e.addEventListener('click', function (e) {
                    e.preventDefault();
                    let effect = this.getAttribute('data-bs-effect');
                    document
                        .querySelector("#modaldemo8")
                        .classList
                        .add(effect);
                });
            })
        /* === Showing === */

        /* === Hidden === */
        document
            .getElementById("modaldemo8")
            .addEventListener('hidden.bs.modal', function (e) {
                let removeClass = this
                    .classList
                    .value
                    .match(/(^|\s)effect-\S+/g);
                removeClass = removeClass[0].trim();
                this
                    .classList
                    .remove(removeClass);
            });
        /* === Hidden === */
    /* === Animation === */

})();
