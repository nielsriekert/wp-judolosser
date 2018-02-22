<?php
/* //// */
/* POST */
/* //// */

function photoalbum_columns($columns){
	$columns_photoalbum = array();

	foreach( $columns as $key => $value ) {
		$columns_photoalbum[$key] = $value;
		if( $key == 'title' || end( $columns ) == $value ) {
			$columns_photoalbum['event'] = 'Verbonden evenement';
			$columns_photoalbum['post'] = 'Verbonden bericht';
		}
	}

	return $columns_photoalbum;
}

add_filter( 'manage_edit-photoalbum_columns', 'photoalbum_columns' );


function manage_photoalbum_columns( $column, $post_id ){
	global $post;

	switch( $column ){
		case 'event':
			$events = new WP_Query(array(
				'connected_type' => 'event_to_photoalbum',
				'connected_items' => $post_id,
				'nopaging' => true,
			));

			$counter = 0;
			if( $events->post_count > 0 ){
				foreach( $events->posts as $event ) { $counter ++;
					echo '<a class="column-connected column-connected-event" href="' . get_edit_post_link( $event->ID ) . '">' . $event->post_title . '</a>';
					if( $counter < $events->post_count ){
						echo '<br />';
					}
				}
			}
			else {
				echo '-';
			}
			break;
		case 'post':
			$posts = new WP_Query(array(
				'connected_type' => 'post_to_photoalbum',
				'connected_items' => $post_id,
				'nopaging' => true,
			));

			$counter = 0;
			if( $posts->post_count > 0 ){
				foreach( $posts->posts as $wp_post ) { $counter ++;
					echo '<a class="column-connected column-connected-post" href="' . get_edit_post_link( $wp_post->ID ) . '">' . $wp_post->post_title . '</a>';
					if( $counter < $posts->post_count ){
						echo '<br />';
					}
				}
			}
			else {
				echo '-';
			}
			break;
		default :
			break;
	}
}

add_action( 'manage_photoalbum_posts_custom_column', 'manage_photoalbum_columns', 10, 2 );


/* ///// */
/* EVENT */
/* ///// */

function event_columns($columns){

	$columns_event = array();

	foreach( $columns as $key => $value ) {
		$columns_event[$key] = $value;
		if( $key == 'title' || end( $columns ) == $value ) {
			$columns_event['date-event'] = 'Datum evenement';
		}
	}

	return $columns_event;
}

add_filter('manage_edit-event_columns', 'event_columns') ;


function manage_event_columns($column, $post_id){
	global $post;

	switch($column){
		case 'date-event' :
			if(CFS()->get('e_datum', $post_id))
				echo humanize_date(CFS()->get('e_datum', $post_id));
			else
				echo '-';
			break;
		default :
			break;
	}
}
add_action('manage_event_posts_custom_column', 'manage_event_columns', 10, 2 );

function event_sortable_columns($columns) {
	$columns['date-event'] = 'date-event';

	return $columns;
}
add_filter( 'manage_edit-event_sortable_columns', 'event_sortable_columns' );

/* Only run our customization on the 'edit.php' page in the admin. */
function edit_event_load() {
	add_filter( 'request', 'sort_event_event_date' );
}
add_action( 'load-edit.php', 'edit_event_load' );

/* Sorts the movies. */
function sort_event_event_date( $vars ) {

	/* Check if we're viewing the 'movie' post type. */
	if ( isset( $vars['post_type'] ) && 'event' == $vars['post_type'] ) {

		/* Check if 'orderby' is set to 'duration'. */
		if ( isset( $vars['orderby'] ) && 'date-event' == $vars['orderby'] ) {

			/* Merge the query vars with our custom variables. */
			$vars = array_merge(
				$vars,
				array(
					'meta_key' => 'e_datum',
					'orderby' => 'meta_value'
				)
			);
		}
	}

	return $vars;
}

/* //// */
/* POST */
/* //// */

function post_columns($columns){
	$columns_post = array();

	foreach( $columns as $key => $value ) {
		$columns_post[$key] = $value;
		if( $key == 'title' || end( $columns ) == $value ) {
			$columns_post['photoalbum'] = 'Verbonden fotoalbums';
		}
	}

	return $columns_post;
}

add_filter( 'manage_edit-post_columns', 'post_columns' );


function manage_post_columns( $column, $post_id ){
	global $post;

	switch( $column ){
		case 'photoalbum':
			$photoalbums = new WP_Query(array(
				'connected_type' => 'post_to_photoalbum',
				'connected_items' => $post_id,
				'nopaging' => true,
			));

			$counter = 0;
			if( $photoalbums->post_count > 0 ){
				foreach( $photoalbums->posts as $photoalbum ) { $counter ++;
					echo '<a class="column-connected column-connected-photoalbum" href="' . get_edit_post_link( $photoalbum->ID ) . '">' . $photoalbum->post_title . '</a>';
					if( $counter < $photoalbums->post_count ){
						echo '<br />';
					}
				}
			}
			else {
				echo '-';
			}
			break;
		default :
			break;
	}
}

add_action( 'manage_post_posts_custom_column', 'manage_post_columns', 10, 2 );
?>