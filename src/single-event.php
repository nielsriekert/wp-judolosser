<?php get_header(); ?>
<?php
while( have_posts() ) { the_post();
	$event = EventModel::getEvent( get_post() );

	if( $event->hasFeaturedPhoto() ) {
		$photo = $event->getFeaturedPhoto( 'post' ); ?>
		<div class="article-image-post">
			<div class="article-image-post-content">
				<h1><?php the_title(); ?></h1>
				<?php
				if( $event->isRegistrationEnabled() && EVENTS()->getEventRegistrationUrl( $event->getId() ) ) { ?>
					<div class="event-header-content-container">
						<?php
						EventView::viewRegistrationButton( $event );
						?>
					</div>
					<?php
				}
				?>
			</div>
			<div class="article-image-post-image" style="background-image: url('<?php echo $photo->getSrc(); ?>');"></div>
		</div>
		<?php
	}
	?>
	<article class="article content">
		<?php
		if( ! $event->hasFeaturedPhoto() ) { ?>
			<h1 class="article-title"><?php echo $event->getName(); ?></h1>
			<?php
		}
		the_content();

		if( $event->isRegistrationEnabled() && EVENTS()->getEventRegistrationUrl( $event->getId() ) ) { ?>
			<div class="event-footer-container">
				<?php EventView::viewRegistrationButton( $event ); ?>
			</div>
			<?php
		}

		$location = LocationModel::getLocationFromEvent( $event );
		if( $location instanceof Location ) {
			LocationView::viewLocation( $location );
		}
		?>
	</article>
	<?php
	$photo_album = PhotoAlbumModel::getPhotoAlbumByPost( get_post() );

	if( $photo_album ) { //TODO: to view function ?>
		<div class="photoalbum-item-wrapper is-server-renderd">
			<div class="photoalbum-item-content content">
				<ul class="photoalbum-items-container article-items-container items-item-container content">
					<?php PhotoAlbumView::displayPhotoAlbum( $photo_album, array(
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