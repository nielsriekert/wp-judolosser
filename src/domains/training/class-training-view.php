<?php
class TrainingView {

	/**
	 * @return void
	 */
	public static function viewTrainingTimes( array $days, array $columns ) { ?>
		<table class="trainingstijden">
			<tr>
				<th><?php echo __( 'Day', 'judo-losser' ); ?></th>
				<?php
				foreach( $columns as $column ) { ?>
					<th><?php echo $column['label'] ?></th>
					<?php
				}
				?>
			</tr>
			<?php
		foreach( $days as $day ) {
			$counter = 0;
			foreach( $day['trainings'] as $training ) { $counter ++; ?>
				<tr>
					<?php
					if ( $counter === 1 ) { ?>
						<td class="trainingstijden-dag" data-column-name="Dag" rowspan="<?php echo count( $day['trainings'] ); ?>"><?php echo $day['name']['label']; ?></td>
						<?php
					}

					foreach( $columns as $column ) {
						call_user_func( $column['view'], $training, $column );

					}
					?>
				</tr>
				<?php
			}
		}
		?>
			</table>
		<?php
	}

	public static function viewTrainingTimeCell( Training $training, $column ) { ?>
		<td data-column-name="<?php echo $column['label']; ?>" ><?php echo implode(' t/m ', array_filter( [$training->getFromTime(), $training->getToTime()] ) ); ?></td>
		<?php
	}

	public static function viewTrainingTrainerCell( Training $training, $column ) { ?>
		<td data-column-name="<?php echo $column['label']; ?>" ><?php echo $training->getTrainer(); ?></td>
		<?php
	}

	public static function viewTrainingTypeCell( Training $training, $column ) { ?>
		<td data-column-name="<?php echo $column['label']; ?>" ><?php echo $training->getType(); ?></td>
		<?php
	}

	public static function viewTrainingLocationCell( Training $training, $column ) {
		$location = $training->getLocation();
		?>
		<td data-column-name="<?php echo $column['label']; ?>" ><?php echo $location ? $location->getName() . ' (' . $location->getAddress() . ', ' . $location->getLocality() . ')' : '-' ?></td>
		<?php
	}
}