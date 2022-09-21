<?php
/*
Plugin Name: Timagazine Core
Plugin URI: https://themetim.com/
Description: Timagazine core contains all the functionality of timagazine theme.
Author: themetim
Author URI: https://themetim.com
Version: 1.0.0
*/

if ( ! defined( 'ABSPATH' ) ) exit;
define( 'TIMAGAZINE_VERSION', '1.0.0' );
define( 'TIMAGAZINE_PLUG_DIR', dirname(__FILE__).'/' );
define('TIMAGAZINE_PLUG_URL', plugin_dir_url(__FILE__));

function timagazine_framework_init_check() {
    require_once TIMAGAZINE_PLUG_DIR .'includes/index.php';
    require_once TIMAGAZINE_PLUG_DIR .'vendor/index.php';
}
add_action( 'plugins_loaded', 'timagazine_framework_init_check' );

?>