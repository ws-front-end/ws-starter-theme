<?php
/***
 * Checkout page products list template.
 *
 * @var string     $cart_item_key
 * @var array      $cart_item
 * @var WC_Product $product
 * @var int        $product_id
 *
 * @package ws-checkout
 */

defined( 'ABSPATH' ) || exit;
?>

<li id="product-<?php echo esc_attr( $cart_item_key ); ?>" class="checkout__product__single">
    <a target="_blank"
        href="<?php echo esc_url( apply_filters( 'woocommerce_cart_item_permalink', $product->is_visible() ? $product->get_permalink() : '', $cart_item, $cart_item_key ) ); ?>">
        <?php echo $product->get_image( 'large' ); //phpcs:ignore ?>
    </a>

    <div class="checkout__products__info">
        <div>
            <div class="checkout__products__info__top">
                <a target="_blank"
                    href="<?php echo esc_url( apply_filters( 'woocommerce_cart_item_permalink', $product->is_visible() ? $product->get_permalink() : '', $cart_item, $cart_item_key ) ); ?>">
                    <p><?php echo esc_html( $product->get_name() ); ?></p>
                    <span><?php echo esc_html( $product->get_sku() ); ?></span>
                </a>
            </div>

            <?php
			wc_get_template(
				'woocommerce/checkout/checkout-product-single-bottom.php',
				[
					'product'       => $product,
					'product_id'    => $product_id,
					'cart_item'     => $cart_item,
					'cart_item_key' => $cart_item_key,
				]
			);
			?>

        </div>
        <?php
		echo apply_filters( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			'woocommerce_cart_item_remove_link',
			sprintf(
				'<button type="button" name="button" class="js-remove-product" data-cart_item_key="%s" data-product_id="%s" data-product_sku="%s">%s</button>',
				esc_attr( $cart_item_key ),
				esc_attr( $product_id ),
				esc_attr( $product->get_sku() ),
				esc_html__( 'Remove', 'ws-checkout' )
			),
			$cart_item_key
		);
		?>
    </div>

    <div class="checkout__products__more checkout--touch--only js-cart-open-more">
        <button type="button" name="button">
            <span></span>
            <span></span>
            <span></span>
        </button>
    </div>

    <div class="checkout__products__quantity js-product-quantity-container">
        <?php if ( $product->is_sold_individually() ) : ?>
        <div>
            <button type="button" name="quantity-remove" disabled>-</button>
            <label class="screen-reader--only"
                for="value-input"><?php esc_html__( 'Quantity', 'ws-checkout' ); ?></label>
            <?php
				printf(
					'<input id="js-quantity-%s" type="text" class="js-cart-item-quantity-input" name="quantity" value="%s" data-cart_item_key="%s" readonly>',
					esc_attr( $cart_item_key ),
					esc_attr( $cart_item['quantity'] ),
					esc_attr( $cart_item_key )
				);
				?>

            <button type="button" name="quantity-add" disabled>+</button>
        </div>
        <?php else : ?>
        <div>
            <button type="button" name="quantity-remove" class="js-decrease-product-quantity">-</button>
            <label class="screen-reader--only"
                for="value-input"><?php esc_html__( 'Quantity', 'ws-checkout' ); ?></label>
            <?php
				printf(
					'<input id="js-quantity-%s"  type="text" class="js-cart-item-quantity-input" name="quantity" value="%s" data-cart_item_key="%s" max="%s" min="%s">',
					esc_attr( $cart_item_key ),
					esc_attr( $cart_item['quantity'] ),
					esc_attr( $cart_item_key ),
					esc_attr( $product->get_max_purchase_quantity() ),
					esc_attr( $product->get_min_purchase_quantity() )
				);
				?>
            <button type="button" name="quantity-add" class="js-increase-product-quantity">+</button>
        </div>
        <?php endif; ?>
    </div>

    <?php
	wc_get_template(
		'woocommerce/checkout/checkout-product-single-total.php',
		[
			'product'       => $product,
			'cart_item'     => $cart_item,
			'cart_item_key' => $cart_item_key,
		]
	);
	?>

    <div class="checkout__products__more__options touch--only js-product-read-more">
        <?php
		echo apply_filters( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			'woocommerce_cart_item_remove_link',
			sprintf(
				'<button type="button" name="button" class="js-remove-product" data-cart_item_key="%s" data-product_id="%s" data-product_sku="%s">%s</button>',
				esc_attr( $cart_item_key ),
				esc_attr( $product_id ),
				esc_attr( $product->get_sku() ),
				esc_html__( 'Remove', 'ws-checkout' )
			),
			$cart_item_key
		);
		?>
        <button type="button" class="js-cart-close-more"><?php esc_html_e( 'Close', 'ws-checkout' ); ?></button>
    </div>
</li>
