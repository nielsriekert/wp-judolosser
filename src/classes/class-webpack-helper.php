<?php
/**
 * Webpack helper functions
 * 
 * @author Niels Riekert
 */

 
class WebpackHelper {

	/**
	 * Manifest with assets information from webpack.
	 *
	 * @var object
	 */
	public $manifest = null;


	/**
	 * Class constructor.
	 */

	public function __construct() {
		$this->manifest = json_decode( file_get_contents( get_template_directory() . '/manifest.json' ) );
	}


	/**
	 * Gets the hashed asset url
	 *
	 * @param string $unhased_file_name. The unhashed version of the filename.
	 * @return null|string null if not found or error, path from theme root when found.
	 * @author: Niels Riekert
	 */

	public function getHashedAssetUrl( $unhased_file_name ){
		if( ! $unhased_file_name || ! is_string( $unhased_file_name ) || ! preg_match( '/\./', $unhased_file_name ) ) {
			return null;
		}

		$filename_parts = explode( '.', $unhased_file_name );

		$extension = array_pop( $filename_parts );
		$filename = implode( $filename_parts );

		if( $this->manifest ) {
			foreach( $this->manifest as $asset ) {
				if( preg_match( '/' . $filename . '\.[a-f\d]{6}\.' . $extension . '$/', $asset->name ) ) {
					return $asset->name;
				}
			}
		}

		return null;
	}
}
?>