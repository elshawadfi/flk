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

function couponapi_delete_offers() {
	
	global $wpdb;
	$wp_prefix = $wpdb->prefix;
	
	$count_new = $count_suspended = $count_updated = 0;
	
	wp_defer_term_counting( true );
	$wpdb->query( 'SET autocommit = 0;' );
	
	$coupons = $wpdb->get_results("SELECT post_id FROM  ".$wp_prefix."postmeta WHERE meta_key = 'offer_id'");
	
	foreach($coupons as $coupon) {	
			$post_id = $coupon->post_id;
			wp_delete_post($post_id,true);
			$count_suspended = $count_suspended + 1;
	}
		
	$wpdb->query("DELETE FROM ".$wp_prefix."couponapi_upload");
	
	wp_defer_term_counting( false );
	$wpdb->query( 'COMMIT;' );
	$wpdb->query( 'SET autocommit = 1;' );
	
	$wpdb->query("INSERT INTO ".$wp_prefix."couponapi_logs (microtime,msg_type,message) VALUES (".microtime(true).",'success','All Offers imported from CouponAPI have been dropped.')");
	
	$message .= '<div class="notice notice-success is-dismissible"><p>Dropped '.$count_suspended.' offers.</p></div>';

	return $message;
	
}
	
?>
