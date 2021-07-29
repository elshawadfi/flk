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

function couponapi_display_settings(){
	//Bootstrap CSS
	wp_register_style( 'bootstrap.min', plugins_url('css/bootstrap.min.css', __FILE__));
	wp_enqueue_style('bootstrap.min');
	//Custom CSS
	wp_register_style('couponapi_css', plugins_url('css/couponapi_style.css', __FILE__));
	wp_enqueue_style('couponapi_css');
	//Bootstrap JS
	wp_register_script( 'bootstrap.min', plugins_url('js/bootstrap.min.js', __FILE__), array( 'jquery' ));
	wp_enqueue_script('bootstrap.min');

	set_time_limit(0);

	// Get Messages
	if(!empty($_COOKIE['message'])) {
		$message = stripslashes($_COOKIE['message']);
		echo '<script>document.cookie = "message=; expires=Thu, 01 Jan 1970 00:00:00 UTC;"</script>'; // php works only before html
	}


	global $wpdb;
	$wp_prefix = $wpdb->prefix;
	
	// GET CONFIG DETAILS
	$sql = "SELECT
						(SELECT value FROM ".$wp_prefix."couponapi_config WHERE name = 'autopilot') autopilot,
						(SELECT value FROM ".$wp_prefix."couponapi_config WHERE name = 'API_KEY') API_KEY,
						(SELECT value FROM ".$wp_prefix."couponapi_config WHERE name = 'last_extract') last_extract,
						(SELECT value FROM ".$wp_prefix."couponapi_config WHERE name = 'default_end_date') default_end_date,
						(SELECT value FROM ".$wp_prefix."couponapi_config WHERE name = 'cashback') cashback,
						(SELECT value FROM ".$wp_prefix."couponapi_config WHERE name = 'last_cron') last_cron,
						(SELECT value FROM ".$wp_prefix."couponapi_config WHERE name = 'import_images') import_images
					FROM dual";
	$config = $wpdb->get_row($sql);

	$usage = array();
	if(!empty($config->API_KEY)) {
		$usage = json_decode(file_get_contents('https://couponapi.org/api/getUsage/?API_KEY='.$config->API_KEY), true);
	}
	?>
	<div class="wrap" style="background:#F1F1F1;">

		<h2>Coupon API</h2>
		<?php echo $message; // some WP js moves this under the <h2> automatically even if you place this somewhere else. so dont bother too much. ?>
		<h6>Import Coupons &amp; Deals from Affiliate Networks</h6>
	
		<script>
			function confirmDelete() {
				var cnf = confirm("Are you sure you want to delete all offers imported from Coupon API?");
				if (cnf == true) {
					document.getElementById("deleteOffersForm").submit();
				}
			}

			function confirmSync() {
				var cnf = confirm("This will drop current offers and pull everything again. Do you want to proceed?");
				if (cnf == true) {
					document.getElementById("syncOffersForm").submit();
				}
			}
		</script>
		
		<hr/>
		<div class="row mb-5">
			<div class="col-md-4 mb-5">
				<div class="card p-0 mt-0 bg-dark text-white">
					<div class="card-header"><h5>Autopilot Settings</h5></div>
						
					<div class="card-body">
					
						<form name="autoPilot" role="form" method="post" action="<?php echo admin_url( 'admin-post.php' ); ?>">
						
							<div class="row">

								<div class="col-12">
									<div class="form-group">
										<label for="API_KEY">API Key</label>
										<input type="text" name="API_KEY" id="API_KEY" class="form-control" value="<?php echo $config->API_KEY; ?>" />
										<?php if(empty($config->API_KEY)) { ?>
											<small style="color:#a7a7a7;">Don't have an account? <a target="_blank" href="https://couponapi.org">Register Now</a></small>
										<?php } ?>
									</div>
								</div>

								<div class="col-12">	
									<div class = "form-group">
										<div class="custom-control custom-switch">
											<input type="checkbox" <?php if($config->autopilot=='On') { echo 'checked'; } ?> name="autopilot" class="custom-control-input" id="auto-pilot" />
											<label class="custom-control-label" for="auto-pilot" style="display:block;">Auto Pilot</label>
											
										</div>
									</div>
								</div>
								
								<div class="col-12">	
									<div class="form-group">
										<label for="last_extract">Last Extract :</label>
										<div class="row">
											<div class="col-lg-6">
												<input type="date" name="last_extract_date" class="form-control" id="last_extract" value="<?php if(!empty($config->last_extract)) { echo date('Y-m-d',$config->last_extract + get_option('gmt_offset')*60*60); } ?>" />
											</div>
											<div class="col-lg-6">
												<input type="time" name="last_extract_time" class="form-control" id="last_extract-time" value="<?php if(!empty($config->last_extract)) { echo date('H:i:s',$config->last_extract + get_option('gmt_offset')*60*60); } ?>" />
											</div>
										</div>
									</div>
								</div>

								<div class="col-12">
									<div class="form-group">
										<?php wp_nonce_field( 'couponapi', 'config_nonce' ); ?>
										<input type="hidden" name="action" value="capi_save_api_config" />
										<button class="btn btn-primary btn-block mt-3" style="background:#4e54c8;" type="submit" name="submit_config">Save <span class="dashicons dashicons-arrow-right" style="margin-top:2px;"></span></button>
									</div>
								</div>
								
							</div>
						
						</form>

					</div>

				</div>
			</div>
		
			
			<div class="col-md-4 mb-5">
				<div class="card p-0 mt-0 bg-dark text-white">
				<div class="card-header"><h5>Import Settings</h5></div>
					<div class="card-body">
						
						<?php if(get_template()=='clipmydeals') { ?> 
						<form name="feedSetting" role="form" method="post" action="<?php echo admin_url( 'admin-post.php' ); ?>">
							<div class="row">
								
								<div class="col-12 <?= (get_template()!='clipmydeals' ? 'd-none':'') ?>">
								<div class = "form-group">
										<div class="custom-control custom-switch">
											<input type="checkbox" <?php if($config->cashback=='On') { echo 'checked'; } ?> name="cashback" class="custom-control-input" id="cashback">
  											<label class="custom-control-label" for="cashback" style = "display:block;">Cashback Mode</label>
										</div>
									</div>
								</div>

								<div class="col-12 <?= (get_template()!='clipmydeals' ? 'd-none':'') ?>">
									<div class = "form-group">
										<div class="custom-control custom-switch">
											<input type="checkbox" <?php if($config->import_images=='On') { echo 'checked'; } ?> name="import_images" class="custom-control-input" id="import_images">
  											<label class="custom-control-label" for="import_images" style = "display:block;">Import Images</label>
										</div>
									</div>
								</div>
								
								<div class="col-12">
									<div class="form-group">
										<?php wp_nonce_field( 'couponapi', 'feed_config_nonce' ); ?>
										<input type="hidden" name="action" value="capi_save_import_config" />
										<button class="btn btn-primary btn-block" style="background:#4e54c8;" type="submit" name="submit_feed_config">Save <span class="dashicons dashicons-arrow-right" style="margin-top:2px;"></span></button>
									</div>
								</div>
								
							</div>
						
						</form>
						<?php } ?>
						
						<hr/>

						<form name="syncOffersForm" id="syncOffersForm" role="form" method="post" action="<?php echo admin_url('admin-post.php'); ?>">
							<input type="hidden" name="action" value="capi_sync_offers" />
							<?php wp_nonce_field( 'couponapi', 'sync_offers_nonce' ); ?>
							
							<button class="btn btn-warning btn-block mt-3" type="button" name="button_delete_offers" onclick="confirmSync();">Resync offers with CouponAPI DB <span class="dashicons dashicons-update"></span></button>
						</form>
						
						<form name="deleteOffersForm" id="deleteOffersForm" role="form" method="post" action="<?php echo admin_url('admin-post.php'); ?>">
							<input type="hidden" name="action" value="capi_delete_offers" />
							<?php wp_nonce_field( 'couponapi', 'delete_offers_nonce' ); ?>
							<button class="btn btn-danger btn-block mt-3" type="button" name="button_delete_offers" onclick="confirmDelete();">Drop offers fetched from CouponsAPI <span class="dashicons dashicons-trash"></span></button>
						</form>
				
					</div>
				</div>
			</div>


			<div class="col-md-4 mb-5">
				<div class="card p-0 mt-0 bg-dark text-white">
					<div class="card-header"><h5>Status</h5></div>
					<div class="card-body">
						<div class="row">

							<?php
								$troubleshootings = couponapi_get_troubleshootings();
								$critical = $warnings = 0;
								foreach ($troubleshootings as $key => $value) {
									if($value['status']=='warning') {
										$warnings++;
									} elseif($value['status']=='no') {
										$critical++;
									}
								}
								if($critical or $warnings) {
									if($critical and $warnings) {
										$issue_msg = $critical." critical issue(s) and ".$warnings." warning(s)";
									} elseif($critical) {
										$issue_msg = $critical." critical issue(s)";
									} elseif($warnings) {
										$issue_msg = $warnings." warning(s)";
									}
							?>
							<div class="col-12 py-3">
								<p><span class="dashicons dashicons-bell text-warning"></span> You have <?= $issue_msg ?> in your configuration. <a class="text-info" href="<?= admin_url('admin.php?page=couponapi-troubleshoot') ?>">See details</a></p>
							</div>
							<?php
								}
							?>
							
							<?php if(!empty($config->API_KEY)) { ?>
							<div class="col-12 py-3">
								<form name="pullFeedForm" role="form" method="post" action="<?php echo admin_url( 'admin-post.php' ); ?>">
									<input type="hidden" name="action" value="capi_pull_feed" />
									<?php wp_nonce_field( 'couponapi', 'pull_feed_nonce' ); ?>
									<button class="btn btn-primary btn-block" style="background:#4e54c8;" type="submit" name="submit_pull_feed">Fetch Feed Now <span class="dashicons dashicons-download"></span></button>
								</form>
							</div>
							<?php } ?>
							
							<div class="col-12 py-3">
								<b>Feeds extracted Today</b> <br> <?php echo ($usage['limit_used']??'0').' out of '.$usage['daily_limit']; ?>
							</div>
							
							<?php if($config->autopilot=='On') { ?>
							<div class="col-12 py-3">
								<?php
									$nextSchedule = date('g:i a',wp_next_scheduled('couponapi_pull_feed_event') + get_option('gmt_offset')*60*60);
								?>
								<b>Next Scheduled Extract</b> <br> <?php echo $nextSchedule; ?>
							</div>
							<?php } ?>
							
						</div>
					</div>
				</div>
			</div>
		
		</div>
	</div>
	<?php
}


