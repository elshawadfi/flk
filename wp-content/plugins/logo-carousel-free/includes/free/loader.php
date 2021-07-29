<?php

/**
 * The Free Loader Class
 *
 * @package logo-carousel-free
 * @since 3.0
 */
class SPLC_Free_Loader {

	/*
	 * Free Loader constructor
	 */
	function __construct() {
		add_action( 'admin_menu', array( $this, 'admin_menu' ) );
		require_once SP_LC_PATH . 'public/views/shortcoderender.php';
		require_once SP_LC_PATH . 'admin/wpl-mce-button/button.php';
	}

	/**
	 * Admin Menu
	 */
	function admin_menu() {
		add_submenu_page( 'edit.php?post_type=sp_logo_carousel', __( 'Logo Carousel Pro', 'logo-carousel-free' ), __( 'Premium', 'logo-carousel-free' ), 'manage_options', 'lc_upgrade', array( $this, 'upgrade_page_callback' ) );
		add_submenu_page( 'edit.php?post_type=sp_logo_carousel', __( 'Logo Carousel Help', 'logo-carousel-free' ), __( 'Help', 'logo-carousel-free' ), 'manage_options', 'lc_help', array( $this, 'help_page_callback' ) );
	}

	/**
	 * Upgrade Page Callback
	 */
	public function upgrade_page_callback() {
		?>
		<div class="wrap about-wrap sp-lc-help sp-lc-upgrade">
			<h1><?php _e( 'Upgrade to <span>Logo Carousel Pro</span>', 'logo-carousel-free' ); ?></h1>
			<p class="about-text">
			<?php
			esc_html_e(
				'Get more Advanced Functionality & Flexibility with the Premium version.',
				'logo-carousel-free'
			);
			?>
			</p>
			<div class="wp-badge"></div>
			<ul>
				<li class="lc-upgrade-btn"><a href="https://shapedplugin.com/plugin/logo-carousel-pro/?ref=1" target="_blank">Buy Logo Carousel Pro <img src="data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0idXRmLTgiPz4KPHN2ZyB3aWR0aD0iMTc5MiIgaGVpZ2h0PSIxNzkyIiB2aWV3Qm94PSIwIDAgMTc5MiAxNzkyIiBmaWxsPSIjZmZmIiB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciPjxwYXRoIGQ9Ik0xMTUyIDg5NnEwIDI2LTE5IDQ1bC00NDggNDQ4cS0xOSAxOS00NSAxOXQtNDUtMTktMTktNDV2LTg5NnEwLTI2IDE5LTQ1dDQ1LTE5IDQ1IDE5bDQ0OCA0NDhxMTkgMTkgMTkgNDV6Ii8+PC9zdmc+" alt="" style="max-width: 15px;"/></a></li>
				<li class="lc-upgrade-btn"><a href="https://demo.shapedplugin.com/logo-carousel/" target="_blank">Live Demo & All Features <img src="data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0idXRmLTgiPz4KPHN2ZyB3aWR0aD0iMTUiIGhlaWdodD0iMTUiIHZpZXdCb3g9IjAgMCAxNzkyIDE3OTIiIGZpbGw9IiMwMDczYWEiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyI+PHBhdGggZD0iTTk3OSA5NjBxMCAxMy0xMCAyM2wtNDY2IDQ2NnEtMTAgMTAtMjMgMTB0LTIzLTEwbC01MC01MHEtMTAtMTAtMTAtMjN0MTAtMjNsMzkzLTM5My0zOTMtMzkzcS0xMC0xMC0xMC0yM3QxMC0yM2w1MC01MHExMC0xMCAyMy0xMHQyMyAxMGw0NjYgNDY2cTEwIDEwIDEwIDIzem0zODQgMHEwIDEzLTEwIDIzbC00NjYgNDY2cS0xMCAxMC0yMyAxMHQtMjMtMTBsLTUwLTUwcS0xMC0xMC0xMC0yM3QxMC0yM2wzOTMtMzkzLTM5My0zOTNxLTEwLTEwLTEwLTIzdDEwLTIzbDUwLTUwcTEwLTEwIDIzLTEwdDIzIDEwbDQ2NiA0NjZxMTAgMTAgMTAgMjN6Ii8+PC9zdmc+" alt="" style="max-width: 15px;"/></a></li>
			</ul>

			<hr>

			<div class="sp-lc-pro-features">
				<h2 class="sp-lc-text-center">Premium Features You'll Love</h2>
				<p class="sp-lc-text-center sp-lc-pro-subtitle">We've added 250+ extra features in our Premium Version of this plugin. Let’s see some amazing features.</p>
				<div class="feature-section three-col">
					<div class="col">
						<div class="sp-lc-feature">
							<h3><span class="dashicons dashicons-yes"></span>Responsive & Touch Ready</h3>
							<p>All layouts are responsive with touch-friendly on any devices, and thoroughly tested & optimized for best performance. Logo Carousel Pro performs speedily on all sites.</p>
						</div>
					</div>
					<div class="col">
						<div class="sp-lc-feature">
							<h3><span class="dashicons dashicons-yes"></span>Compatible with any Themes</h3>
							<p>Guaranteed to work with your any WordPress site including Genesis, Divi, WooThemes, ThemeForest or any theme, in any WordPress single site and multisite network.</p>
						</div>
					</div>
					<div class="col">
						<div class="sp-lc-feature">
							<h3><span class="dashicons dashicons-yes"></span>Advanced Shortcode Generator</h3>
							<p>Logo Carousel Pro comes with a built-in Shortcode Generator to control easily the look and settings of the logo showcase. Save, edit, copy and paste shortcode where you want!</p>
						</div>
					</div>
					<div class="col">
						<div class="sp-lc-feature">
							<h3><span class="dashicons dashicons-yes"></span>Design Without Writing CSS</h3>
							<p>There are unlimited stunning styling options like color, font family, size, alignment etc. to stylize your own way without any limitation. No Coding Skills Needed!</p>
						</div>
					</div>
					<div class="col">
						<div class="sp-lc-feature">
							<h3><span class="dashicons dashicons-yes"></span>5 Logo Layouts (Carousel, Grid, Filter, List, Inline)</h3>
							<p>With Logo Carousel Pro, You can display a set of logo images in 5 beautiful layouts: Carousel Slider, Grid, List, Filter, and Inline. All the layouts are completely customizable.</p>
						</div>
					</div>
					<div class="col">
						<div class="sp-lc-feature">
							<h3><span class="dashicons dashicons-yes"></span>840+ Google Fonts</h3>
							<p>Add your desired font family from 840+ Google Fonts library. Customize the font family, size, transform, letter spacing, color, alignment, and line-height for each logo showcase.</p>
						</div>
					</div>


					<div class="col">
						<div class="sp-lc-feature">
							<h3><span class="dashicons dashicons-yes"></span>Drag & Drop Logo ordering</h3>
							<p>Drag & Drop Logo ordering is one of the amazing features of Logo Carousel Pro. You can order your logos easily by drag & drop feature and also order by date, title, random etc.</p>
						</div>
					</div>
					<div class="col">
						<div class="sp-lc-feature">
							<h3><span class="dashicons dashicons-yes"></span>Group Logo Showcase</h3>
							<p>Manage your logos by grouping into separate categories based on your demand. Create an unlimited category for logos and display logos from particular or selected categories.</p>
						</div>
					</div>
					<div class="col">
						<div class="sp-lc-feature">
							<h3><span class="dashicons dashicons-yes"></span>Internal & External Logo Links</h3>
							<p>You can set URLs to them, they can have links that can open on the same page or on a new page. If you don’t add any URL for the particular logo, the logo will not be linked up.</p>
						</div>
					</div>
					<div class="col">
						<div class="sp-lc-feature">
							<h3><span class="dashicons dashicons-yes"></span>Isotope Filtering by Category</h3>
							<p>Group your logo images by categories and display only a selected category or all of them! This way you can even have a list for clients, other lists for sponsors, and so on!</p>
						</div>
					</div>
					<div class="col">
						<div class="sp-lc-feature">
							<h3><span class="dashicons dashicons-yes"></span>Live Category Filter (Opacity)</h3>
							<p>In the Grid layouts you can also include a live category filter, so your visitors can select which logos to see. An opacity will be on the logo and change opacity you need.</p>
						</div>
					</div>
					<div class="col">
						<div class="sp-lc-feature">
							<h3><span class="dashicons dashicons-yes"></span>Carousel Mode</h3>
							<p>Logo Carousel Pro has three(3) carousel mode: Standard, Ticker (Smooth looping, with no pause), and Center. You can change the carousel mode based on your choice or demand.</p>
						</div>
					</div>
					<div class="col">
						<div class="sp-lc-feature">
							<h3><span class="dashicons dashicons-yes"></span>Vertical and Horizontal Direction</h3>
							<p>The plugin has both Horizontal and Vertical carousel direction. By default Horizontal direction mode is enabled. The Vertical direction is an amazing feature for the plugin.</p>
						</div>
					</div>
					<div class="col">
						<div class="sp-lc-feature">
							<h3><span class="dashicons dashicons-yes"></span>Logo Display Options</h3>
							<p>Showcase your logo images with Tooltips, Title, Description and CTA button (Read more). You can also set easily the logo title and tooltips positions from settings.</p>
						</div>
					</div>
					<div class="col">
						<div class="sp-lc-feature">
							<h3><span class="dashicons dashicons-yes"></span>Popup View for Logo Detail</h3>
							<p>Display logo details like Logo, Title, Description etc. in a Popup view. Make your logo showcase visually appealing popup full view and customize the popup content easily.</p>
						</div>
					</div>
					<div class="col">
						<div class="sp-lc-feature">
							<h3><span class="dashicons dashicons-yes"></span>Logo Effects on Hover</h3>
							<p>We have set different logo image hover effects like, GrayScale, Zoom In, Zoom out, Blur, Opacity etc. that are both edgy and appealing. Try them all. Use the one you like best.</p>
						</div>
					</div>
					<div class="col">
						<div class="sp-lc-feature">
							<h3><span class="dashicons dashicons-yes"></span>Multiple Logo Row</h3>
							<p>With the Premium Version, you can add and slide the unlimited number of rows at a time in carousel layout. We normally set single row by default. Set number of rows based on your choice.</p>
						</div>
					</div>
					<div class="col">
						<div class="sp-lc-feature">
							<h3><span class="dashicons dashicons-yes"></span>Tooltips Settings & Highlight</h3>
							<p>You can choose to display tooltips or not, positions, width, effects, background etc. Simply stylize logo background, border, box-shadow, and display on hover highlight of the image.</p>
						</div>
					</div>
					<div class="col">
						<div class="sp-lc-feature">
							<h3><span class="dashicons dashicons-yes"></span>Custom Logo Re-sizing</h3>
							<p>You can change the default size of your logo images on the settings. Set width or height from settings. New uploaded images will be resized to the specified dimensions.</p>
						</div>
					</div>
					<div class="col">
						<div class="sp-lc-feature">
							<h3><span class="dashicons dashicons-yes"></span>Multilingual Ready</h3>
							<p>Logo Carousel Pro is fully multilingual ready with WPML, Polylang, qTranslate-x, GTranslate, Google Language Translator, WPGlobus etc. popular translation plugins.</p>
						</div>
					</div>
					<div class="col">
						<div class="sp-lc-feature">
							<h3><span class="dashicons dashicons-yes"></span>Multi-site Supported</h3>
							<p>One of the important features of Logo Carousel Pro is Multi-site ready. The Premium version works great in the multi-site network.</p>
						</div>
					</div>
					<div class="col">
						<div class="sp-lc-feature">
							<h3><span class="dashicons dashicons-yes"></span>Widget Ready</h3>
							<p>To include logo carousel or grid inside a widget area is as simple as including any other widget! The plugin is widget ready. Create a shortcode first and use it simply in the widget.</p>
						</div>
					</div>
					<div class="col">
						<div class="sp-lc-feature">
							<h3><span class="dashicons dashicons-yes"></span>Custom CSS to Override Styles</h3>
							<p>Logo Carousel Pro is completely customizable and also added a custom CSS field option to override styles, if necessary without editing the CSS files. It’s easy enough!</p>
						</div>
					</div>
					<div class="col">
						<div class="sp-lc-feature">
							<h3><span class="dashicons dashicons-yes"></span>Enqueue or Dequeue Scripts/CSS</h3>
							<p>We have set advanced options to disable and enable Scripts or CSS files. This advanced settings fields will help you avoid conflicts and loading issue.</p>
						</div>
					</div>
					<div class="col">
						<div class="sp-lc-feature">
							<h3><span class="dashicons dashicons-yes"></span>Fast and Friendly Support</h3>
							<p>A fully dedicated and expert support team is ready to help you instantly whenever you face with any issues to configure or use the plugin. We love helping our customers.</p>
						</div>
					</div>
					<div class="col">
						<div class="sp-lc-feature">
							<h3><span class="dashicons dashicons-yes"></span>Lifetime Automatic Updates</h3>
							<p>Logo Carousel Pro is integrated with automatic updates which allows you to update the plugin through the WordPress dashboard without downloading them manually.</p>
						</div>
					</div>
					<div class="col">
						<div class="sp-lc-feature">
							<h3><span class="dashicons dashicons-yes"></span>Page Builders Ready</h3>
							<p>The plugin is carefully crafted and tested with the popular Page Builders plugins: Gutenberg, WPBakery, Elementor, Divi builder, BeaverBuilder, SiteOrgin etc.</p>
						</div>
					</div>

				</div>
			</div>
			<hr>					
			<h2 class="sp-lc-text-center sp-lc-promo-video-title">Watch How <b>Logo Carousel Pro</b> Works</h2>
				<div class="headline-feature feature-video">

				<iframe width="1050" height="590" src="https://www.youtube.com/embed/2R16tCBOw-s" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
				</div>
				<hr>
				<div class="sp-lc-join-community sp-lc-text-center">
					<h2>Join the <b>20000+</b> Happy Users Worldwide!</h2>
					<a class="lc-upgrade-btn" target="_blank" href="https://shapedplugin.com/plugin/logo-carousel-pro/?ref=1">Get a license instantly</a>
					<p>Every purchase comes with <b>14-day</b> money back guarantee and access to our incredibly Top-notch Support with lightening-fast response time and 100% satisfaction rate. One-Time payment, lifetime automatic update.</p>
				</div>
				<br>
				<br>

				<hr>
				<div class="sp-lc-upgrade-sticky-footer sp-lc-text-center">
					<p><a href="https://demo.shapedplugin.com/logo-carousel/" target="_blank" class="button
					button-primary">Live Demo</a> <a href="https://shapedplugin.com/plugin/logo-carousel-pro/?ref=1" target="_blank" class="button button-primary">Upgrade Now</a></p>
				</div>

			</div>
		<?php
	}

