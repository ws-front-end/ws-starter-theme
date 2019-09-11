<?php
/**
 * The main template file
 * @link https://codex.wordpress.org/Template_Hierarchy
 */

get_header();

if ( have_posts() ) :

	while ( have_posts() ) :
		the_post();

		get_template_part( 'template-parts/content', get_post_format() );

	endwhile;

	the_posts_navigation();

endif;

get_footer();
