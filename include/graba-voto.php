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

// Agrega los action hooks para grabar el formulario (el primero para usuarios
// logeados y el otro para el resto)
// Lo que viene tras admin_post_ y admin_post_nopriv_ tiene que coincidir con
// el valor del campo input con nombre "action" del formulario enviado.
add_action( 'admin_post_kfp-votoabrios', 'kfp_graba_ticket' );
add_action( 'admin_post_nopriv_kfp-votoabrios', 'kfp_graba_ticket' );
/**
 * Graba los datos enviados por el formulario como un nuevo CPT kfp-taller
 *
 * @return void
 */
function kfp_graba_ticket() {
	global $wpdb;
	// Si viene en $_POST aprovecha uno de los campos que crea wp_nonce para volver al form.
	$url_origen = home_url( '/' );
	if ( ! empty( $_POST['_wp_http_referer'] ) ) {
		$url_origen = esc_url_raw( wp_unslash( $_POST['_wp_http_referer'] ) );
	}
	// Define condicion de error a priori y si la cosa sale bien cambia a 'success'
	$query_arg = array( 'kfp-votoabrios-resultado' => 'error' );
	// Comprueba campos requeridos y nonce.
	if ( isset( $_POST['asunto'] )
	&& isset( $_POST['descripcion'] )
	&& isset( $_POST['categoria_id'] )
	&& isset( $_POST['kfp-votoabrios-nonce'] )
	&& wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['kfp-votoabrios-nonce'] ) ), 'kfp-votoabrios' )
	) {
		$asunto          = sanitize_text_field( wp_unslash( $_POST['asunto'] ) );
		$descripcion       = sanitize_text_field( wp_unslash( $_POST['descripcion'] ) );
		$categoria_id = (int) $_POST['categoria_id'];
		$created_at = date('Y-m-d H:i:s');
		$tabla_ticket = $wpdb->prefix . 'ticket';
		$resultado = $wpdb->insert(
			$tabla_ticket,
			array(
				'asunto' => $asunto,
				'descripcion' => $descripcion,
				'categoria_id' => $categoria_id,
				'created_at' => $created_at,
			)
		);
		if ( $resultado ) {
			$query_arg = array( 'kfp-votoabrios-resultado' => 'success' );
		}
	}
	wp_redirect( esc_url_raw( add_query_arg( $query_arg , $url_origen ) ) );
	exit();
}
