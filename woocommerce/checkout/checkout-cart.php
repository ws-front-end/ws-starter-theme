<?php
/***
 * Checkout page right side cart details template.
 *
 * @package ws-checkout
 */

defined( 'ABSPATH' ) || exit;
?>
<section id="js-checkout-cart-details" class="checkout__cart">

    <h2><?php esc_html_e( 'Cart', 'ws-checkout' ); ?></h2>
    <div class="checkout__cart__discount">
        <input type="checkbox" id="js-coupon-form-toggle" checked>
        <h3><?php esc_html_e( 'Do you have a coupon?', 'ws-checkout' ); ?></h3>
        <div class="checkout__cart__discount__active">

            <div class="checkout__cart__discount__active__input js-coupon-container">
                <input type="text" name="coupon_code" class="js-coupon-input"
                    placeholder="<?php esc_html_e( 'Coupon', 'ws-checkout' ); ?>">
                <button type="button" name="add-discount"
                    class="js-add-coupon"><?php echo esc_html_x( 'Add', 'Add Coupon code button', 'ws-checkout' ); ?></button>
            </div>
        </div>

        <ul class="checkout__cart__discount__active__codes">
            <li class="coupon-code-error">
                <p><?php esc_html_e( 'Submitted coupon code is invalid.', 'ws-checkout' ); ?></p>
            </li>
            <?php foreach ( WC()->cart->get_applied_coupons() as $applied_coupon ) : ?>
            <li>
                <p><?php echo esc_html( $applied_coupon ); ?><span><?php echo esc_html( WS_WC_Checkout_Controller::get_coupon_amount( $applied_coupon ) ); ?></span>
                </p>
                <button type="button" name="remove-discount" class="js-remove-coupon"
                    data-coupon_code="<?php echo esc_attr( $applied_coupon ); ?>">X</button>
            </li>
            <?php endforeach; ?>
        </ul>
    </div>

    <p><?php esc_html_e( 'Ostukorvi info' ); ?></p>

    <ul class="checkout__cart__details">
        <?php if ( WC()->cart->has_discount() ) : ?>
        <li>
            <p><?php esc_html_e( 'Total discount:', 'ws-checkout' ); ?></p>
            <span><?php echo wp_kses( WC()->cart->get_discount_total(), [] ); ?></span>
        </li>
        <?php endif; ?>
        <?php if ( wc_tax_enabled() ) : ?>
        <li>
            <p><?php esc_html_e( 'Price excluding taxes:', 'ws-checkout' ); ?></p>
            <span><?php echo wp_kses( WS_WC_Checkout_Controller::get_cart_total_without_tax(), [] ); ?></span>
        </li>
        <li>
            <p><?php esc_html_e( 'Tax:', 'ws-checkout' ); ?></p>
            <span><?php echo wp_kses( WC()->cart->get_cart_tax(), [] ); ?></span>
        </li>
        <?php endif; ?>
        <?php if ( WC()->cart->needs_shipping() ) : ?>
        <li>
            <p><?php esc_html_e( 'Shipping:', 'ws-checkout' ); ?></p>
            <span><?php echo wp_kses( WC()->cart->get_cart_shipping_total(), [] ); ?></span>
        </li>
        <?php endif; ?>
        <li class="checkout__cart__sum">
            <p><?php esc_html_e( 'Cart total:', 'ws-checkout' ); ?></p>
            <span><?php echo wp_kses( WC()->cart->get_total(), [] ); ?></span>
        </li>
    </ul>

    <button class="checkout--touch--only js-cart-toggle" type="button" name="button">X</button>

</section>
