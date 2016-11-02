<?php
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

	add_editor_style(array('editor-style.css', google_fonts_url(true), fontscom_fonts_url()));
}
add_action( 'after_setup_theme', 'theme_setup' );


/* /////// */
/* SCRIPTS */
/* /////// */

function theme_scripts() {
	//styles
	wp_enqueue_style( 'google-fonts', google_fonts_url(), array(), false );

	//javascript
	wp_enqueue_script(
	 'main',
		get_template_directory_uri() . '/js/main.min.js',
		array(),
		false,
		true
	);
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

add_action( 'admin_menu', 'judo_losser_menu' );

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
}

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
		$rhtml .= '</tr>';
		foreach($trainingstijden as $dag){
			$counter = 0;
			foreach($dag['t_training'] as $training){$counter ++;
			?>
		<tr>
			<?php if($counter === 1){
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
/* POSTS 2 POSTS */
/* ///////////// */

function connection_types() {
	/*p2p_register_connection_type( array(
		'name' => 'module_to_page',
		'from' => 'module',
		'to' => 'page',
		'sortable' => 'to'
	));

	p2p_register_connection_type( array(
		'name' => 'button_to_module',
		'from' => 'button',
		'to' => 'module'
	));*/
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
		'rewrite' => array('slug' => 'evenement'),
		'menu_position' => 5,
		'supports' => array( 'title', 'thumbnail', 'editor', 'excerpt', 'author', 'revisions'),
		//'has_archive' => false,
		'menu_icon' => 'dashicons-calendar'
	);

	register_post_type('event', $args);

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


/* /////////// */
/* USER FIELDS */
/* /////////// */

function get_bestuursrollen(){
	return array(
		'Voorzitter',
		'Secretaris',
		'Penningmeester',
		'Technisch coordinator',
		'Algemeen'
	);
}

function show_extra_profile_fields( $user ) { ?>

	<h3>Leden informatie</h3>
	<table class="form-table">
		<tr>
			<th><label for="boardmember">Bestuursrol</label></th>
			<td>
				<?php
				$bestuursrollen = get_bestuursrollen();
				?>
				<select name="boardmember">
					<option value="">- geen -</option>
					<?php
					$user_boardmember = get_the_author_meta('boardmember', $user->ID);
					foreach($bestuursrollen as $rol){?>
					<option value="<?php echo $rol; ?>"<?php if($user_boardmember == $rol){?> selected="selected"<?php } ?>><?php echo $rol; ?></option>
					<?php }
					?>
				</select>
			</td>
		</tr>
		<tr>
			<th><label for="address">Straat en huisnummer</label></th>
			<td>
				<input type="text" name="address" id="address" value="<?php echo esc_attr( get_the_author_meta( 'address', $user->ID ) ); ?>" class="regular-text" /><br />
			</td>
		</tr>
		<tr>
			<th><label for="postcode">Postcode</label></th>
			<td>
				<input type="text" name="postcode" id="postcode" value="<?php echo esc_attr( get_the_author_meta( 'postcode', $user->ID ) ); ?>" class="regular-text" /><br />
			</td>
		</tr>
		<tr>
			<th><label for="town">Plaats</label></th>
			<td>
				<input type="text" name="town" id="town" value="<?php echo esc_attr( get_the_author_meta( 'town', $user->ID ) ); ?>" class="regular-text" /><br />
			</td>
		</tr>
		<tr>
			<th><label for="phone-home">Telefoon (thuis)</label></th>
			<td>
				<input type="tel" name="phonehome" id="phonehome" value="<?php echo esc_attr( get_the_author_meta( 'phonehome', $user->ID ) ); ?>" class="regular-text" /><br />
			</td>
		</tr>
		<tr>
			<th><label for="phone">Telefoon (mobiel)</label></th>
			<td>
				<input type="tel" name="phone" id="phone" value="<?php echo esc_attr( get_the_author_meta( 'phone', $user->ID ) ); ?>" class="regular-text" /><br />
			</td>
		</tr>
	</table>
<?php
}

add_action( 'show_user_profile', 'show_extra_profile_fields' );
add_action( 'edit_user_profile', 'show_extra_profile_fields' );


function save_extra_profile_fields($user_id){

	if(!current_user_can('edit_user'))
		return false;

	update_user_meta($user_id, 'boardmember', $_POST['boardmember']);
	update_user_meta($user_id, 'address', $_POST['address']);
	update_user_meta($user_id, 'postcode', $_POST['postcode']);
	update_user_meta($user_id, 'town', $_POST['town']);
	update_user_meta($user_id, 'phonehome', $_POST['phonehome']);
	update_user_meta($user_id, 'phone', $_POST['phone']);
}

add_action( 'personal_options_update', 'save_extra_profile_fields' );
add_action( 'edit_user_profile_update', 'save_extra_profile_fields' );

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


/* ///// */
/* ROLES */
/* ///// */

add_action('after_switch_theme', 'judolosser_setup_options');

function judolosser_setup_options () {
	add_role( 'manager', 'Manager', array(
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

function judolosser_deactivate_options () {
	remove_role('manager');
}
?>