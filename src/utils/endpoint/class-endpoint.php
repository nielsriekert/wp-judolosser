<?php
class Endpoint {

	/**
	 * Echo the json data for the requested data
	 *
	 * @return json
	 */
	public static function handleRequest( $request, $options = array() ) {
		header( 'content-type: application/json; charset=utf-8' );

		if( isset( $options['status'] ) && $options['status'] ) {
			echo self::getStatusMessage( $options['status'], $options['message'] );
			exit;
		}

		if( ! isset( $request['endpoint-resource'] ) ) {
			echo self::getStatusMessage( 404, __( 'The request is incorrect or the requested data doesn\'t exist.' , 'judo-losser' ) );
			exit;
		}

		$json_response = (object) array();

		switch( $request['endpoint-resource'] ) {
			case 'events':
				if( isset( $_GET['event-id'] ) ) {
					try {
						$json_response = self::prepareEventForJson( EventModel::getEvent( (int) $_GET['event-id'] ) );
					}
					catch( Exception $exception ) {
						echo self::getStatusMessage( 404, $exception->getMessage() );
						exit;
					}
				}
				else {
					$json_response = self::getEventsForJson();
				}
				break;
		}

		echo json_encode( $json_response );
		exit;
	}

	private static function getEventsForJson() {
		$events = EventModel::getEvents();

		return array_map( function( $event ) {
			return self::prepareEventForJson( $event );
		}, $events );
	}

	/**
	 * Gets correct status message when the request is incorrect
	 *
	 * @param integer $status. http status code.
	 * @param string $message
	 * @return json
	 */
	public static function getStatusMessage( $status, $message = null ) {
		http_response_code( $status );

		switch( $status ) {
			case 400:
			default:
				$api_result = json_encode( array(
					'error' => array(
						'message' => ( $message ) ? $message : __( 'Something went wrong, contact us for support.', 'judo-losser' )
					)
				), JSON_PRETTY_PRINT );
				break;
			case 404:
				$api_result = json_encode( array(
					'error' => array(
						'message' => ( $message ) ? $message : __( 'Resource not found.', 'judo-losser' )
					)
				), JSON_PRETTY_PRINT );
				break;
		}

		return $api_result;
	}

	/**
	 * @param Event $event
	 * @return object
	 */
	private static function prepareEventForJson( Event $event ) {
		return (object) array(
			'id' => $event->getId(),
			'name' => $event->getName(),
			'date' => $event->getDate('U'),
			'shortDescription' => html_entity_decode( $event->getExcerpt() ),
			'registrationEnabled' => $event->isRegistrationEnabled()
		);
	}
}