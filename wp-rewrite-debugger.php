<?php
declare( strict_types=1 );
/*
Plugin Name: WP rewrite debugger
Plugin URI: https:///joerivanveen.com
Description: Debug rewrite issues by logging before and after situations
Author: Joeri van Veen
Author URI: https://joerivanveen.com
Version: 0.0.1
*/
defined( 'ABSPATH' ) || die();
// This is plugin nr. 13 by Ruige hond. It identifies as: ruigehond013.
define( 'RUIGEHOND013_VERSION', '0.0.1' );
// Startup the plugin
add_action( 'parse_request', 'ruigehond013' );
//
function ruigehond013() {
	if ( 0 === strpos( ( $uri = $_SERVER['REQUEST_URI'] ), '/portfolio/' ) ) {
		register_shutdown_function( static function () use ( $uri ) {
			global $wp_query;
			$file = __DIR__ . '/' . date( 'Y-m-d' ) . '-wp-rewrite-debugger.log';
			// find out if a 404 was sent, because we need to log stuff then
			if ( $wp_query->is_404() || ! file_exists($file) ) {
				ruigehond013_log( $uri, $file);
				ruigehond013_log( var_export( get_option( 'rewrite_rules' ), true ), $file);
				ruigehond013_log( var_export( $wp_query, true ), $file );
			}
		} );
	}
	//if($_SERVER['REQUEST_URI'])
}

function ruigehond013_log( $str, $file ) {
	$str  = date( 'H:i:s' ) . ' ' . $str . PHP_EOL . '------------------------' . PHP_EOL;
	file_put_contents( $file, $str, FILE_APPEND );
}
