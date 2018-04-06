/**
 * Display toggleable forms into show views.
 */

$(document).ready(function() {
    $('.ui.with.form').toggleableForm();

    $(document)
        .on('submit', '.ui.with.form form', function() {
            $(this).removeClass('transition').addClass('loading');
        })
        .on('submit', '.ui.ajax.with.form form', function(e) {
            e.preventDefault();
            e.stopPropagation();
            e.stopImmediatePropagation();

            let form = $(this);
            let formUrl = form.attr('action') !== undefined ? form.attr('action') : window.location.href;
            let formMethod = form.attr('method') !== undefined ? form.attr('method') : 'GET';

            if (form[0].checkValidity()) {
                $.ajax({
                    url: formUrl,
                    method: formMethod,
                    processData: false,
                    contentType: false,
                    data: new FormData(form[0]),
                    success: function(html) {
                        var newForm = $(html);

                        form.html(newForm.html());
                        form.removeClass('loading');

                        initForms();
                    },
                    error: function() {

                    }
                });
            }
        });
});

jQuery.fn.toggleableForm = function() {

    $(document).on('click', '.ui.right.floated.toggler, .ui.form .ui.button.close', jQuery.fn.toggleForm);

    return this.each(function() {

        var o = $(this);
        var toggler = $('<a class="ui right floated toggler"><i class="edit outline icon center aligned"></i></a>');
        var form = o.find('.ui.form');

        o.find('.ui.header').append(toggler);
    });
};

jQuery.fn.toggleForm = function(e) {
    var currentWidget = $(e.currentTarget).closest('.ui.segment.with.form');
    var showContent = currentWidget.find('.ui.show.data');
    var formContent = currentWidget.find('.ui.form');
    var currentToggler = currentWidget.find('.ui.toggler');

    if (formContent.is(':visible')) {
        formContent.transition({
            'animation': 'slide down',
            'duration': 200,
            onComplete: function() {
                showContent.transition({ 'animation': 'slide down', 'duration': 200 });
                currentToggler.find('.icon').removeClass('undo alternate').addClass('edit outline');
            }
        });
    } else {
        showContent.transition({
            'animation': 'slide down',
            'duration': 200,
            onComplete: function() {
                formContent.transition({ 'animation': 'slide down', 'duration': 200 });
                currentToggler.find('.icon').removeClass('edit outline').addClass('undo alternate');
            }
        });
    }
};
