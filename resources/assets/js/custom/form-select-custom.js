(function () {

    "use strict";

    /* === Choice JS === */

    // === Single Select ===
    const singleDefault = document.getElementById('choices-single-default');
    if (singleDefault) {
        new Choices(singleDefault, {
            searchEnabled: true
        });
    }
    // === End Single Select ===

    // === Single Select Option Group ===
    const singleGroups = document.getElementById('choices-single-groups');
    if (singleGroups) {
        new Choices(singleGroups, {
            searchEnabled: true
        });
    }
    // === End Single Select Option Group ===

    // === Multiple Select ===
    const multipleDefault = document.getElementById('choices-multiple-default');
    if (multipleDefault) {
        new Choices(multipleDefault, {
            allowSearch: false
        });
    }
    // === End Multiple Select ===

    // === Multiple Select With Remove Button ===
    const multipleRemoveButton = document.getElementById('choices-multiple-remove-button');
    if (multipleRemoveButton) {
        new Choices(multipleRemoveButton, {
            allowHTML: true,
            removeItemButton: true
        });
    }
    // === End Multiple Select With Remove Button ===

    // === Multiple Select With Option Group ===
    const multipleGroups = document.getElementById('choices-multiple-groups');
    if (multipleGroups) {
        new Choices(multipleGroups, {
            allowHTML: true
        });
    }
    // === End Multiple Select With Option Group ===

    // === Email Address ===
    const emailInput = document.getElementById('choices-text-email-filter');
    if (emailInput) {
        const emailChoices = new Choices(emailInput, {
            allowHTML: true,
            editItems: true,
            addItemFilter: function (value) {
                if (!value) return false;
                const regex = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
                return new RegExp(regex.source, 'i').test(value);
            }
        });
        emailChoices.clearStore();
        emailChoices.setValue(['abc@hotmail.com']);
    }
    // === End Email Address ===

    // === Passing Through Value ===
    const presetValues = document.getElementById('choices-text-preset-values');
    if (presetValues) {
        new Choices(presetValues, {
            allowHTML: true,
            removeItems: true,
            items: [
                'one',
                {
                    value: 'two',
                    label: 'two',
                    customProperties: {
                        description: 'Numbers are infinite'
                    }
                }
            ]
        });
    }
    // === End Passing Through Value ===

    // === Config With No Search ===
    const noSearch = document.getElementById('choices-single-no-search');
    if (noSearch) {
        new Choices(noSearch, {
            allowHTML: true,
            searchEnabled: false,
            removeItemButton: true,
            choices: [
                { value: 'One', label: 'Label One' },
                { value: 'Two', label: 'Label Two' },
                { value: 'Three', label: 'Label Three' }
            ]
        }).setChoices([
            { value: 'Four', label: 'Label Four' },
            { value: 'Five', label: 'Label Five' },
            { value: 'Six', label: 'Label Six', selected: true }
        ], 'value', 'label', false);
    }
    // === End Config With No Search ===

    // === Unique Value ===
    const uniqueValues = document.getElementById('choices-text-unique-values');
    if (uniqueValues) {
        new Choices(uniqueValues, {
            allowHTML: true,
            paste: false,
            duplicateItemsAllowed: false,
            editItems: true
        });
    }
    // === End Unique Value ===

    /* === End Choice JS === */

})();