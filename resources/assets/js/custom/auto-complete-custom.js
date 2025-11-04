(function () {

    "use strict";

    /* === Basic === */
    const autoCompleteJS = new autoComplete({
        selector: "#autoComplete",
        placeHolder: "Search for Food & Drinks Combo",
        data: {
            src: [
                'Nasi Goreng, Teh Manis',
                'Rendang, Es Teh',
                'Sate Ayam, Es Jeruk',
                'Gado-Gado, Air Mineral',
                'Bakso, Teh Botol',
                'Soto Ayam, Es Kelapa Muda',
                'Nasi Padang, Jus Alpukat',
                'Pempek, Cuko',
                'Rawon, Wedang Jahe',
                'Mie Aceh, Es Timun Serut'
            ],
            cache: true
        },
        resultItem: {
            highlight: true
        },
        events: {
            input: {
                selection: (event) => {
                    const selection = event.detail.selection.value;
                    autoCompleteJS.input.value = selection;
                }
            }
        }
    });
    /* === Basic === */

    /* === Advanced === */
    const autoCompleteJS1 = new autoComplete({
        selector: "#autoComplete-color",
        placeHolder: "Search For Advanced Colors...",
        data: {
            src: [
                'Lavender',
                'Turquoise',
                'Coral',
                'Sapphire',
                'Emerald',
                'Rose Gold',
                'Azure',
                'Goldenrod',
                'Amethyst',
                'Crimson',
                'Periwinkle',
                'Mint Green',
                'Tangerine',
                'Charcoal',
                'Champagne',
                'Aqua',
                'Ruby',
                'Topaz',
                'Cerulean',
                'Pearl'
            ],
            cache: true
        },
        resultsList: {
            element: (list, data) => {
                const info = document.createElement("p");
                if (data.results.length > 0) {
                    info.innerHTML = `Displaying <strong>${data.results.length}</strong> out of <strong>${data.matches.length}</strong> results`;
                } else {
                    info.innerHTML = `Found <strong>${data.matches.length}</strong> matching results for <strong>"${data.query}"</strong>`;
                }
                list.prepend(info);
            },
            noResults: true,
            maxResults: 15,
            tabSelect: true
        },
        resultItem: {
            highlight: true
        },
        events: {
            input: {
                selection: (event) => {
                    const selection = event.detail.selection.value;
                    autoCompleteJS1.input.value = selection;
                }
            }
        }
    });
    /* === Advanced === */

})();
