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
}

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

export default null
