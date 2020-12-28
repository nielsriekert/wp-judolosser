<?php
class Event {

	/**
	 * WP post id
	 *
	 * @var integer
	 */
	public $id = null;

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
	 * Date time timestamp
	 *
	 * @var integer
	 */
	public $dateTimeTimestamp = null;


	public function __construct( $post ) {
		if( ctype_digit( $post ) || is_int( $post ) && $post > 0 ) {
			$post = get_post( $post );
		}
		else if( ! $post instanceof WP_Post ) {
			return false;
		}

		$this->id = $post->ID;
		$this->post = $post;
		$this->name = $post->post_title;

		$this->dateTimeTimestamp = CFS()->get( 'e_datum', $this->getId() );
	}

	public function getId() {
		return $this->post->ID;
	}

	public function getName() {
		return $this->name;
	}

	public function getDate( $format = 'j F Y' ) {
		return humanize_date( $this->dateTimeTimestamp, $format );
	}

	public function isPastEvent() {
		return ( date_i18n( 'Ymd', $this->dateTimeTimestamp ) < date_i18n( 'Ymd' ) );
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

	/**
	 * @param string $size
	 * @return string
	 */
	public function getFeaturedImageSrc( $size = 'post-thumb' ) {
		if( isset( $this->featuredImageSources[$size] ) && $this->featuredImageSources[$size] ) {
			return $this->featuredImageSources[$size];
		}

		$feature_image_id = get_post_thumbnail_id( $this->id );
		if( ! $feature_image_id ) {
			return '';
		}

		$this->featuredImageSources[$size] = wp_get_attachment_image_src( $feature_image_id, $size )[0];
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

	public function getWpPost() {
		return $this->post;
	}
}