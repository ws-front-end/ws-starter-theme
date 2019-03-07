<?php
/**
 * The sidebar containing the main widget area
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 */

if ( ! is_active_sidebar( 'sidebar-1' ) ) {
	return;
}
?>

<?php if ( is_active_sidebar( 'main-sidebar' ) ) : ?>
<div id="sidebar-main-sidebar" class="sidebar">
	<?php dynamic_sidebar( 'main-sidebar' ); ?>
	<?php else : ?>
		<!-- Create some custom HTML or call the_widget().  It's up to you. -->
</div>
<?php endif; ?>