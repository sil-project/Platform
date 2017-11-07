$(document).ready(function() {
    channelLinkedList(['select[name$="[shipment]"]', 'select[name$="[payment]"]'], 'select[name$="[channel]"]');
});

var channelLinkedList = function(lists, channelList) {
    $(document).on('change', channelList, function(e) {
        $(lists).each(function(i, item) {
            $(item).attr('disabled', 'disabled');
        });

        var formData = $('form.order-form').serializeArray();

        $.ajax({
            url: $('form.order-form').attr('action'),
            data: formData,
            method: $('form.order-form').attr('method'),
            success: function(html) {
                $(lists).each(function(i, item) {
                    $(item).parent().replaceWith($(html).find($(item).selector).parent());
                    Admin.shared_setup();
                    $(item).removeAttr('disabled');
                });
            }
        });
    });
};
