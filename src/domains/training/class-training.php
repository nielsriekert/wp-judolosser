<?php
class Training {

	/**
	 * @var array
	 */
	private $fields = array();

	public function __construct( array $fields ) {
		$this->fields = $fields;
	}

	public function getFromTime() : ?string {
		return $this->fields['training_times_day_training_from'] ?? null;
	}

	public function getToTime() : string {
		return $this->fields['training_times_day_training_to'] ?? null;
	}

	public function getType() : string {
		return $this->fields['training_times_day_training_type'] ?? '';
	}

	public function getTrainer() : string {
		return $this->fields['training_times_day_training_trainer'] ?? '';
	}

	public function getLocation() : ?Location {
		$wp_post = $this->fields['training_times_day_training_location'];
		return $wp_post ? LocationModel::getLocation( $wp_post ) : null;
	}
}