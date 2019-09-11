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
		add_action( 'widgets_init', [ $this, 'theme_widgets_sidebar_register' ] );
		add_action( 'wp_before_admin_bar_render', [ $this, 'add_admin_bar_button' ] );
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
		add_filter( 'parse_query', [ $this, 'hide_options_page' ] );
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
			[
				'search-form',
				'comment-form',
				'comment-list',
				'gallery',
				'caption',
			]
		);

		register_nav_menus(
			[
				'header-menu' => esc_html__( 'Header', 'ws-starter-theme' ),
			]
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

		wp_enqueue_script( 'ws-custom-js', get_template_directory_uri() . '/assets/dist/js/bundle.min.js', [ 'jquery' ], $asset_version, true );
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

	/**
	 * Registers sidebars for widgets.
	 */
	public function theme_widgets_sidebar_register() {
		register_sidebar(
			[
				'name'          => __( 'Main Sidebar' ),
				'id'            => 'main-sidebar',
				'description'   => __( 'Widgets in this area can be called out with ' ),
				'before_widget' => '<div id="%1$s" class="widget %2$s">',
				'after_widget'  => '</div>',
				'before_title'  => '<h3 class="widget-title">',
				'after_title'   => '</h3>',
			]
		);
	}

	/**
	 * Adds custom admin bar button.
	 */
	public static function add_admin_bar_button() {
		get_template_part( 'template-parts/adminbar-button' );
	}

	/**
	 * Returns the themes options page id.
	 * TODO: Add options page to admin menu
	 * TODO: Hide from pages list in admin
	 *
	 * @return int|WP_Error
	 */
	public static function get_theme_options_page_id() {
		$ws_options_page_id = get_option( 'WS_OPTIONS_PAGE_ID', false );
		if ( false === $ws_options_page_id ) {
			$args  = [
				'post_type'                         => 'page',
				'fields'                            => 'ids',
				'nopaging'                          => true,
				'meta_key'                          => "_wp_page_template",
				'meta_value'                        => 'page-templates/ws-general-options-page-dummy-template.php',
				'numberposts'                       => 1,
				'disable_for_options_page_id_query' => 'yes',
			];
			$pages = get_posts( $args );
			if ( ! empty( $pages ) ) {
				$ws_options_page_id = $pages[0];

				return apply_filters( 'wpml_object_id', $ws_options_page_id, 'page', true );
			} else {
				return new WP_Error( 'Unable to fetch options page ID. It might not exist?' );
			}
		}

		return apply_filters( 'wpml_object_id', $ws_options_page_id, 'page', true );
	}

	/**
	 * Hides options page from everywhere.
	 *
	 * @param WP_Query $query WordPress query instance.
	 */
	public function hide_options_page( $query ) {
		global $pagenow, $post_type;
		if ( is_admin() && 'edit.php' === $pagenow && 'page' === $post_type ) {
			if ( ! filter_var( $query->get( 'disable_for_options_page_id_query' ), FILTER_VALIDATE_BOOLEAN ) ) {
				$options_page_id = self::get_theme_options_page_id();
				if ( ! is_wp_error( $options_page_id ) && filter_var( $options_page_id, FILTER_VALIDATE_INT ) ) {
					if ( 'ws-general-options-page-dummy-template.php' !== $query->query_vars['meta_value'] ) {
						$post_not_in = $query->query_vars['post__not_in'];
						if ( ! is_array( $post_not_in ) ) {
							$post_not_in = [];
						}
						$post_not_in[] = $options_page_id;

						$query->query_vars['post__not_in'] = $post_not_in;
					}
				}
			}
		}
	}

	/**
	 * Gets the theme option value for the page id or current page
	 *
	 * @param string $meta_key Meta key of theme option that is fetched.
	 * @param integer $post_id Post ID from which to get meta value from.
	 *
	 * @return string|int|array
	 */
	public static function get_theme_option( string $meta_key, int $post_id = 0 ) {
		if ( ! function_exists( 'get_field' ) ) {
			return null;
		}

		if ( 0 === $post_id ) {
			$post_id = get_the_id();
		}
		if ( class_exists( 'SitePress' ) ) {
			$post_id = apply_filters( 'wpml_object_id', $post_id, 'page', true );
		}
		$meta_value = get_field( $meta_key, $post_id );
		if ( $meta_value ) {
			return $meta_value;
		}

		return get_field( $meta_key, self::get_theme_options_page_id() );
	}


	/**
	 * Generate img tag with various attributes.
	 *
	 * @param int|bool $image_id Image ID to generate img tag of.
	 * @param array $args Various arguments.
	 *
	 * @return string
	 */
	public function generate_img_html( $image_id = false, $args = [] ) {
		if ( ! $image_id ) {
			return '';
		}

		$default_args = [
			'size'             => 'large',
			'disable_lazyload' => false,
			'for_swiper'       => false,
			'classes'          => [],
			'alt'              => '',
			'title'            => '',
		];

		$options = wp_parse_args( $args, $default_args );

		$img_html = '<img ';

		if ( $options['disable_lazyload'] ) {
			$img_html .= 'src="' . esc_url( wp_get_attachment_image_url( $image_id, $options['size'] ) ) . '" ';
			$img_html .= 'class="' . esc_attr( implode( ' ', $options['classes'] ) ) . '" ';
			$img_html .= 'srcset="' . esc_attr( wp_get_attachment_image_srcset( $image_id, $options['size'] ) ) . '" ';
			$img_html .= 'sizes="' . esc_attr( wp_get_attachment_image_sizes( $image_id, $options['size'] ) ) . '"';
		} else {
			$img_html .= 'data-src="' . esc_url( wp_get_attachment_image_url( $image_id, $options['size'] ) ) . '" ';
			$img_html .= 'data-srcset="' . esc_attr( wp_get_attachment_image_srcset( $image_id, $options['size'] ) ) . '" ';
			$img_html .= 'data-sizes="' . esc_attr( wp_get_attachment_image_sizes( $image_id, $options['size'] ) ) . '"';

			if ( $options['for_swiper'] ) {
				$img_html .= 'class="swiper-lazy ' . esc_attr( implode( ' ', $options['classes'] ) ) . '" ';
			} else {
				$img_html .= 'class="js-lazyload-image ' . esc_attr( implode( ' ', $options['classes'] ) ) . '" ';
				$img_html .= 'src="' . esc_url( wp_get_attachment_image_url( $image_id, 'thumbnail' ) ) . '" ';
			}
		}

		$img_html .= 'alt="' . $options['alt'] . '"';
		$img_html .= 'title="' . $options['title'] . '"';

		$img_html .= '/>';

		return $img_html;
	}
}

new ThemeSetup();
