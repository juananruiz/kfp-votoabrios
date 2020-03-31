<?php
/**
 * File: kfp-votoabrios/include/inserta-obras.php
 *
 * @package kfp_votoabrios
 */

defined( 'ABSPATH' ) || die();

register_activation_hook( KFP_VOTOABRIOS_PLUGIN_FILE, 'kfp_votoabrios_inserta_obras' );
/**
 * Cuando el plugin se active se insertan las obras si no existen.
 * Esta función es provisional mientras se implementa algo más versatil
 *
 * @return void
 */
function kfp_votoabrios_inserta_obras() {
	global $wpdb;
	$tabla_obra = $wpdb->prefix . 'votoabrios_obra';
	// De manera provisional se agregan 149 registros en blanco a obras.
	$registros = $wpdb->get_var( "SELECT count(id) FROM $tabla_obra;" );
	if ( 0 == $registros ) {
		for ( $i = 1; $i <= 149; $i++ ) {
			$wpdb->insert( $tabla_obra, [ 'id' => $i ] );
		}
	}
}
