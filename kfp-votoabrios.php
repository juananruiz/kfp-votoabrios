<?php
/**
 * Plugin Name:  KFP VotoABrios
 * Plugin URI:   https://github.com/kungfupress/kfp-votoabrios
 * Description:  Votaciones sobre una colección de imágenes, de momento de voto único [kfp_votoabrios_obras]
 * Version:      0.2.0
 * Author:       KungFuPress
 * Author URI:   https://kungfupress.com/
 * PHP Version:  5.6
 *
 * @package  kfp_votoabrios
 */

defined( 'ABSPATH' ) || die();

// Constantes que afectan a todos los ficheros del plugin.
define( 'KFP_VOTOABRIOS_PLUGIN_FILE', __FILE__ );
define( 'KFP_VOTOABRIOS_DIR', plugin_dir_path( __FILE__ ) );
define( 'KFP_VOTOABRIOS_URL', plugin_dir_url( __FILE__ ) );
define( 'KFP_VOTOABRIOS_VERSION', '0.2.0' );

// Crea tablas si no existen.
require_once KFP_VOTOABRIOS_DIR . 'include/crea-tablas.php';
// Inserta obras si no existen.
require_once KFP_VOTOABRIOS_DIR . 'include/inserta-obras.php';
// Agrega shortcode [kfp_votoabrios] para mostrar lista de obras.
require_once KFP_VOTOABRIOS_DIR . 'include/shortcode-obras.php';
// Agrega función para que admin-post.php capture el envío de un nuevo taller desde un formulario.
require_once KFP_VOTOABRIOS_DIR . 'include/graba-voto.php';
// Panel con lista de registros en el escritorio.
require_once KFP_VOTOABRIOS_DIR . 'include/admin-menu.php';
// Módulo para descargar las registros existentes.
require_once KFP_VOTOABRIOS_DIR . 'include/descarga-registros.php';
