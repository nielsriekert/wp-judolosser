<?php get_header(); the_post(); ?>
<div class="content">
	<h1><?php the_title(); ?></h1>
	<?php
	the_content();

	$photos = CFS()->get('p_photos');
	if($photos){?>
	<div class="photos">
		<?php
		foreach($photos as $photo){
			echo '<a class="photo" href="' . wp_get_attachment_image_src($photo['p_photo'], 'media-full')[0] . '"><img src="' . wp_get_attachment_image_src($photo['p_photo'], 'media-thumb')[0] . '"></a>';
		}
		?>
	</div>
		<?php
	}
	?>
</div>
<?php get_footer(); ?>