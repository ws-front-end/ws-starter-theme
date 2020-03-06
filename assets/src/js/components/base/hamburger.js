class HamburgerToggle {
  constructor(hamburgerToggle) {
    this.hamburgerButton = hamburgerToggle;
    this.navigationContainer = document.getElementById('js-main-menu-container');
    this.pageBody = document.getElementById('js-page-body');

    this.eventListeners();
  }

  eventListeners() {
    this.hamburgerButton.addEventListener('click', el => {
      el.classList.toggle('active');
      this.navigationContainer.classList.toggle('visible');
      this.pageBody.classList.toggle('overflow--disable');
    });
  }
}

const hamburgerToggle = document.getElementById('js-main-menu-toggle');
if (hamburgerToggle) {
  new HamburgerToggle(hamburgerToggle);
}
