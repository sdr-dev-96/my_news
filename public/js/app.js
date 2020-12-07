$(function() {

    "use strict";

    /**
     * Permet d'ajouter un article en favori
     */
    $(document).on('click', '.add-favori', function(element) {
        let article_id = $(this).attr('article-id');
        $.ajax({
            url: '/favoris/new',
            data: {'articleId': article_id},
            async: true,
            method: "POST",
            success: function() {
                $(element.target)
                    .removeClass('far')
                    .removeClass('add-favori')
                    .addClass('fas')
                    .addClass('remove-favori');
            },
            error: function(error) {
                console.log(error);
            }
        });
    });

    /**
     * Permet de retirer un article des favoris
     */
    $(document).on('click', '.remove-favori', function() {
        let article_id  = $(this).attr('article-id');
        let data_target = $(this).attr('data-target');
        $.ajax({
            url: '/favoris/' + article_id + '/delete',
            method: 'DELETE',
            async: true,
            success: function(response) {
                $(data_target).html(response);
                $('.bloc-article span.remove-favori').each(function(k, v) {
                    $(v).attr('data-target', data_target);
                });
            },
        })
    });

    /**
     * Permet de changer l'état d'un commentaire
     */
    $(document).on('click', '.valid-commentaire', function(e) {
        e.preventDefault();
        let commentaire_id = $(this).attr('commentaire-id');
        $.ajax({
            url: '/commentaire/' + commentaire_id + '/valid',
            method: 'PUT',
            async: true,
            success: function(response) {
                alert(response.message);
                $(this).removeClass('btn-outline-success valid-commentaire');
                $(this).addClass('btn-outline-danger');
                $(this).html('<i class="fas fa-check"></i>');
            },
            error: function(response) {
                console.error(response.message);
            }
        })
    })


})