<?php
/**
 * Class OnsoUserModel
 *
 * User model and reading reading and filtering event data.
 */

class OnsoUserModel {

	/**
	 * Get users.
	 *
	 * @param array $args.
	 * @return array with OnsoUser instances.
	 * @author Niels Riekert
	 */
	public static function getUsers( $args = array() ) {
		$user_args = array();

		if( isset( $args['role'] ) && $args['role'] ) {
			$user_args = array( 'role' => $args['role'] );
		}

		$unsorted_users = new WP_User_Query( $user_args );

		$users = array();
		if ( ! empty( $unsorted_users->get_results() ) ) {
			foreach( $unsorted_users->get_results() as $user ) {
				$users[] = new OnsoUser( $user );
			}
		}

		usort( $users, function( $a, $b ){
			if ( $a->first_name == $b->first_name ) {
				return 0;
			}
			return ( $a->first_name < $b->first_name ) ? -1 : 1;
		});

		$found_users = array();

		if( isset( $args['role'] ) && $args['role'] ) {
			switch( $args['role'] ) {
				case 'board-member':
					$roles = ONSOUSERS()->getBoardRoles();
					if( isset( $args['sub-roles'] ) && is_array( $args['sub-roles'] ) ) {
						$roles = array_intersect( $roles, $args['sub-roles'] );
					}
					break;
			}

			if( isset( $roles ) && count( $roles ) > 0 ) {
				$filtered_users_roles = array();
				foreach( $roles as $role ) {
					foreach( $users as $user ) {
						switch( $args['role'] ) {
							case 'board-member':
								if( $role == $user->getBoardMemberRole() ) {
									$filtered_users_roles[] = $user;
								}
								break;
						}
					}
				}
				$found_users = $filtered_users_roles;
			}
		}

		$tasks = ONSOUSERS()->getTasks();

		if( count( $tasks ) > 0 && isset( $args['task'] ) ) {
			$tasks = array_intersect( $tasks, $args['task'] );
			$filtered_users_tasks = array();
			foreach( $tasks as $task ) {
				foreach( $users as $user ) {
					if( $task == $user->getTask() ) {
						$filtered_users_tasks[] = $user;
					}
				}
			}
			$found_users = array_merge( $found_users, $filtered_users_tasks );
		}

		return $found_users;
	}


	public static function getUserPhoto( $user, $size = 250 ) {
		if( ctype_digit( $user ) && (integer) $user > 1 ) {
			$user = new OnsoUser( $user );
		}
		else if( ! $user instanceof OnsoUser ) {
			return false;
		}

		$photo = get_avatar( $user->getId(), $size, '' );

		$domain = preg_replace('/^www\./', '', $_SERVER['SERVER_NAME'] );

		if( strpos( $photo, $domain ) === false ) {
			return false;
		}

		preg_match( '/src="(\S+)"/', $photo, $matches );

		return $matches[1];
	}
}