<?php
class Locations {

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
		define( 'LOCATIONS_ABSPATH', dirname( __FILE__ ) . '/' );
	}

	public function includes() {
		require_once( LOCATIONS_ABSPATH . 'location/class-location.php' );
		require_once( LOCATIONS_ABSPATH . 'location/class-location-image.php' );

		require_once( LOCATIONS_ABSPATH . 'location/class-location-model.php' );
		require_once( LOCATIONS_ABSPATH . 'location/class-location-view.php' );
	}

	private function initHooks() {
		add_action( 'after_setup_theme', array( $this, 'addImageSizes' ) );
		add_action( 'init', array( $this, 'createPostTypes' ) );
		add_action( 'acf/init', array( $this, 'addLocationFields' ) );

		add_filter( 'manage_edit-location_columns', array( $this, 'addAdminColumns' ), 10, 1 );
		add_action( 'manage_location_posts_custom_column', array( $this, 'renderAdminRows' ), 10, 2 );
		add_filter( 'manage_edit-location_sortable_columns', array( $this, 'addSortableColumns' ), 10, 1 );
	}

	/**
	 * Adds WordPress images sizes
	 */
	public function addImageSizes() {
		add_image_size( 'location', 750, 500, true );
	}

	public function createPostTypes() {
		$labels = array(
			'name' => _x( 'Locations', 'post type general name', 'judo-losser' ),
			'singular_name' => _x( 'Location', 'post type singular name', 'judo-losser' ),
			'menu_name' => _x( 'Locations', 'admin menu', 'judo-losser' ),
			'name_admin_bar' => _x( 'Location', 'add new on admin bar', 'judo-losser' ),
			'add_new' => _x( 'Add New', 'location', 'judo-losser' ),
			'add_new_item' => __( 'Add New Location', 'judo-losser' ),
			'new_item' => __( 'New Location', 'judo-losser' ),
			'edit_item' => __( 'Edit Location', 'judo-losser' ),
			'view_item' => __( 'View Location', 'judo-losser' ),
			'all_items' => __( 'All Locations', 'judo-losser' ),
			'search_items' => __( 'Search Locations', 'judo-losser' ),
			'parent_item_colon' => __( 'Parent Locations:', 'judo-losser' ),
			'not_found' => __( 'No locations found.', 'judo-losser' ),
			'not_found_in_trash' => __( 'No locations found in Trash.', 'judo-losser' )
		);

		$args = array(
			'labels' => $labels,
			'description' => __( 'Judo Losser Locations', 'judo-losser' ),
			'public' => false,
			'show_ui' => true,
			'rewrite' => false,
			'show_in_menu' => 'edit.php?post_type=event',
			'supports' => array( 'title', 'author', 'revisions' ),
			'capability_type' => 'location',
			'map_meta_cap' => true,
		);

		register_post_type( 'location', $args );
	}

	public function addLocationFields() {
		acf_add_local_field_group(array (
			'key' => 'group_582afe635a2f3',
			'title' => __( 'Location', 'judo-losser' ),
			'fields' => array (
				array (
					'key' => 'field_59f5ac2dcb7e1',
					'label' => __( 'Address', 'judo-losser' ),
					'name' => '',
					'type' => 'tab',
				),
				array(
					'key' => 'field_59af1f9a431cf',
					'label' => __( 'Street', 'judo-losser' ),
					'name' => 'location_street',
					'type' => 'text',
					'placeholder' => __( 'Street or alternative', 'judo-losser' ),
				),
				array(
					'key' => 'field_54ea13fa42bec',
					'label' => __( 'House number', 'judo-losser' ),
					'name' => 'location_street_number',
					'type' => 'text',
				),
				// array (
				// 	'key' => 'field_5c5a1e2f78be2',
				// 	'label' => __( 'Additional address information', 'judo-losser' ),
				// 	'name' => 'location_address_additional',
				// 	'type' => 'text',
				// 	'placeholder' => __( 'Apartment, suite, unit, building, floor, etc.', 'judo-losser' ),
				// ),
				array (
					'key' => 'field_527e3ed48e2a2',
					'label' => __( 'Postcode', 'judo-losser' ),
					'name' => 'location_postcode',
					'type' => 'text'
				),
				array (
					'key' => 'field_597e2f2427e2f',
					'label' => __( 'Locality', 'judo-losser' ),
					'name' => 'location_locality',
					'type' => 'text'
				),
				// array (
				// 	'key' => 'field_5c45af2d9f59f',
				// 	'label' => __( 'Location', 'judo-losser' ),
				// 	'name' => '',
				// 	'type' => 'tab',
				// ),
				// array (
				// 	'key' => 'field_5c80f32ef3e9a',
				// 	'label' => __( 'Location', 'judo-losser' ),
				// 	'name' => 'location_coordinates',
				// 	'type' => 'group',
				// 	'layout' => 'block',
				// 	'sub_fields' => array (
				// 		array (
				// 			'key' => 'field_59e0a3a3f3a9c',
				// 			'label' => __( 'Latitude', 'judo-losser' ),
				// 			'name' => 'location_latitude',
				// 			'type' => 'text',
				// 			'placeholder' => '##.#######',
				// 		),
				// 		array (
				// 			'key' => 'field_52e1f3b1c1e9a',
				// 			'label' => __( 'Longitude', 'judo-losser' ),
				// 			'name' => 'location_longitude',
				// 			'type' => 'text',
				// 			'placeholder' => '##.#######',
				// 		),
				// 	),
				// ),
				array (
					'key' => 'field_59f43aed3ff2e',
					'label' => __( 'Photo', 'judo-losser' ),
					'name' => '',
					'type' => 'tab',
				),
				array (
					'key' => 'field_57b28a3f2718f',
					'label' => __( 'Photo', 'judo-losser' ),
					'name' => 'location_featured_photo',
					'type' => 'image',
				),
			),
			'location' => array (
				array (
					array (
						'param' => 'post_type',
						'operator' => '==',
						'value' => 'location',
					),
				),
			),
		));
	}

	public function addAdminColumns( $columns ) {

		$columns_new = array();

		foreach( $columns as $key => $value ) {
			$columns_new[$key] = $value;
			if( $key == 'title' || end( $columns ) === $value ) {
				$columns_new['location-address'] = __( 'Address', 'judo-losser' );
				$columns_new['location-photo'] = __( 'Photo', 'judo-losser' );
			}
		}

		return $columns_new;
	}


	public function renderAdminRows( $column, $post_id ) {
		switch( $column ) {
			case 'location-address':
				$location = LocationModel::getLocation( $post_id );

				if( $location->getFullAddress() ) {
					echo $location->getFullAddress();
				}
				else {
					echo '-';
				}
				break;
			case 'location-photo':
				$location = LocationModel::getLocation( $post_id );

				if( $location->hasFeaturedPhoto() ) {
					$image = $location->getFeaturedPhoto( 'thumbnail' );
					echo '<img src="' . $image->getSrc() . '">';
				}
				else {
					echo '-';
				}
				break;
			default :
				break;
		}
	}
}

/**
 * Returns the main instance of Locations.
 */
function LOCATIONS() {
	return Locations::instance();
}

LOCATIONS();
?>