<?php
class Article {

	/**
	 * WP_Post id
	 *
	 * @var integer
	 */
	protected $id = null;

	/**
	 * @var WP_Post
	 */
	protected $post = null;

	/**
	 * @var string
	 */
	protected $featuredImageHtml = '';

	/**
	 * @var array
	 */
	private $featuredImageSources = array();

	/**
	 * @var array
	 */
	private $image = array();

	/**
	 * @var boolean
	 */
	private $hidePublicationDate = false;


	public function __construct( $post ) {
		if( ctype_digit( $post ) || is_int( $post ) && (integer) $post > 1 ) {
			$post = get_post( $post );
		}
		else if( ! $post instanceof WP_Post ) {
			return false;
		}

		$this->id = $post->ID;
		$this->post = $post;
	}

	public function getId() {
		return $this->id;
	}

	public function getTitle() {
		return $this->post->post_title;
	}

	/**
	 * @param string $format. See https://codex.wordpress.org/Formatting_Date_and_Time
	 * @return string
	 */
	public function getDate( $format = 'j F Y' ) {
		return get_the_date( $format, $this->post );
	}

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

	public function getExcerpt() {
		global $post;
		$post_temp = $post;
		setup_postdata( $this->post );

		$excerpt = get_the_excerpt( $this->post );

		$post = $post_temp;
		wp_reset_postdata();

		return $excerpt;
	}

	public function getType() {
		return $this->post->post_type;
	}

	public function getUrl() {
		return get_permalink( $this->id );
	}
}
?>