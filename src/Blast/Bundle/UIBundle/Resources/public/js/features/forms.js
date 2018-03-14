$(document).ready(function() {
    $('.ui.dropdown').dropdown();
    $('.ui.checkbox').checkbox();
    $('.ui.calendar input[type="text"]').flatpickr({
        enableTime: true,
        time_24hr: true,
        // mode: "range",
        locale: "fr"
    });
});
