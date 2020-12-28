<?php
class CardView {

	/**
	 * @param array $cards
	 * @return void
	 */
	public static function viewCards( array $cards ) { ?>
		<div class="cards-wrapper">
			<div class="cards-container cards-content content">
				<?php
				foreach( $cards as $card ) {
					switch( $card->getType() ) {
						case 'news-post':
							self::viewNewsPostCard( $card );
							break;
						case 'event':
							self::viewEventCard( $card );
							break;
						case 'photo-album':
							self::viewPhotoAlbumCard( $card );
							break;
						case 'page':
							self::viewPageCard( $card );
							break;
					}
				}
				?>
			</div>
		</div>
		<?php
	}

	/**
	 * @param CardNewsPost $card
	 * @return void
	 */
	public static function viewNewsPostCard( CardNewsPost $card ) {
		$news_post = $card->getNewsPost();
		?>
		<div class="card card-<?php echo $card->getType(); ?>">
			<?php
			self::viewCardTab( __( 'News', 'judo-losser' ), $card->getNewsPostsOverviewUrl(), );
			?>
			<a href="<?php echo $news_post->getUrl(); ?>">
				<div class="card-body">
					<h2><?php echo $news_post->getTitle(); ?></h2>
					<div class="card-date">
						<?php echo $news_post->getDate(); ?>
					</div>
					<p><?php echo $news_post->getExcerpt(); ?></p>
				</div>
			</a>
			<?php
			self::viewCardNav( $card->getNavButtons() );
			?>
		</div>
		<?php
	}

	/**
	 * @param CardEvent $card
	 * @return void
	 */
	public static function viewEventCard( CardEvent $card ) {
		$event = $card->getEvent();
		?>
		<div class="card card-<?php echo $card->getType(); ?>">
			<?php
			self::viewCardTab( __( 'Events', 'judo-losser' ), $card->getEventsOverviewUrl(), );
			?>
			<a href="<?php echo $event->getUrl(); ?>">
				<div class="card-body">
					<h2><?php echo $event->getName(); ?></h2>
					<div class="card-date">
						<?php echo $event->getDate(); ?>
					</div>
					<p><?php echo $event->getExcerpt(); ?></p>
				</div>
			</a>
			<?php
			self::viewCardNav( $card->getNavButtons() );
			?>
		</div>
		<?php
	}

	/**
	 * @param CardPhotoAlbum $card
	 * @return void
	 */
	public static function viewPhotoAlbumCard( CardPhotoAlbum $card ) {
		$photo_album = $card->getPhotoAlbum();
		?>
		<div class="card card-<?php echo $card->getType(); ?>" style="background-image: url('<?php echo $photo_album->getFeaturedImageSrc( 'card' ); ?>');">
			<?php
			self::viewCardTab( __( 'Photos', 'judo-losser' ), $card->getPhotoAlbumsOverviewUrl(), );
			?>
			<a href="<?php echo $photo_album->getUrl(); ?>">
				<div class="card-body">
					<h2><?php echo $photo_album->getName(); ?></h2>
					<div class="card-date">
						<?php echo $photo_album->getDate(); ?>
					</div>
					<p><?php echo $photo_album->getExcerpt(); ?></p>
				</div>
			</a>
			<?php
			self::viewCardNav( $card->getNavButtons() );
			?>
		</div>
		<?php
	}

	/**
	 * @param CardPage
	 * @return void
	 */
	public static function viewPageCard( CardPage $card ) {
		$wp_post = $card->getPage();
		?>
		<div class="card card-<?php echo $card->getType(); ?>">
			<a href="<?php echo get_permalink( $wp_post ); ?>">
				<div class="card-body">
					<h2><?php echo get_the_title( $wp_post ); ?></h2>
					<p><?php echo get_the_excerpt( $wp_post ); ?></p>
				</div>
			</a>
			<?php
			self::viewCardNav( $card->getNavButtons() );
			?>
		</div>
		<?php
	}

	public static function viewCardTab( string $label, string $url = null ) { ?>
		<<?php if( isset( $url ) ) { echo 'a data-button-type="label" href="' . $url . '"'; } else { echo 'div'; } ?> class="card-type">
			<?php echo $label; ?>
		</<?php if( isset( $url ) ) { echo 'a'; } else { echo 'div';} ?>>
		<?php
	}

	public static function viewCardNav( array $buttons ) { ?>
		<nav class="card-navigation card-navigation-count-<?php echo count( $buttons ); ?>">
			<?php
			foreach( $buttons as $button ) { ?>
				<a class="card-button card-navigation-<?php echo $button['type']; ?>" href="<?php echo $button['url']; ?>">
					<?php echo $button['label'] ?>
				</a>
				<?php
			}
			?>
		</nav>
		<?php
	}
}