<?php
/**
 * Template Name: Sample template
 */

get_header(); ?>

<?php while ( have_posts() ) : the_post(); ?>

<!-- Write HTML here -->

<?php endwhile; ?>

<?php
// get_sidebar();
get_footer();