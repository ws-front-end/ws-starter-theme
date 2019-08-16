<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * WS_Ajax_Functions class
 */
class WS_Admin_Post_Functions {
	/**
	 * WS_Admin_Post_Functions constructor.
	 */
	public function __construct() {
		$this->init_event_listeners();
		$this->init_nopriv_event_listeners();
	}
	/**
	 * Initialize hooks for frontend post requests for logged in users.
	 */
	private function init_event_listeners() {
        // add_action('admin_post_example_action', [$this, 'example_function']); just an example!
	}
	/**
	 * Initialize hooks for frontend post requests for logged out users.
	 */
	private function init_nopriv_event_listeners() {
        // add_action('admin_post_nopriv_example_action', [$this, 'example_function']);
	}
	/**
	 * Just an example callback for an event listener above
	 * Highly suggested to always do nonce verification!
	 */
	public function example_function() {
		if ( ! isset( $_REQUEST['_wpnonce'] ) || ! wp_verify_nonce( sanitize_key( $_REQUEST['_wpnonce'] ), 'example_function' ) ) {
			die( 'Failed security check' );
		}

		$some_variable = filter_input( INPUT_POST, 'some_variable_name', FILTER_VALIDATE_INT );
		if ( ! $some_variable ) {
			wp_send_json_success( 'POST value doesn\'t exist or isn\'t an integer.' );
		}
		wp_send_json_error( 'No variable set.' );
	}
}
