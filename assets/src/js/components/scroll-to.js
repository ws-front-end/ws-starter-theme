class ScrollTo {
  constructor() {
    // Start of js
    this.eventListeners()
  }

  eventListeners() {
    document.querySelectorAll('.js-scroll-initiator').forEach(e => {
      e.addEventListener('click', el => {
        this.scrollView(el)
      })
    })
  }

  scrollView(e) {
    if (document.getElementById('scroll-here') !== null) {
      e.preventDefault();

      document.getElementById('scroll-here').scrollIntoView({
        behavior: 'smooth',
        block: 'center',
      })
    }
  }
}

new ScrollTo();
