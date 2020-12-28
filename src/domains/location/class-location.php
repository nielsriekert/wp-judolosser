<?php
class Location {

	/**
	 * @var Wp_Post
	 */
	public $wp_post = null;

	/**
	 * @var array
	 */
	private $fields = array();


	public function __construct( WP_Post $wp_post ) {
		$this->wp_post = $wp_post;

		$fields = get_fields( $wp_post->ID );
		$this->fields = is_array( $fields ) ? $fields : array();
	}

	public function getId() {
		return $this->wp_post->ID;
	}

	/**
	 * @return string
	 */
	public function getName() {
		return $this->wp_post->post_title;
	}

	/**
	 * @return string
	 */
	public function getStreet() : string {
		return is_string( $this->fields['location_street'] ) ? trim( $this->fields['location_street'] ) : '';
	}

	/**
	 * @return string
	 */
	public function getPostcode() {
		return is_string( $this->fields['location_postcode'] ) ? trim( $this->fields['location_postcode'] ) : '';
	}

	/**
	 * @return string
	 */
	public function getStreetNumber() {
		return is_string( $this->fields['location_street_number'] ) ? trim( $this->fields['location_street_number'] ) : '';
	}

	/**
	 * @return string
	 */
	public function getAddress() {
		return implode( ' ', array_filter( [ $this->getStreet(), $this->getStreetNumber() ] ) );
	}

	/**
	 * @return string
	 */
	public function getPostcodeAndLocality() {
		return implode( ' ', array_filter( [ $this->getPostcode(), $this->getLocality() ] ) );
	}

	/**
	 * @return string
	 */
	public function getAddressAdditional() {
		return is_string( $this->fields['location_address_additional'] ) ? trim( $this->fields['location_address_additional'] ) : '';
	}

	/**
	 * @return string
	 */
	public function getLocality() {
		return is_string( $this->fields['location_locality'] ) ? trim( $this->fields['location_locality'] ) : '';
	}

	/**
	 * @return string.
	 */
	public function getFullAddress() {
		return implode( ' ', array_filter( [ $this->getStreet(), $this->getStreetNumber(), $this->getPostcode(), $this->getLocality() ] ) );
	}

	/**
	 * Gets the latitude and longitude coordinates.
	 *
	 * @return object with latitude longitude properties.
	 */
	public function getCoordinates() {
		$location = array(
			'latitude' => $this->fields['location_coordinates']['location_latitude'],
			'longitude' => $this->fields['location_coordinates']['location_longitude']
		);

		return (object) array_map( function( $degree ) {
			return is_numeric( $degree ) ? floatval( $degree ) : null;
		}, $location );
	}

	public function hasFeaturedPhoto() {
		return isset( $this->fields['location_featured_photo'] ) && is_array( $this->fields['location_featured_photo'] );
	}

	/**
	 * @param string $size WordPress image size
	 * @return LocationImage|null null when not found
	 */
	public function getFeaturedPhoto( $size = 'location' ) {
		return isset( $this->fields['location_featured_photo'] ) && is_array( $this->fields['location_featured_photo'] ) ?
			new LocationImage(
				$this->fields['location_featured_photo']['id'],
				$this->fields['location_featured_photo']['sizes'][$size],
				$this->fields['location_featured_photo']['alt'],
				$this->fields['location_featured_photo']['sizes'][$size . '-width'],
				$this->fields['location_featured_photo']['sizes'][$size . '-height']
			) : null;
	}

	public function getWpPost() {
		return $this->post;
	}
}