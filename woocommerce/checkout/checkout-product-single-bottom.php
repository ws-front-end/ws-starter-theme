<?php
/**
 * Checkout products table single product info column bottom part.
 *
 * @var WC_Product $product WooCommerce product instance.
 * @var int        $product_id WooCommerce product instance.
 * @var string     $cart_item_key WooCommerce cart item key.
 * @var array      $cart_item WooCommerce cart item array.
 *
 * @package ws-checkout
 */

?>

<div id="product-bottom-<?php echo esc_attr( $cart_item_key ); ?>" class="checkout__products__info__bottom">
	<p><?php esc_html_e( 'Price per piece', 'ws-checkout' ); ?>
		: <?php echo wp_kses( $product->get_price_html(), [ 'del' => [] ] ); ?></p>


	<?php
	if ( $product->is_type( 'variation' ) || $product->is_type( 'variable' ) ) :
		wc_get_template(
			'woocommerce/checkout/checkout-product-variation-select.php',
			[
				'cart_item_key' => $cart_item_key,
				'cart_item'     => $cart_item,
				'product_id'    => $product_id,
				'product'       => $product,
			]
		);
	endif;
	?>
</div>