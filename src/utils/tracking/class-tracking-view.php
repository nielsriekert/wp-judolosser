<?php
class TrackingView {

	public static function viewTrackingCodeHead() {
		if( getenv('ENVIRONMENT') !== 'production' ) {
			return;
		}

		$user = wp_get_current_user();

		if( $user->exists() && USERS()->isUserAnAdmin( $user ) ) {
			return;
		}

		echo get_option('tc-code-head') . "\n";
	}

	public static function viewTrackingCodeBody() {
		if( getenv('ENVIRONMENT') !== 'production' ) {
			return;
		}

		$user = wp_get_current_user();

		if( $user->exists() && USERS()->isUserAnAdmin( $user ) ) {
			return;
		}

		echo get_option('tc-code-body') . "\n";
	}
}
?>