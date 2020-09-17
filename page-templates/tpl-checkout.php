<?php
/**
 * Template Name: Checkout tpl
 */

get_header();?>
<?php if ( have_posts() ) :

	while ( have_posts() ) : the_post();

		the_content();

	endwhile;

else :

endif;  ?>
<?php
// get_sidebar('main-sidebar');
get_footer();
