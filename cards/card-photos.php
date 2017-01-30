<?php
$photoalbums = new WP_Query(array(
	'post_type' => 'photoalbum',
	'nopaging' => true,
	'no_found_rows' => true,
	'orderby' => 'rand'
));

if($photoalbums->have_posts()){$photoalbums->the_post();
	$events = new WP_Query( array(
		'connected_type' => 'event_to_photoalbum',
		'connected_items' => get_post(),
		'nopaging' => true,
	));

	if($events->post_count == 1){
		$date = CFS()->get('e_datum', current($events->posts)->ID);
	}
	
	$photos = CFS()->get('p_photos');
	if($photos){
		$image_src = wp_get_attachment_image_src($photos[array_rand($photos, 1)]['p_photo'], 'card')[0];
	}
	$pages = get_pages(array(
		'meta_key' => '_wp_page_template',
		'meta_value' => 'photoalbums.php'
	));

	if(count($pages) == 1){
		$link = get_permalink(current($pages)->ID);
	}
	?>
	<section class="card card-photo"<?php if($photos){?> style="background-image: url('<?php echo $image_src; ?>');"<?php } ?>>
		<<?php if(isset($link)){ echo 'a href="' . $link . '"'; } else { echo 'div'; } ?> class="card-type">
			Foto's
		</<?php if(isset($link)){ echo 'a'; } else { echo 'div';} ?>>
		<a href="<?php the_permalink(); ?>">

			<div class="card-body">
				<h2><?php the_title(); ?></h2>
				<?php if($date){?>
				<div class="card-date">
					<?php echo humanize_date($date); ?>
				</div>
				<?php
				}
				the_excerpt(); ?>
			</div>
		</a>
		<?php echo get_card_navigation($post, 'photoalbums.php', 'Bekijk foto\'s', 'Fotoalbums'); ?>
	</section>
	<?php
}
?>