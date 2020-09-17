<?php
/**
 * Checkout cart toggle for mobile users.
 *
 * @package ws-checkout
 */

?>

<div id="js-mobile-cart-toggle" class="checkout__cart__button checkout--touch--only">
    <button class="js-cart-toggle">
        <img src="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/dist/img/svg/checkout-cart.svg"
            alt="cart">
    </button>
    <p id="js-mobile-cart-total"><?php echo wp_kses( WC()->cart->get_total(), [] ); ?></p>
</div>
