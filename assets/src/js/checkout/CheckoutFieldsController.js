import Choices from 'choices.js';
import {addGlobalEventListener} from '../components/helpers';

export default class CheckoutFieldsController {
	constructor() {
		const $billingInfoContainer = document.querySelector('#js-checkout-billing-info');
		if ( $billingInfoContainer ) {
			this.initCountrySelect();
			this.initListeners();

			CheckoutFieldsController.toggleBusinessFields(document.querySelector('#ws_is_business_client'));
		}
	}

	static updateCheckout() {
		const $orderSubmitBtn = document.querySelector('#place_order');
		if ( $orderSubmitBtn ) {
			$orderSubmitBtn.setAttribute('disabled', 'disabled');
		}
	}

	static updatedCheckout() {
		const selectedShippingMethod = document.querySelector('input.shipping_method:checked');
		if (selectedShippingMethod) {
			const selectedShippingMethodId = selectedShippingMethod.value;

			if (selectedShippingMethodId.indexOf('parcel') >= 0) {
				CheckoutFieldsController.hideNonParcelMachineFields();
			} else {
				CheckoutFieldsController.showNonParcelMachineFields();
			}
		}
		const $orderSubmitBtn = document.querySelector('#place_order');
		if ( $orderSubmitBtn ) {
			$orderSubmitBtn.removeAttribute('disabled');
		}
		CheckoutFieldsController.toggleShippingFields();
	}

	static hideNonParcelMachineFields() {
		document.querySelectorAll('.js-billing-fields-wrapper .js-hide-for-parcel-machines').forEach((input) => {
			const inputEl = input;
			inputEl.setAttribute('hidden', 'hidden');
			inputEl.querySelector('input').removeAttribute('required');
		});

		document.querySelectorAll('.js-shipping-fields-wrapper .js-hide-for-parcel-machines').forEach((input) => {
			const inputEl = input;
			inputEl.setAttribute('hidden', 'hidden');
			inputEl.querySelector('input').removeAttribute('required');
		});

	}

	static showNonParcelMachineFields() {
		document.querySelectorAll('.js-billing-fields-wrapper .js-hide-for-parcel-machines').forEach((input) => {
			const inputEl = input;
			inputEl.querySelector('input').setAttribute('required', 'required');
			inputEl.removeAttribute('hidden');
		});

		document.querySelectorAll('.js-shipping-fields-wrapper .js-hide-for-parcel-machines').forEach((input) => {
			const inputEl = input;
			inputEl.removeAttribute('hidden');
			inputEl.querySelector('input').setAttribute('required', 'required');
		});
	}

	initListeners() {
		jQuery(document.body).on('update_checkout', () => CheckoutFieldsController.updateCheckout());
		jQuery(document.body).on('updated_checkout', () => CheckoutFieldsController.updatedCheckout());

		addGlobalEventListener('change', '#ws_is_business_client',  (event, currentTarget) => CheckoutFieldsController.toggleBusinessFields(currentTarget) );
		addGlobalEventListener( 'click', '#place_order', CheckoutFieldsController.validateFields );
		addGlobalEventListener( 'click', '#place_order', CheckoutFieldsController.validatePolicies );
		addGlobalEventListener( 'click', '.js-shipping-fields-toggle', CheckoutFieldsController.toggleShippingFields );
		addGlobalEventListener('input', '.js-validate-field', CheckoutFieldsController.validateField);
		addGlobalEventListener('change', '.js-policies-checkbox', CheckoutFieldsController.validatePolicies);
	}

	static toggleShippingFields() {
		const $shippingFieldsToggle = document.querySelector('.js-shipping-fields-toggle');
		if ( $shippingFieldsToggle ) {
			const $shippingFieldsWrapper = document.querySelector('.js-shipping-fields-wrapper');
			if ($shippingFieldsWrapper) {
				if ($shippingFieldsToggle.checked) {
					$shippingFieldsWrapper.classList.add('shipping-fields-visible');
					$shippingFieldsWrapper.classList.remove('shipping-fields-hidden');
					$shippingFieldsWrapper.querySelectorAll('input').forEach((input) => {
						input.setAttribute('required', 'required');
					});

				} else {
					$shippingFieldsWrapper.classList.remove('shipping-fields-visible');
					$shippingFieldsWrapper.classList.add('shipping-fields-hidden');
					$shippingFieldsWrapper.querySelectorAll('input').forEach((input) => {
						input.removeAttribute('required');
					});
				}
			}
		}
	}

	initCountrySelect() {
		document.querySelector('#js-checkout-billing-info').querySelectorAll('select').forEach((select) => {
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
			new Choices(select, choicesArgs);
			select.addEventListener('choice', (event) => {
				event.target.closest('div.choices').classList.add('choices--selected');
			}, false);
		});
	}

	static showBusinessFields() {
		document.querySelectorAll('.js-show-for-business-only').forEach((input) => {
			const inputEl = input;
			inputEl.removeAttribute('hidden');
			inputEl.querySelector('input').setAttribute('required', 'required');
		});
	}

	static hideBusinessFields() {
		document.querySelectorAll('.js-show-for-business-only').forEach((input) => {
			const inputEl = input;
			inputEl.setAttribute('hidden', 'hidden');
			inputEl.querySelector('input').removeAttribute('required');
		});
	}

	static toggleBusinessFields(currentTarget) {
		if (currentTarget) {
			if (currentTarget.checked) {
				CheckoutFieldsController.showBusinessFields();
			} else {
				CheckoutFieldsController.hideBusinessFields();
			}
		}
	}

	static validatePolicies( event ) {
		const $termCheckbox = document.querySelector('#terms');
		const $privacyCheckbox = document.querySelector('#privacy-policy');

		let isInvalid = false;
		if ( $termCheckbox ) {
			if ( ! $termCheckbox.checked ) {
				$termCheckbox.classList.add('checkbox-invalid');
				isInvalid = true;
			} else {
				$termCheckbox.classList.remove('checkbox-invalid');
			}
		}
		if ( $privacyCheckbox ) {
			if ( ! $privacyCheckbox.checked ) {
				$privacyCheckbox.classList.add('checkbox-invalid');
				isInvalid = true;
			} else {
				$privacyCheckbox.classList.remove('checkbox-invalid');
			}
		}

		if ( isInvalid ) {
			event.preventDefault();
			event.stopPropagation();
		}
	}

	static validateFields(event) {
		const $checkoutForm = document.querySelector('form.checkout');
		if ( $checkoutForm ) {
			$checkoutForm.querySelectorAll('input').forEach((el) => {
				if ( el.hasAttribute('required') ) {
					const isWhitespaces = ( !el.value.replace(/\s/g, '').length );
					if (!el.checkValidity() || isWhitespaces) {
						event.preventDefault();
						event.stopPropagation();

						console.log('Invalid field: ', el);
						el.classList.add('is-invalid');
					}
				}
			});

			if (document.getElementsByClassName('is-invalid')) {
				CheckoutFieldsController.scrollTo(document.getElementsByClassName('is-invalid')[0]);
			}
		}
	}

	static scrollTo(element) {
		let top = element.getBoundingClientRect();
		let header = document.getElementsByClassName('site-header')[0].getBoundingClientRect();

		if(window.navigator.userAgent.indexOf("Edge") > -1){
			window.scroll(0, top.top + window.pageYOffset - header.height - 5);
		} else {
			window.scroll({
				behavior: 'smooth',
				left: 0,
				top: top.top + window.pageYOffset - header.height - 5
			});
		}
	}

	static validateField(event, currentTarget) {
		currentTarget.classList.remove('is-invalid');
	}

}