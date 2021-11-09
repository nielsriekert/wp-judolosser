<?php
class PhotoAlbums {

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
		define( 'PHOTO_ALBUMS_ABSPATH', dirname( __FILE__ ) . '/' );
	}

	public function includes() {
		require_once( PHOTO_ALBUMS_ABSPATH . 'photo-album/class-photo-album.php' );
		require_once( PHOTO_ALBUMS_ABSPATH . 'photo-album/class-photo-album-model.php' );
		require_once( PHOTO_ALBUMS_ABSPATH . 'photo-album/class-photo-album-view.php' );
		require_once( PHOTO_ALBUMS_ABSPATH . 'photo-album/class-photo.php' );
	}

	private function initHooks() {
		add_action( 'init', array( $this, 'createPostTypes' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'createJsGlobals' ), 20 );
		add_action( 'acf/init', array( $this, 'addFields' ) );

		add_action( 'wp_ajax_nopriv_get_photoalbums', array( 'PhotoAlbumModel', 'getPhotoAlbumsAjax' ) );
		add_action( 'wp_ajax_get_photoalbums', array( 'PhotoAlbumModel', 'getPhotoAlbumsAjax' ) );
	}

	public function createPostTypes() {
		$labels = array(
			'name' => 'Fotoalbums',
			'singular_name' => 'Fotoalbum',
			'add_new' => 'Nieuw fotoalbum', 'fotoalbum',
			'add_new_item' => 'Voeg nieuw fotoalbum toe',
			'edit_item' => 'Bewerk fotoalbum',
			'new_item' => 'Nieuw fotoalbum',
			'view_item' => 'Bekijk fotoalbum',
			'search_items' => 'Zoek fotoalbums',
			'not_found' => 'Geen fotoalbums gevonden',
			'not_found_in_trash' => 'Geen fotoalbums gevonden in de prullenbak',
			'all_items' => 'Alle fotoalbums',
			'parent_item_colon'  => '',
			'menu_name' => 'Fotoalbums'
		);

		$args = array(
			'labels' => $labels,
			'description' => 'Fotoalbums van Judo Losser',
			'public' => true,
			'rewrite' => array('slug' => 'fotoalbum'),
			'menu_position' => 6,
			'supports' => array( 'title', 'thumbnail', 'excerpt', 'author', 'revisions'),
			'menu_icon' => 'dashicons-format-gallery'
		);

		register_post_type( 'photoalbum', $args );
	}

	public function createJsGlobals() {
		wp_localize_script( 'main', 'ajax_get_photoalbums', admin_url( 'admin-ajax.php?action=get_photoalbums&nonce=' . wp_create_nonce( 'get_photoalbums' ) ) );
	}

	public function addFields() {
		acf_add_local_field_group(array (
			'key' => 'group_593a2fa413f4b',
			'title' => __( 'Photos', 'judo-losser' ),
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

	public function getOverviewUrl() {
		$events_post = get_post_by_template( 'photo-albums.php' );
		if( $events_post instanceof WP_Post) {
			return get_permalink( $events_post );
		}
	}
}

function PHOTO_ALBUMS() {
	return PhotoAlbums::instance();
}

PHOTO_ALBUMS();
?>