// js/checkout/checkout.js

// Require scripts
import CartItemExtraOptionsController from './CartItemExtraOptionsController'
import CartItemQuantitiesController from './CartItemQuantitiesController'
import CartCouponController from './CartCouponController'

import CheckoutTransportationController from './CheckoutTransportationController'
import CheckoutFieldsController from './CheckoutFieldsController'
import CheckoutPaymentMethodsController from './CheckoutPaymentMethodsController'

import { WsLoader } from '../components/helpers';

// Create a global window variable
window.ws_wc_checkout = {
	cart: {
		itemQuantitiesController: new CartItemQuantitiesController(),
		itemExtraOptionsController: new CartItemExtraOptionsController(),
		couponController: new CartCouponController(),
	},
	checkout: {
		transportationController: new CheckoutTransportationController(),
		fieldsController: new CheckoutFieldsController(),
		paymentMethodsController: new CheckoutPaymentMethodsController(),
	},
	wsLoader: WsLoader,
};