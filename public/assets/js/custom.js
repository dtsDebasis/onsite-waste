window.onload = function() {
    var formSubmitting = false;
    var $form = $('form.checkediting'),
    origForm = $form.serialize();
    console.log(origForm);

    $('form.checkediting :input, form select').on('change input', function() {
        if($form.serialize() !== origForm) {
            formSubmitting = true;
        }
    });
    window.addEventListener("beforeunload", function (e) {
        var clicked = e.target.activeElement;
        if ($(clicked).attr('type') == 'submit') {
            return undefined;
        }
        if (!formSubmitting) {
            return undefined;
        }

        var confirmationMessage = 'It looks like you have been editing something. '
                                + 'If you leave before saving, your changes will be lost.';

        (e || window.event).returnValue = confirmationMessage; //Gecko + IE
        return confirmationMessage; //Gecko + Webkit, Safari, Chrome etc.
    });
};