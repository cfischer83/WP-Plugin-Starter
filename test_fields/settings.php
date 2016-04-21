<?php
/*
	Plugin Name: PK Plugins FIELDS Settings
	Description: Setting up your own fields on a settings page
	Author: Cory Fischer
	Version: 1.0.0
*/
class Test_Fields_Plugin {
	public function __construct() {
		// Hook into the admin menu
		add_action( 'admin_menu', array( $this, 'create_plugin_settings_page' ) );
		add_action( 'admin_init', array( $this, 'setup_sections' ) );
		add_action( 'admin_init', array( $this, 'setup_fields' ) );
		// Tracks new sections for whitelist_custom_options_page()
		$this->page_sections = array();
		// Must run after wp's `option_update_filter()`, so priority > 10
		//add_action( 'whitelist_options', array( $this, 'whitelist_custom_options_page' ),11 );
	}

	public function create_plugin_settings_page() {
		// Add the menu item and page
		$page_title = 'My Awesome Settings Page';
		$menu_title = 'Test Fields';
		$capability = 'manage_options';
		$slug = 'pk_test_fields';
		$callback = array( $this, 'plugin_settings_page_content' );
		$icon = 'dashicons-admin-plugins';
		$position = 2;
	
		// change 'test_settings_page' to a top-level wp-admin page such as "index.php" or "options-general.php"
		add_submenu_page( 'test_settings_page', $page_title, $menu_title, $capability, $slug, $callback );
	}

	public function plugin_settings_page_content() { ?>
		<div class="wrap">
			<h2>PK Plugins FIELDS Settings</h2>
			<form method="post" action="options.php">
				<?php
					// 'pk_test_fields' is important. It namespaces your content so it won't conflict with other plugins.
					settings_fields( 'pk_test_fields' );
					do_settings_sections( 'pk_test_fields' );
					submit_button();
				?>
			</form>
		</div> <?php
	}

	public function setup_sections() {
		$this->add_settings_section( 'our_first_section', 'My First Section Title', array( $this, 'section_callback' ), 'pk_test_fields' );
		$this->add_settings_section( 'our_second_section', 'My Second Section Title', array( $this, 'section_callback' ), 'pk_test_fields' );
		$this->add_settings_section( 'our_third_section', 'My Third Section Title', array( $this, 'section_callback' ), 'pk_test_fields' );
	}

	public function section_callback( $arguments ) {
		switch( $arguments['id'] ){
			case 'our_first_section':
				echo 'This is the first description here!';
				break;
			case 'our_second_section':
				echo 'This one is number two';
				break;
			case 'our_third_section':
				echo 'Third time is the charm!';
				break;
		}
	}

	public function setup_fields() {
		add_settings_field( 'our_first_field', 'Field Name', array( $this, 'field_callback' ), 'pk_test_fields', 'our_first_section' );
		register_setting( 'pk_test_fields', 'our_first_field' );
	}

	public function field_callback( $arguments ) {
		echo '<input name="our_first_field" id="our_first_field" type="text" value="' . get_option( 'our_first_field' ) . '" />';
		// Can't save without this. 'test_fields' is the $slug from your create_plugin_settings_page
	}

	// White-lists options on custom pages.
	// Workaround for second issue: http://j.mp/Pk3UCF
	public function whitelist_custom_options_page( $whitelist_options ){
		// Custom options are mapped by section id; Re-map by page slug.
		foreach($this->page_sections as $page => $sections ){
			$whitelist_options[$page] = array();
			foreach( $sections as $section )
				if( !empty( $whitelist_options[$section] ) )
					foreach( $whitelist_options[$section] as $option )
						$whitelist_options[$page][] = $option;
				}
		return $whitelist_options;
	}
	
	// Wrapper for wp's `add_settings_section()` that tracks custom sections
	private function add_settings_section( $id, $title, $cb, $page ){
		add_settings_section( $id, $title, $cb, $page );
		if( $id != $page ){
			if( !isset($this->page_sections[$page]))
				$this->page_sections[$page] = array();
			$this->page_sections[$page][$id] = $id;
		}
	}
}
new Test_Fields_Plugin();
/**/