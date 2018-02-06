<?php
/**
 * Class JlPhotoalbum
 *
 * Photoalbum class.
 */

class JlPhotoalbum {

	/**
	 * WP post id
	 *
	 * @var integer
	 */
	public $id = null;

	/**
	 * Photoalbum title
	 *
	 * @var string
	 */
	public $title = null;

	/**
	 * Photoalbum url
	 *
	 * @var string
	 */
	public $url = null;

	/**
	 * Photoalbum featured image.
	 *
	 * @var object
	 */
	public $featuredImage = null;

	/**
	 * Photoalbum event date
	 *
	 * @var string
	 */
	public $eventDate = null;

	/**
	 * Photoalbum event date humanized
	 *
	 * @var string
	 */
	public $eventDateHumanize = null;

	public function __construct( $post ) {
		if( ctype_digit( $post ) && (integer) $post > 1 ) {
			$post = get_post( $post );
		}
		else if( ! $post instanceof WP_Post ) {
			return false;
		}

		$this->id = $post->ID;
		$this->title = $post->post_title;
		$this->url = get_permalink( $post );

		$post_thumb_id = get_post_thumbnail_id( $post );
		$post_thumb = wp_get_attachment_image_src( $post_thumb_id, 'post-thumb' );

		$this->featuredImage = (object) array(
			'url' => $post_thumb[0],
			'width' => $post_thumb[1],
			'height' => $post_thumb[2],
			'alt' => get_post_meta( $post_thumb_id, '_wp_attachment_image_alt', true)
		);
		
		$events = new WP_Query( array(
			'connected_type' => 'event_to_photoalbum',
			'connected_items' => $post,
			'nopaging' => true,
		));

		if( $events->post_count == 1 ){
			$this->eventDate = CFS()->get( 'e_datum', current( $events->posts )->ID );
			if( $this->eventDate ) {
				$this->eventDateHumanize = humanize_date( $this->eventDate );
			}
		}
	}
}