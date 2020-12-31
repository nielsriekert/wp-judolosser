<?php
class Image {

	private $id = null;

	private $src = '';

	private $alt = '';

	private $width = null;

	private $height = null;

	/**
	 * @param int $id
	 * @param string $src
	 * @param string $alt
	 * @param int $width
	 * @param int $height
	 */
	public function __construct( int $id, string $src, $alt = '', int $width = null, int $height = null ) {
		$this->id = $id;
		$this->src = $src;
		$this->alt = $alt;
		$this->width = $width;
		$this->height = $height;
	}

	public function getId() {
		return $this->id;
	}

	public function getSrc() {
		return $this->src;
	}

	public function getHeight() {
		return $this->height;
	}

	public function getWidth() {
		return $this->width;
	}

	public function getAlt() {
		return $this->alt;
	}
}
?>