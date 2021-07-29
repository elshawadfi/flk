<?php
/**
 * Fired during plugin updates
 *
 * @link       https://shapedplugin.com/
 * @since      3.2.8
 *
 * @package    Logo_Carousel_Free
 * @subpackage Logo_Carousel_Free/includes
 */

// don't call the file directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Fired during plugin updates.
 *
 * This class defines all code necessary to run during the plugin's updates.
 *
 * @since      3.2.8
 * @package    Logo_Carousel_Free
 * @subpackage Logo_Carousel_Free/includes
 * @author     ShapedPlugin <support@shapedplugin.com>
 */
class Logo_Carousel_Free_Updates {

	/**
	 * DB updates that need to be run
	 *
	 * @var array
	 */
	private static $updates = [
		'3.2.8' => 'updates/update-3.2.8.php',
		'3.2.11' => 'updates/update-3.2.11.php',
	];

	/**
	 * Binding all events
	 *
	 * @since 3.2.8
	 *
	 * @return void
	 */
	public function __construct() {
		add_action( 'plugins_loaded', array( $this, 'do_updates' ) );
	}

	/**
	 * Check if need any update
	 *
	 * @since 3.2.8
	 *
	 * @return boolean
	 */
	public function is_needs_update() {
		$installed_version = get_option( 'logo_carousel_free_version' );
		$first_version = get_option( 'logo_carousel_free_first_version' );
		$activation_date = get_option( 'logo_carousel_free_activation_date' );

		if ( false === $installed_version ) {
			update_option( 'logo_carousel_free_version', SP_LC_VERSION );
			update_option( 'logo_carousel_free_db_version', SP_LC_VERSION );
		}
		if ( false === $first_version ) {
			update_option( 'logo_carousel_free_first_version', SP_LC_VERSION );
		}
		if ( false === $activation_date ) {
			update_option( 'logo_carousel_free_activation_date', current_time( 'timestamp' ) );
		}

		if ( version_compare( $installed_version, SP_LC_VERSION, '<' ) ) {
			return true;
		}

		return false;
	}

	/**
	 * Do updates.
	 *
	 * @since 3.2.8
	 *
	 * @return void
	 */
	public function do_updates() {
		$this->perform_updates();
	}

	/**
	 * Perform all updates
	 *
	 * @since 3.2.8
	 *
	 * @return void
	 */
	public function perform_updates() {
		if ( ! $this->is_needs_update() ) {
			return;
		}

		$installed_version = get_option( 'logo_carousel_free_version' );

		foreach ( self::$updates as $version => $path ) {
			if ( version_compare( $installed_version, $version, '<' ) ) {
				include $path;
				update_option( 'logo_carousel_free_version', $version );
			}
		}

		update_option( 'logo_carousel_free_version', SP_LC_VERSION );

	}

}
new Logo_Carousel_Free_Updates();
