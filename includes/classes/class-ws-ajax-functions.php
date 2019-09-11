<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * WS_Ajax_Functions class
 */
class WS_Ajax_Functions {
	/**
	 * WS_Ajax_Functions constructor.
	 */
	public function __construct() {
		$this->init_event_listeners();
		$this->init_nopriv_event_listeners();
	}
	/**
	 * Initialize callbacks for the AJAX requests from the frontend by logged in users.
	 */
	private function init_event_listeners() {
		// add_action('wp_ajax_example_action', [$this, 'example_function']);
	}
	/**
	 * Initialize callbacks for the AJAX requests from the frontend by logged out users.
	 */
	private function init_nopriv_event_listeners() {
		// add_action('wp_ajax_nopriv_example_action', [$this, 'example_function']);
	}
	/**
	 * Just an example callback for the example AJAX hooks.
	 */
	public function example_function() {
		if ( ! check_ajax_referer( 'my_nonce' ) ) {
			wp_die(); // Die because nonce verification has failed.
		}

		$some_boolean_variable = filter_input( INPUT_GET, 'some_boolean_variable', FILTER_VALIDATE_BOOLEAN );
		if ( $some_boolean_variable ) {
			wp_send_json_success( 'Some boolean variable value is set and has value of 1, true, on or yes' );
		}
		wp_send_json_error( 'No variable set.' );
	}
}
