class LangToggle {
  constructor(container) {
    this.langContainer = container;
    this.langButton = this.langContainer.querySelector('[lang-button]');
    this.langOthers = this.langContainer.querySelector('[lang-others]');

    this.langToggleCondition = false;

    if (this.langButton && this.langOthers) {
      this.eventListeners();
    }
  }

  eventListeners() {
    this.langButton.addEventListener('click', (e) => this.statehandler(e));
    document.addEventListener('click', (e) => this.missClick(e));
  }

  statehandler(e) {
    if (this.langToggleCondition === false) {
      this.openLang(e);
    } else {
      this.closeLang(e);
    }
  }

  missClick = (e) => {
    const target = this.langContainer;

    if (target !== null) {
      const isClickInsideTarget = target.contains(event.target);
      if (!isClickInsideTarget) {
        this.closeLang();
      }
    }
  };

  openLang(e) {
    this.langToggleCondition = true;
    this.langContainer.classList.add('active');
  }

  closeLang(e) {
    this.langToggleCondition = false;
    this.langContainer.classList.remove('active');
  }
}

const langContainer = document.querySelectorAll('[lang-container]');

if (langContainer.length) {
  langContainer.forEach((e) => {
    new LangToggle(e);
  });
}
