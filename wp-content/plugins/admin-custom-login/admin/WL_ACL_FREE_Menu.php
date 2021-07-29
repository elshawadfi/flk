<?php
defined( 'ABSPATH' ) or die();

class WL_ACL_FREE_Menu {
	public static function admin_menu() {
		require_once( 'inc/admin_menu.php' );
	}

	public static function admin_menu_assets() {
		wp_enqueue_style( 'wp_acl_lc', WEBLIZAR_NALF_PLUGIN_URL . '/admin/inc/css/admin_menu.css' );
	}
}
?>