<?php # -*- coding: utf-8 -*-

namespace dna\WP_Mail_Log\Model;

interface Mail_Logger {

	/**
	 * add a record to a log
	 *
	 * @param string $to
	 * @param string $subject
	 * @param string $message
	 * @param string $headers
	 * @param array $attachments
	 *
	 * @return void
	 */
	public function log( $to, $subject, $message, $headers = '', array $attachments = [] );
}
