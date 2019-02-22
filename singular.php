<?php
/**
 * The template for displaying pages and custom post types
 * @link https://codex.wordpress.org/Template_Hierarchy
 */

get_header();?>
<main id="content" class="site-content">
	<?php if ( have_posts() ) :

		while ( have_posts() ) : the_post();

			get_template_part( 'template-parts/content', get_post_type() );

		endwhile;

	else :

	endif;  ?>
</main>

<?php // get_sidebar();
get_footer();