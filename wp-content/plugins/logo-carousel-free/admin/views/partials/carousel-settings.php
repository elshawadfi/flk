<?php
/**
 * Provides the 'Resources' view for the corresponding tab in the Shortcode Meta Box.
 *
 * @since 3.0
 *
 * @package    logo-carousel-free
 * @subpackage logo-carousel-free/admin/views/partials
 */
?>

<div id="splc-tab-2" class="sp-lc-tab-content">
	<?php
	$this->metaboxform->checkbox(
		array(
			'id'      => 'lc_auto_play',
			'name'    => __( 'AutoPlay', 'logo-carousel-free' ),
			'desc'    => __( 'Check to on autoplay carousel.', 'logo-carousel-free' ),
			'default' => true,
		)
	);
	$this->metaboxform->number(
		array(
			'id'      => 'lc_auto_play_speed',
			'name'    => __( 'AutoPlay Speed', 'logo-carousel-free' ),
			'desc'    => __( 'Set autoplay speed in millisecond.', 'logo-carousel-free' ),
			'after'   => __( '(ms)', 'logo-carousel-free' ),
			'default' => 3000,
		)
	);
	$this->metaboxform->checkbox(
		array(
			'id'      => 'lc_pause_on_hover',
			'name'    => __( 'Pause on Hover', 'logo-carousel-free' ),
			'desc'    => __( 'Check to activate pause on hover carousel.', 'logo-carousel-free' ),
			'default' => 'on',
		)
	);
	$this->metaboxform->checkbox(
		array(
			'id'      => 'lc_show_navigation',
			'name'    => __( 'Navigation', 'logo-carousel-free' ),
			'desc'    => __( 'Check to show navigation arrows.', 'logo-carousel-free' ),
			'default' => 'on',
		)
	);
	$this->metaboxform->color(
		array(
			'id'      => 'lc_nav_arrow_color',
			'type'    => 'color',
			'name'    => __( 'Navigation Color	', 'logo-carousel-free' ),
			'desc'    => __( 'Pick a color for navigation arrows.', 'logo-carousel-free' ),
			'default' => '#afafaf',
		)
	);
	$this->metaboxform->checkbox(
		array(
			'id'      => 'lc_show_pagination_dots',
			'name'    => __( 'Pagination Dots', 'logo-carousel-free' ),
			'desc'    => __( 'Check to show pagination dots.', 'logo-carousel-free' ),
			'default' => 'on',
		)
	);
	$this->metaboxform->color(
		array(
			'id'      => 'lc_pagination_color',
			'type'    => 'color',
			'name'    => __( 'Pagination Color	', 'logo-carousel-free' ),
			'desc'    => __( 'Pick a color for pagination dots.', 'logo-carousel-free' ),
			'default' => '#ddd',
		)
	);
	$this->metaboxform->number(
		array(
			'id'      => 'lc_scroll_speed',
			'name'    => __( 'Pagination Speed.', 'logo-carousel-free' ),
			'desc'    => __( 'Set pagination/slide scroll speed in millisecond.', 'logo-carousel-free' ),
			'after'   => __( '(ms).', 'logo-carousel-free' ),
			'default' => 450,
		)
	);
	$this->metaboxform->checkbox(
		array(
			'id'      => 'lc_touch_swipe',
			'name'    => __( 'Touch Swipe', 'logo-carousel-free' ),
			'desc'    => __( 'Check to on touch swipe.', 'logo-carousel-free' ),
			'default' => 'on',
		)
	);
	$this->metaboxform->checkbox(
		array(
			'id'      => 'lc_mouse_draggable',
			'name'    => __( 'Mouse Draggable', 'logo-carousel-free' ),
			'desc'    => __( 'Check to on mouse draggable.', 'logo-carousel-free' ),
			'default' => 'on',
		)
	);
	$this->metaboxform->checkbox(
		array(
			'id'      => 'lc_logo_rtl',
			'name'    => __( 'RTL Mode', 'logo-carousel-free' ),
			'desc'    => __( 'Check and Set a RTL language from admin settings to make the rtl option work.', 'logo-carousel-free' ),
			'default' => 'off',
		)
	);
	?>
</div>
