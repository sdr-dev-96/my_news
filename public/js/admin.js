$(function() {

    "use strict";
    
    setTimeout(function() {
        //$(".alert").alert('close');
    }, 2000);

    /**
     * Permet de valider un commentaire
     */
    $(document).on('click', '.valid-commentaire', function(e) {
        e.preventDefault();
        let commentaire_id  = $(this).attr('commentaire-id');
        let commentaire     = $(this);
        $.ajax({
            url: '/commentaire/' + commentaire_id + '/valid/' + 1,
            method: 'PUT',
            async: true,
            success: function(response) {
                commentaire.removeClass('btn-outline-success valid-commentaire');
                commentaire.addClass('btn-outline-danger refuse-commentaire');
                commentaire.html('<i class="fas fa-times"></i>');
                $('#commentaire_' + commentaire_id + ' .valid-comm').html('Oui');
            },
            error: function(response) {
                console.error(response.message);
            }
        })
    });

    /**
     * Permet de refuser un commentaire
     */
    $(document).on('click', '.refuse-commentaire', function(e) {
        e.preventDefault();
        let commentaire_id  = $(this).attr('commentaire-id');
        let commentaire     = $(this);
        $.ajax({
            url: '/commentaire/' + commentaire_id + '/valid/' + 0,
            method: 'PUT',
            async: true,
            success: function(response) {
                commentaire.removeClass('btn-outline-danger refuse-commentaire');
                commentaire.addClass('btn-outline-success valid-commentaire');
                commentaire.html('<i class="fas fa-check"></i>');
                $('#commentaire_' + commentaire_id + ' .valid-comm').html('Non');
            },
            error: function(response) {
                console.error(response.message);
            }
        })
    });
    
})