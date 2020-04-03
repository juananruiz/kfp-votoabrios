<?php
/**
 * File: kfp-votoabrios/include/descarga-registros.php
 *
 * @package kfp_votoabrios
 */

defined( 'ABSPATH' ) || die();

// Agrega el action hook solo si accion=kfp_votoabrios_descarga_csv.
if ( isset( $_GET['accion'] ) && $_GET['accion'] == 'kfp_votoabrios_descarga_csv' ) {
	add_action( 'admin_init', 'kfp_votoabrios_genera_csv' );
}

function kfp_votoabrios_genera_csv() {
	// Comprueba que el usuario actual tenga permisos suficientes.
	if( !current_user_can( 'manage_options' ) ){
		return false;
	}
	// Comprueba que estamos en el escritorio.
	if( !is_admin() ){
		return false;
	}
	// Comprueba Nonce.
	$nonce = isset( $_GET['_wpnonce'] ) ? $_GET['_wpnonce'] : '';
	if ( ! wp_verify_nonce( $nonce, 'kfp_votoabrios_descarga_csv' ) ) {
		die( 'Error de comprobación de seguridad' );
	}
	ob_start();
	$filename = 'kfp-votoabrios-' . date('YmdHi') . '.csv';

	$fila_titulos = array(
		'Obra',
		'Autor',
		'Título',
		'Votos',
	);
	$filas_datos = array();
	global $wpdb;
	$votos     = $wpdb->get_results(
		'SELECT obra.id as obra, obra.autor as autor, obra.titulo as titulo, count(voto.id) as votos
		FROM wp_votoabrios_obra obra 
		INNER JOIN wp_votoabrios_voto voto ON obra.id = voto.obra_id
		GROUP BY obra.id;',
		OBJECT
	);
	foreach ( $votos as $voto ) {
		$fila = array(
			$voto->obra, 
			$voto->autor,
			$voto->titulo,
			$voto->votos,
		);
		$filas_datos[] = $fila;
	}
	$handler = @fopen( 'php://output', 'w' );
	fprintf( $handler, chr(0xEF) . chr(0xBB) . chr(0xBF) );
	header( 'Cache-Control: must-revalidate, post-check=0, pre-check=0' );
	header( 'Content-Description: File Transfer' );
	header( 'Content-type: text/csv' );
	header( "Content-Disposition: attachment; filename={$filename}" );
	header( 'Expires: 0' );
	header( 'Pragma: public' );
	fputcsv( $handler, $fila_titulos );
	foreach ( $filas_datos as $fila ) {
		fputcsv( $handler, $fila );
	}
	fclose( $handler );
	ob_end_flush();
	die();
}
