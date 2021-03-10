<?php
namespace SiteGround_Optimizer\Helper;

use SiteGround_Optimizer;
use SiteGround_Optimizer\Admin\Admin;
use SiteGround_Optimizer\Rest\Rest;
use SiteGround_Optimizer\Supercacher\Supercacher;
use SiteGround_Optimizer\Supercacher\Supercacher_Helper;
use SiteGround_Optimizer\Install_Service\Install_Service;
use SiteGround_Optimizer\Memcache\Memcache;
use SiteGround_Optimizer\Front_End_Optimization\Front_End_Optimization;
use SiteGround_Optimizer\Cli\Cli;
use SiteGround_Optimizer\Config\Config;
use SiteGround_Optimizer\I18n\I18n;
use SiteGround_Optimizer\Heartbeat_Control\Heartbeat_Control;
use SiteGround_Optimizer\Database_Optimizer\Database_Optimizer;
use SiteGround_Optimizer\DNS\Cloudflare;
use SiteGround_Optimizer\Settings\Settings;

/**
 * Helper functions and main initialization class.
 */
class Helper {

	/**
	 * Create a new helper.
	 */
	public function __construct() {
		add_action( 'plugins_loaded', array( $this, 'is_plugin_installed' ) );
		add_action( 'init', array( $this, 'hide_warnings_in_rest_api' ) );
		add_action( 'wp_head', array( $this, 'add_plugin_info_comment' ), 1, 2 );

		set_error_handler( array( $this, 'error_handler' ) );

		// Run the plugin functionality.
		$this->run();
	}

	/**
	 * Run the plugin functionality.
	 *
	 * @since  5.0.0
	 */
	public function run() {
		new I18n();

		new Install_Service();
		// Initialize dashboard page.
		new Admin();

		// Initialize the rest api endpoints.
		new Rest();

		// Init the supercacher.
		// DO NOT REMOVE $this->supercacher, as its used from `sg_cachepress_purge_cache` helper function.
		$this->supercacher = new Supercacher();

		// Init the memcacher.
		new Memcache();

		// Init and run the helper class that set cache headers and bypass cookies.
		new Supercacher_Helper();

		// Init the main class responsible for front-end optionmization.
		new Front_End_Optimization();

		// Init the CLI commands.
		new Cli();

		// Init the config class.
		new Config();

		// Init the Heartbeat Control.
		new Heartbeat_Control();

		// Init the Database Optimizer.
		new Database_Optimizer();

		// Init Cloudflare API.
		new Cloudflare();

		// Init Settings class.
		new Settings();

	}

	/**
	 * Check if the plugin is installed.
	 *
	 * @since  5.0.0
	 */
	public function is_plugin_installed() {
		if (
			isset( $_GET['sgCacheCheck'] ) &&
			md5( 'wpCheck' ) === $_GET['sgCacheCheck']
		) {
			die( 'OK' );
		}
	}

	/**
	 * Load the global wp_filesystem.
	 *
	 * @since  5.0.0
	 *
	 * @return object The instance.
	 */
	public static function setup_wp_filesystem() {
		global $wp_filesystem;

		// Initialize the WP filesystem, no more using 'file-put-contents' function.
		if ( empty( $wp_filesystem ) ) {
			require_once( ABSPATH . '/wp-admin/includes/file.php' );
			WP_Filesystem();
		}

		return $wp_filesystem;
	}

	/**
	 * Check if wp cron is disabled and send error message.
	 *
	 * @since  5.0.0
	 */
	public static function is_cron_disabled() {
		if ( defined( 'DISABLE_WP_CRON' ) && true == DISABLE_WP_CRON ) {
			return 1;
		}

		return 0;
	}

	/**
	 * Hide warnings in rest api.
	 *
	 * @since  5.0.0
	 */
	public function hide_warnings_in_rest_api() {
		if ( self::is_rest() ) {
			error_reporting( E_ERROR | E_PARSE );
		}
	}

