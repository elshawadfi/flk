<?php
/**
 * This file contains all the helper functions for Logo Carousel
 *
 * @since 3.0
 * @Package log-carousel-free
 */

/**
 * Generate Unique Number
 *
 * @package Logo Carousel
 * @since 3.1.1
 */
function sp_lc_get_unique() {
	static $unique = 0;
	$unique ++;

	return $unique;
}


/**
 * Logo and URL columns on admin panel
 *
 * @since 3.0.1
 * @param $columns
 *
 * @return array
 */
function sp_logo_carousel_add_columns( $columns ) {
	$columns = array(
		'cb'    => 'cb',
		'title' => __( 'Title', 'logo-carousel-free' ),
		'thumb' => __( 'Logo', 'logo-carousel-free' ),
		'date'  => __( 'Date', 'logo-carousel-free' ),
	);

	return $columns;
}
add_action( 'manage_sp_logo_carousel_posts_columns', 'sp_logo_carousel_add_columns' );

function sp_logo_carousel_logo_thumb( $column, $post_id ) {
	if ( has_post_thumbnail( $post_id ) ) {
		$image_url      = get_the_post_thumbnail_url( $post_id, 'thumbnail' );
		$featured_image = '<img src="' . esc_url( $image_url ) . '" class="list-logo"/>';
	} else {
		$featured_image = '<span aria-hidden="true">—</span>';
	}
	if ( $column == 'thumb' ) {
		echo $featured_image;
	}
}
add_action( 'manage_sp_logo_carousel_posts_custom_column', 'sp_logo_carousel_logo_thumb', 10, 2 );

/**
 * Logo Meta Box
 *
 * @return void
 */
function sp_lc_add_meta_box() {
	remove_meta_box( 'postimagediv', 'sp_logo_carousel', 'side' );
	add_meta_box( 'postimagediv', __( 'Logo Image', 'logo-carousel-free' ), 'post_thumbnail_meta_box', 'sp_logo_carousel', 'normal', 'high' );
}
add_action( 'add_meta_boxes', 'sp_lc_add_meta_box' );

/**
 * Review Text
 *
 * @param $text
 *
 * @return string
 */
function sp_logo_carousel_admin_footer( $text ) {
	$screen = get_current_screen();
	if ( 'sp_lc_shortcodes' == get_post_type() || 'sp_logo_carousel' == get_post_type() || $screen->id == 'sp_logo_carousel_page_lc_category' ) {
		$url  = 'https://wordpress.org/support/plugin/logo-carousel-free/reviews/?filter=5#new-post';
		$text = sprintf( __( 'If you like <strong>Logo Carousel</strong> please leave us a <a href="%s" target="_blank">&#9733;&#9733;&#9733;&#9733;&#9733;</a> rating. Your Review is very important to us as it helps us to grow more. ', 'logo-carousel-free' ), $url );
	}

	return $text;
}
add_filter( 'admin_footer_text', 'sp_logo_carousel_admin_footer', 1, 2 );

/**
 * Function creates logo carousel duplicate as a draft.
 */
