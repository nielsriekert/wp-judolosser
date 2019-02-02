<?php
class User extends WP_User {

	/**
	 * @var WP_User
	 */
	public $user = null;


	public function __construct( $user ) {
		if( ctype_digit( $user ) || is_int( $user ) && $user > 0 ) {
			$post = new WP_User( $user );
		}
		else if( ! $user instanceof WP_User ) {
			return false;
		}

		$this->user = $user;
	}

	public function getId() {
		return $this->user->ID;
	}

	public function getFirstName() {
		return $this->user->first_name;
	}

	public function getLastName() {
		return $this->user->last_name;
	}

	public function getFullName() {
		return trim( $this->user->first_name . ' ' . $this->user->last_name );
	}

	public function getFullNameAsPublic() {
		switch( $this->getNameDisplay() ) {
			case 'anonymous':
				return '- ' . __( 'Anonymous', 'onso' ) . ' -';
			case 'first-name':
				return $this->user->first_name;
			case 'full-name':
			default:
				return $this->getFullName();
		}
	}

	public function getNameDisplay() {
		return get_the_author_meta( 'name-display', $this->user->ID );
	}

	public function getCommittee() {
		return $this->user->get( 'committee' );
	}

	public function getCommitteeRole() {
		return $this->user->get( 'role' );
	}

	public function getTask() {
		return $this->user->get( 'task' );
	}

	public function getEmail() {
		return strtolower( $this->user->get( 'user_email' ) );
	}

	public function getPhone() {
		return $this->user->get( 'phone' );
	}

	public function getPhoneAdditional() {
		return $this->user->get( 'phone-additional' );
	}

	public function getStreet() {
		return $this->user->get( 'street' );
	}

	public function getHouseNumber() {
		return $this->user->get( 'house-number' );
	}

	public function getAddress() {
		return trim( $this->getStreet() . ' ' . $this->getHouseNumber() );
	}

	public function getPostcode() {
		return $this->user->get( 'postcode' );
	}

	public function getLocality() {
		return $this->user->get( 'locality' );
	}
}
?>