	/**
	 * Checks if the current request is a WP REST API request.
	 *
	 * Case #1: After WP_REST_Request initialisation
	 * Case #2: Support "plain" permalink settings
	 * Case #3: URL Path begins with wp-json/ (your REST prefix)
	 *          Also supports WP installations in subfolders
	 *
	 * @since 5.0.0
	 *
	 * @return bool True if it's rest request, false otherwise.
	 */
	public static function is_rest() {
		$prefix = rest_get_url_prefix();

		if (
			defined( 'REST_REQUEST' ) && REST_REQUEST ||
			(
				isset( $_GET['rest_route'] ) &&
				0 === @strpos( trim( $_GET['rest_route'], '\\/' ), $prefix, 0 )
			)
		) {
			return true;
		}

		$rest_url    = wp_parse_url( site_url( $prefix ) );
		$current_url = wp_parse_url( add_query_arg( array() ) );

		return 0 === @strpos( $current_url['path'], $rest_url['path'], 0 );
	}

	/**
	 * Our custom error handler
	 *
	 * @since 5.0.8
	 *
	 * @param int    $errno        The first parameter, errno, contains the level of the error raised.
	 * @param string $errstr    The second parameter, errstr, contains the error message.
	 * @param string $errfile   The third parameter is optional, errfile, which contains the
	 *                          filename that the error was raised in.
	 * @param int    $errline      The fourth parameter is optional, errline, which contains the line
	 *                             number the error was raised at.
	 * @param array  $errcontext The fifth parameter is optional, errcontext that contains an array
	 *                           of every variable that existed in the scope the error was triggered
	 *                           in. User error handler must not modify error context.
	 * @return bool             True if error is within /plugins, false otherwise.
	 */
	public function error_handler( $errno, $errstr, $errfile, $errline, $errcontext = array() ) {
		// Path to error file.
		$error_file = str_replace( '\\', '/', $errfile );

		// Path to plugins.
		$vendor = str_replace( '\\', '/', SiteGround_Optimizer\DIR . '/vendor' );

		// Do nothing for errors inside of the plugins directory.
		if ( @strpos( $error_file, $vendor ) !== false ) {
			return true;
		}

		// Default error handler otherwise.
		return false;
	}

	/**
	 * Some plugins like WPML for example are overwriting the home url.
	 *
	 * @since  5.0.10
	 *
	 * @return string The real home url.
	 */
	public static function get_home_url() {
		$url = get_option( 'home' );

		$scheme = is_ssl() ? 'https' : parse_url( $url, PHP_URL_SCHEME );

		$url = set_url_scheme( $url, $scheme );

		return trailingslashit( $url );
	}

	/**
	 * Some plugins like WPML for example are overwriting the site url.
	 *
	 * @since  5.0.10
	 *
	 * @return string The real site url.
	 */
	public static function get_site_url() {
		$url = get_option( 'siteurl' );

		$scheme = is_ssl() ? 'https' : parse_url( $url, PHP_URL_SCHEME );

		$url = set_url_scheme( $url, $scheme );

		return trailingslashit( $url );
	}

	/**
	 * Checks if the plugin run on the new SiteGround interface.
	 *
	 * @since  5.3.0
	 *
	 * @return boolean True/False.
	 */
	public static function is_avalon() {
		return (int) file_exists( '/etc/yum.repos.d/baseos.repo' );
	}

	/**
	 * Add comment in the head tag
	 *
	 * @since  5.6.0
	 */
	public function add_plugin_info_comment() {
		echo '<!-- Optimized by SG Optimizer plugin version - ' . \SiteGround_Optimizer\VERSION . ' -->';
	}

	/**
	 * Checks what are the upload dir permissions.
	 *
	 * @since  5.7.11
	 *
	 * @return boolean True/false
	 */
	public static function check_upload_dir_permissions() {
		// If the function does not exist the file permissions are correct.
		if ( ! function_exists( 'fileperms' ) ) {
			return true;
		}

		// Check if directory permissions are set accordingly.
		if ( 700 <= intval( substr( sprintf( '%o', fileperms( self::get_uploads_dir() ) ), -3 ) ) ) {
			return true;
		}

		// Return false if permissions are below 700.
		return false;
	}

	/**
	 * Get WordPress uploads dir
	 *
	 * @since  5.7.11
	 *
	 * @return string Path to the uploads dir.
	 */
	public static function get_uploads_dir() {
		// Get the uploads dir.
		$upload_dir = wp_upload_dir();

		$base_dir = $upload_dir['basedir'];

		if ( defined( 'UPLOADS' ) ) {
			$base_dir = ABSPATH . UPLOADS;
		}

		return $base_dir;
	}
}
