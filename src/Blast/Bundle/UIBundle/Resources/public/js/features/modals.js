$(document).ready(function() {
    $('[data-content]').popup({ inline: true });

    $('[data-modal]:not(.ui.modal)').on('click', function(e) {
        e.preventDefault();
        e.stopPropagation();
        e.stopImmediatePropagation();

        var btn = $(this);
        var modal = $('.ui.modal[data-modal="' + btn.attr('data-modal') + '"]');

        if (btn.attr('data-modal-not-dissmissible')) {
            modal.modal('setting', 'closable', false);
        }

        modal.modal('setting', 'onApprove', function() { return false; });
        modal.modal('setting', 'transition', 'pulse');
        modal.modal('show');
    });
});
