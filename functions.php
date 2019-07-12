<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

require_once 'includes/classes/class-ws-acf-controller.php';
require_once 'includes/classes/class-ws-relative-url-maker.php';
require_once 'includes/classes/class-ws-woocommerce-functions.php';
require_once 'includes/classes/class-ws-ajax-functions.php';
require_once 'includes/classes/class-admin-post-functions.php';

/**
 * Class ThemeSetup
 */
class ThemeSetup {
	/**
	 * ThemeSetup constructor.
	 */
	public function __construct() {
		$this->remove_actions();
		$this->remove_filters();
		$this->add_actions();
		$this->add_filters();

		new WS_Acf_Controller();
		new WS_Relative_Url_Maker();
		new WS_Admin_Post_Functions();
		new WS_Ajax_Functions();
		if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ), true ) ) {
			new WS_Woocommerce_Functions();
		}
	}
	/**
	 * Add WordPress actions
	 */
	private function add_actions() {
		add_action( 'wp_head', 'ob_start', 1, 0 );
		add_action( 'after_setup_theme', [ $this, 'ws_theme_setup' ] );
		add_action( 'wp_enqueue_scripts', [ $this, 'ws_theme_enqueue_scripts_and_styles' ] );
		add_action( 'the_generator', '__return_empty_string' );
		add_action( 'widgets_init', [$this, 'theme_widgets_sidebar_register'] );
		add_action( 'wp_before_admin_bar_render', ['ThemeSetup', 'add_admin_bar_button'] ); 
		add_filter( 'ws_get_url_from_acf_image_array', [ $this, 'ws_get_url_from_acf_image_array' ], 10, 3 );
	}
	/**
	 * Remove WordPress existing filters
	 */
	private function remove_filters() {
		// remove_filter('filter_name', 'function_name', 'priority); !
	}
	/**
	 * Remove WordPress existing actions
	 */
	private function remove_actions() {
		remove_action( 'wp_head', 'feed_links_extra', 3 );
		remove_action( 'wp_head', 'rsd_link' );
		remove_action( 'wp_head', 'wlwmanifest_link' );
		remove_action( 'wp_head', 'adjacent_posts_rel_link_wp_head', 10 );
		remove_action( 'wp_head', 'wp_generator' );
		remove_action( 'wp_head', 'wp_shortlink_wp_head', 10 );
		remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
		remove_action( 'wp_head', 'wp_oembed_add_discovery_links' );
		remove_action( 'wp_head', 'wp_oembed_add_host_js' );
		remove_action( 'wp_head', 'rest_output_link_wp_head', 10 );
		remove_filter( 'wp_mail', 'wp_staticize_emoji_for_email' );
		remove_action( 'wp_print_styles', 'print_emoji_styles' );
		remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
		remove_action( 'admin_print_styles', 'print_emoji_styles' );
		remove_filter( 'the_content_feed', 'wp_staticize_emoji' );
		remove_filter( 'comment_text_rss', 'wp_staticize_emoji' );
	}
	/**
	 * Add WordPress custom filters
	 */
	private function add_filters() {
		add_filter( 'body_class', [ $this, 'ws_body_class' ] );
		add_filter( 'use_default_gallery_style', '__return_false' );
		add_filter( 'emoji_svg_url', '__return_false' );
		add_filter( 'show_recent_comments_widget_style', '__return_false' );
	}
	/**
	 * Setup various WordPress theme variables
	 */
	public function ws_theme_setup() {
		// Make theme available for translation.
		load_theme_textdomain( 'ws-starter-theme', get_template_directory() . '/languages' );

		add_image_size( '1440p', 2560, 1440 );
		add_image_size( '4k', 4096, 2160 );

		// Add default posts and comments RSS feed links to head.
		add_theme_support( 'automatic-feed-links' );

		// Let WordPress manage the document title.
		add_theme_support( 'title-tag' );

		// Enable support for Post Thumbnails on posts and pages.
		add_theme_support( 'post-thumbnails' );

		// Switch default core markup for search form, comment form, and comments.
		add_theme_support(
			'html5',
			array(
				'search-form',
				'comment-form',
				'comment-list',
				'gallery',
				'caption',
			)
		);

		register_nav_menus(
			array(
				'header-menu' => esc_html__( 'Header', 'ws-starter-theme' ),
			)
		);
	}
	/** Adds a certain class to body tag at specified situations.
	 *
	 * @param array $classes Array of existing classes.
	 *
	 * @return array
	 */
	public function ws_body_class( $classes ) {
		// Adds a class of hfeed to non-singular pages.
		if ( ! is_singular() ) {
			$classes[] = 'hfeed';
		}
		return $classes;
	}
	/** Enqueues theme stylesheet and Javascript files.
	 */
	public function ws_theme_enqueue_scripts_and_styles() {
		$manifest      = json_decode( file_get_contents( get_template_directory_uri() . '/package.json', true ) );
		$asset_version = $manifest->version;

		wp_enqueue_style( 'ws-main-stylesheet', get_stylesheet_uri(), [], $asset_version );

		wp_enqueue_script( 'ws-custom-js', get_template_directory_uri() . '/assets/dist/js/bundle.min.js', array( 'jquery' ), $asset_version, true );
		wp_localize_script(
			'ws-custom-js',
			'php_object',
			[
				'ajax_url' => admin_url( 'admin-ajax.php' ),
			]
		);

		if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
			wp_enqueue_script( 'comment-reply' );
		}
	}
	/**
	 * Registers a WordPress sidebar
	 */
	public function ws_head_pingbacks() {
		if ( is_singular() && pings_open() ) {
			echo '<link rel="pingback" href="', esc_url( get_bloginfo( 'pingback_url' ) ), '">';
		}
	}
	
	/** Get any field from ACF image array. Also able to get certain size only if image URL is asked for.
	 *
	 * @param array  $image_array Image array from ACF image field.
	 * @param string $field Name of the field required from the image array.
	 * @param string $size Image size slug.
	 *
	 * @return bool|mixed Returns false if field or image size not found. Otherwise returns the requested value.
	 */
	public function ws_get_url_from_acf_image_array( $image_array = [], $field = 'url', $size = 'medium' ) {
		if ( ! is_array( $image_array ) || empty( $image_array ) ) {
			return false;
		}
		if ( 'url' === $field || 'sizes' === $field ) {
			if ( isset( $image_array['sizes'][ $size ] ) ) {
				return $image_array['sizes'][ $size ];
			}
		} else {
			if ( isset( $image_array[ $field ] ) ) {
				return $image_array[ $field ];
			}
		}
		return false;
	}
	public function theme_widgets_sidebar_register() {
		register_sidebar(
			array(
				'name'          => __( 'Main Sidebar' ),
				'id'            => 'main-sidebar',
				'description'   => __( 'Widgets in this area can be called out with ' ),
				'before_widget' => '<div id="%1$s" class="widget %2$s">',
				'after_widget'  => '</div>',
				'before_title'  => '<h3 class="widget-title">',
				'after_title'   => '</h3>',
			)
		);
	}
	public static function add_admin_bar_button() {
		get_template_part('template-parts/adminbar-button');
	}
}

new ThemeSetup();
