$(document)
    .on('submit', '.ui.modal[data-modal="add_attribute_to_product"] form[name="product_attribute_create"]', function(e) {
        e.preventDefault();

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
                    var newForm = $(html).find('form[name="product_attributes"]');

                    $('form[name="product_attributes"]').html(newForm.html());
                    form.removeClass('loading');

                    initForms();
                    form.closest('.ui.modal').modal('hide');
                },
                error: function() {

                }
            });
        }
    });

$(document).ready(function() {
    function initAttributeChooser() {
        $('.ui.modal[data-modal="add_attribute_to_product"] form[name="product_attribute_choose"] select[name="product_attribute_choose[attributeType]"]').dropdown('setting', 'onChange', function() {
            let form = $(this).closest('form');
            let formUrl = form.attr('action') + '?attributeType=' + $(this).val();

            $.ajax({
                url: formUrl,
                method: 'GET',
                processData: false,
                contentType: false,
                data: new FormData(form[0]),
                success: function(html) {
                    var newForm = $(html).find('form[name="product_attribute_choose"]');

                    form.parent().html(newForm);

                    initForms();
                    initAttributeChooser();
                },
                error: function() {

                }
            });
        });
    }
    initAttributeChooser();
});
