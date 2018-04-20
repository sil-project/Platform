$(document).on('click', '[data-requires-confirm]', function (event) {
    event.preventDefault();

    var actionButton = $(this);
    var confirmModal = $('#confirm-modal');

    if (actionButton.is('a')) {
        $('#confirm-button').attr('href', actionButton.attr('href'));
    }

    if (actionButton.is('button')) {
        $('#confirm-button').on('click', function (event) {
            event.preventDefault();

            return actionButton.closest('form').submit();
        });
    }

    let messages = {
        'title': actionButton.attr('data-confirm-title') || Translator.trans('blast.ui.modal.confirm.title', {}, 'messages'),
        'message': actionButton.attr('data-confirm-message') || Translator.trans('blast.ui.modal.confirm.message', {}, 'messages'),
        'yes_label': actionButton.attr('data-confirm-yes') || Translator.trans('blast.ui.yes_label', {}, 'messages'),
        'no_label': actionButton.attr('data-confirm-no') || Translator.trans('blast.ui.no_label', {}, 'messages'),
    };

    confirmModal.find('> .ui.header').html(messages.title);
    confirmModal.find('> .content').html(messages.message);
    confirmModal.find('> .actions > .ui.cancel.button').html(messages.no_label);
    confirmModal.find('> .actions > .ui.ok.button').html(messages.yes_label);

    return confirmModal.modal('show');
});
