<?php
/**
 * SportsPress Officials Settings
 *
 * @author 		SavvasHa
 * @category 	Admin
 * @package 	SportsPress Officials
 * @version     1.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! class_exists( 'SP_Settings_Officials' ) ) :

/**
 * SP_Settings_Officials
 */
class SP_Settings_Officials extends SP_Settings_Page {

	/**
	 * Constructor
	 */
	public function __construct() {
		$this->id    = 'officials';
		$this->label = __( 'Officials', 'sportspress' );
		$this->template = 'officials';

		add_filter( 'sportspress_settings_tabs_array', array( $this, 'add_settings_page' ), 20 );
		add_action( 'sportspress_settings_' . $this->id, array( $this, 'output' ) );
		add_action( 'sportspress_admin_field_officials_layout', array( $this, 'layout_setting' ) );
		add_action( 'sportspress_admin_field_officials_tabs', array( $this, 'tabs_setting' ) );
		add_action( 'sportspress_settings_save_' . $this->id, array( $this, 'save' ) );
	}

	/**
	 * Get settings array
	 *
	 * @return array
	 */
	public function get_settings() {

		return apply_filters(
			'otfs_officials_settings',
			array_merge(
				array(
					array(
						'title' => esc_attr__( 'Officials Options', 'sportspress' ),
						'type'  => 'title',
						'desc'  => '',
						'id'    => 'officials_options',
					),
				),
				apply_filters(
					'sportspress_post_type_options',
					array(
						array( 'type' => 'officials_layout' ),

						array( 'type' => 'officials_tabs' ),
					),
					'official'
				),
				array(
					array(
						'type' => 'sectionend',
						'id'   => 'officials_options',
					),
				)
			)
		); // End staff settings
	}

	/**
	 * Save settings
	 */
	public function save() {
		$settings = $this->get_settings();
		SP_Admin_Settings::save_fields( $settings );
	}

}

endif;

return new SP_Settings_Officials();
