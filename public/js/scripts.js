/*
 * Copyright (c) 2018.  MyArtSide 
 */

$(document).ready(function() {

    $('#left-menu').sidr({
        name: 'sidr-left',
        side: 'left'
    });
    $('#right-menu').sidr({
        name: 'sidr-right',
        side: 'right'
    });

    window.setTimeout(function() {
        $(".alert-dismissible").fadeTo(800, 0).slideUp(800, function(){
            $(this).remove();
        });
    }, 5000);

    $(function () {
        $('[data-toggle="tooltip"]').tooltip()
    });
});