function couponapi_display_file_upload(){
	//Bootstrap CSS
	wp_register_style( 'bootstrap.min', plugins_url('css/bootstrap.min.css', __FILE__));
	wp_enqueue_style('bootstrap.min');
	//Custom CSS
	wp_register_style('couponapi_css', plugins_url('css/couponapi_style.css', __FILE__));
	wp_enqueue_style('couponapi_css');
	//Bootstrap JS
	wp_register_script( 'bootstrap.min', plugins_url('js/bootstrap.min.js', __FILE__), array( 'jquery' ));
	wp_enqueue_script('bootstrap.min');

	set_time_limit(0);

	// Get Messages
	if(!empty($_COOKIE['message'])) {
		$message = stripslashes($_COOKIE['message']);
		echo '<script>document.cookie = "message=; expires=Thu, 01 Jan 1970 00:00:00 UTC;"</script>'; // php works only before html
	}

	global $wpdb;
	$wp_prefix = $wpdb->prefix;
	
	if(isset($_POST['submit_upload_feed'])) {

		if (!function_exists( 'wp_handle_upload' )) {
			require_once(ABSPATH.'wp-admin/includes/file.php');
		}
		$delimiter=',';
		$file_processed = false;
		$uploadedfile = $_FILES['feed'];
		$upload_overrides = array('test_form' => false,'mimes' => array('csv' => 'text/csv'));
		$movefile = wp_handle_upload( $uploadedfile, $upload_overrides );
		if ( !$movefile or isset($movefile['error']) ) {
			$error = true;
			$error_msg = 'Error during File Upload :'.$movefile['error'];
		} else {
			$sql = "INSERT INTO ".$wp_prefix."couponapi_logs (microtime,msg_type,message) VALUES (".microtime(true).",'info','Uploading File')";
			$wpdb->query($sql);
			$feedFile = $movefile['file'];
			include 'saveFileToDb.php';
			$batchSize = '99999'; // process full file
			include 'processBatch.php';
		}
		
	}
	
	?>
	<div class="wrap" style="background:#F1F1F1;">
		<h2>CSV Upload</h2>
		<h6>Manually Import Coupons &amp; Deals using CSV File.</h6>
			
		<?php echo $message; // some WP js moves this under the <h2> automatically even if you place this somewhere else. so dont bother too much. ?>

		<hr/>

		<div class="card p-0 mt-0 col-md-6 bg-dark text-white">
			<div class="card-body">
				<form name="bulkUpload" class="form-inline" role="form" method="post" enctype="multipart/form-data" action="<?php echo admin_url( 'admin-post.php' ); ?>">
					<div class="form-group">
						<input type="file" name="feed" id="feed" />
					</div>
					<input type="hidden" name="action" value="capi_file_upload" />
					<?php wp_nonce_field( 'couponapi', 'file_upload_nonce' ); ?>
					<button class="btn btn-primary" type="submit" name="submit_upload_feed">Import <span class="dashicons dashicons-upload"></span></button>
				</form>
			</div>
		</div>
	</div>
	<?php
}


