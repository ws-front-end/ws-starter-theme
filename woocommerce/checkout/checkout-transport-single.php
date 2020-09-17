<?php
/**
 * Single transport option template part.
 *
 * @package ws-checkout
 */

?>

<li class="checkout__transport__single" aria-label="Transport option">
	<?php printf( '<input type="radio" name="shipping_method[%1$d]" data-index="%1$d" id="shipping_method_%1$d_%2$s" value="%3$s" class="shipping_method" %4$s />', $index, esc_attr( sanitize_title( $method->id ) ), esc_attr( $method->id ), checked( $method->id, $chosen_method, false ) ); // WPCS: XSS ok. ?>

	<div class="checkout__transport__single__box">
		<span class="selected__checkmark">
			<img src="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/dist/img/svg/checked.svg" alt="checked">
		</span>

		<?php
		echo WS_WC_Checkout_Controller::get_shipping_logo( $method ); //phpcs:ignore
		?>

		<p class="checkout__transport__single__box__name"><?php echo esc_html( $method->get_label() ); ?></p>

		<p class="checkout__transport__single__box__price">
			<?php
			$price_cost = floatval( $method->get_cost() );
			if ( ! $price_cost ) {
				echo esc_html_x( 'Free', 'Text to show for free shipping.', 'ws-checkout' );
			} else {
				echo esc_html( $method->get_cost() ) . ' ' . esc_html( get_woocommerce_currency_symbol() );
			}
			?>
		</p>
	</div>

</li>
