<?php
class EventModel {

	/**
	 * @return array with Event instances
	 */
	public static function getEvents() {
		$wp_args = self::getEventDefaultWpQueryArgs();

		$wp_events = new WP_Query( $wp_args );

		return self::wpPostToEventInstances( $wp_events->posts );
	}

	/**
	 * @return Event|null
	 */
	public static function getNextEvent() {
		$events = self::getEvents();

		return count( $events ) > 0 ? current( $events ) : null;
	}

	public static function getEventDefaultWpQueryArgs() {
		return array(
			'post_type' => 'event',
			'nopaging' => true,
			'no_found_rows' => true,
			'meta_key' => 'e_datum',
			'orderby' => 'meta_value',
			'order' => 'ASC',
			'meta_query' => array(
				array(
					'key' => 'e_datum',
					'value' => date_i18n('Y-m-d'),
					'compare' => '>='
				)
			)
		);
	}

	/**
	 * @param array with WP_Post instances
	 * @return array with Event instances
	 */
	public static function wpPostToEventInstances( $wp_posts ) {
		$events = array();

		if( count( $wp_posts ) > 0 ) {
			foreach( $wp_posts as $wp_post ) {
				$events[] = self::getEvent( $wp_post );
			}
		}

		return $events;
	}

	/**
	 * @param WP_Post|int $wp_post or post id
	 * @return Event
	 * @throws Exception when cannot find WP_Post for id or WP_Post doens't have the right post type
	 */
	public static function getEvent( $wp_post ) {
		if( ctype_digit( $wp_post ) || is_int( $wp_post ) && $wp_post > 0 ) {
			$wp_post = get_post( $wp_post );
		}

		if( ! $wp_post instanceof WP_Post ) {
			throw new Exception( 'Cannot find WP_Post' );
		}

		if( $wp_post->post_type !== 'event' ) {
			throw new Exception( 'WP_Post doesn\'t have the right post_type value' );
		}

		return new Event( $wp_post );
	}

	public static function getEventsAjax() {
		$events = self::getEvents();

		$events_json = array();
		foreach( $events as $event ) {
			$event_json = array(
				'id' => $event->getId(),
				'name' => $event->getName(),
				'url' => $event->getUrl(),
				'date' => $event->getDate(),
				'excerpt' => html_entity_decode( $event->getExcerpt() ),
			);

			if( $event->getFeaturedImageSrc() ) {
				$event_json['featuredImage'] = array(
					'src' => $event->getFeaturedImageSrc()
				);
			}

			$events_json[] = $event_json;
		}

		header('Content-Type: application/json');
		echo json_encode( $events_json );

		wp_die();
	}
}
?>