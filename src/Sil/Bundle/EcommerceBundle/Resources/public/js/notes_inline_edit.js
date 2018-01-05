$('.notes-inline-edit').each(
    function(key, element) {
        var notes = $('.notes-inline-edit');
        var data = notes.data();

        notes.editable({
            value: $(element).parent().find('.notes-value').html(),
            pk: 1,
            type: 'textarea',
            url: data.url,
            mode: 'popup',
            savenochange: false,
            emptytext: '',
            display: false,
            params: {
                id: data.objectId,
                field: data.field
            },
            success: function(value) {
                if (value) {
                    $(notes).siblings('.notes-value').text(value);
                }
            }
        });
    }
);
