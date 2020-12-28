<?php
class WebpackHelper {

	/**
	 * @var object Instance of this class
	 */
	public static $instance;

	/**
	 * Manifest with assets information from webpack.
	 *
	 * @var object
	 */
	public $manifest = null;


	public function __construct() {
		$this->manifest = json_decode( file_get_contents( get_template_directory() . '/manifest.json' ) );
	}

	/**
	 * Get the singleton instance of this class
	 *
	 * @return object
	 */
	public static function instance() {
		if ( ! ( self::$instance instanceof self ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * Gets the hashed asset url
	 *
	 * @param string $file_name. The unhashed version of the filename.
	 * @return null|string null if wrong input, path from theme root when found, input when nog found
	 */
	public function getHashedAssetUrl( $file_name ){
		if( ! $file_name || ! is_string( $file_name ) || ! preg_match( '/\./', $file_name ) ) {
			return null;
		}

		if( $this->manifest ) {
			foreach( $this->manifest as $unhashed_file_path => $hashed_file_path ) {
				if( basename( $unhashed_file_path ) ===  $file_name ) {
					return $hashed_file_path;
				}
			}
		}

		return $file_name;
	}

	/**
	 * Gets the hashed asset urls
	 *
	 * @param array with extensions
	 * @return array
	 */
	public function getHashedAssetsByExtensions( array $extensions ) {
		if( count( $extensions ) < 1 ) {
			return [];
		}

		$files = [];

		if( $this->manifest ) {
			foreach( $this->manifest as $hashed_file_path ) {
				$path_info = pathinfo( $hashed_file_path );
				foreach( $extensions as $extension ) {
					if( $extension === $path_info['extension'] ) {
						$files[] = $hashed_file_path;
					}
				}
			}
		}

		return $files;
	}
}

/**
 * Returns the main instance of WebpackHelper.
 */
function WPH() {
	return WebpackHelper::instance();
}

WPH();
?>