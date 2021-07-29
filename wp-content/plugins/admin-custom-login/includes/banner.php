<?php

if ( ! defined( 'ABSPATH' ) ) exit;
$acl_imgpath = WEBLIZAR_NALF_PLUGIN_URL."images/aclp.png";
$acl_bg_imgpath =  WEBLIZAR_NALF_PLUGIN_URL."images/bg.jpg";
?>
<div class="wb_plugin_feature notice  is-dismissible">
	<div class="wb_plugin_feature_banner default_pattern pattern_ ">
	<div class="wb-col-md-6 wb-col-sm-12 wb-text-center institute_banner_img">
	<h2> <?php esc_html_e(' Admin Custom Login Pro ', WEBLIZAR_ACL)?></h2>
		<img class="wp-img-responsive" src="<?php echo esc_url($acl_imgpath); ?>" alt="img">
	</div>
		<div class="wb-col-md-6 wb-col-sm-12 wb_banner_featurs-list"><?php esc_html_e('', WEBLIZAR_ACL)?>
			<span><h2><?php esc_html_e('Admin Custom Login Pro Features', WEBLIZAR_ACL)?></h2></span>
			<ul>
				<li> <?php esc_html_e('Max Login Retry', WEBLIZAR_ACL)?></li>
				<li> <?php esc_html_e('Login With Access Token', WEBLIZAR_ACL)?></li>
				<li> <?php esc_html_e('Freeze Login Form On Brute Force Attack', WEBLIZAR_ACL)?></li>
				<li> <?php esc_html_e('Unfreeze Login Form By Admin', WEBLIZAR_ACL)?></li>
				<li> <?php esc_html_e('Social Media Login', WEBLIZAR_ACL)?></li>
				<li> <?php esc_html_e('Login Restriction By User Roles', WEBLIZAR_ACL)?></li>					
				<li> <?php esc_html_e('Ban User', WEBLIZAR_ACL)?>(s) <?php esc_html_e('Login Access', WEBLIZAR_ACL)?></li>
				<li> <?php esc_html_e('Max User Access Management', WEBLIZAR_ACL)?></li>
				<li> <?php esc_html_e('Restrict Unauthorized IP', WEBLIZAR_ACL)?></li>
				<li> <?php esc_html_e('Import Export Settings', WEBLIZAR_ACL)?></li>
				<li> <?php esc_html_e('Login Form Logo', WEBLIZAR_ACL)?></li>
				<li> <?php esc_html_e('Redirect Users After Login', WEBLIZAR_ACL)?></li>
				<li> <?php esc_html_e('Google reCpatcha', WEBLIZAR_ACL)?></li>
				
			</ul>
			<div class="wp_btn-grup">
				<a class="wb_button-primary"  href="http://demo.weblizar.com/admin-custom-login-pro/wp-login.php" target="_blank"><?php esc_html_e('View Demo', WEBLIZAR_ACL)?></a>
				<a class="wb_button-primary" href="https://weblizar.com/plugins/admin-custom-login-pro/" target="_blank"><?php esc_html_e('Buy Now $25', WEBLIZAR_ACL)?></a>
			</div>
			<div class="plugin_vrsion"> <span> <b><?php esc_html_e(' 5.8 ', WEBLIZAR_ACL)?></b><?php esc_html_e('Version', WEBLIZAR_ACL)?></span> 
			</div>
		</div>
</div>
</div>
