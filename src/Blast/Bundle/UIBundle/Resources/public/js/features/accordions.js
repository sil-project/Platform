$(document).ready(function() {
    $('.ui.accordion:not(.exclusive)').accordion({exclusive: false, duration: 200});
    $('.ui.accordion.exclusive').accordion({exclusive: true, duration: 200});
});
