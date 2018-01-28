<?php
/*
Template Name: Trainingstijden
*/
get_header(); ?>
<div class="content article">
	<h1><?php the_title(); ?></h1>
	<?php the_content(); ?>
	<?php echo get_training_times(); ?>
</div>
<?php get_footer(); ?>