<?php

class WS_Acf_Controller {
	public function __construct() {
		$this->init_local_json();
	}
	
	private function init_local_json(){
		add_filter('acf/settings/save_json', [$this, 'local_acf_json_save_point']);
		add_filter('acf/settings/load_json', [$this, 'local_acf_json_load_point']);
	}
	
	public function local_acf_json_save_point( $path ) {
		$path = get_stylesheet_directory() . '/includes/acf-fields-json/';
		
		return $path;
	}
	public function local_acf_json_load_point( $paths ) {
		$paths[] = get_stylesheet_directory() . '/includes/acf-fields-json/';
		
		return $paths;
	}
}