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
}