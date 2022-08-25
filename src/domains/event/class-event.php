<?php
class Event {

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
	 * Date time timestamp
	 *
	 * @var integer
	 */
	public $dateTimeTimestamp = null;

	/**
	 * @var array
	 */
	private $fields = array();

	public function __construct( WP_Post $wp_post ) {
		$this->wp_post = $wp_post;

		$this->dateTimeTimestamp = CFS()->get( 'e_datum', $this->getId() );

		$fields = get_fields( $wp_post->ID );
		$this->fields = is_array( $fields ) ? $fields : array();
	}

	public function getId() {
		return $this->wp_post->ID;
	}

	public function getName() {
		return $this->wp_post->post_title;
	}

	public function getDate( $format = 'j F Y' ) {
		// TODO: no dependency like this
		return humanize_date( $this->dateTimeTimestamp, $format );
	}

	public function getStartDateTime( $format = 'j F Y H:i' ) {
		if( ! isset( $this->fields['event_start_time'] ) ) {
			throw new Exception( 'No start date time set for event with id ' . $this->getId() );
		}

		return date_i18n( $format, strtotime( $this->fields['event_start_time'] ) );
	}

	public function isPastEvent() {
		return ( date_i18n( 'Ymd', strtotime( $this->dateTimeTimestamp ) ) < date_i18n( 'Ymd' ) );
	}

	public function isRegistrationEnabled() {
		return isset( $this->fields['event_registration_enable'] ) && $this->fields['event_registration_enable'];
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

	public function hasFeaturedPhoto() {
		return isset( $this->fields['event_featured_photo'] ) && is_array( $this->fields['event_featured_photo'] );
	}

	/**
	 * @param string $size WordPress image size
	 * @return Image|null null when not found
	 */
	public function getFeaturedPhoto( $size = 'post-thumb' ) {
		return isset( $this->fields['event_featured_photo'] ) && is_array( $this->fields['event_featured_photo'] ) ?
			new Image(
				$this->fields['event_featured_photo']['id'],
				$this->fields['event_featured_photo']['sizes'][$size],
				$this->fields['event_featured_photo']['alt'],
				$this->fields['event_featured_photo']['sizes'][$size . '-width'],
				$this->fields['event_featured_photo']['sizes'][$size . '-height']
			) : null;
	}

	/**
	 * @param string $size
	 * @return string
	 * @deprecated 1.13.0
	 */
	public function getFeaturedImageSrc( $size = 'post-thumb' ) {
		if( isset( $this->featuredImageSources[$size] ) && $this->featuredImageSources[$size] ) {
			return $this->featuredImageSources[$size];
		}

		$feature_image_id = get_post_thumbnail_id( $this->getId() );
		if( ! $feature_image_id ) {
			return '';
		}

		$this->featuredImageSources[$size] = wp_get_attachment_image_src( $feature_image_id, $size )[0];
		return $this->featuredImageSources[$size];
	}

	/**
	 * @deprecated 1.13.0
	 */
	public function getFeaturedImageHtml( $classes = array() ) {
		if( $this->featuredImageHtml ) {
			return $this->featuredImageHtml;
		}

		$this->featuredImageHtml = get_the_post_thumbnail( $this->getId(), 'post-thumb' );
		return $this->featuredImageHtml;
	}

	public function getUrl() {
		return get_permalink( $this->wp_post );
	}

	public function getWpPost() {
		return $this->wp_post;
	}
}