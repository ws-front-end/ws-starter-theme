<?php
/**
 * Checkout billing payment methods template part.
 *
 * @package ws-checkout
 */

$available_gateways = WC()->payment_gateways->get_available_payment_gateways();

if ( count( $available_gateways ) ) {
	current( $available_gateways )->set_current();
}

if ( WC()->cart->needs_payment() ) : ?>
	<div id="js-checkout-payment-methods" class="checkout__billing-info__container__payment">
		<?php if ( count( $available_gateways ) > 1 ) : ?>
			<p><?php esc_html_e( 'Choose a payment method:', 'ws-checkout' ); ?></p>
		<?php endif; ?>
		<div class="single-field">
			<?php if ( count( $available_gateways ) > 1 ) : ?>
				<select name="payment_method" id="js-payment-method-select">
					<?php
					if ( ! empty( $available_gateways ) ) :
						foreach ( $available_gateways as $gateway ) :
							?>
							<option value="<?php echo esc_attr( $gateway->id ); ?>" <?php checked( $gateway->chosen, true ); ?>>
								<?php echo $gateway->get_title(); /* phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotEscaped */ ?><?php echo $gateway->get_icon(); /* phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotEscaped */ ?>
							</option>
						<?php
						endforeach;
					endif;
					?>
				</select>
				<label for="last_name"><?php esc_html_e( 'Pay with', 'ws-checkout' ); ?></label>
			<?php elseif ( count( $available_gateways ) === 1 ) : ?>
				<?php $gateway = current( $available_gateways ); ?>
				<input type="hidden" name="payment_method" value="<?php echo esc_attr( $gateway->id ); ?>"/>
				<input type="text"
						value="<?php echo $gateway->get_title(); /* phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotEscaped */ ?>"
						readonly/>
			<?php endif; ?>
		</div>

		<div class="checkout__billing-info__container__payment__methods">
			<?php
			if ( ! empty( $available_gateways ) ) :
				foreach ( $available_gateways as $gateway ) :
					if ( $gateway->has_fields() || $gateway->get_description() ) :
						?>
						<div class="checkout__billing-info__container__payment__methods__single js-payment-method payment_box js-payment_method_<?php echo esc_attr( $gateway->id ); ?>"
							<?php if ( ! $gateway->chosen ) : /* phpcs:ignore Squiz.ControlStructures.ControlSignature.NewlineAfterOpenBrace */ ?>style="display:none;"<?php endif; /* phpcs:ignore Squiz.ControlStructures.ControlSignature.NewlineAfterOpenBrace */ ?>>
							<?php $gateway->payment_fields(); ?>
						</div>
					<?php
					endif;
				endforeach;
			endif;
			?>
		</div>

	</div>
<?php endif; ?>
