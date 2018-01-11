if (LI === undefined)
    var LI = {};
if (LI.decorator === undefined)
    LI.decorator = [];

LI.decorator.init = function() {
    LI.decorator.inline();
    LI.decorator.newLine();
    LI.decorator.multipleCheckbox();
};

$(document)
    .ready(function() {
        LI.decorator.init();
    })
    .on('sonata.add_element', function() {
        LI.decorator.init();
    });

// display several sonata form fields on the same line
LI.decorator.inline = function() {

    $('.inline-block').each(function() {

        var widget = LI.decorator.getWidget($(this));
        var width = widget.attr('width');
        var height = widget.attr('height');

        var formGroup = LI.decorator.getFormGroup($(this));

        formGroup
            .css({ 'width': width + '%' })
            .addClass('field-as-inline');

        $(this).css('height', height + 'px');

    });
};

// Add line breaks to inline-block fields
LI.decorator.newLine = function() {

    $('.new-line, .new-line-before, .new-line-after').each(function() {

        var widget = LI.decorator.getWidget($(this));
        var width = widget.attr('width');
        var height = widget.attr('height');
        var widgetClass = '';
        var currentFieldWrapper = LI.decorator.getFormGroup($(this));

        if ($(this).hasClass('new-line-before')) {
            widgetClass = 'new-line-before';
            currentFieldWrapper.before('<br />');
        } else if ($(this).hasClass('new-line-after')) {
            widgetClass = 'new-line-after';
            currentFieldWrapper.after('<br />');
        }

        currentFieldWrapper.css({
            'width': width + '%',
            'height': height + 'px',
        }).addClass(widgetClass);

        $(this).css('height', height + 'px');
    });
};

LI.decorator.multipleCheckbox = function() {

    $('.multiple-checkbox').each(function() {
        LI.decorator.getFormGroup($(this)).css({
            'clear': 'both',
        });
    });
};

//check if the field as one or two level form group parent
LI.decorator.getFormGroup = function(field) {

    var formGroup = field.closest('div.form-group').parent(':not(.nested-form)').closest('div.form-group');

    if (formGroup.length === 0) {

        formGroup = field.closest('div.form-group');
    }

    return formGroup;
};

//check what type of widget is being dealt with
LI.decorator.getWidget = function(field) {

    if (field.siblings('select') > 0) {

        return field.siblings('select');
    } else {

        return field;
    }
};
