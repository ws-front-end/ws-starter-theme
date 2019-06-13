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
	}
	/**
	 * Add action hooks.
	 */
	private function add_actions() {
		add_action( 'after_setup_theme', [ $this, 'ws_theme_add_woocommerce_support' ] );
		
		
		add_action( 'woocommerce_checkout_process', [ 'WS_Woocommerce_Functions', 'not_approved_privacy' ] );
		add_action( 'woocommerce_checkout_terms_and_conditions', [ 'WS_Woocommerce_functions', 'add_checkout_privacy_policy' ], 20 );
	}
	/**
	 * Remove already added action hooks.
	 */
	private function remove_actions() {
		// Remove default privacy policy text from checkout to replace it with a checkbox.
		remove_action( 'woocommerce_checkout_terms_and_conditions', 'wc_checkout_privacy_policy_text', 20 );
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
	/**
	 * Adds a privacy policy agreement checkbox to checkout.
	 */
	public static function add_checkout_privacy_policy() {
	    
	    $privacy_policy_url = get_permalink(  wc_privacy_policy_page_id() );
	    
	    $link_start = '<a href="' . esc_url( $privacy_policy_url ) . '">';
	    $link_end   = '</a>';
		
		woocommerce_form_field( 'privacy_policy', array(
			'type'          => 'checkbox',
			'class'         => array('form-row privacy'),
			'label_class'   => array('woocommerce-form__label woocommerce-form__label-for-checkbox checkbox'),
			'input_class'   => array('woocommerce-form__input woocommerce-form__input-checkbox input-checkbox'),
			'required'      => true,
			'label'         => sprintf( __('I\'ve read and accept the %s privacy policy %s'), $link_start, $link_end ),
		));
		
	}
	
	/**
	 * Validates the privacy policy agreement checkbox.
	 */
	public static function not_approved_privacy() {
		if ( ! (int) isset( $_POST['privacy_policy'] ) ) {
			wc_add_notice( __( 'Please acknowledge the privacy policy' ), 'error' );
		}
	}
}