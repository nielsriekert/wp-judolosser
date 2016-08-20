<?php
$events = new WP_Query(array(
	'post_type' => 'event',
	'nopaging' => true,
	'no_found_rows' => true,
	'orderby' => 'rand'
));

if($events->have_posts()){
	$events->the_post();
	?>
	<section class="card card-event">
		<a href="<?php the_permalink(); ?>">
			<div class="card-body">
				<h2><?php the_title(); ?></h2>
				<?php the_excerpt(); ?>
				<div class="card-date">
					<?php echo humanize_date(CFS()->get('e_datum')); ?>
				</div>
				<div class="card-type">
					Evenement
				</div>
			</div>
		</a>
		<?php
		$links = array(
			'Lees verder' => get_permalink()
		);

		$pages = get_pages(array(
			'meta_key' => '_wp_page_template',
			'meta_value' => 'events.php'
		));
		if(count($pages) == 1){
			$links['Alles'] = get_permalink(current($pages)->ID);
		}
		?>
		<nav class="card-navigation card-navigation-count-<?php echo count($links); ?>">
			<?php
			foreach($links as $text => $link){
			?><a class="card-button" href="<?php echo $link; ?>"><?php echo $text; ?></a><?php
			}
			?>
		</nav>
	</section>
	<?php
}
?>