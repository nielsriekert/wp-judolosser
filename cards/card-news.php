<?php
$posts = new WP_Query(array(
	'post_type' => 'post',
	'nopaging' => true,
	'no_found_rows' => true,
));

if($posts->have_posts()){
	$posts->the_post();
	?>
	<section class="card card-nieuws">
		<a href="<?php the_permalink(); ?>">
			<div class="card-body">
				<h2><?php the_title(); ?></h2>
				<?php the_excerpt(); ?>
				<div class="card-date">
					<?php echo humanize_date(get_the_time('Ymd')); ?>
				</div>
				<div class="card-type">
					Nieuws
				</div>
			</div>
		</a>
	</section>
	<?php
}
?>