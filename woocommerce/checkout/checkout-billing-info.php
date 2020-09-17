<?php
/**
 * Billing info template part.
 *
 * @var WC_Checkout $checkout
 *
 * @package ws-checkout
 */

$billing_fields  = $checkout->get_checkout_fields( 'billing' );
$shipping_fields = $checkout->get_checkout_fields( 'shipping' );

?>

<section id="js-checkout-billing-info" class="checkout__billing-info">
	<h2 class="checkout-section-title"><?php esc_html_e( 'Billing details', 'ws-checkout' ); ?></h2>
	<hr>

	<div class="checkout__billing-info__container">
		<div class="checkout__billing-info__container__fields">
			<?php do_action( 'woocommerce_before_checkout_billing_form', $checkout ); ?>

			<div class="switch-button">
				<label class="switch-button__text"
						for="ws_is_business_client"><?php esc_html_e( 'Private customer', 'ws-checkout' ); ?></label>
				<input type="checkbox" id="ws_is_business_client" name="ws_is_business_client" class='switch'/>
				<label for="ws_is_business_client"></label>
				<label class="switch-button__text"
						for="ws_is_business_client"><?php esc_html_e( 'Business client', 'ws-checkout' ); ?></label>
			</div>

			<p><?php esc_html_e( 'Add your contact information so we can deliver the shipment to you.', 'ws-checkout' ); ?>
			</p>

			<div class="js-billing-fields-wrapper">
				<?php
				$cycle = 0;
				foreach ( $billing_fields as $key => $field ) {
					if ( 0 === $cycle ) {
						echo '<div class="two-columns">';
					}
					WS_WC_Checkout_Controller::render_form_field( $key, $field, $checkout->get_value( $key ) );
					if ( 1 === $cycle ) {
						echo '</div>';
					}

					$cycle ++;
				}
				?>
			</div>

			<?php if ( true === WC()->cart->needs_shipping_address() ) : ?>
				<div class="checkbox-field">
					<input type="checkbox" id="ship_to_different_address" class="js-shipping-fields-toggle"
							name="ship_to_different_address" value="1"
						<?php checked( apply_filters( 'woocommerce_ship_to_different_address_checked', 'shipping' === get_option( 'woocommerce_ship_to_destination' ) ? 1 : 0 ), 1 ); ?>>
					<div class="checkbox-field__indicator"></div>
					<label
							for="ship_to_different_address"><?php esc_html_e( 'Ship to a different address?', 'ws-checkout' ); ?></label>
				</div>

				<div
						class="js-shipping-fields-wrapper <?php echo esc_attr( apply_filters( 'woocommerce_ship_to_different_address_checked', 'shipping' === get_option( 'woocommerce_ship_to_destination' ) ? 1 : 0 ) ? 'shipping-fields-visible' : 'shipping-fields-hidden' ); ?>">
					<?php
					$cycle = 0;
					foreach ( $shipping_fields as $key => $field ) {
						if ( 0 === $cycle ) {
							echo '<div class="two-columns">';
						}
						WS_WC_Checkout_Controller::render_form_field( $key, $field, $checkout->get_value( $key ) );
						if ( 1 === $cycle ) {
							echo '</div>';
						}

						$cycle ++;
					}
					?>
				</div>
			<?php endif; ?>
			<?php do_action( 'woocommerce_after_checkout_billing_form', $checkout ); ?>
		</div>

		<?php wc_get_template( 'woocommerce/checkout/checkout-billing-payment-methods.php' ); ?>
	</div>

</section>
