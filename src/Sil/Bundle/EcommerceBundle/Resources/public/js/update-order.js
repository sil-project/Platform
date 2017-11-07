$(document).ready(
    function() {
        $('#add-to-order').click(getProducts);
        $(document).on('submit', 'form[name^="bulkQuantityUpdate_"]', function(e) {
            e.preventDefault();
            updateBulkQuantity($(this));
        });

    }
);

var getProducts = function() {
    var data = $(this).data();

    //Retrieve product list
    $.get(
        data.url,
        function(html) {
            var modal = $('.li-modal.modal');
            html = $(html);

            //Turn list action buttons into variant list dropdowns
            html.find('td.sonata-ba-list-field-select a').each(
                function(key, button) {
                    button = $(button);

                    button
                        .addClass('dropdown-toggle')
                        .attr('href', '#')
                        .html(data.btnText)
                        .attr('data-toggle', 'dropdown')
                        .append($('<span>').addClass('caret'))
                        .click(
                            function() {
                                if ($(this).siblings('ul').find('.variant-list table').length < 1) {
                                    getVariants(data.orderId, $(this).parents('td').attr('objectid'), data.variantsUrl, data.addProductUrl);
                                }
                            }
                        );

                    button.parent().html(
                        $('<div>')
                        .addClass('dropdown')
                        .html(button)
                        .append(
                            $('<ul>')
                            .addClass('dropdown-menu')
                            .html(
                                $('<li>').addClass('variant-list')
                            )
                        )
                    );
                }
            );

            //Prepare and open product list modal
            modal.attr('id', 'order-edit-modal');
            modal.find('.modal-title').html(data.title);
            modal.find('.modal-body').html(html);
            modal.modal('show');
        }
    );
};

//Retrieve product variant list
var getVariants = function(orderId, productId, url, addProductUrl) {
    $.get(
        url + '?productId=' + productId,
        function(html) {
            html = $(html);

            html.find('td.sonata-ba-list-field-select a').each(
                function(key, button) {
                    button = $(button);

                    var variantId = button.parent().attr('objectid');

                    //Remove variants that are already in the order
                    if ($('#order-summary').find('[data-variant-id="' + variantId + '"]').length > 0) {
                        button.parents('tr').remove();
                    }

                    button.attr('href', '#').click(
                        function() {
                            addProduct(orderId, $(this).parent().attr('objectid'), addProductUrl);

                            return false;
                        }
                    );
                }
            );

            $('[objectid="' + productId + '"]')
                .find('.variant-list')
                .html(html.find('table'));
        }
    );
};

//Add product to order
var addProduct = function(orderId, variantId, url) {
    $.post(
        url, {
            orderId: orderId,
            variantId: variantId
        },
        function() {
            document.cookie = 'selectedTab=' + $('.nav-tabs li.active').data('tabName');
            window.location.reload();
        }
    );
};


var updateBulkQuantity = function(form) {

    var url = form.attr('action');

    $.post(
        url,
        JSON.parse(JSON.stringify($(form).serializeArray())),
        function(jsonResponse) {
            document.cookie = 'selectedTab=' + $('.nav-tabs li.active').data('tabName');
            if (typeof jsonResponse.message !== 'undefined')
                Admin.flashMessage.show(jsonResponse.message, 'error');
            else
                window.location.reload();
        }
    );
};
