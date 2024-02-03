<?php
/**
 * Officials Template Loader
 *
 * @class 		OTFS_Officials_Template_Loader
 * @version		1.0
 * @package		OTFS OFFICIALS
 * @category	Class
 * @author 		savvasha
 */
class OTFS_Officials_Template_Loader {

	/**
	 * Constructor
	 */
	public function __construct() {
		add_filter( 'the_content', array( $this, 'officials_content' ) );
	}

	public function add_content( $content, $type, $position = 10, $caption = null ) {
		if ( ! defined( 'ABSPATH' ) ) {
			exit; // Exit if accessed directly
		}
		if ( ! in_the_loop() ) {
			return; // Return if not in main loop
		}

		// Return password form if required
		if ( post_password_required() ) {
			echo get_the_password_form(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			return;
		}

		// Prepend caption to content if given
		if ( $content ) {
			if ( $caption ) {
				$content = '<h3 class="sp-post-caption">' . $caption . '</h3>' . $content;
			}

			$content = '<div class="sp-post-content">' . $content . '</div>';
		}

		// Get layout setting
		$layout = (array) get_option( 'sportspress_' . $type . '_template_order', array() );

		// Get templates
		$templates = SP()->templates->$type;

		// Combine layout setting with available templates
		$templates = array_merge( array_flip( $layout ), $templates );

		$templates = apply_filters( 'sportspress_' . $type . '_templates', $templates );

		// Split templates into sections and tabs
		$slice = array_search( 'tabs', array_keys( $templates ) );
		if ( $slice ) {
			$section_templates = array_slice( $templates, 0, $slice );
			$tab_templates     = array_slice( $templates, $slice );
		} else {
			$section_templates = $templates;
			$tab_templates     = array();
		}

		ob_start();

		// Before template hook
		do_action( 'sportspress_before_single_' . $type );

		// Loop through sections
		if ( ! empty( $section_templates ) ) {
			foreach ( $section_templates as $key => $template ) {
				// Ignore templates that are unavailable or that have been turned off
				if ( ! is_array( $template ) ) {
					continue;
				}
				if ( ! isset( $template['option'] ) ) {
					continue;
				}
				if ( 'yes' !== get_option( $template['option'], sp_array_value( $template, 'default', 'yes' ) ) ) {
					continue;
				}

				// Render the template
				echo '<div class="sp-section-content sp-section-content-' . esc_attr( $key ) . '">';
				if ( 'content' === $key ) {
					echo $content; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
					// Template content hook
					do_action( 'sportspress_single_' . $type . '_content' );
				} else {
					call_user_func( $template['action'] );
				}
				echo '</div>';
			}
		}

		// After template hook
		do_action( 'sportspress_after_single_' . $type );

		$ob = ob_get_clean();

		$tabs = '';

		if ( ! empty( $tab_templates ) ) {
			$i           = 0;
			$tab_content = '';

			foreach ( $tab_templates as $key => $template ) {
				// Ignore templates that are unavailable or that have been turned off
				if ( ! is_array( $template ) ) {
					continue;
				}
				if ( ! isset( $template['option'] ) ) {
					continue;
				}
				if ( 'yes' !== get_option( $template['option'], sp_array_value( $template, 'default', 'yes' ) ) ) {
					continue;
				}

				// Put tab content into buffer
				ob_start();
				if ( 'content' === $key ) {
					echo $content; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				} else {
					call_user_func( $template['action'] );
				}
				$buffer = ob_get_clean();

				// Trim whitespace from buffer
				$buffer = trim( $buffer );

				// Continue if tab content is empty
				if ( empty( $buffer ) ) {
					continue;
				}

				// Get template label
				$label = sp_array_value( $template, 'label', $template['title'] );

				// Add to tabs
				$tabs .= '<li class="sp-tab-menu-item' . ( 0 === $i ? ' sp-tab-menu-item-active' : '' ) . '"><a href="#sp-tab-content-' . $key . '" data-sp-tab="' . $key . '">' . apply_filters( 'gettext', $label, $label, 'sportspress' ) . '</a></li>';

				// Render the template
				$tab_content .= '<div class="sp-tab-content sp-tab-content-' . $key . '" id="sp-tab-content-' . $key . '"' . ( 0 === $i ? ' style="display: block;"' : '' ) . '>' . $buffer . '</div>';

				$i++;
			}

			$ob .= '<div class="sp-tab-group">';

			if ( ! empty( $tabs ) ) {
				$ob .= '<ul class="sp-tab-menu">' . $tabs . '</ul>';
			}

			$ob .= $tab_content;

			$ob .= '</div>';
		}

		return $ob;
	}

	public function officials_content( $content ) {
		if ( is_singular( 'sp_official' ) )
			$sp_template_class = new SP_Template_Loader();
			$content = $sp_template_class->add_content( $content, 'officials', apply_filters( 'sportspress_official_content_priority', 10 ) );
		return $content;
	}
}

new OTFS_Officials_Template_Loader();
			