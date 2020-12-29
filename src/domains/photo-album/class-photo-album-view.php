<?php
class PhotoAlbumView {


	public static function displayPhotoAlbum( PhotoAlbum $photoalbum, $args = array() ) {
		if( ! isset( $args['wrapper-element'] ) ) {
			$args['wrapper-element'] = 'li';
		}

		$el = $args['wrapper-element'];
		?>
		<<?php echo $el; ?> class="article-item article-item-photoalbum<?php if( $args['label'] ) { echo ' is-label'; } ?>">
			<a href="<?php echo $photoalbum->getUrl(); ?>">
				<?php
				if( $photoalbum->getFeaturedImageSrc() ) { ?>
				<div class="article-item-thumb">
					<img src="<?php echo $photoalbum->getFeaturedImageSrc(); ?>">
				</div>
				<?php
				}
				?>
				<div class="article-item-body">
					<h2 class="article-item-title"><?php echo $photoalbum->getName(); ?></h2>
					<?php
					if( is_string( $photoalbum->getDate() ) ) { ?>
					<div class="article-item-date">
						<?php echo $photoalbum->getDate(); ?>
					</div>
					<?php
					}
					?>
				</div>
			</a>
		</<?php echo $el; ?>>
		<?php
	}
}