<?php
/*
Template Name: Fotoalbums
*/
get_header(); ?>
<div class="content articles-content">
	<h1><?php the_title(); ?></h1>
	<?php the_content(); ?>
</div>
<?php
$photoalbums = JlPhotoalbumModel::getPhotoalbums();

if( count( $photoalbums ) > 0 ) {?>
<div class="article-item-wrapper">
	<ul class="article-item-content content">
	<?php
	foreach( $photoalbums as $photoalbum ) {
		JlPhotoalbumView::displayPhotoalbum( $photoalbum );
	}
	?>
	</ul>
</div>
	<?php
}
?>
<?php get_footer(); ?>