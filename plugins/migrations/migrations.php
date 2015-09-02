<?php
/*
Plugin Name: Migrations
Description: Migrate WordPress databases.
Author: Hibam Iru Dionisio Alor
Version: 1.0
Author URI: http://iru.me
Network: True
*/

$GLOBALS['mdb_meta']['migrations']['version'] = '1.5';
$GLOBALS['mdb_meta']['migrations']['folder'] = basename( plugin_dir_path( __FILE__ ) );

// Define the directory seperator if it isn't already
if( !defined( 'DS' ) ) {
	if (strtoupper(substr(PHP_OS, 0, 3)) == 'WIN') {
		define('DS', '\\');
	}
	else {
		define('DS', '/');
	}
}

function migrations_loaded() {
	// if neither WordPress admin nor running from wp-cli, exit quickly to prevent performance impact
	if ( !is_admin() && ! ( defined( 'WP_CLI' ) && WP_CLI ) ) return;

	require_once 'class/mdb-base.php';
	require_once 'class/mdb-addon.php';
	require_once 'class/mdb.php';

	global $mdb;
	$mdb = new MDB( __FILE__ );
}

add_action( 'plugins_loaded', 'migrations_loaded' );

function migrations_init() {
	// if neither WordPress admin nor running from wp-cli, exit quickly to prevent performance impact
	if ( !is_admin() && ! ( defined( 'WP_CLI' ) && WP_CLI ) ) return;

	load_plugin_textdomain( 'migrations', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
}

add_action( 'init', 'migrations_init' );
