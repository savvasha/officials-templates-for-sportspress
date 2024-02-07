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
		$templates = $this->templates();
		$templates = apply_filters( 'sportspress_' . $this->template . '_templates', $templates );

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
						array(
							'title'   => esc_attr__( 'Link', 'sportspress' ),
							'desc'    => esc_attr__( 'Link officials', 'sportspress' ),
							'id'      => 'sportspress_link_officials',
							'default' => 'yes',
							'type'    => 'checkbox',
						),

						array(
							'title'         => esc_attr__( 'Details', 'sportspress' ),
							'desc'          => esc_attr__( 'Name', 'sportspress' ),
							'id'            => 'sportspress_officials_show_name',
							'default'       => 'no',
							'type'          => 'checkbox',
							'checkboxgroup' => 'start',
						),

						array(
							'desc'          => esc_attr__( 'Nationality', 'sportspress' ),
							'id'            => 'sportspress_officials_show_nationality',
							'default'       => 'yes',
							'type'          => 'checkbox',
							'checkboxgroup' => '',
						),

						array(
							'desc'          => esc_attr__( 'Duties', 'sportspress' ),
							'id'            => 'sportspress_officials_show_duties',
							'default'       => 'yes',
							'type'          => 'checkbox',
							'checkboxgroup' => 'end',
						),
						array(
						'title'         => esc_attr__( 'Birthday', 'sportspress' ),
						'desc'          => esc_attr__( 'Display birthday', 'sportspress' ),
						'id'            => 'sportspress_officials_show_birthday',
						'default'       => 'no',
						'type'          => 'checkbox',
						'checkboxgroup' => 'start',
					),

					array(
						'desc'          => esc_attr__( 'Display age', 'sportspress' ),
						'id'            => 'sportspress_officials_show_age',
						'default'       => 'no',
						'type'          => 'checkbox',
						'checkboxgroup' => 'end',
					),

					),
					'officials'
				),
				array(
					array(
						'type' => 'sectionend',
						'id'   => 'officials_options',
					),
				)
			)
		); // End official settings
	}

	/**
	 * Save settings
	 */
	public function save() {
		parent::save();
	}

}

endif;

return new SP_Settings_Officials();
