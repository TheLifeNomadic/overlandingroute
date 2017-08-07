<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
class TLN_Route_Settings {

	public function __construct() {
		include_once( 'views/admin-settings.php' );
		wp_print_scripts( array( 'tln-routemap-admin', 'bitcoin-donation-button' ) );
	}
}

$settings_view = new TLN_Route_Settings();
