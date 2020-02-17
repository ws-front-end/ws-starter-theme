<?php
/**
 * Template part for displaying posts
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 */

?>

<section>
    <?php
if ( is_singular() ) :
	the_title( '<h1 class="entry-title section-title">', '</h1>' );
	else :
		the_title( '<h2 class="entry-title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h2>' );
	endif;
	?>

    <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
        <div class="entry-content">
            <?php
		the_content();
		
		wp_link_pages(
			[
				'before' => '<div class="page-links">' . esc_html__( 'Pages:' ),
				'after'  => '</div>',
				]
			);
			?>
        </div><!-- .entry-content -->

    </article><!-- #post-<?php the_ID(); ?> -->
</section>
