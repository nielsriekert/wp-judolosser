<?php
class ImagesInit {

	/**
	 * @var object Instance of this class
	 */
	public static $instance;


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

	private function defineConstants() {
		define( 'IMAGES_ABSPATH', dirname( __FILE__ ) . '/' );
	}

	/**
	 * Include required core files used in admin and on the frontend.
	 */
	public function includes() {
		require_once( IMAGES_ABSPATH . 'image/class-image.php' );
	}
}

function IMAGES() {
	return ImagesInit::instance();
}

IMAGES();
?>