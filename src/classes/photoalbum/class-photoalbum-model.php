<?php
/**
 * Class JlPhotoalbumModel
 *
 * Photoalbum model for getting photoalbum data
 */

class JlPhotoalbumModel {


	/**
	 * Get all photoalbums.
	 *
	 * @param array $args.
	 * @return array with JlPhotoalbum instances.
	 * @author Niels Riekert
	 */

	public static function getPhotoalbums( $args = array() ) {
		// To do: sorting should be on event date followed by photoalbum published date
		$wp_photoalbums = new WP_Query(array(
			'post_type' => 'photoalbum',
			'nopaging' => true,
			'no_found_rows' => true,
			'meta_key' => 'e_datum',
			'orderby' => 'meta_value',
			'order' => 'DESC'
		));

		$photoalbums = array();

		if( $wp_photoalbums->post_count > 0 ) {
			foreach( $wp_photoalbums->posts as $photoalbum ) {
				$photoalbums[] = new JlPhotoalbum( $photoalbum );
			}
		}

		return $photoalbums;
	}


	/**
	 * Get all photoalbums.
	 *
	 * @param WP_Post $event. WP_Post instance with a post type of event.
	 * @return JlPhotoalbum instance.
	 * @author Niels Riekert
	 */

	public static function getPhotoalbumByEvent( $event ) {
		if( ! $event instanceof WP_Post ) {
			return false;
		}

		$wp_photoalbums = new WP_Query(array(
			'post_type' => 'photoalbum',
			'connected_type' => 'event_to_photoalbum',
			'connected_items' => $event,
			'nopaging' => true,
			'no_found_rows' => true,
		));

		if( $wp_photoalbums->post_count == 1 ) {
			return new JlPhotoalbum( current( $wp_photoalbums->posts ) );
		}

		return false;
	}


	/**
	 * Get all photoalbums.
	 *
	 * @param WP_Post $post. WP_Post instance with a post type of post.
	 * @return JlPhotoalbum instance.
	 * @author Niels Riekert
	 */

	public static function getPhotoalbumByPost( $post ) {
		if( ! $post instanceof WP_Post ) {
			return false;
		}

		$wp_photoalbums = new WP_Query(array(
			'post_type' => 'photoalbum',
			'connected_type' => 'post_to_photoalbum',
			'connected_items' => $post,
			'nopaging' => true,
			'no_found_rows' => true,
		));

		if( $wp_photoalbums->post_count == 1 ) {
			return new JlPhotoalbum( current( $wp_photoalbums->posts ) );
		}

		return false;
	}
}