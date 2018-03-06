/**
 * Display toggleable forms into show views.
 */

$(document).ready(function() {
    $('.ui.with.form').toggleableForm();

    $('.ui.with.form form .submit').on('click', function() {
        $(this).closest('form').removeClass('transition').addClass('loading');
    });
});

jQuery.fn.toggleableForm = function() {
    return this.each(function() {

        var o = $(this);
        var toggler = $('<a class="ui right floated toggler"><i class="edit outline icon center aligned"></i></a>');
        var form = o.find('.ui.form');

        o.find('.ui.header').append(toggler);

        toggler.on('click', toggleForm);
        form.find('.ui.button.close').on('click', toggleForm);

        function toggleForm() {

            var showContent = o.find('.ui.show.data');

            if (form.is(':visible')) {
                form.transition({
                    'animation': 'slide down',
                    'duration': 200,
                    onComplete: function() {
                        showContent.transition({'animation': 'slide down', 'duration': 200});
                        toggler.find('.icon').removeClass('undo alternate').addClass('edit outline');
                    }
                });
            } else {
                showContent.transition({
                    'animation': 'slide down',
                    'duration': 200,
                    onComplete: function() {
                        form.transition({'animation': 'slide down', 'duration': 200});
                        toggler.find('.icon').removeClass('edit outline').addClass('undo alternate');
                    }
                });
            }

        }
    });
};
