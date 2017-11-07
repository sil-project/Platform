$(document).ready(function () {

    templateSelect();
    checkIsTest();
    inline();
    addEmailLinkListener();

    //reset confirmexit plugin
    $('.sonata-ba-form form').confirmExit();
});

// Returns the current action (show/list/edit) from url
function getAction() {

    return window.location.href.split("/").pop();
}

//handles retrieving and insertion of template into main content
function templateSelect() {

    $('select.template_select').click(function () {

        getTemplate($(this).val());
    });
}

//retrieves template content and inserts it into tinymce editor
function getTemplate(templateId) {

    $.get(Routing.generate('librinfo_email.getTemplate',{'templateId': templateId}), function (data) {

        tinyMceInsert(data);
    });
}

//handles checking and disabling of isTest checkbox
function checkIsTest() {

    var action = getAction();
    var checkbox = $("input.is_test");

    checkbox.iCheck('check');

    if (action === 'create' || action === 'duplicate') {

        checkbox.iCheck('disable');
    }

}

//retrieves img tag generated from file and inserts it into tinymce editor
function inline() {

    $('.dropzone').on('click', '.inline', function (event) {

        var id = $(event.target)
            .closest('.file-row')
            .data('file-id')
        ;

        event.preventDefault();

        $.get(Routing.generate('librinfo_email.insert',{'fileId': id}), function (data) {

            tinyMceInsert(data);
        });
    });

    return false;
}

function tinyMceInsert(data) {

    tinymce.activeEditor.execCommand('mceInsertContent', false, data);
    tinymce.activeEditor.execCommand('mceEndUndoLevel', false, data);
}

var addEmailLinkListener = function(){

    $('.email-link').click(function(e){

        $('#send-email-form').submit();
    });
}
