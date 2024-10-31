<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              #
 * @since             1.0.0
 * @package           Social_Oracle
 *
 * @wordpress-plugin
 * Plugin Name:       Sales Notifications By Social Oracle
 * Plugin URI:        https://socialoracle.app
 * Description:       The Social Oracle plugin helps you add social proof to your store and build trust with visitors. Drive up sales and conversions with our social proof plugin.
 * Version:           1.0.5
 * Author:            Social Oracle
 * Author URI: 		  https://socialoracle.app
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       social-oracle
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'SOCIAL_ORACLE_VERSION', '1.0.5' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-social-oracle-activator.php
 */
function activate_social_oracle() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-social-oracle-activator.php';
	Social_Oracle_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-social-oracle-deactivator.php
 */
function deactivate_social_oracle() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-social-oracle-deactivator.php';
	Social_Oracle_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_social_oracle' );
register_deactivation_hook( __FILE__, 'deactivate_social_oracle' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-social-oracle.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_social_oracle() {

	$plugin = new Social_Oracle();
	$plugin->run();

}
run_social_oracle();
