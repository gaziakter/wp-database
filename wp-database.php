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

 define( "WP_DATABASE_VERSION", "1.1" );

function tabledata_load_textdomain() {
	load_plugin_textdomain( 'wp-database', false, dirname( __FILE__ ) . "/languages" );
}

add_action( "plugins_loaded", "tabledata_load_textdomain" );


function plugin_init_on_activation(){
	global $wpdb;
	$table_name = $wpdb->prefix . 'persons';
	$sql        = "CREATE TABLE {$table_name} (
			id INT NOT NULL AUTO_INCREMENT,
			name VARCHAR(250),
			email VARCHAR(250),
			PRIMARY KEY (id)
	);";
	require_once( ABSPATH . "wp-admin/includes/upgrade.php" );
	dbDelta( $sql );

	add_option( "wp_database_version", WP_DATABASE_VERSION );

	if ( get_option( "wp_database_version" ) != WP_DATABASE_VERSION ) {
		$sql = "CREATE TABLE {$table_name} (
			id INT NOT NULL AUTO_INCREMENT,
			name VARCHAR(250),
			email VARCHAR(250),
			age INT,
			PRIMARY KEY (id)
		);";
		dbDelta( $sql );
		update_option( "wp_database_version", WP_DATABASE_VERSION );
	}

}
register_activation_hook( __FILE__, "plugin_init_on_activation" );
