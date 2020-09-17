import Choices from 'choices.js';
import {addGlobalEventListener, WsLoader} from '../components/helpers';

export default class CheckoutPaymentMethodsController {
	constructor() {
		this.initListeners();
	}

	initListeners() {
		addGlobalEventListener('change', '#js-payment-method-select', CheckoutPaymentMethodsController.switchPaymentMethod);
		addGlobalEventListener( 'click', '#place_order', CheckoutPaymentMethodsController.validatePaymentMethods );
		addGlobalEventListener( 'click', 'input[name=PRESELECTED_METHOD_makecommerce]', CheckoutPaymentMethodsController.validatePaymentMethods );

		jQuery(document.body).on('update_checkout', () => CheckoutPaymentMethodsController.updateCheckout() );
		jQuery(document.body).on('updated_checkout', () => this.updatedCheckout());
	}

	static switchPaymentMethod(event, currentTarget) {
		const selectedMethodId = currentTarget.value;

		document.querySelectorAll('.js-payment-method').forEach((el) => {
			// eslint-disable-next-line no-param-reassign
			el.style.display = 'none';
		});

		const selectedMethod = document.querySelector(`.js-payment_method_${selectedMethodId}`);
		if (selectedMethod) {
			selectedMethod.style.display = 'block';
		}
	}

	static updateCheckout() {
		WsLoader.addLoader('#js-checkout-payment-methods', 'checkout-payments-loader');
	}

	updatedCheckout() {
		this.reInitSelects();
		this.makeCommerceCountryPickerFix();
	}

	reInitSelects() {
		const paymentMethodSelect = document.querySelector('#js-payment-method-select');
		if ( paymentMethodSelect ) {
			const choicesArgs          = WS_WC_CHECKOUT_DATA.choices_args;
			choicesArgs.callbackOnInit = function () {
				if ('passedElement' in this) {
					if ('element' in this.passedElement) {
						if (this.passedElement.value !== '') {
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

			new Choices(paymentMethodSelect, WS_WC_CHECKOUT_DATA.choices_args);
			paymentMethodSelect.addEventListener('choice', (event) => {
				event.target.closest('div.choices').classList.add('choices--selected');
			}, false);
		}
	}


	makeCommerceCountryPickerFix() {
		const makeCommerceCountryPicker = document.querySelector('input[name=makecommerce_country_picker]:checked');

		if (makeCommerceCountryPicker) {
			const selectedPickerCountry = makeCommerceCountryPicker.value;
			document.querySelectorAll('.makecommerce_country_picker_methods').forEach(item => {
				if (`makecommerce_country_picker_methods_${selectedPickerCountry}` !== item.getAttribute('id')) {
					item.closest('.makecommerce-picker-country').style.display = 'none';
				}
			});
		}else {
			const $selectedCountry = document.querySelector('#billing_country');

			if ( $selectedCountry ) {
				const selectedCountry = $selectedCountry.value.toLowerCase();
				console.log(selectedCountry);
				const $makecommerce_payment_methods = document.querySelectorAll('.makecommerce-picker-method');
				$makecommerce_payment_methods.forEach(($pickerMethod) => {
					const $input = $pickerMethod.querySelector('input');
					if ( $input ) {
						const inputValue = $input.value.toLowerCase();
						if ( inputValue.indexOf( `${selectedCountry  }_` ) > -1 || inputValue.indexOf( `card_` ) > -1 ) {
							$pickerMethod.style.display = '';
						} else {
							$pickerMethod.style.display = 'none';
						}
					}
				});
			}
		}
	}



	static validatePaymentMethods(event) {
		const makeCommercePaymentMethodSelect = document.querySelector('#makecommerce');
		if (makeCommercePaymentMethodSelect) {
			if ('' === makeCommercePaymentMethodSelect.value) {
				event.preventDefault();
				event.stopPropagation();

				document.querySelector('#js-checkout-payment-methods').classList.add('is-invalid');
			} else {
				document.querySelector('#js-checkout-payment-methods').classList.remove('is-invalid');
			}
		} else {
			if ( document.querySelectorAll('input[name=PRESELECTED_METHOD_makecommerce]').length ){
				if ( document.querySelectorAll('input[name=PRESELECTED_METHOD_makecommerce]:checked').length ) {
					document.querySelector('#js-checkout-payment-methods').classList.remove('is-invalid');
				}else {
					event.preventDefault();
					event.stopPropagation();

					document.querySelector('#js-checkout-payment-methods').classList.add('is-invalid');
				}
			}
		}
	}
}