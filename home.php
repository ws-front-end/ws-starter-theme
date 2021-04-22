<?php

get_header();

$latest_articles = get_posts(['suppress_filters' => 0, 'posts_per_page' => -1]);

?>
<section class="content-page">

	<div class="content-page__content max--width--smaller">
		<h1 class="title title--center"><?php _e('Uudised', 'ws_theme'); ?></h1>
		<div class="news__grid">
			<?php foreach ($latest_articles as $article) : ?>
				<a href="<?php the_permalink($article); ?>" class="news-box">
					<div class="news-box__image" style="background-image:url('<?php echo esc_url(get_the_post_thumbnail_url($article, 'large')); ?>');"></div>
					<div class="news-box__info">
						<!--<p><?php echo get_the_date('d.m.Y'); ?></p>-->
						<h3><?php echo esc_html($article->post_title); ?></h3>
					</div>
				</a>
			<?php endforeach; ?>
		</div>
	</div>
</section>

<?php
get_footer();
