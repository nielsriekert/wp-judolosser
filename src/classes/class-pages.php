<?php
class Page {

	/**
	 * @var object Instance of this class
	 */
	public static $instance;


	public function __construct() {
		$this->initHooks();
	}

	/**
	 * Get the singleton instance of this class
	 *
	 * @return object
	 */
	public static function instance() {
		if ( ! self::$instance ) {// TODO: was "if ( ! ( self::$instance instanceof self ) ) {" cannot change wp-pot (npm)
			self::$instance = new self();
		}

		return self::$instance;
	}

	private function initHooks() {
		add_filter( 'manage_edit-page_columns', array( $this, 'addAdminColumns' ), 10, 1 );
		add_action( 'manage_page_posts_custom_column', array( $this, 'renderAdminRows' ), 10, 2 );
	}

	public function addAdminColumns( $columns ) {

		$columns_event = array();

		foreach( $columns as $key => $value ) {
			$columns_event[$key] = $value;
			if( $key == 'title' || end( $columns ) == $value ) {
				$columns_event['attachment'] = __( 'Attachment', 'judo-losser' );
			}
		}

		return $columns_event;
	}


	public function renderAdminRows( $column, $post_id ) {
		switch( $column ) {
			case 'attachment':
				$page = new Event( get_post( $post_id ) );
				echo count( $page->getAttachments() ) > 0 ? '&#10004' : '-';
				break;
			default :
				break;
		}
	}
}

/**
 * Returns the main instance of Page.
 */
function PAGES() {
	return Page::instance();
}

PAGES();
?>