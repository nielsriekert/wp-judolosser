<?php
class ArticleModel {

	/**
	 * @param array array. $args.
	 * @return array with Reference|Article instances.
	 */
	public static function getArticles( $args = array() ) {
		$wp_args = array(
			'post_type' => 'post',
			'post_status' => 'publish',
			'nopaging' => true,
			'no_found_rows' => true
		);

		if( isset( $args['types'] ) ) {
			$wp_args['post_type'] = $args['types'];
		}

		$wp_articles = new WP_Query( $wp_args );

		$articles = array();

		if( $wp_articles->post_count > 0 ) {
			foreach( $wp_articles->posts as $article ) {
				$articles[] = new Article( $article );
			}
		}

		return $articles;
	}

	/**
	 * @return Article|null
	 */
	public static function getNextArticle() {
		$articles = self::getArticles();

		return count( $articles ) > 0 ? current( $articles ) : null;
	}

	public static function getArticlesAjax() {
		if( ! wp_verify_nonce( $_REQUEST['nonce'], 'get_articles' ) ) {
			wp_die( 'No naughty business please' );
		}

		$articles = self::getArticles( array( 'types' => 'post' ) );

		$articles_json = array();
		foreach( $articles as $article ) {
			$article_json = array(
				'id' => $article->getId(),
				'title' => $article->getTitle(),
				'url' => $article->getUrl(),
				'date' => $article->getDate(),
				'excerpt' => html_entity_decode( $article->getExcerpt() ),
			);

			if( $article->getFeaturedImageSrc() ) {
				$article_json['featuredImage'] = array(
					'src' => $article->getFeaturedImageSrc()
				);
			}

			$articles_json[] = $article_json;
		}

		header('Content-Type: application/json');
		echo json_encode( $articles_json );

		wp_die();
	}
}
?>