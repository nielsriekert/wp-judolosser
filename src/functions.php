<?php
require_once( 'vendor/autoload.php' );
if( getenv("ENVIRONMENT") === 'development' ) {
	\Tracy\Debugger::enable( \Tracy\Debugger::DEVELOPMENT );
}

// include required core files
require_once('classes/class-webpack-helper.php');
require_once('classes/class-users.php');
require_once('classes/class-articles.php');
require_once('classes/class-events.php');
require_once('classes/class-pages.php');
require_once('classes/class-photoalbums.php');

function theme_setup() {
	add_theme_support('menus');

	add_theme_support('post-thumbnails');
	set_post_thumbnail_size( 500, 375, true );

	add_post_type_support( 'page', 'excerpt' );

	register_nav_menus(array(
		'headernav' => 'Hoofdmenu',
		'footernav' => 'Footermenu'
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
	add_theme_support('html5', array(
		'search-form',
		'comment-form',
		'comment-list',
		'gallery',
		'caption'
	));

	add_image_size('post-thumb', 500, 375, true);
	add_image_size('post', 1920, 1080);
	add_image_size('card', 400, 300, true);
	add_image_size('media-thumb', 300, 200, true);
	add_image_size('media-full', 1200, 1100);

	$webpack_helper = new WebpackHelper();
	$editorcss = $webpack_helper->getHashedAssetUrl( 'editor-style.css' );

	if($editorcss){
		add_editor_style(array($editorcss, google_fonts_url(true), fontscom_fonts_url()));
	}
}
add_action( 'after_setup_theme', 'theme_setup' );


/* /////// */
/* SCRIPTS */
/* /////// */

function theme_scripts() {
	$webpack_helper = new WebpackHelper();
	$maincss = $webpack_helper->getHashedAssetUrl( 'main.css' );
	$mainjs = $webpack_helper->getHashedAssetUrl( 'main.js' );

	//styles
	wp_enqueue_style( 'google-fonts', google_fonts_url(), array(), false );

	if( $maincss ) {
		wp_enqueue_style(
			'judolosser-style',
			get_template_directory_uri() . '/' . $maincss,
			array(),
			wp_get_theme()->get('Version')
		);
	}

	if( $mainjs ) {
		wp_enqueue_script(
			'main',
			get_template_directory_uri() . '/' . $mainjs,
			array(),
			wp_get_theme()->get('Version'),
			true
		);
	}

	wp_localize_script('main', 'wp', array(
		'templateDirectory' => get_bloginfo('template_directory')
	));

	wp_enqueue_script('main');

	wp_deregister_style( 'wp-block-library' );
}

add_action('wp_enqueue_scripts', 'theme_scripts');


/* ///// */
/* FONTS */
/* ///// */

function google_fonts_url($urlencode = false) {
	$fonts_url = '';
	$fonts = array();

	$fonts[] = 'Lato:400,700';
	$fonts[] = 'Roboto+Slab:400,700';

	if($urlencode){
		$family = urlencode(implode( '|', $fonts ));
	}
	else {
		$family = implode( '|', $fonts );
	}

	if ( $fonts ) {
		$fonts_url = add_query_arg( array(
			'family' => $family,
		), 'https://fonts.googleapis.com/css' );
	}

	return $fonts_url;
}


function fontscom_fonts_url() {
	$fonts_url = '//fast.fonts.net/cssapi/11f4e116-36b1-4853-877a-76a60cd83d95.css';

	return $fonts_url;
}

/* /////////// */
/* JUDO LOSSER */
/* /////////// */

/*add_action( 'admin_menu', 'judo_losser_menu' );

function judo_losser_menu() {
	//add_options_page( 'My Plugin Options', 'My Plugin', 'manage_options', 'my-unique-identifier', 'my_plugin_options' );

	add_menu_page('Judo Losser', 'Judo Losser', 'manage_options', 'judo-losser', 'setup_judo_losser', 'dashicons-admin-site', 31);
}

function setup_judo_losser() {
	if ( !current_user_can( 'manage_options' ) )  {
		wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
	}
	echo '<div class="wrap">';
	echo '<p>Here is where the form would go if I actually had options.</p>';
	echo '</div>';
}*/

function get_training_times($post_id = false, $columns = 'all'){
	if(!$post_id){
		wp_reset_query();
		$pages = get_pages(array(
			'meta_key' => '_wp_page_template',
			'meta_value' => 'trainingstijden.php'
		));
		if(count($pages) == 1){
			$post_id = current($pages)->ID;
		}
		else {
			return false;
		}
	}
	$trainingstijden = CFS()->get('t_trainingstijden', $post_id);
	if($trainingstijden){
	$rhtml = '<table class="trainingstijden">
		<tr>
			<th>Dag</th>';

			if($columns == 'all' || array_search('tijd', $columns) !== false){
				$rhtml .= '<th>Tijd</th>';
			}
			if($columns == 'all' || array_search('trainer', $columns) !== false){
				$rhtml .= '<th>Trainer</th>';
			}
			if($columns == 'all' || array_search('training', $columns) !== false){
				$rhtml .= '<th>Soort training</th>';
			}
			if($columns == 'all' || array_search('locatie', $columns) !== false){
				$rhtml .= '<th>Locatie</th>';
			}
		$rhtml .= '</tr>' . "\n\r";
		foreach($trainingstijden as $dag){
			$counter = 0;
			foreach($dag['t_training'] as $training){$counter ++;

			$rhtml .= '<tr>' . "\n\r";
			if($counter === 1){
			$rhtml .= '<td class="trainingstijden-dag" data-column-name="Dag" rowspan="' . count($dag['t_training']) . '">' . current($dag['t_dag']) . '</td>';
			}
			if($columns == 'all' || array_search('tijd', $columns) !== false){
				$rhtml .= '<td class="trainingstijden-tijd" data-column-name="Tijd">' . current($training['t_tijd_van_uren']) . ':' . current($training['t_tijd_van_minuten']) . ' t/m ' .  current($training['t_tijd_tot_uren']) . ':' . current($training['t_tijd_tot_minuten']) . '</td>' . "\n\r";
			}
			if($columns == 'all' || array_search('trainer', $columns) !== false){
				$rhtml .= '<td class="trainingstijden-trainer" data-column-name="Trainer">' . $training['t_trainer'] . '</td>' . "\n\r";
			}
			if($columns == 'all' || array_search('training', $columns) !== false){
				$rhtml .= '<td class="trainingstijden-training" data-column-name="Training">' . $training['t_soort_training'] . '</td>' . "\n\r";
			}
			if($columns == 'all' || array_search('locatie', $columns) !== false){
				$rhtml .= '<td class="trainingstijden-locatie" data-column-name="Locatie">' . $training['t_locatie'] . '</td>' . "\n\r";
			}
		$rhtml .= '</tr>';
			}
		}
	$rhtml .= '</table>'. "\n\r";
	return $rhtml;
	}
	else {
		return false;
	}
}


/* ///// */
/* OTHER */
/* ///// */

function cc_mime_types( $mimes ){
	$mimes['svg'] = 'image/svg+xml';
	return $mimes;
}
add_filter( 'upload_mimes', 'cc_mime_types' );


function new_excerpt_length($length) {
	return 30;
}

add_filter('excerpt_length', 'new_excerpt_length');


add_filter( 'image_size_names_choose', 'add_to_post_media_selecter' );

function add_to_post_media_selecter( $sizes ) {
	return array_merge( $sizes, array(
			'bericht-thumb' => __('Bericht'),
	) );
}


/* ////////// */
/* ADMIN MENU */
/* ////////// */

/*function add_admin_menu_separators() {

	$positions = array(21, 24);

	global $menu;

	foreach( $positions as $position ){
		$menu[$position] = array('', 'read', 'separator' . $position, '', 'wp-menu-separator');
	}

}
add_action( 'admin_menu', 'add_admin_menu_separators' );*/

function judolosser_cleanup_menu() {
	remove_menu_page( 'edit-comments.php' );
}
add_action( 'admin_menu', 'judolosser_cleanup_menu' );


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


function get_post_by_template( $template ) {
	if( gettype( $template ) != 'string' ) {
		return false;
	}

	$posts = new WP_Query(array(
		'post_type'  => 'page',
		'meta_query' => array(
			array(
				'key'   => '_wp_page_template',
				'value' => $template
			)
		)
	));

	if( $posts->post_count == 1 ) {
		return current( $posts->posts );
	}
	else {
		return false;
	}
}

function get_card_navigation($post = false, $template = false, $txt_article = 'Lees verder', $txt_all = 'Alles'){
	$html_navigation = '';

	$links = array();

	if($post){
		$links[] = array(
			'text' => $txt_article,
			'link' => get_permalink($post->ID),
			'class' => 'card-navigation-article'
		);
	}

	if($template){
		$pages = get_pages(array(
			'meta_key' => '_wp_page_template',
			'meta_value' => $template
		));

		if(count($pages) == 1){
			$links[] = array(
				'text' => $txt_all,
				'link' => get_permalink(current($pages)->ID),
				'class' => 'card-navigation-all'
			);
		}
	}

	$html_navigation .= '<nav class="card-navigation card-navigation-count-'. count($links) . '">';
	foreach($links as $button){
		$html_navigation .= '<a class="card-button';
		if(null !== $button['class'] && $button['class']){
			$html_navigation .= ' ' . $button['class'];
		}
		$html_navigation .= '" href="' . $button['link'] . '">' . $button['text'] . '</a>';
	}
	$html_navigation .= '</nav>';

	return $html_navigation;
}


/* ///////////// */
/* WP ALL IMPORT */
/* ///////////// */

function map_user_id($old_user_id){
	switch($old_user_id){
		case 2:
			return 3;
			break;
		case 6:
			return 2;
			break;
		case 10:
			return 9;
			break;
		case 1:
			return 1;
			break;
		default:
			return 1;
			break;
	}
}


/* ///////////// */
/* POSTS 2 POSTS */
/* ///////////// */

function connection_types() {
	p2p_register_connection_type( array(
		'name' => 'post_to_photoalbum',
		'from' => 'post',
		'to' => 'photoalbum',
		'cardinality' => 'one-to-one'
	));

	p2p_register_connection_type( array(
		'name' => 'event_to_photoalbum',
		'from' => 'event',
		'to' => 'photoalbum',
		'cardinality' => 'one-to-one'
	));
}

add_action( 'p2p_init', 'connection_types' );


/* ////////////// */
/* CUSTOM COLUMNS */
/* ////////////// */

get_template_part('includes/custom', 'columns');

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

function disable_embeds_init() {
	/* @var WP $wp */
	global $wp;

	// Remove the embed query var.
	$wp->public_query_vars = array_diff( $wp->public_query_vars, array(
		'embed',
	) );

	// Remove the REST API endpoint.
	remove_action( 'rest_api_init', 'wp_oembed_register_route' );

	// Turn off oEmbed auto discovery.
	add_filter( 'embed_oembed_discover', '__return_false' );

	// Don't filter oEmbed results.
	remove_filter( 'oembed_dataparse', 'wp_filter_oembed_result', 10 );

	// Remove oEmbed discovery links.
	remove_action( 'wp_head', 'wp_oembed_add_discovery_links' );

	// Remove oEmbed-specific JavaScript from the front-end and back-end.
	remove_action( 'wp_head', 'wp_oembed_add_host_js' );
	add_filter( 'tiny_mce_plugins', 'disable_embeds_tiny_mce_plugin' );

	// Remove filter of the oEmbed result before any HTTP requests are made.
	remove_filter( 'pre_oembed_result', 'wp_filter_pre_oembed_result', 10 );
}

add_action( 'init', 'disable_embeds_init', 9999 );

function disable_embeds_tiny_mce_plugin( $plugins ) {
	return array_diff( $plugins, array( 'wpembed' ) );
}


/* /////////// */
/* SHORT CODES */
/* /////////// */

function shortcode_fotos( $atts ){
	ob_start();
	get_template_part('includes/include', 'fotos');
	return ob_get_clean();
}
add_shortcode( 'fotos', 'shortcode_fotos' );


/* //////////////////////////////// */
/* CUSTOM POST TYPES AND TAXONOMIES */
/* //////////////////////////////// */

function create_post_types() {

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

	$wp_customize->add_section('tracking_code' , array(
		'title' => 'Tracking code',
		'priority' => 50,
		'capability' => 'administrator'
	));

	/* tracking code */

	$wp_customize->add_setting('code-head');

	$wp_customize->add_control( new WP_Customize_Textarea_Control($wp_customize, 'code-head', array(
		'label' => 'Plaats (tracking) code (head)',
		'section' => 'tracking_code',
		'settings' => 'code-head',
		'type' => 'textarea'
	)));

	$wp_customize->add_setting('code-body');

	$wp_customize->add_control( new WP_Customize_Textarea_Control($wp_customize, 'code-body', array(
		'label' => 'Plaats (tracking) code (body)',
		'section' => 'tracking_code',
		'settings' => 'code-body',
		'type' => 'textarea'
	)));
}

add_action( 'customize_register', 'customize_template' );
?>