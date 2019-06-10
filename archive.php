<?php
/**
 * The template for displaying archive pages
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 */

get_header();

if ( have_posts() ) :

	while ( have_posts() ) :
		the_post();

		get_template_part( 'template-parts/content', get_post_format() );

	endwhile;

	the_posts_navigation();

else:
	echo _e( 'Nothing found.' );
endif;

// get_sidebar('main-sidebar');

get_footer();