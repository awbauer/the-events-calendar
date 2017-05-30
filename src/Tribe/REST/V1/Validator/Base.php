<?php


class Tribe__Events__REST__V1__Validator__Base
	extends Tribe__Events__Validator__Base
	implements Tribe__Events__REST__V1__Validator__Interface {

	public function is_venue_id_or_entry( $venue ) {
		if ( ! is_array( $venue ) ) {
			return tribe_is_venue( $venue );
		}

		$request = new WP_REST_Request();
		/** @var Tribe__Events__REST__V1__Endpoints__Linked_Post_Endpoint_Interface $venue_endpoint */
		$venue_endpoint = tribe( 'tec.rest-v1.endpoints.single-venue' );

		$request->set_attributes( [ 'args' => $venue_endpoint->POST_args() ] );
		foreach ( $venue as $key => $value ) {
			$request->set_param( $key, $value );
		}

		$has_valid_params = $request->has_valid_params();

		return true === $has_valid_params ? true : false;
	}
}