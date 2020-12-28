<?php
class CardPage extends Card {

	public function getPage() {
		return $this->fields['page'] instanceof WP_Post ? $this->fields['page'] : null;
	}

	public function getNavButtons() {
		return $this->filterButtons([]);
	}
}