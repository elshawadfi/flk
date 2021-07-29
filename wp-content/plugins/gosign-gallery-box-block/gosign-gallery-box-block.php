<?php
/**
 * Plugin Name: Gosign — Gallery Box Block
 * Plugin URI: https://www.gosign.de/
 * Description: Simple Logo Slider to show your all clients as logo
 * Author: Gosign.de
 * Author URI: https://www.gosign.de/wordpress-agentur/
 * Version: 1.0.0
 * License: GPL3+
 * License URI: https://www.gnu.org/licenses/gpl-3.0.txt
 *
 * @package GLSB
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * GosignGalleryBoxBlock Class
 */
class GosignGalleryBoxBlock {

    /**
     * The single class instance.
     *
     * @var $_instance
     */
    private static $_instance = null;

    /**
     * Path to the plugin directory
     *
     * @var $plugin_path
     */
    public $plugin_path;

    /**
     * URL to the plugin directory
     *
     * @var $plugin_url
     */
    public $plugin_url;

    /**
     * Plugin name
     *
     * @var $plugin_name
     */
    public $plugin_name;

    /**
     * Plugin version
     *
     * @var $plugin_version
     */
    public $plugin_version;

    /**
     * Plugin slug
     *
     * @var $plugin_slug
     */
    public $plugin_slug;

    /**
     * Plugin name sanitized
     *
     * @var $plugin_name_sanitized
     */
    public $plugin_name_sanitized;

    /**
     * GhostKit constructor.
     */
    public function __construct() {
        /* We do nothing here! */
    }

    /**
     * Main Instance
     * Ensures only one instance of this class exists in memory at any one time.
     */
    public static function instance() {
        if ( is_null( self::$_instance ) ) {
            self::$_instance = new self();
            self::$_instance->init_options();
            self::$_instance->init_hooks();
        }
        return self::$_instance;
    }

    /**
     * Init options
     */
    public function init_options() {
        $this->plugin_path = plugin_dir_path( __FILE__ );
        $this->plugin_url = plugin_dir_url( __FILE__ );

        // load textdomain.
        load_plugin_textdomain( 'gosign-gallery-box-block', false, basename( dirname( __FILE__ ) ) . '/languages' );

    }


    /**
     * Init hooks
     */
    public function init_hooks() {
        add_action( 'admin_init', array( $this, 'admin_init' ) );

        // include blocks.
        // work only if Gutenberg available.
        if ( function_exists( 'register_block_type' ) ) {
            add_action( 'init', array( $this, 'register_scripts' ) );

            // we need to enqueue the main script earlier to let 3rd-party plugins add custom styles support.
            add_action( 'enqueue_block_editor_assets', array( $this, 'enqueue_block_editor_assets' ), 9 );
            add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_block_assets' ) );
        }
    }

    /**
     * Register scripts.
     */
    public function register_scripts() {
        wp_register_script(
            'gosign-gallery-box-slick-slider',
            plugins_url(
                'assets/vendor/slick/js/slick.min.js', __FILE__
            )
        );

        // adding css for slick slider for frontend.
        wp_register_style(
            'gosign-gallery-box-slick-slider-css',
            plugins_url(
                'assets/vendor/slick/css/slick.min.css', __FILE__
            )
        );
        // adding css for slick slider for frontend.
        wp_register_style(
            'gosign-gallery-box-slick-slider-css-theme',
            plugins_url(
                'assets/vendor/slick/css/slick-theme.min.css', __FILE__
            )
        );

        // fancybox js and css.
        wp_register_script(
            'gosign-gallery-box-fancybox-js',
            plugins_url(
                'assets/vendor/fancybox/dist/jquery.fancybox.min.js', __FILE__
            )
        );
        wp_register_style(
            'gosign-gallery-box-fancybox-css',
            plugins_url(
                'assets/vendor/fancybox/dist/jquery.fancybox.min.css', __FILE__
            )
        );
    }

    /**
     * Enqueue editor assets
     */
    public function enqueue_block_editor_assets() {
        $css_deps = array();
        $js_deps = array( 'wp-editor', 'wp-blocks', 'wp-i18n', 'wp-element', 'wp-edit-post', 'wp-compose', 'underscore', 'wp-components', 'jquery' );

        // Styles.
        wp_enqueue_style(
            'gosign-gallery-box-block-admin-css',
            plugins_url( 'assets/admin/css/style.min.css', __FILE__ ),
            $css_deps,
            filemtime( plugin_dir_path( __FILE__ ) . 'assets/admin/css/style.min.css' )
        );

        // Scripts.
        wp_enqueue_script(
            'gosign-gallery-box-block-admin-js', // Handle.
            plugins_url( 'blocks/index.min.js', __FILE__ ), // Block.build.js: We register the block here. Built with Webpack.
            $js_deps, // Dependencies, defined above.
            filemtime( plugin_dir_path( __FILE__ ) . 'blocks/index.min.js' )
        );
    }

    /**
     * Enqueue editor frontend assets
     */
    public function enqueue_block_assets() {
        $css_deps = array( 'gosign-gallery-box-slick-slider-css', 'gosign-gallery-box-slick-slider-css-theme', 'gosign-gallery-box-fancybox-css' );
        $js_deps = array( 'jquery', 'gosign-gallery-box-slick-slider', 'gosign-gallery-box-fancybox-js' );

        // Styles.
        wp_enqueue_style(
            'gosign-gallery-box-block-frontend-css',
            plugins_url( 'blocks/style.min.css', __FILE__ ),
            $css_deps,
            filemtime( plugin_dir_path( __FILE__ ) . 'blocks/style.min.css' )
        );
        // Scripts.
        wp_enqueue_script(
            'gosign-gallery-box-block-backend-js',
            plugins_url( 'assets/js/script.min.js', __FILE__ ),
            $js_deps
        );
    }

    /**
     * Init variables
     */
    public function admin_init() {
        // get current plugin data.
        $data = get_plugin_data( __FILE__ );
        $this->plugin_name = $data['Name'];
        $this->plugin_version = $data['Version'];
        $this->plugin_slug = plugin_basename( __FILE__, '.php' );
        $this->plugin_name_sanitized = basename( __FILE__, '.php' );
    }
}

/**
 * Function works with the GosignGalleryBoxBlock class instance
 *
 * @return object GosignGalleryBoxBlock
 */
function gosigngalleryboxblock() {
    return GosignGalleryBoxBlock::instance();
}
add_action( 'plugins_loaded', 'gosigngalleryboxblock' );
