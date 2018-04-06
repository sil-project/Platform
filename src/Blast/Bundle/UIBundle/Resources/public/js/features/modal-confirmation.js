$(document).on('click', '[data-requires-confirm]', function(event) {
    event.preventDefault();

    var actionButton = $(this);

    if (actionButton.is('a')) {
        $('#confirm-button').attr('href', actionButton.attr('href'));
    }

    if (actionButton.is('button')) {
        $('#confirm-button').on('click', function(event) {
            event.preventDefault();

            return actionButton.closest('form').submit();
        });
    }

    return $('#confirm-modal').modal('show');
});
