<?php get_header(); the_post(); ?>
<?php
if(has_post_thumbnail()){
	$image_src = wp_get_attachment_image_src(get_post_thumbnail_id(), 'post')[0];
	?>
<div class="article-image-post">
	<div class="article-image-post-content">
		<h1><?php the_title(); ?></h1>
	</div>
	<div class="article-image-post-image" style="background-image: url('<?php echo $image_src; ?>');"></div>
</div>
	<?php
}
?>
<div class="content article">
	<?php
	if(!has_post_thumbnail()){?>
		<h1><?php the_title(); ?></h1>
		<?php
	}
	the_content();

	$photos = CFS()->get('p_photos');
	//print_r($photos);
	if($photos){?>
	<div class="photos">
		<?php
		foreach($photos as $photo){
			echo '<a class="photo" href="' . wp_get_attachment_image_src($photo['p_photo'], 'media-full')[0] . '"><img src="' . wp_get_attachment_image_src($photo['p_photo'], 'media-thumb')[0] . '" alt="' . get_post_meta($photo['p_photo'], '_wp_attachment_image_alt', true) . '"></a>';
		}
		?>
	</div>
	<script>
	window.addEventListener('load', function(){
		var lightBox = new LightBox(document.getElementsByClassName('photo'));
	});
	</script>
		<?php
	}
	?>
</div>
<?php get_footer(); ?>