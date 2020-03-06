import qs from 'qs'

export const postData = (url = ``, data = {}) => {
  // Default options are marked with *
  return fetch(url, {
    method: 'POST',
    headers: {
      'Content-Type': 'application/x-www-form-urlencoded',
    },
    body: qs.stringify(data, { arrayFormat: 'index' }),
  }).then(response => response.json()) // parses response to JSON
};

/**
 * Adds an event listener that attaches to the document.
 *
 * @param eventType Type of event to listen to.
 * @param target Target element selector.
 * @param callback Callback function for even listener.
 */
export const addGlobalEventListener = (eventType, target, callback) => {
  document.addEventListener(eventType, (event) => {
    if (!event.target.closest(target)) return;
    const currentTarget = event.target.closest(target);

    callback(event, currentTarget);
  });
};

/**
 * Trigger an event on a selected HTML DOM element.
 *
 * @param eventType Type of event to trigger on element.
 * @param targetEl Target element to trigger event on.
 */
export const triggerEvent = function(eventType, targetEl){
  const event = document.createEvent('HTMLEvents');
  event.initEvent(eventType, true, false);
  targetEl.dispatchEvent(event);
};

export const debounce = (callback, wait) => {
  let timeout;
  return (...args) => {
    const context = this;
    clearTimeout(timeout);
    timeout = setTimeout(() => callback.apply(context, args), wait);
  };
};

export class WsLoader {
  static generateLoaderHtml() {
    if ( ! WsLoader.loaderHtml ) {
      const outputHtml = document.createElement('div');
      outputHtml.classList.add('loader-container');
      outputHtml.style.display = 'flex';

      const rollers = document.createElement('div');
      rollers.classList.add('lds-roller');

      for (let i = 0; i < 8; i += 1) {
        rollers.appendChild(document.createElement('div'));
      }

      outputHtml.appendChild(rollers);
      WsLoader.loaderHtml = outputHtml;
    }

    return WsLoader.loaderHtml.cloneNode(true);
  }

  static addLoader( targetElement, loaderId ) {
    let customLoaderId = loaderId;
    if ( typeof customLoaderId === 'undefined' || customLoaderId === null || ! customLoaderId ) {
      customLoaderId = 'ws-default-loader-id';
    }

    let targetedElement = targetElement;
    if ( typeof targetedElement === 'string' ) {
      targetedElement = document.querySelector(targetedElement);
      if ( ! targetedElement ) {
        return;
      }
    }

    if ( targetedElement.querySelector(`[data-loader_id=${  customLoaderId}]` ) ) {
      return;
    }

    const loaderHtml = WsLoader.generateLoaderHtml();
    loaderHtml.setAttribute( 'data-loader_id', customLoaderId );

    targetedElement.insertAdjacentElement( 'afterbegin', loaderHtml );
  }

  static removeLoader( loaderId ) {
    const targetLoader = document.querySelector( `[data-loader_id=${  loaderId  }]` );
    if ( targetLoader ) {
      targetLoader.remove();
    }
  }

  static removeAllLoaders() {
    document.querySelectorAll( 'loader-container' ).forEach( (el) => {
      el.remove();
    });
  }
}


export default null
