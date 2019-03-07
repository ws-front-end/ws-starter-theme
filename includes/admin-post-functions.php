<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

/**
 * WS_Ajax_Functions class
 */
class WS_Admin_Post_Functions {
    function __construct() {
        $this->init_event_listeners();
        $this->init_nopriv_event_listeners();
    }

    private function init_event_listeners() {
        // add_action('admin_post_example_action', [$this, 'example_function']);
    }

    private function init_nopriv_event_listeners() {
        // add_action('admin_post_nopriv_example_action', [$this, 'example_function']);
    }

    public function example_function() {
        if (isset($_REQUEST['variable']) && !empty($_REQUEST['variable'])) {
            wp_send_json_success($_REQUEST);
        }
        wp_send_json_error("No variable set.");
    }
}
