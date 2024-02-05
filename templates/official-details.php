<?php
/**
 * Official Details
 *
 * @author      savvasha
 * @package     OTFS/Templates
 * @version     1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
if ( get_option( 'sportspress_officials_show_details', 'yes' ) === 'no' ) {
	return;
}

if ( ! isset( $id ) ) {
	$id = get_the_ID();
}

$defaults = array(
	'show_nationality'       => get_option( 'sportspress_officials_show_nationality', 'yes' ) == 'yes' ? true : false,
	'show_nationality_flags' => get_option( 'sportspress_officials_show_flags', 'yes' ) == 'yes' ? true : false,
);

extract( $defaults, EXTR_SKIP );

$countries = SP()->countries->countries;

//$official = new SP_Official( $id );

//$nationalities = $official->nationalities();
$nationalities = null;

$data = array();
if ( $show_nationality && $nationalities && is_array( $nationalities ) ) :
	$values = array();
	foreach ( $nationalities as $nationality ) :
		if ( 2 == strlen( $nationality ) ) :
			$legacy      = SP()->countries->legacy;
			$nationality = strtolower( $nationality );
			$nationality = sp_array_value( $legacy, $nationality, null );
		endif;
		$country_name = sp_array_value( $countries, $nationality, null );
		$values[]     = $country_name ? ( $show_nationality_flags ? sp_flags( $nationality ) : '' ) . $country_name : '&mdash;';
	endforeach;
	$data[ esc_attr__( 'Nationality', 'sportspress' ) ] = implode( '<br>', $values );
endif;

$data = apply_filters( 'sportspress_officials_details', $data, $id );

if ( empty( $data ) ) {
	return;
}

$output = '<div class="sp-list-wrapper">' .
	'<dl class="sp-official-details">';

foreach ( $data as $label => $value ) :

	$output .= '<dt>' . $label . '</dt><dd>' . $value . '</dd>';

endforeach;

$output .= '</dl></div>';
?>
<div class="sp-template sp-template-official-details sp-template-details">
	<?php echo wp_kses_post( $output ); ?>
</div>
