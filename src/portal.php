<?php
/*
Template Name: Portal
*/
get_header();

while( have_posts() ) { the_post();

	$cards = CardModel::getCards( get_post() );
	CardView::viewCards( $cards );

	bdump(EventModel::getEvent(3914)->isPastEvent());

	$content = get_the_content();
	if( $content ) { ?>
		<div class="content article">
			<?php the_content();?>
		</div>
		<?php
	}
}
?>
<?php get_footer(); ?>