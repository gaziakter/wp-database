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

function wp_database_load_textdomain() {
	load_plugin_textdomain( 'wp-database', false, dirname( __FILE__ ) . "/languages" );
}

add_action( "plugins_loaded", "wp_database_load_textdomain" );


function plugin_init_on_activation(){
	global $wpdb;

	/** Create table and column */
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

	/**  Update version */
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

/** Table drop */
function wp_database_drop_column() {
	global $wpdb;
	$table_name = $wpdb->prefix . 'persons';
	if ( get_option( "wp_database_version" ) != WP_DATABASE_VERSION ) {
		$query = "ALTER TABLE {$table_name} DROP COLUMN age";
		$wpdb->query( $query );
	}
	update_option( "wp_database_version", WP_DATABASE_VERSION );
}

add_action( "plugins_loaded", "wp_database_drop_column" );

/** Inset data on activation the plugin */
function wp_database_load_data() {
	global $wpdb;
	$table_name = $wpdb->prefix . 'persons';
	$wpdb->insert( $table_name, [
		'name'  => 'John Doe',
		'email' => 'john@doe.com'
	] );
	$wpdb->insert( $table_name, [
		'name'  => 'Jane Doe',
		'email' => 'jane@doe.com'
	] );

}

register_activation_hook( __FILE__, "wp_database_load_data" );

/** Flush data on deavtivation plugin */
function wp_database_flush_data() {
	global $wpdb;
	$table_name = $wpdb->prefix . 'persons';
	$query      = "TRUNCATE TABLE {$table_name}";
	$wpdb->query( $query );
}

register_deactivation_hook( __FILE__, "wp_database_flush_data" );

/** Create an menu on dashboard */
add_action( 'admin_menu', function () {
	add_menu_page( 'WP Database', 'WP Database', 'manage_options', 'wpdatabase', 'wp_database_admin_page' );
} );

/** get data from database */
function wp_database_admin_page(){
	global $wpdb;
	echo '<h2>DB Demo</h2>';
	$id = $_GET['pid'] ?? 0;
	$id = sanitize_key( $id );
	if ( $id ) {
		$result = $wpdb->get_row( "select * from {$wpdb->prefix}persons WHERE id='{$id}'" );
		if($result){
			echo "Name: {$result->name}<br/>";
			echo "Email: {$result->email}<br/>";
		}
	}
}