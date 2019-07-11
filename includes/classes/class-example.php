<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
/**
 * Example class
 */
class Example {
	/**
	 * Example constructor.
	 */
	public function __construct() {
		$this->init_event_listeners();
	}
	/**
	 *   Initialize hooks
	 */
	private function init_event_listeners() {
		add_action( 'example_action', [ $this, 'example_function' ] );
	}
	/**
	 * Just an example function for the example hook callback.
	 */
	public function example_function() {
		// Do something here.
	}
}

new Example();