function couponapi_display_logs(){
	//Bootstrap CSS
	wp_register_style( 'bootstrap.min', plugins_url('css/bootstrap.min.css', __FILE__));
	wp_enqueue_style('bootstrap.min');
	//Custom CSS
	wp_register_style('couponapi_css', plugins_url('css/couponapi_style.css', __FILE__));
	wp_enqueue_style('couponapi_css');
	//Bootstrap JS
	wp_register_script( 'bootstrap.min', plugins_url('js/bootstrap.min.js', __FILE__), array( 'jquery' ));
	wp_enqueue_script('bootstrap.min');

	set_time_limit(0);

	// Get Messages
	if(!empty($_COOKIE['message'])) {
		$message = stripslashes($_COOKIE['message']);
		echo '<script>document.cookie = "message=; expires=Thu, 01 Jan 1970 00:00:00 UTC;"</script>'; // php works only before html
	}

	global $wpdb;
	$wp_prefix = $wpdb->prefix;
	
	// Get Messages
	if(!empty($_COOKIE['message'])) {
		$message = stripslashes($_COOKIE['message']);
		echo '<script>document.cookie = "message=; expires=Thu, 01 Jan 1970 00:00:00 UTC;"</script>'; // php works only before html
	}
	
	// Get Logs
	if(!empty($_POST['log_duration'])) { $log_duration = $_POST['log_duration']; } else { $log_duration = '1 HOUR'; }
	if(!isset($_POST['log_debug'])) { $log_debug = "msg_type != 'debug'"; } else { $log_debug = "TRUE"; }
	
	$gmt_offset = get_option('gmt_offset');
	$offset_sign = ($gmt_offset < 0) ? '-' : '+';
	$positive_offset = ($gmt_offset < 0) ? $gmt_offset*-1 : $gmt_offset;
	$hours = floor($positive_offset);
	$minutes = round(($positive_offset - $hours)*60);
	$tz = $offset_sign . $hours . ':' . $minutes;
	
	$sql_logs = "SELECT
									CONVERT_TZ(logtime,@@session.time_zone,'".$tz."') logtime,
									msg_type,
									message,
									CASE
										WHEN msg_type = 'success' then 'green'
										WHEN msg_type = 'error' then 'red'
										WHEN msg_type = 'debug' then '#4a92bf'
									END as color
								FROM  ".$wp_prefix."couponapi_logs
								WHERE logtime > NOW() - INTERVAL $log_duration
								AND $log_debug
								ORDER BY microtime";
	$logs = $wpdb->get_results($sql_logs);
	
	?>
	<div class="wrap" style="background:#F1F1F1;">

		<h2>Logs</h2>
			
		<?php echo $message; // some WP js moves this under the <h2> automatically even if you place this somewhere else. so dont bother too much. ?>

		<hr/>

		
		<div class="card p-0 mt-0 col-12">
		<div class="card-header d-flex bg-dark text-white">
					<form name="refreshLogs" role="form" class="form-inline w-100" method="post" action="<?php echo str_replace('&tab=','&oldtab=',str_replace( '%7E', '~', $_SERVER['REQUEST_URI'])); ?>&tab=logs">
						<button class="btn btn-primary btn-sm px-2" type="submit" name="submit_fetch_logs">Refresh <span class="dashicons dashicons-update"></span></button>
						<div class="form-group px-2">
							<label>Duration : </label> 
							<select name="log_duration">
								<option value="1 HOUR" <?php if($log_duration == '1 HOUR') echo 'selected'; ?>>1 Hour</option>
								<option value="1 DAY" <?php if($log_duration == '1 DAY') echo 'selected'; ?>>24 Hours</option>
								<option value="1 WEEK" <?php if($log_duration == '1 WEEK') echo 'selected'; ?>>This Week</option>
							</select>
						</div>
						<div class="checkbox px-2">
							<label class="d-inline small">Show Debug Logs</label> <input name="log_debug" type="checkbox" <?php if(isset($_POST['log_debug'])) echo 'checked'; ?>>
						</div>
						<a href="<?php echo wp_nonce_url( admin_url( 'admin-post.php?action=capi_download_logs' ), 'couponapi', 'log_nonce' ); ?>" class="btn btn-sm btn-outline-light ml-auto px-2" style="margin-right:10px;">Download Logs <span class="dashicons dashicons-download"></span></a>
					</form>
			</div>
			
			<div class="card-body">
				<?php if(sizeof($logs) >= 1) { ?>
					<table>
						<tr><th style="white-space: nowrap;">Time</th><th style="padding-left:20px;">Message</th></tr>
						<?php
							foreach($logs as $log) {
								if($log->message)
								echo '<tr style="font-size:0.85em;"><td >'.$log->logtime.'</td><td style="padding-left:20px;color:'.$log->color.';">'.$log->message.'</td></tr>';
							}
						?>
					</table>
				<?php } else { ?>
					<i>No Data to display</i>
				<?php } ?>
			</div>
			
		</div>
	</div>
	<?php
}

