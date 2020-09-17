export default class CartFragmentsRenderer {
  static renderFragments (fragments) {
    const fragmentKeys = Object.keys(fragments)
    if (fragmentKeys.length) {
      fragmentKeys.forEach(fragmentKey => {
        if (fragmentKey in fragments) {
          const fragment = fragments[fragmentKey]
          if (typeof fragment === 'string') {
            const targetElement = document.querySelector(fragmentKey)
            if (targetElement) {
              targetElement.outerHTML = fragment
            }
          }
          else {
            CartFragmentsRenderer.renderFragments(fragment)
          }
        }
      })
    }
  }
}
