(function () {

    "use strict";

    /* === Date Format 1 === */
    new Cleave('.date-format-1', {
        date: true,
        delimiter: '-',
        datePattern: ['d', 'm', 'Y']
    });
    /* === Date Format 1 === */

    /* === Date Format 2 === */
    new Cleave('.date-format-2', {
        date: true,
        delimiter: '-',
        datePattern: ['m', 'd', 'Y']
    });
    /* === Date Format 2 === */

    /* === Date Format 3 === */
    new Cleave('.date-format-3', {
        date: true,
        datePattern: ['m', 'y']
    });
    /* === Date Format 3 === */

    /* === Number Format === */
    new Cleave('.number-format', {
        numeral: true,
        numeralThousandsGroupStyle: 'lakh'
    });
    /* === Number Format === */

    /* === Time Format 1 === */
    new Cleave('.time-format-1', {
        time: true,
        timePattern: ['h', 'm', 's']
    });
    /* === Time Format 1 === */

    /* === Time Format 2 === */
    new Cleave('.time-format-2', {
        time: true,
        timePattern: ['h', 'm']
    });
    /* === Time Format 2 === */

    /* === Formatting Into Block === */
    new Cleave('.formatting-blocks', {
        blocks: [
            4, 3, 3, 4
        ],
        uppercase: true
    });
    /* === Formatting Into Block === */

    /* === Delimeter === */
    new Cleave('.delimiter', {
        delimiter: '·',
        blocks: [
            3, 3, 3
        ],
        uppercase: true
    });
    /* === Delimeter === */

    /* === Multiple Delimeter === */
    new Cleave('.delimiters', {
        delimiters: [
            '/', '/', '-'
        ],
        blocks: [
            3, 3, 3, 2
        ],
        uppercase: true
    });
    /* === Multiple Delimeter === */

    /* === Prefix === */
    new Cleave('.prefix-element', {
        prefix: 'Prefix',
        delimiter: '-',
        blocks: [
            6, 4, 4, 4
        ],
        uppercase: true
    });
    /* === Prefix === */

    /* === Phone Number === */
    new Cleave('.phone-number', {
        blocks: [3, 4, 4]
    });
    /* === Phone Number === */

})();
