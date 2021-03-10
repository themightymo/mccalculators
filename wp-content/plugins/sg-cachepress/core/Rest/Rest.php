<?php
namespace SiteGround_Optimizer\Rest;

/**
 * Handle PHP compatibility checks.
 */
class Rest {

	const REST_NAMESPACE = 'siteground-optimizer/v1';

	/**
	 * The constructor.
	 */
	public function __construct() {
		$this->webp_helper          = new Rest_Helper_Webp();
		$this->options_helper       = new Rest_Helper_Options();
		$this->cache_helper         = new Rest_Helper_Cache();
		$this->multisite_helper     = new Rest_Helper_Multisite();
		$this->misc_helper          = new Rest_Helper_Misc();
		$this->image_helper         = new Rest_Helper_Images();
		$this->environment_helper   = new Rest_Helper_Environment();
		$this->cloudflare_helper    = new Rest_Helper_Cloudflare();

		add_action( 'rest_api_init', array( $this, 'register_rest_routes' ) );
	}

	/**
	 * Check if a given request has admin access
	 *
	 * @since  5.0.13
	 *
	 * @param  WP_REST_Request $request Full data about the request.
	 * @return WP_Error|bool
	 */
	public function check_permissions( $request ) {
		return current_user_can( 'activate_plugins' );
	}

	/**
	 * Register rest routes.
	 *
	 * @since  5.0.0
	 */
	public function register_rest_routes() {
		$this->register_webp_routes();
		$this->register_images_routes();
		$this->register_cache_routes();
		$this->register_options_routes();
		$this->register_environment_rest_routes();
		$this->register_multisite_rest_routes();
		$this->register_cloudflare_routes();
		$this->register_misc_rest_routes();
	}

	/**
	 * Register php and ssl rest routes.
	 *
	 * @since  5.4.0
	 */
	public function register_environment_rest_routes() {
		register_rest_route(
			self::REST_NAMESPACE, '/enable-ssl/', array(
				'methods'  => 'POST',
				'callback' => array( $this->environment_helper, 'enable_ssl' ),
				'permission_callback' => array( $this, 'check_permissions' ),
			)
		);

		register_rest_route(
			self::REST_NAMESPACE, '/disable-ssl/', array(
				'methods'  => 'POST',
				'callback' => array( $this->environment_helper, 'disable_ssl' ),
				'permission_callback' => array( $this, 'check_permissions' ),
			)
		);

		register_rest_route(
			self::REST_NAMESPACE, '/enable-database-optimization/', array(
				'methods'  => 'POST',
				'callback' => array( $this->environment_helper, 'enable_database_optimization' ),
				'permission_callback' => array( $this, 'check_permissions' ),
			)
		);
		register_rest_route(
			self::REST_NAMESPACE, '/disable-database-optimization/', array(
				'methods'  => 'POST',
				'callback' => array( $this->environment_helper, 'disable_database_optimization' ),
				'permission_callback' => array( $this, 'check_permissions' ),
			)
		);
	}

	/**
	 * Register options rest routes.
	 *
	 * @since  5.4.0
	 */
	public function register_options_routes() {
		register_rest_route(
			self::REST_NAMESPACE, '/enable-option/', array(
				'methods'  => 'POST',
				'callback' => array( $this->options_helper, 'enable_option_from_rest' ),
				'permission_callback' => array( $this, 'check_permissions' ),
			)
		);

		register_rest_route(
			self::REST_NAMESPACE, '/disable-option/', array(
				'methods'  => 'POST',
				'callback' => array( $this->options_helper, 'disable_option_from_rest' ),
				'permission_callback' => array( $this, 'check_permissions' ),
			)
		);

		register_rest_route(
			self::REST_NAMESPACE, '/fetch-options/', array(
				'methods'  => 'GET',
				'callback' => array( $this->options_helper, 'fetch_options' ),
				'permission_callback' => array( $this, 'check_permissions' ),
			)
		);

		register_rest_route(
			self::REST_NAMESPACE, '/change-option/', array(
				'methods'  => 'POST',
				'callback' => array( $this->options_helper, 'change_option_from_rest' ),
				'permission_callback' => array( $this, 'check_permissions' ),
			)
		);
	}

	/**
	 * Register cache rest routes.
	 *
	 * @since  5.4.0
	 */
	public function register_cache_routes() {
		register_rest_route(
			self::REST_NAMESPACE, '/update-excluded-urls/', array(
				'methods'  => 'POST',
				'callback' => array( $this->cache_helper, 'update_excluded_urls' ),
				'permission_callback' => array( $this, 'check_permissions' ),
			)
		);

		register_rest_route(
			self::REST_NAMESPACE, '/test-url-cache/', array(
				'methods'  => 'POST',
				'callback' => array( $this->cache_helper, 'test_cache' ),
				'permission_callback' => array( $this, 'check_permissions' ),
			)
		);

		register_rest_route(
			self::REST_NAMESPACE, '/purge-cache/', array(
				'methods'  => 'GET',
				'callback' => array( $this->cache_helper, 'purge_cache_from_rest' ),
				'permission_callback' => array( $this, 'check_permissions' ),
			)
		);

		register_rest_route(
			self::REST_NAMESPACE, '/enable-memcache/', array(
				'methods'  => 'GET',
				'callback' => array( $this->cache_helper, 'enable_memcache' ),
				'permission_callback' => array( $this, 'check_permissions' ),
			)
		);

		register_rest_route(
			self::REST_NAMESPACE, '/disable-memcache/', array(
				'methods'  => 'GET',
				'callback' => array( $this->cache_helper, 'disable_memcache' ),
				'permission_callback' => array( $this, 'check_permissions' ),
			)
		);

	}

