<?php
/**
 * The template for displaying pages and custom post types
 * @link https://codex.wordpress.org/Template_Hierarchy
 */

get_header();?>
<?php if ( have_posts() ) :

	while ( have_posts() ) : the_post();

		get_template_part( 'template-parts/content', get_post_type() );

	endwhile;

else :

endif;  ?>
<?php
// get_sidebar('main-sidebar');
get_footer();