function sp_lc_shortcode_duplicate() {
	global $wpdb;
	if ( ! ( isset( $_GET['post'] ) || isset( $_POST['post'] ) || ( isset( $_REQUEST['action'] ) && 'sp_lc_shortcode_duplicate' == $_REQUEST['action'] ) ) ) {
		wp_die( __( 'No shortcode to duplicate has been supplied!', 'logo-carousel-free' ) );
	}

	/**
	 * Nonce verification
	 */
	if ( ! isset( $_GET['sp_lc_duplicate_nonce'] ) || ! wp_verify_nonce( $_GET['sp_lc_duplicate_nonce'], basename( __FILE__ ) ) ) {
		return;
	}

	/*
		* Get the original shortcode id
		*/
	$post_id = ( isset( $_GET['post'] ) ? absint( $_GET['post'] ) : absint( $_POST['post'] ) );
	/*
		* and all the original shortcode data then
		*/
	$post = get_post( $post_id );

	$current_user    = wp_get_current_user();
	$new_post_author = $current_user->ID;

	/*
		* if shortcode data exists, create the shortcode duplicate
		*/
	if ( isset( $post ) && $post != null ) {

		/*
			* new shortcode data array
			*/
		$args = array(
			'comment_status' => $post->comment_status,
			'ping_status'    => $post->ping_status,
			'post_author'    => $new_post_author,
			'post_content'   => $post->post_content,
			'post_excerpt'   => $post->post_excerpt,
			'post_name'      => $post->post_name,
			'post_parent'    => $post->post_parent,
			'post_password'  => $post->post_password,
			'post_status'    => 'draft',
			'post_title'     => $post->post_title,
			'post_type'      => $post->post_type,
			'to_ping'        => $post->to_ping,
			'menu_order'     => $post->menu_order,
		);

		/*
			* insert the shortcode by wp_insert_post() function
			*/
		$new_post_id = wp_insert_post( $args );

		/*
			* get all current post terms ad set them to the new post draft
			*/
		$taxonomies = get_object_taxonomies( $post->post_type ); // returns array of taxonomy names for post type, ex array("category", "post_tag");
		foreach ( $taxonomies as $taxonomy ) {
			$post_terms = wp_get_object_terms( $post_id, $taxonomy, array( 'fields' => 'slugs' ) );
			wp_set_object_terms( $new_post_id, $post_terms, $taxonomy, false );
		}

		/*
			* duplicate all post meta just in two SQL queries
			*/
		$post_meta_infos = $wpdb->get_results( "SELECT meta_key, meta_value FROM $wpdb->postmeta WHERE post_id=$post_id" );
		if ( count( $post_meta_infos ) != 0 ) {
			$sql_query = "INSERT INTO $wpdb->postmeta (post_id, meta_key, meta_value) ";
			foreach ( $post_meta_infos as $meta_info ) {
				$meta_key = $meta_info->meta_key;
				if ( $meta_key == '_wp_old_slug' ) {
					continue;
				}
				$meta_value      = addslashes( $meta_info->meta_value );
				$sql_query_sel[] = "SELECT $new_post_id, '$meta_key', '$meta_value'";
			}
			$sql_query .= implode( ' UNION ALL ', $sql_query_sel );
			$wpdb->query( $sql_query );
		}

		/*
			* finally, redirect to the edit post screen for the new draft
			*/
		wp_redirect( admin_url( 'edit.php?post_type=' . $post->post_type ) );
		exit;
	} else {
		wp_die( __( 'Shortcode creation failed, could not find original post: ', 'logo-carousel-free' ) . $post_id );
	}
}
add_action( 'admin_action_sp_lc_shortcode_duplicate', 'sp_lc_shortcode_duplicate' );

/*
	* Add the duplicate link to action list for post_row_actions
	*/
function sp_lc_shortcode_duplicate_link( $actions, $post ) {
	if ( current_user_can( 'edit_posts' ) && $post->post_type == 'sp_lc_shortcodes' ) {
		$actions['duplicate'] = '<a href="' . wp_nonce_url( 'admin.php?action=sp_lc_shortcode_duplicate&post=' . $post->ID, basename( __FILE__ ), 'sp_lc_duplicate_nonce' ) . '" rel="permalink">' . __( 'Duplicate', 'logo-carousel-free' ) . '</a>';
	}
	return $actions;
}
add_filter( 'post_row_actions', 'sp_lc_shortcode_duplicate_link', 10, 2 );

/**
 * Do Shortcode used as a function
 *
 * @since 3.1
 * @param $id
 */
function logocarousel( $id ) {
	echo do_shortcode( '[logocarousel id="' . $id . '"]' );
}

/**
 * Widget area support
 *
 * @since 3.0.1
 */
add_filter( 'widget_text', 'do_shortcode' );
