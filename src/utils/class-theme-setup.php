<?php
class ThemeSetupInit {

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
		define( 'THEME_SETUP_ABSPATH', dirname( __FILE__ ) . '/' );
	}

	public function includes() {
		require_once( THEME_SETUP_ABSPATH . 'theme-setup/class-theme-setup-view.php' );
	}

	private function initHooks() {
		add_action( 'after_setup_theme', array( $this, 'setupTheme' ) );

		add_action( 'admin_menu', array( $this, 'cleanAdminMenu' ) );
		add_action( 'admin_menu', array( $this, 'addAdminMenuSeparators' ) );

		add_action( 'wp_head', array( $this, 'addFontPreloadTags' ), 1 );

		add_action( 'wp_head', array( 'ThemeSetupView', 'viewMetaTags' ), 5  );
		add_action( 'wp_head', array( 'ThemeSetupView', 'viewFavicons' ), 50  );
	}

	public function setupTheme() {
		load_theme_textdomain( 'judo-losser', get_template_directory() . '/languages' );

		add_theme_support( 'menus' );

		add_theme_support( 'post-thumbnails' );
		add_post_type_support( 'page', 'excerpt' );
		set_post_thumbnail_size( 500, 375, true );


		register_nav_menus(array(
			'headernav' => __( 'Main navigation', 'judo-losser' ),
			'footernav' => __( 'Footer navigation', 'judo-losser' ),
		));

		/*
		 * Let WordPress manage the document title.
		 * By adding theme support, we declare that this theme does not use a
		 * hard-coded <title> tag in the document head, and expect WordPress to
		 * provide it for us.
		 */
		add_theme_support( 'title-tag' );

		/*
		 * Switch default core markup for search form, comment form, and comments
		 * to output valid HTML5.
		 */
		add_theme_support( 'html5', array(
			'search-form',
			'comment-form',
			'comment-list',
			'gallery',
			'caption'
		) );

		add_image_size('post-thumb', 500, 375, true);
		add_image_size('post', 1920, 1080);
		add_image_size('card', 400, 300, true);
		add_image_size('media-thumb', 300, 200, true);
		add_image_size('media-full', 1200, 1100);

		$editorcss = WPH()->getHashedAssetUrl( 'editor-style.css' );

		if( $editorcss ) {
			add_editor_style( array( $editorcss, google_fonts_url(true) ) );
		}
	}

	public function cleanAdminMenu() {
		remove_menu_page( 'edit-comments.php' );
	}

	public function addAdminMenuSeparators() {

		$positions = array( 21, 23, 28 );

		global $menu;

		foreach( $positions as $position ) {
			$menu[$position] = array( '', 'read', 'separator' . $position, '', 'wp-menu-separator' );
		}
	}

	public function addFontPreloadTags() {
		$font_files = WPH()->getHashedAssetsByExtensions( [ 'woff', 'woff2' ] );

		foreach( $font_files as $font_file ) { ?>
			<link rel="preload" href="<?php echo get_template_directory_uri() . '/' . $font_file; ?>" as="font" crossorigin>
			<?php
		}
	}
}

/**
 * Returns the main instance of ThemeSetupInit.
 */
function THEME_SETUP() {
	return ThemeSetupInit::instance();
}

THEME_SETUP();
?>