<?php
class UserModel {

	/**
	 * Get users.
	 *
	 * @param array $args.
	 * @return array with OnsoUser instances.
	 * @author Niels Riekert
	 */
	public static function getUsers() {
		$wp_users = get_users();

		$users = array();
		foreach( $wp_users as $user ) {
			$users[] = new User( $user );
		}

		usort( $users, function( $a, $b ){
			if ( $a->getFirstName() == $b->getFirstName() ) {
				return 0;
			}
			return ( $a->getFirstName() < $b->getFirstName() ) ? -1 : 1;
		});

		return $users;
	}

	public static function getUsersByCommittee( $committee ) {
		$users = self::getUsers();

		$users_by_committee = array();
		$committee_roles = USERS()->getCommitteeRoles();
		foreach( $committee_roles as $committee_role ) {
			foreach( $users as $user ) {
				if( $user->getCommitteeRole() == $committee_role && $user->getCommittee() == $committee ) {
					$users_by_committee[] = $user;
				}
			}
		}

		return $users_by_committee;
	}
}