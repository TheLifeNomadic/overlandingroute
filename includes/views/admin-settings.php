<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if ( isset( $_POST['key'] ) ) {
	if ( ! wp_verify_nonce( $_REQUEST['_gmaps_api_nonce'], 'tln-gmap-api-key' ) ) {
		wp_die( 'No. Not for you!' );
	}
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
		_e( 'Information', 'tln-overlandingroute' );
		?>
	</h2>
<p>
	<?php printf( __( 'For help with this plugin or general overlanding chat, %s join us on slack %s', 'tln-overlandingroute' ), '<a href="https://thelifenomadic.slack.com/">', '</a>' );
	?>
</p>

<p>
 <?php _e( 'Donate to future deveopment of this plugin', 'tln-overlandingroute' ); ?>
</p>

<div id="coinwidget-bitcoin-1MAZhKpWvDxGCUAth5zJNad4YfNoZ8PxzP"></div>

<hr />
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
	<input type="hidden" name="_gmaps_api_nonce" value="<?php echo wp_create_nonce( 'tln-gmap-api-key' ); ?>" />
	<p><input type="password" id="tln-gmaps-api-key-input" name="key" size="39" value="<?php echo sanitize_text_field( get_option( 'tln_gmaps-api-key', '' ) ); ?>" /></p>
	<p><input type="submit" value="Submit"  class="button-primary" /></p>
</form>
<div class="waypoints" style="display: none;">
	<hr />
	<h2>
		<?php

		_e( 'Waypoints', 'tln-overlandingroute' );
	 ?>
	</h2>
	<form id="tln_waypoint_inputs" name="tln_waypoints_inputs" method="post" action="http://dev.thelifenomadic.com/wp-admin/options-general.php?page=overlanding-routemap">
		<input type="hidden" name="action" value="tln-update-waypoints" />
		<input type="hidden" name="_tln_waypoint_nonce" value="<?php echo wp_create_nonce( 'tln_update_waypoints' ); ?>" />
		<p><input type="submit" value="<?php _e( 'Update', 'tln-overlandingroute' ); ?>"  class="button-primary" /></p>
		<?php
		$waypoints = get_option( 'tln_waypoints', array() );
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
				<input type="text" name="lat[<?php echo $count; ?>]" style="width:45%; max-width: 250px; float:left; margin-right: 5%;" />
				<input type="text" name="lng[<?php echo $count; ?>]" style="width:45%; max-width: 250px; float:left;" />
			</p>
		</div><!--.waypoint-row-->
		<?php
		//$waypoints = array_reverse( $waypoints, true );
		//$
		foreach( $waypoints as $key => $waypoint ) {
			?>
			<div class="waypoint-row" style="width: 100%; float: left;">
				<p style="width: 100%; float: left;">
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
