<?php

/**
 * Custom Post Type Mail_Log
 *
 */

namespace dna\WP_Mail_Log\Model\Post_Type;
use \dna\WP_Mail_Log\Model as Model;

class Mail_Log extends Model\Custom_Post_Type {

	public function __construct() {

		$this->post_type = 'mail_log';

		$this->labels = array(
			'name'               => __( 'wp_mail Records', 'wp_mail_log' ),
			'singular_name'      => __( 'wp_mail Record', 'wp_mail_log' ),
			'menu_name'          => __( 'Mail Log', 'wp_mail_log' ),
		);

		$this->params = array(
			'labels' => $this->labels,
			'description'          => __( 'Log of wp_mail() function calls', 'wp_mail_log' ),
			'public'               => TRUE,
			'publicly_queryable'   => FALSE,
			'exclude_from_search'  => TRUE,
			'show_ui '             => TRUE,
			'show_in_menu'         => TRUE,
			'menu_position'        => 20,
			'capabilities'         => array(
				'read_post'        => 'manage_options',
				'delete_post'      => 'manage_options',
				'edit_post'        => 'manage_options',
				'edit_other_posts' => 'manage_options',
				'read_posts'       => 'manage_options',
				'edit_posts'       => 'manage_options',
				'delete_posts'     => 'manage_options'
			), 'hierarchical'         => FALSE,
			'supports'             => array(
				'title',
				'editor',
			),
			'has_archive'
			'query_var'            => 'wp_mail_log',
			'can_export'           => TRUE,
			'show_in_nav_menus'    => FALSE,
			'register_meta_box_cb' => array( $this, 'register_meta_box' )
		);
	}
}
