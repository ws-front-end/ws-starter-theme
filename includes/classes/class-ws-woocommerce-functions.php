<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * WS_Woocommerce_Functions class
 */
class WS_Woocommerce_Functions {
	/**
	 * WS_Woocommerce_Functions constructor.
	 */
	public function __construct() {
		$this->add_actions();
		$this->remove_actions();
		$this->add_filters();
		$this->remove_filters();
	}

	/**
	 * Add action hooks.
	 */
	private function add_actions() {
		add_action( 'after_setup_theme', [ 'WS_Woocommerce_Functions', 'ws_theme_add_woocommerce_support' ] );
		add_action( 'woocommerce_checkout_process', [ 'WS_Woocommerce_Functions', 'not_approved_privacy' ] );
		add_action(
			'woocommerce_checkout_terms_and_conditions',
			[
				'WS_Woocommerce_functions',
				'add_checkout_privacy_policy',
			],
			20
		);
	}

	/**
	 * Remove already added action hooks.
	 */
	private function remove_actions() {
		// Remove default privacy policy text from checkout to replace it with a checkbox.
		remove_action( 'woocommerce_checkout_terms_and_conditions', 'wc_checkout_privacy_policy_text', 20 );
	}

	/**
	 * Add filter hooks.
	 */
	private function add_filters() {
		add_filter( 'woocommerce_breadcrumb_defaults', [ 'WS_Woocommerce_Functions', 'change_breadcrumb_delimiter' ] );
		add_filter( 'woocommerce_enqueue_styles', '__return_empty_array' );
	}
	/**
	 * Remove filter hooks.
	 */
	private function remove_filters() {
		// remove_filter( 'woocommerce_checkout_order_review', 'woocommerce_order_review', 10 );
	}
	/**
	 * Add Woocommerce support to current theme.
	 */
	public static function ws_theme_add_woocommerce_support() {
		add_theme_support(
			'woocommerce',
			array(
				'thumbnail_image_width' => 150,
				'single_image_width'    => 300,
				'product_grid'          => array(
					'default_rows'    => 3,
					'min_rows'        => 2,
					'max_rows'        => 8,
					'default_columns' => 4,
					'min_columns'     => 2,
					'max_columns'     => 5,
				),
			)
		);
	}

	/**
	 * Adds a privacy policy agreement checkbox to checkout.
	 */
	public static function add_checkout_privacy_policy() {
		if ( function_exists( 'wc_privacy_policy_page_id' ) ) {
			$privacy_policy_url = get_permalink( wc_privacy_policy_page_id() );
			?><label for="privacy_policy">
				<input type="checkbox" name="privacy_policy" id="privacy_policy" value="yes"/>
				<p><?php echo sprintf( esc_html__( 'Olen tutvunud ning aktsepteerin %s privaatsuspoliitikat %s' ), '<a href="' . esc_url( $privacy_policy_url ) . '" target="_blank">', '</a>' ); ?></p>
			</label>
			<?php
		}
	}

	/**
	 * Validates the privacy policy agreement checkbox.
	 */
	public static function not_approved_privacy() {
		if ( ! filter_input( INPUT_POST, 'privacy_policy', FILTER_VALIDATE_BOOLEAN ) ) {
			if ( function_exists( 'wc_add_notice' ) ) {
				wc_add_notice( __( 'Please acknowledge the privacy policy' ), 'error' );
			}
		}
	}

	/**
	 * Change the breadcrumb separator.
	 *
	 * @param array $defaults Array of breadcrumbs options.
	 *
	 * @return mixed
	 */
	public static function change_breadcrumb_delimiter( $defaults ) {
		$defaults['home']      = get_the_title( get_option( 'page_on_front' ) );
		$defaults['delimiter'] = '<span>›</span>';

		return $defaults;
	}
}
