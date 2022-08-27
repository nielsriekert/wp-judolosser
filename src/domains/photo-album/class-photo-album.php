<?php
class PhotoAlbum {

	/**
	 * @var Wp_Post
	 */
	private $wp_post = null;

	/**
	 * @var string
	 */
	protected $featuredImageHtml = '';

	/**
	 * @var array
	 */
	private $featuredImageSources = array();

	/**
	 * @var string
	 */
	public $eventDate = '';

	/**
	 * @var array width Photo instances
	 */
	private $photos = array();

	/**
	 * @var array width Photo instances
	 */
	private $photoThumbs = array();


	public function __construct( WP_Post $wp_post ) {
		$this->wp_post = $wp_post;

		// TODO: should not be here
		$events = new WP_Query( array(
			'connected_type' => 'event_to_photoalbum',
			'connected_items' => $wp_post,
			'nopaging' => true,
		));

		if( $events->post_count == 1 ){
			$this->eventDate = get_field( 'event_start_time', current( $events->posts )->ID );
		}
	}

	public function getId() {
		return $this->wp_post->ID;
	}

	public function getName() {
		return $this->wp_post->post_title;
	}

	public function getDate( $format = 'j F Y' ) {
		if( ! $this->eventDate ) {
			return '';
		}
		return date_i18n( $format, strtotime( $this->eventDate ) );
	}

	public function getExcerpt() {
		global $post;
		$post_temp = $post;
		setup_postdata( $this->wp_post );

		$excerpt = get_the_excerpt( $this->wp_post );

		$post = $post_temp;
		wp_reset_postdata();

		return $excerpt;
	}

	public function getFeaturedImageSrc( $size = 'post-thumb' ) {
		if( isset( $this->featuredImageSources[$size] ) && $this->featuredImageSources[$size] ) {
			return $this->featuredImageSources[$size];
		}

		$this->featuredImageSources[$size] = wp_get_attachment_image_src( get_post_thumbnail_id( $this->getId() ), $size )[0];
		return $this->featuredImageSources[$size];
	}

	public function getFeaturedImageHtml( $classes = array() ) {
		if( $this->featuredImageHtml ) {
			return $this->featuredImageHtml;
		}

		$this->featuredImageHtml = get_the_post_thumbnail( $this->getId(), 'post-thumb' );
		return $this->featuredImageHtml;
	}

	private function getPhotosWithSize( $size = 'media-full' ) {
		$acf_photos = get_field( 'photoalbum_photos', $this->getId() );

		$photos = [];
		if( is_array( $acf_photos ) ) {
			foreach( $acf_photos as $photo ) {
				$photos[] = new Photo(
					$photo['photoalbum_photo']['sizes'][$size],
					$photo['photoalbum_photo']['alt'],
					$photo['photoalbum_photo']['sizes'][$size . '-width'],
					$photo['photoalbum_photo']['sizes'][$size . '-height']
				);
			}
		}

		return $photos;
	}

	public function getPhotos() {
		if( count( $this->photos ) > 0 ) {
			return $this->photos;
		}

		$this->photos = $this->getPhotosWithSize( 'media-full' );

		return $this->photos;
	}

	public function getPhotoThumbs() {
		if( count( $this->photoThumbs ) > 0 ) {
			return $this->photoThumbs;
		}

		$this->photoThumbs = $this->getPhotosWithSize( 'media-thumb' );

		return $this->photoThumbs;
	}

	public function getUrl() {
		return get_permalink( $this->wp_post );
	}
}