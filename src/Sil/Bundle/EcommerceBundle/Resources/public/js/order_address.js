$(document).ready(function() {
    var useSameAddressForBilling = 'input[name$="[shippingAddress][useSameAddressForBilling]"]';
    var billingFormGroup = $('div[id^="sonata-ba-field-container-"][id$="_billingAddress"]');

    $(useSameAddressForBilling).prop("checked") ? billingFormGroup.hide() : billingFormGroup.show();

    $(document).on('ifChanged', useSameAddressForBilling, function(event) {
        var checked = $(this).prop("checked");

        checked ? billingFormGroup.hide() : billingFormGroup.show();
    });
});
