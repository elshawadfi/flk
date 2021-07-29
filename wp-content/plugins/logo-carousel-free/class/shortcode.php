<?php
/**
 * This is to register the shortcode post type.
 *
 * @package logo-carousel-free
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
class SPLC_Shortcode {

	private static $_instance;

	/**
	 * SPLC_Shortcode constructor.
	 */
	public function __construct() {
		add_filter( 'init', array( $this, 'register_post_type' ) );
	}

	/**
	 * Allows for accessing single instance of class. Class should only be constructed once per call.
	 *
	 * @return SPLC_Shortcode
	 */
	public static function getInstance() {
		if ( ! self::$_instance ) {
			self::$_instance = new SPLC_Shortcode();
		}

		return self::$_instance;
	}

	/**
	 * Shortcode Post Type
	 */
	function register_post_type() {
		register_post_type(
			'sp_lc_shortcodes', array(
				'label'           => __( 'Logo Carousel Shortcode', 'logo-carousel-free' ),
				'description'     => __( 'Generate Shortcode for Logo Carousel', 'logo-carousel-free' ),
				'public'          => false,
				'show_ui'         => true,
				'show_in_menu'    => 'edit.php?post_type=sp_logo_carousel',
				'hierarchical'    => false,
				'query_var'       => false,
				'supports'        => array( 'title' ),
				'capability_type' => 'post',
				'labels'          => array(
					'name'               => __( 'All Logo Carousels', 'logo-carousel-free' ),
					'singular_name'      => __( 'Logo Carousel', 'logo-carousel-free' ),
					'menu_name'          => __( 'Shortcode Generator', 'logo-carousel-free' ),
					'add_new'            => __( 'Add New', 'logo-carousel-free' ),
					'add_new_item'       => __( 'Add New Carousel', 'logo-carousel-free' ),
					'edit'               => __( 'Edit', 'logo-carousel-free' ),
					'edit_item'          => __( 'Edit Carousel', 'logo-carousel-free' ),
					'new_item'           => __( 'New Carousel', 'logo-carousel-free' ),
					'view'               => __( 'View Shortcode', 'logo-carousel-free' ),
					'view_item'          => __( 'View Shortcode', 'logo-carousel-free' ),
					'search_items'       => __( 'Search Carousel', 'logo-carousel-free' ),
					'not_found'          => __( 'No Logo Carousel Found', 'logo-carousel-free' ),
					'not_found_in_trash' => __( 'No Logo Carousel Found in Trash', 'logo-carousel-free' ),
					'parent'             => __( 'Parent Logo Carousel', 'logo-carousel-free' ),
				),
			)
		);
	}

}
