<?php
class ArticleView {

	public static function viewArticleItem( Article $article ) { ?>
		<li class="article-item">
			<a href="<?php echo $article->getUrl(); ?>">
				<?php
				if( $article->getFeaturedImageHtml() ) { ?>
					<div class="article-item-thumb">
						<?php echo $article->getFeaturedImageHtml(); ?>
					</div>
					<?php
				}
				?>
				<div class="article-item-body">
					<h2 class="article-item-title"><?php echo $article->getTitle(); ?></h2>
					<div class="article-item-date">
						<?php echo $article->getDate(); ?>
					</div>
					<?php
					if( ! $article->getFeaturedImageHtml() ) { ?>
						<div class="article-item-excerpt">
							<p><?php echo $article->getExcerpt(); ?></p>
						</div>
						<?php
					}
					?>
				</div>
			</a>
		</li>
		<?php
	}
}
?>