	/**
	 * Help Page Callback
	 */
	public function help_page_callback() {
		?>
		<div class="wrap about-wrap sp-lc-help">
			<h1><?php _e( 'Welcome to Logo Carousel!', 'logo-carousel-free' ); ?></h1>
			<p class="about-text"><?php _e( 'Thank you for installing Logo Carousel! You\'re now running the most popular Logo Carousel plugin. This video will help you get started with the plugin.', 'logo-carousel-free' ); ?></p>
			<div class="wp-badge"></div>
			<hr>

			<div class="headline-feature feature-video">
				<iframe width="560" height="315" src="https://www.youtube.com/embed/nWuTLgmAzd0" frameborder="0" allow="autoplay; encrypted-media" allowfullscreen></iframe>
			</div>

			<hr>

			<div class="feature-section three-col">
				<div class="col">
					<div class="sp-lc-feature sp-lc-text-center">
						<img class="sp-lc-font-icon" src="data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0idXRmLTgiPz4KPHN2ZyB3aWR0aD0iMTc5MiIgaGVpZ2h0PSIxNzkyIiB2aWV3Qm94PSIwIDAgMTc5MiAxNzkyIiBmaWxsPSIjMWRhYjg3IiB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciPjxwYXRoIGQ9Ik04OTYgMHExODIgMCAzNDggNzF0Mjg2IDE5MSAxOTEgMjg2IDcxIDM0OC03MSAzNDgtMTkxIDI4Ni0yODYgMTkxLTM0OCA3MS0zNDgtNzEtMjg2LTE5MS0xOTEtMjg2LTcxLTM0OCA3MS0zNDggMTkxLTI4NiAyODYtMTkxIDM0OC03MXptMCAxMjhxLTE5MCAwLTM2MSA5MGwxOTQgMTk0cTgyLTI4IDE2Ny0yOHQxNjcgMjhsMTk0LTE5NHEtMTcxLTkwLTM2MS05MHptLTY3OCAxMTI5bDE5NC0xOTRxLTI4LTgyLTI4LTE2N3QyOC0xNjdsLTE5NC0xOTRxLTkwIDE3MS05MCAzNjF0OTAgMzYxem02NzggNDA3cTE5MCAwIDM2MS05MGwtMTk0LTE5NHEtODIgMjgtMTY3IDI4dC0xNjctMjhsLTE5NCAxOTRxMTcxIDkwIDM2MSA5MHptMC0zODRxMTU5IDAgMjcxLjUtMTEyLjV0MTEyLjUtMjcxLjUtMTEyLjUtMjcxLjUtMjcxLjUtMTEyLjUtMjcxLjUgMTEyLjUtMTEyLjUgMjcxLjUgMTEyLjUgMjcxLjUgMjcxLjUgMTEyLjV6bTQ4NC0yMTdsMTk0IDE5NHE5MC0xNzEgOTAtMzYxdC05MC0zNjFsLTE5NCAxOTRxMjggODIgMjggMTY3dC0yOCAxNjd6Ii8+PC9zdmc+" alt=""/>
						<h3>Need any Assistance?</h3>
						<p>Our Expert Support Team is always ready to help you out promptly.</p>
						<a href="https://shapedplugin.com/support/?user=lite" target="_blank" class="button button-primary">Contact Support</a>
					</div>
				</div>
				<div class="col">
					<div class="sp-lc-feature sp-lc-text-center">
						<img class="sp-lc-font-icon" src="data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0idXRmLTgiPz4KPHN2ZyB3aWR0aD0iMTc5MiIgaGVpZ2h0PSIxNzkyIiB2aWV3Qm94PSIwIDAgMTc5MiAxNzkyIiBmaWxsPSIjMWRhYjg3IiB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciPjxwYXRoIGQ9Ik0xNTk2IDQ3NnExNCAxNCAyOCAzNmgtNDcydi00NzJxMjIgMTQgMzYgMjh6bS00NzYgMTY0aDU0NHYxMDU2cTAgNDAtMjggNjh0LTY4IDI4aC0xMzQ0cS00MCAwLTY4LTI4dC0yOC02OHYtMTYwMHEwLTQwIDI4LTY4dDY4LTI4aDgwMHY1NDRxMCA0MCAyOCA2OHQ2OCAyOHptMTYwIDczNnYtNjRxMC0xNC05LTIzdC0yMy05aC03MDRxLTE0IDAtMjMgOXQtOSAyM3Y2NHEwIDE0IDkgMjN0MjMgOWg3MDRxMTQgMCAyMy05dDktMjN6bTAtMjU2di02NHEwLTE0LTktMjN0LTIzLTloLTcwNHEtMTQgMC0yMyA5dC05IDIzdjY0cTAgMTQgOSAyM3QyMyA5aDcwNHExNCAwIDIzLTl0OS0yM3ptMC0yNTZ2LTY0cTAtMTQtOS0yM3QtMjMtOWgtNzA0cS0xNCAwLTIzIDl0LTkgMjN2NjRxMCAxNCA5IDIzdDIzIDloNzA0cTE0IDAgMjMtOXQ5LTIzeiIvPjwvc3ZnPg==" alt="">
						<h3>Looking for Documentation?</h3>
						<p>We have detailed documentation on every aspects of Logo Carousel.</p>
						<a href="https://docs.shapedplugin.com/docs/logo-carousel/introduction/" target="_blank" class="button button-primary">Documentation</a>
					</div>
				</div>
				<div class="col">
					<div class="sp-lc-feature sp-lc-text-center">
						<img class="sp-lc-font-icon" src="data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0idXRmLTgiPz4KPHN2ZyB3aWR0aD0iMTc5MiIgaGVpZ2h0PSIxNzkyIiB2aWV3Qm94PSIwIDAgMTc5MiAxNzkyIiBmaWxsPSIjMWRhYjg3IiB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciPjxwYXRoIGQ9Ik0zMjAgMTM0NHEwLTI2LTE5LTQ1dC00NS0xOXEtMjcgMC00NS41IDE5dC0xOC41IDQ1cTAgMjcgMTguNSA0NS41dDQ1LjUgMTguNXEyNiAwIDQ1LTE4LjV0MTktNDUuNXptMTYwLTUxMnY2NDBxMCAyNi0xOSA0NXQtNDUgMTloLTI4OHEtMjYgMC00NS0xOXQtMTktNDV2LTY0MHEwLTI2IDE5LTQ1dDQ1LTE5aDI4OHEyNiAwIDQ1IDE5dDE5IDQ1em0xMTg0IDBxMCA4Ni01NSAxNDkgMTUgNDQgMTUgNzYgMyA3Ni00MyAxMzcgMTcgNTYgMCAxMTctMTUgNTctNTQgOTQgOSAxMTItNDkgMTgxLTY0IDc2LTE5NyA3OGgtMTI5cS02NiAwLTE0NC0xNS41dC0xMjEuNS0yOS0xMjAuNS0zOS41cS0xMjMtNDMtMTU4LTQ0LTI2LTEtNDUtMTkuNXQtMTktNDQuNXYtNjQxcTAtMjUgMTgtNDMuNXQ0My0yMC41cTI0LTIgNzYtNTl0MTAxLTEyMXE2OC04NyAxMDEtMTIwIDE4LTE4IDMxLTQ4dDE3LjUtNDguNSAxMy41LTYwLjVxNy0zOSAxMi41LTYxdDE5LjUtNTIgMzQtNTBxMTktMTkgNDUtMTkgNDYgMCA4Mi41IDEwLjV0NjAgMjYgNDAgNDAuNSAyNCA0NSAxMiA1MCA1IDQ1IC41IDM5cTAgMzgtOS41IDc2dC0xOSA2MC0yNy41IDU2cS0zIDYtMTAgMTh0LTExIDIyLTggMjRoMjc3cTc4IDAgMTM1IDU3dDU3IDEzNXoiLz48L3N2Zz4=" alt="">
						<h3>Like This Plugin?</h3>
						<p>If you like Logo Carousel, please leave us a 5 star rating.</p>
						<a href="https://wordpress.org/support/plugin/logo-carousel-free/reviews/?filter=5#new-post" target="_blank" class="button button-primary">Rate the Plugin</a>
					</div>
				</div>
			</div>

		</div>
		<?php
	}

}

new SPLC_Free_Loader();
