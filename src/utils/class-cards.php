<?php
class CardsInit {

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
		define( 'CARDS_ABSPATH', dirname( __FILE__ ) . '/' );
	}

	/**
	 * Include required core files used in admin and on the frontend.
	 */
	public function includes() {
		require_once( CARDS_ABSPATH . 'card/class-card.php' );
		require_once( CARDS_ABSPATH . 'card/class-card-news-post.php' );
		require_once( CARDS_ABSPATH . 'card/class-card-event.php' );
		require_once( CARDS_ABSPATH . 'card/class-card-photo-album.php' );
		require_once( CARDS_ABSPATH . 'card/class-card-page.php' );

		require_once( CARDS_ABSPATH . 'card/class-card-model.php' );
		require_once( CARDS_ABSPATH . 'card/class-card-view.php' );
	}

	private function initHooks() {
		add_action( 'acf/init', array( $this, 'addCardFields' ) );
	}

	public function addCardFields() {
		acf_add_local_field_group(array (
			'key' => 'group_582efe6c5a243',
			'title' => __( 'Cards', 'judo-losser' ),
			'fields' => array (
				array (
					'key' => 'field_59fea92acb7e1',
					'label' => __( 'Cards', 'judo-losser' ),
					'name' => 'cards_cards',
					'type' => 'flexible_content',
					'button_label' => __( 'New card', 'judo-losser' ),
					'layouts' => array(
						'layout_5fea1b3c210bd' => array(
							'key' => 'layout_5fea1b3c210bd',
							'label' => __( 'News Post', 'judo-losser' ),
							'name' => 'news-post',
							'display' => 'block',
						),
						'layout_5fef1b0c2a0bd' => array(
							'key' => 'layout_5fef1b0c2a0bd',
							'label' => __( 'Event', 'judo-losser' ),
							'name' => 'event',
							'display' => 'block',
						),
						'layout_5fefab0c22fbd' => array(
							'key' => 'layout_5fefab0c22fbd',
							'label' => __( 'Photo album', 'judo-losser' ),
							'name' => 'photo-album',
							'display' => 'block',
						),
						'layout_5fef4b0c2ffad' => array(
							'key' => 'layout_5fef4b0c2ffad',
							'label' => __( 'Page', 'judo-losser' ),
							'name' => 'page',
							'display' => 'block',
							'sub_fields' => array(
								array(
									'key' => 'field_5fea4402001ce',
									'label' => __('Page', 'judo-losser' ),
									'name' => 'page',
									'type' => 'post_object',
									'post_type' => array(
										'page',
									)
								)
							)
						),
					),
					'min' => 3,
					'max' => 6,
				)
			),
			'location' => array (
				array (
					array (
						'param' => 'page_template',
						'operator' => '==',
						'value' => 'portal.php'
					),
				),
			),
		));
	}
}

function CARDS() {
	return CardsInit::instance();
}

CARDS();
?>