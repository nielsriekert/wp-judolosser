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
	private $title = '';

	/**
	 * Event featured image.
	 *
	 * @var object
	 */
	public $featuredImage = null;

	/**
	 * Event featured image for single template.
	 *
	 * @var object
	 */
	public $featuredImageArticle = null;

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
		$this->title = $post->post_title;

		$this->dateTimeTimestamp = CFS()->get( 'e_datum', $this->getId() );
	}

	public function getId() {
		return $this->id;
	}

	public function getName() {
		return $this->title;
	}

	public function getDateFormatted( $format = 'j F Y' ) {
		return humanize_date( $this->dateTimeTimestamp, $format );
	}

	public function isPastEvent() {
		return ( date_i18n( 'Ymd', $this->dateTimeTimestamp ) < date_i18n( 'Ymd' ) );
	}

	public function getUrl() {
		return get_permalink( $this->post );
	}
}