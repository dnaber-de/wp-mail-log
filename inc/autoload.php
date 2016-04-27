<?php

/**
 * Class-Autoloader for MID-Blog-Mapping
 *
 */

namespace dna\WP_Mail_Log;

spl_autoload_register( __NAMESPACE__ . '\autoload' );

/**
 * requires a class by classname
 *
 * @param string $class
 * @return void
 */
function autoload( $class ) {

	# strip namespace base
	$class = str_replace( __NAMESPACE__, '', $class );

	# strip leading slash
	$class = ltrim( $class, '\\' );

	#namespace separator to directory separator
	$class = str_replace( '\\', '/', $class );

	# build the path
	$info = pathinfo( $class );

	$file_parts = array(
		__DIR__,
		$info[ 'dirname' ],
		$info[ 'basename' ] . '.php'
	);

	$file = implode( '/', $file_parts );

	if ( file_exists( $file ) )
		require_once $file;
}
