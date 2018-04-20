$(document).ready(function () {

    // BULK ACTION FORM HANDLING

    $(document).on('submit', 'form[name="grid-bulk-form"]', function (e) {
        e.preventDefault();
        e.stopPropagation();
        e.stopImmediatePropagation();

        let ids = [];
        let currentForm = $(this);

        let checkboxes = currentForm.closest('.table').find('tbody > tr > td:first-of-type > .field.grid-select > .ui.checkbox.checked > input[type="checkbox"]');

        $.each(checkboxes, function (i, checkbox) {
            ids.push($(checkbox).val());
        });

        let formData = new FormData(currentForm[0]);

        formData.append('ids', ids.join(','));

        $.ajax({
            url: currentForm.attr('action'),
            data: formData,
            method: currentForm.attr('method'),
            processData: false,
            contentType: false,
            success: function (data, textStatus, request) {
                window.location.reload();
            }
        });
    });

    // SELECTABLES ROWS

    var selectAll = $('.select.all > .ui.checkbox');
    var table = selectAll.closest('.ui.table');
    var suspendCount = false; // suspend count routine to improve performances

    selectAll.checkbox({
        onChange: function () {
            if (suspendCount == false) {
                suspendCount = true;
                var checked = $(this).closest('.ui.checkbox').checkbox('is checked');
                var checkboxes = table.find('tbody > tr > td:first-of-type > .field.grid-select > .ui.checkbox');

                checkboxes.checkbox(checked ? 'check' : 'uncheck');

                selectAll.checkbox(checked ? 'check' : 'uncheck');

                suspendCount = false;
                countChecked(table);
            }
        }
    });

    var checkboxes = table.find('tbody > tr > td:first-of-type > .field.grid-select > .ui.checkbox');

    checkboxes.checkbox({
        onChange: function () {
            if (suspendCount == false) {
                countChecked(table);
            }
        }
    });

    countChecked(table);

    function countChecked(table) {
        var countLabel = table.find('tfoot > tr > th > .selection-count');
        countLabel.html(table.find('tbody > tr > td:first-of-type > .field.grid-select > .ui.checkbox.checked').length);
    }
});
