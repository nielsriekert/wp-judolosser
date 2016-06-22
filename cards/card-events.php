<?php
$events = new WP_Query(array(
	'post_type' => 'evenement',
	'nopaging' => true,
	'no_found_rows' => true,
	'orderby' => 'rand'
));

if($events->have_posts()){
	$events->the_post();
	?>
	<section class="card card-event">
		<a href="<?php the_permalink(); ?>">
			<div class="card-body">
				<h2><?php the_title(); ?></h2>
				<?php the_excerpt(); ?>
				<div class="card-date">
					<?php echo humanize_date(CFS()->get('e_datum')); ?>
				</div>
				<div class="card-type">
					Evenement
				</div>
			</div>
		</a>
	</section>
	<?php
}
?>