<?php
/**
 * File: kfp-votoabrios/include/admin-menu.php
 *
 * @package kfp_votoabrios
 */

defined( 'ABSPATH' ) || die();

add_action( 'admin_menu', 'kfp_certamen_crea_menu_admin' );
/**
 * Crea el menú de administración del plugin en el escritorio de WordPress
 *
 * @return void
 */
function kfp_certamen_crea_menu_admin() {
	add_menu_page(
		'Certamen',
		'Certamen',
		'manage_options',
		'kfp_certamen_admin',
		'',
		'dashicons-megaphone',
		75
	);
	remove_submenu_page(
		'kfp_certamen_admin',
		'kfp_certamen_admin'
	);
	add_submenu_page(
		'kfp_certamen_admin',
		'Obras Certamen',
		'Obras',
		'manage_options',
		'kfp_certamen_obras',
		'kfp_certamen_crea_panel_obras'
	);
	add_submenu_page(
		'kfp_certamen_admin',
		'Votos Certamen',
		'Votos',
		'manage_options',
		'kfp_certamen_votos',
		'kfp_certamen_crea_panel_votos'
	);
}

function kfp_certamen_crea_panel_obras() {
	echo 'Obras';
}

function kfp_certamen_crea_panel_votos() {
	global $wpdb;
	$votos = $wpdb->get_results(
		'SELECT obra.id as obra, count(voto.id) as votos
		FROM wp_votoabrios_obra obra 
		INNER JOIN wp_votoabrios_voto voto ON obra.id = voto.obra_id
		GROUP BY obra.id;',
		OBJECT
	);

	$html  = '<div class="wrap"><h1>Registro de votos</h1>';
	$html .= '<div class="dashicons-before dashicons-admin-page"><a href="' . admin_url( 'admin.php?page=kfp_certamen_votos' );
	$html .= '&accion=kfp_votoabrios_descarga_csv&_wpnonce=';
	$html .= wp_create_nonce( 'kfp_votoabrios_descarga_csv' ) . '">Descargar fichero CSV</a></div><br>';
	$html .= '<table class="wp-list-table widefat fixed striped">';
	$html .= '<thead><tr><th>Obra</th><th>Autor</th><th>Votos</th>';
	$html .= '<th></th></tr></thead>';
	$html .= '<tbody id="the-list">';
	foreach ( $votos as $voto ) {
		$obra  = (int) $voto->obra;
		$autor = esc_textarea( '-' );
		$votos = (int) $voto->votos;
		$html .= "<tr><td>$obra</td><td>$autor</td><td>$votos</td>";
		$html .= '</tr>';
	}
	$html .= '</tbody></table></div>';
	$html .= '<br><div class="dashicons-before dashicons-admin-page"><a href="' . admin_url( 'admin.php?page=kpf_votoabrios_menu' );
	$html .= '&accion=kfp_votoabrios_descarga_csv&_wpnonce=';
	$html .= wp_create_nonce( 'kfp_votoabrios_descarga_csv' ) . '">Descargar fichero CSV</a></div>';
	echo $html;
}
