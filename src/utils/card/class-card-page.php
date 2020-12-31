<?php
class CardPage extends Card {

	public function getPage() {
		return $this->fields['page'] instanceof WP_Post ? $this->fields['page'] : null;
	}

	public function getNavButtons() {
		return $this->filterButtons([
			[
				'label' => __( 'More', 'judo-losser' ),
				'url' => get_permalink( $this->getPage() ),
				'type' => 'article'
			]
		]);
	}
}