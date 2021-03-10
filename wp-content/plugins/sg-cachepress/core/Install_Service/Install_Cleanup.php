<?php
namespace SiteGround_Optimizer\Install_Service;
use SiteGround_Optimizer\Htaccess\Htaccess;
use SiteGround_Optimizer\Helper\Helper;
use SiteGround_Optimizer\Options\Options;

class Install_Cleanup {

	/**
	 * Run the install procedure.
	 *
	 * @since 5.5.4
	 */
	public static function cleanup() {
		$htaccess = new Htaccess();
		// Disable gzip & BC rules for avalon.
		if ( Helper::is_avalon() ) {
			$htaccess->disable( 'gzip' );
			$htaccess->disable( 'browser-caching' );
			return;
		}

		if ( ! Options::is_enabled( 'siteground_optimizer_enable_browser_caching' ) ) {
			$htaccess->disable( 'browser-caching' );
		}

		if ( ! Options::is_enabled( 'siteground_optimizer_enable_gzip_compression' ) ) {
			$htaccess->disable( 'gzip' );
		}
	}
}
