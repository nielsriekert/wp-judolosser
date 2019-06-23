<?php
class PhotoalbumView {


	public static function displayPhotoalbum( $photoalbum, $args = array() ) {
		if( ! isset( $args['wrapper-element'] ) ) {
			$args['wrapper-element'] = 'li';
		}

		$el = $args['wrapper-element'];
		?>
		<<?php echo $el; ?> class="article-item article-item-photoalbum<?php if( $args['label'] ) { echo ' is-label'; } ?>">
			<a href="<?php echo $photoalbum->url; ?>">
				<?php
				if( $photoalbum->featuredImage ) { ?>
				<div class="article-item-thumb">
					<img width="<?php echo $photoalbum->featuredImage->width; ?>" height="<?php echo $photoalbum->featuredImage->height; ?>" src="<?php echo $photoalbum->featuredImage->url; ?>" alt="<?php echo $photoalbum->featuredImage->alt; ?>">
				</div>
				<?php
				}
				?>
				<div class="article-item-body">
					<h2 class="article-item-title"><?php echo $photoalbum->getName(); ?></h2>
					<?php
					if( is_string( $photoalbum->eventDateHumanize ) ) { ?>
					<div class="article-item-date">
						<?php echo $photoalbum->eventDateHumanize; ?>
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