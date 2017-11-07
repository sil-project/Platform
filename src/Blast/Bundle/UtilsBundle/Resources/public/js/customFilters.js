var customFilters = {
    saveForm: {
        submitForm: function(button) {
            var form = $(button).closest('form');
            var filterName = form.find('input[name="filterName"]').val();

            if (filterName && filterName != '') {
                form.submit();
            } else {
                alert(Translator.trans('You must define a filter name to save it'));
            }
        }
    },
    deleteForm: {
        submitForm: function(button) {
            var form = $(button).closest('form');
            var filterId = form.find('input[name="filterId"]').val();

            if (filterId && filterId != '') {
                var confirmFilterDeletion = confirm("Do you really want to delete this filter ?");
                if (confirmFilterDeletion == true) {
                    form.submit();
                }
            }
        }
    }
}
