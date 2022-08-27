<?php
class AttachmentView {

	/**
	 * @param Attachment[] $attachments
	 * @return void
	 */
	public static function viewAttachmentsHeaderDropDown( Array $attachments ) {
		if ( count( $attachments ) <= 0 ) {
			return;
		}
		?>
		<button class="header-nav-download-button" id="header-nav-download-button">Download</button>
		<ul class="header-nav-download-container" id="header-nav-download-container">
			<?php
			foreach( $attachments as $attachment ) {
				echo '<li class="header-nav-download-single download-mime-' . preg_replace('/\W/', '-', $attachment->getMimeType() ) . '"><a target="_blank" href="' . $attachment->getUrl(). '">' . $attachment->getName() . '</a></li>';
			}
			?>
		</ul>
		<?php
	}
}