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
		}

		$administrator->add_cap( 'modern_forms_edit_forms' );
		$administrator->add_cap( 'modern_forms_edit_entries' );

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
				<th><label for="committee">Commissie</label></th>
				<td>
					<?php
					$committees = $this->getCommittees();
					?>
					<select name="committee">
						<option value="">- geen -</option>
						<?php
						$user_committee = get_the_author_meta('committee', $user->ID);
						foreach( $committees as $committee ) { ?>
							<option value="<?php echo $committee; ?>"<?php if( $user_committee == $committee ) { ?> selected="selected"<?php } ?>><?php echo $committee; ?></option>
							<?php
							}
						?>
					</select>
				</td>
			</tr>
			<tr>
				<th><label for="role">Role</label></th>
				<td>
					<?php
					$roles = $this->getCommitteeRoles();
					?>
					<select name="committee-role">
						<option value="">- geen -</option>
						<?php
						$user_role = get_the_author_meta( 'committee-role', $user->ID );
						foreach( $roles as $rol ) { ?>
							<option value="<?php echo $rol; ?>"<?php if ( $user_role == $rol ) { ?> selected="selected"<?php } ?>><?php echo $rol; ?></option>
							<?php
						}
						?>
					</select>
				</td>
			</tr>
			<tr>
				<th><label for="street">Straat</label></th>
				<td>
					<input type="text" name="street" id="street" value="<?php echo esc_attr( get_the_author_meta( 'street', $user->ID ) ); ?>" class="regular-text" /><br />
				</td>
			</tr>
			<tr>
				<th><label for="house-number">Huisnummer</label></th>
				<td>
					<input type="text" name="house-number" id="house-number" value="<?php echo esc_attr( get_the_author_meta( 'house-number', $user->ID ) ); ?>" class="regular-text" /><br />
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
				<th><label for="phone">Telefoon</label></th>
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

		update_user_meta( $user_id, 'committee', $_POST['committee'] );
		update_user_meta( $user_id, 'committee-role', $_POST['committee-role'] );
		update_user_meta( $user_id, 'street', $_POST['street'] );
		update_user_meta( $user_id, 'house-number', $_POST['house-number'] );
		update_user_meta( $user_id, 'postcode', $_POST['postcode'] );
		update_user_meta( $user_id, 'town', $_POST['town'] );
		update_user_meta( $user_id, 'phone', $_POST['phone'] );
	}

	public function getCommittees() {
		return array(
			'Bestuur',
			'Activiteitencommissie',
			'Selectiecommissie',
			'Dojo / Wedstrijd commissie',
			'Trainers',
			'Ledenadministratie',
			'Vertrouwenspersonen'
		);
	}

	public function getCommitteeRoles() {
		return array(
			'Voorzitter',
			'Secretaris',
			'Penningmeester',
			'Technisch coordinator',
			'Oudervertegenwoordiger',
			'Wedstrijdsecretariaat',
			'Algemeen',
			'Coach',
			'Assistent trainer / coach',
			'Contactpersoon'
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