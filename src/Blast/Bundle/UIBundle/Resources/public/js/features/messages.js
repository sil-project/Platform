$(document).ready(function() {
    $(document)
        .on('click', '.message .close', function() {
            $(this)
                .closest('.message')
                .transition({
                    animation: 'fade up',
                    duration: 200,
                    onComplete: function() {
                        $(this).remove();
                    }
                });
        })
        .ajaxStop(function() {
            $.fn.displayFlashMessages();
        });

    $.fn.displayFlashMessages();
});

/**
 * Display a flash message via javascript
 *
 * @param  {string} type
 * @param  {string} message
 */
$.fn.showFlashMessage = function(type, message) {
    let globalContainer = $('.ui.global-flash-container');
    let messageTemplate = globalContainer.data('template');

    messageTemplate = messageTemplate.replace('%%type%%', type);
    messageTemplate = messageTemplate.replace('%%message%%', message);
    messageTemplate = messageTemplate.replace('%%icon%%', message);

    let flashMessage = $(messageTemplate);

    flashMessage.find('.lightbulb.icon').removeClass('lightbulb').addClass($(flashMessage).data('icons')[type]);

    globalContainer.append(flashMessage);
    flashMessage.fadeInMessageWithDelay(200, 10000);
};

/**
 * displays all flash messages
 */
$.fn.displayFlashMessages = function() {
    let container = $('.ui.flash-container');
    let globalContainer = $('.ui.global-flash-container');
    let messages = container.find('.ui.message');

    messages.appendTo(globalContainer);
    messages.fadeInMessageWithDelay(200, 10000);
}

/**
 * Fades-in messages with a delay
 *
 * @param  {int} interval
 * @param  {int} removeDelay
 *
 * @return {jQuery}
 */
$.fn.fadeInMessageWithDelay = function(interval, removeDelay) {
    if (typeof interval === "undefined") {
        interval = 100;
    }
    var delay = 0;

    if (typeof removeDelay === "undefined") {
        removeDelay = 5000;
    }

    this.each(function() {
        $(this).css({ opacity: 0 });
    });

    return this.each(function() {
        var that = $(this);
        that
            .delay(delay)
            .css({ visibility: 'visible' })
            .animate({ opacity: 1 }, interval, function() {
                if (removeDelay != -1) {
                    setTimeout(function() {
                        that.animate({ opacity: 0 }, interval * 5, function() {
                            that.remove();
                        });
                    }, removeDelay);
                }
            });
        delay += interval;
    });
};
