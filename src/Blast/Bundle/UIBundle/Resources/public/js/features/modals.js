$(document).ready(function() {
    $('[data-requires-confirm]').modalConfirmation();

    $('[data-content]').popup({inline: true});

    $('[data-modal]:not(.ui.modal)').on('click', function(e) {
        e.preventDefault();
        e.stopPropagation();
        e.stopImmediatePropagation();

        var btn = $(this);
        var modal = $('.ui.modal[data-modal="' + btn.attr('data-modal') + '"]');

        modal.modal('setting', 'transition', 'pulse').modal('show');
    });
});
