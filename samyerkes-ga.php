<?php
/**
 * @package Sam Yerkes Google Analytics
 * @version 1.0
 */
/*
Plugin Name: Sam Yerkes Google Analytics
Plugin URI: https://samyerkes.com
Description: A simple plugin to add a google analytics script to the theme
Version: 1.0
Author URI: https://samyerkes.com
*/

class SamYerkesGA {

	public function __construct() {
		add_action( 'admin_menu', array( $this, 'create_plugin_settings_page' ) );
		add_action( 'admin_init', array( $this, 'setup_sections' ) );
		add_action( 'admin_init', array( $this, 'setup_fields' ) );

		add_action( 'wp_footer', array( $this, 'samyerkes_ga_build_script'), 10 );	
	}

	public function create_plugin_settings_page() {
		// Add the menu item and page
		$page_title = 'Sam Yerkes GA';
		$menu_title = 'Sam Yerkes GA';
		$capability = 'manage_options';
		$slug = 'samyerkes_ga';
		$callback = array( $this, 'plugin_settings_page_content' );
		$icon = 'dashicons-admin-plugins';
		$position = 100;

		add_submenu_page( 'options-general.php', $page_title, $menu_title, $capability, $slug, $callback );
	}

	public function plugin_settings_page_content() { ?>
		<div class="wrap">
			<h2>Sam Yerkes GA</h2>
			<form method="post" action="options.php">
	            <?php
	                settings_fields('samyerkes_ga_fields' );
	                do_settings_sections( 'samyerkes_ga_fields' );
	                submit_button();
	            ?>
			</form>
		</div> <?php
	}

	public function setup_sections() {
		add_settings_section( 'setting_section', 'Settings', false, 'samyerkes_ga_fields' );
	}

	public function setup_fields() {
	    add_settings_field( 'google_analytics_id', 'Google Analytics ID', array( $this, 'field_callback' ), 'samyerkes_ga_fields', 'setting_section' );
	    register_setting( 'samyerkes_ga_fields', 'google_analytics_id' );
	}

	public function field_callback( $arguments ) {
		echo '<input name="google_analytics_id" id="google_analytics_id" type="text" placeholder="UA-XXXXXXXXXX-1" value="' . get_option( 'google_analytics_id' ) . '" />';
	}

	public function samyerkes_ga_build_script() {
		$id = get_option('google_analytics_id');
		$format = '<script async src="https://www.googletagmanager.com/gtag/js?id=%s"></script>
<script>
	window.dataLayer = window.dataLayer || [];
	function gtag() { dataLayer.push(arguments); }
	gtag("js", new Date());
	gtag("config", "%s");
</script>

';
		printf($format, $id, $id);
	}

}

new SamYerkesGA();