<?php
class Photo {

	/**
	 * @var string
	 */
	private $src = '';

	/**
	 * @var string
	 */
	private $alt = '';

	/**
	 * @var string
	 */
	private $width = null;

	/**
	 * @var string
	 */
	private $height = null;

	public function __construct( String $src, String $alt = '', $width = null, $height = null ) {
		$this->src = $src;
		$this->alt = $alt;
		$this->width = is_float( $width ) ? $width : null;
		$this->height = is_float( $height ) ? $height : null;
	}

	public function getSrc() {
		return $this->src;
	}

	public function getAlt() {
		return $this->alt;
	}

	public function getWidth() {
		return $this->width;
	}

	public function getHeight() {
		return $this->height;
	}
}