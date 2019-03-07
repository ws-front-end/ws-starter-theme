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

export default null