function couponapi_display_troubleshoot(){
	
	//Bootstrap CSS
	wp_register_style( 'bootstrap.min', plugins_url('css/bootstrap.min.css', __FILE__));
	wp_enqueue_style('bootstrap.min');
	//Custom CSS
	wp_register_style('couponapi_css', plugins_url('css/couponapi_style.css', __FILE__));
	wp_enqueue_style('couponapi_css');
	//Bootstrap JS
	wp_register_script( 'bootstrap.min', plugins_url('js/bootstrap.min.js', __FILE__), array( 'jquery' ));
	wp_enqueue_script('bootstrap.min');

	set_time_limit(0);

	$troubleshooting = couponapi_get_troubleshootings();

	?>
	<div class="wrap" style="background:#F1F1F1;">
		<h2>Troubleshoot</h2>
		<h6>Checks for common issues related to server & setup configurations.</h6>
			
		<hr/>

		<div class="card p-0 mt-0 col-12">
			<div class="card-body p-0">
				<table class="table m-0 table-striped">
					<thead class="thead-dark">
						<tr>
							<th>Check</th>
							<th>Status</th>
							<th>Message</th>
						</tr>
					</thead>
					<tbody>
						
						<?php foreach($troubleshooting as $name => $value){?>
						<tr>
							<td><strong><?=$name?></strong></td>
							<td><span class="capi_troubleshoot dashicons dashicons-<?=$value['status']?>"></span></td>
							<td style="font-size: small;"><?=$value['message']?></td>
						</tr>
						<?php } ?>

					</tbody>
				</table>
			</div>
		</div>

		<a class="btn btn-primary btn-sm px-2 ml-2 mt-3" href="<?= admin_url('admin.php?page=couponapi-troubleshoot') ?>">Refresh <span class="dashicons dashicons-update"></span></a>

	</div>
	<?php
}

?>
