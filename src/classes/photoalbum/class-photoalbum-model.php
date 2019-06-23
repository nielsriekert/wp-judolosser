<?php
class PhotoalbumModel {

	/**
	 * Get all photoalbums.
	 *
	 * @return array with Photoalbum instances.
	 */
	public static function getPhotoalbums() {
		// To do: sorting should be on event date followed by photoalbum published date
		$wp_photoalbums = new WP_Query(array(
			'post_type' => 'photoalbum',
			'nopaging' => true,
			'no_found_rows' => true,
		));

		$photoalbums = array();

		if( $wp_photoalbums->post_count > 0 ) {
			foreach( $wp_photoalbums->posts as $photoalbum ) {
				$photoalbums[] = new Photoalbum( $photoalbum );
			}
		}

		return $photoalbums;
	}

	/**
	 * @param WP_Post $event. WP_Post instance with a post type of event.
	 * @return Photoalbum instance.
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
			return new Photoalbum( current( $wp_photoalbums->posts ) );
		}

		return false;
	}

	/**
	 * Get all photoalbums.
	 *
	 * @param WP_Post $post. WP_Post instance with a post type of post.
	 * @return Photoalbum instance.
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
			return new Photoalbum( current( $wp_photoalbums->posts ) );
		}

		return false;
	}

	public static function getPhotoalbumsAjax() {
		if( ! wp_verify_nonce( $_REQUEST['nonce'], 'get_photoalbums' ) ) {
			wp_die( 'No naughty business please' );
		}

		$photoalbums = self::getPhotoalbums();

		$photoalbums_json = array();
		foreach( $photoalbums as $photoalbum ) {
			$photoalbum_json = array(
				'id' => $photoalbum->getId(),
				'name' => $photoalbum->getName(),
				'url' => $photoalbum->getUrl(),
				'date' => $photoalbum->getDate(),
				'excerpt' => html_entity_decode( $photoalbum->getExcerpt() ),
			);

			if( $photoalbum->getFeaturedImageSrc() ) {
				$photoalbum_json['featuredImage'] = array(
					'src' => $photoalbum->getFeaturedImageSrc()
				);
			}

			$photoalbums_json[] = $photoalbum_json;
		}


		header('Content-Type: application/json');
		echo json_encode( $photoalbums_json );

		wp_die();
	}
}