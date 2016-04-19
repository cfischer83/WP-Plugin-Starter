<?php
/*
	Plugin Name: PK Settings Page Plugin
	Description: Setting up your own top-level settings page for your plugin.
	Author: Cory Fischer
	Version: 1.0.0
*/
class Settings_Page_Plugin {
	public function __construct() {
		// Hook into the admin menu
		add_action( 'admin_menu', array( $this, 'create_plugin_settings_page' ) );
	}

	public function create_plugin_settings_page() {
		// Add the menu item and page
		$page_title = 'My Awesome Settings Page';
		$menu_title = 'Awesome Plugin';
		$capability = 'manage_options';
		$slug = 'test_settings_page';
		$callback = array( $this, 'plugin_settings_page_content' );
		$icon = 'dashicons-admin-plugins';
		$position = 2;
	
		add_menu_page( $page_title, $menu_title, $capability, $slug, $callback, $icon, $position );
	}

	public function plugin_settings_page_content() {
		echo 'Hello World!';
	}
}
new Settings_Page_Plugin();
