import onChange from 'on-change';
import qs from 'qs';
import {addGlobalEventListener, WsLoader} from '../components/helpers';
import CartFragmentsRenderer from './CartFragmentsRenderer'

export default class CartItemQuantitiesController {
	constructor() {
		const quantityContainers = document.querySelectorAll('.js-product-quantity-container');
		if (!quantityContainers) {
			return;
		}

		this.quantitiesUpdatesRunning = 0;

		this.quantities = {};

		this.initListeners();
		this.watchChanges();
	}

	static getQuantityContainer($currentTarget) {
		return $currentTarget.closest('.js-product-quantity-container');
	}

	static getQuantityInput($quantityContainer) {
		return $quantityContainer.querySelector('input.js-cart-item-quantity-input');
	}

	initListeners() {
		addGlobalEventListener('click', '.js-decrease-product-quantity', (event, currentTarget) => this.decreaseQuantity(event, currentTarget));
		addGlobalEventListener('click', '.js-increase-product-quantity', (event, currentTarget) => this.increaseQuantity(event, currentTarget));
		addGlobalEventListener('input', '.js-cart-item-quantity-input', (event, currentTarget) => this.updateQuantity(event, currentTarget));
		addGlobalEventListener('change', '.js-cart-item-quantity-input', (event, currentTarget) => this.limitAndPreventNull(event, currentTarget));
	}

	watchChanges() {
		const self = this;
		this.quantitiesWatch = onChange(this.quantities, (path, value) => {
			self.updateQuantitiesInServer(self);
			document.getElementById(`js-quantity-${path}`).value = value;
		});
	}

	updateQuantitiesInServer(self) {
		const quantitiesInstance = self;
		quantitiesInstance.quantitiesUpdatesRunning += 1;
		if (quantitiesInstance.quantitiesUpdatesRunning > 1) {
			try {
				quantitiesInstance.quantitiesUpdateAbortController.abort();
			} catch (e) {
				console.log('Aborting quantites update');
			}
		}


		quantitiesInstance.quantitiesUpdateAbortController = new AbortController();
		quantitiesInstance.quantitesUpdateSignal           = quantitiesInstance.quantitiesUpdateAbortController.signal;

		const requestData = {
			'action': 'ws_update_cart_quantities',
			'quantities': quantitiesInstance.quantitiesWatch,
		};

		WsLoader.addLoader(document.querySelector('#js-checkout-cart-transport'), 'checkout-transport-loader');
		WsLoader.addLoader(document.querySelector('#js-checkout-cart-details'), 'checkout-cart-loader');
		fetch(
			php_object.ajax_url,
			{
				method: 'POST',
				signal: quantitiesInstance.quantitesUpdateSignal,
				headers: {
					'Content-Type': 'application/x-www-form-urlencoded',
				},
				body: qs.stringify(requestData, {arrayFormat: 'index'}),
			},
		).then(response => {
			return response.json();
		}).then(response => {
			if (quantitiesInstance.quantitiesUpdatesRunning === 1) {
				if ( response.success ) {
					if ( response.fragments ) {
						CartFragmentsRenderer.renderFragments(response.fragments);
						/*eslint-disable */
						// ws_wc_checkout.checkout.transportationController.reInitSelects();
						// ws_wc_checkout.checkout.paymentMethodsController.reInitSelects();
						/* eslint-enable */

						jQuery(document.body).trigger("updated_checkout",[]);
					}
				}
				quantitiesInstance.quantitiesUpdatesRunning -= 1;

				WsLoader.removeLoader('checkout-transport-loader');
				WsLoader.removeLoader('checkout-cart-loader');
			} else {
				quantitiesInstance.quantitiesUpdatesRunning -= 1;
			}
		}).catch(() => {
			quantitiesInstance.quantitiesUpdatesRunning -= 1;
		});
	}

	setQuantityData(cartItemKey, quantity) {
		this.quantitiesWatch[cartItemKey] = quantity;
	}

	removeQuantityData( cartItemKey ) {
		onChange.unsubscribe(this.quantitiesWatch);
		try {
			delete this.quantities[cartItemKey];
		} catch(e) {
			console.log('Coluldn\'t remove quantity data' );
		}
		this.watchChanges();
	}

	updateQuantity(event, currentTarget) {
		if (Number.isNaN(parseInt(event.data, 10))) {
			event.preventDefault();
			const cartItemKey = currentTarget.getAttribute('data-cart_item_key');
			let quantity      = currentTarget.value.replace(event.data, '');
			if (Number.isNaN(quantity)) {
				quantity = currentTarget.getAttribute('min');
			}

			this.setQuantityData(cartItemKey, quantity);
			currentTarget.value = quantity;  // eslint-disable-line no-param-reassign
		}
	}

	limitAndPreventNull(event, currentTarget) {
		const cartItemKey = currentTarget.getAttribute('data-cart_item_key');
		const max         = parseInt(currentTarget.getAttribute('max'), 10);
		let min           = parseInt(currentTarget.getAttribute('min'), 10);

		if (Number.isNaN(min) || min <= 0) {
			min = 1;
		}

		let quantity = parseInt(currentTarget.value, 10);
		if (Number.isNaN(quantity)) {
			quantity = min;
		} else {
			this.setQuantityData(cartItemKey, quantity);
			if (!Number.isNaN(max) && max > 1) {
				if (quantity > max) {
					quantity = max;
				}
			}
			if (!Number.isNaN(min)) {
				if (quantity < max) {
					quantity = min;
				}
			}
		}

		this.setQuantityData(cartItemKey, quantity);
	}

	decreaseQuantity(event, currentTarget) {
		event.preventDefault();

		const $container     = CartItemQuantitiesController.getQuantityContainer(currentTarget);
		const $quantityInput = CartItemQuantitiesController.getQuantityInput($container);

		const cartItemKey = $quantityInput.getAttribute('data-cart_item_key');

		let minPurchaseQuantity = parseInt($quantityInput.getAttribute('min'), 10);
		if (Number.isNaN(minPurchaseQuantity)) {
			minPurchaseQuantity = 1;
		}

		let currentQuantity = parseInt($quantityInput.value, 10);
		if (Number.isNaN(currentQuantity)) {
			currentQuantity = 1;
		}

		currentQuantity -= 1;
		if (currentQuantity < minPurchaseQuantity) {
			currentQuantity = minPurchaseQuantity;
		}

		this.setQuantityData(cartItemKey, currentQuantity);
	}

	increaseQuantity(event, currentTarget) {
		event.preventDefault();


		const $container     = CartItemQuantitiesController.getQuantityContainer(currentTarget);
		const $quantityInput = CartItemQuantitiesController.getQuantityInput($container);

		const cartItemKey = $quantityInput.getAttribute('data-cart_item_key');

		let maxPurchaseQuantity = parseInt($quantityInput.getAttribute('max'), 10);
		if (Number.isNaN(maxPurchaseQuantity)) {
			maxPurchaseQuantity = 1;
		}

		let currentQuantity = parseInt($quantityInput.value, 10);
		if (Number.isNaN(currentQuantity)) {
			currentQuantity = 1;
		}

		currentQuantity += 1;
		if ( maxPurchaseQuantity > 0 ) {
			if (currentQuantity > maxPurchaseQuantity) {
				currentQuantity = maxPurchaseQuantity;
			}
		}

		this.setQuantityData(cartItemKey, currentQuantity);
	}

}
