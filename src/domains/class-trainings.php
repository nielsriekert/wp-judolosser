<?php
class TrainingsInit {

	/**
	 * @var object Instance of this class
	 */
	public static $instance;

	public function __construct() {
		$this->defineConstants();
		$this->includes();
		$this->initHooks();
	}

	public static function instance() {
		if ( ! self::$instance ) {// TODO: was "if ( ! ( self::$instance instanceof self ) ) {" cannot change wp-pot (npm)
			self::$instance = new self();
		}

		return self::$instance;
	}

	private function defineConstants() {
		define( 'TRAININGS_ABSPATH', dirname( __FILE__ ) . '/' );
	}

	public function includes() {
		require_once( TRAININGS_ABSPATH . 'training/class-training.php' );
		require_once( TRAININGS_ABSPATH . 'training/class-training-model.php' );
		require_once( TRAININGS_ABSPATH . 'training/class-training-view.php' );
	}

	private function initHooks() {
		add_action( 'acf/init', array( $this, 'addTrainingTimesFields' ) );
	}

	public function addTrainingTimesFields() {
		acf_add_local_field_group(array (
			'key' => 'group_582f05e25a2f3',
			'title' => __( 'Training times', 'judo-losser' ),
			'fields' => array (
				array (
					'key' => 'field_59f5ae2dab7e1',
					'label' => __( 'General', 'judo-losser' ),
					'name' => '',
					'type' => 'tab',
				),
				array (
					'key' => 'field_5a1e23bf2e8fc',
					'label' => _x( 'Training times', 'WP admin field label', 'judo-losser' ),
					'name' => 'training_times',
					'type' => 'repeater',
					'layout' => 'block',
					'button_label' => __( 'Add day', 'judo-losser' ),
					'sub_fields' => array(
						array(
							'key' => 'field_59a2ae28c1e8f',
							'label' => __( 'Day', 'judo-losser' ),
							'name' => 'training_times_day_name',
							'type' => 'select',
							'return_format' => 'array',
							'choices' => [
								__( 'Monday', 'judo-losser' ),
								__( 'Tuesday', 'judo-losser' ),
								__( 'Wednesday', 'judo-losser' ),
								__( 'Thursday', 'judo-losser' ),
								__( 'Friday', 'judo-losser' ),
								__( 'Saturday', 'judo-losser' ),
								__( 'Sunday', 'judo-losser' ),
							]
						),
						array(
							'key' => 'field_5957a4a8c1e8f',
							'label' => __( 'Trainings', 'judo-losser' ),
							'name' => 'training_times_day_trainings',
							'type' => 'repeater',
							'layout' => 'block',
							'sub_fields' => [
								[
									'key' => 'field_59a2a0a3c1e2f',
									'label' => __( 'From', 'judo-losser' ),
									'name' => 'training_times_day_training_from',
									'type' => 'time_picker',
									'display_format' => get_option( 'time_format' ),
									'return_format' => 'H:i',
								],
								[
									'key' => 'field_59a5c3f3a1e2f',
									'label' => __( 'To', 'judo-losser' ),
									'name' => 'training_times_day_training_to',
									'type' => 'time_picker',
									'display_format' => get_option( 'time_format' ),
									'return_format' => 'H:i',
								],
								[
									'key' => 'field_59aaa023c1e8f',
									'label' => __( 'Trainer', 'judo-losser' ),
									'name' => 'training_times_day_training_trainer',
									'type' => 'text',
								],
								[
									'key' => 'field_59a3a0e3c1f8c',
									'label' => __( 'Type', 'judo-losser' ),
									'name' => 'training_times_day_training_type',
									'type' => 'text',
								],
								[
									'key' => 'field_59aaa023c14f8',
									'label' => __( 'Location', 'judo-losser' ),
									'name' => 'training_times_day_training_location',
									'type' => 'post_object',
									'post_type' => ['location'],
								]
							]
						),
					)
				),
			),
			'location' => array(
				array(
					array(
						'param' => 'page_template',
						'operator' => '==',
						'value' => 'trainingstijden.php',
					),
				),
			),
		));
	}
}

function TRAININGS() {
	return TrainingsInit::instance();
}

TRAININGS();
?>