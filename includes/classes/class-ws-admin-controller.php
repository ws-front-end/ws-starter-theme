<?php
if (!defined('ABSPATH')) {
	exit; // Exit if accessed directly.
}
/**
 * Example class
 */
class WS_Admin_Controller
{
	/**
	 * Example constructor.
	 */
	public function __construct()
	{
		$this->remove_actions();
		$this->remove_filters();
		$this->add_actions();
		$this->add_filters();
	}

	/**
	 * Add WordPress actions
	 */
	private function add_actions()
	{
		add_action('login_enqueue_scripts', [$this, 'ws_enqueue_admin_styles']);
		add_action('admin_enqueue_scripts', [$this, 'ws_enqueue_admin_styles']);

		add_action('login_header', [$this, 'include_custom_login_header_html']);
		add_action('login_footer', [$this, 'include_custom_login_footer_html']);
		add_action('login_head', [$this, 'ws_remove_loginshake']);


		add_action('admin_init', [$this, 'add_site_options_menu_item']);
		add_action('admin_init', [$this, 'hide_content_editor_from_options_page']);
		add_action('admin_menu', [$this, 'hide_page_attributes_from_options_page']);

		add_action('wp_before_admin_bar_render', [$this, 'add_admin_bar_button']);

		// add_action('wp_dashboard_setup', [$this, 'dashboard_ws_info']);
		// add_action('wp_dashboard_setup', [$this, 'dashboard_ws_rss']);
	}

	/**
	 * Remove WordPress existing filters
	 */
	private function remove_filters()
	{
	}

	/**
	 * Remove WordPress existing actions
	 */
	private function remove_actions()
	{
		remove_action('login_head', 'wp_shake_js', 12);
	}

	/**
	 * Add WordPress custom filters
	 */
	private function add_filters()
	{
		add_filter('login_headerurl', [$this, 'ws_override_login_logo_url']);
		add_filter('parse_query', [$this, 'hide_options_page']);
		add_filter('lostpassword_url', [$this, 'maybe_reset_login_url'], 10, 2);
	}

	/**
	 * LOGIN
	 *
	 * Enqueues gulp generated admin-style.css file
	 *
	 * @return void
	 */
	public function ws_enqueue_admin_styles()
	{
		$manifest      = json_decode(file_get_contents(get_template_directory() . '/package.json', true));
		$asset_version = $manifest->version;
		wp_enqueue_style('ws-custom-login-style', get_stylesheet_directory_uri() . '/admin-style.css', [], $asset_version);
	}

	public function ws_remove_loginshake()
	{
		remove_action('login_head', 'wp_shake_js', 12);
	}

	public function include_custom_login_header_html()
	{
		include get_template_directory() . '/template-parts/admin/admin-login-header.php';
	}

	public function include_custom_login_footer_html()
	{
		include get_template_directory() . '/template-parts/admin/admin-login-footer.php';
	}
	/**
	 * Override wp-login.php header logo URL.
	 *
	 * @param string $header_link
	 *
	 * @return string
	 */
	public function ws_override_login_logo_url($header_link)
	{
		return home_url();
	}

	/**
	 * OPTIONS PAGE
	 *
	 * Adds the options page link to the admin menu.
	 */
	public function add_site_options_menu_item()
	{
		$options_page_id = ThemeSetup::get_theme_options_page_id();
		if (!is_wp_error($options_page_id) && filter_var($options_page_id, FILTER_VALIDATE_INT)) {
			add_menu_page(
				'ws_site_options',
				esc_html__('Site options'),
				'read',
				'post.php?post=' . $options_page_id . '&action=edit',
				'',
				'dashicons-admin-site',
				12
			);
		}
	}

