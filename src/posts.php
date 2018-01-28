<?php
/*
Template Name: Berichten
*/
get_header(); ?>
<div class="content articles-content">
	<h1><?php the_title(); ?></h1>
	<?php the_content(); ?>
</div>
<?php
$posts = new WP_Query(array(
	'post_type' => 'post',
	'nopaging' => true,
	'no_found_rows' => true,
));

if($posts->have_posts()){?>
<div class="article-item-wrapper">
	<div class="article-item-content content">
	<?php
	while($posts->have_posts()){ $posts->the_post();
		get_template_part('loops/loop', 'post');
	}
	?>
	</div>
</div>
	<?php
}
?>
<?php get_footer(); ?>