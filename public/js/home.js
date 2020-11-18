$(function() {

    "use strict";

    let tab = [];
    $.ajax({
        url: '/article/favoris',
        method: 'GET',
        async: true,
        success: function(response) {
            tab = response.ids;
        }
    });

    $('.bloc-article .add-favorite').each(function(k, v) {
        var id = $(v).attr('article-id');
        if(tab.includes(id)) {
            $(this).removeClass('far');
            $(this).addClass('fas');
        }
    });

    /**
     * Permet d'ajouter un article en favori
     */
    $(document).on('click', '.add-favorite', function() {
        let article_id = $(this).attr('article-id');
        $.ajax({
            url: '/article/favoris/new',
            data: {'articleId': article_id},
            async: true,
            method: "POST",
            success: function(response) {
                console.log(response);
                $(this).toggleClass('far');
                $(this).toggleClass('fas');
            },
            error: function(error) {
                console.log(error);
            }
        });
    });


})