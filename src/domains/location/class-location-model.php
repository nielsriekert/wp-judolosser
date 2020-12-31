<?php
class LocationModel {

	/**
	 * @return array with Location instances
	 */
	public static function getLocations() {
		$wp_args = self::getLocationDefaultWpQueryArgs();

		$wp_locations = new WP_Query( $wp_args );

		return self::wpPostToLocationInstances( $wp_locations->posts );
	}

	/**
	 * @return Location|null
	 */
	public static function getLocationFromEvent( Event $event ) {
		$wp_args = self::getLocationDefaultWpQueryArgs();

		$wp_args['connected_type'] = 'event_to_location';
		$wp_args['connected_items'] = $event->getWpPost();

		$wp_locations = new WP_Query( $wp_args );

		return count( $wp_locations->posts ) === 1 ? self::getLocation( current( $wp_locations->posts ) ) : null;
	}

	private static function getLocationDefaultWpQueryArgs() {
		return array(
			'post_type' => 'location',
			'orderby' => 'title',
			'order' => 'ASC',
			'nopaging' => true,
			'no_found_rows' => true
		);
	}

	/**
	 * @param array with WP_Post instances
	 * @return array with Location instances
	 */
	private static function wpPostToLocationInstances( $wp_posts ) {
		$locations = array();

		if( count( $wp_posts ) > 0 ) {
			foreach( $wp_posts as $wp_post ) {
				$locations[] = self::getLocation( $wp_post );
			}
		}

		return $locations;
	}

	/**
	 * @param WP_Post|int $wp_post or post id
	 * @return Location
	 * @throws Exception when cannot find WP_Post for id or WP_Post doens't have the right post type
	 */
	public static function getLocation( $wp_post ) {
		if( ctype_digit( $wp_post ) || is_int( $wp_post ) && $wp_post > 0 ) {
			$wp_post = get_post( $wp_post );
		}

		if( ! $wp_post instanceof WP_Post ) {
			throw new Exception( 'Cannot find WP_Post' );
		}

		if( $wp_post->post_type !== 'location' ) {
			throw new Exception( 'WP_Post doesn\'t have the right post_type value' );
		}

		return new Location( $wp_post );
	}
}