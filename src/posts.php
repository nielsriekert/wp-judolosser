<?php
/*
Template Name: Berichten
*/
get_header(); ?>
<div class="content articles-content">
	<h1><?php the_title(); ?></h1>
	<?php the_content(); ?>
</div>

<div class="article-item-wrapper"></div>
<?php get_footer(); ?>