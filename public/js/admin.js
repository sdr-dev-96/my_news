$(function() {

    "use strict";

    $(document).on('click', '#admin-dashboard .nav-link', function() {
        $('#admin-dashboard .nav-link').each(function(k, v) {
            $(v).removeClass('active');
        });
        $(this).addClass('active');
    });


})