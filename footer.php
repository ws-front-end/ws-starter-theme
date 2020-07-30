<?php

/**
 * The template for displaying the footer
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 */
$my_current_lang = '';
if (class_exists('SitePress')) {
	$my_current_lang = apply_filters('wpml_current_language', NULL) . '/';
}
?>
</main>

</div><!-- #page -->

<footer id="colophon" class="site-footer">
    <div class="site-footer__container__bottom">
        <div class="site-footer__container__bottom__left">
            <p>© <?php echo ThemeSetup::get_theme_option('company_name'); ?> <?php echo date('Y'); ?></p>

            <?php if (ThemeSetup::get_theme_option('footer_fields')) : ?>

            <?php foreach (ThemeSetup::get_theme_option('footer_fields') as $key => $section) : ?>
            <?php if (!$section['is_displayed']) : ?>

            <span>|</span>
            <?php
		switch ($section['link_type']) {
			case 'link':
				echo "<a href='" . $section["href"]["url"] . "' target='" . $section["href"]["target"] . "'>" . $section["href"]["title"] . "</a>";
				break;
			case 'tel':
				echo "<a href='tel:+" . preg_replace('/\D/', '', $section['href']) . "'>" . $section['href'] . "</a>";
				break;
			case 'mailto':
				echo "<a href='mailto:" . $section['href'] . "'>" . $section['href'] . "</a>";
				break;

			default:
				echo "<p>" . $section['href'] . "</p>";
				break;
		}
		?>

            <?php endif; ?>
            <?php endforeach; ?>

            <?php endif; ?>

            <span>|</span>
            <a
                href="<?php echo get_privacy_policy_url(); ?>"><?php _e('Privaatsustingimused', THEME_TEXT_DOMAIN); ?></a>
        </div>

        <div class="ws-logo">
            <a href="https://www.websystems.ee/<?php echo $my_current_lang !== 'et/' ? $my_current_lang : ''; ?>kodulehe-tegemine" target="_blank"
                title="Web Systems kodulehe tegemine">
                <?php _e('kodulehe <br /> tegemine', 'WebSystems'); ?>
            </a>
            <a class="ws-logo__image" href="https://www.websystems.ee/<?php echo $my_current_lang !== 'et/' ? $my_current_lang : ''; ?>" target="_blank"
                title="Web Systems OÜ">
                <img src="https://websystems.ee/ws-logos/ws-small-black-logo.svg" alt="Websystems Logo" />
            </a>
        </div>
    </div>
</footer><!-- #colophon -->

<?php get_template_part( 'template-parts/generic/ie-alert' );?>

<div class="page--shadow" id="js-page-shadow"></div>

<?php wp_footer(); ?>

</body>

</html>
