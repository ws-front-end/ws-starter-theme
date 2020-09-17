<?php
/**
 * Thankyou page
 *
 * @package ws-checkout
 */

defined( 'ABSPATH' ) || exit;
?>
<div class="max--width">
    <section class="thankyou">
        <div class="thankyou__top">
            <span class="thankyou__top__selected-checkmark">
                <img src="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/dist/img/svg/checked.svg"
                    alt="checked">
            </span>
            <h1><?php esc_html_e( 'Thank you!', 'ws-checkout' ); ?></h1>
            <p><?php echo apply_filters( 'woocommerce_thankyou_order_received_text', esc_html__( 'Your order has been recieved and will be processed once the payment is verified.', 'ws-checkout' ), $order ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
            </p>

            <!-- <div class="thankyou__top__mail">
                <form action="">
                    <p>Parimate pakkumiste saamiseks lisa enda e-posti aadress:</p>
                    <div>
                        <input type="email" name="" id="" placeholder="E-posti address">
                        <button>Lisa</button>
                    </div>
                </form>
            </div> -->

            <a class="thankyou__back-home"
                href="<?php echo esc_url( get_permalink( wc_get_page_id( 'shop' ) ) ); ?>"><?php esc_html_e( 'Back to the shop', 'ws-checkout' ); ?></a>
        </div>

        <h2 class="section-title"><?php esc_html_e( 'Order details', 'ws-checkout' ); ?></h2>
        <hr>

        <div class="thankyou__order_details-header">
            <ul class="woocommerce-order-overview woocommerce-thankyou-order-details order_details">

                <li class="woocommerce-order-overview__order order">
                    <?php esc_html_e( 'Order number:', 'woocommerce' ); ?>
                    <strong><?php echo $order->get_order_number(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></strong>
                </li>

                <li class="woocommerce-order-overview__date date">
                    <?php esc_html_e( 'Date:', 'woocommerce' ); ?>
                    <strong><?php echo wc_format_datetime( $order->get_date_created() ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></strong>
                </li>

                <?php if ( is_user_logged_in() && $order->get_user_id() === get_current_user_id() && $order->get_billing_email() ) : ?>
                <li class="woocommerce-order-overview__email email">
                    <?php esc_html_e( 'Email:', 'woocommerce' ); ?>
                    <strong><?php echo $order->get_billing_email(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></strong>
                </li>
                <?php endif; ?>

                <li class="woocommerce-order-overview__total total">
                    <?php esc_html_e( 'Total:', 'woocommerce' ); ?>
                    <strong><?php echo $order->get_formatted_order_total(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></strong>
                </li>

                <?php if ( $order->get_payment_method_title() ) : ?>
                <li class="woocommerce-order-overview__payment-method method">
                    <?php esc_html_e( 'Payment method:', 'woocommerce' ); ?>
                    <strong><?php echo wp_kses_post( $order->get_payment_method_title() ); ?></strong>
                </li>
                <?php endif; ?>

            </ul>
        </div>
        <div class="thankyou__order-details-body">
            <?php do_action( 'woocommerce_thankyou_' . $order->get_payment_method(), $order->get_id() ); ?>
            <?php do_action( 'woocommerce_thankyou', $order->get_id() ); ?>
        </div>
    </section>
</div>
