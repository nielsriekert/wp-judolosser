<?php
class CardPhotoAlbum extends Card {

	/**
	 * @var PhotoAlbum
	 */
	private $randomPhotoAlbum = null;

	public function getPhotoAlbum() {
		if( ! $this->randomPhotoAlbum instanceof PhotoAlbum ) {
			$this->randomPhotoAlbum = PhotoAlbumModel::getRandomPhotoAlbum();
		}
		return $this->randomPhotoAlbum;
	}

	public function getPhotoAlbumsOverviewUrl() {
		return PHOTO_ALBUMS()->getOverviewUrl();
	}

	public function getNavButtons() {
		return $this->filterButtons( [
			[
				'label' => __( 'View photos', 'judo-losser' ),
				'url' => $this->getPhotoAlbum()->getUrl(),
				'type' => 'article'
			],
			[
				'label' => __( 'Photo albums', 'judo-losser' ),
				'url' => $this->getPhotoAlbumsOverviewUrl(),
				'type' => 'all'
			]
		] );
	}
}