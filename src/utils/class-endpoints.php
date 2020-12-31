<?php
class EndpointsInit {

	/**
	 * @var object Instance of this class
	 */
	public static $instance;


	public function __construct() {
		$this->defineConstants();
		$this->includes();
		$this->initHooks();
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

	private function defineConstants() {
		define( 'ENDPOINTS_ABSPATH', dirname( __FILE__ ) . '/' );
	}

	/**
	 * Include required core files used in admin and on the frontend.
	 */
	public function includes() {
		require_once( ENDPOINTS_ABSPATH . 'endpoint/class-endpoint.php' );
	}

	/**
	 * Hook into actions and filters.
	 */
	private function initHooks() {
		add_filter( 'init', array( $this, 'addApiQueryTags') );
		add_action( 'init', array( $this, 'addApiEndpoint') );
		add_action( 'parse_request', array( $this, 'removeRestrictedSiteAccessForEndpoint'), 0 );
		add_action( 'parse_request', array( $this, 'sniffApiRequests' ) );
	}

	/**
	 * Add public query vars for the api
	 *
	 * @param array $vars List of current public query vars
	 */
	public function addApiQueryTags( $vars ) {
		add_rewrite_tag( '%endpoint-base%', '([^&]+)' );
		add_rewrite_tag( '%endpoint-resource%', '([^&]+)' );
	}

	/**
	 * Adds rewrite rule for the api.
	 *
	 * @return void
	 */
	public function addApiEndpoint() {
		add_rewrite_rule('^endpoints/?([^&/]+)?/?','index.php?endpoint-base=1&endpoint-resource=$matches[1]','top');
	}

	/**
	 * Removes restricted site access (plugin) for endpoint
	 *
	 * @return void
	 */
	public function removeRestrictedSiteAccessForEndpoint() {
		global $wp;
		if( isset( $wp->query_vars['endpoint-base'] ) ) {
			add_filter( 'restricted_site_access_user_can_access', '__return_true' );
		}
	}

	/**
	 * Checks for api requests.
	 *
	 * @return void
	 */
	public function sniffApiRequests() {
		global $wp;
		if( ! isset( $wp->query_vars['endpoint-base'] ) ) {
			return;
		}

		$token = self::getBearerToken();

		if( ( $token !== getenv('ENDPOINTS_ACCESS_TOKEN') && $_SERVER['SERVER_ADDR'] !== $_SERVER['REMOTE_ADDR'] && array_search( $_SERVER['REMOTE_ADDR'], self::getAllowedIpAddresses() ) === false ) && getenv('WP_ENV') !== 'development' ) {
			Endpoint::handleRequest( false, array(
				'status' => 400,
				'message' => $token ? __( 'No access with provided token', 'judo-losser' ) : sprintf( __( 'No access with specified ip-address %s.', 'judo-losser' ), $_SERVER['REMOTE_ADDR'] )
			) );
			exit;
		}

		if( $_SERVER['REQUEST_METHOD'] !== 'GET' ) {
			Endpoint::handleRequest( false, array(
				'status' => 405,
				'message' => __( 'This method is not allowed.', 'judo-losser' )
			) );
			exit;
		}

		if( ! is_ssl() && getenv('WP_ENV') !== 'development'  ) {
			Endpoint::handleRequest( false, array(
				'status' => 497,
				'message' => __( 'An ssl connection needs to be used.', 'judo-losser' )
			) );
			exit;
		}

		Endpoint::handleRequest( $wp->query_vars );
		exit;
	}

	public static function getAllowedIpAddresses() {
		$ip_addresses = getenv('ENDPOINTS_ALLOWED_IP_ADDRESSES');

		if( ! is_string( $ip_addresses ) ) {
			return array();
		}

		return array_map( 'trim', explode( ',', $ip_addresses ) );
	}

	public static function getAuthorizationHeader() {
		$headers = null;
		if ( isset( $_SERVER['Authorization'] ) ) {
			$headers = trim( $_SERVER["Authorization"] );
		}
		else if ( isset( $_SERVER['HTTP_AUTHORIZATION'] ) ) { //Nginx or fast CGI
			$headers = trim( $_SERVER["HTTP_AUTHORIZATION"] );
		}
		else if ( isset( $_SERVER['REDIRECT_HTTP_AUTHORIZATION'] ) ) { //Nginx or fast CGI
			$headers = trim( $_SERVER["REDIRECT_HTTP_AUTHORIZATION"] );
		} elseif ( function_exists( 'apache_request_headers' ) ) {
			$requestHeaders = apache_request_headers();
			// Server-side fix for bug in old Android versions (a nice side-effect of this fix means we don't care about capitalization for Authorization)
			$requestHeaders = array_combine( array_map( 'ucwords', array_keys( $requestHeaders ) ), array_values( $requestHeaders ) );
			if( isset( $requestHeaders['Authorization'] ) ) {
				$headers = trim( $requestHeaders['Authorization'] );
			}
		}
		return $headers;
	}

	public static function getBearerToken() {
		$headers = self::getAuthorizationHeader();
		// HEADER: Get the access token from the header
		if (!empty( $headers ) ) {
			if( preg_match( '/Bearer\s(\S+)/', $headers, $matches ) ) {
				return $matches[1];
			}
		}
		return null;
	}
}

function ENDPOINTS() {
	return EndpointsInit::instance();
}

ENDPOINTS();
?>