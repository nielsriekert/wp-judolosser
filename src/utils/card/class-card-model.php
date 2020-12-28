<?php
class CardModel {

	/**
	 * @param array $cards
	 * @return array
	 */
	public static function getCards( WP_Post $wp_post ) {
		$acf_cards = get_field( 'cards_cards', $wp_post );

		$cards = array();
		foreach( $acf_cards as $acf_card ) {
			switch( $acf_card['acf_fc_layout'] ) {
				case 'news-post':
					$cards[] = new CardNewsPost( $acf_card );
					break;
				case 'event':
					$cards[] = new CardEvent( $acf_card );
					break;
				case 'photo-album':
					$cards[] = new CardPhotoAlbum( $acf_card );
					break;
				case 'page':
					$cards[] = new CardPage( $acf_card );
					break;
			}
		}

		return $cards;
	}
}