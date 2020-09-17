<?php
/**
 * Single variation product attribute selects for the checkout products table.
 *
 * @var string                         $cart_item_key Current cart item key.
 * @var array                          $cart_item Current cart item array.
 * @var int                            $product_id Current product ID.
 * @var WC_Product|WC_Product_Variable $product Current WooCommerce product instance.
 *
 * @package ws-checkout
 */

$parent_product_id = $product->get_parent_id();

$product = wc_get_product( $parent_product_id );

$get_variations       = count( $product->get_children() ) <= apply_filters( 'woocommerce_ajax_variation_threshold', 30, $product );
$available_variations = $get_variations ? $product->get_available_variations() : false;
$attributes           = $product->get_variation_attributes();
$selected_attributes  = $product->get_default_attributes();


?>
<div class="checkout__products__info__bottom__options">
	<?php foreach ( $attributes as $attribute_name => $options ) : ?>
		<div class="checkout__products__info__bottom__options__select">
			<label for="<?php echo esc_attr( sanitize_title( $attribute_name ) ); ?>"><?php echo wc_attribute_label( $attribute_name ); // WPCS: XSS ok. ?>:</label>
			<?php
			echo WS_WC_Checkout_Controller::get_selected_variation_attribute_label( $attribute_name, $cart_item ); // WPCS: XSS ok.

			?>
		</div>
	<?php endforeach; ?>
</div>
