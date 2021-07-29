<?php
/**
 * Plugin Name:       Datac
 * Plugin URI:        https://example.com/plugins/the-basics/
 * Description:       Handle the basics with this plugin.
 * Version:           1.10.3
 * Requires at least: 5.2
 * Requires PHP:      7.2
 * Author:            Elshawadfi
 * Author URI:        https://datac.com
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       datac
 * Domain Path:       /languages
 */
function clipmydeals_add_custom_coupon_columns_datac($columns) {
	$columns['start_date'] = "Start Date";
	
	return $columns;
}
function clipmydeals_display_custom_coupon_columns_datac( $column, $post_id ) {
	switch ($column) {
		
		case 'start_date':
			$valid_till = get_post_meta($post_id, 'cmd_start_date', true);
			if(empty($valid_till)) {
				echo 'Does not Expire';
			} elseif(strtotime($valid_till.' 23:59:59') < microtime(true)) {
				echo '<span style="color:#f16a6a;">'.date('Y/m/d',strtotime($valid_till)).'</span>';
			} else {
				echo '<span style="color:#5e8e41;">'.date('Y/m/d',strtotime($valid_till)).'</span>';
			}
			break;
		
		
	}
}


function clipmydeals_coupons_sortable_columns_datac( $columns ) {
  $columns['start_date'] = 'start_date';

  return $columns;
}
function clipmydeals_coupons_orderby_datac( $query ) {
    if(!is_admin()) return;
    $orderby = $query->get( 'orderby');
    if( 'start_date' == $orderby ) {
        $query->set('meta_key','cmd_start_date');
        $query->set('orderby','meta_value');
    }
}
add_filter('manage_coupons_posts_columns', 'clipmydeals_add_custom_coupon_columns_datac');
add_action('manage_coupons_posts_custom_column' , 'clipmydeals_display_custom_coupon_columns_datac', 10, 2 );
add_filter('manage_edit-coupons_sortable_columns', 'clipmydeals_coupons_sortable_columns_datac' );
add_action('pre_get_posts', 'clipmydeals_coupons_orderby_datac' );





function get_client_ip() {
     $ipaddress = '';
     if (getenv('HTTP_CLIENT_IP'))
        $ipaddress = getenv('HTTP_CLIENT_IP');
     else if(getenv('HTTP_X_FORWARDED_FOR'))
        $ipaddress = getenv('HTTP_X_FORWARDED_FOR');
     else if(getenv('HTTP_X_FORWARDED'))
        $ipaddress = getenv('HTTP_X_FORWARDED');
     else if(getenv('HTTP_FORWARDED_FOR'))
        $ipaddress = getenv('HTTP_FORWARDED_FOR');
     else if(getenv('HTTP_FORWARDED'))
        $ipaddress = getenv('HTTP_FORWARDED');
     else if(getenv('REMOTE_ADDR'))
        $ipaddress = getenv('REMOTE_ADDR');
     else
        $ipaddress = 'UNKNOWN';

     return $ipaddress;
}

function ip_details($url) {
   $ch = curl_init();
   curl_setopt($ch, CURLOPT_URL, $url);
   curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
   $data = curl_exec($ch);
   curl_close($ch);

   return $data;
}

add_filter('next_posts_link_attributes', 'posts_link_attributes');
add_filter('previous_posts_link_attributes', 'posts_link_attributes');

function posts_link_attributes() {
  return 'class="d-none"';
}

// add_filter("the_content", "plugin_myContentFilter");

//   function plugin_myContentFilter($content)
//   {
//     // Take the existing content and return a subset of it
//     return substr($content, 0, 200);
//   }