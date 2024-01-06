<?php
/**
 * Plugin Name:       WP Database
 * Plugin URI:        https://classysystem.com/plugin/wp-column/
 * Description:       WordPress database usages
 * Version:           1.0.0
 * Requires at least: 5.2
 * Requires PHP:      7.2
 * Author:            Gazi Akter
 * Author URI:        https://gaziakter.com/
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Update URI:        https://classysystem.com/
 * Text Domain:       wp-database
 * Domain Path:       /languages
 */

function tabledata_load_textdomain() {
	load_plugin_textdomain( 'tabledata_example', false, dirname( __FILE__ ) . "/languages" );
}

add_action( "plugins_loaded", "tabledata_load_textdomain" );