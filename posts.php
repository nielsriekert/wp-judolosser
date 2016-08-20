<?php
/*
Template Name: Berichten
*/
get_header(); the_post(); ?>
<article class="content article">
	<h1><?php the_title(); ?></h1>
	<?php the_content(); ?>
</article>
<?php get_footer(); ?>