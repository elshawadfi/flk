<?php

/*******************************************************************************
 *
 *  Copyrights 2017 to Present - Sellergize Web Technology Services Pvt. Ltd. - ALL RIGHTS RESERVED
 *
 * All information contained herein is, and remains the
 * property of Sellergize Web Technology Services Pvt. Ltd.
 *
 * The intellectual and technical concepts & code contained herein are proprietary
 * to Sellergize Web Technology Services Pvt. Ltd. (India), and are covered and protected
 * by copyright law. Reproduction of this material is strictly forbidden unless prior
 * written permission is obtained from Sellergize Web Technology Services Pvt. Ltd.
 * 
 * ******************************************************************************/
 
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

function couponapi_save_import_config() {
	
	if( wp_verify_nonce($_POST['feed_config_nonce'], 'couponapi') ) {

		global $wpdb;
		$wp_prefix = $wpdb->prefix;
		$cashback = (sanitize_text_field($_POST['cashback']) == 'on'? 'On':'Off');
		$import_images = (sanitize_text_field($_POST['import_images']) == 'on'? 'On':'Off');

		$sql = "REPLACE INTO ".$wp_prefix."couponapi_config (name,value) VALUES
							('cashback','$cashback'),
							('import_images','$import_images')";
		if($wpdb->query($sql) === false) {
			$message .= '<div class="notice notice-error is-dismissible"><p>'.$wpdb->last_error.'</p></div>';
		} else {
			$message .= '<div class="notice notice-success is-dismissible"><p>Import Settings saved successfully.</p></div>';
		}
		
	} else {
		$message .= '<div class="notice notice-error is-dismissible"><p>Access Denied. Nonce could not be verified.</p></div>';
	}
	
	setcookie('message',$message);
	wp_redirect('admin.php?page=couponapi&tab=import-settings');
	exit;
}

?>
