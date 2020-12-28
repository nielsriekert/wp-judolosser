<?php
class CardNewsPost extends Card {

	public function getNewsPost() {
		return ArticleModel::getNextArticle();
	}

	public function getNewsPostsOverviewUrl() {
		return ARTICLES()->getPermalinkOverviewPage();
	}

	public function getNavButtons() {
		return $this->filterButtons( [
			[
				'label' => __( 'News Posts', 'judo-losser' ),
				'url' => $this->getNewsPostsOverviewUrl(),
				'type' => 'all'
			]
		] );
	}
}