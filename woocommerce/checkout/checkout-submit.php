<?php
/**
 * Checkout submit template part.
 *
 * @package ws-checkout
 */

$order_button_text = apply_filters( 'woocommerce_pay_order_button_text', __( 'Pay for order', 'woocommerce' ) );
?>

<div class="checkout__submit-button">
    <hr>

    <div class="checkbox-field">
        <input type="checkbox" name="terms" id="terms" class="js-policies-checkbox" required
            <?php checked( apply_filters( 'woocommerce_terms_is_checked_default', isset( $_POST['terms'] ) ), true ); // WPCS: input var ok, csrf ok. ?>>
        <div class="checkbox-field__indicator"></div>
        <label for="terms"><?php wc_terms_and_conditions_checkbox_text(); ?></label>
        <input type="hidden" name="terms-field" value="1">
    </div>
    <div class="checkbox-field">
        <input type="checkbox" name="" id="privacy-policy" class="js-policies-checkbox" required>
        <div class="checkbox-field__indicator"></div>
        <label
            for="privacy-policy"><?php echo WS_WC_Checkout_Controller::get_privacy_policy_label(); //phpcs:ignore ?></label>
    </div>


    <input type="hidden" name="woocommerce_pay" value="1" />

    <?php do_action( 'woocommerce_pay_order_before_submit' ); ?>

    <?php echo apply_filters( 'woocommerce_pay_order_button_html', '<button type="submit" class="button alt" id="place_order" value="' . esc_attr( $order_button_text ) . '" data-value="' . esc_attr( $order_button_text ) . '">' . esc_html( $order_button_text ) . '</button>' ); // @codingStandardsIgnoreLine ?>

    <?php do_action( 'woocommerce_pay_order_after_submit' ); ?>

    <?php wp_nonce_field( 'woocommerce-process_checkout', 'woocommerce-process-checkout-nonce' ); ?>
</div>
