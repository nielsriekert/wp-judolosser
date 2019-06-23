<?php
class Photoalbum {

	/**
	 * WP post id
	 *
	 * @var integer
	 */
	private $id = null;

	/**
	 * @var Wp_Post
	 */
	public $post = null;

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


	public function __construct( $post ) {
		if( ctype_digit( $post ) && (integer) $post > 1 ) {
			$post = get_post( $post );
		}
		else if( ! $post instanceof WP_Post ) {
			return false;
		}

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

	public function getUrl() {
		return get_permalink( $this->post );
	}
}