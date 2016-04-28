<?php

/**
 * Plugin Name: wp_mail() Log
 * Description: Logs wp_mail() calls as CPT.
 * Plugin URI:  https://github.com/dnaber-de/wp-mail-log
 * Version:     0.1.0
 * Author:      David Naber
 * Author URI:  http://dnaber.de/
 * License:     MIT
 * License URI: http://www.opensource.org/licenses/mit-license.php
 */
namespace dna\WP_Mail_Log;

if ( ! function_exists( 'add_filter' ) )
	return;

require_once( 'inc/autoload.php' );

add_action( 'wp_loaded', __NAMESPACE__ . '\load_plugin' );
add_filter( 'wp_mail', __NAMESPACE__ . '\log_wp_mail', 10, 5 );
register_uninstall_hook( __FILE__, __NAMESPACE__ . '\uninstall' );

/**
 * @wp-hook plugins_loaded
 *
 */
function load_plugin() {

	$mail_log_cpt = new Model\Post_Type\Mail_Log;
	$mail_log_cpt->register();

	do_action( 'dnaml_post_type_registered' );

	add_action( 'phpmailer_init', function( \PHPMailer $phpmailer ) {

		// avoid sending the mails

		if ( ! defined( 'MAIL_LOG_BLOCK_MAILS' ) || ! MAIL_LOG_BLOCK_MAILS )
			return;

		$phpmailer->clearAllRecipients();
	} );
}



/**
 * log wp_mail calls
 *
 * @wp-hook wp_mail
 *
 * @param array $mail ( 'to', 'subject', 'message', 'headers', 'attachments' )
 *
 * @return void
 */
function log_wp_mail( $mail ) {

	$logger = new Model\WP_Mail_Logger;
	$logger->set_post_type( new Model\Post_Type\Mail_Log );
	$logger->log(
		$mail[ 'to' ],
		$mail[ 'subject' ],
		$mail[ 'message' ],
		$mail[ 'headers' ],
		$mail[ 'attachments' ]
	);
	if ( did_action( 'dnaml_post_type_registered' ) )
		$logger->save();
	else
		add_action( 'dnaml_post_type_registered', array( $logger, 'save' ) );
}

/**
 * drop all log entries
 *
 * @return void
 */
function uninstall() {

	$post_type = new Model\Post_Type\Mail_Log;
	if ( ! post_type_exists( $post_type->post_type ) )
		$post_type->register();

	$query_args = array(
		'post_type'      => $post_type->post_type,
		'post_status'    => 'any',
		'posts_per_page' => -1,
		'fields'         => 'ids',
		'update_post_term_cache' => FALSE,
		'update_post_meta_cache' => FALSE
	);

	$query = new \WP_Query( $query_args );
	if ( ! $query->have_posts() )
		return;

	foreach ( $query->posts as $post_ID ) {
		wp_delete_post( $post_ID, TRUE );
	}
}
