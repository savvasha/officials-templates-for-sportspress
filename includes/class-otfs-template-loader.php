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

	public function add_content( $content, $template, $position = 10 ) {
		if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
		if ( ! in_the_loop() ) return; // Return if not in main loop

		$content = '<div class="sp-post-content">' . $content . '</div>';

		ob_start();

		if ( $position <= 0 )
			echo $content;

		do_action( 'sportspress_before_single_' . $template );

		if ( post_password_required() ) {
			echo get_the_password_form();
			return;
		}

		if ( $position > 0 && $position <= 5 )
			echo $content;

		do_action( 'sportspress_single_' . $template . '_content' );

		if ( $position > 5 && $position <= 10 )
			echo $content;

		do_action( 'sportspress_after_single_' . $template );

		if ( $position > 10 )
			echo $content;

		return ob_get_clean();
	}

	public function officials_content( $content ) {
		if ( is_singular( 'sp_official' ) )
			$content = self::add_content( $content, 'officials', apply_filters( 'sportspress_officials_content_priority', 10 ) );
		return $content;
	}
}

new OTFS_Officials_Template_Loader();
			