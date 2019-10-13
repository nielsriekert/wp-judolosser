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

	private function defineConstants() {
		define( 'PHOTOALBUMS_ABSPATH', dirname( __FILE__ ) . '/' );
	}

	public function includes() {
		require_once( PHOTOALBUMS_ABSPATH . 'photoalbum/class-photoalbum.php' );
		require_once( PHOTOALBUMS_ABSPATH . 'photoalbum/class-photoalbum-model.php' );
		require_once( PHOTOALBUMS_ABSPATH . 'photoalbum/class-photoalbum-view.php' );
		require_once( PHOTOALBUMS_ABSPATH . 'photoalbum/class-photo.php' );
	}

	private function initHooks() {
		add_action( 'wp_enqueue_scripts', array( $this, 'createJsGlobals' ), 20 );
		add_action( 'acf/init', array( $this, 'addFields' ) );

		add_action( 'wp_ajax_nopriv_get_photoalbums', array( 'PhotoalbumModel', 'getPhotoalbumsAjax' ) );
		add_action( 'wp_ajax_get_photoalbums', array( 'PhotoalbumModel', 'getPhotoalbumsAjax' ) );
	}

	public function createJsGlobals() {
		wp_localize_script( 'main', 'ajax_get_photoalbums', admin_url( 'admin-ajax.php?action=get_photoalbums&nonce=' . wp_create_nonce( 'get_photoalbums' ) ) );
	}

	public function addFields() {
		acf_add_local_field_group(array (
			'key' => 'group_593a2fa413f4b',
			'title' => 'Foto\'s',
			'fields' => array (
				array (
					'key' => 'field_59e436fc7281a',
					'label' => 'Foto\'s',
					'name' => 'photoalbum_photos',
					'type' => 'repeater',
					'button_label' => 'Nieuwe foto',
					'sub_fields' => array (
						array (
							'key' => 'field_58ae22f4af792',
							'label' => 'Foto',
							'name' => 'photoalbum_photo',
							'type' => 'image',
						),
					),
				),
			),
			'location' => array (
				array (
					array (
						'param' => 'post_type',
						'operator' => '==',
						'value' => 'photoalbum',
					),
				),
			),
		));
	}
}

function PHOTOALBUMS() {
	return Photoalbums::instance();
}

PHOTOALBUMS();
?>