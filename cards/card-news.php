<?php
$posts = new WP_Query(array(
	'post_type' => 'post',
	'nopaging' => true,
	'no_found_rows' => true,
));

if($posts->have_posts()){
	$posts->the_post();
	?>
	<section class="card card-nieuws">
		<a href="<?php the_permalink(); ?>">
			<div class="card-body">
				<h2><?php the_title(); ?></h2>
				<?php the_excerpt(); ?>
				<div class="card-date">
					<?php echo humanize_date(get_the_time('Ymd')); ?>
				</div>
				<div class="card-type">
					Nieuws
				</div>
			</div>
		</a>
		<?php
		$links = array(
			'Lees verder' => get_permalink()
		);

		$pages = get_pages(array(
			'meta_key' => '_wp_page_template',
			'meta_value' => 'berichten.php'
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