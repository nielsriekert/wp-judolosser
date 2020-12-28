<?php
class Articles {

	/**
	 * @var object Instance of this class
	 */
	public static $instance;

	/**
	 * @var string
	 */
	private $articleOverviewUrl = '';


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
		define( 'ARTICLES_ABSPATH', dirname( __FILE__ ) . '/' );
	}

	/**
	 * Include required core files used in admin and on the frontend.
	 */
	public function includes() {
		require_once( ARTICLES_ABSPATH . 'article/class-article-model.php' );
		require_once( ARTICLES_ABSPATH . 'article/class-article-view.php' );
		require_once( ARTICLES_ABSPATH . 'article/class-article.php' );
	}


	/**
	 * Hook into actions and filters.
	 */
	private function initHooks() {
		add_action( 'after_setup_theme', array( $this, 'addImageSizes' ) );
		add_action( 'acf/init', array( $this, 'addFields' ) );
		add_filter( 'excerpt_length', array( $this, 'articleExcerptLength' ), 20 );
		add_action( 'wp_enqueue_scripts', array( $this, 'createJsGlobals' ), 20 );

		add_action( 'wp_ajax_nopriv_get_articles', array( 'ArticleModel', 'getArticlesAjax' ) );
		add_action( 'wp_ajax_get_articles', array( 'ArticleModel', 'getArticlesAjax' ) );
	}

	/**
	 * Adds WordPress images sizes
	 */
	public function addImageSizes() {
		add_image_size( 'featured-image', 450, 450, true );
		add_image_size( 'featured-image-landscape', 450, 250, true );
	}

	/**
	 * Adds the Advanced Custom Fields
	 */
	public function addFields() {
		acf_add_local_field_group(array (
			'key' => 'group_578dfcc3162fb',
			'title' => __( 'Articles', 'conseiller' ),
			'fields' => array (
				array (
					'key' => 'field_59bfa21341fa9',
					'label' => __( 'Default filter', 'conseiller' ),
					'name' => 'ar_default_filter_type',
					'instructions' => __( 'Defaults to "Show all".', 'conseiller' ),
					'type' => 'select',
					'allow_null' => 1,
					'choices' => array(
						'newspost' => __( 'Newspost', 'conseiller'),
						'training' => __( 'Training', 'conseiller'),
						'reference' => __( 'Reference', 'conseiller')
					)
				),
			),
			'location' => array (
				array (
					array(
						'param' => 'post_template',
						'operator' => '==',
						'value' => 'articles.php',
					),
				),
			),
		) );
	}

	public function articleExcerptLength( $length ) {
		return 20;
	}

	public function createJsGlobals() {
		wp_localize_script( 'main', 'ajax_get_articles', admin_url( 'admin-ajax.php?action=get_articles&nonce=' . wp_create_nonce( 'get_articles' ) ) );
	}

	public function getPermalinkOverviewPage() {
		if( $this->articleOverviewUrl ) {
			return $this->articleOverviewUrl;
		}

		$articles_pages = new WP_Query( array(
			'post_type' => 'page',
			'nopaging' => true,
			'no_found_rows' => true,
			'meta_query' => array(
				array(
					'key' => '_wp_page_template',
					'value' => 'posts.php',
				)
			)
		) );

		if( $articles_pages->post_count == 1 ) {
			$this->articleOverviewUrl = get_permalink( current( $articles_pages->posts ) );
			return $this->articleOverviewUrl;
		}
	}
}

/**
 * Main instance of Articles.
 */
function ARTICLES() {
	return Articles::instance();
}

ARTICLES();
?>