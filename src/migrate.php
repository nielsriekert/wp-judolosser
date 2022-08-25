<?php
/*
Template Name: Migrate
*/

$events = EventModel::getAllEvents();

foreach( $events as $event ) {
	update_field( 'event_start_time', $event->getDate( 'Y-m-d H:i:s' ), $event->getId() );
}