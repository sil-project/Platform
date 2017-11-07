$(document).ready(
    function() {
        $('.add-to-cart, .remove-from-cart').click(
            function() {
                var elem = $(this);
                var data = elem.data();

                $.post(
                    data.url,
                    {
                        order: data.orderId,
                        item: data.itemId
                    },
                    function(response) {
                        if(response.lastItem) {
                            Admin.flashMessage.show('error', response.message);
                        }

                        if(response.remove) {
                            elem.parents('tr').remove();
                        }

                        var parent = elem.parent();

                        if(!response.remove && !response.lastItem) {
                            $.each(
                                response.item, function(key, value) {
                                    parent.siblings('.' + key).html(value);
                                }
                            );
                        }

                        $.each(
                            response.order, function(key, value) {
                                var th = $('#' + key);
                                var label = th.find('strong');

                                th.html(label).append(': ' + value);
                            }
                        );

                        $.each(
                            response.payments, function(key, value) {
                                var span = $('#' + key);
                                span.html(value);
                            }
                        );
                    }
                );
            }
        );
    }
);
