<?php

if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

if ( ! class_exists( 'Tribe__Events__Pro__Template_Factory' ) ) {
	class Tribe__Events__Pro__Template_Factory extends Tribe__Events__Template_Factory {

		/**
		 * The class constructor.
		 */
		public function __construct() {
			parent::__construct();
			add_action( 'tribe_events_asset_package', array( __CLASS__, 'asset_package' ), 10, 2 );
		}

		/**
		 * The asset loading function.
		 *
		 * @param string $name The name of the package reqested.
		 * @param array  $deps An array of dependencies (this should be the registered name that is registered to the wp_enqueue functions).
		 *
		 * @return void
		 */
		public static function asset_package( $name, $deps = array() ) {

			$tec_pro = Tribe__Events__Pro__Main::instance();
			$prefix  = 'tribe-events-pro';

			// setup plugin resources & 3rd party vendor urls
			$vendor_url = trailingslashit( $tec_pro->pluginUrl ) . 'vendor/';

			parent::handle_asset_package_request( $name, $deps, $vendor_url, $prefix, $tec_pro );
		}

		/**
		 * Retrieves the appropriate asset factory instance
		 */
		protected static function get_asset_factory_instance( $name ) {
			$asset = Tribe__Events__Pro__Asset__Factory::instance()->make_for_name( $name );
			return $asset;
		}
	}
}
