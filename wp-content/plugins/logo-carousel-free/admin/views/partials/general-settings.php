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

<div id="splc-tab-1" class="sp-lc-tab-content nav-tab-active">

	<?php
	$this->metaboxform->select_layout(
		array(
			'id'      => 'lc_logo_layout',
			'name'    => __( 'Layout', 'logo-carousel-free' ),
			'desc'    => __( 'Select your layout to display the logos.', 'logo-carousel-free' ),
			'default' => 'carousel',
		)
	);
	$this->metaboxform->display_logos(
		array(
			'id'      => 'lc_display_logos',
			'name'    => __( 'Filter Logos', 'logo-carousel-free' ),
			'desc'    => __( 'Select an option to display by filtering logos.', 'logo-carousel-free' ),
			'default' => 'latest',
		)
	);
	$this->metaboxform->number(
		array(
			'id'      => 'lc_number_of_total_logos',
			'name'    => __( 'Total Logos to Show', 'logo-carousel-free' ),
			'desc'    => __( 'Number of total logos to show.', 'logo-carousel-free' ),
			'default' => 15,
		)
	);
	$this->metaboxform->number(
		array(
			'id'      => 'lc_number_of_column',
			'name'    => __( 'Column(s) in Large Desktop', 'logo-carousel-free' ),
			'desc'    => __( 'Set number of column(s) in large desktop.', 'logo-carousel-free' ),
			'default' => 5,
		)
	);
	$this->metaboxform->number(
		array(
			'id'      => 'lc_number_of_column_dt',
			'name'    => __( 'Column(s) in Desktop', 'logo-carousel-free' ),
			'desc'    => __( 'Set number of column(s) in desktop.', 'logo-carousel-free' ),
			'default' => 5,
		)
	);

	$this->metaboxform->number(
		array(
			'id'      => 'lc_number_of_column_smdt',
			'name'    => __( 'Column(s) in Tablet', 'logo-carousel-free' ),
			'desc'    => __( 'Set number of column(s) in tablet.', 'logo-carousel-free' ),
			'default' => 4,
		)
	);
	$this->metaboxform->number(
		array(
			'id'      => 'lc_number_of_column_tablet',
			'name'    => __( 'Column(s) in Mobile Landscape', 'logo-carousel-free' ),
			'desc'    => __( 'Set number of column(s) in mobile landscape.', 'logo-carousel-free' ),
			'default' => 3,
		)
	);
	$this->metaboxform->number(
		array(
			'id'      => 'lc_number_of_column_mobile',
			'name'    => __( 'Column(s) in Mobile', 'logo-carousel-free' ),
			'desc'    => __( 'Set number of column(s) in mobile.', 'logo-carousel-free' ),
			'default' => 2,
		)
	);
	$this->metaboxform->select(
		array(
			'id'      => 'lc_logos_order_by',
			'name'    => __( 'Order By', 'logo-carousel-free' ),
			'desc'    => __( 'Select an order by option.', 'logo-carousel-free' ),
			'options' => array(
				'title' => __( 'Title', 'logo-carousel-free' ),
				'date'  => __( 'Date', 'logo-carousel-free' ),
			),
			'default' => 'date',
		)
	);
	$this->metaboxform->select(
		array(
			'id'      => 'lc_logos_order',
			'name'    => __( 'Order', 'logo-carousel-free' ),
			'desc'    => __( 'Select an order option.', 'logo-carousel-free' ),
			'options' => array(
				'ASC'  => __( 'Ascending', 'logo-carousel-free' ),
				'DESC' => __( 'Descending', 'logo-carousel-free' ),
			),
			'default' => 'descending',
		)
	);

	?>

</div>
