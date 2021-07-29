<?php
/**
 * This file display meta box tab
 *
 * @package logo-carousel-free
 */

$current_screen        = get_current_screen();
$the_current_post_type = $current_screen->post_type;
if ( $the_current_post_type == 'sp_lc_shortcodes' ) {
	global $post;
	?>
	<div class="wpl-metabox-framework">
		<div class="sp_lc_shortcode_header">
			<div class="sp_lc_shortcode_header_logo">
				<img src="<?php echo SP_LC_URL . 'admin/assets/images/lc-logo.svg'; ?>" alt="Logo Carousel">
			</div>
			<div class="sp_lc_shortcode_header_support">
				<a href="https://shapedplugin.com/support/?user=lite" target="_blank"><i
						class="fa fa-support"></i><span>Support</span></a>
			</div>
		</div>
		<div class="lc_shortcode text-center">
			<div class="lc-col-lg-6">
			<div class="lc-after-copy-text"><i class="fa fa-check-circle"></i> <?php _e( 'Shortcode  Copied to Clipboard! ', 'logo-carousel-free' ); ?>  </div>
				<div class="lc_shortcode_content">
					<h2 class="lc-shortcode-title"><?php _e( 'Shortcode', 'logo-carousel-free' ); ?> </h2>
					<p><?php _e( 'Copy and paste this shortcode into your posts or pages:', 'logo-carousel-free' ); ?></p>
					<div class="shortcode-wrap">
							<img class="lc-copy-btn" src="<?php echo SP_LC_URL; ?>admin/assets/images/copy.svg">
							<div class="lc-sc-code selectable" >[logocarousel <?php echo 'id="' . $post->ID . '"'; ?>]</div>
						</div>
				</div>
			</div>
			<div class="lc-col-lg-6">
			<div class="lc-after-copy-text"><i class="fa fa-check-circle"></i> <?php _e( 'Shortcode  Copied to Clipboard! ', 'logo-carousel-free' ); ?>  </div>
				<div class="lc_shortcode_content">
					<h2 class="lc-shortcode-title"><?php _e( 'Template Include', 'logo-carousel-free' ); ?> </h2>
					<p><?php _e( 'Paste the PHP code into your template file:', 'logo-carousel-free' ); ?></p>
					<div class="shortcode-wrap">
						<img class="lc-copy-btn" src="<?php echo SP_LC_URL; ?>admin/assets/images/copy.svg">
						<div class="lc-sc-code selectable">
							&lt;?php echo do_shortcode('[logocarousel id="<?php echo $post->ID; ?>"]'); ?&gt;</div>
						</div>
				</div>
			</div>
		</div>

		<div class="splc-shortcode-body">
			<div class="wplmb-nav nav-tab-wrapper current">
				<a class="nav-tab nav-tab-active" data-tab="splc-tab-1"><i class="sp-icon fa fa-cog"></i>General
					Settings</a>
				<a class="nav-tab" data-tab="splc-tab-2"><i class="sp-icon fa fa-sliders"></i>Carousel Controls</a>
				<a class="nav-tab" data-tab="splc-tab-3"><i class="sp-icon fa fa-paint-brush"></i>Style Settings</a>
				<a class="nav-tab" data-tab="splc-tab-4"><i class="sp-icon fa fa-font"></i>Typography</a>
				<a class="nav-tab" data-tab="splc-tab-5 "><i class="sp-icon fa fa-rocket"></i>Upgrade to Pro</a>
			</div>
			<?php
			include_once 'partials/general-settings.php';
			include_once 'partials/carousel-settings.php';
			include_once 'partials/stylization.php';
			include_once 'partials/typography.php';
			include_once 'partials/upgrade-to-pro.php';
			?>
			<div class="splc-nav-background"></div>
		</div>

	</div>
	<?php
}
