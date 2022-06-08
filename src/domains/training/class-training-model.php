<?php
class TrainingModel {

	/**
	 * @return array
	 */
	public static function getTrainingGroupedByDays() {
		$pages = get_pages(array(
			'meta_key' => '_wp_page_template',
			'meta_value' => 'trainingstijden.php'
		));

		if( count( $pages ) !== 1) {
			return null;
		}

		$fields = get_field( 'training_times', current( $pages )->ID );

		if( ! is_array( $fields ) ) {
			return null;
		}

		return array_map( function( $field ) {
			return [
				'name' => $field['training_times_day_name'],
				'trainings' => array_map( function( $fields ) {
					return new Training( $fields );
				}, is_array( $field['training_times_day_trainings'] ) ? $field['training_times_day_trainings'] : [] )
			];
		}, $fields );
	}
}
?>