
/** kfp-votoabrios/assets/admin-actualiza-obra.js */
jQuery(document).ready(function($){
	$('.ajax-edit').blur(function(event){
		$.post(ajax_object.ajax_url, 
			{
				action:'kfp_certamen_actualiza_obra', 
				obra_id:$(this).parents('tr').data('obra_id'),
				campo:$(this).attr('name'),
				valor:$(this).val(),
				nonce:ajax_object.ajax_nonce
			}, 
			function(response) {
				return true;
			});
		return false;
	});
});