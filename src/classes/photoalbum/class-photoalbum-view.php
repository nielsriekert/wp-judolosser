<?php
/**
 * Class JlPhotoalbumView
 *
 * For displaying the photoalbums tot the front end.
 */

class JlPhotoalbumView {


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
					<h2 class="article-item-title"><?php echo $photoalbum->title; ?></h2>
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


	/**
	 * Displays a document with a video as a thumbnail (doesn't contain lightbox script)
	 *
	 * @param IgDocument $video
	 * @param array $args. optional settings: wrapper-element.
	 * @return void
	 * @author Niels Riekert
	 */

	public static function displayVideoThumb( $video, $args = array() ) {
		if( ! isset( $args['wrapper-element'] ) ) {
			$args['wrapper-element'] = 'li';
		}

		if( $args['wrapper-element'] ) {
			$el = $args['wrapper-element'];
		}

		if( isset( $el ) ) {
		?>
		<<?php echo $el; ?> class="document-video-item">
		<?php
		}
		?>
		<a class="document-video-item-container" href="<?php echo $video->videoUrl; ?>">
			<span class="document-video-item-title"><?php echo $video->title; ?></span>
			<?php
			if( $video->thumb && isset( $video->thumb->src ) ) {
				?>
			<img class="document-video-item-thumb" src="<?php echo $video->thumb->src; ?>" alt="<?php echo $video->thumb->alt; ?>" width="<?php echo $video->thumb->width; ?>px" height="<?php echo $video->thumb->height; ?>px">
				<?php
			}
			else {
				$youtube_id = getYoutubeVideoId( $video->videoUrl );
				?>
				<img class="document-video-item-thumb" src="https://img.youtube.com/vi/<?php echo $youtube_id; ?>/mqdefault.jpg" alt="<?php echo $video->title; ?>">
				<?php
			}
			?>
		</a>
		<?php

		if( $args['wrapper-element'] ) {
		?>
		</<?php echo $el; ?>>
		<?php
		}
	}
}