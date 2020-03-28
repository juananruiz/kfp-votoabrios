<?php
/**
 * File: kfp-votoabrios/include/graba-voto.php
 *
 * @package kfp_votoabrios
 */

defined( 'ABSPATH' ) || die();

// Prepara los hooks para grabar con ajax.
add_action( 'wp_ajax_votoabrios_graba_voto', 'kfp_votoabrios_graba_voto' );
add_action( 'wp_ajax_nopriv_votoabrios_graba_voto', 'kfp_votoabrios_graba_voto' );
/**
 * Crear un nuevo voto para la idea y devuelve el número de votos actual
 *
 * @return void
 */
function kfp_votoabrios_graba_voto() {
	global $wpdb;
	if ( defined( 'DOING_AJAX' ) && DOING_AJAX && isset( $_POST['nonce'] )
		&& wp_verify_nonce(
			sanitize_text_field( wp_unslash( $_POST['nonce'] ) ),
			'enlace_voto_' . admin_url( 'admin-ajax.php' )
		)
		) {
		$obra_id    = (int) $_POST['obra_id'];
		$ip_usuario = isset( $_SERVER['REMOTE_ADDR'] )
			? sanitize_text_field( wp_unslash( $_SERVER['REMOTE_ADDR'] ) )
			: '0.0.0.0';
		$fecha_voto = date('Y-m-d H:i:s');
		// Graba el voto en la base de datos
		$tabla_voto = $wpdb->prefix . 'votoabrios_voto';
		$resultado = $wpdb->insert(
			$tabla_voto,
			array(
				'obra_id' => $obra_id,
				'ip' => $ip_usuario,
				'fecha_voto' => $fecha_voto,
			)
		);
		$html = 'Tiene mi voto';
		echo $html;
		die();
	} else {
		echo '-1';
		die( 'Error de seguridad' );
	}
}
