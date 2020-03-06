class LangMenu {
  constructor() {
    this.eventListeners()
  }

  eventListeners() {
    document.getElementById('lang-dropdown').forEach(e => {
      e.addEventListener('click', this.toggleMenu)
    })
  }

  toggleMenu(event) {
    event.currentTarget.nextElementSibling.classList.toggle('visible')
  }
}

new LangMenu();
