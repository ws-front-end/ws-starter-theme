class IeAlert {
  constructor(ieContainer) {
    this.container = ieContainer;
    this.pageShadow = document.getElementById('js-page-shadow');
    this.closeButton = document.getElementById('js-close-alert');

    this.eventListeners();
  }

  eventListeners() {
    this.closeButton.addEventListener('click', () => {
      this.container.classList.add('js-hide');
      this.pageShadow.classList.add('js-hide-on-ie');
    });
  }
}

const ieContainer = document.getElementById('js-ie-alert');
if (ieContainer) {
  new IeAlert(ieContainer);
}
