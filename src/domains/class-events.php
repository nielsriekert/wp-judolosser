<?php
class Events {

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
		if ( ! self::$instance ) {// TODO: was "if ( ! ( self::$instance instanceof self ) ) {" cannot change wp-pot (npm)
			self::$instance = new self();
		}

		return self::$instance;
	}

	private function defineConstants() {
		define( 'EVENTS_ABSPATH', dirname( __FILE__ ) . '/' );
	}

	public function includes() {
		require_once( EVENTS_ABSPATH . 'event/class-event.php' );
		require_once( EVENTS_ABSPATH . 'event/class-event-model.php' );
		require_once( EVENTS_ABSPATH . 'event/class-event-view.php' );
	}

	private function initHooks() {
		add_action( 'init', array( $this, 'createPostTypes' ) );
		add_filter( 'manage_edit-event_columns', array( $this, 'addAdminColumns' ), 10, 1 );
		add_action( 'manage_event_posts_custom_column', array( $this, 'renderAdminRows' ), 10, 2 );
		add_filter( 'manage_edit-event_sortable_columns', array( $this, 'addSortableColumns' ), 10, 1 );

		add_action( 'wp_enqueue_scripts', array( $this, 'createJsGlobals' ), 20 );

		add_action( 'wp_ajax_nopriv_get_events', array( 'EventModel', 'getEventsAjax' ) );
		add_action( 'wp_ajax_get_events', array( 'EventModel', 'getEventsAjax' ) );

		add_action( 'load-edit.php', function(){
			add_filter( 'request', array( $this, 'sortByEventDate' ), 10, 1 );
		} );
	}

	public function createPostTypes() {
		$labels = array(
			'name' => _x( 'Events', 'post type general name', 'judo-losser' ),
			'singular_name' => _x( 'Event', 'post type singular name', 'judo-losser' ),
			'menu_name' => _x( 'Events', 'admin menu', 'judo-losser' ),
			'name_admin_bar' => _x( 'Event', 'add new on admin bar', 'judo-losser' ),
			'add_new' => _x( 'Add New', 'event', 'judo-losser' ),
			'add_new_item' => __( 'Add New Event', 'judo-losser' ),
			'new_item' => __( 'New Event', 'judo-losser' ),
			'edit_item' => __( 'Edit Event', 'judo-losser' ),
			'view_item' => __( 'View Event', 'judo-losser' ),
			'all_items' => __( 'All Events', 'judo-losser' ),
			'search_items' => __( 'Search Events', 'judo-losser' ),
			'parent_item_colon' => __( 'Parent Events:', 'judo-losser' ),
			'not_found' => __( 'No events found.', 'judo-losser' ),
			'not_found_in_trash' => __( 'No events found in Trash.', 'judo-losser' )
		);

		$args = array(
			'labels' => $labels,
			'description' => __( 'Judo Losser Events', 'judo-losser' ),
			'public' => true,
			'rewrite' => array(
				'slug' => __( 'event', 'judo-losser'),
				'with_front' => false,
			),
			'menu_position' => 21,
			'supports' => array( 'title', 'editor', 'author', 'thumbnail', 'excerpt', 'revisions' ),
			'menu_icon' => 'dashicons-calendar',
			'capability_type' => 'event',
			'map_meta_cap' => true,
		);

		register_post_type( 'event', $args );
	}


	public function getFeaturedImage( $image = null ){
		$featured_image = new stdClass();

		if( $image ) {
			if( isset( $image->default ) ) {
				$featured_image->default = (object) array(
					'src' => $image->default->url,
					'width' => $image->default->width,
					'height' => $image->default->height
				);
			}
			if( isset( $image->small ) ) {
				$featured_image->small = (object) array(
					'src' => $image->small->url,
					'width' => $image->small->width,
					'height' => $image->small->height
				);
			}
			if( isset( $image->articleDefault ) ) {
				$featured_image->articleDefault = (object) array(
					'src' => $image->articleDefault->url,
					'width' => $image->articleDefault->width,
					'height' => $image->articleDefault->height
				);
			}
			if( isset( $image->articleMiddle ) ) {
				$featured_image->articleMiddle = (object) array(
					'src' => $image->articleMiddle->url,
					'width' => $image->articleMiddle->width,
					'height' => $image->articleMiddle->height
				);
			}
			$featured_image->alt = $image->alt;
		}
		else {
			$featured_image->default = (object) array(
				'src' => get_bloginfo( 'template_directory' ) . '/' . WPH()->getHashedAssetUrl( 'event-placeholder.jpg' ),
				'width' => 420,
				'height' => 250
			);
			$featured_image->small = (object) array(
				'src' => get_bloginfo( 'template_directory' ) . '/' . WPH()->getHashedAssetUrl( 'event-placeholder-small.jpg' ),
				'width' => 350,
				'height' => 350
			);
			$featured_image->articleDefault = (object) array(
				'src' => get_bloginfo( 'template_directory' ) . '/' . WPH()->getHashedAssetUrl( 'event-article-placeholder.jpg' ),
				'width' => 450,
				'height' => 350
			);
			$featured_image->articleMiddle = (object) array(
				'src' => get_bloginfo( 'template_directory' ) . '/' . WPH()->getHashedAssetUrl( 'event-article-placeholder-middle.jpg' ),
				'width' => 450,
				'height' => 540
			);
			$featured_image->alt = 'viool';
		}

		return $featured_image;
	}

	public function addAdminColumns( $columns ) {

		$columns_event = array();

		foreach( $columns as $key => $value ) {
			$columns_event[$key] = $value;
			if( $key == 'title' || end( $columns ) == $value ) {
				$columns_event['date-event'] = __( 'Event date', 'judo-losser' );
				$columns_event['attachment'] = __( 'Attachment', 'judo-losser' );
			}
		}

		return $columns_event;
	}


	public function renderAdminRows( $column, $post_id ) {
		switch( $column ) {
			case 'date-event':
				if(CFS()->get('e_datum', $post_id))
					echo humanize_date(CFS()->get('e_datum', $post_id));
				else
					echo '-';
				break;
			case 'attachment':
				echo CFS()->get('bl_bijlage', $post_id ) ? '&#10004' : '-';
				break;
			default :
				break;
		}
	}

	public function addSortableColumns( $columns ) {
		$columns['date-event'] = 'date-event';

		return $columns;
	}

	public function sortByEventDate( $vars ) {
		if ( isset( $vars['post_type'] ) && 'event' == $vars['post_type'] ) {

			if ( isset( $vars['orderby'] ) && 'date-event' == $vars['orderby'] ) {

				$vars = array_merge(
					$vars,
					array(
						'meta_key' => 'e_date_time',
						'orderby' => 'meta_value'
					)
				);
			}
		}

		return $vars;
	}

	public function createJsGlobals() {
		wp_localize_script( 'main', 'ajax_get_events', admin_url( 'admin-ajax.php?action=get_events' ) );
	}

	public function redirectPastEvent() {
		global $post;
		if( get_post_type( $post ) == 'event' ) {
			$event = new Event( $post );
			if( $event->isPastEvent() ) {
				wp_redirect( $this->getEventsOverviewUrl() );
				exit;
			}
		}
	}

	public function getEventsOverviewUrl() {
		$events_post = get_post_by_template( 'events.php' );
		if( $events_post instanceof WP_Post) {
			return get_permalink( $events_post );
		}
	}
}

/**
 * Returns the main instance of Events.
 */
function EVENTS() {
	return Events::instance();
}

EVENTS();
?>