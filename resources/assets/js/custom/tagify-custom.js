(function () {

    "use strict";

    /* === Basic === */
        const basicInput = document.querySelector('input[name="basic"]');
        if (basicInput) {
            new Tagify(basicInput);
        }
    /* === Basic === */

    /* === Read Only Tag === */
        const readOnlyInput = document.querySelector('input[name="tags4"]');
        if (readOnlyInput) {
            new Tagify(readOnlyInput, {
                readOnly: true,
            });
        }
    /* === Read Only Tag === */

    /* === Read Only Mix === */
        const mixedReadOnlyInput = document.querySelector('input[name="tags-readonly-mix"]');
        if (mixedReadOnlyInput) {
            new Tagify(mixedReadOnlyInput, {
                readOnly: true,
            });
        }
    /* === Read Only Mix === */

    /* === Custom Suggestion === */
        const customDropdownInput = document.querySelector('input[name="input-custom-dropdown"]');
        if (customDropdownInput) {
            new Tagify(customDropdownInput, {
                whitelist: [
                    // Programming languages (a long list trimmed for brevity)
                    "A# .NET",
                    "ABAP",
                    "Ada",
                    "ALGOL 60",
                    "APL",
                    "Assembly language",
                    "Awk",
                    "Bash",
                    "Basic",
                    "C",
                    "C++",
                    "C#",
                    "COBOL",
                    "CoffeeScript",
                    "Common Lisp",
                    "Dart",
                    "Elixir",
                    "Erlang",
                    "F#",
                    "Fortran",
                    "Go",
                    "Groovy",
                    "Haskell",
                    "HTML",
                    "Java",
                    "JavaScript",
                    "Julia",
                    "Kotlin",
                    "Lisp",
                    "Lua",
                    "MATLAB",
                    "Objective-C",
                    "Pascal",
                    "Perl",
                    "PHP",
                    "Prolog",
                    "Python",
                    "R",
                    "Ruby",
                    "Rust",
                    "Scala",
                    "Shell",
                    "SQL",
                    "Swift",
                    "TypeScript",
                    "VBA",
                    "Visual Basic",
                    "Zig"
                ],
                maxTags: 10, // Maximum number of tags the user can enter
                dropdown: {
                    maxItems: 20, // Maximum number of suggestions shown in the dropdown
                    classname: "tags-look", // Custom class name for styling the dropdown
                    enabled: 0, // Show suggestions only after user types (0 = disabled on focus)
                    closeOnSelect: false // Keep the dropdown open after a tag is selected
                }
            });
        }
    /* === Custom Suggestion === */

    /* === Disabled User Input === */
        const disabledInput = document.querySelector('input[name="tags-disabled-user-input"]');
        if (disabledInput) {
            new Tagify(disabledInput, {
                whitelist: [1, 2, 3, 4, 5], // Predefined list of allowed tags
                userInput: false            // Disables typing custom tags (only from whitelist)
            });
        }
    /* === Disabled User Input === */

    /* === Drag & Sort === */
        const dragSortInput = document.querySelector('input[name="drag-sort"]');
        if (dragSortInput) {
            const dragSortTagify = new Tagify(dragSortInput);
            new DragSort(dragSortTagify.DOM.scope, {
                selector: '.' + dragSortTagify.settings.classNames.tag, // Select individual tags
                callbacks: {
                    dragEnd: () => {
                        // Update Tagify's internal value after dragging ends
                        dragSortTagify.updateValueByDOMTags();
                    }
                }
            });
        }
    /* === Drag & Sort === */

    /* === Single Value Select === */
        const selectModeInput = document.querySelector('input[name="tags-select-mode"]');
        if (selectModeInput) {
            const selectModeTagify = new Tagify(selectModeInput, {
                enforceWhitelist: true, // Only allow tags from the whitelist
                mode: "select",         // Only one item can be selected at a time (like <select>)
                whitelist: ["First", "Second", "Third"], // Allowed tags
                blacklist: ["foo", "bar"] // Disallowed tags
            });

            // Triggered when a tag is added (selected)
            selectModeTagify.on('add', onTagAdd);

            // Triggered when the Tagify input receives focus
            selectModeTagify.DOM.input.addEventListener('focus', onTagInputFocus);

            // Event handler: when a tag is added
            function onTagAdd(event) {
                console.log("Tag added:", event.detail);
            }

            // Event handler: when the input is focused
            function onTagInputFocus(event) {
                console.log("Input focused:", event);
            }
        }
    /* === Single Value Select === */

    /* === Mix Text & Tag === */
        // Whitelist for "@" prefix — complex user objects
        const mentionWhitelist = [
            {
                value: 100,
                text: 'kenny',
                title: 'Kenny McCormick'
            }, {
                value: 200,
                text: 'cartman',
                title: 'Eric Cartman'
            }, {
                value: 300,
                text: 'kyle',
                title: 'Kyle Broflovski'
            }, {
                value: 400,
                text: 'token',
                title: 'Token Black'
            }, {
                value: 500,
                text: 'jimmy',
                title: 'Jimmy Valmer'
            }, {
                value: 600,
                text: 'butters',
                title: 'Butters Stotch'
            }, {
                value: 700,
                text: 'stan',
                title: 'Stan Marsh'
            }, {
                value: 800,
                text: 'randy',
                title: 'Randy Marsh'
            }, {
                value: 900,
                text: 'Mr. Garrison',
                title: 'POTUS'
            }, {
                value: 1000,
                text: 'Mr. Mackey',
                title: "M'Kay"
            }
        ];

        // Whitelist for "#" prefix — simple string values
        const hashtagWhitelist = [
            'Homer Simpson',
            'Marge Simpson',
            'Bart',
            'Lisa',
            'Maggie',
            'Mr. Burns',
            'Ned',
            'Milhouse',
            'Moe'
        ];

        // Initialize Tagify on input[name="mix"]
        const mixInput = document.querySelector('[name="mix"]');

        if (mixInput) {
            const mixTagify = new Tagify(mixInput, {
                mode: 'mix', // Enable mixed-content mode (text + tags)
                pattern: /@|#/, // Trigger pattern: "@" or "#"
                tagTextProp: 'text', // Use 'text' property for rendering tag label
                whitelist: [
                    ...mentionWhitelist,
                    ...hashtagWhitelist
                ].map(
                    item => typeof item === 'string'
                        ? {
                            value: item
                        }
                        : item
                ),
                validate: data => !/[^a-zA-Z0-9 ]/.test(data.value), // Only allow alphanumeric + space
                dropdown: {
                    enabled: 1, // Show dropdown after typing 1 character
                    position: 'text', // Show dropdown next to typed text (caret position)
                    mapValueTo: 'text', // Use 'text' for displaying dropdown items
                    highlightFirst: true // Highlight first suggestion automatically
                },
                callbacks: {
                    add: console.log, // Log on tag add
                    remove: console.log // Log on tag remove
                }
            });

            // Update whitelist dynamically based on prefix typed (@ or #)
            mixTagify.on('input', function (e) {
                const prefix = e.detail.prefix;
                const typedValue = e.detail.value;

                if (!prefix) 
                    return;
                
                // Clear the current whitelist to prepare for dynamic update
                mixTagify.settings.whitelist.length = 0;

                // Set appropriate whitelist based on prefix
                if (prefix === '@') {
                    mixTagify.whitelist = mentionWhitelist;
                } else if (prefix === '#') {
                    mixTagify.whitelist = hashtagWhitelist;
                }

                // Show suggestions only if more than 1 character is typed
                if (typedValue.length > 1) {
                    mixTagify
                        .dropdown
                        .show(typedValue);
                }

                // Debug logs
                console.log('Current tagify values:', mixTagify.value);
                console.log('Input event details:', e.detail);
            });

            // Optional: Additional "add" event handler (already logged above via callback)
            mixTagify.on('add', function (e) {
                console.log('Tag added:', e.detail);
            });
        }
    /* === Mix Text & Tag === */

    /* === User List Tag === */
        const usersInput = document.querySelector('input[name="users-list-tags"]');

        // Tag template (how tags appear after being selected)
        function tagTemplate(tagData) {
            return `
                <tag title="${tagData.email}" contenteditable="false" spellcheck="false"
                    tabIndex="-1" class="tagify__tag ${tagData.class || ""}"
                    ${this.getAttributes(tagData)}>
                    <x class="tagify__tag__removeBtn" role="button" aria-label="remove tag"></x>
                    <div>
                        <div class="tagify__tag__avatar-wrap">
                            <img onerror="this.style.visibility='hidden'" src="${tagData.avatar}">
                        </div>
                        <span class="tagify__tag-text">${tagData.name}</span>
                    </div>
                </tag>
            `;
        }

        // Dropdown item template (how each suggestion appears)
        function suggestionItemTemplate(tagData) {
            return `
                <div ${this.getAttributes(tagData)}
                    class="tagify__dropdown__item ${tagData.class || ""}"
                    tabindex="0" role="option">
                    ${tagData.avatar ? `
                        <div class="tagify__dropdown__item__avatar-wrap">
                            <img onerror="this.style.visibility='hidden'" src="${tagData.avatar}">
                        </div>` : ''}
                    <strong>${tagData.name}</strong>
                    <span>${tagData.email}</span>
                </div>
            `;
        }

        // Custom dropdown header template
        function dropdownHeaderTemplate(suggestions) {
            return `
                <header data-selector="tagify-suggestions-header"
                        class="${this.settings.classNames.dropdownItem} ${this.settings.classNames.dropdownItem}__addAll">
                    <strong style="grid-area: add">${this.value.length ? "Add Remaining" : "Add All"}</strong>
                    <span style="grid-area: remaning">${suggestions.length} members</span>
                    <a class="remove-all-tags">Remove all</a>
                </header>
            `;
        }

        // Initialize Tagify
        const tagify = new Tagify(usersInput, {
            tagTextProp: "name",
            skipInvalid: true,
            dropdown: {
                enabled: 0,
                closeOnSelect: false,
                classname: "users-list",
                searchKeys: ["name", "email"]
            },
            templates: {
                tag: tagTemplate,
                dropdownItem: suggestionItemTemplate,
                dropdownHeader: dropdownHeaderTemplate
            },
            whitelist: [
                {
                    value: 1,
                    name: "Justinian Hattersley",
                    avatar: "https://i.pravatar.cc/80?img=1",
                    email: "jhattersley0@ucsd.edu",
                    team: "A"
                },
                {
                    value: 2,
                    name: "Antons Esson",
                    avatar: "https://i.pravatar.cc/80?img=2",
                    email: "aesson1@ning.com",
                    team: "B"
                },
                // ... continue with others ...
            ],
            transformTag(tagData) {
                const { name, email } = parseFullValue(tagData.name);
                tagData.name = name;
                tagData.email = email || tagData.email;
            },
            validate({ name, email }) {
                if (!email && name) {
                    ({ name, email } = parseFullValue(name));
                }
                if (!name) return "Missing name";
                if (!validateEmail(email)) return "Invalid email";
                return true;
            }
        });

        // Group dropdown items by "team"
        tagify.dropdown.createListHTML = suggestionsList => {
            const teams = suggestionsList.reduce((acc, item) => {
                const team = item.team || "Not Assigned";
                acc[team] = acc[team] || [];
                acc[team].push(item);
                return acc;
            }, {});

            const renderGroup = users => users.map(user => {
                const value = tagify.dropdown.getMappedValue.call(tagify, user);
                user.value = typeof value === "string" ? escapeHTML(value) : value;
                return tagify.settings.templates.dropdownItem.call(tagify, user);
            }).join("");

            return Object.entries(teams)
                .map(([team, users]) => `
                    <div class="tagify__dropdown__itemsGroup" data-title="Team ${team}:">
                        ${renderGroup(users)}
                    </div>
                `)
                .join("");
        };

        // === Utility Functions ===
        function validateEmail(email) {
            return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
        }

        function parseFullValue(value) {
            const parts = value.split(/<(.*?)>/g);
            const name = parts[0].trim();
            const email = parts[1]?.replace(/<(.*?)>/g, "").trim();
            return { name, email };
        }

        // escapeHTML for value sanitization (basic version)
        function escapeHTML(s) {
            return typeof s === "string"
                ? s.replace(/&/g, "&amp;")
                    .replace(/</g, "&lt;")
                    .replace(/>/g, "&gt;")
                    .replace(/"/g, "&quot;")
                    .replace(/'/g, "&#39;")
                : s;
        }

        // Handle dropdown selection (Add All / Remove All)
        tagify
            .on("dropdown:select", onSelectSuggestion)
            .on("edit:start", onEditStart);

        function onSelectSuggestion(e) {
            const target = e.detail.event.target;
            const dropdownItemClass = tagify.settings.classNames.dropdownItem;

            if (target.matches(".remove-all-tags")) {
                tagify.removeAllTags();
            } else if (e.detail.elm.classList.contains(`${dropdownItemClass}__addAll`)) {
                tagify.dropdown.selectAll();
            }
        }

        // Handle edit start to format name <email>
        function onEditStart({ detail: { tag, data } }) {
            tagify.setTagTextNode(tag, `${data.name} <${data.email}>`);
        }
    /* === User List Tag === */

})();
