<?php
/*
Template Name: Portaal
*/
get_header(); ?>
<?php
$kaarten = CFS()->get('k_kaarten');
if($kaarten){?>
<div class="cards-wrapper">
	<div class="cards-content content">
		<?php
		foreach($kaarten as $kaart){
			switch(key($kaart['k_type'])){
				case 'news':
					get_template_part('cards/card', 'news');
					break;
				case 'events':
					get_template_part('cards/card', 'events');
					break;
				case 'photos':
					get_template_part('cards/card', 'photos');
					break;
				case 'location':
					get_template_part('cards/card', 'location');
					break;
				case 'sign-up':
					get_template_part('cards/card', 'sign-up');
					break;
				case 'judo':
					get_template_part('cards/card', 'judo');
					break;
			}
		}
	wp_reset_query();
	?>
	</div>
</div>
<?php
}
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