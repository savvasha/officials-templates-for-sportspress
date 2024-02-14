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
add_action( 'sportspress_init', 'otfs_add_officials_templates' );
add_action( 'sportspress_include_post_type_handlers', 'otfs_include_post_type_handlers' );
add_action( 'plugins_loaded', 'otfs_load_officials_class', 99 );

/**
 * Make sure that all plugins are loaded before extend SP_Custom_Post Class.
 */
function otfs_load_officials_class() {
	// Exit if SportsPress is not installed and activated.
	if ( class_exists( 'SP_Custom_Post' ) ) {
		// Load needed class functions.
		include_once( 'includes/class-otfs-officials.php' );
	}	
}

/**
 * Add settings page.
 */
function otfs_add_settings_page( $settings = array() ) {
	$settings[] = include( 'includes/class-otfs-settings-officials.php' );
	return $settings;
}

/**
 * Include required files used on the backend.
 */
function otfs_add_officials_templates() {
	include_once( 'includes/class-otfs-templates.php' );
}

/**
 * Include required files used on the frontend.
 */
if ( ! is_admin() || defined( 'DOING_AJAX' ) ) {
	include_once( 'includes/class-otfs-template-loader.php' );
}

/**
 * Conditonally load classes and functions only needed when viewing the post type.
 */
function otfs_include_post_type_handlers() {
	include_once( 'includes/class-otfs-meta-boxes.php' );
}
