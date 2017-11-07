
var inherit = function(){

    $('*[inherit="source"]').change(function(){

        $.get('/admin/libio/variety/hierarchy/' + $(this).val(), function(data){

            $('*[inherit="target"]').each(function(key, input){
                var id = $(input).prop('id');
                var fieldName = id.substring(id.indexOf('_') + 1);

                $(input).val(data[fieldName]);

                if( $(input).prop('tagName') == 'SELECT' )
                    $(input).trigger('change');
            });
        });
    });
};

$(document).ready(inherit);
$(document).on('sonata-admin-setup-list-modal sonata-admin-append-form-element', inherit);
