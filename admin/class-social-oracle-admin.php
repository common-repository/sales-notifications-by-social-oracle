<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       #
 * @since      1.0.0
 *
 * @package    Social_Oracle
 * @subpackage Social_Oracle/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Social_Oracle
 * @subpackage Social_Oracle/admin
 * @author     Jaan Koppe <jaankdesign@gmail.com>
 */
class Social_Oracle_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Social Oracle API key.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $API_KEY    The API key for your Social Oracle account.
	 */
	private $API_KEY;
	
	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {
		$this->plugin_name = $plugin_name;
		$this->version = $version;
		$this->API_KEY = get_option('socialoracle_api_key');
		$this->PUBLIC_API_KEY = get_option('socialoracle_public_api_key');
	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {
		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/social-oracle-admin.css', array(), $this->version, 'all' );
		wp_register_style( 'dashicons-socialoracle', plugin_dir_url( __FILE__ ) . 'css/icon-socialoracle.css', array(), $this->version, 'all' );
    	wp_enqueue_style('dashicons-socialoracle');
	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {
		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/social-oracle-admin.js', array( 'jquery' ), $this->version, false );
	}

	public function init() {
		register_setting('socialoracle_options', 'socialoracle_api_key');
	}

	public function add_menu() {
		add_menu_page('Social Oracle Settings', 'Social Oracle', 'manage_options', 'social-oracle', array($this, 'get_admin_menu_page_html'), 'dashicons-socialoracle');
	}
	public function get_admin_menu_page_html() {
		$logo_path = plugin_dir_url( __FILE__ ) . 'images/socialoracle-logo.png';
		include( plugin_dir_path( __FILE__ ) . 'partials/social-oracle-admin-display.php');
	}

	public function notice_html() {
		if ($this->API_KEY != null) {
			return;
		}

		?>
		<div class="notice notice-error is-dismissible">
			<p class="">Social Oracle is not configured! <a href="admin.php?page=social-oracle">Click here</a></p>
		</div>
		<?php
	}
}
