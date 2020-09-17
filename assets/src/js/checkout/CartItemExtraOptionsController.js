import {addGlobalEventListener, postData, WsLoader} from '../components/base/helpers';
import CartFragmentsRenderer from './CartFragmentsRenderer';

export default class CartItemExtraOptionsController {
	constructor() {
		this.initListeners();
	}

	initListeners() {
		addGlobalEventListener('click', '.js-cart-toggle', (event) => this.handleToggleCart(event));
		addGlobalEventListener('click', '.js-cart-open-more', CartItemExtraOptionsController.openMore);
		addGlobalEventListener('click', '.js-cart-close-more', CartItemExtraOptionsController.closeMore);

		addGlobalEventListener('click', '.js-remove-product', CartItemExtraOptionsController.removeItem);
	}

	handleToggleCart(event) {
		event.preventDefault();

		this.toggleCart();
	}

	toggleCart() {
		const cart       = document.querySelector('.checkout__cart');
		const cartButton = document.querySelector('.checkout__cart__button');

		if (cart.classList.contains('active')) {
			cart.classList.remove('active');
			cartButton.classList.remove('hidden');
		} else {
			cart.classList.add('active');
			cartButton.classList.add('hidden');
		}
	}

	static openMore(event, currentTarget) {
		document.querySelectorAll('.js-product-read-more').forEach(el => {
			el.classList.remove('active');
		});
		currentTarget.closest('li').querySelector('.js-product-read-more').classList.toggle('active');
	}

	static closeMore(event, currentTarget) {
		currentTarget.closest('.js-product-read-more').classList.remove('active');
	}

	static removeItem(event, currentTarget) {
		event.preventDefault();

		const cartItemKey = currentTarget.getAttribute('data-cart_item_key');

		const $singleProductRow = document.querySelector(`${'#product-'}${cartItemKey}`);
		WsLoader.addLoader($singleProductRow, `product-${cartItemKey}`);
		WsLoader.addLoader('#js-checkout-cart-transport', 'checkout-transportation-loader');

		postData(
			php_object.ajax_url,
			{
				action: 'ws_remove_cart_item',
				cart_item_key: cartItemKey,
			},
		).then((response) => {
			if (response.success) {
				if (response.is_cart_empty) {
					window.location.reload(true);
				}

				window.ws_wc_checkout.cart.itemQuantitiesController.removeQuantityData(cartItemKey);
				$singleProductRow.classList.add('remove');
				setTimeout(() => {
					$singleProductRow.remove();
				}, 400);

				if (response.fragments) {
					CartFragmentsRenderer.renderFragments(response.fragments);
					jQuery(document.body).trigger('updated_checkout');
				}
			}

			WsLoader.removeLoader('checkout-transport-loader');
			WsLoader.removeLoader('checkout-cart-loader');
		});
	}

}
