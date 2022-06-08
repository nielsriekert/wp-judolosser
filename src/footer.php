	<div class="footer-wrapper">
		<footer class="content footer">
			<div class="footer-column footer-training-times">
				<h2>Trainingstijden</h2>
				<?php
				$days = TrainingModel::getTrainingGroupedByDays();

				if( is_array( $days ) ) {
					TrainingView::viewTrainingTimes( $days, [
						[
							'label' => __( 'Time', 'judo-losser' ),
							'view' => ['TrainingView', 'viewTrainingTimeCell']
						],
						[
							'label' => __( 'Type of training', 'judo-losser' ),
							'view' => ['TrainingView', 'viewTrainingTypeCell']
						]
					] );
				}
				?>
			</div>
			<div class="footer-column">
			<?php
			$events = array_slice( EventModel::getEvents(), 0, 8);

			if( count( $events ) > 0 ) { ?>
				<h2>Agenda</h2>
				<ul class="footer-events">
				<?php
				foreach( $events as $event ) { ?>
					<li class="footer-event">
						<a href="<?php echo $event->getUrl(); ?>">
							<?php echo $event->getName() ?> (<?php echo $event->getDate( 'j F' ); ?>)
						</a>
					</li>
					<?php
				}
				?>
				</ul>
				<?php
			}
			?>
			</div>
			<div class="footer-column">
				<p>
					<a href="mailto:info@judolosser.nl">info@judolosser.nl</a>
				</p>
			</div>
			<nav id="footer-nav" class="footer-nav">
				<?php wp_nav_menu(array('theme_location' => 'footernav', 'container' => ''));?>
			</nav>
		</footer>
	</div>
	<?php wp_footer(); ?>
	</body>
</html>