	/**
	 * Register the rest routes for images optimization.
	 *
	 * @since  5.4.0
	 */
	public function register_images_routes() {
		register_rest_route(
			self::REST_NAMESPACE, '/optimize-images/', array(
				'methods'  => 'GET',
				'callback' => array( $this->image_helper, 'optimize_images' ),
				'permission_callback' => array( $this, 'check_permissions' ),
			)
		);

		register_rest_route(
			self::REST_NAMESPACE, '/stop-images-optimization/', array(
				'methods'  => 'GET',
				'callback' => array( $this->image_helper, 'stop_images_optimization' ),
				'permission_callback' => array( $this, 'check_permissions' ),
			)
		);

		register_rest_route(
			self::REST_NAMESPACE, '/check-image-optimizing-status/', array(
				'methods'  => 'GET',
				'callback' => array( $this->image_helper, 'check_image_optimizing_status' ),
				'permission_callback' => array( $this, 'check_permissions' ),
			)
		);

		register_rest_route(
			self::REST_NAMESPACE, '/reset-images-optimization/', array(
				'methods'  => 'GET',
				'callback' => array( $this->image_helper, 'reset_images_optimization' ),
				'permission_callback' => array( $this, 'check_permissions' ),
			)
		);
	}

	/**
	 * Register the rest routes for webp conversion.
	 *
	 * @since  5.4.0
	 */
	public function register_webp_routes() {
		register_rest_route(
			self::REST_NAMESPACE, '/delete-webp-files/', array(
				'methods'  => 'GET',
				'callback' => array( $this->webp_helper, 'delete_webp_files' ),
				'permission_callback' => array( $this, 'check_permissions' ),
			)
		);

		register_rest_route(
			self::REST_NAMESPACE, '/generate-webp-files/', array(
				'methods'  => 'GET',
				'callback' => array( $this->webp_helper, 'generate_webp_files' ),
				'permission_callback' => array( $this, 'check_permissions' ),
			)
		);

		register_rest_route(
			self::REST_NAMESPACE, '/stop-webp-conversion/', array(
				'methods'  => 'GET',
				'callback' => array( $this->webp_helper, 'stop_webp_conversion' ),
				'permission_callback' => array( $this, 'check_permissions' ),
			)
		);

		register_rest_route(
			self::REST_NAMESPACE, '/check-webp-conversion-status/', array(
				'methods'  => 'GET',
				'callback' => array( $this->webp_helper, 'check_webp_conversion_status' ),
				'permission_callback' => array( $this, 'check_permissions' ),
			)
		);
	}

	/**
	 * Register multisite rest routes.
	 *
	 * @since  5.4.0
	 */
	public function register_multisite_rest_routes() {
		register_rest_route(
			self::REST_NAMESPACE, '/enable-multisite-optimization/', array(
				'methods'  => 'POST',
				'callback' => array( $this->multisite_helper, 'enable_multisite_optimization' ),
				'permission_callback' => array( $this, 'check_permissions' ),
			)
		);

		register_rest_route(
			self::REST_NAMESPACE, '/disable-multisite-optimization/', array(
				'methods'  => 'POST',
				'callback' => array( $this->multisite_helper, 'disable_multisite_optimization' ),
				'permission_callback' => array( $this, 'check_permissions' ),
			)
		);
	}

	/**
	 * Register Cloudflare routes.
	 *
	 * @since  5.7
	 */
	public function register_cloudflare_routes() {
		register_rest_route(
			self::REST_NAMESPACE, '/authenticate-cloudflare/', array(
				'methods'  => 'POST',
				'callback' => array( $this->cloudflare_helper, 'authenticate' ),
				'permission_callback' => array( $this, 'check_permissions' ),
			)
		);

		register_rest_route(
			self::REST_NAMESPACE, '/purge-cloudflare-cache/', array(
				'methods'  => 'GET',
				'callback' => array( $this->cloudflare_helper, 'purge_cloudflare_cache_from_rest' ),
				'permission_callback' => array( $this, 'check_permissions' ),
			)
		);

		register_rest_route(
			self::REST_NAMESPACE, '/deauthenticate-cloudflare/', array(
				'methods'  => 'GET',
				'callback' => array( $this->cloudflare_helper, 'deauthenticate' ),
				'permission_callback' => array( $this, 'check_permissions' ),
			)
		);
	}

	/**
	 * Register misc rest routes.
	 *
	 * @since  5.4.0
	 */
	public function register_misc_rest_routes() {
		register_rest_route(
			self::REST_NAMESPACE, '/hide-rating/', array(
				'methods'  => 'GET',
				'callback' => array( $this->misc_helper, 'handle_hide_rating' ),
				'permission_callback' => array( $this, 'check_permissions' ),
			)
		);

		register_rest_route(
			self::REST_NAMESPACE, '/get-assets/', array(
				'methods'  => 'GET',
				'callback' => array( $this->misc_helper, 'get_assets' ),
				'permission_callback' => array( $this, 'check_permissions' ),
			)
		);

		register_rest_route(
			self::REST_NAMESPACE, '/update-exclude-list/', array(
				'methods'  => 'POST',
				'callback' => array( $this->misc_helper, 'update_exclude_list' ),
				'permission_callback' => array( $this, 'check_permissions' ),
			)
		);

		register_rest_route(
			self::REST_NAMESPACE, '/run-analysis/', array(
				'methods'  => 'POST',
				'callback' => array( $this->misc_helper, 'run_analysis' ),
				'permission_callback' => array( $this, 'check_permissions' ),
			)
		);
	}
}
