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

<div id="splc-tab-3" class="sp-lc-tab-content">
	<?php
	$this->metaboxform->checkbox(
		array(
			'id'      => 'lc_logo_border',
			'name'    => __( 'Logo Border', 'logo-carousel-free' ),
			'desc'    => __( 'Check to show logo border.', 'logo-carousel-free' ),
			'default' => 'on',
		)
	);

	$this->metaboxform->color(
		array(
			'id'      => 'lc_brand_color',
			'type'    => 'color',
			'name'    => __( 'Brand Color	', 'logo-carousel-free' ),
			'desc'    => __( 'Brand/Main color includes all hover & active color of the carousel.', 'logo-carousel-free' ),
			'default' => '#16a08b',
		)
	);

	?>
</div>
