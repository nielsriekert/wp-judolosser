<?php
class AttachmentsInit {

	/**
	 * @var object Instance of this class
	 */
	public static $instance;


	public function __construct() {
		$this->defineConstants();
		$this->includes();
	}

	/**
	 * Get the singleton instance of this class
	 *
	 * @return object
	 */
	public static function instance() {
		if ( ! self::$instance ) {// TODO: was "if ( ! ( self::$instance instanceof self ) ) {" cannot change wp-pot (npm)
			self::$instance = new self();
		}

		return self::$instance;
	}

	private function defineConstants() {
		define( 'ATTACHMENTS_ABSPATH', dirname( __FILE__ ) . '/' );
	}

	public function includes() {
		require_once( ATTACHMENTS_ABSPATH . 'attachment/class-attachment-model.php' );
		require_once( ATTACHMENTS_ABSPATH . 'attachment/class-attachment-view.php' );
		require_once( ATTACHMENTS_ABSPATH . 'attachment/class-attachment.php' );
	}
}

function ATTACHMENTS() : AttachmentsInit {
	return AttachmentsInit::instance();
}

ATTACHMENTS();
?>