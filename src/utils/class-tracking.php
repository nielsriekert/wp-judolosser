<?php
class TrackingInit {

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
		define( 'TRACKING_ABSPATH', dirname( __FILE__ ) . '/' );
	}

	/**
	 * Include required core files used in admin and on the frontend.
	 */
	public function includes() {
		require_once( TRACKING_ABSPATH . 'tracking/class-tracking-view.php' );
	}

	private function initHooks() {
		add_action( 'customize_register', array( $this, 'initSettings') );

		add_action( 'wp_head', array( 'TrackingView', 'viewTrackingCodeHead' ), 1  );
		add_action( 'jl-theme/body', array( 'TrackingView', 'viewTrackingCodeBody' ), 2  );
	}

	public function initSettings( $wp_customize ) {
		$wp_customize->add_section( 'tc-tracking-code' , array(
			'title' => __( 'Tracking code', 'judo-losser' ),
			'priority' => 50,
			'capability' => 'administrator'
		));


		$wp_customize->add_setting( 'tc-code-head', array(
			'type' => 'option'
		) );

		$wp_customize->add_control( new WP_Customize_Textarea_Control( $wp_customize, 'tc-code-head', array(
			'label' => __( 'Insert (tracking) code (head)', 'judo-losser' ),
			'section' => 'tc-tracking-code',
			'settings' => 'tc-code-head',
			'type' => 'textarea'
		)));

		$wp_customize->add_setting( 'tc-code-body', array(
			'type' => 'option'
		)  );

		$wp_customize->add_control( new WP_Customize_Textarea_Control( $wp_customize, 'tc-code-body', array(
			'label' => __( 'Insert (tracking) code (body)', 'judo-losser' ),
			'section' => 'tc-tracking-code',
			'settings' => 'tc-code-body',
			'type' => 'textarea'
		)));
	}
}

/**
 * Returns the main instance of TrackingInit to prevent the need to use globals.
 */
function TRACKING() {
	return TrackingInit::instance();
}

TRACKING();
?>