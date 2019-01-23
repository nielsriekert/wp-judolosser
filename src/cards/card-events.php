<?php
$events = EventModel::getEvents();

if( count( $events ) > 0 ) {
	$event = current( $events );

	setup_postdata( $event->post );
	$link = EVENTS()->getEventsOverviewUrl();
	?>
	<section class="card card-event">
		<<?php if( isset( $link ) ) { echo 'a data-button-type="label" href="' . $link . '"'; } else { echo 'div'; } ?> class="card-type">
			Evenementen
		</<?php if(isset($link)){ echo 'a'; } else { echo 'div';} ?>>
		<a href="<?php echo $event->getUrl(); ?>">
			<div class="card-body">
				<h2><?php echo $event->getName(); ?></h2>
				<div class="card-date">
					<?php echo $event->getDateFormatted(); ?>
				</div>
				<?php the_excerpt(); ?>
			</div>
		</a>
		<?php echo get_card_navigation(false, 'events.php', 'Lees verder', 'Agenda'); ?>
	</section>
	<?php
}
?>