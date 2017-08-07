<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if ( isset( $_POST['key'] ) ) {
update_option( 'tln_gmaps-api-key', sanitize_text_field( $_POST['key'] ) );
?>
<script src="//maps.googleapis.com/maps/api/js?key=<?php echo sanitize_text_field( $_POST['key'] ); ?>"></script>
<script type="text/javascript">
function gm_authFailure() {
	alert(tln_routemap_settings_params.invalid_key_message);
	jQuery.post(ajaxurl, {'action':'tln_delete_gmap_api_key'}, function(data){
		location.reload();
	})
}
</script>
<?php
	}
	if ( ! empty( get_option( 'tln_gmaps-api-key', '' ) ) ) {
		?>
		<script>
		jQuery(document).ready(function(){
			jQuery('div.waypoints').show();
		});
		</script>
		<?php
	}
	?>
<h2>
<?php
	_e( 'Google Maps API Key', 'tln-overlandingroute' );
?>
</h2>
<?php
	if ( empty( get_option( 'tln_gmaps-api-key', '' ) ) ) {
		echo '<p>';
		printf( __( 'Get your maps api key %s here %s', 'tln-overlandingroute' ), '<a href="https://developers.google.com/maps/documentation/javascript/get-api-key">', '</a>' );
		echo '</p>';
	}
?>
<form id="gmaps-api-key" name="gmaps-api-key" method="post" action="http://dev.thelifenomadic.com/wp-admin/options-general.php?page=overlanding-routemap">
	<input type="hidden" name="action" value="tln_gmap_api_key" />
	<p><input type="password" id="tln-gmaps-api-key-input" name="key" size="39" value="<?php echo sanitize_text_field( get_option( 'tln_gmaps-api-key', '' ) ); ?>" /></p>
	<p><input type="submit" value="Submit"  class="button-primary" /></p>
</form>
<div class="waypoints" style="display: none;">
	<h2>
		<?php

		_e( 'Waypoints', 'tln-overlandingroute' );
	 ?>
	</h2>
	<form id="tln_waypoint_inputs" name="tln_waypoints_inputs" method="post" action="http://dev.thelifenomadic.com/wp-admin/options-general.php?page=overlanding-routemap">
		<p><input type="submit" value="<?php _e( 'Update', 'tln-overlandingroute' ); ?>"  class="button-primary" /></p>
		<?php
		$waypoints = array(
			//use the following format for latitude/longitude
				array( 'lat' => 1.11638, 'lng' => -77.16864 ),
				array( 'lat' => 0.81408, 'lng' => -77.66522 ),
			);

			//get_option( 'tln_waypoints', array() );
		$count = count( $waypoints );
	 ?>
		<div class="waypoint-row" style="width: 100%; float: left;">
			<p style="width: 100%; float: left;">
				<span style="width:45%; max-width: 250px; float:left; margin-right: 5%;">Latitude</span>
				<span style="width:45%; max-width: 250px; float:left;">Longitude</span>
			</p>
		</div><!--.waypoint-row-->

		<div class="waypoint-row" style="width: 100%; float: left;">
			<p style="width: 100%; float: left;">
				<span style="float: left;"><?php echo $count + 1; ?>:</span>
				<input type="text" name="lat[<?php echo $count; ?>]" style="width:45%; max-width: 250px; float:left; margin-right: 5%;" />
				<input type="text" name="lng[<?php echo $count; ?>]" style="width:45%; max-width: 250px; float:left;" />
			</p>
		</div><!--.waypoint-row-->
		<?php
		$waypoints = array_reverse( $waypoints, true );
		foreach( $waypoints as $key => $waypoint ) {
			?>
			<div class="waypoint-row" style="width: 100%; float: left;">
				<p style="width: 100%; float: left;">
					<span style="float: left;"><?php echo $key +1; ?>:</span>
					<input type="text" name="lat[<?php echo $key; ?>]" value="<?php echo $waypoint['lat']; ?>" style="width:45%; max-width: 250px; float:left; margin-right: 5%;" />
					<input type="text" name="lng[<?php echo $key; ?>]" value="<?php echo $waypoint['lng']; ?>" style="width:45%; max-width: 250px; float:left;" />
				</p>
			</div><!--.waypoint-row-->
			<?php

		}
	 ?>
		<p><input type="submit" value="<?php _e( 'Update', 'tln-overlandingroute' ); ?>"  class="button-primary" /></p>
	</form>
</div><!--.waypoints-->
