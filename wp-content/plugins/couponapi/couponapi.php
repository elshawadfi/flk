<?php

/**
* Plugin Name: CouponAPI
* Plugin URI: https://couponapi.org
* Description: Automatically import Coupons & Deals from popular Affiliate Networks into your WordPress Coupon Website.
* Version: 3.4
* Author: CouponAPI.org
* Author URI: https://couponapi.org
**/

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

require plugin_dir_path(__FILE__).'activate.php';
require plugin_dir_path(__FILE__).'views.php';
require plugin_dir_path(__FILE__).'save-api-config.php';
require plugin_dir_path(__FILE__).'save-import-config.php';
require plugin_dir_path(__FILE__).'delete-offers.php';
require plugin_dir_path(__FILE__).'pull-feed.php';

function couponapi_submit_delete_offers() {
	if( wp_verify_nonce($_POST['delete_offers_nonce'], 'couponapi') ) {
		$message = couponapi_delete_offers();
	} else {
		$message = '<div class="notice notice-error is-dismissible"><p>Access Denied. Nonce could not be verified.</p></div>';
	}
	setcookie('message',$message);
	wp_redirect('admin.php?page=couponapi');
	exit();
}

function couponapi_submit_sync_offers() {
	if( wp_verify_nonce($_POST['sync_offers_nonce'], 'couponapi') ) {
		// drop all offers
		couponapi_delete_offers();
		// change last extract
		global $wpdb;
		$wp_prefix = $wpdb->prefix;
		$wpdb->query("REPLACE INTO ".$wp_prefix."couponapi_config (name,value) VALUES ('last_extract','100')");
		// pull feed
		wp_schedule_single_event( time() , 'couponapi_pull_feed_event');
		$message = '<div class="notice notice-success is-dismissible"><p>Sync process has been initiated. Refresh Logs to see current status.</p></div>';
	} else {
		$message = '<div class="notice notice-error is-dismissible"><p>Access Denied. Nonce could not be verified.</p></div>';
	}
	setcookie('message',$message);
	wp_redirect('admin.php?page=couponapi');
	exit();
}

function couponapi_submit_pull_feed() {
	if( wp_verify_nonce($_POST['pull_feed_nonce'], 'couponapi') ) {
		$message = couponapi_pull_feed();
	} else {
		$message = '<div class="notice notice-error is-dismissible"><p>Access Denied. Nonce could not be verified.</p></div>';
	}
	setcookie('message',$message);
	wp_redirect('admin.php?page=couponapi');
	exit();
}

