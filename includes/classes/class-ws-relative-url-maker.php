<?php
/**
 * WS_Relative_Url_Maker class
 */
class WS_Relative_Url_Maker {
	/**
	 * WS_Relative_Url_Maker constructor.
	 */
	public function __construct() {
		$this->ws_make_urls_relative();
	}
	/**
	 * Converts various URLs into relative URLs.
	 */
	private function ws_make_urls_relative() {
		if (
				is_admin() ||
				isset( $_GET['sitemap'] ) ||
				in_array(
					$GLOBALS['pagenow'],
					[
						'wp-login.php',
						'wp-register.php',
					],
					true
				)
			) {
				return;
		}
			$root_rel_filters = [
				'bloginfo_url',
				'the_permalink',
				'wp_list_pages',
				'wp_list_categories',
				'wp_get_attachment_url',
				'the_content_more_link',
				'the_tags',
				'get_pagenum_link',
				'get_comment_link',
				'month_link',
				'day_link',
				'year_link',
				'term_link',
				'the_author_posts_link',
				'script_loader_src',
				'style_loader_src',
				'theme_file_uri',
				'parent_theme_file_uri',
			];
			$this->ws_add_filters( $root_rel_filters, [ $this, 'ws_root_relative_url' ] );

			add_filter(
				'wp_calculate_image_srcset',
				function ( $sources ) {
					foreach ( (array) $sources as $source => $src ) {
						$sources[ $source ]['url'] = $this->ws_root_relative_url( $src['url'] );
					}
					return $sources;
				}
			);
		/**
		 * Compatibility with The SEO Framework
		 */
		add_action(
			'the_seo_framework_do_before_output',
			function () {
				remove_filter( 'wp_get_attachment_url', [ $this, 'ws_root_relative_url' ] );
			}
		);
		add_action(
			'the_seo_framework_do_after_output',
			function () {
				add_filter( 'wp_get_attachment_url', [ $this, 'ws_root_relative_url' ] );
			}
		);
	}
	/** Adds all the filters in one loop.
	 *
	 * @param array $tags Array of filter tags.
	 * @param mixed $function Callback to run.
	 * @param int   $priority Priority of callback for certain action.
	 * @param int   $accepted_args Number of arguments supplied by the filter call.
	 */
	public function ws_add_filters( $tags, $function, $priority = 10, $accepted_args = 1 ) {
		foreach ( (array) $tags as $tag ) {
			add_filter( $tag, $function, $priority, $accepted_args );
		}
	}
	/** Removes the scheme and domain from URL and returns it as a faux relative.
	 *
	 * @param string $input An URL to convert.
	 *
	 * @return string
	 */
	public function ws_root_relative_url( $input ) {
		if ( is_feed() ) {
			return $input;
		}
		$url = wp_parse_url( $input );
		if ( ! isset( $url['host'] ) || ! isset( $url['path'] ) ) {
			return $input;
		}
		$site_url = wp_parse_url( network_home_url() );  // falls back to home_url.
		if ( ! isset( $url['scheme'] ) ) {
			$url['scheme'] = $site_url['scheme'];
		}
		$hosts_match   = $site_url['host'] === $url['host'];
		$schemes_match = $site_url['scheme'] === $url['scheme'];
		$ports_exist   = isset( $site_url['port'] ) && isset( $url['port'] );
		$ports_match   = ( $ports_exist ) ? $site_url['port'] === $url['port'] : true;
		if ( $hosts_match && $schemes_match && $ports_match ) {
			return wp_make_link_relative( $input );
		}

		return $input;
	}
}
