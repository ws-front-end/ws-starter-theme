class CF7Disabler {
  constructor() {
    // Start of js
    this.init()
  }

  init() {
    setTimeout(() => {
      document.querySelectorAll('.wpcf7-form input[type="submit"]').forEach(function(el) {
        el.removeAttribute('disabled');
        const container = el.closest('.wpcf7');
        container.addEventListener(
          'wpcf7invalid',
          () => {
            container.querySelectorAll('input[name="your-consent"]').forEach(el2 => {
              if (!el2.checked) {
                container.classList.add('red-checkbox')
              } else {
                container.classList.remove('red-checkbox')
              }
            })
          },
          false
        )
      })
    }, 1000)
  }
}

new CF7Disabler();
