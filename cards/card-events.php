<?php
$events = new WP_Query(array(
	'post_type' => 'event',
	'nopaging' => true,
	'no_found_rows' => true,
	'meta_key' => 'e_datum',
	'orderby' => 'meta_value',
	'order' => 'ASC'
));

if($events->have_posts()){
	$events->the_post();
	?>
	<section class="card card-event">
		<a href="<?php the_permalink(); ?>">
			<div class="card-body">
				<h2><?php the_title(); ?></h2>
				<div class="card-date">
					<?php echo humanize_date(CFS()->get('e_datum')); ?>
				</div>
				<?php the_excerpt(); ?>
				<div class="card-type">
					Evenementen
				</div>
			</div>
		</a>
		<?php echo get_card_navigation(false, 'events.php', 'Lees verder', 'Agenda'); ?>
	</section>
	<?php
}
?>