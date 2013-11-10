<?php

/**
 * Abstract wrapper for custom post types
 *
 */

namespace dna\WP_Mail_Log\Model;

abstract class Custom_Post_Type {

	/**
	 * post type slug
	 *
	 */
	public $post_type = '';

	public $labels = array();

	public $params = array();

	public function register() {

		register_post_type(
			$this->post_type,
			$this->params
		);
	}

	/**
	 * register meta box callback
	 *
	 */
	public function register_meta_box() {

		$slug = $this->post_type;
		do_action( "dnaml_register_{$slug}_meta_box" );
	}
}
