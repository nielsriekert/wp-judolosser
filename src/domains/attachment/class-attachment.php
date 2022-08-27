<?php
class Attachment {
	/**
	 * @var array
	 */
	private $acf_file = array();

	public function __construct( array $acf_file ) {
		$this->acf_file = $acf_file;
	}

	public function getId() : int {
		return $this->acf_file['attachment']['ID'];
	}

	public function getName() : string {
		return $this->acf_file['attachment']['title'];
	}

	public function getMimeType() : string {
		return $this->acf_file['attachment']['mime_type'];
	}

	public function getUrl() : string {
		return $this->acf_file['attachment']['url'];
	}

	public function __toString() {
		return strval( $this->getId() );
	}
}