<?php
/*
Template Name: Trainingstijden
*/
get_header(); ?>
<div class="content article">
	<h1><?php the_title(); ?></h1>
	<?php the_content(); ?>
	<?php
	$days = TrainingModel::getTrainingGroupedByDays();

	if( is_array( $days ) ) {
		TrainingView::viewTrainingTimes( $days, [
			[
				'label' => __( 'Time', 'judo-losser' ),
				'view' => ['TrainingView', 'viewTrainingTimeCell']
			],
			[
				'label' => __( 'Trainer', 'judo-losser' ),
				'view' => ['TrainingView', 'viewTrainingTrainerCell']
			],
			[
				'label' => __( 'Type of training', 'judo-losser' ),
				'view' => ['TrainingView', 'viewTrainingTypeCell']
			],
			[
				'label' => __( 'Location', 'judo-losser' ),
				'view' => ['TrainingView', 'viewTrainingLocationCell']
			]
		] );
	}
	?>
</div>
<?php get_footer(); ?>