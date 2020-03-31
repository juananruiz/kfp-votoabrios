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
	wp_enqueue_script( 'kfp-votoabrios-fancybox' );
	wp_enqueue_style( 'kfp-votoabrios-fancybox-css' );
	wp_enqueue_style( 'kfp-votoabrios-frontend' );
	// phpcs:ignore WordPress.DB.DirectDatabaseQuery
	$obras = $wpdb->get_results(
		"SELECT * FROM {$wpdb->prefix}votoabrios_obra"
	);
	$html  = '<div id="vista-previa-galeria">';
	foreach ( $obras as $obra ) {
		$html .= '<article class="obra"><figure class="miniatura">';
		$html .= '<a class="miniatura-galeria" data-fancybox="gallery" ';
		$html .= 'href="/wp-content/uploads/artesplasticas1920/obra_' . $obra->id;
		$html .= '_imagen.jpg"><img src="/wp-content/uploads/artesplasticas1920/';
		$html .= 'obra_' . $obra->id . '_imagen_th.jpg"></a><figure>';
		$html .= '<span class="enlace">' . $obra->autor . '</span><br>';
		$html .= '<span class="enlace">' . $obra->titulo . '</span><br>';
		$html .= '<span class="enlace"><a href="#" class="voto"';
		$html .= 'data-obra-id="' . $obra->id . '">Votar obra ' . $obra->id . '</a>';
		$html .= '</span></article>';
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
		KFP_VOTOABRIOS_URL . 'assets/enlace-voto.js',
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
		'kfp-votoabrios-fancybox',
		KFP_VOTOABRIOS_URL . 'assets/jquery.fancybox.min.js',
		array( 'jquery' ),
		KFP_VOTOABRIOS_VERSION,
		false
	);
	wp_register_style(
		'kfp-votoabrios-fancybox-css',
		KFP_VOTOABRIOS_URL . 'assets/jquery.fancybox.min.css',
		null,
		KFP_VOTOABRIOS_VERSION
	);
	wp_register_style(
		'kfp-votoabrios-frontend',
		KFP_VOTOABRIOS_URL . 'assets/frontend.css',
		null,
		KFP_VOTOABRIOS_VERSION
	);
}
