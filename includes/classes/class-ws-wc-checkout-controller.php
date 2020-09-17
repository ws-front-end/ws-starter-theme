<?php
/**
 * WooCommerce Checkout controller class.
 *
 * @package ws-checkout
 */

/**
 * Class WS_WC_Checkout_Controller
 */
class WS_WC_Checkout_Controller {
	/**
	 * WS_WC_Checkout_Controller constructor.
	 */
	public function __construct() {
		$this->init_actions();
		$this->init_filters();
	}

	/**
	 * Register WordPress action hooks.
	 */
	private function init_actions() {
		add_action( 'admin_init', [ $this, 'override_makecommerce_display_type' ], 99 );
		add_action( 'wp_enqueue_scripts', [ $this, 'include_js_data' ], 10 );

		add_action( 'wp_ajax_ws_apply_coupon', [ $this, 'apply_coupon' ] );
		add_action( 'wp_ajax_nopriv_ws_apply_coupon', [ $this, 'apply_coupon' ] );
		add_action( 'wp_ajax_ws_remove_coupon', [ $this, 'remove_coupon' ] );
		add_action( 'wp_ajax_nopriv_ws_remove_coupon', [ $this, 'remove_coupon' ] );

		add_action( 'wp_ajax_ws_remove_cart_item', [ $this, 'remove_cart_item' ] );
		add_action( 'wp_ajax_nopriv_ws_remove_cart_item', [ $this, 'remove_cart_item' ] );

		add_action( 'wp_ajax_ws_update_cart_quantities', [ $this, 'update_cart_quantities' ] );
		add_action( 'wp_ajax_nopriv_ws_update_cart_quantities', [ $this, 'update_cart_quantities' ] );

		add_action( 'woocommerce_after_checkout_validation', [ $this, 'override_checkout_fields_validation' ], 99, 2 );
		add_action( "template_redirect", [ $this, 'redirect_empty_cart' ] );

	}

	/**
	 * Initialize various WordPress filters.
	 */
	private function init_filters() {
		add_filter( 'woocommerce_enqueue_styles', '__return_empty_array' );
		add_filter( 'woocommerce_update_order_review_fragments', [ $this, 'include_custom_fragments' ], 10, 1 );
		add_filter( 'woocommerce_checkout_fields', [ $this, 'override_checkout_fields' ] );

		add_filter( 'woocommerce_get_cart_page_permalink', [ $this, 'override_cart_url_with_checkout' ] );

		add_filter( 'woocommerce_get_sections_shipping', [ $this, 'add_shipping_logos_section' ] );
		add_filter( 'woocommerce_get_settings_shipping', [ $this, 'add_shipping_logos_settings' ], 10, 2 );
	}

	public function redirect_empty_cart() {
		if ( ! is_order_received_page() ) {
			if ( is_checkout() && WC()->cart->is_empty() ) {
				$redirect_result = wp_redirect( home_url() );
				exit();
			}
		}
	}

	/**
	 * Forces the MakeCommerce payment methods display type to 'inline'.
	 */
	public function override_makecommerce_display_type() {
		$makecommerce_settings = get_option( 'woocommerce_makecommerce_settings', [] );
		if ( ! empty( $makecommerce_settings ) ) {
			if ( isset( $makecommerce_settings['ui_mode'] ) && 'inline' !== $makecommerce_settings['ui_mode'] ) {
				$makecommerce_settings['ui_mode'] = 'inline';

				update_option( 'woocommerce_makecommerce_settings', $makecommerce_settings );
			}
		}
	}

	/**
	 * Overrides cart URL with the checkout URL.
	 *
	 * @return string
	 */
	public function override_cart_url_with_checkout() {
		return wc_get_checkout_url();
	}

	/**
	 * Adds shipping logos section to WooCommerce shipping settings.
	 *
	 * @param array $sections
	 *
	 * @return array
	 */
	public function add_shipping_logos_section( $sections ) {

		$sections = array_slice( $sections, 0, 3, true ) +
		            [ 'ws_shipping_logos' => __( 'Shipping logos' ) ] +
		            array_slice( $sections, 3, count( $sections ) - 1, true );

		return $sections;
	}

	/**
	 * Adds shipping logo fields to shipping logos section in WooCommerce shipping settings.
	 *
	 * @param array  $settings
	 * @param string $current_section
	 *
	 * @return array
	 */
	public function add_shipping_logos_settings( $settings, $current_section ) {
		if ( $current_section == 'ws_shipping_logos' ) {
			$shipping_logos_settings = [];

			$shipping_logos_settings[] = array(
				'name' => __( 'Shipping logos' ),
				'type' => 'title',
				'desc' => __( 'Logos to show for each shipping method.' ),
				'id'   => 'ws_shipping_logos'
			);

			$shipping_methods = WC()->shipping()->get_shipping_methods();
			foreach ( $shipping_methods as $shipping_method ) :
				$shipping_logos_settings[ 'ws_shipping_logo_' . $shipping_method->id ] = [
					'id'          => 'ws_shipping_logo_' . $shipping_method->id,
					'title'       => esc_html_x( 'Shipping logo for ', 'Shipping logo field label in admin', 'ws-checkout' ) . $shipping_method->get_method_title(),
					'type'        => 'text',
					'description' => esc_html_x( 'This controls the logo that is shown to the users in the custom checkout transportation select area. It should be the ID of the logo that should be uploaded through the Media page.', 'Shipping logo field description in admin', 'ws-checkout' ),
					'default'     => '',
					'placeholder' => esc_html_x( 'Logo image ID', 'Placeholder for Shipping logo field in admin', 'ws-checkout' ),
					'desc_tip'    => true,
				];
			endforeach;

			$shipping_logos_settings[] = array( 'type' => 'sectionend', 'id' => 'ws_shipping_logos' );

			return $shipping_logos_settings;
		} else {
			return $settings;
		}
	}

