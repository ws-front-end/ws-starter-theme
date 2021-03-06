<?php

/**
 * The header for our theme
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 */

?>
<!doctype html>
<html <?php language_attributes(); ?>>

<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#ffffff" />
    <link rel="profile" href="http://gmpg.org/xfn/11">
    <?php wp_head(); ?>
</head>

<body <?php body_class(); ?> id="js-page-body">
    <div id="page" class="site">

        <header id="js-masthead" class="site-header" navigation-container>

            <div class="site-logo">
                <a href="<?php echo esc_url(home_url('/')); ?>" rel="home" title="<?php bloginfo('name'); ?>">
                    <?php if (ThemeSetup::get_theme_option('site_logo')) : ?>
                    <img src="<?php echo ThemeSetup::get_theme_option('site_logo'); ?>" alt="Company logo">
                    <?php else:?>
                    <img src="<?php bloginfo('template_url'); ?>/assets/dist/img/svg/site-logo.svg" alt="Company logo">
                    <?php endif;?>
                </a>
            </div>

            <button class="site-menu-toggle menu-toggle hamburger hamburger--squeeze touch--only"
                id="js-main-menu-toggle" aria-controls="js-main-menu" aria-expanded="false" type="button"
                aria-label="Hamburger Button" burger-button>
                <span class="hamburger-box">
                    <span class="hamburger-inner"></span>
                </span>
            </button>

            <nav class="site-nav" id="js-main-menu-container" navigation-list>
                <?php
                if(has_nav_menu( 'header-menu' )) {
                    wp_nav_menu(array(
                        'theme_location' => 'header-menu',
                        'menu_class'     => 'site-header__main-menu',
                        'menu_id'        => 'js-main-menu',
                    ));
                }
                ?>

                <?php if (class_exists('SitePress')) : ?>
                <div class="lang-container" lang-container>
                    <?php
                        $languages = apply_filters('wpml_active_languages', NULL, 'orderby=id&order=desc');
                        $current_language = apply_filters('wpml_current_language', NULL);
                        if (count($languages) > 0) :
                        ?>

                    <button class="lang-container__current <?php echo count($languages) > 1 ? 'has--children' : ''; ?>"
                        lang-button>
                        <?php echo $languages[$current_language]['translated_name']; ?><span>&#10095;</span>
                    </button>

                    <?php if (count($languages) > 1) : ?>
                    <div class="lang-container__lang-other" lang-others>
                        <?php foreach ($languages as $language_code => $language) : ?>
                        <?php if ($language_code === $current_language) continue; ?>
                        <a
                            href="<?php echo esc_url($language['url']); ?>"><?php echo $language['translated_name']; ?></a>
                        <?php endforeach; ?>
                    </div>
                    <?php endif; ?>
                    <?php endif; ?>
                </div>
                <?php endif; ?>
            </nav>

        </header><!-- #masthead -->

        <main id="content" class="site-content">
