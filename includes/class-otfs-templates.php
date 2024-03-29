<?php
/**
 * OTFS Templates Class (Extends the SP_Templates class)
 *
 * @class       OTFS_Templates
 * @version     1.0.0
 * @package     OTFS/Classes
 * @category    Class
 * @author      SavvasHa
 */

if ( ! class_exists( 'SP_Templates' ) || ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * OTFS_Templates
 */
class OTFS_Templates {

	/**
	 * Constructor for the officials templates class.
	 *
	 * @access public
	 * @return void
	 */
	public function __construct() {
		// Add your custom template values here.
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
				'content'    => array(
					'title'   => esc_attr__( 'Profile', 'sportspress' ),
					'option'  => 'sportspress_officials_show_content',
					'action'  => array( $this, 'sportspress_output_officials_content' ),
					'default' => 'yes',
				),
				'events'     => array(
					'title'   => esc_attr__( 'Events', 'sportspress' ),
					'option'  => 'sportspress_officials_show_events',
					'action'  => array( $this, 'sportspress_output_officials_events' ),
					'default' => 'yes',
				),
				'statistics' => array(
					'title'   => esc_attr__( 'Statistics', 'sportspress' ),
					'option'  => 'sportspress_officials_show_statistics',
					'action'  => array( $this, 'sportspress_output_officials_statistics' ),
					'default' => 'yes',
				),
			),
			apply_filters( 'sportspress_after_officials_template', array() )
		);
	}

	/**
	 * Output otfs Officials Selector template.
	 *
	 * @access public
	 * @return void
	 */
	public function sportspress_output_officials_selector() {
		sp_get_template( 'officials-selector.php', array(), '', OTFS_PLUGIN_DIR . 'templates/' );
	}

	/**
	 * Output otfs Officials Photo template.
	 *
	 * @access public
	 * @return void
	 */
	public function sportspress_output_officials_photo() {
		sp_get_template( 'officials-photo.php', array(), '', OTFS_PLUGIN_DIR . 'templates/' );
	}

	/**
	 * Output otfs Officials Details template.
	 *
	 * @access public
	 * @return void
	 */
	public function sportspress_output_officials_details() {
		sp_get_template( 'officials-details.php', array(), '', OTFS_PLUGIN_DIR . 'templates/' );
	}

	/**
	 * Output otfs Officials Content template.
	 *
	 * @access public
	 * @return void
	 */
	public function sportspress_output_officials_content() {
		sp_get_template( 'officials-content.php', array(), '', OTFS_PLUGIN_DIR . 'templates/' );
	}

	/**
	 * Output otfs Officials Events template.
	 *
	 * @access public
	 * @return void
	 */
	public function sportspress_output_officials_events() {
		sp_get_template( 'officials-events.php', array(), '', OTFS_PLUGIN_DIR . 'templates/' );
	}

	/**
	 * Output otfs Officials Statistics template.
	 *
	 * @access public
	 * @return void
	 */
	public function sportspress_output_officials_statistics() {
		sp_get_template( 'officials-statistics.php', array(), '', OTFS_PLUGIN_DIR . 'templates/' );
	}
}

new OTFS_Templates();
