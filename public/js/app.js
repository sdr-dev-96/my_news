$(function() {

    "use strict";

    /**
     * Permet de retirer un article des favoris
     */
    $(document).on('click', '.remove-favori', function() {
        let article_id = $(this).attr('article-id');
        $.ajax({
            url: '/favoris/' + article_id + '/delete',
            method: 'DELETE',
            async: true,
            success: function(response) {
                $('#mes-favoris .content').html(response);
            },
        })
    });

    /**
     * Permet de retirer un article des favoris
     */
    $(document).on('click', '.remove-favori', function() {
        let article_id = $(this).attr('article-id');
        $.ajax({
            url: '/favoris/' + article_id + '/delete',
            method: 'DELETE',
            async: true,
            success: function(response) {
                $('#mes-favoris .content').html(response);
            },
        })
    });


})