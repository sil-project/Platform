$(document).ready(function() {
    $(document)
        .on('click', '.step-nav-container .ui.button.next.step:not(.disabled)', function() {
            moveStep();
        })
        .on('click', '.step-nav-container .ui.button.prev.step:not(.disabled)', function() {
            moveStep(true);
        });

    function moveStep(prev) {
        var steps = $('.ui.steps .step');
        var currentStep = steps.filter('.active');
        var stepContents = $('.step.content .step');
        var currentStepContent = stepContents.filter('[data-step="' + currentStep.attr('data-name') + '"]');

        var nextButton = $('.ui.button.next.step');
        var prevButton = $('.ui.button.prev.step');

        nextButton.removeClass('disabled');
        prevButton.removeClass('disabled');

        currentStep.removeClass('active');

        currentStepContent.transition({
            'animation': 'slide ' + (prev ? 'left' : 'right'),
            'duration': 200,
            onComplete: function() {
                currentStepContent.removeClass('active');
                if (prev == true) {
                    currentStep = $(steps.get(currentStep.index() - 1));
                } else {
                    currentStep = $(steps.get(currentStep.index() + 1));
                }
                currentStepContent = stepContents.filter('[data-step="' + currentStep.attr('data-name') + '"]');

                currentStepContent.transition({
                    'animation': 'slide ' + (prev ? 'right' : 'left'),
                    'duration': 200
                }).addClass('active');
                currentStep.addClass('active');

                if (currentStep.is(':first-child')) {
                    prevButton.addClass('disabled');
                }

                if (currentStep.is(':last-child')) {
                    nextButton.addClass('disabled');
                }
            }
        });
    }
});
