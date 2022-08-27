<?php
class AttachmentModel {

	/**
	 * @return Attachment[]
	 */
	public static function getAttachments() {
		$wp_args = self::getPageDefaultWpQueryArgs();

		$wp_pages = new WP_Query( $wp_args );

		$events_and_pages = array_merge( EventModel::getEvents(), array_map( function( WP_Post $wp_post ) {
			return new Event( $wp_post );
		}, $wp_pages->posts ) );

		return array_unique( array_merge( ...array_map( function( Event $event ) {
			return $event->getAttachments();
		}, $events_and_pages ) ) );
	}

	public static function getPageDefaultWpQueryArgs() {
		return array(
			'post_type' => 'page',
			'nopaging' => true,
			'no_found_rows' => true,
		);
	}
}
?>