<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       http://jeanbaptisteaudras.com
 * @since      1.0.0
 *
 * @package    ssmp
 * @subpackage ssmp/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * @package    ssmp
 * @subpackage ssmp/admin
 * @author     audrasjb <audrasjb@gmail.com>
 */

/**
 *
 * Plugin options in reading section
 *
 */

function ssmp_settings_init() {
	register_setting('reading', 'ssmp_settings');
	add_settings_section(
		'ssmp_settings_section',
    	__('Site map page', 'simple-site-map-page'),
		'ssmp_settings_section_callback',
		'reading'
	);
	add_settings_field(
		'ssmp_settings',
		__('Site map page', 'simple-site-map-page'),
		'ssmp_settings_selectpage_callback',
		'reading',
		'ssmp_settings_section'
	);
}
add_action('admin_init', 'ssmp_settings_init');

/**
 * callback functions
 */
function ssmp_settings_section_callback() {
	echo '<p>' . __('Once the site map page is defined, you should go to <em>Appearance &gt; Menus</em>, build your site map and register it in the <em>Site Map</em> location. <br />The site map will automatically appear under the content of the selected page.', 'simple-site-map-page') . '</p>';
}
function ssmp_settings_selectpage_callback() {
	$options = get_option('ssmp_settings');
	if (isset($options['ssmp_page'])) {
		$optionPage = $options['ssmp_page'];
	} else {
		$optionPage = '';		
	}
	wp_dropdown_pages(
		array(
			'name' => 'ssmp_settings[ssmp_page]',
			'echo' => 1,
			'show_option_none' => __( '&mdash; Select &mdash;', 'ssmp' ),
			'option_none_value' => '',
			'selected' => $optionPage
		)
	);
}

/**
 *
 * Menu location
 *
 */
function ssmp_register_menu() {
  register_nav_menu('ssmp', __( 'Site map' ));
}
add_action( 'init', 'ssmp_register_menu' );

