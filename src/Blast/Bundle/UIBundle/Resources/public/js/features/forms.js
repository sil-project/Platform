$(document).ready(function() {
    initForms();
    $(document).on('click', '.ui:not(.with.form) > .ui.form > .ui.button.close', function() {
        window.history.back();
    });
});

function initForms() {
    $('.ui.dropdown').dropdown();
    $('.ui.checkbox').checkbox();
    $('.ui.calendar input[type="text"]').flatpickr({
        enableTime: true,
        time_24hr: true,
        // mode: "range",
        locale: "fr"
    });
}
