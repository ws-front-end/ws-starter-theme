/* eslint import/no-unresolved: [2, {ignore: ["jquery"]}] */
import $ from 'jquery'
import { postData } from './helpers'

class Main {
  constructor() {
    this.eventListeners();
    this.fetchExample()
  }

  eventListeners() {
    // https://developer.mozilla.org/en-US/docs/Web/API/EventTarget/addEventListener
    if (document.getElementById('#example') !== null) {
      document.getElementById('#example').addEventListener('click', this.clickEvent)
    }

    document.querySelectorAll('.multipleExample').forEach(e => {
      e.addEventListener('click', this.clickEvent)
    })
  }

  clickEvent(e) {
    const $this = e.currentTarget;
    console.log($this)
  }

  fetchExample() {
    const inputData = {
      action: 'example_action',
      variable: 5,
      array: [1, 2.22, 'example'],
      object: {
        int: 1,
        string: 'example',
        float: 2.22,
      },
    };
    postData(php_object.ajax_url, inputData)
      // JSON-string from `response.json()` call
      .then(res => {
        if (res.success) {
          // Do something with the data
          console.log(res.data)
        } else {
          // PHP Side found an error with the input data
          console.log(res)
        }
      })
      .catch(error => console.error(error))
  }
}
new Main();
