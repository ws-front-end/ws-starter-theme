<?php

/**
 * admin-login-header.php file.
 *
 * @package ws
 */

?>

<div class="site">

    <style>
    <?php if (ThemeSetup::get_theme_option('site_logo')) : ?>body.login div#login h1 a {
        background-image: url('<?php echo ThemeSetup::get_theme_option('site_logo'); ?>') !important;
    }

    <?php endif;

    ?><?php if (ThemeSetup::get_theme_option('login_background') && !wp_is_mobile()) : ?>.site {
        background-image: url('<?php echo ThemeSetup::get_theme_option('login_background'); ?>');
    }

    <?php elseif (rand(0, 99) > 25 && !wp_is_mobile()) : ?>.site {
        background-image: url('https://source.unsplash.com/featured/1920x1080/?<?php echo ThemeSetup::get_theme_option('login_background_filters'); ?>');
    }

    <?php endif;
    ?>

    </style>
