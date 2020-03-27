<?php
/**
 * File: kfp-votoabrios/include/shortcode-obras.php
 *
 * @package kfp_votoabrios
 */

defined( 'ABSPATH' ) || die();

add_shortcode( 'kfp_votoabrios_obras', 'kfp_votoabrios_obras' );
/**
 * Implementa formulario para crear un nuevo taller.
 *
 * @return string
 */
function kfp_votoabrios_obras() {
	global $wpdb;
	$html = '';
	// phpcs:ignore WordPress.DB.DirectDatabaseQuery
	$obras = $wpdb->get_results(
		"SELECT * FROM {$wpdb->prefix}votoabrios_obra"
	);
	foreach ( $obras as $obra ) {
		$html .= '<div><img src="/wp-content/uploads/artesplasticas1920/';
		$html .= 'XXVIceapis' . $obra->id . '.jpg"><br>Obra ' . $obra->id . ' - ';
		$html .= '<a href="#" class="voto"';
		$html .= 'data-obra-id="' . $obra->id . '">Votar</a></div>';
	}
	echo $html;
	ob_start();
	if ( filter_input( INPUT_GET, 'kfp-votoabrios-resultado', FILTER_SANITIZE_STRING ) === 'success' ) {
		echo '<h4>Se ha grabado su solicitud correctamente</h4>';
	}
	if ( filter_input( INPUT_GET, 'kfp-votoabrios-resultado', FILTER_SANITIZE_STRING ) === 'error' ) {
		echo '<h4>Se ha producido un error al grabar su solicitud</h4>';
	}
	?>
	<form action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" method="post"
		id="form_solicitud">
		<?php wp_nonce_field( 'kfp-votoabrios', 'kfp-votoabrios-nonce' ); ?>
		<input type="hidden" name="action" value="kfp-votoabrios">
	</form>
	<?php
	return ob_get_clean();
}

add_action( 'wp_enqueue_scripts', 'kfp_votoabrios_voto_scripts' );
/**
 * Encola los scripts para permitir el voto
 *
 * @return void
 */
function kfp_votoabrios_voto_scripts() {
	wp_register_script(
		'kfp-votoabrios-enlace-voto',
		plugins_url( '../js/enlace-voto.js', __FILE__ ),
		array( 'jquery' ),
		KFP_VOTOABRIOS_VERSION,
		false
	);
	wp_localize_script(
		'kfp-votoabrios-enlace-voto',
		'ajax_object',
		array(
			'ajax_url'   => admin_url( 'admin-ajax.php' ),
			'ajax_nonce' => wp_create_nonce( 'enlace_voto_' . admin_url( 'admin-ajax.php' ) ),
		)
	);
}
