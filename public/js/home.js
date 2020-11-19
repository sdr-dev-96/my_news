$(function() {

    "use strict";

    let tab = [];
    
    /**
     * Permet d'ajouter un article en favori
     */
    $(document).on('click', '.add-favori', function(element) {
        let article_id = $(this).attr('article-id');
        $.ajax({
            url: '/article/favoris/new',
            data: {'articleId': article_id},
            async: true,
            method: "POST",
            success: function(response) {
                $(element.target).removeClass('far')
                .removeClass('add-favori')
                .addClass('fas')
                .addClass('remove-favori');
            },
            error: function(error) {
                console.log(error);
            }
        });
    });


})