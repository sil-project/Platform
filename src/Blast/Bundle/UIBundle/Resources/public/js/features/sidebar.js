$(document).ready(function() {
    $('#sidebar').addClass('visible');
    $('#sidebar').sidebar('attach events', '#sidebar-toggle', 'toggle');
    $('#sidebar').sidebar('setting', {
        dimPage: false,
        closable: false
    });
});
