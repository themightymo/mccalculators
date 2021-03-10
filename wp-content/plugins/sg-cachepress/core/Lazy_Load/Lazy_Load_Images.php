<?php
namespace SiteGround_Optimizer\Lazy_Load;

use SiteGround_Optimizer\Options\Options;
/**
 * SG Lazy_Load_Images main plugin class
 */
class Lazy_Load_Images extends Abstract_Lazy_Load {

	/**
	 * Regex parts for checking content
	 *
	 * @var string
	 */
	public $regexp = '/<img[\s\r\n]+.*?>/is';

	/**
	 * Regex for already replaced items
	 *
	 * @var string
	 */
	public $regex_replaced = "/src=['\"]data:image/is";

	/**
	 * Replace patterns.
	 *
	 * @var array
	 */
	public $patterns = array(
		'/(?<!noscript\>)((<img.*?src=["|\'].*?["|\']).*?(\/?>))/i',
		'/(?<!noscript\>)(<img.*?)(src)=["|\']((?!data).*?)["|\']/i',
		'/(?<!noscript\>)(<img.*?)((srcset)=["|\'](.*?)["|\'])/i',
	);

	/**
	 * Replacements.
	 *
	 * @var array
	 */
	public $replacements = array(
		'$1<noscript>$1</noscript>',
		'$1src="data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7" data-$2="$3"',
		'$1data-$3="$4"',
	);

	/**
	 * The constructor.
	 *
	 * @since 5.0.0
	 */
	public function __construct() {
		parent::__construct();

		// If enabled replace the 'src' attr with 'data-src' in text widgets.
		if ( Options::is_enabled( 'siteground_optimizer_lazyload_textwidgets' ) ) {
			add_filter( 'widget_text', array( $this, 'filter_html' ) );
		}

		// If enabled replace the 'src' attr with 'data-src' in the_post_thumbnail.
		if ( Options::is_enabled( 'siteground_optimizer_lazyload_thumbnails' ) ) {
			add_filter( 'post_thumbnail_html', array( $this, 'filter_html' ) );
		}

		// If enabled replace the 'src' attr with 'data-src' in the_post_thumbnail.
		if ( Options::is_enabled( 'siteground_optimizer_lazyload_gravatars' ) ) {
			add_filter( 'get_avatar', array( $this, 'filter_html' ) );
		}

		// If enabled replace the 'src' attr with 'data-src' in text widgets.
		if ( Options::is_enabled( 'siteground_optimizer_lazyload_woocommerce' ) ) {
			add_filter( 'woocommerce_product_get_image', array( $this, 'filter_html' ) );
			add_filter( 'woocommerce_single_product_image_thumbnail_html', array( $this, 'filter_html' ) );
		}
	}

	/**
	 * Add classname to the html element.
	 *
	 * @since  5.6.0
	 *
	 * @param  string $element HTML element.
	 *
	 * @return string          HTML element with lazyload class.
	 */
	public function add_lazyload_class( $element ) {
		return str_replace( '<img', '<img class="lazyload"', $element );
	}
}
