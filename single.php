<?php get_header(); ?>
<?php
get_template_part('includes/include', 'schema');

while(have_posts()){ the_post();
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
	<article class="article content">	
		<?php
			//print_r($post);
			if(!has_post_thumbnail()){?>
				<h1><?php the_title(); ?></h1>
				<?php
			}
			the_content();
		?>
	</article>
	<?php
}
get_template_part('includes/include', 'lightbox');
get_footer();
?>