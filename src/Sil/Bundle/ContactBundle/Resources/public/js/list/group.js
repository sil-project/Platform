
var cookieName = 'sil_contact_group_open_groups';

$(document)
    .ready(()=> {
        initAccordion();
    })

    /**
     * Toggle accordion
     */
    .on('click', '#toggle-accordion', () => {
        if($('#groups').hasClass('open')) {
            closeAccordion();
        } else {
            openAccordion();
        }
    })

    /**
     * Add group button
     */
    .on('click', '#add-group', function() {
        showGroupLoader();

        $.get($(this).data('url'), (response) => {
            $('#group-form').parent().replaceWith($(response));

            $('#group-modal').modal('show');

            initDropdowns();
            hideGroupLoader();
        });

        return false;
    })

    /**
     * Add child group buttons
     */
    .on('click', '#groups .add-child-group', function() {
        let id = $(this).parents('li.group').data('id');

        showGroupLoader();

        $.get($(this).data('url'), (response) => {
            $('#group-form').parent().replaceWith($(response));

            $('#group_parent')
                .val(id)
                .change()
            ;

            $('#group-modal').modal('show');

            initDropdowns();
            hideGroupLoader();
        });

        return false;
    })

    /**
     * Edit group buttons
     */
    .on('click', '#groups .edit-group', function() {
        showGroupLoader();

        var group = $(this).parents('li.group');

        $.get(group.data('edit-url'), response => {
            $('#group-form').parent().replaceWith($(response));
            $('#group-form').data('edit', true);
            $('#group-modal').modal('show');

            initDropdowns();
            hideGroupLoader();
        });

        return false;
    })

    /**
     * Remove group buttons
     */
    .on('click', '#groups .remove-group', function() {
        var group = $(this).parents('li.group');

        $('#confirm-modal').modal('show');

        $('#confirm-button').off('click').click(() => {
            showGroupLoader();

            $.get(group.data('remove-url'), response => {
                removeOpenGroup(group.data('id'));
                updateGroupList(response);

                hideGroupLoader();
            });
        });

        return false;
    })

    /**
     * Group form submission
     */
    .on('submit', '#group-form', function() {
        showGroupLoader();

        var url = $(this).data('edit') ?
            $(this).data('edit-action') :
            $(this).data('action')
        ;

        var data = new FormData($(this).get(0));

        $.ajax({
            url: url,
            method: 'POST',
            data: data,
            processData: false,
            contentType: false,
            success: response => {
                updateGroupList(response);
                hideGroupLoader();

                $('#group-modal').modal('hide');
            }
        });

        return false;
    })

    /**
     * Close Group form
     */
    .on('click', '#group-cancel', () => {
        $('#group-modal').modal('hide');
    })
;

var initAccordion = function() {
    var groups = $('#groups');
    var openGroups = getOpenGroups();

    $('#groups .group').each((key, group) => {
        $(group).data('index', key);
    });

    groups.accordion({
        exclusive: false,
        selector: {
            trigger: '.title .group-title'
        },
        onChanging: function() {
            $(this).siblings('.title').find('.children-count').toggle();
        },
        onOpen: function() {
            saveOpenGroup($(this).parent('.group').data('id'));
        },
        onClose: function() {
            removeOpenGroup($(this).parent('.group').data('id'));
        }
    });

    $.each(openGroups, (key, groupId) => {
        var index = $('.group[data-id="' + groupId + '"]').data('index');

        openAccordion(index);
    });

    // Check if all accordion sections are expanded
    if(openGroups.length == $('#groups .group .group-list').length) {
        groups.addClass('open');
    }
}

var openAccordion = function(index) {
    if(index !== undefined) {
        $('#groups').accordion('open', index);
    } else {
        $('#groups .group .title').each((key, group) => {
            $('#groups').accordion('open', key);
        });

        $('#groups').addClass('open');
    }
}

var closeAccordion = function(index) {
    if(index !== undefined) {
        $('#groups').accordion('close', index);
    } else {
        $('#groups .group .title').each((key, group) => {
            $('#groups').accordion('close', key);
        });

        $('#groups').removeClass('open');
    }
}

var showGroupLoader = function() {
    $('#group-loader').addClass('active');
}

var hideGroupLoader = function() {
    $('#group-loader').removeClass('active');
}

var initDropdowns = function() {
    $('.ui.dropdown').dropdown();
}

var updateGroupList = function(html) {
    $('#groups').replaceWith($(html).find('#groups'));
    initAccordion();
}

/**
 * Save opened accordion sections ids into a cookie to reopen them after content refresh
 */
var saveOpenGroup = function(groupId) {
    var openGroups = getOpenGroups();

    if(openGroups.indexOf(groupId) == -1 ) {
        openGroups.push(groupId);
    }

    Cookies.set(cookieName, openGroups);
}

var removeOpenGroup = function(groupId) {
    var openGroups = getOpenGroups();

    openGroups.splice(openGroups.indexOf(groupId), 1);

    Cookies.set(cookieName, openGroups);
}

var getOpenGroups = function() {
    var openGroups = Cookies.get(cookieName);

    if(openGroups) {
        openGroups = JSON.parse(openGroups);
    } else{
        openGroups = [];
    }

    return openGroups
}
