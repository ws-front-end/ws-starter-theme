<?php
/**
 * The template for displaying archive pages
 *
 * @package ws-starter-theme
 */

get_header();

if ( have_posts() ) :

	while ( have_posts() ) :
		the_post();

		get_template_part( 'template-parts/content', get_post_format() );

	endwhile;

	the_posts_navigation();

else :
	esc_html_e( 'Nothing found.' );
endif;

get_footer();
