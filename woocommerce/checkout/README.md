# Web Systems OÜ WooCommerce Checkout template changelog

> Version: 1.0.2

## Changelog:

**1.0.2**

- JS:

  - Removed payment validation from `CheckoutFieldsController.js` and moved it to `CheckoutPaymentMethodsController.js`

**1.0.1**

- CSS:

  - Added class `.checkout--touch--only` in base.scss to manipulate only in checkout view.
  - Updated in `Checkout-billing-info.scss` payment method structure style.
  - Added in `Checkout-billing-info.scss` class `is-invalid` and added animation, style.

- PHP:

  - Added PHP admin_init to override MakeCommerce hidden setting for payment method display.
