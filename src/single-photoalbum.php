<?php get_header(); the_post(); ?>
<?php
$photoalbum = PhotoAlbumModel::getPhotoalbum( get_post() );

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
<div class="content article">
	<?php
	if(!has_post_thumbnail()){?>
		<h1><?php the_title(); ?></h1>
		<?php
	}
	the_content();

	$photos = array();
	$photo_thumbs = $photoalbum->getPhotoThumbs();
	foreach( $photoalbum->getPhotos() as $photo ) {
		$photos[] = (object) array(
			'full' => $photo,
			'thumb' => $photo_thumbs[count( $photos )]
		);
	}

	if( count( $photos ) > 0 ) { ?>
		<div class="photo-album-container">
			<?php
			foreach( $photos as $photo ) { ?>
				<a class="photo-container" href="<?php echo $photo->full->getSrc(); ?>"><img src="<?php echo $photo->thumb->getSrc(); ?>"></a>
			<?php
			}
			?>
		</div>
	<?php
	}
	?>
</div>
<?php get_footer(); ?>