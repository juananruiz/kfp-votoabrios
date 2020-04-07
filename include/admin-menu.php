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
	remove_submenu_page(
		'kfp_certamen_admin',
		'kfp_certamen_admin'
	);
}

/**
 * Undocumented function
 *
 * @return void
 */
function kfp_certamen_crea_panel_principal() {
	echo '<h2>Aquí aparecerá el panel principal de los certámenes</h2>';
}

/**
 * Undocumented function
 *
 * @return void
 */
function kfp_certamen_crea_panel_obras() {
	global $wpdb;
	// Prepara los scripts para que los datos se actualicen mediante ajax.
	wp_enqueue_script(
		'kfp-certamen-admin-actualiza-obra',
		KFP_VOTOABRIOS_URL . 'assets/admin-actualiza-obra.js',
		array( 'jquery' ),
		KFP_VOTOABRIOS_VERSION,
		true
	);
	wp_localize_script(
		'kfp-certamen-admin-actualiza-obra',
		'ajax_object',
		array(
			'ajax_url'   => admin_url( 'admin-ajax.php' ),
			'ajax_nonce' => wp_create_nonce( 'ajax_update_' . admin_url( 'admin-ajax.php' ) ),
		)
	);
	$obras = $wpdb->get_results( 'SELECT * FROM wp_votoabrios_obra;', OBJECT );

	$html  = '<div class="wrap"><h1>Lista de obras</h1>';
	$html .= '<table class="wp-list-table widefat fixed striped">';
	$html .= '<thead><tr><th width="2%">ID</th><th width="1%">P</th><th>Autor</th>';
	$html .= '<th>Título</th><th>Dimensiones</th><th>Técnica</th>';
	$html .= '</tr></thead>';
	$html .= '<tbody id="the-list">';
	foreach ( $obras as $obra ) {
		$obra_id     = (int) $obra->id;
		$es_publica  = (int) $obra->es_publica;
		$autor       = esc_textarea( $obra->autor );
		$titulo      = esc_textarea( $obra->titulo );
		$dimensiones = esc_textarea( $obra->dimensiones );
		$tecnica     = esc_textarea( $obra->tecnica );

		$html .= "<tr data-obra_id='$obra->id'><td>$obra_id</td>";
		$html .= "<td><input type='text' class='ajax-edit small-text' name='es_publica' value='$es_publica'></td>";
		$html .= "<td><input type='text' class='ajax-edit regular-text' name='autor' value='$autor'></td>";
		$html .= "<td><input type='text' class='ajax-edit regular-text' name='titulo' value='$titulo'></td>";
		$html .= "<td><input type='text' class='ajax-edit regular-text' name='dimensiones' value='$dimensiones'></td>";
		$html .= "<td><input type='text' class='ajax-edit regular-text' name='tecnica' value='$tecnica'></td>";
		$html .= '</tr>';
	}
	$html .= '</tbody></table></div>';
	echo $html;
}

add_action( 'wp_ajax_kfp_certamen_actualiza_obra', 'kfp_certamen_actualiza_obra' );
/**
 * Actualiza mediante AJAX el panel de administración de obras
 *
 * @return void
 */
function kfp_certamen_actualiza_obra() {
	global $wpdb;
	if (
		defined( 'DOING_AJAX' ) && DOING_AJAX && isset( $_POST['nonce'] ) 
		&& isset( $_POST['obra_id'] ) && wp_verify_nonce( $_POST['nonce'], 
		'ajax_update_' . admin_url( 'admin-ajax.php' ) )
		) {
		$obra_id = (int)$_POST['obra_id'];
		$campo   = sanitize_text_field( $_POST['campo'] );
		$valor   = sanitize_text_field( $_POST['valor'] );
		
		$tabla_obra = $wpdb->prefix . 'votoabrios_obra';
		$resultado = $wpdb->update(
			$tabla_obra,
			[$campo => $valor],
			['id' => $obra_id]
		);
		echo 'Ok';
		die();
	} else {
		die('Error');
	}
}

/**
 * Crea el panel de administración que muestra los votos recogidos
 * Tiene un enlace para descargar los datos en CSV
 *
 * @return void
 */
function kfp_certamen_crea_panel_votos() {
	global $wpdb;
	$votos = $wpdb->get_results(
		'SELECT obra.id as obra_id, obra.autor as autor, obra.titulo as titulo, count(voto.id) as votos
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
	$html .= '<thead><tr><th>ID Obra</th><th>Autor</th><th>Título</th><th>Votos</th>';
	$html .= '<th></th></tr></thead>';
	$html .= '<tbody id="the-list">';
	foreach ( $votos as $voto ) {
		$obra  = (int) $voto->obra_id;
		$autor = esc_textarea( $voto->autor );
		$titulo = esc_textarea( $voto->titulo );
		$votos = (int) $voto->votos;
		$html .= "<tr><td>$obra</td><td>$autor</td><td>$titulo</td><td>$votos</td>";
		$html .= '</tr>';
	}
	$html .= '</tbody></table></div>';
	$html .= '<br><div class="dashicons-before dashicons-admin-page"><a href="' . admin_url( 'admin.php?page=kfp_certamen_votos' );
	$html .= '&accion=kfp_votoabrios_descarga_csv&_wpnonce=';
	$html .= wp_create_nonce( 'kfp_votoabrios_descarga_csv' ) . '">Descargar fichero CSV</a></div><br>';
	echo $html;
}
