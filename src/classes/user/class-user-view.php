<?php
/**
 * Class OnsoUserView
 *
 * User view for displaying users.
 */

class OnsoUserView {

	/**
	 * Display a single member.
	 *
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


	/**
	 * Display a single board member.
	 *
	 * @param array OnsoUser. $user.
	 * @param array array. $args.
	 * @return void
	 * @author Niels Riekert
	 */

	public static function viewBoardMember( $user, $args = array(), $is_public = true ) {
		if( ! isset( $args['wrapper-element'] ) ) {
			$args['wrapper-element'] = 'li';
		}

		$el = $args['wrapper-element'];
		?>
		<<?php echo $el; ?> class="single-board-member">
		<?php if( $user->getBoardMemberRole() ) { ?>
			<h3 class="single-board-member-title"><?php echo $user->getBoardMemberRole(); ?></h3>
		<?php } ?>
			<table class="single-board-member-info">
				<tr>
					<th><?php echo __( 'Name:', 'onso' ); ?></th>
					<td><?php echo $is_public ? $user->getFullNameAsPublic() : $user->getFullName(); ?></td>
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
				if( $user->getEmail() ) {
				?>
				<tr>
					<th><?php echo __( 'E-mail:', 'onso' ); ?></th>
					<td><a href="mailto:<?php echo $user->getEmail(); ?>"><?php echo $user->getEmail(); ?></a></td>
				</tr>
				<?php
				}
				if( $user->getPhone() && ! $is_public || strtolower( $user->getBoardMemberRole() ) == 'voorzitter' ) {
				?>
				<tr>
					<th><?php echo __( 'Phone:', 'onso' ); ?></th>
					<td><a href="tel:<?php echo preg_replace( '/\D/', '', $user->getPhone() ); ?>"><?php echo $user->getPhone(); ?></a></td>
				</tr>
				<?php
				}
				?>
			</table>
		</<?php echo $el; ?>>
		<?php
	}


	/**
	 * View a single board member as a simple list item.
	 *
	 * @param OnsoUser $user
	 * @param array $args
	 * @return void
	 * @author: Niels Riekert
	 */

	public static function viewBoardMemberAsList( $user, $args = array() ) {
		$pclass = ( $args['prefix-class'] ) ? $args['prefix-class'] : '';
		?>
		<li class="<?php if( $pclass ) { echo $pclass . '-contact-item '; } ?>contact-item">
			<?php if( $user->getBoardMemberRole() ) { ?>
				<strong><?php echo $user->getBoardMemberRole(); ?></strong><br />
			<?php } ?>
			<?php echo $user->first_name . ' ' . $user->last_name; ?><br />
			<?php
			if( $user->getPhone() ) { ?>
				<a href="tel:<?php echo preg_replace( '/\D/', '', $user->getPhone() ); ?>"><?php echo $user->getPhone(); ?></a><br />
			<?php
			}
			?>
			<a href="mailto:<?php echo $user->user_email; ?>"><?php echo $user->user_email; ?></a>
		</li>
		<?php
	}

	public static function viewBoardMemberPhone( $user, $public = true) {
		?>
		<div class="user-board-member-phone-container">
			<strong class="user-board-member-phone-role-name"><?php echo $user->getBoardMemberRole(); ?> - <?php echo $public ? $user->getFullNameAsPublic() : $user->getFullName(); ?></strong>
			<a class="user-board-member-phone-tel" href="tel:<?php echo preg_replace( '/(\(0\))?[^0-9\+]?/', '', $user->getPhone() ) ?>"><?php echo $user->getPhone();?></a>
		</div>
		<?php
	}

	public static function viewBoardMemberEmail( $user, $public = true ) {
		?>
		<div class="user-board-member-email-container">
			<strong class="user-board-member-email-role-name"><?php echo $user->getBoardMemberRole(); ?> - <?php echo $public ? $user->getFullNameAsPublic() : $user->getFullName(); ?></strong>
			<a class="user-board-member-email" href="mailto:<?php echo $user->getEmail(); ?>"><?php echo $user->getEmail(); ?></a>
		</div>
		<?php
	}

	public static function viewBoardMemberPhoneEmail( $user, $public = true ) {
		?>
		<div class="user-board-member-phone-email-container">
			<strong class="user-board-member-phone-email-role-name"><?php echo $user->getBoardMemberRole(); ?> - <?php echo $public ? $user->getFullNameAsPublic() : $user->getFullName(); ?></strong>
			<a class="user-board-member-phone-tel" href="tel:<?php echo preg_replace( '/(\(0\))?[^0-9\+]?/', '', $user->getPhone() ) ?>"><?php echo $user->getPhone();?></a>
			<a class="user-board-member-email" href="mailto:<?php echo $user->getEmail(); ?>"><?php echo $user->getEmail(); ?></a>
		</div>
		<?php
	}
}