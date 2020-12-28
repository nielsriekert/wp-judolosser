<?php
class PhotoAlbum {

	/**
	 * WP post id
	 *
	 * @var integer
	 */
	private $id = null;

	/**
	 * @var Wp_Post
	 */
	private $post = null;

	/**
	 * @var string
	 */
	private $name = '';

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


	public function __construct( WP_Post $post ) {
		$this->id = $post->ID;
		$this->post = $post;
		$this->name = $post->post_title;

		// TODO: should not be here
		$events = new WP_Query( array(
			'connected_type' => 'event_to_photoalbum',
			'connected_items' => $post,
			'nopaging' => true,
		));

		if( $events->post_count == 1 ){
			$this->eventDate = CFS()->get( 'e_datum', current( $events->posts )->ID );
		}
	}

	public function getId() {
		return $this->id;
	}

	public function getName() {
		return $this->name;
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
		setup_postdata( $this->post );

		$excerpt = get_the_excerpt( $this->post );

		$post = $post_temp;
		wp_reset_postdata();

		return $excerpt;
	}

	public function getFeaturedImageSrc( $size = 'post-thumb' ) {
		if( isset( $this->featuredImageSources[$size] ) && $this->featuredImageSources[$size] ) {
			return $this->featuredImageSources[$size];
		}

		$this->featuredImageSources[$size] = wp_get_attachment_image_src( get_post_thumbnail_id( $this->id ), $size )[0];
		return $this->featuredImageSources[$size];
	}

	public function getFeaturedImageHtml( $classes = array() ) {
		if( $this->featuredImageHtml ) {
			return $this->featuredImageHtml;
		}

		$this->featuredImageHtml = get_the_post_thumbnail( $this->id, 'post-thumb' );
		return $this->featuredImageHtml;
	}

	private function getPhotosWithSize( $size = 'media-full' ) {
		$acf_photos = get_field( 'photoalbum_photos', $this->id );

		$photos = array();
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

		if( count( $photos ) <= 0 ) {
			$cfs_photos = CFS()->get( 'p_photos', $this->id );

			if( is_array( $cfs_photos ) ) {
				foreach( $cfs_photos as $photo ) {
					$photos[] = new Photo(
						wp_get_attachment_image_src( $photo['p_photo'], $size )[0],
						get_post_meta( $photo['p_photo'], '_wp_attachment_image_alt', true )
					);
				}
			}
		}

		return is_array( $photos ) ? $photos : array();
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
		return get_permalink( $this->post );
	}
}