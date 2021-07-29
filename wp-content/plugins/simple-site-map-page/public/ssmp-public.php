<?php

/**
 * The public-specific functionality of the plugin.
 *
 * @link       http://jeanbaptisteaudras.com
 * @since      1.0.0
 *
 * @package    ssmp
 * @subpackage ssmp/public
 */

/**
 * The public-specific functionality of the plugin.
 *
 * @package    ssmp
 * @subpackage ssmp/admin
 * @author     audrasjb <audrasjb@gmail.com>
 */

add_filter( 'the_content', 'ssmp_display_sitemap', 100 );
function ssmp_display_sitemap( $content ) {
	$newContent = '';
	$newContent .= $content;
	if ( get_option( 'ssmp_settings' ) ) {
		$options = get_option('ssmp_settings');
		if ( function_exists( 'pll_get_post' ) ) {
			$options['ssmp_page'] = pll_get_post( $options['ssmp_page'] );
		}
		if ( isset($options['ssmp_page']) && is_page($options['ssmp_page']) ) {
			$optionPage = $options['ssmp_page'];
			$newContent .= wp_nav_menu(
				array(
					'echo' => false,
					'menu_class' => 'ssmp simple-site-map',
					'theme_location' => 'ssmp',
				)
			);
		}
	}
	return $newContent;
}

function ssmp_print_inline_script() {
	if ( get_option( 'ssmp_settings' ) ) {
		$options = get_option('ssmp_settings');
		if ( function_exists( 'pll_get_post' ) ) {
			$options['ssmp_page'] = pll_get_post( $options['ssmp_page'] );
		}
		if ( wp_script_is( 'jquery', 'done' ) && is_page($options['ssmp_page']) ) {
			?>
			<script type="text/javascript">
			(function( $ ) {		  "use strict";		
				$(function() {
					$('.menu-item-type-custom > a').each(function() {
						if ($(this).attr('href') == '#') {
							$(this).replaceWith('<span>' + $(this).text() + '</span>');
						}
					});
				});
			}(jQuery));
			</script>
			<?php
		}
	}
}
add_action( 'wp_footer', 'ssmp_print_inline_script' );