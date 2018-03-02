$(document).ready(function() {
    var searchField = $('.ui.top.bar .search.field');

    $(document)
        .on('focusin', '.ui.top.bar .search.field input', function() {
            searchField.addClass('focused');
        })
        .on('focusout', '.ui.top.bar .search.field input', function() {
            searchField.removeClass('focused');
        })
    ;
});
