<?php
/**
 * Class JlPhotoalbums
 *
 * Handles al the photoalbums functionality
 */

class JlPhotoalbums {

	/**
	 * @var object Instance of this class
	 */
	public static $instance;

	/**
	 * Class constructor.
	 */
	public function __construct() {
		$this->defineConstants();
		$this->includes();
	}


	/**
	 * Get the singleton instance of this class
	 *
	 * @return object
	 */

	public static function instance() {
		if ( ! ( self::$instance instanceof self ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}


	/**
	 * Define igforms Constants.
	 */

	private function defineConstants() {
		define( 'JLPHOTOALBUMS_ABSPATH', dirname( __FILE__ ) . '/' );
	}


	/**
	 * Include required core files used in admin and on the frontend.
	 */

	public function includes() {
		require_once( JLPHOTOALBUMS_ABSPATH . 'photoalbum/class-photoalbum.php' );
		require_once( JLPHOTOALBUMS_ABSPATH . 'photoalbum/class-photoalbum-model.php' );
		require_once( JLPHOTOALBUMS_ABSPATH . 'photoalbum/class-photoalbum-view.php' );
	}
}

/**
 * Main instance of IgDocuments.
 *
 * Returns the main instance of IgDocuments to prevent the need to use globals.
 */
function JLPHOTOALBUMS() {
	return JlPhotoalbums::instance();
}

// Global for backwards compatibility.
$GLOBALS['jlphotoalbums'] = JLPHOTOALBUMS();
?>