<?php
/**
 * OTFS Officials Extra Meta Boxes
 *
 * @author 		savvasha
 * @category 	Admin
 * @package		OTFS OFFICIALS
 * @version		1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * OTFS_Officials_Extra_Meta_Boxes
 */
class OTFS_Officials_Extra_Meta_Boxes {

	/**
	 * Constructor
	 */
	public function __construct() {
		//add_action( 'add_meta_boxes', array( $this, 'remove_meta_boxes' ), 10 );
		add_action( 'add_meta_boxes', array( $this, 'add_meta_boxes' ), 30 );
		add_action( 'sportspress_process_sp_official_meta', array( $this, 'save' ) );
	}

	/**
	 * Add Meta boxes
	 */
	public function add_meta_boxes() {
		add_meta_box( 'sp_detailsdiv', __( 'Details', 'sportspress' ), array( $this, 'details' ), 'sp_official', 'side', 'default' );
	}

	/**
	 * Output the details metabox
	 */
	public static function details( $post ) {
		wp_nonce_field( 'sportspress_save_data', 'sportspress_meta_nonce' );
		$continents = SP()->countries->continents;
		$nationalities       = get_post_meta( $post->ID, 'sp_nationality', true );
		$default_nationality = get_option( 'sportspress_default_nationality', true );
		
		if ( '' == $nationalities ) {
			$nationalities = array();
		}

		if ( empty( $nationalities ) && $default_nationality ) {
			if ( $default_nationality != '' ) {
				$nationalities[] = $default_nationality;
			}
		}

		foreach ( $nationalities as $index => $nationality ) :

			if ( is_string( $nationality ) && 2 == strlen( $nationality ) ) :
				$legacy                  = SP()->countries->legacy;
				$nationality             = strtolower( $nationality );
				$nationality             = sp_array_value( $legacy, $nationality, null );
				$nationalities[ $index ] = $nationality;
			endif;
		endforeach;

		if ( taxonomy_exists( 'sp_duty' ) ) :
			$duties    = get_the_terms( $post->ID, 'sp_duty' );
			$duty_ids = array();
			if ( $duties ) :
				foreach ( $duties as $duty ) :
					$duty_ids[] = $duty->term_id;
				endforeach;
			endif;
		endif;
	?>
		<p><strong><?php esc_attr_e( 'Nationality', 'sportspress' ); ?></strong></p>
		<p><select id="sp_nationality" name="sp_nationality[]" data-placeholder="<?php printf( esc_attr__( 'Select %s', 'sportspress' ), esc_attr__( 'Nationality', 'sportspress' ) ); ?>" class="widefat chosen-select
																							   <?php
																								if ( is_rtl() ) :
																									?>
			 chosen-rtl<?php endif; ?>" multiple="multiple">
			<option value=""></option>
			<?php foreach ( $continents as $continent => $countries ) : ?>
				<optgroup label="<?php echo esc_attr( $continent ); ?>">
					<?php foreach ( $countries as $code => $country ) : ?>
						<option value="<?php echo esc_attr( $code ); ?>" <?php selected( in_array( $code, $nationalities ) ); ?>><?php echo esc_html( $country ); ?></option>
					<?php endforeach; ?>
				</optgroup>
			<?php endforeach; ?>
		</select></p>
		
				<?php if ( taxonomy_exists( 'sp_duty' ) ) { ?>
			<p><strong><?php esc_attr_e( 'Duties', 'sportspress' ); ?></strong></p>
			<p>
			<?php
			$args = array(
				'taxonomy'    => 'sp_duty',
				'name'        => 'tax_input[sp_duty][]',
				'selected'    => $duty_ids,
				'values'      => 'term_id',
				'placeholder' => sprintf( esc_attr__( 'Select %s', 'sportspress' ), esc_attr__( 'Duties', 'sportspress' ) ),
				'class'       => 'widefat',
				'property'    => 'multiple',
				'chosen'      => true,
			);
			sp_dropdown_taxonomies( $args );
			?>
			</p>
		<?php } ?>
		
	<?php
	}

	/**
	 * Save meta boxes data.
	 */
	public static function save( $post_id ) {
		global $wpdb;

		// Nationalities.
		update_post_meta( $post_id, 'sp_nationality', sp_array_value( $_POST, 'sp_nationality', '' ) );
	}
}

new OTFS_Officials_Extra_Meta_Boxes();