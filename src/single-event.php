<?php get_header(); ?>
<?php
get_template_part('includes/include', 'schema');

while( have_posts() ) { the_post();
	$event = EventModel::getEvent( get_post() );

	if(has_post_thumbnail()){
		$image_src = wp_get_attachment_image_src(get_post_thumbnail_id(), 'post')[0];
		?>
	<div class="article-image-post">
		<div class="article-image-post-content">
			<h1><?php the_title(); ?></h1>
		</div>
		<div class="article-image-post-image" style="background-image: url('<?php echo $image_src; ?>');"></div>
	</div>
		<?php
	}
	?>
	<article class="article content">
		<?php
		if( has_post_thumbnail() && get_post_type() == 'post' ) {?>
			<time class="article-date"><?php the_date('j F Y'); ?></time>
			<?php
		}

		if(!has_post_thumbnail()){?>
			<h1 class="article-title"><?php the_title(); ?></h1>
			<?php
			if( get_post_type() == 'post' ) {?>
			<time class="article-date"><?php the_date('j F Y'); ?></time>
			<?php
			}
		}
		the_content();

		$location = LocationModel::getLocationFromEvent( $event );
		if( $location instanceof Location ) {
			LocationView::viewLocation( $location );
		}
		?>
	</article>
	<?php
	$photoalbum = PhotoAlbumModel::getPhotoalbumByPost( get_post() );

	if( $photoalbum ) {?>
	<div class="photoalbum-item-wrapper is-server-renderd">
		<div class="photoalbum-item-content content">
			<ul class="photoalbum-items-container article-items-container items-item-container content">
				<?php PhotoalbumView::displayPhotoalbum( $photoalbum, array(
					'label' => true
				) );?>
			</ul>
		</div>
	</div>
		<?php
	}
}
get_footer();
?>