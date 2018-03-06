$(document).ready(function() {
    var filterSelect = $('.ui.filter.list');
    var filters = $('.ui.filters');
    var filterElements = filters.find('.ui.filter');

    var visibleFilters = 0;

    filterSelect.dropdown({
        action: 'combo',
        onAdd: function(value, text, $selectedItem) {
            visibleFilters++;

            if (visibleFilters == 1) {
                $('.ui.filters').transition('slide down in');
            }

            toggleFilter(value);
        },
        onRemove: function(value, text, $selectedItem) {
            visibleFilters--;

            if (visibleFilters == 0) {
                $('.ui.filters').transition('slide down out');
            }

            toggleFilter(value);
        }
    });

    function toggleFilter(value) {
        var elements;
        if (typeof value !== 'undefined') {
            elements = filterElements.filter('[data-name="' + value + '"]');
        }

        elements.each(function() {
            $(this).transition('slide down');
            $(this).prependTo(filters);
        });

        if (cal = elements.find('.ui.calendar')) {
            cal.calendar();
        }
    }

    $(document).on('click', '.ui.button.cancel', function() {
        filterSelect.dropdown('clear');
    });

    function initFilters() {
        var parameters = getUrlParameters();

        $(parameters).each(function() {
            if (this.value != '') {
                filterInput = filters.find('.ui.filter[data-name="' + this.name + '"] input');
                filterInput.val(this.value);

                if (filterInput.parent().is('.dropdown')) {
                    filterInput.parent().dropdown('set selected',this.value);
                }
                filterSelect.dropdown('set selected', this.name);
            }
        });
    }

    function getUrlParameters() {
        var url = decodeURIComponent(window.location.search.substring(1));
        var parameters = url.split('&');
        var out = [];

        for (var i = 0; i < parameters.length; i++) {
            var parameter = parameters[i].split('=');

            out.push({name: parameter[0], value: parameter[1]});
        }

        return out;
    }

    initFilters();
});
