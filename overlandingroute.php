<?php
/**
	* @package Overlanding Route Map
	* @version 0.1
	*/
/*
Plugin Name: Overlanding Route Map
Plugin URI: https://www.thelifenomadic.com
Description: Takes your input GPS coordinates and outputs them on a map using a shortcode.
Author: Will Brubaker
Version: 0.1
Author URI: https://www.thelifenomadic.com
*/
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
if ( ! defined( 'TLN_ROUTEMAP_VERSION' ) ) {
	define( 'TLN_ROUTEMAP_VERSION', '0.1' );
}

add_shortcode( 'routemap', 'tln_routemap' );

function tln_routemap() {
	wp_enqueue_script( 'google-maps' );
	$id = substr( sha1( "Google Map" . time() ), rand( 2, 10 ), rand( 5, 8 ) );

		$markers = tln_coordinates();
		ob_start();
		?>
		<div class="map" style="min-height: 300px; z-index:10;" id="map-<?php echo $id ?>"></div>

		<script type='text/javascript'>

		function initMap() {
			var height = jQuery(window).height()
			jQuery('.map').height(height)
			var map = new google.maps.Map(document.getElementById('map-<?php echo $id ?>'), {
				zoom: 5,
				center: {lat: 0.81408, lng: -77.66522},
				title: 'My Overlanding Route'
			})

			var route = new google.maps.Polyline({
				path: JSON.parse(tln_coordinates),
				geodesic: true,
				strokeColor: '#FF0000',
				strokeOpacity: 1.0,
				strokeWeight: 2
			});
			route.setMap(map);
		}
		</script>
		<?php
		$output = ob_get_clean();
		return $output;
}

add_action( 'admin_menu', 'tln_settings_menu' );

function tln_settings_menu() {
	$submenu = add_submenu_page( 'options-general.php', 'My Overlanding Route Settings', 'Route Map', 'manage_options', 'overlanding-routemap', 'tln_routemap_settings' );
}

function tln_routemap_settings() {
	if ( ! class_exists( 'TLN_Route_Settings' ) ) {
		include_once( 'includes/class-settings.php' );
	}
}

add_action( 'admin_enqueue_scripts', 'tln_register_scripts' );

function tln_register_scripts() {
	$suffix = ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) ? '' : '.min';
	wp_register_script( 'blockr.io-coinwidget', '//blockr.io/js_external/coinwidget/coin.js' );
	wp_register_script( 'bitcoin-donation-button', plugins_url( '/assets/js/bc-donate-button' . $suffix . '.js', __FILE__ ), array( 'blockr.io-coinwidget' ), TLN_ROUTEMAP_VERSION, true );
	wp_register_script( 'tln-routemap-admin', plugins_url( '/assets/js/admin-settings' . $suffix . '.js', __FILE__ ), array( 'jquery' ), TLN_ROUTEMAP_VERSION, true );
	wp_localize_script( 'tln-routemap-admin', 'tln_routemap_settings_params', array( 'invalid_key_message' => __( 'Invalid Key', 'tln-overlandingroute' ) ) );
}

add_action( 'wp_ajax_tln_delete_gmap_api_key', 'tln_delete_key' );

function tln_delete_key() {
	delete_option( 'tln_gmaps-api-key' );
	wp_send_json( array( 'success' => true ) );
}

add_action( 'wp_ajax_tln-update-waypoints', 'tln_update_waypoints' );

function tln_update_waypoints() {
	$waypoints = array();
	check_ajax_referer( 'tln_update_waypoints', '_tln_waypoint_nonce' );
	foreach( $_POST['lat'] as $key => $lat ) {
		if ( tln_validateLatLong( $_POST['lat'][ $key ], $_POST['lng'][ $key ] ) ) {
			$waypoints[] = array( 'lat' => $_POST['lat'][ $key ], 'lng' => $_POST['lng'][ $key ] );
		} else {
			wp_send_json( array( 'success' => false, 'message' => __( 'Invalid Latitude/Longitude detected, please try again', 'tln-overlandingroute' ) ) );
		}
	}
	$success = update_option( 'tln_waypoints', array_values( array_unique( $waypoints, SORT_REGULAR ) ) );
	wp_send_json( array( 'success' => $success, 'message' => $_POST ) );
}

/**
 * Validates a given coordinate
 *
 * @param float|int|string $lat Latitude
 * @param float|int|string $long Longitude
 * @return bool `true` if the coordinate is valid, `false` if not
 */
function tln_validateLatLong( $lat, $long ) {
  return preg_match('/^[-]?(([0-8]?[0-9])\.(\d+))|(90(\.0+)?),[-]?((((1[0-7][0-9])|([0-9]?[0-9]))\.(\d+))|180(\.0+)?)$/', $lat.','.$long);
}