	/**
	 * Fetches and renders shipping method logo img HTML.
	 *
	 * @param ‌WC_Shipping_Rate $method WooCommerce shipping rate instance for current shipping method.
	 *
	 * @return string
	 */
	public static function get_shipping_logo( $method ) {
		$shipping_logo_id = get_option( 'ws_shipping_logo_' . $method->get_method_id(), null );

		if ( is_null( $shipping_logo_id ) ) {
			$placeholder_image = get_option( 'woocommerce_placeholder_image', 0 );

			if ( wp_attachment_is_image( $placeholder_image ) ) {
				$shipping_logo_id = $placeholder_image;
			} else {
				$shipping_logo_id = null;
			}
		}

		if ( is_null( $shipping_logo_id ) ) {
			return '';
		}

		return wp_get_attachment_image( $shipping_logo_id, 'medium', false, [ 'class' => '' ] );
	}

	/**
	 * Gets the label for the privacy policy checkbox in checkout.
	 */
	public static function get_privacy_policy_label() {
		$privacy_policy_url = get_privacy_policy_url();
		$before_policy_link = sprintf( '<a href="%s" target="_blank">', $privacy_policy_url );
		$after_policy_link  = '</a>';

		$terms_url = get_permalink( wc_terms_and_conditions_page_id() );
		$before_terms_link  = sprintf( '<a href="%s" target="_blank">', $terms_url );
		$after_terms_link   = '</a>';

		/* translators: 1 start of link element, 2 end of link element. */

		return sprintf( __( 'I have read and agree to the websites %1$s privacy policy %2$s and %3$s terms %4$s.', 'ws-checkout' ), $before_policy_link, $after_policy_link, $before_terms_link, $after_terms_link );
	}

