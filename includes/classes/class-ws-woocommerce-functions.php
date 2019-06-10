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
	}
	/**
	 * Add action hooks.
	 */
	private function add_actions() {
		add_action( 'after_setup_theme', [ $this, 'ws_theme_add_woocommerce_support' ] );
	}
	/**
	 * Add Woocommerce support to current theme.
	 */
	public function ws_theme_add_woocommerce_support() {
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
}