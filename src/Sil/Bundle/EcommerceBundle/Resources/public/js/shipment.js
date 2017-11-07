
$('[name="sylius_shipment_ship"]').submit(
    function(e) {
        $.post(
            $(this).prop('action'), $(this).serialize(), function() {
                document.cookie = 'selectedTab='+$('.nav-tabs li.active').data('tabName'); 
                window.location.reload();
            }
        );

        return false;
    }
);
