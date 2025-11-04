(function () {
    "use strict";

    // === Basic Date Picker ===
    const basicDate = document.getElementById('date');
    if (basicDate) {
        flatpickr("#date", { disableMobile: true });
    }
    // === End Basic Date Picker ===

    // === Date & Time Picker ===
    const datetime = document.getElementById('datetime');
    if (datetime) {
        flatpickr("#datetime", {
            enableTime: true,
            dateFormat: "Y-m-d H:i",
            disableMobile: true
        });
    }
    // === End Date & Time Picker ===

    // === Human Friendly Date ===
    const friendly = document.getElementById('humanfrienndlydate');
    if (friendly) {
        flatpickr("#humanfrienndlydate", {
            altInput: true,
            altFormat: "F j, Y",
            dateFormat: "Y-m-d",
            disableMobile: true
        });
    }
    // === End Human Friendly Date ===

    // === Date Range Picker ===
    const range = document.getElementById('daterange');
    if (range) {
        flatpickr("#daterange", {
            mode: "range",
            dateFormat: "Y-m-d",
            disableMobile: true
        });
    }
    // === End Date Range Picker ===

    // === Time Picker ===
    const timePicker = document.getElementById('timepikcr');
    if (timePicker) {
        flatpickr("#timepikcr", {
            enableTime: true,
            noCalendar: true,
            dateFormat: "H:i",
            disableMobile: true
        });
    }
    // === End Time Picker ===

    // === Time Picker (24 Hour Format) ===
    const time24 = document.getElementById('timepickr1');
    if (time24) {
        flatpickr("#timepickr1", {
            enableTime: true,
            noCalendar: true,
            dateFormat: "H:i",
            time_24hr: true,
            disableMobile: true
        });
    }
    // === End Time Picker (24 Hour Format) ===

    // === Time Picker With Time Limit ===
    const limitTime = document.getElementById('limittime');
    if (limitTime) {
        flatpickr("#limittime", {
            enableTime: true,
            noCalendar: true,
            dateFormat: "H:i",
            minTime: "16:00",
            maxTime: "22:30",
            disableMobile: true
        });
    }
    // === End Time Picker With Time Limit ===

    // === DateTime Picker With Limited Time Range ===
    const limitDateTime = document.getElementById('limitdatetime');
    if (limitDateTime) {
        flatpickr("#limitdatetime", {
            enableTime: true,
            minTime: "16:00",
            maxTime: "22:00",
            disableMobile: true
        });
    }
    // === End DateTime Picker With Limited Time Range ===

    // === Inline Calendar ===
    const inlineCalendar = document.getElementById('inlinecalendar');
    if (inlineCalendar) {
        flatpickr("#inlinecalendar", {
            inline: true,
            disableMobile: true
        });
    }
    // === End Inline Calendar ===

    // === Date Picker With Week Number ===
    const weeknum = document.getElementById('weeknum');
    if (weeknum) {
        flatpickr("#weeknum", {
            weekNumbers: true,
            disableMobile: true
        });
    }
    // === End Date Picker With Week Number ===

    // === Inline Time Picker ===
    const inlineTime = document.getElementById('inlinetime');
    if (inlineTime) {
        flatpickr("#inlinetime", {
            inline: true,
            enableTime: true,
            noCalendar: true,
            dateFormat: "H:i",
            disableMobile: true
        });
    }
    // === End Inline Time Picker ===

    // === Time Picker With Preloaded Time ===
    const preTime = document.getElementById('pretime');
    if (preTime) {
        flatpickr("#pretime", {
            enableTime: true,
            noCalendar: true,
            dateFormat: "H:i",
            defaultDate: "13:45",
            disableMobile: true
        });
    }
    // === End Time Picker With Preloaded Time ===

})();