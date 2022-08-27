<?php
/*
Template Name: Migrate
*/

$events = EventModel::getAllEvents();

$updated = [];
foreach( $events as $event ) {
	$updated[] = [
		'eventId' => $event->getId(),
		'eventName' => $event->getName(),
		'updated' => update_field( 'event_start_time', $event->getDate( 'Y-m-d H:i:s' ), $event->getId() )
	];
}

wp_send_json( [
	'eventCount' => count( $events ),
	'events' => $updated
]);