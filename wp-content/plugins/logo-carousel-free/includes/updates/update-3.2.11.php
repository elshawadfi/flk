<?php
/**
 * Update version.
 */
update_option( 'logo_carousel_free_version', '3.2.11' );
update_option( 'logo_carousel_free_db_version', '3.2.11' );

/**
 * Change the post type wpl_logo_carousel to sp_logo_carousel.
 */
function sp_change_lc_post_type() {
	global $wpdb;
	$old_post_types = array( 'wpl_logo_carousel' => 'sp_logo_carousel' );
	foreach ( $old_post_types as $old_type => $type ) {
		$wpdb->query(
			$wpdb->prepare(
				"UPDATE {$wpdb->posts} SET post_type = REPLACE(post_type, %s, %s) 
							WHERE post_type LIKE %s", $old_type, $type, $old_type
			)
		);
		$wpdb->query(
			$wpdb->prepare(
				"UPDATE {$wpdb->posts} SET guid = REPLACE(guid, %s, %s) 
							WHERE guid LIKE %s", "post_type={$old_type}", "post_type={$type}", "%post_type={$type}%"
			)
		);
		$wpdb->query(
			$wpdb->prepare(
				"UPDATE {$wpdb->posts} SET guid = REPLACE(guid, %s, %s) 
							WHERE guid LIKE %s", "/{$old_type}/", "/{$type}/", "%/{$old_type}/%"
			)
		);
	}
}

/**
 * Change the post type wpl_lcp_shortcodes to sp_lc_shortcodes.
 */
function sp_change_lc_shortcodes_post_type() {
	global $wpdb;
	$old_post_types = array( 'wpl_lcp_shortcodes' => 'sp_lc_shortcodes' );
	foreach ( $old_post_types as $old_type => $type ) {
		$wpdb->query(
			$wpdb->prepare(
				"UPDATE {$wpdb->posts} SET post_type = REPLACE(post_type, %s, %s) 
							WHERE post_type LIKE %s", $old_type, $type, $old_type
			)
		);
		$wpdb->query(
			$wpdb->prepare(
				"UPDATE {$wpdb->posts} SET guid = REPLACE(guid, %s, %s) 
							WHERE guid LIKE %s", "post_type={$old_type}", "post_type={$type}", "%post_type={$type}%"
			)
		);
		$wpdb->query(
			$wpdb->prepare(
				"UPDATE {$wpdb->posts} SET guid = REPLACE(guid, %s, %s) 
							WHERE guid LIKE %s", "/{$old_type}/", "/{$type}/", "%/{$old_type}/%"
			)
		);
	}
}
sp_change_lc_post_type();
sp_change_lc_shortcodes_post_type();
