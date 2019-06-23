<?php
class Photoalbums {

	/**
	 * @var object Instance of this class
	 */
	public static $instance;


	public function __construct() {
		$this->defineConstants();
		$this->includes();
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

	/**
	 * Define igforms Constants.
	 */
	private function defineConstants() {
		define( 'PHOTOALBUMS_ABSPATH', dirname( __FILE__ ) . '/' );
	}

	/**
	 * Include required core files used in admin and on the frontend.
	 */
	public function includes() {
		require_once( PHOTOALBUMS_ABSPATH . 'photoalbum/class-photoalbum.php' );
		require_once( PHOTOALBUMS_ABSPATH . 'photoalbum/class-photoalbum-model.php' );
		require_once( PHOTOALBUMS_ABSPATH . 'photoalbum/class-photoalbum-view.php' );
	}

	/**
	 * Hook into actions and filters.
	 */
	private function initHooks() {
		add_action( 'wp_enqueue_scripts', array( $this, 'createJsGlobals' ), 20 );

		add_action( 'wp_ajax_nopriv_get_photoalbums', array( 'PhotoalbumModel', 'getPhotoalbumsAjax' ) );
		add_action( 'wp_ajax_get_photoalbums', array( 'PhotoalbumModel', 'getPhotoalbumsAjax' ) );
	}

	public function createJsGlobals() {
		wp_localize_script( 'main', 'ajax_get_photoalbums', admin_url( 'admin-ajax.php?action=get_photoalbums&nonce=' . wp_create_nonce( 'get_photoalbums' ) ) );
	}
}

/**
 * Main instance of IgDocuments.
 *
 * Returns the main instance of IgDocuments to prevent the need to use globals.
 */
function PHOTOALBUMS() {
	return Photoalbums::instance();
}

PHOTOALBUMS();
?>