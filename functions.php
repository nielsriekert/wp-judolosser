<?php
if(function_exists('add_theme_support')) {
	add_theme_support('menus');
	
	add_theme_support('post-thumbnails');
	
	register_nav_menus(array('headernav' => 'Hoofdmenu', 'footernavigatie' => 'Footermenu'));

	add_theme_support( 'html5', array('search-form', 'comment-form', 'comment-list', 'gallery', 'caption' ) );
}

add_editor_style();

function cc_mime_types( $mimes ){
	$mimes['svg'] = 'image/svg+xml';
	return $mimes;
}
add_filter( 'upload_mimes', 'cc_mime_types' );


add_action('wp_enqueue_scripts', 'scripts');

function scripts() {
	wp_enqueue_script(
	 'main',
		get_template_directory_uri() . '/js/main.min.js',
		array(),
		false,
		true
	);

	wp_deregister_script( 'wp-embed' );
}

function new_excerpt_length($length) {
		return 30;
}

add_filter('excerpt_length', 'new_excerpt_length');

if(function_exists('add_image_size')){
	add_image_size('bericht-thumb', 500, 375, true);
	add_image_size('bericht', 800, 600);
	add_image_size('card', 400, 300, true);
	add_image_size('media-thumb', 300, 200, true);
	add_image_size('media-full', 1000, 800);
}

add_filter( 'image_size_names_choose', 'add_to_post_media_selecter' );

function add_to_post_media_selecter( $sizes ) {
	return array_merge( $sizes, array(
		'bericht-thumb' => __('Bericht'),
	) );
}


/* //////// */
/* UTILITY  */
/* //////// */

function humanize_date($date, $dateformat = 'j F Y', $tolerantie = 2){
	if(date_i18n('Ymd', time()) == date_i18n('Ymd', strtotime($date)) && $tolerantie > 0){
		return 'vandaag';
	}
	else if(date_i18n('Ymd', strtotime('+1 day')) == date_i18n('Ymd', strtotime($date)) && $tolerantie > 1){
		return 'morgen';
	}
	else if(date_i18n('Ymd', strtotime('-1 day')) == date_i18n('Ymd', strtotime($date)) && $tolerantie > 1){
		return 'gisteren';
	}
	else {
		return date_i18n($dateformat, strtotime($date));
	}
}


function getSocialMedia($return_follow_us_text = false){
	$facebook_account = get_theme_mod('facebook');
	$twitter_account = get_theme_mod('twitter');
	
	$sm_array = array();
	if($facebook_account){ $sm_array['facebook'] = array('url' => 'https://www.facebook.com/', 'account' => $facebook_account);}
	if($twitter_account){ $sm_array['twitter'] = array('url' => 'https://www.twitter.com/', 'account' => $twitter_account);}
	
	if(count($sm_array) > 0){
		return $sm_array;
	}
	else {
		return false;
	}
}


/* ///////////// */
/* POSTS 2 POSTS */
/* ///////////// */

function connection_types() {
	p2p_register_connection_type( array(
		'name' => 'module_to_page',
		'from' => 'module',
		'to' => 'page',
		'sortable' => 'to'
	));

	p2p_register_connection_type( array(
		'name' => 'button_to_module',
		'from' => 'button',
		'to' => 'module'
	));
}

add_action( 'p2p_init', 'connection_types' );


/* ////////////// */
/* CUSTOM COLUMNS */
/* ////////////// */

add_filter('manage_edit-module_columns', 'module_columns') ;

function module_columns($columns){
	$columns = array(
		'cb' => '<input type="checkbox" />',
		'title' => 'Titel',
		'author' => 'Auteur',
		'type' => 'Type',
		'standaard' => 'Standaard',
		'date' => 'Datum'
	);

	return $columns;
}

add_action('manage_module_posts_custom_column', 'manage_module_columns', 10, 2 );

function manage_module_columns($column, $post_id){
	global $post;

	switch($column){
		case 'type' :
			$type = get_field('m_type', $post_id);
			if (empty($type))
					echo '-';
			else
					echo ucfirst(preg_replace('/_/', ' ', $type));
			break;
		case 'standaard' :
			if(get_field('m_gebruik_standaard', $post_id))
					echo '&#10004';
			break;
		default :
			break;
	}
}

/* /////////*/
/* CLEANING */
/* //////// */

function disable_wp_emojicons() {

	// all actions related to emojis
	remove_action( 'admin_print_styles', 'print_emoji_styles' );
	remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
	remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
	remove_action( 'wp_print_styles', 'print_emoji_styles' );
	remove_filter( 'wp_mail', 'wp_staticize_emoji_for_email' );
	remove_filter( 'the_content_feed', 'wp_staticize_emoji' );
	remove_filter( 'comment_text_rss', 'wp_staticize_emoji' );
}
add_action( 'init', 'disable_wp_emojicons' );

remove_action('wp_head', 'wlwmanifest_link');


/* ////////*/
/* WALKERS */
/* /////// */


/* /////////// */
/* SHORT CODES */
/* /////////// */

function shortcode_fotos( $atts ){
	ob_start();
	get_template_part('includes/include', 'fotos');
	return ob_get_clean();
}
add_shortcode( 'fotos', 'shortcode_fotos' );

