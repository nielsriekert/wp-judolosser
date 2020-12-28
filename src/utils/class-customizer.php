<?php
class CustomizerInit {

	/**
	 * @var object Instance of this class
	 */
	public static $instance;


	public function __construct() {
		$this->defineConstants();
		$this->initHooks();
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
		define( 'CUSTOMIZER_ABSPATH', dirname( __FILE__ ) . '/' );
	}

	/**
	 * Include required core files used in admin and on the frontend.
	 */
	public function includes() {
		require_once( CUSTOMIZER_ABSPATH . 'customizer/class-customizer-image-control-svg.php' );
		require_once( CUSTOMIZER_ABSPATH . 'customizer/class-customizer-multiple-select.php' );
		require_once( CUSTOMIZER_ABSPATH . 'customizer/class-customizer-textarea.php' );
	}

	/**
	 * Hook into actions and filters.
	 */
	private function initHooks() {
		add_action( 'customize_register', array( $this, 'initSettings'), 1 );
	}

	public function initSettings( $wp_customize ) {
		require_once( CUSTOMIZER_ABSPATH . 'customizer/class-customizer-image-control-svg.php' );
		require_once( CUSTOMIZER_ABSPATH . 'customizer/class-customizer-multiple-select.php' );
		require_once( CUSTOMIZER_ABSPATH . 'customizer/class-customizer-textarea.php' );
	}
}

function CUSTOMIZER() {
	return CustomizerInit::instance();
}

CUSTOMIZER();
?>