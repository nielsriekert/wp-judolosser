<?php
$events = new WP_Query(array(
	'post_type' => 'evenement',
	'nopaging' => true,
	'no_found_rows' => true,
	'orderby' => 'rand'
));

if($events->have_posts()){
	while($events->have_posts()){ $events->the_post();
		get_template_part('loops/loop', 'event');
	}
}
wp_reset_query();
?>