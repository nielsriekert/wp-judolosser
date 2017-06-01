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
			'value' => date('Y-m-d'),
			'compare' => '>=',
			'type' => 'DATE'
		),
		array(
			'key' => 'bl_bijlage',
			'compare' => 'EXISTS'
		)
	)
));

if($events->have_posts()){?>
<button class="header-nav-download-button" id="header-nav-download-button">Download</button>
<ul class="header-nav-download-container" id="header-nav-download-container">
	<?php
	while($events->have_posts()){ $events->the_post();
		$bijlagen = CFS()->get('bl_bijlagen');
		foreach($bijlagen as $bijlage){
			$file = get_post($bijlage['bl_bijlage']);
			echo '<li class="header-nav-download-single download-mime-' . preg_replace('/\W/', '-', $file->post_mime_type) . '"><a href="' . get_permalink($bijlage['bl_bijlage']) . '">' . $file->post_title . '</a></li>';
		}
	}
	?>
</ul>
<?php
}
?>