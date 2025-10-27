(function () {
    "use strict";

    const alertPlaceholder = document.getElementById('liveAlertPlaceholder')
    const appendAlert = (message, type) => {
        const wrapper = document.createElement('div')
        wrapper.innerHTML = [
            `<div class="alert alert-${type} alert-dismissible mb-3" role="alert">`, `   <div>${message}</div>`, '   <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label=' +
                    '"Close"><i class="ti ti-x"></i></button>',
            '</div>'
        ].join('')

        alertPlaceholder.append(wrapper)
    }

    const alertTrigger = document.getElementById('liveAlertBtn')
    if (alertTrigger) {
        alertTrigger.addEventListener('click', () => {
            appendAlert('Nice, you triggered this alert message!', 'success')
        })
    }

})();
