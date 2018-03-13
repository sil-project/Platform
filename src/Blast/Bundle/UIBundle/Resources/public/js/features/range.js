$(document).ready(function() {
    $('.ui.range').each(function(i, elem) {
        var associatedInputSelector = 'input[type="hidden"][name="' + $(elem).data('name') + '"]';
        $(elem).range({
            min: $(elem).data('min'),
            max: $(elem).data('max'),
            input: associatedInputSelector,
            start: $(associatedInputSelector).val(),
            onChange: function(value, meta) {
                if (meta.triggeredByUser) {
                    $('.ui.label[data-name="' + $(elem).data('name') + '"]').html(value);
                }
            }
        });
    });
});
