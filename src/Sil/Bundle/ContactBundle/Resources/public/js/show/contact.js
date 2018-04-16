
$(document)
    /**
     * Display contact edit form
     */
    .on('click', '#edit-contact', () => {
        showContactLoader();
        var url = $('#contact').data('edit-url');

        $.get(url, response => {
            $('#contact-header').replaceWith($(response));

            hideContactLoader();
        });
    })

    /**
     * Cancel contact edition
     */
    .on('click', '#cancel-contact', () => {
        location.reload();
    })

    /**
     * Display Group create form
     */
    .on('click', '#add-contact-group', () => {
        resetForm($('#group-form'));

        $('#group-modal').modal('show');

        return false;
    })

    /**
     * Close Group form
     */
    .on('click', '#group-cancel', () => {
        $('#group-modal').modal('hide');
    })

    /**
     * Group form submission
     */
    .on('submit', '#contact-group-form', function() {
        showGroupLoader();
        var url;

        //Temporary to be removed
        $.fn.showFlashMessage = function(a, b) {alert(b)};

        // populate contact id field
        $('#group_member_member').val($('#contact').data('id'));

        var data = new FormData($(this).get(0));

        $.ajax({
            url: $(this).data('action'),
            method: 'POST',
            data: data,
            processData: false,
            contentType: false,
            success: response => {
                $('#contact-group-list').replaceWith($(response));

                $('#group-modal').modal('hide');
                hideGroupLoader();
            },
            error: (error) => {
                console.error(error);

                hideGroupLoader();

                $(document).showFlashMessage('error', error.responseText);
            }
        });

        return false;
    })

    /**
     * Remove Group from list
     */
    .on('click', '#contact-groups .remove-group', function() {
        showGroupLoader();
        var group = $(this).parents('.contact-group');
        var contactId = $('#contact').data('id');
        var url = group.data('remove-url') + '&id=' + contactId;

        $.get(url, response => {
            $('#contact-group-list').replaceWith($(response));
            hideGroupLoader();
        });

        return false;
    })

    /**
     * Display Phone create form
     */
    .on('click', '#add-contact-phone', () => {
        resetForm($('#phone-form'));
        $('#phone-modal').modal('show');

        return false;
    })

    /**
     * Phone form submission
     */
    .on('submit', '#phone-form', function() {
        showPhoneLoader();
        var url;

        if($(this).data('edit')) {
            $(this).data('edit', false);

            url = $(this).data('edit-action');
        } else {
            url = $(this).data('action')
        }

        // populate contact id field
        $('#phone_contact').val($('#contact').data('id'));

        var data = new FormData($(this).get(0));

        $.ajax({
            url: url,
            method: 'POST',
            data: data,
            processData: false,
            contentType: false,
            success: response => {
                response = $(response);

                hidePhoneLoader();

                // Failure due to form errors
                if(response.find('#phone-form').length > 0) {
                    $('#phone-form').parent().replaceWith(response);

                    return;
                }

                $('#phone-list').replaceWith(response);

                $('#phone-modal').modal('hide');
            }
        });

        return false;
    })

    /**
     * Close Phone form
     */
    .on('click', '#cancel-phone', () => {
        $('#phone-modal').modal('hide');
    })

    /**
     * Edit phone
     */
    .on('click', '#phone-list .edit-phone', function() {
        showPhoneLoader();

        var phone = $(this).parents('.contact-phone');

        $.get(phone.data('edit-url'), response => {
            $('#phone-form').parent().replaceWith($(response));
            $('#phone-form').data('edit', true);
            $('#phone-modal').modal('show');

            initDropdowns();
            hidePhoneLoader();
        });

        return false;
    })

    /**
     * Remove phone from list
     */
    .on('click', '#phone-list .remove-phone', function() {
        showPhoneLoader();
        var phone = $(this).parents('.contact-phone');

        $.get(phone.data('remove-url'), response => {
            $('#phone-list').replaceWith($(response));
            hidePhoneLoader();
        });

        return false;
    })

    /**
     * Set phone as contact default
     */
    .on('click', '#phone-list .make-default', function() {
        showPhoneLoader();
        var contactId = $('#contact').data('id');
        var phone = $(this).parents('.contact-phone');

        $.get(phone.data('make-default-url'), response => {
            $('#phone-list').replaceWith($(response));
            hidePhoneLoader();
        });
    })

    /**
     * Add address
     */
    .on('click', '#add-contact-address', () => {
        resetForm($('#address-form'));
        $('#address-modal').modal('show');

        return false;
    })

    /**
     * Address form submission
     */
    .on('submit', '#address-form', function() {
        showAddressLoader();
        var url;

        if($(this).data('edit')) {
            $(this).data('edit', false);

            url = $(this).data('edit-action');
        } else {
            url = $(this).data('action')
        }

        // populate contact id field
        $('#address_contact').val($('#contact').data('id'));

        var data = new FormData($(this).get(0));

        $.ajax({
            url: url,
            method: 'POST',
            data: data,
            processData: false,
            contentType: false,
            success: response => {
                response = $(response);

                hideAddressLoader();

                if(response.find('#address-form').length > 0) {
                    $('#address-form').parent().replaceWith(response);

                    return;
                }
                $('#address-list').replaceWith(response);

                $('#address-modal').modal('hide');
            }
        });

        return false;
    })

    /**
     * Close address form
     */
    .on('click', '#cancel-address', () => {
        $('#address-modal').modal('hide');
    })

    /**
     * Edit address
     */
    .on('click', '#address-list .edit-address', function() {
        showAddressLoader();

        var address = $(this).parents('.contact-address');

        $.get(address.data('edit-url'), response => {
            $('#address-form').parent().replaceWith($(response));
            $('#address-form').data('edit', true);
            $('#address-modal').modal('show');

            initDropdowns();
            hideAddressLoader();
        });

        return false;
    })

    /**
     * Remove address from list
     */
    .on('click', '#address-list .remove-address', function() {
        showAddressLoader();
        var address = $(this).parents('.contact-address');

        $.get(address.data('remove-url'), response => {
            $('#address-list').replaceWith($(response));
            hideAddressLoader();
        });

        return false;
    })

    /**
     * Set address as contact default
     */
    .on('click', '#address-list .make-default', function() {
        showAddressLoader();

        var contactId = $('#contact').data('id');
        var address = $(this).parents('.contact-address');

        $.get(address.data('make-default-url'), response => {
            $('#address-list').replaceWith($(response));
            hideAddressLoader();
        });
    })

    /**
     * Remove a note from the list
     */
    .on('click', '.remove-note', function() {
        $(this).parents('.contact-note').remove();
    })

;

var showContactLoader = function() {
    $('#contact-loader').addClass('active');
}

var hideContactLoader = function() {
    $('#contact-loader').removeClass('active');
}

var showGroupLoader = function() {
    $('#group-loader').addClass('active');
}

var hideGroupLoader = function() {
    $('#group-loader').removeClass('active');
}

var showPhoneLoader = function() {
    $('#phone-list-loader').addClass('active');
}

var hidePhoneLoader = function() {
    $('#phone-list-loader').removeClass('active');
}

var showAddressLoader = function() {
    $('#address-list-loader').addClass('active');
}

var hideAddressLoader = function() {
    $('#address-list-loader').removeClass('active');
}

var initDropdowns = function() {
    $('.ui.dropdown').dropdown();
}

var resetForm = function(form) {
    form.find('input:text, input:password, input:file, input[type="tel"], select, textarea').val('');
    form.find('input:radio, input:checkbox, option').removeAttr('checked').removeAttr('selected');
    form.find('.ui.dropdown').dropdown('clear');
    form.find('div.red.pointing.label').remove();
    form.find('.field').removeClass('error');
}
