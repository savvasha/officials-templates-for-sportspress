<?php
/**
 * Plugin Name: Officials Templates for SportsPress
 * Description: Add templates to Officials pages
 * Version: 1.0.0
 * Author: Savvas
 * Author URI: https://savvasha.com
 * Requires at least: 5.3
 * Requires PHP: 7.0
 * License: GPL v2 or later
 * License URI: https://www.gnu.org/licenses/gpl.html
 *
 * @package officials-templates-for-sportspress
 * @category Core
 * @author savvasha
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Define constants.
if ( ! defined( 'OTFS_PLUGIN_BASE' ) ) {
	define( 'OTFS_PLUGIN_BASE', plugin_basename( __FILE__ ) );
}
if ( ! defined( 'OTFS_PLUGIN_DIR' ) ) {
	define( 'OTFS_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
}

if ( ! defined( 'OTFS_PLUGIN_URL' ) ) {
	define( 'OTFS_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
}

// Hooks.
add_filter( 'sportspress_get_settings_pages', 'otfs_add_settings_page' );

/**
 * Add settings page
 */
function otfs_add_settings_page( $settings = array() ) {
	$settings[] = include( 'includes/class-otfs-settings-officials.php' );
	return $settings;
}

/**
 * Include required files used on the frontend.
 */
if ( ! is_admin() || defined( 'DOING_AJAX' ) ) {
	include_once( 'includes/class-otfs-template-loader' );
}
