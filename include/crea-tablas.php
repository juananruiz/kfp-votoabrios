<?php
/**
 * File: kfp-votoabrios/include/crea-tablas.php
 *
 * @package kfp_votoabrios
 */

defined( 'ABSPATH' ) || die();

register_activation_hook( KFP_VOTOABRIOS_PLUGIN_FILE, 'kfp_votoabrios_crea_tablas' );
/**
 * Cuando el plugin se active se crean las tablas si no existen.
 *
 * @return void
 */
function kfp_votoabrios_crea_tablas() {
	global $wpdb;
	$tabla_obra      = $wpdb->prefix . 'votoabrios_obra';
	$tabla_voto      = $wpdb->prefix . 'votoabrios_voto';
	$charset_collate = $wpdb->get_charset_collate();

	$query1 = "CREATE TABLE IF NOT EXISTS $tabla_obra (
		id int(11) NOT NULL AUTO_INCREMENT,
		autor varchar(250),
		descripcion text,
		UNIQUE (id)
		) $charset_collate;";
	$query2 = "CREATE TABLE IF NOT EXISTS $tabla_voto (
		id int(11) NOT NULL AUTO_INCREMENT,
		obra_id int(11) NOT NULL,
		ip varchar(15) NOT NULL,
		UNIQUE (id)
		) $charset_collate;";
	// La función dbDelta que nos permite crear tablas de manera segura se
	// define en el fichero upgrade.php que se incluye a continuación.
	include_once ABSPATH . 'wp-admin/includes/upgrade.php';
	dbDelta( $query1 );
	dbDelta( $query2 );
}
