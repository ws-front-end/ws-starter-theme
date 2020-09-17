import Choices from 'choices.js';
import 'choices.js/public/assets/styles/choices.min.css';
import {addGlobalEventListener, WsLoader} from '../components/helpers';

export default class CheckoutTransportationController {
	constructor () {
		this.transportationContainer = CheckoutTransportationController.getContainer();
		if ( ! this.transportationContainer ) {
			return;
		}

		this.initListeners();
		this.reInitSelects();
	}

	static getContainer() {
		return document.querySelector('.checkout__transport');
	}

	initListeners() {
		jQuery(document.body).on('update_checkout', () => CheckoutTransportationController.updateCheckout() );
		jQuery(document.body).on('updated_checkout', () => this.updatedCheckout() );

		addGlobalEventListener( 'click', '#place_order', CheckoutTransportationController.validateParcelMachines );
	}

	static validateParcelMachines(event) {
		const $activeParcelsSelection = document.querySelector('.checkout__transport__retrieval__container__options .single-field > .choices select');
		if ( $activeParcelsSelection && $activeParcelsSelection.value === '' ) {
			event.preventDefault();
			event.stopPropagation();

			const $choicesContainer = $activeParcelsSelection.closest('.choices');
			if ( $choicesContainer ) {
				$choicesContainer.classList.add('choices--invalid-selection');
				document.querySelector('#js-checkout-cart-transport').scrollIntoView()
			} else {
				$choicesContainer.classList.remove('choices--invalid-selection');
			}
		}
	}

	static updateCheckout() {
		WsLoader.addLoader(document.querySelector('#js-checkout-cart-transport'), 'checkout-transport-loader');
		WsLoader.addLoader(document.querySelector('#js-checkout-cart-details'), 'checkout-cart-loader');
	}

	updatedCheckout() {
		CheckoutTransportationController.reOrderShippingSubElements();

		jQuery('select:visible option').each((i, $option) => {
			if ( $option.value === '' || $option.value === 'undefined' || ! $option.hasAttribute('value') ) {
				// eslint-disable-next-line no-param-reassign
				$option.innerText = '';
			}
		});

		this.reInitSelects();

		WsLoader.removeLoader('checkout-transport-loader');
		WsLoader.removeLoader('checkout-cart-loader');
	}

	static reOrderShippingSubElements() {
		const firstElement = CheckoutTransportationController.getContainer().querySelector('label');
		const secondElement = CheckoutTransportationController.getContainer().querySelector('select');

		if ( firstElement && secondElement ) {
			CheckoutTransportationController.swapElements( firstElement, secondElement);
		}
	}

	static swapElements(obj1, obj2) {
		// save the location of obj2
		const parent2 = obj2.parentNode;
		const next2 = obj2.nextSibling;
		// special case for obj1 is the next sibling of obj2
		if (next2 === obj1) {
			// just put obj1 before obj2
			parent2.insertBefore(obj1, obj2);
		} else {
			// insert obj2 right before obj1
			obj1.parentNode.insertBefore(obj2, obj1);

			// now insert obj1 where obj2 was
			if (next2) {
				// if there was an element after obj2, then insert obj1 right before that
				parent2.insertBefore(obj1, next2);
			} else {
				// otherwise, just append as last child
				parent2.appendChild(obj1);
			}
		}
	}

	reInitSelects() {
		CheckoutTransportationController.getContainer().querySelectorAll('select').forEach( select => {
			// eslint-disable-next-line no-undef
			const choicesArgs = WS_WC_CHECKOUT_DATA.choices_args;
			choicesArgs.callbackOnInit = function() {
				if ( 'passedElement' in this) {
					if ( 'element' in this.passedElement ) {
						if ( this.passedElement.value !== '' ) {
							if ('containerOuter' in this) {
								if ('element' in this.containerOuter) {
									const choicesDiv = this.containerOuter.element;
									if (choicesDiv) {
										choicesDiv.classList.add('choices--selected');
									}
								}
							}
						}
					}
				}
			};
			new Choices(select, choicesArgs );
			select.addEventListener('choice', (event) => {
				event.target.closest('div.choices').classList.add('choices--selected');
				event.target.closest('div.choices').classList.remove('choices--invalid-selection');
			}, false);
		});
	}
}