	/**
	 * Hides the content editor from the options page admin UI.
	 */
	public function hide_content_editor_from_options_page()
	{
		$post_id = filter_input(INPUT_GET, 'post', FILTER_VALIDATE_INT);
		if (!$post_id) {
			$post_id = filter_input(INPUT_POST, 'post', FILTER_VALIDATE_INT);
		}
		if (!$post_id) {
			return;
		}
		$options_page_id = ThemeSetup::get_theme_options_page_id();
		if (!is_wp_error($options_page_id) && filter_var($options_page_id, FILTER_VALIDATE_INT)) {
			if ($options_page_id === $post_id) {
				remove_post_type_support('page', 'editor');
				remove_post_type_support('page', 'thumbnail');
			}
		}
	}
	/**
	 * Hides the page attributes from the options page admin UI.
	 */
	public function hide_page_attributes_from_options_page()
	{
		$post_id = filter_input(INPUT_GET, 'post', FILTER_VALIDATE_INT);
		if (!$post_id) {
			$post_id = filter_input(INPUT_POST, 'post', FILTER_VALIDATE_INT);
		}
		if (!$post_id) {
			return;
		}
		$options_page_id = ThemeSetup::get_theme_options_page_id();
		if (!is_wp_error($options_page_id) && filter_var($options_page_id, FILTER_VALIDATE_INT)) {
			if ($options_page_id === $post_id) {
				remove_meta_box('pageparentdiv', 'page', 'normal');
			}
		}
	}
	/**
	 * Hides options page from everywhere.
	 *
	 * @param WP_Query $query WordPress query instance.
	 */
	public function hide_options_page($query)
	{
		global $pagenow, $post_type;
		if (is_admin() && 'edit.php' === $pagenow && 'page' === $post_type) {
			if (!filter_var($query->get('disable_for_options_page_id_query'), FILTER_VALIDATE_BOOLEAN)) {
				$options_page_id = ThemeSetup::get_theme_options_page_id();
				if (!is_wp_error($options_page_id) && filter_var($options_page_id, FILTER_VALIDATE_INT)) {
					if ('ws-general-options-page-dummy-template.php' !== $query->query_vars['meta_value']) {
						$post_not_in = $query->query_vars['post__not_in'];
						if (!is_array($post_not_in)) {
							$post_not_in = [];
						}
						$post_not_in[] = $options_page_id;

						$query->query_vars['post__not_in'] = $post_not_in;
					}
				}
			}
		}
	}

	/**
	 * ADMIN BAR
	 *
	 * Adds custom admin bar button.
	 */
	public static function add_admin_bar_button()
	{
		get_template_part('template-parts/adminbar-button');
	}

	/**
	 * ADMIN WIDGETS
	 *
	 *
	 */
	public function dashboard_ws_info()
	{
		global $wp_meta_boxes;

		wp_add_dashboard_widget('dashboard_ws_info', 'Web Systems Info', [$this, 'ws_dashboard_info_function']);
	}
	public function ws_dashboard_info_function()
	{
		get_template_part('template-parts/admin/info-widget');
	}
	public function dashboard_ws_rss()
	{
		global $wp_meta_boxes;

		wp_add_dashboard_widget('dashboard_ws_rss', __('Kasulikku lugemist', 'WebSystems'), [$this, 'ws_dashboard_rss_function']);
	}
	public function ws_dashboard_rss_function()
	{
		get_template_part('template-parts/admin/rss-widget');
	}
	/**
	 * Filters the Lost Password URL.
	 *
	 * @since 2.8.0
	 *
	 * @param string $lostpassword_url The lost password page URL.
	 * @param string $redirect         The path to redirect to on login.
	 */
	public function maybe_reset_login_url($lostpassword_url, $redirect)
	{

		if (in_array('woocommerce/woocommerce.php', apply_filters('active_plugins', get_option('active_plugins')), true) && $GLOBALS['pagenow'] === 'wp-login.php') {
			$args = array();
			if (!empty($redirect)) {
				$args['redirect_to'] = urlencode($redirect);
			}

			$lostpassword_url = add_query_arg($args, network_site_url('wp-login.php?action=lostpassword', 'login'));
		}

		return $lostpassword_url;
	}
}
