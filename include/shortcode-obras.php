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
	// phpcs:ignore WordPress.DB.DirectDatabaseQuery
	$obras = $wpdb->get_results(
		"SELECT * FROM {$wpdb->prefix}votoabrios_obra"
	);
	$html  = '<div id="vista-previa-galeria">';
	foreach ( $obras as $obra ) {
		$html .= '<div><a class="miniatura-galeria" data-fancybox="gallery" ';
		$html .= 'href="#"><img src="/wp-content/uploads/artesplasticas1920/';
		$html .= 'XXVIceapis' . $obra->id . '.jpg"></a><br>';
		$html .= '<span class="enlace"><a href="#" class="voto"';
		$html .= 'data-obra-id="' . $obra->id . '">Votar obra ' . $obra->id . '</a></span></div>';
	}
	$html .= '</div>';
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

	wp_register_script(
		'kfp-votoabrios-lightbox',
		KFP_GALERIA_PLUGIN_URL . '../assets/jquery.fancybox.min.js',
		array( 'jquery' ),
		KFP_GALERIA_VERSION,
		true
	);
	wp_enqueue_script( 'kfp-votoabrios-lightbox' );
	wp_register_style(
		'kfp-votoabrios-lightbox-css',
		KFP_GALERIA_PLUGIN_URL . '../assets/css/jquery.fancybox.min.css',
		null,
		KFP_GALERIA_VERSION
	);
	wp_enqueue_style( 'kfp-votoabrios-lightbox-css' );
	wp_register_style(
		'kfp-votoabrios-frontend-css',
		KFP_GALERIA_PLUGIN_URL . '../assets/css/frontend.css',
		null,
		KFP_GALERIA_VERSION
	);
	wp_enqueue_style( 'kfp-votoabrios-frontend-css' );
}
