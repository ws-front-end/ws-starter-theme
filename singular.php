<?php
/**
 * The template for displaying pages and custom post types
 *
 * @package ws-starter-theme
 */

get_header();

if ( have_posts() ) :

	while ( have_posts() ) :
		the_post();

		get_template_part( 'template-parts/content', get_post_type() );

	endwhile;

endif;

get_footer();
