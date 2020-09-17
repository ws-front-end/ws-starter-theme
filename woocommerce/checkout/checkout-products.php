<?php
/***
 * Checkout page products list template.
 *
 * @package ws-checkout
 */

defined( 'ABSPATH' ) || exit;
?>
<section class="checkout__products">
    <h2 class="checkout-section-title"><?php esc_html_e( 'Products', 'ws-checkout' ); ?></h2>

    <?php do_action( 'woocommerce_before_cart' ); ?>

    <ul class="checkout__products__container">
        <li class="checkout__products__container__column-name">
            <span><?php esc_html_e( 'Product', 'ws-checkout' ); ?></span>
            <span><?php esc_html_e( 'Description', 'ws-checkout' ); ?></span>
            <span><?php esc_html_e( 'Quantity', 'ws-checkout' ); ?></span>
            <span><?php esc_html_e( 'Total', 'ws-checkout' ); ?></span>
            <hr>
        </li>

        <?php
		foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) :
			$_product   = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );
			$product_id = apply_filters( 'woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key );
			if ( $_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters( 'woocommerce_cart_item_visible', true, $cart_item, $cart_item_key ) ) :

				do_action( 'woocommerce_cart_contents' );
				wc_get_template(
					'woocommerce/checkout/checkout-product-single.php',
					[
						'cart_item_key' => $cart_item_key,
						'cart_item'     => $cart_item,
						'product_id'    => $product_id,
						'product'       => $_product,
					]
				);

				do_action( 'woocommerce_before_cart_contents' );

			endif;
		endforeach;
		?>

        <?php do_action( 'woocommerce_after_cart_contents' ); ?>

    </ul>

    <?php do_action( 'woocommerce_after_cart' ); ?>
    <button class="checkout--touch--only js-open-coupon-form"
        type="button"><?php esc_html_e( 'Do you have a coupon?', 'ws-checkout' ); ?></button>
</section>