	/**
	 * Includes the required data for JS.
	 */
	public function include_js_data() {
		if ( ! is_checkout() ) {
			return;
		}

		$data = [
			'products'     => [],
			'choices_args' => [
				'searchFields'      => 'label',
				'shouldSort'        => false,
				'renderChoiceLimit' => - 1,
				'itemSelectText'    => esc_html_x( 'Press to select', 'Checkout dropdown item label.', 'ws-checkout' ),
				'noResultsText'     => esc_html_x( 'No results found', 'Checkout dropdown message when no options found.', 'ws-checkout' ),
				'noChoicesText'     => esc_html_x( 'No choices to choose from', 'Checkout dropdown when no options exist.', 'ws-checkout' ),
			],
		];

		foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) :
			/*  @var WC_Product|WC_Product_Variable $product WooCommerce product instance. */
			$product = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );

			if ( $product->is_type( 'variation' ) ) {
				$product_id     = $product->get_parent_id();
				$product        = wc_get_product( $product_id );
				$get_variations = count( $product->get_children() ) <= apply_filters( 'woocommerce_ajax_variation_threshold', 30, $product );

				$data['products'][ $product_id ]['available_variations'] = $get_variations ? $product->get_available_variations() : false;
				$data['products'][ $product_id ]['attributes']           = $product->get_variation_attributes();
				$data['products'][ $product_id ]['selected_attributes']  = $product->get_default_attributes();
			}
		endforeach;

		wp_localize_script( 'ws-custom-js', 'WS_WC_CHECKOUT_DATA', $data );
	}

	/**
	 * Includes custom HTML fragments to checkout update response.
	 *
	 * @param array $fragments Array of HTML fragments to update in the frontend checkout view.
	 *
	 * @return array
	 */
	public function include_custom_fragments( $fragments ) {
		$fragments['#js-checkout-cart-details']    = $this->get_checkout_cart_details_fragment();
		$fragments['#js-checkout-cart-transport']  = $this->get_checkout_shipping_fragment();
		$fragments['#js-mobile-cart-total']        = $this->get_checkout_cart_total_fragment();
		$fragments['#js-checkout-payment-methods'] = $this->get_checkout_payment_methods_fragment();

		return $fragments;
	}

	/**
	 * Update cart item quantities in batch.
	 */
	public function update_cart_quantities() {
		$quantities = filter_input( INPUT_POST, 'quantities', FILTER_VALIDATE_INT, FILTER_REQUIRE_ARRAY );

		$cart_item_fragments = [];
		if ( $quantities ) {
			foreach ( $quantities as $cart_item_key => $quantity ) {
				WC()->cart->set_quantity( $cart_item_key, $quantity, true );

				$cart_item  = WC()->cart->get_cart_item( $cart_item_key );
				$product    = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );
				$product_id = apply_filters( 'woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key );

				$cart_item_fragments[ '#product-totals-' . $cart_item_key ] = $this->get_checkout_cart_single_item_totals_fragment( $cart_item_key, $cart_item, $product );
				$cart_item_fragments[ '#product-bottom-' . $cart_item_key ] = $this->get_checkout_cart_single_item_bottom_fragment( $cart_item_key, $cart_item, $product, $product_id );
			}
		}

		wp_send_json(
			[
				'success'   => true,
				'fragments' => [
					'#js-checkout-cart-transport'  => $this->get_checkout_shipping_fragment(),
					'#js-checkout-cart-details'    => $this->get_checkout_cart_details_fragment(),
					'#js-checkout-payment-methods' => $this->get_checkout_payment_methods_fragment(),
					'cart_item_fragments'          => $cart_item_fragments,
				],
			]
		);
	}

	/**
	 * Generates single product totals HTML fragment.
	 *
	 * @param string     $cart_item_key
	 * @param array      $cart_item
	 * @param WC_Product $product
	 *
	 * @return false|string
	 */
	public function get_checkout_cart_single_item_totals_fragment( $cart_item_key, $cart_item, $product ) {
		ob_start();
		wc_get_template(
			'woocommerce/checkout/checkout-product-single-total.php',
			[
				'product'       => $product,
				'cart_item'     => $cart_item,
				'cart_item_key' => $cart_item_key,
			]
		);

		return ob_get_clean();
	}

	/**
	 * Generates single product totals HTML fragment.
	 *
	 * @param string     $cart_item_key
	 * @param array      $cart_item
	 * @param WC_Product $product
	 * @param int        $product_id
	 *
	 * @return false|string
	 */
	public function get_checkout_cart_single_item_bottom_fragment( $cart_item_key, $cart_item, $product, $product_id ) {
		ob_start();
		wc_get_template(
			'woocommerce/checkout/checkout-product-single-bottom.php',
			[
				'product'       => $product,
				'product_id'    => $product_id,
				'cart_item'     => $cart_item,
				'cart_item_key' => $cart_item_key,
			]
		);

		return ob_get_clean();
	}

	/**
	 * Parses attribute selected value for cart item.
	 *
	 * @param string $attribute_name Name of the selected variation attribute.
	 * @param array  $cart_item      Cart item array.
	 *
	 * @return null
	 */
	public static function get_selected_variation_attribute( $attribute_name, $cart_item ) {
		if ( isset( $cart_item['variation'][ 'attribute_' . sanitize_title( $attribute_name ) ] ) ) {
			return $cart_item['variation'][ 'attribute_' . sanitize_title( $attribute_name ) ];
		}

		return null;
	}

	/**
	 * Parses attribute selected value for cart item and fetches the label of said attribute value.
	 *
	 * @param string $attribute_name Name of the selected variation attribute.
	 * @param array  $cart_item      Cart item array.
	 *
	 * @return null
	 */
	public static function get_selected_variation_attribute_label( $attribute_name, $cart_item ) {
		if ( isset( $cart_item['variation'][ 'attribute_' . sanitize_title( $attribute_name ) ] ) ) {
			$attribute_slug = $cart_item['variation'][ 'attribute_' . sanitize_title( $attribute_name ) ];
			$attribute_term = get_term_by( 'slug', $attribute_slug, sanitize_title( $attribute_name ) );

			return $attribute_term->name;
		}

		return null;
	}

	/**
	 * Fetches cart item total regular price.
	 *
	 * @param WC_Product $product  WooCommerce product instance.
	 * @param int        $quantity Quantity of product in cart.
	 *
	 * @return string
	 */
	public static function get_item_total_regular_price( $product, $quantity ) {
		return ( $product->get_regular_price() * $quantity ) . ' ' . get_woocommerce_currency_symbol();
	}

	/**
	 * Get cart total excluding taxes.
	 *
	 * @return string
	 */
	public static function get_cart_total_without_tax() {
		$cart_total = filter_var( WC()->cart->get_total( 'calc' ), FILTER_VALIDATE_FLOAT );
		$cart_taxes = WC()->cart->get_taxes_total( true, false );

		return wc_price( $cart_total - $cart_taxes );
	}

	/**
	 * Remove cart item from cart.
	 */
	public function remove_cart_item() {
		// phpcs:ignore WordPress.Security.NonceVerification.NoNonceVerification
		$cart_item_key = wc_clean( isset( $_POST['cart_item_key'] ) ? wp_unslash( $_POST['cart_item_key'] ) : '' );

		if ( $cart_item_key && false !== WC()->cart->remove_cart_item( $cart_item_key ) ) {
			wp_send_json(
				[
					'success'       => true,
					'is_cart_empty' => WC()->cart->is_empty(),
					'fragments'     => [
						'#js-checkout-cart-transport'  => $this->get_checkout_shipping_fragment(),
						'#js-checkout-cart-details'    => $this->get_checkout_cart_details_fragment(),
						'#js-checkout-payment-methods' => $this->get_checkout_payment_methods_fragment(),
						'#js-mobile-cart-total'        => $this->get_checkout_cart_total_fragment(),
					],
				]
			);
		} else {
			wp_send_json(
				[
					'success' => false,
				]
			);
		}
		wp_die();
	}

	/**
	 * Generates the checkout payment methods fragment HTML.
	 *
	 * @return false|string
	 */
	public function get_checkout_payment_methods_fragment() {
		WC()->cart->calculate_totals();
		ob_start();
		wc_get_template( 'woocommerce/checkout/checkout-billing-payment-methods.php' );
		$methods_fragment = ob_get_contents();
		ob_end_clean();

		return $methods_fragment;
	}

	/**
	 * Generates the checkout cart details fragment HTML.
	 *
	 * @return false|string
	 */
	public function get_checkout_cart_details_fragment() {
		WC()->cart->calculate_totals();
		ob_start();
		wc_get_template( 'woocommerce/checkout/checkout-cart.php' );
		$cart_fragment = ob_get_contents();
		ob_end_clean();

		return $cart_fragment;
	}

	/**
	 * Genereates checkout shipping section fragment HTML.
	 *
	 * @return false|string
	 */
	public function get_checkout_shipping_fragment() {
		WC()->cart->calculate_totals();
		WC()->cart->calculate_shipping();
		wc()->cart->calculate_fees();

		ob_start();
		wc_get_template( 'woocommerce/checkout/checkout-transport.php' );
		$shipping_fragment = ob_get_contents();
		ob_end_clean();

		return $shipping_fragment;
	}

	/**
	 * Generates checkout cart mobile total fragment HTML.
	 *
	 * @return false|string
	 */
	public function get_checkout_cart_total_fragment() {
		ob_start();
		?>
		<p id="js-mobile-cart-total"><?php echo wp_kses( WC()->cart->get_total(), [] ); ?></p>
		<?php

		return ob_get_clean();
	}

	/**
	 * Apply the submitted coupon to the cart.
	 */
	public function apply_coupon() {
		$coupon_code = filter_input( INPUT_POST, 'coupon_code', FILTER_SANITIZE_STRING );

		if ( $coupon_code ) {
			$coupon_apply_result = WC()->cart->apply_coupon( $coupon_code );
			wc_clear_notices();

			if ( $coupon_apply_result ) {

				wp_send_json(
					[
						'success'   => true,
						'fragments' => [
							'#js-checkout-cart-transport'  => $this->get_checkout_shipping_fragment(),
							'#js-checkout-cart-details'    => $this->get_checkout_cart_details_fragment(),
							'#js-mobile-cart-total'        => $this->get_checkout_cart_total_fragment(),
							'#js-checkout-payment-methods' => $this->get_checkout_payment_methods_fragment(),
						],
					]
				);
				wp_die();
			}
		}

		wp_send_json(
			[
				'success' => false,
			]
		);
		wp_die();
	}

	/**
	 * Generates the coupon discount amount.
	 *
	 * @param string $applied_coupon_code The coupon being applied to the cart.
	 *
	 * @return string
	 */
	public static function get_coupon_amount( $applied_coupon_code ) {
		$coupon_post_obj = get_page_by_title( $applied_coupon_code, OBJECT, 'shop_coupon' );
		$coupon_id       = $coupon_post_obj->ID;

		$coupon = new WC_Coupon( $coupon_id );

		$coupon_amount = '';
		if ( $coupon->is_type( 'fixed_cart' ) ) {
			$coupon_amount = '-' . $coupon->get_amount() . ' ' . get_woocommerce_currency_symbol();
		}

		if ( $coupon->is_type( 'percent' ) ) {
			$coupon_amount = '-' . $coupon->get_amount() . '%';
		}

		return $coupon_amount;
	}

	/**
	 * Remove the submitted coupon from the cart.
	 */
	public function remove_coupon() {
		$coupon_code = filter_input( INPUT_POST, 'coupon_code', FILTER_SANITIZE_STRING );

		if ( $coupon_code ) {
			$coupon_apply_result = WC()->cart->remove_coupon( $coupon_code );
			wc_clear_notices();
			if ( $coupon_apply_result ) {
				wp_send_json(
					[
						'success'   => true,
						'fragments' => [
							'#js-checkout-cart-transport'  => $this->get_checkout_shipping_fragment(),
							'#js-checkout-cart-details'    => $this->get_checkout_cart_details_fragment(),
							'#js-checkout-payment-methods' => $this->get_checkout_payment_methods_fragment(),
							'#js-mobile-cart-total'        => $this->get_checkout_cart_total_fragment(),
						],
					]
				);
				wp_die();
			}
		}

		wp_send_json(
			[
				'success' => false,
			]
		);
		wp_die();
	}

	/**
	 * Fetches HTML for shipping method options. The goal is to have less distracting HTML on the page and to make MakeCommerce plugin shipping methods work with custom checkout page.
	 *
	 * @param string $chosen_method_id Chosen shipping method by the user.
	 * @param int    $index            Packages array index for current package.
	 *
	 * @return array
	 */

	public static function get_shipping_method_options( $chosen_method_id, $index ) {
		$rendered_shipping_methods = [];
		$shipping_fields_rendered  = false;
		ob_start();
		$shipping_methods = WC()->shipping()->get_shipping_methods();
		foreach ( $shipping_methods as $method ) :
			if ( $method->id !== $chosen_method_id ) :
				continue;
			endif;

			if ( in_array( $method->id, $rendered_shipping_methods ) ) {
				continue;
			}

			if ( method_exists( $method, 'add_parcelmachine_checkout_fields' ) ) :
				$rendered_shipping_methods[] = $method->id;
				$method->add_parcelmachine_checkout_fields( '' );
				$shipping_fields_rendered = true;
				continue;
			endif;

			do_action( 'woocommerce_after_shipping_rate', $method, $index );
		endforeach;

		if ( ! $shipping_fields_rendered ) {
			do_action( 'woocommerce_review_order_after_shipping' );
		}

		$output_html = ob_get_clean();

		if ( $shipping_fields_rendered ) {
			$output_html = wp_kses(
				$output_html,
				[
					'select' => [
						'class' => [],
						'name'  => [],
					],
					'option' => [
						'value'    => [],
						'selected' => [],
					],
				]
			);
			$output_html = '<label for="' . $chosen_method_id . '">' . esc_html__( 'Select parcel machine', 'ws-checkout' ) . '</label>' . $output_html;
		}

		return [
			'is_mk' => $shipping_fields_rendered,
			'html'  => $output_html,
		];
	}

	/**
	 * Overrides checkout fields.
	 *
	 * @param array $fields Array of registered billing and shipping fields.
	 *
	 * @return array
	 */
	public function override_checkout_fields( $fields ) {
		$is_checkout_processing = false;
		$checkout_action        = filter_input( INPUT_GET, 'wc-ajax' );
		$is_checkout_payment    = filter_input( INPUT_POST, 'woocommerce_pay', FILTER_VALIDATE_BOOLEAN );
		if ( 'checkout' === $checkout_action && $is_checkout_payment ) {
			$is_checkout_processing = true;
		}

		$is_business_client = filter_input( INPUT_POST, 'ws_is_business_client', FILTER_VALIDATE_BOOLEAN );

		/*
		 * BILLING FIELDS EDIT.
		 */
		$billing_fields = $fields['billing'];
		unset( $billing_fields['billing_company'] );
		unset( $billing_fields['billing_address_2'] );

		$billing_fields['billing_first_name']['requirements'] = esc_html__( 'First name can not contain numbers.', 'ws-checkout' );
		$billing_fields['billing_last_name']['requirements']  = esc_html__( 'Last name can not contain numbers.', 'ws-checkout' );
		$billing_fields['billing_phone']['requirements']      = esc_html__( 'Phone number must contain numbers.', 'ws-checkout' );
		$billing_fields['billing_email']['requirements']      = esc_html__( 'E-mail is incorrect.', 'ws-checkout' );
		$billing_fields['billing_postcode']['requirements']   = esc_html__( 'Postcode should only contain numbers.', 'ws-checkout' );

		$billing_fields['billing_phone']['required'] = true;

		$billing_fields['billing_first_name']['custom_attributes'] = [
			'pattern' => '[A-Za-zÀ-ÖØ-öø-ÿšŠžŽ -]+',
		];
		$billing_fields['billing_last_name']['custom_attributes']  = [
			'pattern' => '[A-Za-zÀ-ÖØ-öø-ÿšŠžŽ -]+',
		];
		$billing_fields['billing_phone']['custom_attributes']      = [
			'pattern' => '^[0-9-+\s()]+$',
		];
		$billing_fields['billing_company']['custom_attributes']    = [
			'pattern' => '[A-Za-zÀ-ÖØ-öø-ÿšŠžŽ -]+',
		];
		$billing_fields['billing_city']['custom_attributes']       = [
			'pattern' => '[A-Za-zÀ-ÖØ-öø-ÿšŠžŽ -]+',
		];
		$billing_fields['billing_postcode']['custom_attributes']   = [
			'pattern' => '[0-9]+',
		];

		$fields_to_hide_for_parcel_machines = $this->fields_to_hide_for_parcel_machines( 'billing' );
		foreach ( $fields_to_hide_for_parcel_machines as $field_to_hide_key ) {
			if ( isset( $billing_fields[ $field_to_hide_key ] ) ) {
				$billing_fields[ $field_to_hide_key ]['class'][] = 'js-hide-for-parcel-machines';
			}
		}

		$billing_fields['billing_company'] = [
			'label'             => __( 'Company name', 'woocommerce' ),
			'class'             => [
				'js-show-for-business-only',
			],
			'required'          => false,
			'custom_attributes' => [
				'pattern' => '[A-Za-zÀ-ÖØ-öø-ÿšŠžŽ -]+',
			],
		];

		$billing_fields['billing_company_code'] = [
			'label'             => __( 'Company code', 'ws-checkout' ),
			'class'             => [
				'js-show-for-business-only js-non-required-business-field',
			],
			'placeholder'       => '',
			'required'          => false
		];


		if ( $is_checkout_processing ) {
			if ( ! $is_business_client ) {
				$billing_fields['billing_company']['required']          = false;
				$billing_fields['billing_company_code']['required']     = false;
			} else {
				$billing_fields['billing_company']['required']          = true;
				$billing_fields['billing_company_code']['required']     = false;
			}
		}

		$fields['billing'] = $billing_fields;

		/*
		 * SHIPPING FIELDS EDIT.
		 */
		$shipping_fields = $fields['shipping'];
		unset( $shipping_fields['shipping_company'] );
		unset( $shipping_fields['shipping_address_2'] );


		$shipping_fields['shipping_first_name']['requirements'] = esc_html__( 'First name should contain only letters.', 'ws-checkout' );
		$shipping_fields['shipping_last_name']['requirements']  = esc_html__( 'Last name should contain only letters.', 'ws-checkout' );
		$shipping_fields['shipping_phone']['requirements']      = esc_html__( 'Phone number must contain numbers.', 'ws-checkout' );
		$shipping_fields['shipping_email']['requirements']      = esc_html__( 'E-mail is incorrect.', 'ws-checkout' );
		$shipping_fields['shipping_postcode']['requirements']   = esc_html__( 'Postcode should only contain numbers.', 'ws-checkout' );

		$shipping_fields['shipping_first_name']['custom_attributes'] = [
			'pattern' => '[A-Za-zÀ-ÖØ-öø-ÿšŠžŽ -]+',
		];
		$shipping_fields['shipping_last_name']['custom_attributes']  = [
			'pattern' => '[A-Za-zÀ-ÖØ-öø-ÿšŠžŽ -]+',
		];
		$shipping_fields['shipping_phone']['custom_attributes']      = [
			'pattern' => '^[0-9-+\s()]+$',
		];
		$shipping_fields['shipping_city']['custom_attributes']       = [
			'pattern' => '[A-Za-zÀ-ÖØ-öø-ÿšŠžŽ -]+',
		];
		$shipping_fields['shipping_postcode']['custom_attributes']   = [
			'pattern' => '[0-9]+',
		];

		$shipping_fields['shipping_phone']['label'] = $billing_fields['billing_phone']['label'];
		$shipping_fields['shipping_phone']['type']  = 'tel';

		$shipping_fields['shipping_email']['label'] = $billing_fields['billing_email']['label'];
		$shipping_fields['shipping_email']['type']  = 'email';

		$fields_to_hide_for_parcel_machines = $this->fields_to_hide_for_parcel_machines( 'shipping' );
		foreach ( $fields_to_hide_for_parcel_machines as $field_to_hide_key ) {
			if ( isset( $shipping_fields[ $field_to_hide_key ] ) ) {
				$shipping_fields[ $field_to_hide_key ]['class'][] = 'js-hide-for-parcel-machines';
			}
		}

		foreach ( $shipping_fields as &$shipping_field ) {
			$shipping_field['required'] = apply_filters( 'woocommerce_ship_to_different_address_checked', 'shipping' === get_option( 'woocommerce_ship_to_destination' ) ? true : false );
		}

		$fields['shipping'] = $shipping_fields;

		foreach ( $fields as &$field_types ) {
			foreach ( $field_types as &$field ) {
				$field['placeholder']   = ' ';
				$field['input_class'][] = 'js-validate-field';
			}
		}

		if ( $is_checkout_processing ) {
			if ( $this->should_limit_required_fields_based_on_shipping_method() ) {
				foreach ( $this->fields_to_hide_for_parcel_machines( 'billing' ) as $field_to_unrequire ) {
					if ( isset( $fields['billing'][ $field_to_unrequire ] ) ) {
						$fields['billing'][ $field_to_unrequire ]['required'] = false;
					}
				}
				foreach ( $this->fields_to_hide_for_parcel_machines( 'shipping' ) as $field_to_unrequire ) {
					if ( isset( $fields['shipping'][ $field_to_unrequire ] ) ) {
						$fields['shipping'][ $field_to_unrequire ]['required'] = false;
					}
				}
			}
		}


		return $fields;
	}

	/**
	 * Gets the array of fields that require custom validation.
	 *
	 * @param string $fieldset Fieldset type, either shipping or billing.
	 *
	 * @return array
	 */
	public function get_custom_validation_fields( $fieldset ) {
		return [
			$fieldset . '_address_1',
			$fieldset . '_city',
			$fieldset . '_postcode',
			$fieldset . '_state',
			$fieldset . '_company',
//			$fieldset . '_company_code',
		];
	}

	/**
	 * Gets array of field keys that should be hidden if the chosen shipping method is a parchel machine.
	 *
	 * @param string $fieldset_key Fieldset key, either shipping or billing.
	 *
	 * @return array
	 */
	public function fields_to_hide_for_parcel_machines( $fieldset_key ) {
		return [
			$fieldset_key . '_address_1',
			$fieldset_key . '_city',
			$fieldset_key . '_postcode',
			$fieldset_key . '_state',
		];
	}

	/**
	 * Checks whether fewer fields should be validated based on shipping method.
	 *
	 * @return bool
	 */
	public function should_limit_required_fields_based_on_shipping_method() {
		$chosen_shipping_method = filter_input( INPUT_POST, 'shipping_method', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY );
		if ( is_array( $chosen_shipping_method ) && ! empty( $chosen_shipping_method ) ) {
			foreach ( $chosen_shipping_method as $shipping_method ) {
				if ( strpos( $shipping_method, 'parcel' ) !== false || strpos( $shipping_method, 'local_pickup' ) !== false  ) {
					return true;
				}
			}
		}

		return false;
	}


	/**
	 * Outputs a checkout/address form field.
	 *
	 * @param string $key   Key.
	 * @param mixed  $args  Arguments.
	 * @param string $value (default: null).
	 *
	 * @return string
	 */
	public static function render_form_field( $key, $args, $value = null ) {
		$defaults = array(
			'type'              => 'text',
			'label'             => '',
			'description'       => '',
			'placeholder'       => '',
			'maxlength'         => false,
			'required'          => false,
			'autocomplete'      => false,
			'id'                => $key,
			'class'             => array(),
			'label_class'       => array(),
			'input_class'       => array(),
			'return'            => false,
			'options'           => array(),
			'custom_attributes' => array(),
			'validate'          => array(),
			'default'           => '',
			'autofocus'         => '',
			'priority'          => '',
			'requirements'      => false,
		);

		$args = wp_parse_args( $args, $defaults );
		$args = apply_filters( 'woocommerce_form_field_args', $args, $key, $value );

		if ( is_string( $args['label_class'] ) ) {
			$args['label_class'] = array( $args['label_class'] );
		}

		if ( is_null( $value ) ) {
			$value = $args['default'];
		}

		// Custom attribute handling.
		$custom_attributes         = array();
		$args['custom_attributes'] = array_filter( (array) $args['custom_attributes'], 'strlen' );

		if ( $args['maxlength'] ) {
			$args['custom_attributes']['maxlength'] = absint( $args['maxlength'] );
		}

		if ( ! empty( $args['autocomplete'] ) ) {
			$args['custom_attributes']['autocomplete'] = $args['autocomplete'];
		}

		if ( true === $args['autofocus'] ) {
			$args['custom_attributes']['autofocus'] = 'autofocus';
		}

		if ( $args['description'] ) {
			$args['custom_attributes']['aria-describedby'] = $args['id'] . '-description';
		}

		if ( ! empty( $args['custom_attributes'] ) && is_array( $args['custom_attributes'] ) ) {
			foreach ( $args['custom_attributes'] as $attribute => $attribute_value ) {
				$custom_attributes[] = esc_attr( $attribute ) . '="' . esc_attr( $attribute_value ) . '"';
			}
		}

		if ( ! empty( $args['validate'] ) ) {
			foreach ( $args['validate'] as $validate ) {
				$args['class'][] = 'validate-' . $validate;
			}
		}

		$field           = '';
		$label_id        = $args['id'];
		$sort            = $args['priority'] ? $args['priority'] : '';
		$field_container = '<div class="single-field %1$s" id="%2$s" data-priority="' . esc_attr( $sort ) . '">%3$s</div>';

		switch ( $args['type'] ) {
			case 'country':
				$countries = 'shipping_country' === $key ? WC()->countries->get_shipping_countries() : WC()->countries->get_allowed_countries();

				if ( 1 === count( $countries ) ) {

					$field .= '<strong>' . current( array_values( $countries ) ) . '</strong>';

					$field .= '<input type="hidden" name="' . esc_attr( $key ) . '" id="' . esc_attr( $args['id'] ) . '" value="' . current( array_keys( $countries ) ) . '" ' . implode( ' ', $custom_attributes ) . ' class="country_to_state" readonly="readonly" />';

				} else {

					$field = '<select name="' . esc_attr( $key ) . '" id="' . esc_attr( $args['id'] ) . '" class="country_to_state country_select ' . esc_attr( implode( ' ', $args['input_class'] ) ) . '" ' . implode( ' ', $custom_attributes ) . '><option value="">' . esc_html__( 'Select a country&hellip;', 'woocommerce' ) . '</option>';

					foreach ( $countries as $ckey => $cvalue ) {
						$field .= '<option value="' . esc_attr( $ckey ) . '" ' . selected( $value, $ckey, false ) . '>' . $cvalue . '</option>';
					}

					$field .= '</select>';
				}

				break;
			case 'state':
				$field .= '<input type="text" class="' . esc_attr( implode( ' ', $args['input_class'] ) ) . '" name="' . esc_attr( $key ) . '" id="' . esc_attr( $args['id'] ) . '" placeholder="' . esc_attr( $args['placeholder'] ) . '"  value="' . esc_attr( $value ) . '" ' . implode( ' ', $custom_attributes ) . ( $args['required'] ? ' required ' : '' ) . '  />';

				break;
			case 'textarea':
				$field .= '<textarea name="' . esc_attr( $key ) . '" class="input-text " id="' . esc_attr( $args['id'] ) . '" placeholder="' . esc_attr( $args['placeholder'] ) . '" ' . ( empty( $args['custom_attributes']['rows'] ) ? ' rows="2"' : '' ) . ( empty( $args['custom_attributes']['cols'] ) ? ' cols="5"' : '' ) . implode( ' ', $custom_attributes ) . '>' . esc_textarea( $value ) . '</textarea>';

				break;
			case 'checkbox':
				$field = '<label class="checkbox ' . implode( ' ', $args['label_class'] ) . '" ' . implode( ' ', $custom_attributes ) . '>
						<input type="' . esc_attr( $args['type'] ) . '" class="input-checkbox ' . esc_attr( implode( ' ', $args['input_class'] ) ) . '" name="' . esc_attr( $key ) . '" id="' . esc_attr( $args['id'] ) . '" value="1" ' . checked( $value, 1, false ) . ' /> ' . $args['label'] . '</label>';

				break;
			case 'text':
			case 'password':
			case 'datetime':
			case 'datetime-local':
			case 'date':
			case 'month':
			case 'time':
			case 'week':
			case 'number':
			case 'email':
			case 'url':
			case 'tel':
				$field .= '<input type="' . esc_attr( $args['type'] ) . '" class="' . esc_attr( implode( ' ', $args['input_class'] ) ) . '" name="' . esc_attr( $key ) . '" id="' . esc_attr( $args['id'] ) . '" placeholder="' . esc_attr( $args['placeholder'] ) . '"  value="' . esc_attr( $value ) . '" ' . implode( ' ', $custom_attributes ) . ( $args['required'] ? ' required ' : '' ) . '  />';

				break;
			case 'select':
				$field   = '';
				$options = '';

				if ( ! empty( $args['options'] ) ) {
					foreach ( $args['options'] as $option_key => $option_text ) {
						if ( '' === $option_key ) {
							// If we have a blank option, select2 needs a placeholder.
							if ( empty( $args['placeholder'] ) ) {
								$args['placeholder'] = $option_text ? $option_text : __( 'Choose an option', 'woocommerce' );
							}
							$custom_attributes[] = 'data-allow_clear="true"';
						}
						$options .= '<option value="' . esc_attr( $option_key ) . '" ' . selected( $value, $option_key, false ) . '>' . esc_attr( $option_text ) . '</option>';
					}

					$field .= '<select name="' . esc_attr( $key ) . '" id="' . esc_attr( $args['id'] ) . '" class="select ' . esc_attr( implode( ' ', $args['input_class'] ) ) . '" ' . implode( ' ', $custom_attributes ) . ' data-placeholder="' . esc_attr( $args['placeholder'] ) . '">
							' . $options . '
						</select>';
				}

				break;
			case 'radio':
				$label_id .= '_' . current( array_keys( $args['options'] ) );

				if ( ! empty( $args['options'] ) ) {
					foreach ( $args['options'] as $option_key => $option_text ) {
						$field .= '<input type="radio" class="input-radio ' . esc_attr( implode( ' ', $args['input_class'] ) ) . '" value="' . esc_attr( $option_key ) . '" name="' . esc_attr( $key ) . '" ' . implode( ' ', $custom_attributes ) . ' id="' . esc_attr( $args['id'] ) . '_' . esc_attr( $option_key ) . '"' . checked( $value, $option_key, false ) . ' />';
						$field .= '<label for="' . esc_attr( $args['id'] ) . '_' . esc_attr( $option_key ) . '" class="radio ' . implode( ' ', $args['label_class'] ) . '">' . $option_text . '</label>';
					}
				}

				break;
		}

		if ( ! empty( $field ) ) {
			$field_html = '';


			$field_html .= $field;


			if ( $args['label'] && 'checkbox' !== $args['type'] ) {
				$field_html .= '<label for="' . esc_attr( $label_id ) . '" class="' . esc_attr( implode( ' ', $args['label_class'] ) ) . '">' . $args['label'] . '</label>';
			}

			if ( $args['requirements'] ) {
				$field_html .= '<div class="requirements" id="' . esc_attr( $args['id'] ) . '-description" aria-hidden="true">' . wp_kses_post( $args['requirements'] ) . '</div>';
			}

			$container_class = esc_attr( implode( ' ', $args['class'] ) );
			$container_id    = esc_attr( $args['id'] ) . '_field';
			$field           = sprintf( $field_container, $container_class, $container_id, $field_html );
		}

		/**
		 * Filter by type.
		 */
		$field = apply_filters( 'woocommerce_form_field_' . $args['type'], $field, $key, $args, $value );

		/**
		 * General filter on form fields.
		 *
		 * @since 3.4.0
		 */
		$field = apply_filters( 'woocommerce_form_field', $field, $key, $args, $value );

		if ( $args['return'] ) {
			return $field;
		} else {
			echo $field; // WPCS: XSS ok.
		}
	}

	public function override_checkout_fields_validation( $data, &$errors ) {

	}

	/**
	 * See if a fieldset should be skipped.
	 *
	 * @param string $fieldset_key Fieldset key.
	 * @param array  $data         Posted data.
	 *
	 * @return bool
	 */
	private function maybe_skip_fieldset( $fieldset_key, $data ) {
		if ( 'shipping' === $fieldset_key && ( ! $data['ship_to_different_address'] || ! WC()->cart->needs_shipping_address() ) ) {
			return true;
		}

		if ( 'account' === $fieldset_key && ( is_user_logged_in() || ( ! WC()->checkout()->is_registration_required() && empty( $data['createaccount'] ) ) ) ) {
			return true;
		}

		return false;
	}
}
