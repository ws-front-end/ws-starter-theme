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
			<a href="<?php echo get_privacy_policy_url(); ?>"><?php _e('Privaatsustingimused', THEME_TEXT_DOMAIN); ?></a>
		</div>

		<div class="site-footer__container__bottom__right">
			<a href="http://www.websystems.ee/<?php echo $my_current_lang; ?>kodulehe-tegemine" target="_blank" title="Web Systems kodulehe tegemine">
				<?php _e('kodulehe <br /> tegemine', 'WebSystems'); ?>
			</a>
			<a class="site-footer__ws-logo" href="http://www.websystems.ee/<?php echo $my_current_lang; ?>" target="_blank" title="Web Systems OÜ">
				<img src="<?php echo esc_url(get_template_directory_uri()); ?>/assets/dist/img/svg/ws-logo.svg" alt="Websystems Logo" />
			</a>
		</div>
	</div>
</footer><!-- #colophon -->

</div><!-- #page -->

<?php wp_footer(); ?>

</body>

</html>