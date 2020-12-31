<?php
class EventView {

	/**
	 * @return void
	 */
	public static function viewRegistrationButton( Event $event ) { ?>
		<a class="button is-primary event-registration-button" href="<?php echo EVENTS()->getEventRegistrationUrl( $event->getId() ); ?>">
			<?php echo __( 'Sign Up', 'judo-losser' ); ?>
		</a>
		<?php
	}
}