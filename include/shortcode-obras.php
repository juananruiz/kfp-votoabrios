<?php
/**
 * File: kfp-votoabrios/include/shortcode-obras.php
 *
 * @package kfp_votoabrios
 */

defined( 'ABSPATH' ) || die();

add_shortcode( 'kfp_votoabrios_obras', 'kfp_votoabrios_obras' );
/**
 * Muestra la galería de obras.
 *
 * @return string
 */
function kfp_votoabrios_obras( $atts = [], $content = null, $tag = '' ) {
	// normalize attribute keys, lowercase.
	$atts = array_change_key_case( (array) $atts, CASE_LOWER );
	// override default attributes with user attributes.
	$shortcode_atts = shortcode_atts(
		[
			'ver_todo' => 'false',
		],
		$atts,
		$tag
	);
	global $wpdb;
	wp_enqueue_script( 'kfp-votoabrios-enlace-voto' );
	wp_enqueue_script( 'kfp-votoabrios-fancybox' );
	wp_enqueue_style( 'kfp-votoabrios-fancybox-css' );
	wp_enqueue_style( 'kfp-votoabrios-frontend' );
	$query = "SELECT * FROM {$wpdb->prefix}votoabrios_obra";
	if ( 'false' === $shortcode_atts['ver_todo'] ) {
		$query .= ' WHERE es_publica = 1';
	}
	// phpcs:ignore WordPress.DB.DirectDatabaseQuery
	$obras = $wpdb->get_results( $query );
	$html  = '<div id="vista-previa-galeria">';
	foreach ( $obras as $obra ) {
		$html .= '<article class="obra"><figure class="miniatura">';
		$html .= '<a class="miniatura-galeria" data-fancybox="gallery" ';
		$html .= 'href="/wp-content/uploads/artesplasticas1920/obra_' . $obra->id;
		$html .= '_imagen.jpg" data-caption="' . $obra->autor . ' · ';
		$html .= $obra->titulo . ' · ' . $obra->dimensiones . ' · ' . $obra->tecnica;
		$html .= '"><img src="/wp-content/uploads/artesplasticas1920/';
		$html .= 'obra_' . $obra->id . '_imagen_th.jpg"></a><figure>';
		$html .= '<span class="enlace">' . $obra->autor . '</span><br>';
		$html .= '<span class="enlace"><strong>' . wp_trim_words( $obra->titulo, 8, '...' ) . '</strong></span><br>';
		$html .= '<span class="enlace">' . wp_trim_words( $obra->dimensiones, 8, '...' ) . '</span><br>';
		$html .= '<span class="enlace">' . wp_trim_words( $obra->tecnica, 8, '...' ) . '</span><br>';
		$html .= '<span class="enlace"><a href="#" class="voto"';
		$html .= 'data-obra-id="' . $obra->id . '">Votar obra ' . $obra->id . '</a>';
		$html .= '</span></article>';
	}
	$html .= '</div>';
	return $html;
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
