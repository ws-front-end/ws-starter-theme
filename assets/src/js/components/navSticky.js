class NavSticky {
  constructor(observable, options = {}) {
    const observerOptions = {
      ...{
        root: null,
        rootMargin: '0px',
        threshold: [0.25, 0.75],
      },
      ...options,
    }

    const thresholdSets = []
    for (let i = 0; i <= 1.0; i += 0.01) {
      thresholdSets.push(i.toFixed(2))
    }
    observerOptions.threshold = thresholdSets
    this.observer = new IntersectionObserver((entries, observer) => {
      this.observerCallback(entries, observer)
    }, observerOptions)
    observable.classList.add('sticky')
    this.observer.observe(observable)
  }

  observerCallback(entries, observer) {
    entries.forEach(entry => {
      if (entry.isIntersecting) {
        const elem = entry.target
        if (entry.intersectionRatio >= 0.5) {
          elem.classList.remove('sticky')
        } else if (entry.intersectionRatio < 0.5) {
          elem.classList.add('sticky')
        }
      }
    })
  }
}
document.querySelectorAll('header').forEach(el => {
  new NavSticky(el)
})
