<?php
$posts = new WP_Query(array(
	'post_type' => 'post',
	'nopaging' => true,
	'no_found_rows' => true,
));

if($posts->have_posts()){
	$posts->the_post();
	$pages = get_pages(array(
		'meta_key' => '_wp_page_template',
		'meta_value' => 'posts.php'
	));

	if(count($pages) == 1){
		$link = get_permalink(current($pages)->ID);
	}
	?>
	<section class="card card-post">
		<<?php if(isset($link)){ echo 'a data-button-type="label" href="' . $link . '"'; } else { echo 'div'; } ?> class="card-type">
			Nieuws
		</<?php if(isset($link)){ echo 'a'; } else { echo 'div';} ?>>
		<a href="<?php the_permalink(); ?>">
			<div class="card-body">
				<h2><?php the_title(); ?></h2>
				<div class="card-date">
					<?php echo humanize_date(get_the_time('Ymd')); ?>
				</div>
				<?php the_excerpt(); ?>
			</div>
		</a>
		<?php echo get_card_navigation(false, 'posts.php', 'Lees verder', 'Nieuwsberichten'); ?>
	</section>
	<?php
}
?>