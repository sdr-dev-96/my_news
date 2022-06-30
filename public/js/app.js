$(function() {

    "use strict";

    /**
     * Permet d'ajouter un article en favori
     */
    $(document).on('click', '.add-favori', function(element) {
        let article_id = $(this).attr('article-id');
        $.ajax({
            url: '/ajax_new_favori',
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
            url: '/ajax_delete_favori/' + article_id,
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

    $(document).on('submit', '#form-commentaire', function() {
        let articleId = $(this).get('articleId');
        let texte = $(this).get('texte');
        let note = $(this).get('note');
        $.ajax({
            url: '/ajax_add_comment',
            method: 'POST',
            async: true,
            data: {'articleId': articleId, 'texte': texte, 'note': note},
            success: function(response) {
                $('#commentaire-form-response').html(response);
                $('#commentaire-form-response').show();
                setTimeout(function() {
                    $('#commentaire-form-response').hide();
                }, 3000);
            }
        })
    });    
})