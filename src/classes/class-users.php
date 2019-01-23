<?php
class Users {

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
		define( 'USERS_ABSPATH', dirname( __FILE__ ) . '/' );
	}

	public function includes() {
		require_once( USERS_ABSPATH . 'user/class-user-model.php' );
		require_once( USERS_ABSPATH . 'user/class-user-view.php' );
		require_once( USERS_ABSPATH . 'user/class-user.php' );
	}

	/**
	 * Hook into actions and filters.
	 */
	private function initHooks() {
		add_action( 'after_switch_theme',  array( $this, 'addBackendRoles' ) );
		add_action( 'switch_theme',  array( $this, 'removeRoles' ) );

		add_action( 'show_user_profile', array( $this, 'addExtraProfileFields' ), 10, 1 );
		add_action( 'edit_user_profile', array( $this, 'addExtraProfileFields' ), 10, 1 );
		add_action( 'personal_options_update', array( $this, 'saveExtraProfileFields' ), 10, 1 );
		add_action( 'edit_user_profile_update', array( $this, 'saveExtraProfileFields' ), 10, 1 );
	}

	/**
	 * Adds all the user roles for the backend
	 *
	 * @author Niels Riekert
	 */
	public function addBackendRoles() {

		$custom_post_type_caps = USERS()->getCustomPostTypeCaps();

		$manager_member_caps = array(
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
		);

		add_role( 'manager', __( 'Manager', 'judo-losser' ), array_merge( $manager_member_caps, $custom_post_type_caps )  );

		$administrator = get_role('administrator');

		foreach( $custom_post_type_caps as $key => $value ) {
			$administrator->add_cap( $key );
			$administrator->add_cap( 'modern_forms_edit_forms' );
			$administrator->add_cap( 'modern_forms_edit_entries' );
		}

		// remove unused build in roles
		if( get_role( 'editor' ) ) {
			remove_role( 'editor' );
		}

		if( get_role( 'contributor' ) ) {
			remove_role( 'contributor' );
		}

		if( get_role( 'author' ) ) {
			remove_role( 'author' );
		}

		if( get_role( 'subscriber' ) ) {
			remove_role( 'subscriber' );
		}
	}

	/**
	 * Adds extra profile fields
	 *
	 * @author Niels Riekert
	 */
	public function removeRoles() {
		remove_role( 'manager' );
	}

	/**
	 * Gets the capabilities for custom post types.
	 *
	 * @param array custom post types. Defaults to all.
	 * @return array capabilities.
	 * @author Niels Riekert
	 */
	public function getCustomPostTypeCaps( $post_types = array() ) {
		// generate all custom post type caps
		$custom_post_type_caps_prefixes = array(
			'read_private',
			'edit',
			'edit_others',
			'edit_private',
			'edit_published',
			'publish',
			'delete',
			'delete_others',
			'delete_private',
			'delete_published'
		);

		$custom_post_types = array(
			'event',
		);

		$custom_post_type_caps = array();
		foreach( $custom_post_types as $custom_post_type ) {
			if( is_array( $post_types) && count( $post_types) > 0 && array_search( $custom_post_type, $post_types ) === false ) {
				continue;
			}

			foreach( $custom_post_type_caps_prefixes as $custom_post_type_caps_prefix ) {
				$custom_post_type_caps[$custom_post_type_caps_prefix . '_' . $custom_post_type . 's'] = true;
			}
		}

		return $custom_post_type_caps;
	}

	/**
	 * Adds extra profile fields
	 *
	 * @author Niels Riekert
	 */
	public function addExtraProfileFields( $user ) {
		?>
		<h3>Leden informatie</h3>
		<table class="form-table">
			<tr>
				<th><label for="boardmember">Bestuursrol</label></th>
				<td>
					<?php
					$bestuursrollen = $this->getBoardRoles();
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

	public function saveExtraProfileFields( $user_id ){
		if( ! current_user_can('edit_user') ) {
			return false;
		}

		update_user_meta($user_id, 'boardmember', $_POST['boardmember']);
		update_user_meta($user_id, 'address', $_POST['address']);
		update_user_meta($user_id, 'postcode', $_POST['postcode']);
		update_user_meta($user_id, 'town', $_POST['town']);
		update_user_meta($user_id, 'phonehome', $_POST['phonehome']);
		update_user_meta($user_id, 'phone', $_POST['phone']);
	}

	public function getBoardRoles(){
		return array(
			'Voorzitter',
			'Secretaris',
			'Penningmeester',
			'Technisch coordinator',
			'Oudervertegenwoordiger',
			'Algemeen'
		);
	}
}

/**
 * Returns the main instance of Users to prevent the need to use globals.
 */
function USERS() {
	return Users::instance();
}

USERS();
?>