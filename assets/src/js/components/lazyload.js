/**
 * Image LazyLoader class for images with certain class.
 *
 * @package ws-becky
 */

import LazyLoad from "vanilla-lazyload";

const lazyLoadInstance = new LazyLoad(
	{
		elements_selector: ".js-lazyload-image"
	}
);
window.WS_LazyLoad_Instance = lazyLoadInstance;

export default lazyLoadInstance;