/* ///////////////// */
/* CUSTOM POST TYPES */
/* ///////////////// */

function create_post_types() {

	$labels = array(
		'name' => 'Evenementen',
		'singular_name' => 'Evenement',
		'add_new' => 'Nieuw evenement', 'evenement',
		'add_new_item' => 'Voeg nieuw evenement toe',
		'edit_item' => 'Bewerk evenement',
		'new_item' => 'Nieuw evenement',
		'view_item' => 'Bekijk evenement',
		'search_items' => 'Zoek evenementen',
		'not_found' => 'Geen evenementen gevonden',
		'not_found_in_trash' => 'Geen evenementen gevonden in de prullenbak', 
		'all_items' => 'Alle evenementen',
		'parent_item_colon'  => '',
		'menu_name' => 'Evenementen'
	);

	$args = array(
		'labels' => $labels,
		'taxonomies' => array('category'),
		'description' => 'Evenementen van Judo Losser',
		'public' => true,
		'menu_position' => 5,
		'supports' => array( 'title', 'thumbnail', 'editor', 'excerpt', 'author', 'revisions'),
		//'has_archive' => false,
		'menu_icon' => 'dashicons-calendar'
	);

	register_post_type('evenement', $args);

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
		'supports' => array( 'title', 'thumbnail', 'editor', 'excerpt', 'author', 'revisions'),
		'menu_icon' => 'dashicons-format-gallery'
	);

	register_post_type('photoalbum', $args);
}

add_action( 'init', 'create_post_types' );


/* ///////////// */
/* SETTING PAGES */
/* ///////////// */

function customize_template( $wp_customize ) {
	class WP_Customize_Textarea_Control extends WP_Customize_Control {
		public $type = 'textarea';
		public function render_content() {
			?>
			<label>
				<span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
				<textarea rows="5" style="width:100%;" <?php $this->link(); ?>><?php echo esc_textarea( $this->value() ); ?></textarea>
			</label>
			<?php
		}
	}
	class WP_Customize_Image_Control_SVG extends WP_Customize_Image_Control {
		public function __construct( $manager, $id, $args = array()) {
			parent::__construct( $manager, $id, $args );
			$this->remove_tab('uploaded');
			$this->extensions = array( 'jpg', 'jpeg', 'gif', 'png', 'svg' );
		}
	}

	/*// SOCIAL //*/
	
	$wp_customize->add_section('social', array(
		'title' => 'Social',
		'priority' => 45,
		'description' => 'Het account naam kunt u vinden door op de social media pagina het gedeelte van de url achter de domeinnaam te kopiëren.<br />Bijvoorbeeld: www.facebook.com/#######/ of www.twitter.com/#######.'
	));
	
	/* facebook */
	
	$wp_customize->add_setting('facebook');
	
	$wp_customize->add_control( new WP_Customize_Control($wp_customize, 'facebook', array(
		'label' => 'Facebook account',
		'section' => 'social',
		'settings' => 'facebook',
	)));
	
	/* twitter */

	$wp_customize->add_setting('twitter');
	
	$wp_customize->add_control( new WP_Customize_Control($wp_customize, 'twitter', array(
		'label' => 'Twitter account',
		'section' => 'social',
		'settings' => 'twitter',
	)));

	/*// CODE //*/
	
	$wp_customize->add_section('ons_tracking_code' , array(
		'title' => 'Tracking code',
		'priority' => 50,
		'capability' => 'administrator'
	));
	
	/* tracking code */
	
	$wp_customize->add_setting('tracking_code');
	
	$wp_customize->add_control( new WP_Customize_Textarea_Control($wp_customize, 'tracking_code', array(
		'label' => 'Plaats (tracking) code',
		'section' => 'ons_tracking_code',
		'settings' => 'tracking_code',
		'type' => 'textarea'
	)));
}

add_action( 'customize_register', 'customize_template' );


/* ///// */
/* ROLES */
/* ///// */

add_action('after_switch_theme', 'judolosser_setup_options');

function one_pager_setup_options () {
	add_role( 'klant', 'Klant', array(
		'delete_others_pages' => true,
		'delete_others_posts' => true,
		'delete_pages' => true,
		'delete_posts' => true,
		'delete_private_pages' => true,
		'delete_private_posts' => true,
		'delete_published_pages' => true,
		'delete_published_posts' => true,
		'edit_others_pages' => true,
		'edit_others_posts' => true,
		'edit_pages' => true,
		'edit_posts' => true,
		'edit_private_pages' => true,
		'edit_private_posts' => true,
		'edit_published_pages' => true,
		'edit_published_posts' => true,
		'edit_theme_options' => true,
		'manage_categories' => true,
		'manage_links' => true,
		'publish_pages' => true,
		'publish_posts' => true,
		'read' => true,
		'read_private_pages' => true,
		'read_private_posts' => true,
		'unfiltered_html' => true,
		'unfiltered_upload' => true,
		'upload_files' => true,
		'manage_options' => true,
		'wpseo_bulk_edit' => true
		)
	);
};

add_action('switch_theme', 'judolosser_deactivate_options');

function one_pager_deactivate_options () {
	remove_role('klant');
}

?>