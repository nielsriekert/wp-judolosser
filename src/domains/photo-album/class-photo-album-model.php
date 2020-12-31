<?php
class PhotoAlbumModel {

	/**
	 * @return array with PhotoAlbum instances.
	 */
	public static function getPhotoAlbums() {
		$wp_args = self::getPhotoAlbumDefaultWpQueryArgs();

		// To do: sorting should be on event date followed by photo album published date
		$wp_photo_albums = new WP_Query( $wp_args );

		return self::wpPostToPhotoAlbumInstances( $wp_photo_albums->posts );
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

		if( $wp_post->post_type !== 'photoalbum' ) {
			throw new Exception( 'WP_Post doesn\'t have the right post_type value' );
		}

		return new PhotoAlbum( $wp_post );
	}

	/**
	 * @param Event $event.
	 * @return PhotoAlbum instance.
	 */
	public static function getPhotoAlbumByEvent( Event $event ) {
		$wp_args = self::getPhotoAlbumDefaultWpQueryArgs();

		$wp_args['connected_type'] = 'event_to_photoalbum';
		$wp_args['connected_items'] = $event->getWpPost();

		$wp_photo_albums = new WP_Query( $wp_args );

		return count( $wp_photo_albums->posts ) === 1 ? self::getPhotoAlbum( current( $wp_photo_albums->posts ) ) : null;
	}

	/**
	 * @param WP_Post $post. WP_Post instance with a post type of post.
	 * @return PhotoAlbum instance.
	 */
	public static function getPhotoAlbumByPost( WP_Post $wp_post ) {
		$wp_args = self::getPhotoAlbumDefaultWpQueryArgs();

		$wp_args['connected_type'] = 'post_to_photoalbum';
		$wp_args['connected_items'] = $wp_post;

		$wp_photo_albums = new WP_Query( $wp_args );

		return count( $wp_photo_albums->posts ) === 1 ? self::getPhotoAlbum( current( $wp_photo_albums->posts ) ) : null;
	}

	private static function getPhotoAlbumDefaultWpQueryArgs() {
		return array(
			'post_type' => 'photoalbum',
			'nopaging' => true,
			'no_found_rows' => true
		);
	}

		/**
	 * @param array with WP_Post instances
	 * @return array with PhotoAlbum instances
	 */
	private static function wpPostToPhotoAlbumInstances( $wp_posts ) {
		$photo_albums = array();

		if( count( $wp_posts ) > 0 ) {
			foreach( $wp_posts as $wp_post ) {
				$photo_albums[] = self::getPhotoAlbum( $wp_post );
			}
		}

		return $photo_albums;
	}

	public static function getPhotoAlbumsAjax() {
		$photo_albums = self::getPhotoAlbums();

		$photo_albums_json = array();
		foreach( $photo_albums as $photoalbum ) {
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

			$photo_albums_json[] = $photoalbum_json;
		}


		header('Content-Type: application/json');
		echo json_encode( $photo_albums_json );

		wp_die();
	}
}