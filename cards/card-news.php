<?php
$posts = new WP_Query(array(
	'post_type' => 'post',
	'nopaging' => true,
	'no_found_rows' => true,
));

if($posts->have_posts()){
	$posts->the_post();
	?>
	<section class="card card-post">
		<a href="<?php the_permalink(); ?>">
			<div class="card-body">
				<h2><?php the_title(); ?></h2>
				<div class="card-date">
					<?php echo humanize_date(get_the_time('Ymd')); ?>
				</div>
				<?php the_excerpt(); ?>
				<div class="card-type">
					Nieuws
				</div>
			</div>
		</a>
		<?php echo get_card_navigation(false, 'posts.php', 'Lees verder', 'Nieuwsberichten'); ?>
	</section>
	<?php
}
?>