<?php
/**
 * Checkout products table single product totals column.
 *
 * @var WC_Product $product WooCommerce product instance.
 * @var string     $cart_item_key WooCommerce cart item key.
 * @var array      $cart_item WooCommerce cart item array.
 *
 * @package ws-checkout
 */

?>

<div id="product-totals-<?php echo esc_attr( $cart_item_key ); ?>" class="checkout__products__sum">
	<?php if ( $product->is_on_sale() ) : ?>
		<p>
			<span><?php echo wp_kses( WS_WC_Checkout_Controller::get_item_total_regular_price( $product, $cart_item['quantity'] ), [] ); ?></span>
		</p>
	<?php endif; ?>
	<p>
		<?php
		echo wp_kses( WC()->cart->get_product_subtotal( $product, $cart_item['quantity'] ), [] ); // PHPCS: XSS ok.
		?>
	</p>
</div>
