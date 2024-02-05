<?php
/**
 * Officials Template Class (Extends the SP_Templates class)
 *
 * @class 		OTFS_Templates
 * @version		1.0
 * @package		OTFS OFFICIALS
 * @category	Class
 * @author 		savvasha
 */
 
if ( ! class_exists( 'SP_Templates' ) || ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class OTFS_Templates {

	/**
	 * Constructor for the officials templates class.
	 *
	 * @access public
	 * @return void
	 */
	public function __construct() {
		// Add your custom template values here
		SP()->templates->officials = array_merge(
										apply_filters(
											'sportspress_before_officials_template',
											array(
												'selector' => array(
													'title'   => esc_attr__( 'Dropdown', 'sportspress' ),
													'option'  => 'sportspress_officials_show_selector',
													'action'  => array( $this, 'sportspress_output_officials_selector' ),
													'default' => 'yes',
												),
												'photo'    => array(
													'title'   => esc_attr__( 'Photo', 'sportspress' ),
													'option'  => 'sportspress_officials_show_photo',
													'action'  => array( $this, 'sportspress_output_officials_photo' ),
													'default' => 'yes',
												),
												'details'  => array(
													'title'   => esc_attr__( 'Details', 'sportspress' ),
													'option'  => 'sportspress_officials_show_details',
													'action'  => array( $this, 'sportspress_output_officials_details' ),
													'default' => 'yes',
												),
												'excerpt'  => array(
													'title'   => esc_attr__( 'Excerpt', 'sportspress' ),
													'option'  => 'sportspress_officials_show_excerpt',
													'action'  => 'sportspress_output_post_excerpt',
													'default' => 'yes',
												),
											)
										),
										array(
											'content' => array(
												'title'   => esc_attr__( 'Profile', 'sportspress' ),
												'option'  => 'sportspress_officials_show_content',
												'action'  => array( $this, 'sportspress_output_officials_content' ),
												'default' => 'yes',
											),
										),
										apply_filters( 'sportspress_after_officials_template', array() )
									);
	}
	
	/**
	 * Output otfs Officials Templates.
	 *
	 * @access public
	 * @return void
	 */
	public function sportspress_output_officials_selector() {
		sp_get_template( 'official-selector.php', array(), '', OTFS_PLUGIN_DIR . 'templates/' );
	}
	public function sportspress_output_officials_photo() {
		sp_get_template( 'official-photo.php', array(), '', OTFS_PLUGIN_DIR . 'templates/' );
	}
	public function sportspress_output_officials_details() {
		sp_get_template( 'official-details.php', array(), '', OTFS_PLUGIN_DIR . 'templates/' );
	}
	public function sportspress_output_officials_content() {
		sp_get_template( 'official-content.php', array(), '', OTFS_PLUGIN_DIR . 'templates/' );
	}
}

new OTFS_Templates();