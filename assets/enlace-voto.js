/* kfp-votoabrios/assets/enlace-voto.js */

jQuery(document).ready(function ($) {
    $('body').on('click', '.voto', function (event) {
        event.preventDefault();
        var $enlace = $(this);
        var $contenedor = $enlace.parents('span.enlace');
        $.post(ajax_object.ajax_url,
            {
                action: 'votoabrios_graba_voto',
                nonce: ajax_object.ajax_nonce,
                obra_id: $enlace.data('obra-id')
            },
            function (response) {
                $contenedor.html(response);
            });
        return false;
    });
});