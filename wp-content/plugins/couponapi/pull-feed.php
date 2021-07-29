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
global $coupons_to_be_inserted; 
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

function couponapi_pull_feed() {
	
	set_time_limit(0);

	global $wpdb;
	$wp_prefix = $wpdb->prefix;
	
	$config = $wpdb->get_row("SELECT
																	(SELECT value FROM ".$wp_prefix."couponapi_config WHERE name = 'API_KEY') API_KEY,
																	(SELECT value FROM ".$wp_prefix."couponapi_config WHERE name = 'last_extract') last_extract
																FROM dual");

	if(empty($config->API_KEY)) {
		wp_clear_scheduled_hook('couponapi_pull_feed_event');
		return '<div class="notice notice-error is-dismissible"><p>Cannot pull feed without API Key.</p></div>';
	}
	
	if(empty($config->last_extract)) { $config->last_extract = '978307200'; }

	$feedFile = "https://couponapi.org/api/getFeed/?API_KEY=".$config->API_KEY."&incremental=1&last_extract=".$config->last_extract."&format=json";
	
	$sql = "INSERT INTO ".$wp_prefix."couponapi_logs (microtime,msg_type,message) VALUES (".microtime(true).",'info','Pulling Feed using Coupon API')";
	$wpdb->query($sql);
	
	$sql = "INSERT INTO ".$wp_prefix."couponapi_logs (microtime,msg_type,message) VALUES (".microtime(true).",'debug','$feedFile')";
	$wpdb->query($sql);
	
	$wpdb->query( 'SET autocommit = 0;' );
	
	$result = couponapi_save_json_to_db($feedFile);

	if($result['totalCounter'] == 0) {
		// If the account is temporarily inactive, we do not get any offers in the file.
		// Not updating the last_extract time in such situations, prevents loss of data after re-activation.
		$wpdb->query( 'SET autocommit = 1;' );
		$wpdb->query("INSERT INTO ".$wp_prefix."couponapi_logs (microtime,msg_type,message) VALUES (".microtime(true).",'success','No updates found in this extract')");
		return '<div class="notice notice-info is-dismissible"><p>No updates found in this extract.</p></div>';
	} elseif(!$result['error']) {
		$wpdb->query("REPLACE INTO ".$wp_prefix."couponapi_config (name,value) VALUES ('last_extract','".time()."') ");
		$wpdb->query( 'COMMIT;' );
		$wpdb->query( 'SET autocommit = 1;' );
		$wpdb->query("INSERT INTO ".$wp_prefix."couponapi_logs (microtime,msg_type,message) VALUES (".microtime(true).",'info','Starting upload process. This may take several minutes...') ");
		wp_schedule_single_event( time() , 'couponapi_process_batch_event'); // process next batch
		return '<div class="notice notice-info is-dismissible"><p>Upload process is running in background. Refresh Logs to see current status.</p></div>';
	} else {
		$wpdb->query( 'ROLLBACK' );
		$wpdb->query( 'SET autocommit = 1;' );
		$wpdb->query("INSERT INTO ".$wp_prefix."couponapi_logs (microtime,msg_type,message) VALUES
											(".microtime(true).",'debug','".esc_sql($result['error_msg'])."'),
											(".microtime(true).",'error','Error uploading feed to local database')");
		return '<div class="notice notice-error is-dismissible"><p>Error uploading feed to local database.</p></div>';									
	}

}

function couponapi_save_json_to_db($feedURL) {
	global $coupons_to_be_inserted;
	$coupons_to_be_inserted = array();
	
	global $wpdb;
	$wp_prefix = $wpdb->prefix;
	
	$sql = "INSERT INTO ".$wp_prefix."couponapi_logs (microtime,msg_type,message) VALUES (".microtime(true).",'info','Preparing to Save to DB')";
	$wpdb->query($sql);

	$result = array();
	$totalCounter = 0;
	
	$response = json_decode( file_get_contents($feedURL) , true);

	if(!$response['result']){
		
		$result['error'] = true;
		$result['error_msg'] = $response['error'];  
		return $result;

	} else {
		
		$coupons = $response['offers']; //gets all the coupons in array
		
		foreach($coupons as $id => $coupon){			//coupon as key=>value array
			$result = couponapi_save_coupon_to_queue($coupon);
			if($result['error']){
				return $result;
			}
			$totalCounter++; //keeps track of total coupons
		}
		
		$result = couponapi_insert_coupons_to_db();			
		if($result['error']){
			return $result;
		}	
		$result['totalCounter'] = $totalCounter;

	} 
    return 	$result;
}

function couponapi_save_csv_to_db($feedFile) {
	global $coupons_to_be_inserted;
	global $wpdb;

	$coupons_to_be_inserted = array(); //initialize the queue
	$wp_prefix = $wpdb->prefix;

	$sql = "INSERT INTO ".$wp_prefix."couponapi_logs (microtime,msg_type,message) VALUES (".microtime(true).",'info','Preparing to Save to DB')";
	$wpdb->query($sql);

	$result = array();
	$totalCounter = 0;

	if (($handle = fopen($feedFile, 'r')) === FALSE){
		
		$result['error'] = true;
		$result['error_msg'] = "cannot open".$feedFile."file";  
		return $result;

	} else { // $feedFile is set by API or File Upload
		
		$topheader = fgetcsv($handle, 10000, ','); //gets the header (key)
		$topheader_db = array('offer_id', 'title', 'description', 'code', 'featured', 'source', 'url', 'deeplink', 'affiliate_link', 
				'cashback_link', 'image_url', 'type', 'store', 'merchant_home_page', 'categories', 'start_date', 'end_date', 'status');
		$topheader_diff = array_diff($topheader_db,$topheader);
		if(!empty($topheader_diff)){
		
			$result['error'] = true;
			$result['error_msg'] = "header error - missing colums (" .implode(",",$topheader_diff).")";  
			return $result;
		
		}else{
					
			while (($row = fgetcsv($handle, 10000, ',')) !== FALSE) {

				$coupon = array_combine($topheader, $row); //coupon as key=>value array

				if(empty($coupon['offer_id'])){
					$result['error'] = true;
					$result['error_msg'] = "offer_id missing for coupon number ".($totalCounter+1)." (row number ".($totalCounter+2).")";  
					return $result;			
				}
				if(empty($coupon['title'])){
					$result['error'] = true;
					$result['error_msg'] = "title missing for coupon number ".($totalCounter+1)." (row number ".($totalCounter+2).")";  
					return $result;				
				}
				if(!empty($coupon['start_date']) AND empty(strtotime($coupon['start_date']))){
					$result['error'] = true;
					$result['error_msg'] = "invalid start date for coupon number ".($totalCounter+1)." (row number ".($totalCounter+2).")";  
					return $result;
				}
				if(!empty($coupon['end_date']) AND empty(strtotime($coupon['end_date']))){
					$result['error'] = true;
					$result['error_msg'] = "invalid end date for coupon number ".($totalCounter+1)." (row number ".($totalCounter+2).")";  
					return $result;
				}

				$result  = couponapi_save_coupon_to_queue($coupon);
				if($result['error']){
					return $result;
				}
				$totalCounter++;	//keeps track of total coupons
			}
			$result = couponapi_insert_coupons_to_db();			
			if($result['error']){
				return $result;
			}	
			$result['totalCounter'] = $totalCounter;
		}
	} 
    return 	$result;
}

function couponapi_save_coupon_to_queue($coupon){
	
	global $coupons_to_be_inserted;
	array_push($coupons_to_be_inserted, $coupon);
	$result = array();
	
	if(count($coupons_to_be_inserted) >= 500) { //Fire Query to save coupons to db if no. of coupons >500
		$result = couponapi_insert_coupons_to_db();
		if($result['error']){
			return $result;
		}
	}
	return $result;
}


function couponapi_insert_coupons_to_db(){
	global $coupons_to_be_inserted;
	global $wpdb;
	
	$wp_prefix = $wpdb->prefix;

	$result = array();
	if(count($coupons_to_be_inserted) === 0) { 
		return $result;
	}
	
	$sql_insert ="INSERT INTO `".$wp_prefix."couponapi_upload` (`offer_id`, `title`, `description`, `code`, `featured`, `source`, `url`, `deeplink`, `affiliate_link`, `cashback_link`, `image_url`, `type`, `store`, `merchant_home_page`, `categories`, `start_date`, `end_date`, `status`) VALUES ";
	$sep = '';
	
	foreach($coupons_to_be_inserted as $coupon){
		$sql_insert .= $sep."(".$coupon['offer_id'].",
									'".esc_sql($coupon['title'])."',
									'".esc_sql($coupon['description'])."',
									'".esc_sql($coupon['code'])."',
									'".esc_sql($coupon['featured'])."',
									'".esc_sql($coupon['source'])."',
									'".esc_sql($coupon['url'])."',
									'".esc_sql($coupon['deeplink'])."',
									'".esc_sql($coupon['affiliate_link'])."',
									'".esc_sql($coupon['cashback_link'])."',
									'".esc_sql($coupon['image_url'])."',
									'".esc_sql($coupon['type'])."',
									'".esc_sql($coupon['store'])."',
									'".esc_sql($coupon['merchant_home_page'])."',
									'".esc_sql($coupon['categories'])."',
									".( empty($coupon['start_date']) ? 'NULL' : "'".date('Y-m-d',strtotime($coupon['start_date']))."'" ).",
									".( empty($coupon['end_date']) ? 'NULL' : "'".date('Y-m-d',strtotime($coupon['end_date']))."'" ).",
									'".esc_sql($coupon['status'])."')";
		$sep = ',';
	}

	if($wpdb->query($sql_insert) === false) {
		
		$result['error'] = true;
		$result['error_msg'] = $wpdb->last_error . PHP_EOL . 'Query: ' . $sql_insert;
		
		$wpdb->print_error();
		$wpdb->query("INSERT INTO ".$wp_prefix."couponapi_logs (microtime,msg_type,message) VALUES (".microtime(true).",'debug','".esc_sql($sql_insert)."')");
		$wpdb->query("INSERT INTO ".$wp_prefix."couponapi_logs (microtime,msg_type,message) VALUES (".microtime(true).",'error','".esc_sql($result['error_msg'])."')");
		return $result;	

	} else {
		
		$coupons_to_be_inserted = array(); //reset coupon array
		return $result;
	}
}


function couponapi_process_batch() {
	global $wpdb;

	$wp_prefix = $wpdb->prefix;

	$theme = get_template();
	$themeSupport  = TRUE;
	
	$config = array();
	$result = $wpdb->get_results("SELECT * FROM ".$wp_prefix."couponapi_config WHERE name IN ('import_images','cashback')");
	
	foreach($result as $row){
    	$config[$row->name] = $row->value;
    }

	if(empty($batchSize)) {
		$batchSize = 500;
	}
		
	wp_defer_term_counting( true );
	$wpdb->query( 'SET autocommit = 0;' );
	
	$coupons = $wpdb->get_results("SELECT * FROM ".$wp_prefix."couponapi_upload ORDER BY upload_date LIMIT 0,".$batchSize);

	if($theme == 'clipmydeals') {
		couponapi_clipmydeals_process_batch($config, $coupons);
	} elseif($theme == 'clipper') {
		couponapi_clipper_process_batch($config, $coupons);
	} elseif($theme == 'couponxl') {
		couponapi_couponxl_process_batch($config, $coupons);
	} elseif($theme == 'couponxxl') {
		couponapi_couponxxl_process_batch($config, $coupons);
	} elseif($theme == 'rehub' OR $theme == 'rehub-theme') {
		couponapi_rehub_process_batch($config, $coupons);
	} elseif($theme == 'wpcoupon' OR $theme == 'wp-coupon') {
		couponapi_wpcoupon_process_batch($config, $coupons);
	} elseif($theme == 'CP' OR $theme == 'cp' OR $theme == 'CPq' OR substr($theme,0,2)==="CP") {
		couponapi_couponpress_process_batch($config, $coupons);
	} else{
		$wpdb->query("INSERT INTO ".$wp_prefix."couponapi_logs (microtime,msg_type,message) VALUES (".microtime(true).",'error','This theme ($theme) is not supported.')");
		$themeSupport = FALSE;
	}
		
	wp_defer_term_counting( false );
	$wpdb->query( 'COMMIT;' );
	$wpdb->query( 'SET autocommit = 1;' );
	
	if($themeSupport){	
		$remainingCoupons = $wpdb->get_var("SELECT count(1) FROM ".$wp_prefix."couponapi_upload");
		if($remainingCoupons > 0) {
			wp_schedule_single_event( time() , 'couponapi_process_batch_event'); // process next batch
		} else {
			$wpdb->query("DELETE FROM ".$wp_prefix."couponapi_logs WHERE logtime < CURDATE() - INTERVAL 30 DAY");
			$wpdb->query("INSERT INTO ".$wp_prefix."couponapi_logs (microtime,msg_type,message) VALUES (".microtime(true).",'success','All offers processed successfully.')");
		}
	}
	
}


function couponapi_clipmydeals_process_batch($config, $coupons) {
	global $wpdb;
	$wp_prefix = $wpdb->prefix;

	$categories=array();
	$categoryTerms = get_terms(array(
		'taxonomy' => 'offer_categories',
		'hide_empty' => false
	));
	foreach($categoryTerms as $term) {
		$categories[$term->name] = $term->slug;
	}
	
	$stores=array();
	$storeTerms = get_terms(array(
		'taxonomy' => 'stores',
		'hide_empty' => false
	));
	foreach($storeTerms as $term) {
		$stores[$term->name] = $term->slug;
	}
	
	$count_new = $count_suspended = $count_updated = 0;
	$found_count = (count($coupons) > 0) ? count($coupons) : 0;
	
	$wpdb->query("INSERT INTO ".$wp_prefix."couponapi_logs (microtime,msg_type,message) VALUES (".microtime(true).",'info','Found $found_count coupons to process')");
	
	foreach($coupons as $coupon) {
		
		if($coupon->status == 'new' or $coupon->status == '') {
	
			$wpdb->query("INSERT INTO ".$wp_prefix."couponapi_logs (microtime,msg_type,message) VALUES (".microtime(true).",'debug','Adding New Coupon (".$coupon->offer_id.")')");
			
			$post_data = array(
				'ID'             => '',
				'post_title'     => $coupon->title,
				'post_content'   => $coupon->description,
				'post_status'    => 'publish',
				'post_type'      => 'coupons',
				'post_author'    => get_current_user_id()
			);
			
			$post_id = wp_insert_post($post_data,$wp_error);
	
			if (strpos($coupon->categories, ',') !== FALSE) {
				$cat_names = explode(',',$coupon->categories);
				$append = false;
				foreach($cat_names as $cat) {
					wp_set_object_terms($post_id, $cat, 'offer_categories', $append);
					$append = true;
				}
			} else {
				wp_set_object_terms($post_id, $coupon->categories, 'offer_categories', true);
			}
			
			if (strpos($coupon->store, ',') !== FALSE) {
				$str_names = explode(',',$coupon->store);
				$append = false;
				foreach($str_names as $str) {
					if(! array_key_exists( $str, $stores)){					
						
						// Create New Store
						$term = wp_insert_term($str, 'stores'); // , $args third parameter
						$stores[$str] = get_term( $term['term_id'], "stores" )->slug;

						// Update Meta Info
						$meta_args = array ("store_url"	=> $coupon->merchant_home_page); //store taxonomy args in wp_options
						update_option( "taxonomy_term_".$term['term_id'], $meta_args );	
						
					}
					wp_set_object_terms($post_id, $str, 'stores', $append);
					$append = true;
				}
			} else {
				if(! array_key_exists( $coupon->store, $stores)){
					
					// Create New Store
					$term = wp_insert_term($coupon->store, 'stores'); // , $args third parameter
					$stores[$coupon->store] = get_term( $term['term_id'], "stores" )->slug;

					// Update Meta Info
					$meta_args = array ("store_url"	=> $coupon->merchant_home_page); //store taxonomy args in wp_options
					update_option( "taxonomy_term_".$term['term_id'], $meta_args );
					
				}
				wp_set_object_terms($post_id, $coupon->store, 'stores', true);
			}
			
			update_post_meta($post_id, 'offer_id', $coupon->offer_id);
			update_post_meta($post_id, 'cmd_type', ($coupon->type == 'Code' ? 'code' : 'deal'));
			update_post_meta($post_id, 'cmd_code', $coupon->code);
			
			if($config['cashback']=='On') {
				update_post_meta($post_id, 'cmd_url', str_replace("{{replace_userid_here}}","[click_id]",$coupon->cashback_link));
			} else {
				update_post_meta($post_id, 'cmd_url', $coupon->affiliate_link);
			}
			
			if($config['import_images']=='On') {
				update_post_meta($post_id, 'cmd_image_url', $coupon->image_url);
			}
			update_post_meta($post_id, 'cmd_start_date', (empty($coupon->start_date) ? '' : $coupon->start_date));
			if(empty($coupon->end_date)) {
				update_post_meta($post_id, 'cmd_valid_till', '');
			} else{
				update_post_meta($post_id, 'cmd_valid_till', $coupon->end_date);
			}
			update_post_meta($post_id, 'cmd_display_priority', 0);
			
			$count_new = $count_new + 1;
			
		} elseif($coupon->status == 'updated') {
			
			$wpdb->query("INSERT INTO ".$wp_prefix."couponapi_logs (microtime,msg_type,message) VALUES (".microtime(true).",'debug','Updating Coupon (".$coupon->offer_id.")')");
	
			$offer_id = $coupon->offer_id;
			$sql_id = "SELECT post_id FROM ".$wp_prefix."postmeta WHERE meta_key = 'offer_id' AND meta_value = '$offer_id' LIMIT 0,1";
			$post_id = $wpdb->get_var($sql_id);
			
			$post_data = array(
				'ID'             => $post_id,
				'post_title'     => $coupon->title,
				'post_content'   => $coupon->description,
				'post_status'    => 'publish',
				'post_author'    => get_current_user_id()
			);
					
			wp_update_post($post_data);
			
			if (strpos($coupon->categories, ',') !== FALSE) {
				$cat_names = explode(',',$coupon->categories);
				$append = false;
				foreach($cat_names as $cat) {
					wp_set_object_terms($post_id, $cat, 'offer_categories', $append);
					$append = true;
				}
			} else {
				wp_set_object_terms($post_id, $coupon->categories, 'offer_categories', false);
			}
			
			if (strpos($coupon->store, ',') !== FALSE) {
				$str_names = explode(',',$coupon->store);
				$append = false;
				foreach($str_names as $str) {
					wp_set_object_terms($post_id, $str, 'stores', $append);
					$append = true;
				}
			} else {
				wp_set_object_terms($post_id, $coupon->store, 'stores', false);
			}
			
			update_post_meta($post_id, 'cmd_type', ($coupon->type == 'Code' ? 'code' : 'deal'));
			update_post_meta($post_id, 'cmd_code', $coupon->code);
			if($config['cashback']=='On') {
				update_post_meta($post_id, 'cmd_url', str_replace("{{replace_userid_here}}","[click_id]",$coupon->cashback_link));
			} else {
				update_post_meta($post_id, 'cmd_url', $coupon->affiliate_link);
			}
			if($config['import_images']=='On') {
				update_post_meta($post_id, 'cmd_image_url', $coupon->image_url);
			}
			update_post_meta($post_id, 'cmd_start_date', (empty($coupon->start_date) ? '' : $coupon->start_date));
			if(empty($coupon->end_date)) {
				update_post_meta($post_id, 'cmd_valid_till', '');
			} else{
				update_post_meta($post_id, 'cmd_valid_till', $coupon->end_date);
			}
			update_post_meta($post_id, 'cmd_display_priority', 0);
			
			$count_updated = $count_updated + 1;
			
		} elseif($coupon->status == 'suspended') {
			
			$wpdb->query("INSERT INTO ".$wp_prefix."couponapi_logs (microtime,msg_type,message) VALUES (".microtime(true).",'debug','Suspending Coupon (".$coupon->offer_id.")')");
	
			$offer_id = $coupon->offer_id;
			$sql_id = "SELECT post_id FROM ".$wp_prefix."postmeta WHERE meta_key = 'offer_id' AND meta_value = '$offer_id' LIMIT 0,1";
			$post_id = $wpdb->get_var($sql_id);
			
			wp_delete_post($post_id,true);
	
			$count_suspended = $count_suspended + 1;
			
		}
		
		$wpdb->query("DELETE FROM ".$wp_prefix."couponapi_upload WHERE offer_id = ".$coupon->offer_id);
		
		
	}
	
	$wpdb->query("INSERT INTO ".$wp_prefix."couponapi_logs (microtime,msg_type,message) VALUES (".microtime(true).",'info','Processed Offers - $count_new New , $count_updated Updated , $count_suspended Suspended.')");
	
}


function couponapi_clipper_process_batch($config, $coupons) {
	global $wpdb;
	$wp_prefix = $wpdb->prefix;

	$categories=array();
	$categoryTerms = get_terms(array(
		'taxonomy' => 'coupon_category',
		'hide_empty' => false
	));
	foreach($categoryTerms as $term) {
		$categories[$term->name] = $term->slug;
	}
	
	$stores=array();
	$storeTerms = get_terms(array(
		'taxonomy' => 'stores',
		'hide_empty' => false
	));
	foreach($storeTerms as $term) {
		$stores[$term->name] = $term->slug;
	}

	$count_new = $count_suspended = $count_updated = 0;
	$found_count = (count($coupons) > 0) ? count($coupons) : 0;
	
	$wpdb->query("INSERT INTO ".$wp_prefix."couponapi_logs (microtime,msg_type,message) VALUES (".microtime(true).",'info','Found $found_count coupons to process')");
	
	foreach($coupons as $coupon) {
		
		if($coupon->status == 'new' or $coupon->status == '') {
	
			$wpdb->query("INSERT INTO ".$wp_prefix."couponapi_logs (microtime,msg_type,message) VALUES (".microtime(true).",'debug','Adding New Coupon (".$coupon->offer_id.")')");
			
			$post_data = array(
				'ID'             => '',
				'post_title'     => $coupon->title,
				'post_content'   => $coupon->description,
				'post_status'    => 'publish',
				'post_type'      => 'coupon',
				'post_author'    => get_current_user_id()	
			);
			
			$post_id = wp_insert_post($post_data,$wp_error);
	
			if (strpos($coupon->category, ',') !== FALSE) {
				$cat_names = explode(',',$coupon->categories);
				foreach($cat_names as $cat) {
					wp_set_object_terms($post_id, $cat, 'coupon_category', true);
					wp_set_object_terms($post_id, $cat, 'coupon_tag', true);
				}
			} else {
				wp_set_object_terms($post_id, $coupon->categories, 'coupon_category', true);
				wp_set_object_terms($post_id, $coupon->categories, 'coupon_tag', true);
			}
			
			if (strpos($coupon->store, ',') !== FALSE) {
				$store_names = explode(',',$coupon->store);
				foreach($store_names as $str) {
					wp_set_object_terms($post_id, $str, 'stores', true);
				}
			} else {
				wp_set_object_terms($post_id, $coupon->store, 'stores', true);
			}
			
			wp_set_object_terms($post_id, ($coupon->type == 'Code' ? 'coupon-code' : 'deal'), 'coupon_type', true);
			
			update_post_meta($post_id, 'offer_id', $coupon->offer_id);
			update_post_meta($post_id, 'clpr_coupon_aff_url', $coupon->affiliate_link);
			update_post_meta($post_id, 'clpr_coupon_code', $coupon->code);
			if(!empty($coupon->end_date)) {
				update_post_meta($post_id, 'clpr_expire_date', $coupon->end_date);
			}
			update_post_meta($post_id, 'clpr_featured', $coupon->featured);
			update_post_meta($post_id, 'clpr_votes_percent', '100');
			update_post_meta($post_id, 'clpr_coupon_aff_clicks', '0');
			
			$count_new = $count_new + 1;
			
		} elseif($coupon->status == 'updated') {
			
			$wpdb->query("INSERT INTO ".$wp_prefix."couponapi_logs (microtime,msg_type,message) VALUES (".microtime(true).",'debug','Updating Coupon (".$coupon->offer_id.")')");
	
			$offer_id = $coupon->offer_id;
			$sql_id = "SELECT post_id FROM ".$wp_prefix."postmeta WHERE meta_key = 'offer_id' AND meta_value = '$offer_id' LIMIT 0,1";
			$post_id = $wpdb->get_var($sql_id);
			
			$post_data = array(
				'ID'             => $post_id,
				'post_title'     => $coupon->title,
				'post_content'   => $coupon->description,
				'post_status'    => 'publish',
				'post_author'    => get_current_user_id()
			);
					
			wp_update_post($post_data);
			
			if (strpos($coupon->category, ',') !== FALSE) {
				$cat_names = explode(',',$coupon->category);
				$append = false;
				foreach($cat_names as $cat) {
					wp_set_object_terms($post_id, $cat, 'coupon_category', $append);
					wp_set_object_terms($post_id, $cat, 'coupon_tag', $append);
					$append = true;
				}
			} else {
				wp_set_object_terms($post_id, $coupon->category, 'coupon_category', false);
				wp_set_object_terms($post_id, $coupon->category, 'coupon_tag', false);
			}
	
			if (strpos($coupon->store, ',') !== FALSE) {
				$store_names = explode(',',$coupon->store);
				$append = false;
				foreach($store_names as $str) {
					wp_set_object_terms($post_id, $str, 'stores', $append);
					$append = true;
				}
			} else {
				wp_set_object_terms($post_id, $coupon->store, 'stores', false);
			}
			
			wp_set_object_terms($post_id, ($coupon->type == 'Code' ? 'coupon-code' : 'deal'), 'coupon_type', false);
			
			update_post_meta($post_id, 'clpr_coupon_aff_url', $coupon->affiliate_link);
			update_post_meta($post_id, 'clpr_coupon_code', $coupon->code);
			if(!empty($coupon->end_date)) {
				update_post_meta($post_id, 'clpr_expire_date', $coupon->end_date);
			}
			update_post_meta($post_id, 'clpr_featured', $coupon->featured);
			
			$count_updated = $count_updated + 1;
			
		} elseif($coupon->status == 'suspended') {
			
			$wpdb->query("INSERT INTO ".$wp_prefix."couponapi_logs (microtime,msg_type,message) VALUES (".microtime(true).",'debug','Suspending Coupon (".$coupon->offer_id.")')");
	
			$offer_id = $coupon->offer_id;
			$sql_id = "SELECT post_id FROM ".$wp_prefix."postmeta WHERE meta_key = 'offer_id' AND meta_value = '$offer_id' LIMIT 0,1";
			$post_id = $wpdb->get_var($sql_id);
			
			wp_delete_post($post_id,true);
	
			$count_suspended = $count_suspended + 1;
			
		}

		$wpdb->query("DELETE FROM ".$wp_prefix."couponapi_upload WHERE offer_id = ".$coupon->offer_id);
		
	}
	
	$wpdb->query("INSERT INTO ".$wp_prefix."couponapi_logs (microtime,msg_type,message) VALUES (".microtime(true).",'info','Processed Offers - $count_new New , $count_updated Updated , $count_suspended Suspended.')");
		
}


function couponapi_couponxl_process_batch($config, $coupons) {
	global $wpdb;
	$wp_prefix = $wpdb->prefix;

	$categories=array();
	$categoryTerms = get_terms(array(
		'taxonomy' => 'offer_cat',
		'hide_empty' => false
	));
	foreach($categoryTerms as $term) {
		$categories[$term->name] = $term->slug;
	}
	
	$stores=array();
	$sql_stores = "SELECT ID,post_title FROM ".$wp_prefix."posts WHERE post_type = 'store' ";
	$result_stores = $wpdb->get_results($sql_stores);
	foreach($result_stores as $str) {
		$stores[$str->ID] = $str->post_title;
	}
	
	$count_new = $count_suspended = $count_updated = 0;
	$found_count = (count($coupons) > 0) ? count($coupons) : 0;

	$wpdb->query("INSERT INTO ".$wp_prefix."couponapi_logs (microtime,msg_type,message) VALUES (".microtime(true).",'info','Found $found_count coupons to process')");
	
	foreach($coupons as $coupon) {
		
		if($coupon->status == 'new' or $coupon->status == '') {
	
			$wpdb->query("INSERT INTO ".$wp_prefix."couponapi_logs (microtime,msg_type,message) VALUES (".microtime(true).",'debug','Adding New Coupon (".$coupon->offer_id.")')");
			
			$post_data = array(
				'ID'             => '',
				'post_title'     => $coupon->title,
				'post_content'   => $coupon->description,
				'post_status'    => 'publish',
				'post_type'      => 'offer',
				'post_author'    => get_current_user_id()
			);
			
			$post_id = wp_insert_post($post_data,$wp_error);
	
			if (strpos($coupon->categories, ',') !== FALSE) {
				$cat_names = explode(',',$coupon->categories);
				foreach($cat_names as $cat) {
					wp_set_object_terms($post_id, $cat, 'offer_cat', true);
				}
			} else {
				wp_set_object_terms($post_id, $coupon->categories, 'offer_cat', true);
			}
			
			if($coupon->store_id!=0 and $coupon->store_id!='') {
				update_post_meta($post_id, 'offer_store', $coupon->store_id);
			} elseif(array_search($coupon->store,$stores)) {
				update_post_meta($post_id, 'offer_store', array_search($coupon->store,$stores));
			} else {
				$store_data = array(
					'ID'             => '',
					'post_title'     => $coupon->store,
					'post_status'    => 'publish',
					'post_type'      => 'store',
					'post_author'    => get_current_user_id()
				);
				$store_id = wp_insert_post($store_data,$wp_error);
				$stores[$store_id] = $coupon->store;
				update_post_meta($post_id, 'offer_store', $store_id);
			}
			
			update_post_meta($post_id, 'offer_id', $coupon->offer_id);
			update_post_meta($post_id, 'coupon_code', $coupon->code);
			update_post_meta($post_id, 'coupon_url', $coupon->url);
			update_post_meta($post_id, 'coupon_link', $coupon->affiliate_link);
			update_post_meta($post_id, 'coupon_sale', $coupon->affiliate_link);
			update_post_meta($post_id, 'offer_start', strtotime($coupon->start_date));
			if(empty($coupon->end_date)) {
				update_post_meta($post_id, 'offer_expire', '99999999999');
			} else{
				update_post_meta($post_id, 'offer_expire', strtotime($coupon->end_date.' + 1 day'));
			}
			update_post_meta($post_id, 'coupon_type', ($coupon->type == 'Code' ? 'code' : 'sale'));
			update_post_meta($post_id, 'offer_clicks', '0');
			update_post_meta($post_id, 'offer_views', '1');
			update_post_meta($post_id, 'offer_in_slider', 'yes');
			update_post_meta($post_id, 'offer_initial_payment', 'paid');
			update_post_meta($post_id, 'deal_type', 'shared');
			update_post_meta($post_id, 'deal_status', 'has_items');
			update_post_meta($post_id, 'offer_type', 'coupon');
			update_post_meta($post_id, 'offer_views', '1');
			
			$count_new = $count_new + 1;
			
		} elseif($coupon->status == 'updated') {
			
			$wpdb->query("INSERT INTO ".$wp_prefix."couponapi_logs (microtime,msg_type,message) VALUES (".microtime(true).",'debug','Updating Coupon (".$coupon->offer_id.")')");
	
			$offer_id = $coupon->offer_id;
			$sql_id = "SELECT post_id FROM ".$wp_prefix."postmeta WHERE meta_key = 'offer_id' AND meta_value = '$offer_id' LIMIT 0,1";
			$post_id = $wpdb->get_var($sql_id);
			
			$post_data = array(
				'ID'             => $post_id,
				'post_title'     => $coupon->title,
				'post_content'   => $coupon->description,
				'post_status'    => 'publish',
				'post_author'    => get_current_user_id()
			);
					
			wp_update_post($post_data);
			
			if (strpos($coupon->categories, ',') !== FALSE) {
				$cat_names = explode(',',$coupon->categories);
				$append = false;
				foreach($cat_names as $cat) {
					wp_set_object_terms($post_id, $cat, 'offer_cat', $append);
					$append = true;
				}
			} else {
				wp_set_object_terms($post_id, $coupon->categories, 'offer_cat', false);
			}
			
			if($coupon->store_id!=0 and $coupon->store_id!='') {
				update_post_meta($post_id, 'offer_store', $coupon->store_id);
			} elseif(array_search($coupon->store,$stores)) {
				update_post_meta($post_id, 'offer_store', array_search($coupon->store,$stores));
			} else {
				$store_data = array(
					'ID'             => '',
					'post_title'     => $coupon->store,
					'post_status'    => 'publish',
					'post_type'      => 'store',
					'post_author'    => get_current_user_id()
				);
				$store_id = wp_insert_post($store_data,$wp_error);
				$stores[$store_id] = $coupon->store;
				update_post_meta($post_id, 'offer_store', $store_id);
			}
			
			update_post_meta($post_id, 'coupon_code', $coupon->code);
			update_post_meta($post_id, 'coupon_url', $coupon->url);
			update_post_meta($post_id, 'coupon_link', $coupon->affiliate_link);
			update_post_meta($post_id, 'coupon_sale', $coupon->affiliate_link);
			update_post_meta($post_id, 'offer_start', strtotime($coupon->start_date));
			if(empty($coupon->end_date)) {
				update_post_meta($post_id, 'offer_expire', '99999999999');
			} else{
				update_post_meta($post_id, 'offer_expire', strtotime($coupon->end_date.' + 1 day'));
			}
			update_post_meta($post_id, 'coupon_type', ($coupon->type == 'Code' ? 'code' : 'sale'));
			
			$count_updated = $count_updated + 1;
			
		} elseif($coupon->status == 'suspended') {
			
			$wpdb->query("INSERT INTO ".$wp_prefix."couponapi_logs (microtime,msg_type,message) VALUES (".microtime(true).",'debug','Suspending Coupon (".$coupon->offer_id.")')");
	
			$offer_id = $coupon->offer_id;
			$sql_id = "SELECT post_id FROM ".$wp_prefix."postmeta WHERE meta_key = 'offer_id' AND meta_value = '$offer_id' LIMIT 0,1";
			$post_id = $wpdb->get_var($sql_id);
			
			wp_delete_post($post_id,true);
	
			$count_suspended = $count_suspended + 1;
			
		}

		$wpdb->query("DELETE FROM ".$wp_prefix."couponapi_upload WHERE offer_id = ".$coupon->offer_id);
		
	}
	
	$wpdb->query("INSERT INTO ".$wp_prefix."couponapi_logs (microtime,msg_type,message) VALUES (".microtime(true).",'info','Processed Offers - $count_new New , $count_updated Updated , $count_suspended Suspended.')");
		
}


function couponapi_couponxxl_process_batch($config, $coupons) {
	global $wpdb;
	$wp_prefix = $wpdb->prefix;

	$categories=array();
	$categoryTerms = get_terms(array(
		'taxonomy' => 'offer_cat',
		'hide_empty' => false
	));
	foreach($categoryTerms as $term) {
		$categories[$term->name] = $term->slug;
	}
	
	$stores=array();
	$sql_stores = "SELECT ID,post_title FROM ".$wp_prefix."posts WHERE post_type = 'store' ";
	$result_stores = $wpdb->get_results($sql_stores);
	foreach($result_stores as $str) {
		$stores[$str->ID] = $str->post_title;
	}
	
	$count_new = $count_suspended = $count_updated = 0;
	$found_count = (count($coupons) > 0) ? count($coupons) : 0;
	
	$wpdb->query("INSERT INTO ".$wp_prefix."couponapi_logs (microtime,msg_type,message) VALUES (".microtime(true).",'info','Found $found_count coupons to process')");
	
	foreach($coupons as $coupon) {
		
		if($coupon->status == 'new' or $coupon->status == '') {
	
			$wpdb->query("INSERT INTO ".$wp_prefix."couponapi_logs (microtime,msg_type,message) VALUES (".microtime(true).",'debug','Adding New Coupon (".$coupon->offer_id.")')");
			
			$post_data = array(
				'ID'             => '',
				'post_title'     => $coupon->title,
				'post_content'   => $coupon->description,
				'post_status'    => 'publish',
				'post_type'      => 'offer',
				'post_author'    => get_current_user_id()
			);
			
			$post_id = wp_insert_post($post_data,$wp_error);
			
			$wpdb->query($wpdb->prepare("INSERT INTO ".$wp_prefix."offers (post_id,offer_type,offer_start,offer_expire,offer_in_slider,offer_has_items,offer_thumbs_recommend,offer_clicks) VALUES (%d,'coupon',%s,%s,'yes','1','1','1')",$post_id,strtotime($coupon->start_date),strtotime($coupon->end_date)));
	
			if (strpos($coupon->categories, ',') !== FALSE) {
				$cat_names = explode(',',$coupon->categories);
				foreach($cat_names as $cat) {
					wp_set_object_terms($post_id, $cat, 'offer_cat', true);
				}
			} else {
				wp_set_object_terms($post_id, $coupon->categories, 'offer_cat', true);
			}
			
			if($coupon->store_id!=0 and $coupon->store_id!='') {
				update_post_meta($post_id, 'offer_store', $coupon->store_id);
			} elseif(array_search($coupon->store,$stores)) {
				update_post_meta($post_id, 'offer_store', array_search($coupon->store,$stores));
			} else {
				$store_data = array(
					'ID'             => '',
					'post_title'     => $coupon->store,
					'post_status'    => 'publish',
					'post_type'      => 'store',
					'post_author'    => get_current_user_id()
				);
				$store_id = wp_insert_post($store_data,$wp_error);
				$stores[$store_id] = $coupon->store;
				update_post_meta($post_id, 'offer_store', $store_id);
			}
			
			update_post_meta($post_id, 'offer_id', $coupon->offer_id);
			update_post_meta($post_id, 'coupon_code', $coupon->code);
			update_post_meta($post_id, 'coupon_link', $coupon->affiliate_link);
			update_post_meta($post_id, 'coupon_sale', $coupon->affiliate_link);
			update_post_meta($post_id, 'coupon_url', $coupon->url);
			update_post_meta($post_id, 'coupon_type', $coupon->type);
			update_post_meta($post_id, 'coupon_type', ($coupon->type == 'Code' ? 'code' : 'sale'));
			update_post_meta($post_id, 'offer_start', strtotime($coupon->start_date));
			if(empty($coupon->end_date)) {
				update_post_meta($post_id, 'offer_expire', '99999999999');
			} else{
				update_post_meta($post_id, 'offer_expire', strtotime($coupon->end_date.' + 1 day'));
			}
			update_post_meta($post_id, 'deal_type', 'shared');
			update_post_meta($post_id, 'offer_thumbs_up','1');
			update_post_meta($post_id, 'offer_thumbs_down', '0');
			update_post_meta($post_id, 'offer_views', '1');
			
			$count_new = $count_new + 1;
			
		} elseif($coupon->status == 'updated') {
			
			$wpdb->query("INSERT INTO ".$wp_prefix."couponapi_logs (microtime,msg_type,message) VALUES (".microtime(true).",'debug','Updating Coupon (".$coupon->offer_id.")')");
	
			$offer_id = $coupon->offer_id;
			$sql_id = "SELECT post_id FROM ".$wp_prefix."postmeta WHERE meta_key = 'offer_id' AND meta_value = '$offer_id' LIMIT 0,1";
			$post_id = $wpdb->get_var($sql_id);
			
			$post_data = array(
				'ID'             => $post_id,
				'post_title'     => $coupon->title,
				'post_content'   => $coupon->description,
				'post_status'    => 'publish'
			);
					
			wp_update_post($post_data);
			
			if (strpos($coupon->categories, ',') !== FALSE) {
				$cat_names = explode(',',$coupon->categories);
				$append = false;
				foreach($cat_names as $cat) {
					wp_set_object_terms($post_id, $cat, 'offer_cat', $append);
					$append = true;
				}
			} else {
				wp_set_object_terms($post_id, $coupon->categories, 'offer_cat', false);
			}
			
			if($coupon->store_id!=0 and $coupon->store_id!='') {
				update_post_meta($post_id, 'offer_store', $coupon->store_id);
			} elseif(array_search($coupon->store,$stores)) {
				update_post_meta($post_id, 'offer_store', array_search($coupon->store,$stores));
			} else {
				$store_data = array(
					'ID'             => '',
					'post_title'     => $coupon->store,
					'post_status'    => 'publish',
					'post_type'      => 'store',
					'post_author'    => get_current_user_id()
				);
				$store_id = wp_insert_post($store_data,$wp_error);
				$stores[$store_id] = $coupon->store;
				update_post_meta($post_id, 'offer_store', $store_id);
			}
			
			$wpdb->query($wpdb->prepare("UPDATE ".$wp_prefix."offers SET
												offer_start=%s,
												offer_expire=%s
											WHERE post_id = %d",strtotime($coupon->start_date),strtotime($coupon->end_date),$post_id));
			
			update_post_meta($post_id, 'coupon_code', $coupon->code);
			update_post_meta($post_id, 'coupon_link', $coupon->affiliate_link);
			update_post_meta($post_id, 'coupon_sale', $coupon->affiliate_link);
			update_post_meta($post_id, 'coupon_url', $coupon->url);
			update_post_meta($post_id, 'coupon_type', ($coupon->type == 'Code' ? 'code' : 'sale'));
			update_post_meta($post_id, 'offer_start', strtotime($coupon->start_date));
			if(empty($coupon->end_date)) {
				update_post_meta($post_id, 'offer_expire', '99999999999');
			} else{
				update_post_meta($post_id, 'offer_expire', strtotime($coupon->end_date.' + 1 day'));
			}
			
			$count_updated = $count_updated + 1;
			
		} elseif($coupon->status == 'suspended') {
			
			$wpdb->query("INSERT INTO ".$wp_prefix."couponapi_logs (microtime,msg_type,message) VALUES (".microtime(true).",'debug','Suspending Coupon (".$coupon->offer_id.")')");
	
			$offer_id = $coupon->offer_id;
			$sql_id = "SELECT post_id FROM ".$wp_prefix."postmeta WHERE meta_key = 'offer_id' AND meta_value = '$offer_id' LIMIT 0,1";
			$post_id = $wpdb->get_var($sql_id);
			
			wp_delete_post($post_id,true);
	
			$count_suspended = $count_suspended + 1;
			
		}
	
		$wpdb->query("DELETE FROM ".$wp_prefix."couponapi_upload WHERE offer_id = ".$coupon->offer_id);
		
	}
	
	$wpdb->query("INSERT INTO ".$wp_prefix."couponapi_logs (microtime,msg_type,message) VALUES (".microtime(true).",'info','Processed Offers - $count_new New , $count_updated Updated , $count_suspended Suspended.')");

}


function couponapi_couponpress_process_batch($config, $coupons) {
	global $wpdb;
	$wp_prefix = $wpdb->prefix;

	$stores=array();
	$storeTerms = get_terms(array(
		'taxonomy' => 'store',
		'hide_empty' => false
	));
	foreach($storeTerms as $term) {
		$stores[$term->name] = $term->slug;
	}
	
	$categories=array();
	$categoryTerms = get_terms(array(
		'taxonomy' => 'listing',
		'hide_empty' => false
	));
	foreach($categoryTerms as $term) {
		$categories[$term->name] = $term->slug;
	}
	
	$count_new = $count_suspended = $count_updated = 0;
	$found_count = (count($coupons) > 0) ? count($coupons) : 0;

	$wpdb->query("INSERT INTO ".$wp_prefix."couponapi_logs (microtime,msg_type,message) VALUES (".microtime(true).",'info','Found $found_count coupons to process')");
	
	foreach($coupons as $coupon) {
		
		if($coupon->status == 'new' or $coupon->status == '') {
	
			$wpdb->query("INSERT INTO ".$wp_prefix."couponapi_logs (microtime,msg_type,message) VALUES (".microtime(true).",'debug','Adding New Coupon (".$coupon->offer_id.")')");
			
			$post_data = array(
				'ID'             => '',
				'post_title'     => $coupon->title,
				'post_content'   => $coupon->description,
				'post_status'    => 'publish',
				'post_type'      => 'listing_type',
				'post_author'    => get_current_user_id()
			);
			
			$post_id = wp_insert_post($post_data,$wp_error);
	
			if (strpos($coupon->categories, ',') !== FALSE) {
				$cat_names = explode(',',$coupon->categories);
				foreach($cat_names as $cat) {
					wp_set_object_terms($post_id, $cat, 'listing', true);
				}
			} else {
				wp_set_object_terms($post_id, $coupon->categories, 'listing', true);
			}
			
			if (strpos($coupon->store, ',') !== FALSE) {
				$store_names = explode(',',$coupon->store);
				foreach($store_names as $str) {
					wp_set_object_terms($post_id, $str, 'store', true);
				}
			} else {
				wp_set_object_terms($post_id, $coupon->store, 'store', true);
			}

			if($coupon->type != 'Code') {
				$ol = get_term_by('name', 'offer', 'ctype');
				if(isset($ol->term_id)){
					wp_set_post_terms( $post_id, array($ol->term_id), 'ctype');
				}
			}
			
			update_post_meta($post_id, 'offer_id', $coupon->offer_id);
			update_post_meta($post_id, 'url', $coupon->url);
			update_post_meta($post_id, 'link', $coupon->affiliate_link);
			update_post_meta($post_id, 'code', $coupon->code);
			update_post_meta($post_id, 'coupon_type', ($coupon->type == 'Code' ? '1' : '3'));
			update_post_meta($post_id, 'coupon_txt', "%");
			update_post_meta($post_id, 'start_date', $coupon->start_date.' 00:00:00');
			if(empty($coupon->end_date)) {
				update_post_meta($post_id, 'expiry_date', '');
				update_post_meta($post_id, 'listing_expiry_date', '');
			} else{
				update_post_meta($post_id, 'expiry_date',  date("Y-m-d H:i:s",strtotime($coupon->end_date.' + 1 day')));
				update_post_meta($post_id, 'listing_expiry_date',  date("Y-m-d H:i:s",strtotime($coupon->end_date.' + 1 day')));
			}
			update_post_meta($post_id, 'featured', $coupon->featured);
			update_post_meta($post_id, 'listing_sticker', 0);
			
			$count_new = $count_new + 1;
			
		} elseif($coupon->status == 'updated') {
			
			$wpdb->query("INSERT INTO ".$wp_prefix."couponapi_logs (microtime,msg_type,message) VALUES (".microtime(true).",'debug','Updating Coupon (".$coupon->offer_id.")')");
	
			$offer_id = $coupon->offer_id;
			$sql_id = "SELECT post_id FROM ".$wp_prefix."postmeta WHERE meta_key = 'offer_id' AND meta_value = '$offer_id' LIMIT 0,1";
			$post_id = $wpdb->get_var($sql_id);
			
			$post_data = array(
				'ID'             => $post_id,
				'post_title'     => $coupon->title,
				'post_content'   => $coupon->description,
				'post_status'    => 'publish',
				'post_author'    => get_current_user_id()
			);
					
			wp_update_post($post_data);
			
			if (strpos($coupon->store, ',') !== FALSE) {
				$post_stores = array();
				$store_names = explode(',',$coupon->store);
				$append = false;
				foreach($store_names as $str) {
					wp_set_object_terms($post_id, $str, 'store', $append);
					$append = true;
				}
			} else {
				wp_set_object_terms($post_id, $coupon->store, 'store', false); 
			}
			
			if (strpos($coupon->categories, ',') !== FALSE) {
				$post_categories = array();
				$cat_names = explode(',',$coupon->categories);
				$append = false;
				foreach($cat_names as $cat) {
					wp_set_object_terms($post_id, $cat, 'listing', $append);
					$append = true;
				}
			} else {
				wp_set_object_terms($post_id, $coupon->categories, 'listing', false);
			}

			if($coupon->type != 'Code') {
				$ol = get_term_by('name', 'offer', 'ctype');
				if(isset($ol->term_id)){
					wp_set_post_terms( $post_id, array($ol->term_id), 'ctype');
				}
			}
			
			update_post_meta($post_id, 'url', $coupon->url);
			update_post_meta($post_id, 'link', $coupon->affiliate_link);
			update_post_meta($post_id, 'code', $coupon->code);
			update_post_meta($post_id, 'coupon_type', ($coupon->type == 'Code' ? '1' : '3'));
			update_post_meta($post_id, 'coupon_txt', "%");
			update_post_meta($post_id, 'start_date', $coupon->start_date." 00:00:00");
			if(empty($coupon->end_date)) {
				update_post_meta($post_id, 'expiry_date', '');
				update_post_meta($post_id, 'listing_expiry_date', '');
			} else{
				update_post_meta($post_id, 'expiry_date',  date("Y-m-d H:i:s",strtotime($coupon->end_date.' + 1 day')));
				update_post_meta($post_id, 'listing_expiry_date',  date("Y-m-d H:i:s",strtotime($coupon->end_date.' + 1 day')));
			}
			update_post_meta($post_id, 'featured', $coupon->featured);
			update_post_meta($post_id, 'listing_sticker', 0);
			
			$count_updated = $count_updated + 1;
			
		} elseif($coupon->status == 'suspended') {
			
			$wpdb->query("INSERT INTO ".$wp_prefix."couponapi_logs (microtime,msg_type,message) VALUES (".microtime(true).",'debug','Suspending Coupon (".$coupon->offer_id.")')");
	
			$offer_id = $coupon->offer_id;
			$sql_id = "SELECT post_id FROM ".$wp_prefix."postmeta WHERE meta_key = 'offer_id' AND meta_value = '$offer_id' LIMIT 0,1";
			$post_id = $wpdb->get_var($sql_id);
			
			wp_delete_post($post_id,true);
	
			$count_suspended = $count_suspended + 1;
			
		}

		$wpdb->query("DELETE FROM ".$wp_prefix."couponapi_upload WHERE offer_id = ".$coupon->offer_id);
		
	}
	
	$wpdb->query("INSERT INTO ".$wp_prefix."couponapi_logs (microtime,msg_type,message) VALUES (".microtime(true).",'info','Processed Offers - $count_new New , $count_updated Updated , $count_suspended Suspended.')");
		
}


function couponapi_rehub_process_batch($config, $coupons) {
	global $wpdb;
	$wp_prefix = $wpdb->prefix;

	$thumbnails=array();
	$sql_thumbnail = "SELECT
						t.slug store,
						(SELECT IFNULL(ID,'') FROM ".$wp_prefix."posts p, ".$wp_prefix."termmeta tm WHERE term_id = tt.term_id AND p.guid = tm.meta_value AND meta_key = 'brandimage') thumbnail_id
					FROM ".$wp_prefix."term_taxonomy tt, ".$wp_prefix."terms t
					WHERE t.term_id = tt.term_id
					AND tt.taxonomy = 'dealstore'";
	$result_thumbnail = $wpdb->get_results($sql_thumbnail);
	foreach($result_thumbnail as $thm) {
		$thumbnails[$thm->store] = $thm->thumbnail_id;
	}
	
	$categories=array();
	$categoryTerms = get_terms(array(
		'taxonomy' => 'category',
		'hide_empty' => false
	));
	foreach($categoryTerms as $term) {
		$categories[$term->name] = $term->slug;
	}
	
	$stores=array();
	$storeTerms = get_terms(array(
		'taxonomy' => 'dealstore',
		'hide_empty' => false
	));
	foreach($storeTerms as $term) {
		$stores[$term->name] = $term->slug;
	}
	
	$count_new = $count_suspended = $count_updated = 0;
	$found_count = (count($coupons) > 0) ? count($coupons) : 0;

	$wpdb->query("INSERT INTO ".$wp_prefix."couponapi_logs (microtime,msg_type,message) VALUES (".microtime(true).",'info','Found $found_count coupons to process')");
	
	foreach($coupons as $coupon) {
		
		if($coupon->status == 'new' or $coupon->status == '') {
	
			$wpdb->query("INSERT INTO ".$wp_prefix."couponapi_logs (microtime,msg_type,message) VALUES (".microtime(true).",'debug','Adding New Coupon (".$coupon->offer_id.")')");
			
			$post_data = array(
				'ID'             => '',
				'post_title'     => $coupon->title,
				'post_content'   => $coupon->description,
				'post_status'    => 'publish',
				'post_type'      => 'post',
				'post_author'    => get_current_user_id()
			);
			
			$post_id = wp_insert_post($post_data,$wp_error);
	
			if (strpos($coupon->categories, ',') !== FALSE) {
				$cat_names = explode(',',$coupon->categories);
				$append = false;
				foreach($cat_names as $cat) {
					wp_set_object_terms($post_id, $cat, 'category', $append);
					$append = true;
				}
			} else {
				wp_set_object_terms($post_id, $coupon->categories, 'category', true);
			}
			
			if (strpos($coupon->store, ',') !== FALSE) {
				$str_names = explode(',',$coupon->store);
				foreach($str_names as $str) {
					wp_set_object_terms($post_id, $str, 'dealstore', true);
				}
			} else {
				wp_set_object_terms($post_id, $coupon->store, 'dealstore', true);
			}
			
			update_post_meta($post_id, 'offer_id', $coupon->offer_id);
			update_post_meta($post_id, 'post_size', 'normal_post');
			update_post_meta($post_id, 'rehub_framework_post_type', 'regular');
			update_post_meta($post_id, 'rehub_offer_clicks_count', '0');
			update_post_meta($post_id, 'rehub_views', '0');
			if(!empty($coupon->end_date)) {
				update_post_meta($post_id, 'rehub_offer_coupon_date', $coupon->end_date);
			}
			update_post_meta($post_id, 'rehub_offer_product_url', $coupon->affiliate_link);
			if(!empty($coupon->code)) {
				update_post_meta($post_id, 'rehub_offer_coupon_mask', '1');
				update_post_meta($post_id, 'rehub_offer_product_coupon', $coupon->code);
			}
			if(!empty($thumbnails[$coupon->store])) {
				update_post_meta($post_id, '_thumbnail_id', $thumbnails[$coupon->store]);
			}
			
			$count_new = $count_new + 1;
			
		} elseif($coupon->status == 'updated') {
			
			$wpdb->query("INSERT INTO ".$wp_prefix."couponapi_logs (microtime,msg_type,message) VALUES (".microtime(true).",'debug','Updating Coupon (".$coupon->offer_id.")')");
	
			$offer_id = $coupon->offer_id;
			$sql_id = "SELECT post_id FROM ".$wp_prefix."postmeta WHERE meta_key = 'offer_id' AND meta_value = '$offer_id' LIMIT 0,1";
			$post_id = $wpdb->get_var($sql_id);
			
			$post_data = array(
				'ID'             => $post_id,
				'post_title'     => $coupon->title,
				'post_content'   => $coupon->description,
				'post_excerpt'   => $coupon->description,
				'post_status'    => 'publish'
			);
					
			wp_update_post($post_data);
			
			if (strpos($coupon->categories, ',') !== FALSE) {
				$cat_names = explode(',',$coupon->categories);
				$append = false;
				foreach($cat_names as $cat) {
					wp_set_object_terms($post_id, $cat, 'category', $append);
					$append = true;
				}
			} else {
				wp_set_object_terms($post_id, $coupon->categories, 'category', false);
			}
			
			if (strpos($coupon->store, ',') !== FALSE) {
				$str_names = explode(',',$coupon->store);
				$append = false;
				foreach($str_names as $str) {
					wp_set_object_terms($post_id, $str, 'dealstore', $append);
					$append = true;
				}
			} else {
				wp_set_object_terms($post_id, $coupon->store, 'dealstore', false);
			}
			
			if(!empty($coupon->end_date)) {
				update_post_meta($post_id, 'rehub_offer_coupon_date', $coupon->end_date);
			}
			update_post_meta($post_id, 'rehub_offer_product_url', $coupon->affiliate_link);
			if(!empty($coupon->code)) {
				update_post_meta($post_id, 'rehub_offer_coupon_mask', '1');
				update_post_meta($post_id, 'rehub_offer_product_coupon', $coupon->code);
			}
			if(!empty($thumbnails[$coupon->store])) {
				update_post_meta($post_id, '_thumbnail_id', $thumbnails[$coupon->store]);
			}
			
			$count_updated = $count_updated + 1;
			
		} elseif($coupon->status == 'suspended') {
			
			$wpdb->query("INSERT INTO ".$wp_prefix."couponapi_logs (microtime,msg_type,message) VALUES (".microtime(true).",'debug','Suspending Coupon (".$coupon->offer_id.")')");
	
			$offer_id = $coupon->offer_id;
			$sql_id = "SELECT post_id FROM ".$wp_prefix."postmeta WHERE meta_key = 'offer_id' AND meta_value = '$offer_id' LIMIT 0,1";
			$post_id = $wpdb->get_var($sql_id);
			
			wp_delete_post($post_id,true);
	
			$count_suspended = $count_suspended + 1;
			
		}

		$wpdb->query("DELETE FROM ".$wp_prefix."couponapi_upload WHERE offer_id = ".$coupon->offer_id);
		
	}
	
	$wpdb->query("INSERT INTO ".$wp_prefix."couponapi_logs (microtime,msg_type,message) VALUES (".microtime(true).",'info','Processed Offers - $count_new New , $count_updated Updated , $count_suspended Suspended.')");

}


function couponapi_wpcoupon_process_batch($config, $coupons) {
	global $wpdb;
	$wp_prefix = $wpdb->prefix;

	$stores=array();
	$storeTerms = get_terms(array(
		'taxonomy' => 'coupon_store',
		'hide_empty' => false
	));
	foreach($storeTerms as $term) {
		$stores[$term->name] = $term->slug;
	}
	
	$categories=array();
	$categoryTerms = get_terms(array(
		'taxonomy' => 'coupon_category',
		'hide_empty' => false
	));
	foreach($categoryTerms as $term) {
		$categories[$term->name] = $term->slug;
	}
	
	
	$count_new = $count_suspended = $count_updated = 0;
	$found_count = (count($coupons) > 0) ? count($coupons) : 0;

	$wpdb->query("INSERT INTO ".$wp_prefix."couponapi_logs (microtime,msg_type,message) VALUES (".microtime(true).",'info','Found $found_count coupons to process')");
	
	foreach($coupons as $coupon) {
		
		if($coupon->status == 'new' or $coupon->status == '') {
	
			$wpdb->query("INSERT INTO ".$wp_prefix."couponapi_logs (microtime,msg_type,message) VALUES (".microtime(true).",'debug','Adding New Coupon (".$coupon->offer_id.")')");
			
			$post_data = array(
				'ID'             => '',
				'post_title'     => $coupon->title,
				'post_content'   => $coupon->description,
				'post_status'    => 'publish',
				'post_type'      => 'coupon',
				'post_author'    => get_current_user_id()
			);
			
			$post_id = wp_insert_post($post_data,$wp_error);
	
			if (strpos($coupon->categories, ',') !== FALSE) {
				$cat_names = explode(',',$coupon->categories);
				foreach($cat_names as $cat) {
					wp_set_object_terms($post_id, $cat, 'coupon_category', true);
				}
			} else {
				wp_set_object_terms($post_id, $coupon->categories, 'coupon_category', true);
			}
			
			if (strpos($coupon->store, ',') !== FALSE) {
				$store_names = explode(',',$coupon->store);
				foreach($store_names as $str) {
					wp_set_object_terms($post_id, $str, 'coupon_store', true);
				}
			} else {
				wp_set_object_terms($post_id, $coupon->store, 'coupon_store', true);
			}
			
			update_post_meta($post_id, 'offer_id', $coupon->offer_id);
			update_post_meta($post_id, '_wpc_percent_success', '100');
			update_post_meta($post_id, '_wpc_used', '0');
			update_post_meta($post_id, '_wpc_today', '');
			update_post_meta($post_id, '_wpc_vote_up', '0');
			update_post_meta($post_id, '_wpc_vote_down', '0');
			if(!empty($coupon->end_date)) {
				update_post_meta($post_id, '_wpc_expires', strtotime($coupon->end_date));
			}
			update_post_meta($post_id, '_wpc_store', '');
			update_post_meta($post_id, '_wpc_coupon_type', ($coupon->type == 'Code' ? 'code' : 'sale'));
			update_post_meta($post_id, '_wpc_coupon_type_code', $coupon->code);
			update_post_meta($post_id, '_wpc_destination_url', $coupon->affiliate_link);
			update_post_meta($post_id, '_wpc_exclusive', '');
			update_post_meta($post_id, '_wpc_views', '0');
			
			
			$count_new = $count_new + 1;
			
		} elseif($coupon->status == 'updated') {
			
			$wpdb->query("INSERT INTO ".$wp_prefix."couponapi_logs (microtime,msg_type,message) VALUES (".microtime(true).",'debug','Updating Coupon (".$coupon->offer_id.")')");
	
			$offer_id = $coupon->offer_id;
			$sql_id = "SELECT post_id FROM ".$wp_prefix."postmeta WHERE meta_key = 'offer_id' AND meta_value = '$offer_id' LIMIT 0,1";
			$post_id = $wpdb->get_var($sql_id);
			
			$post_data = array(
				'ID'             => $post_id,
				'post_title'     => $coupon->title,
				'post_content'   => $coupon->description,
				'post_status'    => 'publish',
				'post_author'    => get_current_user_id()
			);
					
			wp_update_post($post_data);
			
			if (strpos($coupon->store, ',') !== FALSE) {
				$post_stores = array();
				$store_names = explode(',',$coupon->store);
				$append = false;
				foreach($store_names as $str) {
					wp_set_object_terms($post_id, $str, 'coupon_store', $append);
					$append = true;
				}
			} else {
				wp_set_object_terms($post_id, $coupon->store, 'coupon_store', false); 
			}
			
			if (strpos($coupon->categories, ',') !== FALSE) {
				$post_categories = array();
				$cat_names = explode(',',$coupon->categories);
				$append = false;
				foreach($cat_names as $cat) {
					wp_set_object_terms($post_id, $cat, 'coupon_category', $append);
					$append = true;
				}
			} else {
				wp_set_object_terms($post_id, $coupon->categories, 'coupon_category', false);
			}
			
			if(!empty($coupon->end_date)) {
				update_post_meta($post_id, '_wpc_expires', strtotime($coupon->end_date));
			}
			update_post_meta($post_id, '_wpc_coupon_type', ($coupon->type == 'Code' ? 'code' : 'sale'));
			update_post_meta($post_id, '_wpc_coupon_type_code', $coupon->code);
			update_post_meta($post_id, '_wpc_destination_url', $coupon->affiliate_link);
			
			$count_updated = $count_updated + 1;
			
		} elseif($coupon->status == 'suspended') {
			
			$wpdb->query("INSERT INTO ".$wp_prefix."couponapi_logs (microtime,msg_type,message) VALUES (".microtime(true).",'debug','Suspending Coupon (".$coupon->offer_id.")')");
	
			$offer_id = $coupon->offer_id;
			$sql_id = "SELECT post_id FROM ".$wp_prefix."postmeta WHERE meta_key = 'offer_id' AND meta_value = '$offer_id' LIMIT 0,1";
			$post_id = $wpdb->get_var($sql_id);
			
			wp_delete_post($post_id,true);
	
			$count_suspended = $count_suspended + 1;
			
		}

		$wpdb->query("DELETE FROM ".$wp_prefix."couponapi_upload WHERE offer_id = ".$coupon->offer_id);
		
	}
	
	$wpdb->query("INSERT INTO ".$wp_prefix."couponapi_logs (microtime,msg_type,message) VALUES (".microtime(true).",'info','Processed Offers - $count_new New , $count_updated Updated , $count_suspended Suspended.')");
}

?>
