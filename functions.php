<?php

// Sets up theme defaults and registers support for various WordPress features.
if ( ! function_exists( 'ws_starter_theme_setup' ) ) :
	function ws_starter_theme_setup() {

        // Make theme available for translation.
		load_theme_textdomain( 'ws-starter-theme', get_template_directory() . '/languages' );

        // Add default posts and comments RSS feed links to head.
        add_theme_support( 'automatic-feed-links' );

        // Let WordPress manage the document title.
        add_theme_support( 'title-tag' );

        // Enable support for Post Thumbnails on posts and pages.
		add_theme_support( 'post-thumbnails' );

        add_image_size( 'sample', 2560, 1440 );

		register_nav_menus( array(
			'menu-1' => esc_html__( 'Primary', 'ws-starter-theme' ),
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

// Register widget area.
/*
function ws_starter_theme_widgets_init() {
	register_sidebar( array(
		'name'          => esc_html__( 'Sidebar', 'ws-starter-theme' ),
		'id'            => 'sidebar-1',
		'description'   => esc_html__( 'Add widgets here.', 'ws-starter-theme' ),
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>',
	) );
}
add_action( 'widgets_init', 'ws_starter_theme_widgets_init' );
*/



// Enqueue scripts and styles.
function ws_starter_theme_scripts() {
	$assetVersion = WP_DEBUG == true && WP_DEBUG_DISPLAY == true  ? date('YmdH'):'0.1';
    // Styles
	wp_enqueue_style( 'ws-style-css', get_stylesheet_uri() );
	wp_enqueue_style( 'ws-main-css', get_template_directory_uri() . '/assets/dist/css/main.css', array(), $assetVersion );
	// wp_enqueue_style( 'ws-main-css', get_template_directory_uri() . '/assets/dist/css/main.min.css' );

    // Scripts
	wp_enqueue_script( 'ws-vendor-js', get_template_directory_uri() . '/assets/dist/js/vendors.js', array(), $assetVersion, true );
	wp_enqueue_script( 'ws-custom-js', get_template_directory_uri() . '/assets/dist/js/custom.min.js', array( 'jquery', 'ws-vendor-js' ), $assetVersion, true );
	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}

}
add_action( 'wp_enqueue_scripts', 'ws_starter_theme_scripts' );



// Adds custom classes to the array of body classes.
function ws_starter_theme_body_classes( $classes ) {

	// Adds a class of hfeed to non-singular pages.
	if ( ! is_singular() ) {
		$classes[] = 'hfeed';
	}
	return $classes;

}
add_filter( 'body_class', 'ws_starter_theme_body_classes' );



// Add a pingback url auto-discovery header for singularly identifiable articles.
function ws_starter_theme_pingback_header() {

	if ( is_singular() && pings_open() ) {
		echo '<link rel="pingback" href="', esc_url( get_bloginfo( 'pingback_url' ) ), '">';
	}

}
add_action( 'wp_head', 'ws_starter_theme_pingback_header' );



function project_remove_version() {

    return '';
    
}
add_action('the_generator', 'project_remove_version');

/*******************/
/*** Woocommerce ***/
/*******************/
if ( class_exists( 'WooCommerce' ) ) {
	require_once('woocommerce-functions.php');
}