<?php
class Card {

	/**
	 * @var array
	 */
	protected $fields = array();


	public function __construct( array $fields ) {
		$this->fields = $fields;
	}

	/**
	 * @return string
	 */
	public function getType() {
		return $this->fields['acf_fc_layout'];
	}

	protected function filterButtons( array $buttons ) {
		return array_filter( $buttons, function( $button ) {
			return $button['url'];
		});
	}
}