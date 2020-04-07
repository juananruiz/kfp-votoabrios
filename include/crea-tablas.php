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
	$tabla_certamen  = $wpdb->prefix . 'votoabrios_certamen';
	$tabla_obra      = $wpdb->prefix . 'votoabrios_obra';
	$tabla_voto      = $wpdb->prefix . 'votoabrios_voto';
	$charset_collate = $wpdb->get_charset_collate();

	$sql[] = "CREATE TABLE {$tabla_certamen} (
		id int(11) NOT NULL AUTO_INCREMENT,
		nombre varchar(250) NOT NULL,
		fecha_inicio datetime,
		fecha_fin datetime,
		UNIQUE (id)
		) $charset_collate;";
	$sql[] = "CREATE TABLE {$tabla_obra} (
		id int(11) NOT NULL AUTO_INCREMENT,
		certamen_id int(11),
		autor varchar(250),
		titulo text,
		dimensiones varchar(250),
		tecnica text,
		es_publica int(4) NOT NULL DEFAULT '1',
		UNIQUE (id)
		) $charset_collate;";
	$sql[] = "CREATE TABLE {$tabla_voto} (
		id int(11) NOT NULL AUTO_INCREMENT,
		obra_id int(11) NOT NULL,
		ip varchar(15) NOT NULL,
		fecha_voto datetime NOT NULL,
		UNIQUE (id)
		) $charset_collate;";
	// La función dbDelta que nos permite crear tablas de manera segura se
	// define en el fichero upgrade.php que se incluye a continuación.
	include_once ABSPATH . 'wp-admin/includes/upgrade.php';
	dbDelta( $sql );
}
