$(document).ready(function () {

    var fieldSets = ['professional', 'amateur', 'culture', 'plant', 'inner', 'production', 'commercial'];

    $(fieldSets).each(function (key, fieldSet) {

        var fieldSelector = '#filter_' + fieldSet + '_descriptions_value_Field';
        var valueSelector = '#filter_' + fieldSet + '_descriptions_value_Value';

        if ($('#filter_' + fieldSet + '_descriptions_value').is(':visible'))
            getWidget(fieldSet, $(fieldSelector).val(), $(valueSelector).val());

        $(fieldSelector).change(function (data) {

            getWidget(fieldSet, data.val);
        });
    });
});

var getWidget = function (fieldSet, field, previousValue) {

    $.get('getFilterWidget/' + fieldSet + '/' + field, function (field) {

        var widget = $(field);
        var selector;
        var fieldId = 'filter_' + fieldSet + '_descriptions_value_Value';
        var valueSelector = '#filter_' + fieldSet + '_descriptions_value_Value';

        $(widget).prop({
            id: fieldId,
            name: 'filter[' + fieldSet + '_descriptions][value][Value]'
        });

        if ($('#' + fieldId).prop('tagName') == 'SELECT') {
            selector = '#s2id_filter_' + fieldSet + '_descriptions_value_Value';
        } else {
            selector = '#' + fieldId;
        }

        var label = $(selector).siblings('label');
        var newWidget = $('<div></div>').append(label).append(widget);

        $(selector).parent().replaceWith(newWidget);

        if ($(widget).prop('tagName') == 'SELECT')
            Admin.setup_select2();

        if (undefined !== previousValue) {
            $(valueSelector).val(previousValue);
            if ($(widget).prop('tagName') == 'SELECT')
                $(valueSelector).trigger('change');
        }
    });
};