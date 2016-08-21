<?php
/*
Template Name: Evenementen
*/
get_header(); ?>
<div class="content articles-content">
	<h1><?php the_title(); ?></h1>
	<?php the_content(); ?>
</div>
<?php
$posts = new WP_Query(array(
	'post_type' => 'event',
	'nopaging' => true,
	'no_found_rows' => true,
	'meta_key' => 'e_datum',
	'orderby' => 'meta_value',
	'order' => 'ASC'
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