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
 * @return void
 */
function kfp_votoabrios_obras() {
	global $wpdb;
	wp_enqueue_script( 'kfp-votoabrios-enlace-voto' );
	$html = '';
	// phpcs:ignore WordPress.DB.DirectDatabaseQuery
	$obras = $wpdb->get_results(
		"SELECT * FROM {$wpdb->prefix}votoabrios_obra"
	);
	foreach ( $obras as $obra ) {
		$html .= '<div><img src="/wp-content/uploads/artesplasticas1920/';
		$html .= 'XXVIceapis' . $obra->id . '.jpg"><br>';
		$html .= '<span class="enlace"><a href="#" class="voto"';
		$html .= 'data-obra-id="' . $obra->id . '">Votar obra ' . $obra->id . '</a></span></div>';
	}
	echo $html;
}

add_action( 'wp_enqueue_scripts', 'kfp_votoabrios_voto_script' );
/**
 * Encola los scripts para permitir el voto
 *
 * @return void
 */
function kfp_votoabrios_voto_script() {
	wp_register_script(
		'kfp-votoabrios-enlace-voto',
		plugins_url( '../assets/enlace-voto.js', __FILE__ ),
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