function couponapi_file_upload() {
	if( wp_verify_nonce($_POST['file_upload_nonce'], 'couponapi') ) {
		if (!function_exists( 'wp_handle_upload' )) {
			require_once(ABSPATH.'wp-admin/includes/file.php');
		}
		$delimiter=',';
		$file_processed = false;
		$uploadedfile = $_FILES['feed'];
		$upload_overrides = array('test_form' => false,'mimes' => array('csv' => 'text/csv'));
		$movefile = wp_handle_upload( $uploadedfile, $upload_overrides );
		if ( !$movefile or isset($movefile['error']) ) {
			$message .= '<div class="notice notice-error is-dismissible"><p>Error during File Upload :'.$movefile['error'].'</p></div>';
		} else {
			global $wpdb;
			$wp_prefix = $wpdb->prefix;
			$sql = "INSERT INTO ".$wp_prefix."couponapi_logs (microtime,msg_type,message) VALUES (".microtime(true).",'info','Uploading File')";
			$wpdb->query($sql);
			$feedFile = $movefile['file'];
			$wpdb->query( 'SET autocommit = 0;' );
			$result = couponapi_save_csv_to_db($feedFile);
			if(!$result['error']) {
				$wpdb->query( 'COMMIT;' );
				$wpdb->query( 'SET autocommit = 1;' );
				$wpdb->query("INSERT INTO ".$wp_prefix."couponapi_logs (microtime,msg_type,message) VALUES (".microtime(true).",'info','Offer Feed saved to local database. Starting upload process') ");
				wp_schedule_single_event( time() , 'couponapi_process_batch_event'); // process next batch
				$message = '<div class="notice notice-info is-dismissible"><p>Upload process is running in background. Refresh Logs to see current status.</p></div>';
			} else {
				$wpdb->query( 'ROLLBACK' );
				$wpdb->query( 'SET autocommit = 1;' );
				$wpdb->query("INSERT INTO ".$wp_prefix."couponapi_logs (microtime,msg_type,message) VALUES
													(".microtime(true).",'debug','".esc_sql($result['error_msg'])."'),
													(".microtime(true).",'error','Error uploading feed to local database')");
				$message = '<div class="notice notice-error is-dismissible"><p>Error uploading feed to local database.</p></div>';
			}
		}
	} else {
		$message = '<div class="notice notice-error is-dismissible"><p>Access Denied. Nonce could not be verified.</p></div>';
	}
	setcookie('message',$message);
	wp_redirect('admin.php?page=couponapi-file-upload');
	exit();
}

function couponapi_download_logs() {
	if( wp_verify_nonce($_GET['log_nonce'], 'couponapi') ) {
		global $wpdb;
		$wp_prefix = $wpdb->prefix;

		$gmt_offset = get_option('gmt_offset');
		$offset_sign = ($gmt_offset < 0) ? '-' : '+';
		$positive_offset = ($gmt_offset < 0) ? $gmt_offset*-1 : $gmt_offset;
		$hours = floor($positive_offset);
		$minutes = round(($positive_offset - $hours)*60);
		$tz = $offset_sign . $hours . ':' . $minutes;
		
		$logs = $wpdb->get_results("SELECT
																			CONCAT(CONVERT_TZ(logtime,@@session.time_zone,'".$tz."'),' ','$tz') logtime,
																			msg_type,
																			message
																		FROM  ".$wp_prefix."couponapi_logs
																		ORDER BY microtime");
		
		$filename = "couponapi_".date("YmdHis").".log";
		$seperator = "\t";
		
		header("Content-Type: text/csv");
		header("Content-Disposition: attachment; filename=".$filename);
		header("Cache-Control: no-cache, no-store, must-revalidate"); // HTTP 1.1
		header("Pragma: no-cache"); // HTTP 1.0
		header("Expires: 0"); // Proxies
		header("Content-Transfer-Encoding: UTF-8");
		
		$fp = fopen("php://output", "w");
		
		foreach($logs as $log) {
			fputcsv($fp, array($log->logtime, $log->msg_type, $log->message), $seperator);
		}
		fclose($fp);
		
	} else {
		$message = '<div class="notice notice-error is-dismissible"><p>Access Denied. Nonce could not be verified.</p></div>';
	}
	setcookie('message',$message);
	wp_redirect('admin.php?page=couponapi-logs');
	exit();
}

function couponapi_get_config() {

	global $wpdb;
	$wp_prefix = $wpdb->prefix;

	$result = array(
			'theme'		=> get_template(),
			'charset'  	=> $wpdb->charset,
			'curl'  	=> in_array('curl', get_loaded_extensions()),
			'cashback-plugin' => in_array('clipmydeals-cashback/clipmydeals-cashback.php',get_option('active_plugins')),
	);

	$config = $wpdb->get_results("SELECT * FROM ".$wp_prefix."couponapi_config");
	foreach($config as $row)
		$result[$row->name] = $row->value;
			
	return $result;
}

function couponapi_get_troubleshootings() {
	
	$configs = couponapi_get_config();

	$troubleshooting = array();

	// API Key
	if($configs['autopilot']=='On') {
		$usage = json_decode(file_get_contents('https://couponapi.org/api/getUsage/?API_KEY='.$configs['API_KEY']), true);
		if(!$usage['result']) {
			$troubleshooting['API Key'] = array(
										"status"=>"no",
										"message"=>__("Invalid API Key or Account has Expired. Please check your API Key from <a target='_blank' href='https://couponapi.org/account/dashboard.php'>CouponAPI Dashboard</a>.",'couponapi'),
									);
		} else {
			$troubleshooting['API Key'] = array(
											"status"=>"yes",
											"message"=>__("You have an active subscription with CouponAPI",'couponapi'),
										);
		}
	}
	
	// Theme
	if(couponapi_is_theme_supported($configs['theme'])) {
		$troubleshooting['Theme'] = array(
										"status"=>"yes",
										"message"=>__("CouponAPI works perfectly with ".ucfirst($configs['theme'])." Theme",'couponapi'),
									);
	} else {
		$troubleshooting['Theme'] = array(
										"status"=>"no",
										"message"=>__("CouponAPI does not work with ".ucfirst($configs['theme'])." Theme",'couponapi'),
									);
	}

	// WP-Cron
	if(empty($configs['last_cron'])) {
		$troubleshooting['WP-Cron'] = array(
										"status"=>"no",
										"message"=>__("WP-Cron is possibly disabled on your server.",'couponapi'),
									);
	} elseif(time() - $configs['last_cron'] > 600) {
		$troubleshooting['WP-Cron'] = array(
										"status"=>"warning",
										"message"=>__("WP-Cron has not run since ".date('jS F Y, g:i a',$configs['last_cron']+get_option('gmt_offset')*60*60),'couponapi'),
									);
	} else {
		$troubleshooting['WP-Cron'] = array(
										"status"=>"yes",
										"message"=>__("WP-Cron is working fine. Last successful run was on ".date('jS F Y, g:i a',$configs['last_cron']+get_option('gmt_offset')*60*60),'couponapi'),
									);
	}

	// CURL
	if($configs['curl']) {
		$troubleshooting['cURL'] = array(
										"status"=>"yes",
										"message"=>__("PHP CURL module is working",'couponapi'),
									);
	} else {
		$troubleshooting['cURL'] = array(
										"status"=>"no",
										"message"=>__("PHP CURL directive is not working. It is required to call external APIs. Please contact your hosting provider and get it enabled.",'couponapi'),
									);
	}

	// Images
	if($configs['import_images']=='On' and $configs['theme']!='clipmydeals') {
		$troubleshooting['Images'] = array(
										"status"=>"no",
										"message"=>__(ucfirst($configs['theme'])." Theme does not support images hosted on third-party servers. Please add 'Store Logos' to stores/merchants in your theme to display on your offers.",'couponapi'),
									);
	} elseif($configs['import_images']=='On' and $configs['theme']=='clipmydeals') {
		$troubleshooting['Images'] = array(
										"status"=>"yes",
										"message"=>__("If the source Affiliate Network has not added any image to an offer, CouponAPI will not be able to pass images in such case.<br/>For this, you must add Store Logos in WordPress > Coupons > Stores > Edit, so that the logo displays on offers where image is not available.",'couponapi'),
									);
	}

	// DB Character Set
	if(couponapi_str_like($configs['charset'],'utf')) {
		$troubleshooting['Database'] = array(
										"status"=>"yes",
										"message"=>__("Your Database Character Set (".$configs['charset'].") supports Non-English characters",'couponapi'),
									);
	} else {
		$troubleshooting['Database'] = array(
										"status"=>"warning",
										"message"=>__("Your Database Character Set (".$configs['charset'].") does not support Non-English characters.",'couponapi'),
									);
	}

	// Cashback setting
	if($configs['cashback']=='On' and $configs['theme']!='clipmydeals') {
		$troubleshooting['Cashback'] = array(
										"status"=>"no",
										"message"=>$configs['theme'].__(" Theme does not support Cashback.",'couponapi'),
									);
	} elseif($configs['cashback']=='On' and !$configs['cashback-plugin']) {
		$troubleshooting['Cashback'] = array(
										"status"=>"no",
										"message"=>__("You have enabled 'Cashback Mode' in Import Settings, but ClipMyDeals Cashback Plugin is not installed/activated on your website.",'couponapi'),
									);
	} elseif($configs['cashback']=='Off' and $configs['cashback-plugin']) {
		$troubleshooting['Cashback'] = array(
										"status"=>"warning",
										"message"=>__("ClipMyDeals Cashback Plugin is installed on your website, but you have not enabled 'Cashback' Mode' in Import Settings.",'couponapi'),
									);
	} else {
		$troubleshooting['Cashback'] = array(
										"status"=>"yes",
										"message"=>__("ClipMyDeals Cashback Plugin is installed on your website.",'couponapi'),
									);
	}


	return $troubleshooting;
}

function couponapi_register_api() {
	register_rest_route( 'couponapi/v1', 'checkStatus',array(
									                'methods'  => 'GET',
									                'callback' => 'couponapi_server_checks',
									                'args' => array(
														'API_KEY' => array(
															'required' => true
														),
													)
												));
}

function couponapi_server_checks($data) {
	$status = couponapi_get_config();
		
	if($data['API_KEY'] != $status['API_KEY']) {
		echo json_encode(array("API_KEY" => "Incorrect API Key"));
	} else {
		global $wpdb;
		$wp_prefix = $wpdb->prefix;	
		$status['logs'] = $wpdb->get_results("SELECT logtime,message,msg_type FROM ".$wp_prefix."couponapi_logs WHERE msg_type != 'debug' ORDER BY microtime DESC LIMIT 20");
		echo json_encode($status);
	}

	return;
}


function couponapi_is_theme_supported($theme_name) {
	$supported = array('clipmydeals', 'clipper', 'couponxl', 'couponxxl', 'rehub', 'rehub-theme', 'wpcoupon', 'wp-coupon', 'CP', 'cp', 'CPq');
	return (in_array($theme_name, $supported) OR substr($theme_name,0,2)==="CP");
}

function couponapi_str_like($haystack,$needle) {
	if( strpos( strtolower($haystack),strtolower($needle) ) !== false ) {
		return true;
	} else {
		return false;
	}
}

function couponapi_admin_menu() {
	//add_menu_page("Coupon API", "Coupon API", 7, "couponapi", "couponapi_display_main", "dashicons-rss",9);
	add_menu_page( "Coupon API", 	"Coupon API", 	7, "couponapi", 	"couponapi_display_settings", "dashicons-rss",9);
	add_submenu_page("couponapi", "Settings",	"Settings", 	7, "couponapi", "couponapi_display_settings");
	add_submenu_page("couponapi", "Coupon API CSV Upload",	"CSV Upload", 	7, "couponapi-file-upload", "couponapi_display_file_upload");
	add_submenu_page("couponapi", "Coupon API Logs", 		"Logs", 		7, "couponapi-logs", 		"couponapi_display_logs");
	add_submenu_page("couponapi", "Coupon API Troubleshoot","Troubleshoot", 7, "couponapi-troubleshoot","couponapi_display_troubleshoot");
}

function couponapi_check_wpcron() {
	global $wpdb;
	$wp_prefix = $wpdb->prefix;
	$wpdb->query("REPLACE INTO ".$wp_prefix."couponapi_config (name,value) VALUES ('last_cron',".microtime(true).")");
}

function couponapi_custom_wpcron_schedules( $schedules ) {
    $schedules['every_five_minutes'] = array(
            'interval'  => 60*5,
            'display'   => __( 'Every 5 Minutes', 'couponapi' )
    );
    return $schedules;
}

add_filter( 'cron_schedules', 'couponapi_custom_wpcron_schedules' );

add_action('couponapi_check_wpcron_event', 'couponapi_check_wpcron');
add_action('admin_menu', 'couponapi_admin_menu');
add_action('admin_post_capi_save_api_config', 'couponapi_save_api_config');
add_action('admin_post_capi_save_import_config', 'couponapi_save_import_config');
add_action('admin_post_capi_sync_offers', 'couponapi_submit_sync_offers');
add_action('admin_post_capi_delete_offers', 'couponapi_submit_delete_offers');
add_action('admin_post_capi_pull_feed', 'couponapi_submit_pull_feed');
add_action('admin_post_capi_file_upload', 'couponapi_file_upload');
add_action('admin_post_capi_download_logs', 'couponapi_download_logs');
add_action('couponapi_pull_feed_event','couponapi_pull_feed' );
add_action('couponapi_process_batch_event','couponapi_process_batch' );
add_action('rest_api_init', 'couponapi_register_api');

register_activation_hook( __FILE__, 'couponapi_activate' );

// TODO: Remove this in later versions
add_action( 'plugins_loaded', 'couponapi_update_to_3_point_2_point_1' );

// Schedule an action if it's not already scheduled
if (!wp_next_scheduled('couponapi_check_wpcron_event')) {
	couponapi_check_wpcron();
	wp_schedule_event( time(), 'every_five_minutes', 'couponapi_check_wpcron_event' );
}

?>
