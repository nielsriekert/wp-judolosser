<?php
class EventModel {

	/**
	 * @return array with Event instances
	 */
	public static function getEvents() {
		$wp_events = new WP_Query(array(
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
		));

		$events = array();

		if( $wp_events->post_count > 0 ) {
			foreach( $wp_events->posts as $event ) {
				$events[] = new Event( $event );
			}
		}

		return $events;
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