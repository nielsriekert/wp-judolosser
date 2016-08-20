<?php
$page_id = 58;//tijdelijk gehardcode

$locations = new WP_Query(array(
	'post_type' => 'page',
	'nopaging' => true,
	'no_found_rows' => true,
	'p' => $page_id
));

if($locations->have_posts()){
	$locations->the_post();

	if(has_post_thumbnail()){

		$image_src = wp_get_attachment_image_src(get_post_thumbnail_id(), 'card')[0];
	}
	?>
	<section class="card card-sing-up"<?php if(has_post_thumbnail()){?> style="background-image: url('<?php echo $image_src; ?>');"<?php } ?>>
		<a href="<?php the_permalink(); ?>">
			<div class="card-body">
				<h2><?php the_title(); ?></h2>
				<?php the_excerpt(); ?>
				<div class="card-type">
					Judo
				</div>
			</div>
		</a>
	</section>
	<?php
}
?>