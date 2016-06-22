<?php
/*
Template Name: Portaal
*/
get_header(); ?>
<div class="cards-wrapper">
	<div class="cards-content content">
	<?php
	get_template_part('cards/card', 'news');
	get_template_part('cards/card', 'events');
	get_template_part('cards/card', 'photos');
	get_template_part('cards/card', 'events');
	get_template_part('cards/card', 'events');
	get_template_part('cards/card', 'events');
	wp_reset_query();
	?>
	</div>
</div>
<div class="content">
	<?php the_content();?>
</div>
<?php get_footer(); ?>