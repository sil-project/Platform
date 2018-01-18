
$(document).ready(function(){
    addUniqueFieldsListener();
});

var addUniqueFieldsListener = function(){
    $('.blast-unique-field')
        .focus(function(){
            $(this).closest('.form-group').removeClass('has-error');
            $(this).siblings('.help-block').remove();
        })
        .change(function(){

            var input = $(this);
            var value = input.val();
            var fieldName = input.data('field');

            if( value != '' )
                $.post(
                    input.data('url'),
                    {
                        'className' : input.data('class'),
                        'fieldName' : fieldName,
                        'fieldValue': value,
                        'adminCode' : input.data('admin-code'),
                        'returnLink': input.data('return-link')
                    },
                    function(response){
                        if( !response.available )
                        {
                            input.closest('.form-group').addClass('has-error');

                            var message =
                                '<i class="fa fa-exclamation-circle" aria-hidden="true"></i> ' +
                                value + ' ' +
                                response.error
                            ;

                            if(response.link != undefined)
                                message += '<p><a href="' + response.link + '">' + response.message + '</a></p>';

                            $('<div class="help-block sonata-ba-field-error-messages"></div>')
                                .html(message)
                                .appendTo(input.closest('.sonata-ba-field'))
                            ;
                        }
                    }
                );
        })
    ;
};
