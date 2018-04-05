

$(document).ready(function() {

    window.setTimeout(function() {
        $(".alert-dismissible").fadeTo(800, 0).slideUp(800, function(){
            $(this).remove();
        });
    }, 10000);


    $(function () {
        $('[data-tooltip="true"]').tooltip()
    });

});


