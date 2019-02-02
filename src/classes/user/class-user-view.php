<?php
class UserView {

	/**
	 * @param array OnsoUser. $user.
	 * @param array array. $args.
	 * @return void
	 * @author Niels Riekert
	 */
	public static function viewMember( $user, $args = array() ) {
		?>
		<li class="single-member" data-firstname="<?php echo $user->getFirstName(); ?>" data-lastname="<?php echo $user->getLastName(); ?>">
			<?php
			if( isset( $args['photo'] ) && $args['photo'] ) {
				$photo = OnsoUserModel::getUserPhoto( $user );
				?>
				<div class="single-member-photo<?php if( ! $photo ) { echo ' is-no-photo'; } if( $user->getGender() ) { echo ' is-gender-' . strtolower( $user->getGender() ); } ?>">
					<?php
					if( $photo ) {?>
					<img src="<?php echo $photo; ?>" alt="<?php echo __( 'Member photo', 'onso' ) ?>" />
					<?php
					}
					?>
				</div>
				<?php
			}
			?>
			<table class="single-member-info">
				<tr>
					<th><?php echo __( 'Name:', 'onso' ); ?></th>
					<td><?php echo $user->getFullName(); ?></td>
				</tr>
				<?php
				if( $user->getTask() ) {
				?>
				<tr>
					<th><?php echo __( 'Task:', 'onso' ); ?></th>
					<td><?php echo $user->getTask(); ?></td>
				</tr>
				<?php
				}
				if( $user->getStreet() && $user->getHouseNumber() && $user->getPostcode() && $user->getLocality() ) {
				?>
				<tr>
					<th><?php echo __( 'Address:', 'onso' ); ?></th>
					<td><?php
					echo $user->getStreet() . ' ' . $user->getHouseNumber() . '<br />
					' . $user->getPostcode() . ' ' . $user->getLocality();
					?></td>
				</tr>
				<?php
				}
				if( $user->getPhone() ) {
				?>
				<tr>
					<th><?php echo __( 'Phone:', 'onso' ); ?></th>
					<td><a href="tel:<?php echo preg_replace( '/\D/', '', $user->getPhone() ); ?>"><?php echo $user->getPhone(); ?></a></td>
				</tr>
				<?php
				}
				if( $user->getPhoneAdditional() ) {
				?>
				<tr>
					<th><?php echo __( 'Phone:', 'onso' ); ?></th>
					<td><a href="tel:<?php echo preg_replace( '/\D/', '', $user->getPhoneAdditional() ); ?>"><?php echo $user->getPhoneAdditional(); ?></a></td>
				</tr>
				<?php
				}
				if( $user->getEmail() ) {
					?>
					<tr>
						<th><?php echo __( 'E-mail:', 'onso' ); ?></th>
						<td><a href="mailto:<?php echo $user->getEmail(); ?>"><?php echo $user->getEmail(); ?></a></td>
					</tr>
					<?php
					}
				?>
			</table>
		</li>
		<?php
	}

	public static function viewCommitteeMember( $user ) { ?>
		<li class="user-committee-container">
			<h2 class="user-name"><?php echo $user->getFirstName() . ' ' . $user->getLastName(); ?></h2>
			<p class="user-boardmember"><?php echo $user->getCommitteeRole(); ?></p>
			<?php
			$user_info = array(
				'address' => $user->getAddress(),
				'postcode' => $user->getPostcode(),
				'locality' => $user->getLocality(),
				'phone' => $user->getPhone(),
				'email' => $user->getEmail()
			);

			$user_info = array_filter( $user_info );
			$i = 0;
			if(count($user_info) > 0){
				echo '<p class="user-contact">';
				foreach($user_info as $key => $info){$i++;
					switch($key){
						case 'phone':
							echo '<a href="tel:' . preg_replace('/(\(0\))?[^0-9\+]?/', '', $info) . '">' . $info . '</a>';
							break;
						case 'email':
							echo '<a href="mailto:' . $info . '">' . $info . '</a>';
							break;
						default:
							echo $info;
							break;
					}
					if(count($user_info) != $i){
						echo '<br />';
					}
				}
				echo '</p>';
			}
			?>
		</li>
		<?php
	}
}