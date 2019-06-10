<?php
/**
 * The header for our theme
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 */

?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="profile" href="http://gmpg.org/xfn/11">
	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<div id="page" class="site">

	<header id="masthead" class="site-header">

		<div class="site-header__logo">
			<a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home" title="<?php bloginfo( 'name' ); ?>">
				<?php get_template_part( 'template-parts/svg/site-logo' ); ?>
			</a>
		</div>

		<button class="site-header__menu-toggle menu-toggle hamburger hamburger--squeeze" id="js-main-menu-toggle" aria-controls="js-main-menu" aria-expanded="false" type="button">
			<span class="hamburger-box">
				<span class="hamburger-inner"></span>
			</span>
		</button>

		<nav class="site-header__nav" id="js-main-menu-container">

			<div class="lang-container">
				<?php
					$languages = apply_filters('wpml_active_languages', NULL, 'orderby=id&order=desc');
					$current_language = apply_filters( 'wpml_current_language', NULL );
				?>
						<button id="lang-dropdown"><?php echo $languages[$current_language]['translated_name']; ?><span><img src="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/dist/img/svg/arrow_down_bold_black.svg" alt="<?php _e('Select language'); ?>"></span></button>
						<div class="site-header__lang-other">
					<?php foreach($languages as $language_code => $language): ?>
						<?php if($language_code === $current_language) continue; ?>

									<a href="<?php echo esc_url( $language['url'] ); ?>"><?php echo $language['translated_name']; ?></a>
					<?php endforeach; ?>
						</div>
			</div>

			<?php
				wp_nav_menu( array(
					'theme_location' => 'header-menu',
					'menu_class'     => 'site-header__main-menu',
					'menu_id'        => 'js-main-menu',
				) );
			?>

		</nav>

	</header><!-- #masthead -->
	<main id="content" class="site-content">


