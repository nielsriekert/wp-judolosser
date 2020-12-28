<?php
class WP_Customize_Image_Control_SVG extends WP_Customize_Image_Control {
	public function __construct( $manager, $id, $args = array()) {
		parent::__construct( $manager, $id, $args );
		$this->remove_tab('uploaded');
		$this->extensions = array( 'jpg', 'jpeg', 'gif', 'png', 'svg' );
	}
}
?>