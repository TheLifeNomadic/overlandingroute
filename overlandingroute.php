<?php
/**
	* @package Geotagged Posts
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

if ( ! defined( TLN_ROUTEMAP_GAPI_KEY ) ) {
	//get a google maps API key here https://developers.google.com/maps/documentation/javascript/get-api-key
	define( 'TLN_ROUTEMAP_GAPI_KEY', 'YOUR-API-KEY' );
}
function tln_coordinates() {
	return json_encode(array(
		//use the following format for latitude/longitude
			//array( 'lat' => 1.11638, 'lng' => -77.16864 ),
			//array( 'lat' => 0.81408, 'lng' => -77.66522 ),
		)
	);
}

add_action( 'wp_enqueue_scripts', 'tln_enqueue_scripts' );

function tln_enqueue_scripts() {
	wp_register_script(
				'google-maps',
				'//maps.googleapis.com/maps/api/js?key=' . TLN_ROUTEMAP_GAPI_KEY . '&callback=initMap',
				array(),
				'1.0',
				true
		);
		wp_localize_script( 'google-maps', 'tln_coordinates', tln_coordinates() );
}


add_shortcode( 'routemap', 'tln_routemap' );

function tln_routemap() {
	wp_enqueue_script( 'google-maps' );
	$id = substr( sha1( "Google Map" . time() ), rand( 2, 10 ), rand( 5, 8 ) );

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
