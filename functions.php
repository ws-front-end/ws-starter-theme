<?php
define('WS_TEXT_DOMAIN', 'default');

require_once('includes/ws-automatic-theme-functions.php');
require_once('includes/woocommerce-functions.php');


if ( ! function_exists( 'ws_starter_theme_setup' ) ) :
	/**
	 * General theme setup
	 */
	function ws_starter_theme_setup() {

		remove_action( 'wp_head', 'feed_links_extra', 3 );
		add_action( 'wp_head', 'ob_start', 1, 0 );
		add_action( 'wp_head', function () {
			$pattern = '/.*' . preg_quote( esc_url( get_feed_link( 'comments_' . get_default_feed() ) ), '/' ) . '.*[\r\n]+/';
			echo preg_replace( $pattern, '', ob_get_clean() );
		}, 3, 0 );
		remove_action( 'wp_head', 'rsd_link' );
		remove_action( 'wp_head', 'wlwmanifest_link' );
		remove_action( 'wp_head', 'adjacent_posts_rel_link_wp_head', 10 );
		remove_action( 'wp_head', 'wp_generator' );
		remove_action( 'wp_head', 'wp_shortlink_wp_head', 10 );
		remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
		remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
		remove_action( 'wp_print_styles', 'print_emoji_styles' );
		remove_action( 'admin_print_styles', 'print_emoji_styles' );
		remove_action( 'wp_head', 'wp_oembed_add_discovery_links' );
		remove_action( 'wp_head', 'wp_oembed_add_host_js' );
		remove_action( 'wp_head', 'rest_output_link_wp_head', 10 );
		remove_filter( 'the_content_feed', 'wp_staticize_emoji' );
		remove_filter( 'comment_text_rss', 'wp_staticize_emoji' );
		remove_filter( 'wp_mail', 'wp_staticize_emoji_for_email' );
		add_filter( 'use_default_gallery_style', '__return_false' );
		add_filter( 'emoji_svg_url', '__return_false' );
		add_filter( 'show_recent_comments_widget_style', '__return_false' );

		// Make theme available for translation.
		load_theme_textdomain( 'ws-starter-theme', get_template_directory() . '/languages' );

		// Add default posts and comments RSS feed links to head.
		add_theme_support( 'automatic-feed-links' );

		// Let WordPress manage the document title.
		add_theme_support( 'title-tag' );

		// Enable support for Post Thumbnails on posts and pages.
		add_theme_support( 'post-thumbnails' );

		add_image_size( 'x-large', 2560, 1440 );

		register_nav_menus( array(
		 'primary-menu' => esc_html__( 'Primary', 'ws-starter-theme' ),
		//  'sub-menu' => esc_html__( 'Sub', 'ws-starter-theme' ),
		) );

		// Switch default core markup for search form, comment form, and comments
		add_theme_support( 'html5', array(
		 'search-form',
		 'comment-form',
		 'comment-list',
		 'gallery',
		 'caption',
		) );
	}
endif;
add_action( 'after_setup_theme', 'ws_starter_theme_setup' );

function WS_make_urls_relative() {
	if ( is_admin() || isset( $_GET['sitemap'] ) || in_array( $GLOBALS['pagenow'], [
	  'wp-login.php',
	  'wp-register.php'
	 ] ) ) {
		return;
	}
	$root_rel_filters = [
	 'bloginfo_url',
	 'the_permalink',
	 'wp_list_pages',
	 'wp_list_categories',
	 'wp_get_attachment_url',
	 'the_content_more_link',
	 'the_tags',
	 'get_pagenum_link',
	 'get_comment_link',
	 'month_link',
	 'day_link',
	 'year_link',
	 'term_link',
	 'the_author_posts_link',
	 'script_loader_src',
	 'style_loader_src',
	 'theme_file_uri',
	 'parent_theme_file_uri',
	];
	WS_add_filters( $root_rel_filters, 'WS_root_relative_url' );
	add_filter( 'wp_calculate_image_srcset', function ( $sources ) {
		foreach ( (array) $sources as $source => $src ) {
			$sources[ $source ]['url'] = WS_root_relative_url( $src['url'] );
		}

		return $sources;
	} );
	/**
	 * Compatibility with The SEO Framework
	 */
	add_action( 'the_seo_framework_do_before_output', function () {
		remove_filter( 'wp_get_attachment_url', 'WS_root_relative_url' );
	} );
	add_action( 'the_seo_framework_do_after_output', function () {
		add_filter( 'wp_get_attachment_url', 'WS_root_relative_url' );
	} );
}

function WS_add_filters( $tags, $function, $priority = 10, $accepted_args = 1 ) {
	foreach ( (array) $tags as $tag ) {
		add_filter( $tag, $function, $priority, $accepted_args );
	}
}

function WS_root_relative_url( $input ) {
	if ( is_feed() ) {
		return $input;
	}
	$url = parse_url( $input );
	if ( ! isset( $url['host'] ) || ! isset( $url['path'] ) ) {
		return $input;
	}
	$site_url = parse_url( network_home_url() );  // falls back to home_url
	if ( ! isset( $url['scheme'] ) ) {
		$url['scheme'] = $site_url['scheme'];
	}
	$hosts_match   = $site_url['host'] === $url['host'];
	$schemes_match = $site_url['scheme'] === $url['scheme'];
	$ports_exist   = isset( $site_url['port'] ) && isset( $url['port'] );
	$ports_match   = ( $ports_exist ) ? $site_url['port'] === $url['port'] : true;
	if ( $hosts_match && $schemes_match && $ports_match ) {
		return wp_make_link_relative( $input );
	}

	return $input;
}

WS_make_urls_relative();


add_action( 'wp_enqueue_scripts', function () {
	remove_action( 'wp_head', 'wp_print_scripts' );
	remove_action( 'wp_head', 'wp_print_head_scripts', 9 );
	remove_action( 'wp_head', 'wp_enqueue_scripts', 1 );

	wp_enqueue_style( 'style-name', get_stylesheet_uri() );

	if ( WP_DEBUG == true && WP_DEBUG_DISPLAY == true ) {
		$assetVersion = date( 'YmdH' );
	} else {
		$manifest     = json_decode( file_get_contents( 'package.json', true ) );
		$assetVersion = $manifest->version;
	}

	wp_enqueue_script( 'ws-custom-js', get_template_directory_uri() . '/assets/dist/js/bundle.min.js', array('jquery'), $assetVersion, true );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
} );

add_filter( 'body_class', function ( $classes ) {

	// Adds a class of hfeed to non-singular pages.
	if ( ! is_singular() ) {
		$classes[] = 'hfeed';
	}

	return $classes;

} );

add_action( 'wp_head', function () {
	if ( is_singular() && pings_open() ) {
		echo '<link rel="pingback" href="', esc_url( get_bloginfo( 'pingback_url' ) ), '">';
	}

} );

add_action( 'the_generator', '__return_empty_string' );