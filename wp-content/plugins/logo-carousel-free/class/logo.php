<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Handles displays logo custom post.
 *
 * @package logo-carousel-free
 * @since 3.0
 */
class SPLC_Logo {

	/**
	 * The single instance of the class.
	 *
	 * @var self
	 * @since 3.0
	 */
	private static $_instance = null;

	/**
	 * Allows for accessing single instance of class. Class should only be constructed once per call.
	 *
	 * @since 3.0
	 * @static
	 * @return self Main instance.
	 */
	public static function getInstance() {
		if ( ! self::$_instance ) {
			self::$_instance = new SPLC_Logo();
		}

		return self::$_instance;
	}

	/**
	 * Constructor.
	 */
	public function __construct() {
		add_action( 'init', array( $this, 'register_post_type' ) );
	}

	/**
	 * Registers the custom post type
	 */
	public function register_post_type() {
		if ( post_type_exists( 'sp_logo_carousel' ) ) {
			return;
		}
		$menu_icon      = 'data:image/svg+xml;base64,' . base64_encode(
			'<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" 		id="Layer_1" x="0px" y="0px" viewBox="0 0 288 288" enable-background="new 0 0 288 288" xml:space="preserve">
		<path fill="#A0A5AA" d="M237.7,0H50.3C22.5,0,0,22.5,0,50.3v187.4C0,265.5,22.5,288,50.3,288h187.4c27.8,0,50.3-22.5,50.3-50.3V50.3  C288,22.5,265.5,0,237.7,0z M82.5,197.4c0,6.4-5.2,11.5-11.5,11.5H50c-6.4,0-11.5-5.2-11.5-11.5V90.6c0-6.4,5.2-11.5,11.5-11.5H71  c6.4,0,11.5,5.2,11.5,11.5V197.4z M194.2,217.5c0,6.4-5.2,11.5-11.5,11.5h-77.4c-6.4,0-11.5-5.2-11.5-11.5V70.5  c0-6.4,5.2-11.5,11.5-11.5h77.4c6.4,0,11.5,5.2,11.5,11.5V217.5z M249.5,197.4c0,6.4-5.2,11.5-11.5,11.5H217  c-6.4,0-11.5-5.2-11.5-11.5V90.6c0-6.4,5.2-11.5,11.5-11.5H238c6.4,0,11.5,5.2,11.5,11.5V197.4z"/>
		</svg>'
		);
		$args_post_type = array(
			'label'               => __( 'Logo', 'logo-carousel-free' ),
			'description'         => __( 'Logo carousel post type', 'logo-carousel-free' ),
			'exclude_from_search' => false,
			'publicly_queryable'  => false,
			'show_ui'             => true,
			'show_in_menu'        => true,
			'show_in_admin_bar'   => true,
			'menu_position'       => 20,
			'menu_icon'           => $menu_icon,
			'capability_type'     => 'post',
			'hierarchical'        => false,
			'has_archive'         => false,
			'can_export'          => true,
			'rewrite'             => array( 'slug' => '' ),
			'query_var'           => false,
			'supports'            => array(
				'title',
				'thumbnail',
			),
			'labels'              => array(
				'name'                  => __( 'All Logos', 'logo-carousel-free' ),
				'singular_name'         => __( 'Logo', 'logo-carousel-free' ),
				'menu_name'             => __( 'Logo Carousel', 'logo-carousel-free' ),
				'add_new'               => __( 'Add New', 'logo-carousel-free' ),
				'add_new_item'          => __( 'Add New', 'logo-carousel-free' ),
				'edit'                  => __( 'Edit', 'logo-carousel-free' ),
				'edit_item'             => __( 'Edit', 'logo-carousel-free' ),
				'new_item'              => __( 'New Logo', 'logo-carousel-free' ),
				'view'                  => __( 'View Logo', 'logo-carousel-free' ),
				'view_item'             => __( 'View Logo', 'logo-carousel-free' ),
				'all_items'             => __( 'All Logos', 'logo-carousel-free' ),
				'search_items'          => __( 'Search Logo', 'logo-carousel-free' ),
				'not_found'             => __( 'No Logo Found', 'logo-carousel-free' ),
				'not_found_in_trash'    => __( 'No Logo Found in Trash', 'logo-carousel-free' ),
				'parent'                => __( 'Parent Logos', 'logo-carousel-free' ),
				'featured_image'        => __( 'Logo Image', 'logo-carousel-free' ),
				'set_featured_image'    => __( 'Set Logo', 'logo-carousel-free' ),
				'remove_featured_image' => __( 'Remove logo image', 'logo-carousel-free' ),
				'use_featured_image'    => __( 'Use as logo image', 'logo-carousel-free' ),
			),
		);

		$args_post_type = apply_filters( 'wpl_lc_register_logo_post_type', $args_post_type );

		register_post_type( 'sp_logo_carousel', $args_post_type );
	}

}
