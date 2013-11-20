<?php

/**
 * logs wp_mail calls as Custom post type
 *
 */
namespace dna\WP_Mail_Log\Model;

class WP_Mail_Logger {

	/**
	 * post type
	 *
	 * @type \dna\WP_Mail_Log\Model\Post_Type\Mail_Log
	 */
	protected $post_type;

	/**
	 * list of mails to log
	 *
	 * @var array
	 */
	protected $mail_log = array();

	/**
	 * setter for the post type
	 *
	 * @param \dna\WP_Mail_Log\Model\Post_Type\Mail_Log
	 */
	public function set_post_type( $post_type ) {

		$this->post_type = $post_type;
	}

	/**
	 * add a record to a log
	 *
	 * @param string $to
	 * @param string $subject
	 * @param string $message
	 * @param string $headers
	 * @param array $attachments
	 * @return void
	 */
	public function log( $to, $subject, $message, $headers, $attachments ) {

		$this->mail_log[] = array(
			'to'      => $to,
			'subject' => $subject,
			'message' => $message,
			'headers' => $headers,
			'attachments' => $attachments,
			'timestamp' => time()
		);
	}

	/**
	 * save
	 *
	 * @return TRUE|\WP_Error
	 */
	public function save() {

		if ( ! $this->post_type ) {
			return new \WP_Error( 1, 'No post type set' );
		}

		if ( ! \post_type_exists( $this->post_type->post_type ) )
			return new \WP_Error( 1, 'Invalid post type' );

		$error = FALSE;
		foreach ( $this->mail_log as $mail ) {
			$post = $this->build_post( $mail );
			$post_ID = \wp_insert_post( $post, TRUE );
			if ( \is_wp_error( $post_ID ) ) {
				if ( ! $error )
					$error = new \WP_Error;
				$error->add(
					$post_ID->get_error_code(),
					$post_ID->get_error_message(),
					$post_ID->get_error_data()
				);
			}
		}

		if ( $error )
			return $error;

		return TRUE;
	}

	/**
	 * build the post
	 *
	 * @param array $mail
	 * @return array
	 */
	protected function build_post( $mail ) {

		$content = $mail[ 'headers' ]
			. str_repeat( PHP_EOL, 2 )
			. $mail[ 'message' ];

		$post = array(
			'post_title'   => '<' . $mail[ 'to' ] . '> ' . $mail[ 'subject' ],
			'post_content' => esc_html( $content ),
			'post_type'    => $this->post_type->post_type,
			'post_date'    => date( 'Y-m-d H:i:s', $mail[ 'timestamp' ] ),
			'post_status'  => 'private'
		);

		return $post;
	}
}
