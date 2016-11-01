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
	get_template_part('cards/card', 'location');
	get_template_part('cards/card', 'sign-up');
	get_template_part('cards/card', 'judo');
	wp_reset_query();
	?>
	</div>
</div>
<?php
$content = get_the_content();
if($content){
?>
<div class="content article">
	<?php the_content();?>
</div>
<?php
}
?>
<?php get_footer(); ?>