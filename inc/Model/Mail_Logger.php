<?php # -*- coding: utf-8 -*-

namespace dna\WP_Mail_Log\Model;

interface Mail_Logger {

	/**
	 * Add a record to a log
	 *
	 * the signature follows the hook 'wp_mail'
	 *
	 * @param string|array $to
	 * @param string $subject
	 * @param string $message
	 * @param string|array $headers
	 * @param string|array $attachments
	 *
	 * @return void
	 */
	public function log( $to, $subject, $message, $headers = '', $attachments = '' );
}
