class HamburgerToggle {
  constructor(container) {
    this.navigationContainer = container;
    this.hamburgerButton = this.navigationContainer.querySelector('[burger-button]');
    this.navigationList = this.navigationContainer.querySelector('[navigation-list]');
    this.hamburgerToggleCondition = false;

    if (this.hamburgerButton && this.navigationList) {
      this.eventListeners();
    }
  }

  eventListeners() {
    this.hamburgerButton.addEventListener('click', e => this.statehandler(e));
    document.addEventListener('click', e => this.missClick(e));
  }

  statehandler(e) {
    if (this.hamburgerToggleCondition === false) {
      this.openBurger(e);
    } else {
      this.closeBurger(e);
    }
  }

  missClick = e => {
    if (!e.target.closest('[navigation-container]')) {
      this.closeBurger(e);
    }
  };

  openBurger(e) {
    this.hamburgerToggleCondition = true;
    this.hamburgerButton.classList.add('active');
    this.navigationList.classList.add('active');
  }

  closeBurger(e) {
    this.hamburgerToggleCondition = false;
    this.hamburgerButton.classList.remove('active');
    this.navigationList.classList.remove('active');
  }
}

const globalNavigationContainer = document.querySelectorAll('[navigation-container]');

if (globalNavigationContainer.length) {
  globalNavigationContainer.forEach(e => {
    new HamburgerToggle(e);
  });
}
