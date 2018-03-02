$(document).ready(function() {
    var selectAll = $('.ui.checkbox.select.all');
    var table = selectAll.closest('.ui.table');

    selectAll.checkbox({
        onChange: function() {
            var checked = $(this).closest('.ui.checkbox').checkbox('is checked');
            var checkboxes = table.find('tbody > tr > td:first-of-type > .ui.checkbox');

            checkboxes.checkbox(checked ? 'check' : 'uncheck');
        }
    })
});
