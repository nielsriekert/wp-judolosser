<?php
class PhotoAlbumModel {

	/**
	 * Get all photoalbums.
	 *
	 * @return array with Photoalbum instances.
	 */
	public static function getPhotoAlbums() {
		// To do: sorting should be on event date followed by photoalbum published date
		$wp_photoalbums = new WP_Query(array(
			'post_type' => 'photoalbum',
			'nopaging' => true,
			'no_found_rows' => true,
		));

		$photoalbums = array();

		if( $wp_photoalbums->post_count > 0 ) {
			foreach( $wp_photoalbums->posts as $photoalbum ) {
				$photoalbums[] = new PhotoAlbum( $photoalbum );
			}
		}

		return $photoalbums;
	}

	/**
	 * @return PhotoAlbum|null
	 */
	public static function getRandomPhotoAlbum() {
		$photo_albums = self::getPhotoAlbums();
		return count( $photo_albums ) > 0 ?  $photo_albums[array_rand( $photo_albums )] : null;
	}

	/**
	 * @param int|WP_Post $wp_post or post id
	 * @return PhotoAlbum
	 */
	public static function getPhotoAlbum( $wp_post ) {
		if( ctype_digit( $wp_post ) || is_int( $wp_post ) && $wp_post > 0 ) {
			$wp_post = get_post( $wp_post );
		}

		if( ! $wp_post instanceof WP_Post ) {
			throw new Exception( 'Cannot find WP_Post' );
		}

		return new PhotoAlbum( $wp_post );
	}

	/**
	 * @param WP_Post $event. WP_Post instance with a post type of event.
	 * @return PhotoAlbum instance.
	 */
	public static function getPhotoAlbumByEvent( $event ) {
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
			return new PhotoAlbum( current( $wp_photoalbums->posts ) );
		}

		return false;
	}

	/**
	 * Get all photoalbums.
	 *
	 * @param WP_Post $post. WP_Post instance with a post type of post.
	 * @return PhotoAlbum instance.
	 */
	public static function getPhotoAlbumByPost( $post ) {
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
			return new PhotoAlbum( current( $wp_photoalbums->posts ) );
		}

		return false;
	}

	public static function getPhotoAlbumsAjax() {
		$photoalbums = self::getPhotoAlbums();

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