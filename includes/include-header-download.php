<?php
$args = array(
	'nopaging' => true,
	'no_found_rows' => true,
	'order' => 'ASC',
);

$args_events = array_merge(
	$args,
	array(
		'post_type' => 'event',
		'meta_key' => 'e_datum',
		'orderby' => 'meta_value',
		'meta_query' => array(
			array(
				'key' => 'e_datum',
				'value' => date('Y-m-d'),
				'compare' => '>=',
				'type' => 'DATE'
			),
			array(
				'key' => 'bl_bijlage',
				'compare' => 'EXISTS'
			)
		)
	)
);

$args_pages = array_merge(
	$args,
	array(
		'post_type' => 'page',
		'meta_query' => array(
			array(
				'key' => 'bl_bijlage',
				'compare' => 'EXISTS'
			)
		)
	)
);

$events = new WP_Query($args_events);
$pages = new WP_Query($args_pages);

$posts = array_merge($events->posts, $pages->posts);

if(count($posts) > 0){?>
<button class="header-nav-download-button" id="header-nav-download-button">Download</button>
<ul class="header-nav-download-container" id="header-nav-download-container">
	<?php
	foreach($posts as $post_single){
		$bijlagen = CFS()->get('bl_bijlagen', $post_single->ID);
		foreach($bijlagen as $bijlage){
			$file = get_post($bijlage['bl_bijlage']);
			echo '<li class="header-nav-download-single download-mime-' . preg_replace('/\W/', '-', $file->post_mime_type) . '"><a target="_blank" href="' . wp_get_attachment_url($bijlage['bl_bijlage']) . '">' . $file->post_title . '</a></li>';
		}
	}
	?>
</ul>
<?php
}
?>