<?php
$events = new WP_Query(array(
	'post_type' => 'photoalbum',
	'nopaging' => true,
	'no_found_rows' => true,
	'orderby' => 'rand'
));

if($events->have_posts()){
	$events->the_post();
	$photos = CFS()->get('p_photos');
	if($photos){
		$image_src = wp_get_attachment_image_src($photos[array_rand($photos, 1)]['p_photo'], 'card')[0];
	}
	?>
	<section class="card card-photo"<?php if($photos){?> style="background-image: url('<?php echo $image_src; ?>');"<?php } ?>>

		<a href="<?php the_permalink(); ?>">

			<div class="card-body">
				<h2><?php the_title(); ?></h2>
				<div class="card-date">
					<?php echo humanize_date(CFS()->get('e_datum')); ?>
				</div>
				<?php the_excerpt(); ?>
				<div class="card-type">
					Foto's
				</div>
			</div>
		</a>
		<?php echo get_card_navigation($post, 'photoalbums.php', 'Bekijk foto\'s', 'Fotoalbums'); ?>
	</section>
	<?php
}
?>