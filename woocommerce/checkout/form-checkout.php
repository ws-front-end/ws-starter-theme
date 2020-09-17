<?php
/**
 * Checkout Form
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/checkout/form-checkout.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce/Templates
 * @version 3.5.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$checkout = WC_Checkout::instance();
?>

	<form name="checkout" method="post" class="woocommerce-checkout checkout"
			action="<?php echo esc_url( wc_get_checkout_url() ); ?>" enctype="multipart/form-data">

		<div class="max--width--smaller">
			<section class="checkout">
				<div class="checkout__left">
					<?php wc_get_template( 'woocommerce/checkout/checkout-products.php' ); ?>
					<?php wc_get_template( 'woocommerce/checkout/checkout-transport.php' ); ?>
					<?php
					wc_get_template(
						'woocommerce/checkout/checkout-billing-info.php',
						[
							'checkout' => $checkout,
						]
					);
					?>

					<?php wc_get_template( 'woocommerce/checkout/checkout-submit.php' ); ?>
				</div>

				<aside class="checkout__right">
					<div class="checkout__right--sticky">
						<!--					--><?php //wc_get_template( 'woocommerce/checkout/checkout-progress.php' ); ?>
						<!--					--><?php //wc_get_template( 'woocommerce/checkout/checkout-extra.php' ); ?>
						<?php wc_get_template( 'woocommerce/checkout/checkout-cart.php' ); ?>
					</div>
				</aside>

				<?php wc_get_template('woocommerce/checkout/checkout-mobile-cart-toggle.php' ); ?>
			</section>
		</div>

	</form>
<?php
do_action( 'woocommerce_after_checkout_form', $checkout );
