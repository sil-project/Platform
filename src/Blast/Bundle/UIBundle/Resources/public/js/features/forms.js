$(document).ready(function() {
    $('.ui.dropdown').dropdown();
    $('.ui.checkbox').checkbox();
    $('.ui.calendar').each(function(i, item) {
        $(item).calendar({type: $(item).attr('data-type')});
    });
});
