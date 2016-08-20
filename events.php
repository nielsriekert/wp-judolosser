<?php
/*
Template Name: Evenementen
*/
get_header(); ?>
<div class="content">
	<?php the_content();

	$posts = new WP_Query(array(
		'post_type' => 'event',
		'nopaging' => true,
		'no_found_rows' => true,
	));

	if($posts->have_posts()){
		while($posts->have_posts()){ $posts->the_post();
			get_template_part('loops/loop', 'post');
		}
	}
	?>
</div>
<?php get_footer(); ?>