<?php
$chosen_method_id = null;
if ( WC()->cart->needs_shipping() && WC()->cart->show_shipping() ) :
	$packages = WC()->shipping()->get_packages();
	?>
	<section id="js-checkout-cart-transport" class="checkout__transport woocommerce-shipping-totals shipping">
		<h2 class="section-title"><?php esc_html_e( 'Transportation', 'ws-checkout' ); ?></h2>
		<hr>

		<?php
		foreach ( $packages as $index => $package ) :
			$chosen_method = isset( WC()->session->chosen_shipping_methods[ $index ] ) ? WC()->session->chosen_shipping_methods[ $index ] : '';
			$product_names = array();

			if ( count( $packages ) > 1 ) {
				foreach ( $package['contents'] as $item_id => $values ) {
					$product_names[ $item_id ] = $values['data']->get_name() . ' &times;' . $values['quantity'];
				}
				$product_names = apply_filters( 'woocommerce_shipping_package_details_array', $product_names, $package );
			}
			?>

			<?php
			do_action( 'woocommerce_review_order_before_shipping' );
			if ( isset( $package['rates'] ) && ! empty( $package['rates'] ) ) :
				?>
				<p><?php esc_html_e( 'Choose a shipping method:', 'ws-checkout' ); ?></p>
				<ul class="checkout__transport__company__container">

				<?php
				foreach ( $package['rates'] as $method ) :

						if ( $method->id === $chosen_method ) :
							$chosen_method_id = $method->method_id;
						endif;
						wc_get_template(
							'checkout/checkout-transport-single.php',
							[
								'index'         => $index,
								'package'       => $package,
								'chosen_method' => $chosen_method,
								'method'        => $method,
							]
						);
						do_action( 'woocommerce_after_shipping_rate', $method, $index );

				endforeach;
				?>
				</ul>

				<div class="checkout__transport__retrieval">
					<div class="checkout__transport__retrieval__container">
						<div class="checkout__transport__retrieval__container__options">
							<?php $shipping_method_data = WS_WC_Checkout_Controller::get_shipping_method_options( $chosen_method_id, $index ); ?>
							<div class="single-field <?php echo esc_attr( $shipping_method_data['is_mk'] ? 'makecommerce-shipping' : '' ); ?>">
								<?php echo $shipping_method_data['html']; // phpcs:ignore
								?>
							</div>
						</div>

						<!--					<div class="checkout__transport__retrieval__container__location">-->
						<!--						<iframe-->
						<!--								src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2029.330026299271!2d24.83699700165645!3d59.42757016009396!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x4692eca2bbe86dbd%3A0x286715ce6c5b3ed9!2sMaksimarket!5e0!3m2!1set!2see!4v1568287773861!5m2!1set!2see"-->
						<!--								width="100%" height="100%" frameborder="0" style="border:0;"-->
						<!--								allowfullscreen=""></iframe>-->
						<!--					</div>-->
					</div>
				</div>
			<?php
			else :
				echo wp_kses_post( apply_filters( 'woocommerce_cart_no_shipping_available_html', esc_html__( 'No shipping options are available. Check your address and if the problems persist, contact us.', 'woocommerce' ) ) );
			endif;

			$first = false;
		endforeach;
		?>
	</section>
<?php
endif;
