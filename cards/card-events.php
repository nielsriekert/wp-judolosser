<?php
$events = new WP_Query(array(
	'post_type' => 'event',
	'nopaging' => true,
	'no_found_rows' => true,
	'meta_key' => 'e_datum',
	'orderby' => 'meta_value',
	'order' => 'ASC',
	'meta_query' => array(
		array(
			'key' => 'e_datum',
			'value' => date_i18n('Y-m-d'),
			'compare' => '>='
		)
	)
));

if($events->have_posts()){
	$events->the_post();
	$pages = get_pages(array(
		'meta_key' => '_wp_page_template',
		'meta_value' => 'events.php'
	));

	if(count($pages) == 1){
		$link = get_permalink(current($pages)->ID);
	}
	?>
	<section class="card card-event">
		<<?php if(isset($link)){ echo 'a href="' . $link . '"'; } else { echo 'div'; } ?> class="card-type">
			Evenementen
		</<?php if(isset($link)){ echo 'a'; } else { echo 'div';} ?>>
		<a href="<?php the_permalink(); ?>">
			<div class="card-body">
				<h2><?php the_title(); ?></h2>
				<div class="card-date">
					<?php echo humanize_date(CFS()->get('e_datum')); ?>
				</div>
				<?php the_excerpt(); ?>
			</div>
		</a>
		<?php echo get_card_navigation(false, 'events.php', 'Lees verder', 'Agenda'); ?>
	</section>
	<?php
}
?>