import {addGlobalEventListener, postData, triggerEvent, WsLoader} from '../components/base/helpers';
import CartFragmentsRenderer from './CartFragmentsRenderer'

export default class CartCouponController {
	constructor() {
		addGlobalEventListener('click', '.js-add-coupon', CartCouponController.submitCouponCode );
		addGlobalEventListener( 'click', '.js-remove-coupon', CartCouponController.removeCouponCode );
		addGlobalEventListener( 'click', '.js-open-coupon-form', CartCouponController.openCouponForm);
	}

	static submitCouponCode(event, currentTarget ) {
		event.preventDefault();

		const couponCode = currentTarget.closest('.js-coupon-container').querySelector('.js-coupon-input');
		if ( ! couponCode ) {
			return;
		}

		if ( couponCode.value !== '' ) {

			WsLoader.addLoader(document.querySelector('#js-checkout-cart-transport'), 'checkout-transport-loader');
			WsLoader.addLoader(document.querySelector('#js-checkout-cart-details'), 'checkout-cart-loader');
			postData(
				php_object.ajax_url,
				{
					'action': 'ws_apply_coupon',
					'coupon_code': couponCode.value,
				}
			).then((response) => {
				if ( response.success ) {
					if ( response.fragments ) {
						CartFragmentsRenderer.renderFragments(response.fragments);
						jQuery(document.body).trigger('updated_checkout');
					}
				} else {
					document.querySelector('#js-checkout-cart-details').classList.add('coupon-error');
				}
				WsLoader.removeLoader('checkout-transport-loader');
				WsLoader.removeLoader('checkout-cart-loader');
			})
		}
	}

	static removeCouponCode(event, currentTarget ) {
		event.preventDefault();

		const couponCode = currentTarget.getAttribute('data-coupon_code');
		if ( ! couponCode ) {
			return;
		}

		if ( couponCode !== '' ) {

			WsLoader.addLoader(document.querySelector('#js-checkout-cart-transport'), 'checkout-transport-loader');
			WsLoader.addLoader(document.querySelector('#js-checkout-cart-details'), 'checkout-cart-loader');
			postData(
				php_object.ajax_url,
				{
					'action': 'ws_remove_coupon',
					'coupon_code': couponCode,
				}
			).then((response) => {
				if ( response.success ) {
					if ( response.fragments ) {
						CartFragmentsRenderer.renderFragments(response.fragments);
					}
				}
				WsLoader.removeLoader('checkout-transport-loader');
				WsLoader.removeLoader('checkout-cart-loader');
			})
		}
	}

	static openCouponForm() {
		const $couponFormToggle = document.querySelector('#js-coupon-form-toggle');
		if ( $couponFormToggle ) {
			$couponFormToggle.checked = false;

			// eslint-disable-next-line no-undef
			ws_wc_checkout.cart.itemExtraOptionsController.toggleCart();
			const $couponInput = document.querySelector('.js-coupon-input');
			if ( $couponInput ) {
				$couponInput.focus();
			}
		}
	}
}
