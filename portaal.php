<?php
/*
Template Name: Portaal
*/
get_header(); ?>
<div class="cards-wrapper">
	<?php get_template_part('cards/card', 'events'); ?>
</div>
<div class="content">
	<?php the_content();?>
</div>
<?php get_footer(); ?>