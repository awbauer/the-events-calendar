<?php

namespace Step\Restv1;

class RestGuy extends \Restv1Tester {

	public function seeResponseContainsUrl( $index, $url ) {
		$response = $this->grabResponse();
		$decoded = json_decode( $response );
		$components = parse_url( $url );

		if ( isset( $components['path'] ) ) {
			$components['path'] = trim( $components['path'], '/' );
		}

		if ( false === $components ) {
			throw new \InvalidArgumentException( "Could not parse URL {$url}" );
		}

		if ( empty( $decoded->{$index} ) ) {
			$this->fail( "Response JSON does not contain the {$index} key" );
		}

		$response_url = $decoded->{$index};
		$found = parse_url( $response_url );

		if ( false === $found ) {
			$this->fail( "Response JSON does contain the {$index} key, but it is a malformed URL ({$response_url})" );
		}

		$response_components = parse_url( $response_url );

		if ( isset( $response_components['path'] ) ) {
			$response_components['path'] = trim( $response_components['path'], '/' );
		}

		$intersected = array_intersect_key( $response_components, $components );

		if ( count( $intersected ) !== count( $components ) ) {
			$this->fail( "Response JSON does contain the {$index} key, but " );
		}
		foreach ( $components as $key => $value ) {
			$this->assertArrayHasKey( $key, $response_components );
			if ( $key === 'query' ) {
				$this->assertEquals( parse_str( $response_components[ $key ] ), parse_str( $value ) );
			} else {
				$this->assertEquals( $response_components[ $key ], $value );
			}
		}
	}

	public function generate_nonce_for_role( $role ) {
		$user = $this->haveUserInDatabase( 'user', $role, [ 'user_pass' => 'secret' ] );
		$this->loginAs( 'user', 'secret' );
		$_COOKIE[ LOGGED_IN_COOKIE ] = $this->grabCookie( LOGGED_IN_COOKIE );
		wp_set_current_user( $user );

		return wp_create_nonce( 'wp_rest' );
	}
}