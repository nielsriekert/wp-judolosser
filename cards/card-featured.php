<?php
$page_id = current($kaart['k_uitlichten']);

$post = get_post($page_id);

if($post){
	setup_postdata($post);
	if(has_post_thumbnail()){

		$image_src = wp_get_attachment_image_src(get_post_thumbnail_id(), 'card')[0];
	}
	?>
	<section class="card card-featured <?php if(has_post_thumbnail()){ ?>card-type-image<?php } ?>"<?php if(has_post_thumbnail()){?> style="background-image: url('<?php echo $image_src; ?>');"<?php } ?>>
		<a href="<?php the_permalink(); ?>">
			<div class="card-body">
				<h2><?php the_title(); ?></h2>
				<?php the_excerpt(); ?>
				<div class="card-type">
					Uitgelicht
				</div>
			</div>
		</a>
	</section>
	<?php
}

wp_reset_postdata();
?>